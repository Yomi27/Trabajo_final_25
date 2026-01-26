<?php

require_once __DIR__ . '/../models/Libro.php';
require_once __DIR__ . '/../models/Autor.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Categoria.php';

class AdminController{
    // Obtener un entero seguro desde GET evitando valores inválidos
    private function getInt($key, $default = 0)
    {
        if (!isset($_GET[$key])) return $default;
        $value = (int) $_GET[$key];
        return ($value < $default) ? $default : $value;
    }

    // Redirigir a una ruta del panel admin y cortar ejecución
    private function redirect($path)
    {
        header("Location: " . BASE_URL . $path);
        exit;
    }

    private function requireStaff(){
        if (empty($_SESSION['usuario'])) {
            $this->redirect('/usuario/login');
        }

        $rol = $_SESSION['usuario']['rol'] ?? '';
        if (!in_array($rol, ['admin', 'empleado'], true)) {
            $this->redirect('/');
        }
    }

    // Mostrar el dashboard principal del panel de administración
    public function index(){
        $this->requireStaff();
        require_once __DIR__ . '/../views/admin/index.php';
    }

    // Listar libros con paginación y buscador
    public function libros(){
        $this->requireStaff();

        $porPagina = 5;
        $pagina = $this->getInt('page', 1);
        $buscar = trim($_GET['buscar'] ?? '');

        $offset = ($pagina - 1) * $porPagina;

        if ($buscar !== '') {
            $total = Libro::contarBusqueda($buscar);
            $totalPaginas = ceil($total / $porPagina);
            $libros = Libro::buscarPaginados($buscar, $porPagina, $offset);
        } else {
            $total = Libro::contarTotal();
            $totalPaginas = ceil($total / $porPagina);
            $libros = Libro::obtenerPaginados($porPagina, $offset);
        }

        if ($totalPaginas < 1) $totalPaginas = 1;

        require_once __DIR__ . '/../views/admin/libros.php';
    }

    // Mostrar formulario para crear un libro nuevo
    public function crearLibro(){
        $this->requireStaff();

        $autores = Autor::obtenerTodos();
        $categorias = Categoria::obtenerTodas();

        require_once __DIR__ . '/../views/admin/crear_libro.php';
    }

    // Guardar un libro nuevo en la base de datos
    public function guardarLibro(){
        $this->requireStaff();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/libros');
        }

