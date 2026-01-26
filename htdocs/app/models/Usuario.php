<?php

require_once __DIR__ . '/../config/database.php';

class Usuario
{
    // Registrar un usuario nuevo validando dni y email y guardando la contraseña cifrada
    public static function registrar($dni, $nombre, $email, $password)
{
    $dni = strtoupper(trim((string)$dni));
    $nombre = trim((string)$nombre);
    $email = strtolower(trim((string)$email));
    $password = (string)$password;

    if (!self::validarDni($dni)) return false;
    if (!self::validarEmail($email)) return false;
    if ($nombre === '' || $password === '') return false;

    $db = Database::connect();

    $st = $db->prepare("SELECT 1 FROM usuario WHERE dni = ? OR email = ? LIMIT 1");
    $st->execute([$dni, $email]);
    if ($st->fetchColumn()) return false;

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario
            (dni, nombre, email, password, rol, fecha_registro, activo, reset_token, reset_expira)
            VALUES
            (?, ?, ?, ?, 'usuario', NOW(), 1, NULL, NULL)";

    $stmt = $db->prepare($sql);
    return $stmt->execute([$dni, $nombre, $email, $hash]);
}

    // Validar un DNI español comprobando formato y letra de control
    private static function validarDni($dni)
    {
        $dni = strtoupper(trim((string) $dni));

        if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
            return false;
        }

        $numero = substr($dni, 0, 8);
        $letra = substr($dni, -1);
        $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

