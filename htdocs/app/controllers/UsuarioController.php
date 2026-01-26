<?php

require_once __DIR__ . '/../models/Usuario.php';


class UsuarioController{
    // Redirigir a una ruta de la app y cortar ejecución
    private function redirect($path){
    $path = trim((string)$path);

    if ($path === '/' || $path === '') {
        header("Location: " . BASE_URL . "/index.php");
        exit;
    }

    $path = ltrim($path, '/');
    header("Location: " . BASE_URL . "/index.php?url=" . $path);
    exit;
}



    private function ensureSession(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Mostrar formulario de registro de usuario
    public function registro(){
        require_once __DIR__ . '/../views/usuarios/registro.php';
    }

    // Procesar registro creando usuario y redirigir al login si todo va bien(spoiler: no xd)
    public function guardarRegistro(){
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        require_once __DIR__ . '/../views/usuarios/registro.php';
        return;
    }

    $dni = strtoupper(trim($_POST['dni'] ?? ''));
    $nombre = trim($_POST['nombre'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($dni === '' || $nombre === '' || $email === '' || $password === '') {
        $error = "Rellena todos los campos.";
        require_once __DIR__ . '/../views/usuarios/registro.php';
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email no válido.";
        require_once __DIR__ . '/../views/usuarios/registro.php';
        return;
    }

    if (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
        require_once __DIR__ . '/../views/usuarios/registro.php';
        return;
    }

    $ok = Usuario::registrar($dni, $nombre, $email, $password);

    if (!$ok) {
        $error = "DNI o email no válidos (o ya existen). Revisa los datos.";
        require_once __DIR__ . '/../views/usuarios/registro.php';
        return;
    }

    $this->redirect("/usuario/login");
}


    // Mostrar formulario de login para iniciar sesión
    public function login(){
        require_once __DIR__ . '/../views/usuarios/login.php';
    }

    // Validar credenciales, comprobar cuenta activa y crear sesión del usuario
    public function validarLogin(){
       	$this->ensureSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../views/usuarios/login.php';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email no válido";
            require_once __DIR__ . '/../views/usuarios/login.php';
            return;
        }

        $usuario = Usuario::buscarPorEmail($email);

        if ($usuario && password_verify($password, $usuario['password'])) {
            if (isset($usuario['activo']) && (int) $usuario['activo'] === 0) {
                $error = "Tu cuenta está desactivada";
                require_once __DIR__ . '/../views/usuarios/login.php';
                return;
            }

            $_SESSION['usuario'] = $usuario;
            $this->redirect("/");
        }

        $error = "Email o contraseña incorrectos";
        require_once __DIR__ . '/../views/usuarios/login.php';
    }

    // Cerrar sesión del usuario borrando datos y destruyendo la sesión
    public function logout(){
        $this->ensureSession();

        $_SESSION = [];
        session_destroy();

        $this->redirect("/");
    }

    // Mostrar perfil del usuario con sus datos actuales
    public function datos(){
    $this->ensureSession();
    Auth::requireLogin();

    $dni = $_SESSION['usuario']['dni'];
    $usuario = Usuario::obtenerPorDni($dni);

    require_once __DIR__ . '/../views/usuarios/datos.php';
}


    // Actualizar datos del perfil y opcionalmente cambiar contraseña
    public function actualizar(){
    $this->ensureSession();
    Auth::requireLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect("/usuario/datos");
    }

    $dni = $_SESSION['usuario']['dni'];

    $passwordActual = $_POST['password_actual'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $passwordNueva = $_POST['password'] ?? '';

    $usuarioBD = Usuario::obtenerPorDni($dni);

    if (!$usuarioBD || !password_verify($passwordActual, $usuarioBD['password'])) {
        $usuario = $usuarioBD;
        $mensaje = null;
        $error = "La contraseña actual es incorrecta";
        require_once __DIR__ . '/../views/usuarios/datos.php';
        return;
    }

    // Actualizar nombre y email
    Usuario::actualizarDatos($dni, $nombre, $email);

    // Si hay nueva contraseña, actualizarla y si no pues no 
    if (!empty($passwordNueva)) {
        Usuario::actualizarPassword($dni, $passwordNueva);
    }

    // Refrescar sesión
    $_SESSION['usuario'] = Usuario::obtenerPorDni($dni);
    $usuario = $_SESSION['usuario'];

    $mensaje = "Datos actualizados correctamente";
    require_once __DIR__ . '/../views/usuarios/datos.php';
}
//No funciona
public function recuperar(){
    require_once __DIR__ . '/../views/usuarios/recuperar.php';
}

public function procesarRecuperacion(){
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: " . BASE_URL . "/usuario/recuperar");
        exit;
    }

    $email = trim((string)($_POST['email'] ?? ''));

    $res = Usuario::solicitarRecuperacion($email);
    $mensaje = $res['msg'] ?? 'Si el correo existe, recibirás instrucciones.';
    $debugLink = $res['link'] ?? null;

    require_once __DIR__ . '/../views/usuarios/recuperar.php';
}

public function resetPassword(){
    $token = $_GET['token'] ?? '';
    require_once __DIR__ . '/../views/usuarios/nueva_password.php';
}

public function guardarNuevaPassword(){
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: " . BASE_URL . "/usuario/recuperar");
        exit;
    }

    $token = (string)($_POST['token'] ?? '');
    $pass  = (string)($_POST['password'] ?? '');

    $ok = Usuario::guardarNuevaPassword($token, $pass);

    $mensaje = $ok ? "Contraseña actualizada correctamente." : "Token inválido o caducado.";
    require_once __DIR__ . '/../views/usuarios/login.php';
}

   
}

?>