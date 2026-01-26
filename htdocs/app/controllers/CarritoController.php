<?php

require_once __DIR__ . '/../models/Libro.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Direccion.php';

class CarritoController{
    // Obtener un entero seguro desde un array (GET/POST) con valor por defecto
    private function getInt(array $source, string $key, int $default = 0){
        return isset($source[$key]) ? (int) $source[$key] : $default;
    }

    // Redirigir a una ruta de la app y cortar ejecución
    private function redirect(string $path){
        header("Location: " . BASE_URL . $path);
        exit;
    }

    // Inicializar el carrito en sesión si no existe o viene corrupto
    private function initCarrito(){
        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    // Mostrar la vista del carrito con los productos actuales
    public function index(){
        $this->initCarrito();

        $carrito = $_SESSION['carrito'];
        require_once __DIR__ . '/../views/carrito/index.php';
    }

    // Mostrar selección de dirección para confirmar compra
    public function direccion(){
    $this->initCarrito();

    if (empty($_SESSION['carrito'])) {
        $this->redirect("/carrito");
    }

    if (!isset($_SESSION['usuario'])) {
        $this->redirect("/usuario/login");
    }

    $dni = $_SESSION['usuario']['dni'];
    $direcciones = Direccion::obtenerPorUsuario($dni);

    if (empty($direcciones)) {
        $this->redirect("/direccion/crear");
    }

    require_once __DIR__ . '/../views/carrito/direccion.php';
}

    // Añadir un libro al carrito o aumentar su cantidad si ya estaba
    public function agregar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_libro'])) {
            $this->redirect("/carrito");
        }

        $this->initCarrito();

        $id = $this->getInt($_POST, 'id_libro', 0);
        if ($id <= 0) {
            echo "Libro no válido";
            return;
        }

        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad']++;
            $this->redirect("/carrito");
        }

        $libro = Libro::obtenerPorId($id);
        if (!$libro) {
            echo "Libro no encontrado";
            return;
        }

        $_SESSION['carrito'][$id] = [
            'id' => (int) $libro['id'],
            'titulo' => $libro['titulo'],
            'precio' => (float) $libro['precio'],
            'cantidad' => 1
        ];

        $this->redirect("/carrito");
    }

    // Aumentar en 1 la cantidad de un libro en el carrito
    public function aumentar(){
        $this->initCarrito();

        $id = $this->getInt($_GET, 'id', 0);

        if ($id > 0 && isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad']++;
        }

        $this->redirect("/carrito");
    }

    // Disminuir en 1 la cantidad de un libro y eliminarlo si queda en 0
    public function disminuir(){
        $this->initCarrito();

        $id = $this->getInt($_GET, 'id', 0);

        if ($id > 0 && isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad']--;

            if ($_SESSION['carrito'][$id]['cantidad'] <= 0) {
                unset($_SESSION['carrito'][$id]);
            }
        }

        $this->redirect("/carrito");
    }

    // Eliminar un libro del carrito independientemente de su cantidad
    public function eliminar(){
        $this->initCarrito();

        $id = $this->getInt($_GET, 'id', 0);

        if ($id > 0 && isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
        }

        $this->redirect("/carrito");
    }

    // Confirmar compra creando pedido, líneas, actualizando stock y vaciando carrito
    public function confirmar() {
    Auth::requireLogin();

    if (empty($_SESSION['carrito'])) {
        echo "El carrito está vacío";
        return;
    }

    $direccionId = $this->getInt($_POST, 'direccion_id', 0);
    if ($direccionId <= 0) {
        echo "Debes seleccionar una dirección";
        return;
    }

    $carrito = $_SESSION['carrito'];
    $dni = $_SESSION['usuario']['dni'];

    // 1) Comprobar stock antes de crear pedido
    foreach ($carrito as $item) {
        $stock = Libro::obtenerStock($item['id']);
        if ($item['cantidad'] > $stock) {
            echo "No hay stock suficiente para: " . htmlspecialchars($item['titulo']);
            return;
        }
    }

    // 2) Calcular total
    $total = 0;
    foreach ($carrito as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    // 3) Crear pedido
    $idPedido = Pedido::crearPedido($dni, $direccionId, $total);

    // 4) Guardar líneas de pedido
    foreach ($carrito as $item) {
        Pedido::agregarLinea(
            $idPedido,
            $item['id'],
            $item['cantidad'],
            $item['precio']
        );
    }

    // 5) Restar stock
    foreach ($carrito as $item) {
        Libro::restarStock($item['id'], $item['cantidad']);
    }

    // 6) Vaciar carrito
    unset($_SESSION['carrito']);
	//pagaaaar 4242 4242 4242 4242
    header('Location: ' . BASE_URL . '/index.php?url=pedido/checkout&id=' . (int)$idPedido);
    exit;
}

}
?>