        return $letra === $letras[((int) $numero % 23)];
    }

    // Validar email con filtro estándar de PHP
    private static function validarEmail($email)
    {
        $email = trim((string) $email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Buscar usuario por email para login o recuperación
    public static function buscarPorEmail($email)
    {
        $db = Database::connect();

        $email = trim((string) $email);
        if ($email === '') return null;

        $stmt = $db->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->execute([$email]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    // Obtener usuario por dni devolviendo null si no existe
    public static function obtenerPorDni($dni)
    {
        $db = Database::connect();

        $dni = strtoupper(trim((string) $dni));
        if ($dni === '') return null;

        $stmt = $db->prepare("SELECT * FROM usuario WHERE dni = ?");
        $stmt->execute([$dni]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    // Actualizar datos del perfil del usuario manteniendo el dni
    public static function actualizarDatos($dni, $nombre, $email)
    {
        $db = Database::connect();

        $dni = strtoupper(trim((string) $dni));
        $nombre = trim((string) $nombre);
        $email = trim((string) $email);

        if ($dni === '' || $nombre === '' || !self::validarEmail($email)) return false;

        $stmt = $db->prepare("UPDATE usuario SET nombre = ?, email = ? WHERE dni = ?");
        return $stmt->execute([$nombre, $email, $dni]);
    }

    // Actualizar contraseña del usuario cifrándola antes de guardarla
    public static function actualizarPassword($dni, $password)
    {
        $db = Database::connect();

        $dni = strtoupper(trim((string) $dni));
        $password = (string) $password;

        if ($dni === '' || $password === '') return false;

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("UPDATE usuario SET password = ? WHERE dni = ?");
        return $stmt->execute([$hash, $dni]);
    }

    // Obtener listado de usuarios para administración sin contraseñas
    public static function obtenerTodos()
    {
        $db = Database::connect();

        $stmt = $db->query("SELECT dni, nombre, email, rol, activo FROM usuario");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener usuarios paginados para administración
    public static function obtenerPaginados($limite, $offset)
    {
        $db = Database::connect();

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $sql = "SELECT dni, nombre, email, rol, activo FROM usuario LIMIT ? OFFSET ?";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar usuarios totales para paginación en administración
    public static function contarTotal()
    {
        $db = Database::connect();
        return (int) $db->query("SELECT COUNT(*) FROM usuario")->fetchColumn();
    }

    // Cambiar rol de un usuario desde administración
    public static function cambiarRol(string $dni, string $rol): bool
{
    $db = Database::connect();

    $dni = strtoupper(trim($dni));
    $rol = trim($rol);

    if ($dni === '' || !in_array($rol, ['admin', 'usuario', 'empleado'], true)) {
        return false;
    }

    $stmt = $db->prepare("UPDATE usuario SET rol = ? WHERE dni = ?");
    return $stmt->execute([$rol, $dni]);
}


    // Cambiar estado activo de un usuario para permitir o bloquear acceso
    public static function cambiarActivo($dni, $activo)
    {
        $db = Database::connect();

        $dni = strtoupper(trim((string) $dni));
        $activo = (int) $activo;

        if ($dni === '' || !in_array($activo, [0, 1], true)) return false;

        $stmt = $db->prepare("UPDATE usuario SET activo = ? WHERE dni = ?");
        return $stmt->execute([$activo, $dni]);
    }

    // Actualizar datos de usuario desde admin incluyendo rol y estado activo
    public static function actualizarAdmin($dni, $nombre, $email, $rol, $activo)
{
    $db = Database::connect();

    $dni = strtoupper(trim((string) $dni));
    $nombre = trim((string) $nombre);
    $email = trim((string) $email);
    $rol = trim((string) $rol);
    $activo = (int) $activo;

    if (
        $dni === '' ||
        $nombre === '' ||
        !self::validarEmail($email) ||
        !in_array($rol, ['admin', 'usuario', 'empleado'], true) ||   // ✅ aquí
        !in_array($activo, [0, 1], true)
    ) {
        return false;
    }

    $sql = "UPDATE usuario SET nombre = ?, email = ?, rol = ?, activo = ? WHERE dni = ?";
    $stmt = $db->prepare($sql);

    return $stmt->execute([$nombre, $email, $rol, $activo, $dni]);
}


    // Eliminar un usuario por dni desde administración
    public static function desactivar(string $dni): bool
{
    $db = Database::connect();
    $dni = trim($dni);
    if ($dni === '') return false;

    $stmt = $db->prepare("UPDATE usuario SET activo = 0 WHERE dni = ? LIMIT 1");
    return $stmt->execute([$dni]);
}


 
    public static function solicitarRecuperacion(string $email): array
{
    $db = Database::connect();

    $email = trim($email);
    if ($email === '' || !self::validarEmail($email)) {
        return ['ok' => false, 'msg' => 'Email inválido', 'link' => null];
    }

    // ✅ tu tabla NO tiene id, tiene dni
    $stmt = $db->prepare("SELECT dni FROM usuario WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // mensaje genérico por seguridad
    if (!$usuario) {
        return ['ok' => true, 'msg' => 'Si el correo existe, recibirás instrucciones.', 'link' => null];
    }

    $token  = bin2hex(random_bytes(32));
    $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // ✅ actualizar por dni
    $stmt = $db->prepare("UPDATE usuario SET reset_token = ?, reset_expira = ? WHERE dni = ?");
    $stmt->execute([$token, $expira, $usuario['dni']]);

    $link = BASE_URL . "/usuario/resetPassword?token=" . urlencode($token);

    $asunto  = "Recuperar contraseña";
    $mensaje = "Hola,\n\nPara crear una nueva contraseña entra aquí:\n$link\n\nCaduca en 1 hora.\n";
    $headers = "From: no-reply@tusitio.com\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    @mail($email, $asunto, $mensaje, $headers);

    return ['ok' => true, 'msg' => 'Si el correo existe, recibirás instrucciones.', 'link' => $link];
}


public static function guardarNuevaPassword(string $token, string $password): bool
{
    $db = Database::connect();

    $token = trim($token);
    if ($token === '' || strlen($token) < 20) return false;

    $password = (string)$password;
    if (strlen($password) < 6) return false;

    // 1) validar token y caducidad
    $stmt = $db->prepare("SELECT dni FROM usuario WHERE reset_token = ? AND reset_expira > NOW() LIMIT 1");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) return false;

    // 2) actualizar pass + limpiar token
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("UPDATE usuario
                          SET password = ?, reset_token = NULL, reset_expira = NULL
                          WHERE id = ?");
    return $stmt->execute([$hash, (int)$usuario['id']]);
}


   
}