        $titulo = trim($_POST['titulo'] ?? '');
        $precio = (float) ($_POST['precio'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $sinopsis = $_POST['sinopsis'] ?? null;
        $idAutor = (int) ($_POST['id_autor'] ?? 0);
        $idCategoria = (int) ($_POST['id_categoria'] ?? 0);

        if ($titulo === '' || $idAutor <= 0 || $idCategoria <= 0) {
            die('Datos no válidos');
        }

        $portada = null;

        if (!empty($_FILES['portada']['name']) && is_uploaded_file($_FILES['portada']['tmp_name'])) {
            $portada = time() . "_" . basename($_FILES['portada']['name']);
            $ruta = __DIR__ . "/../../uploads/" . $portada;

            if (!move_uploaded_file($_FILES['portada']['tmp_name'], $ruta)) {
                die('No se pudo subir la portada');
            }
        }

        $idLibro = Libro::crear($titulo, $precio, $stock, $sinopsis, $portada, $idCategoria);
        Libro::asignarAutor($idLibro, $idAutor);

        $this->redirect('/admin/libros');
    }

    // Mostrar formulario para editar un libro existente
    public function editarLibro(){
        $this->requireStaff();

        $id = $this->getInt('id', 0);

        if ($id <= 0) die('ID de libro no válido');

        $libro = Libro::obtenerPorId($id);
        if (!$libro) die('Libro no encontrado');

        $autores = Autor::obtenerTodos();
        $categorias = Categoria::obtenerTodas();
        $autorActual = Libro::obtenerAutorId($id);

        require_once __DIR__ . '/../views/admin/editar_libro.php';
    }

    // Actualizar los datos de un libro existente
    public function actualizarLibro(){
        $this->requireStaff();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/libros');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $precio = (float) ($_POST['precio'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $sinopsis = $_POST['sinopsis'] ?? '';
        $idAutor = (int) ($_POST['id_autor'] ?? 0);
        $idCategoria = (int) ($_POST['id_categoria'] ?? 0);

        if ($id <= 0 || $titulo === '' || $idAutor <= 0 || $idCategoria <= 0) {
            die('Datos no válidos');
        }

        $portada = $_POST['portada_actual'] ?? null;

        if (!empty($_FILES['portada']['name']) && is_uploaded_file($_FILES['portada']['tmp_name'])) {
            $portada = time() . "_" . basename($_FILES['portada']['name']);
            $ruta = __DIR__ . "/../../uploads/" . $portada;

            if (!move_uploaded_file($_FILES['portada']['tmp_name'], $ruta)) {
                die('No se pudo subir la portada');
            }
        }

        Libro::actualizarCompleto($id, $titulo, $precio, $stock, $sinopsis, $portada, $idCategoria);
        Libro::actualizarAutor($id, $idAutor);

        $this->redirect('/admin/libros');
    }

    // Eliminar un libro por su id / baja logica
    public function eliminarLibro(){
        $this->requireStaff();

        $id = $this->getInt('id', 0);
        if ($id <= 0) die('ID de libro no válido');

        Libro::eliminar($id);

        $this->redirect('/admin/libros');
    }
    public function activarLibro(){
    $this->requireStaff();

    $id = $this->getInt('id', 0);
    if ($id <= 0) die('ID de libro no válido');

    Libro::activar($id);

    $this->redirect('/admin/libros');
}


    //Funciones de autores

    public function autores() {
        $this->requireStaff();

        $porPagina = 10;
        $pagina = $this->getInt('page', 1);
        $buscar = trim($_GET['buscar'] ?? '');

        // Si viene id para editar, cargamos ese autor
        $editarId = $this->getInt('editar', 0);
        $autorEditar = null;

        if ($editarId > 0) {
            $autorEditar = Autor::obtenerPorId($editarId);
        }

        // Buscar paginado o listado paginado normal
        if ($buscar !== '') {
            $total = Autor::contarBusqueda($buscar);
            $totalPaginas = ceil($total / $porPagina);
            $offset = ($pagina - 1) * $porPagina;

            $autores = Autor::buscarPaginados($buscar, $porPagina, $offset);
        } else {
            $total = Autor::contarTotal();
            $totalPaginas = ceil($total / $porPagina);
            $offset = ($pagina - 1) * $porPagina;

            $autores = Autor::obtenerPaginados($porPagina, $offset);
        }

        require_once __DIR__ . '/../views/admin/autores.php';
    }

    public function actualizarAutor() {
        $this->requireStaff();

        if (!$_POST) {
            $this->redirect("/admin/autores");
        }

        $id = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');

        if ($id <= 0 || $nombre === '') {
            die("Datos no válidos");
        }

        Autor::actualizar($id, $nombre);

        $this->redirect("/admin/autores");
    }

    //Crear
    public function crearAutor() {
        $this->requireStaff();
        require_once __DIR__ . '/../views/admin/crear_autor.php';
    }

    //Guardar
    public function guardarAutor() {
        $this->requireStaff();

        if (!$_POST) {
            $this->redirect("/admin/autores");
        }

        $nombre = $_POST['nombre'] ?? '';
        Autor::crear($nombre);

        $this->redirect("/admin/autores");
    }

    //Eliminar
    public function eliminarAutor() {
        $this->requireStaff();

        $id = $this->getInt('id', 0);
        if ($id <= 0) die("Autor no válido");

        Autor::eliminar($id);

        $this->redirect("/admin/autores");
    }

    //Funciones de Usuario

    public function usuarios() {
        Auth::requireAdmin();

        $porPagina = 5;
        $pagina = $this->getInt('page', 1);

        $total = Usuario::contarTotal();
        $totalPaginas = ceil($total / $porPagina);
        $offset = ($pagina - 1) * $porPagina;

        $usuarios = Usuario::obtenerPaginados($porPagina, $offset);

        require_once __DIR__ . '/../views/admin/usuarios.php';
    }

    //Editar
    public function editarUsuario() {
        Auth::requireAdmin();

        $dni = $_GET['dni'] ?? '';
        if (!$dni) die("DNI no válido");

        $usuario = Usuario::obtenerPorDni($dni);
        if (!$usuario) die("Usuario no encontrado");

        require_once __DIR__ . '/../views/admin/editar_usuario.php';
    }

    //Actualizar
    public function actualizarUsuario() {
        Auth::requireAdmin();

        if (!$_POST) {
            $this->redirect("/admin/usuarios");
        }

        Usuario::actualizarAdmin(
            $_POST['dni'],
            $_POST['nombre'],
            $_POST['email'],
            $_POST['rol'],
            $_POST['activo']
        );

        $this->redirect("/admin/usuarios");
    }

    //Cambiar rol
    public function cambiarRol()
{
    Auth::requireAdmin();

    $dni = strtoupper(trim((string)($_GET['dni'] ?? '')));
    $rol = trim((string)($_GET['rol'] ?? ''));

    if ($dni === '' || $rol === '') {
        $this->redirect("/index.php?url=admin/usuarios");
        return;
    }

    Usuario::cambiarRol($dni, $rol);

    if (!empty($_SESSION['usuario']['dni']) && $_SESSION['usuario']['dni'] === $dni) {
        $_SESSION['usuario']['rol'] = $rol;
    }

    $this->redirect("/index.php?url=admin/usuarios");
}


    //Cambiar actividad
    public function cambiarActivo() {
        Auth::requireAdmin();

        $dni = $_GET['dni'] ?? '';
        $activo = $_GET['activo'] ?? null;

        if (!$dni || ($activo === null)) die("Datos no válidos");

        Usuario::cambiarActivo($dni, $activo);

        $this->redirect("/admin/usuarios");
    }

    //Eliminacion logica
    public function eliminarUsuario() {
    Auth::requireAdmin();

    $dni = $_GET['dni'] ?? '';
    if (!$dni) die("DNI no válido");

    // Protección: no me quiero eliminar yo sola
    if ($dni === ($_SESSION['usuario']['dni'] ?? '')) {
        die("No puedes eliminar tu propio usuario");
    }

    Usuario::desactivar($dni);

    $this->redirect("/admin/usuarios");
}


    //Funciones de pedidos

    public function pedidos() {
        $this->requireStaff();

        $porPagina = 5;
        $pagina = $this->getInt('page', 1);

        $total = Pedido::contarTotal();
        $totalPaginas = ceil($total / $porPagina);
        $offset = ($pagina - 1) * $porPagina;

        $pedidos = Pedido::obtenerPaginados($porPagina, $offset);

        require_once __DIR__ . '/../views/admin/pedidos.php';
    }

    //mostrar datos del pedido
    public function verPedido() {
        $this->requireStaff();

        $id = $this->getInt('id', 0);
        if ($id <= 0) die("Pedido no válido");

        $pedido = Pedido::obtenerPedidoCompleto($id);

        if (!$pedido) {
            echo "Pedido no encontrado";
            return;
        }

        $lineas = Pedido::obtenerLineasConLibros($id);

        require_once __DIR__ . '/../views/admin/ver_pedido.php';
    }

    //Actualizar el estado del pedido
    public function actualizarEstadoPedido() {
        $this->requireStaff();

        $id = $_POST['id'];
        $estado = $_POST['estado'];

        Pedido::actualizarEstado($id, $estado);

        header("Location: " . BASE_URL . "/admin/pedidos");
        exit;
    }

    //Elimana el pedido
    public function eliminarPedido() {
        $this->requireStaff();

        $id = $this->getInt('id', 0);
        if ($id <= 0) die("Pedido no válido");

        Pedido::eliminar($id);

        $this->redirect("/admin/pedidos");
    }

    //Funciones de categorías

    public function categorias() {
        $this->requireStaff();

        $porPagina = 10;
        $pagina = $this->getInt('page', 1);
        $buscar = trim($_GET['buscar'] ?? '');

        // Si viene id para editar, cargamos esa categoría
        $editarId = $this->getInt('editar', 0);
        $categoriaEditar = null;

        if ($editarId > 0) {
            $categoriaEditar = Categoria::obtenerPorId($editarId);
        }

        $offset = ($pagina - 1) * $porPagina;

        // Si hay búsqueda -> buscar paginado (con conteo)
        if ($buscar !== '') {
            $total = Categoria::contarBusqueda($buscar);
            $totalPaginas = ceil($total / $porPagina);

            $categorias = Categoria::buscarPaginadasConConteo($buscar, $porPagina, $offset);
        } else {
            // Sin búsqueda -> paginación normal (con conteo)
            $total = Categoria::contarTotal();
            $totalPaginas = ceil($total / $porPagina);

            $categorias = Categoria::obtenerPaginadasConConteo($porPagina, $offset);
        }

        if ($totalPaginas < 1) $totalPaginas = 1;

        require_once __DIR__ . '/../views/admin/categorias.php';
    }

    //Actualizamos la categoria 
    public function actualizarCategoria() {
        $this->requireStaff();

        if (!$_POST) {
            $this->redirect("/admin/categorias");
        }

        $id = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');

        if ($id <= 0 || $nombre === '') {
            die("Datos no válidos");
        }

        Categoria::actualizar($id, $nombre);

        $this->redirect("/admin/categorias");
    }

    //Crear categorias nuevas
    public function crearCategoria() {
        $this->requireStaff();

        if (!$_POST) {
            $this->redirect("/admin/categorias");
        }

        $nombre = $_POST['nombre'] ?? '';

        if (trim($nombre) === '') {
            die("Nombre no válido");
        }

        Categoria::crear($nombre);

        $this->redirect("/admin/categorias");
    }

    //Elimina la categoria
    public function eliminarCategoria() {
        $this->requireStaff();

        $id = $this->getInt('id', 0);

        if ($id <= 0) {
            die("Categoría no válida");
        }

        Categoria::eliminar($id);

        $this->redirect("/admin/categorias");
    }

    public function cambiarVisibleCategoria() {
        $this->requireStaff();

        $id = $this->getInt('id', 0);
        $visible = isset($_GET['visible']) ? (int)$_GET['visible'] : -1;

        if ($id <= 0 || !in_array($visible, [0,1], true)) {
            die("Datos no válidos");
        }

        Categoria::cambiarVisible($id, $visible);

        $this->redirect("/admin/categorias");
    }
}

?>
