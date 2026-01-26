<?php
//Modelos:

require_once __DIR__ . '/../models/Direccion.php';

class DireccionController {

    // Obtener un entero seguro desde GET evitando valores inválidos
    private function getInt(array $source, string $key, int $default = 0): int {
        return isset($source[$key]) ? (int)$source[$key] : $default;
    }

    /**
     * Redirige a una ruta de la app.
     */
    private function redirect(string $path): void {
        header("Location: " . BASE_URL . $path);
        exit;
    }

    // Lista
    public function index() {
        Auth::requireLogin();

        $dni = $_SESSION['usuario']['dni'];
        $direcciones = Direccion::obtenerPorUsuario($dni);

        require_once __DIR__ . '/../views/direcciones/index.php';
    }

    //Crear dirección
    public function crear() {
        Auth::requireLogin();
        require_once __DIR__ . '/../views/direcciones/crear.php';
    }
    //Guardar
    public function guardar() {
        Auth::requireLogin();

        if (!$_POST) {
            $this->redirect("/direccion");
        }

        $dni = $_SESSION['usuario']['dni'];

        Direccion::crear(
            $dni,
            $_POST['nombre'] ?? '',
            $_POST['direccion'] ?? '',
            $_POST['ciudad'] ?? '',
            $_POST['provincia'] ?? '',
            $_POST['cp'] ?? '',
            $_POST['pais'] ?? '',
            $_POST['telefono'] ?? ''
        );

        $this->redirect("/direccion");
    }

    //Editar
    public function editar() {
        Auth::requireLogin();

        $id = $this->getInt($_GET, 'id', 0);
        if ($id <= 0) {
            echo "ID no válido";
            return;
        }

        $direccion = Direccion::obtenerPorId($id);
        if (!$direccion) {
            echo "Dirección no encontrada";
            return;
        }

        require_once __DIR__ . '/../views/direcciones/editar.php';
    }  
    //Actualizar
    public function actualizar() {
        Auth::requireLogin();

        if (!$_POST) {
            $this->redirect("/direccion");
        }

        $id = $this->getInt($_POST, 'id', 0);
        if ($id <= 0) {
            echo "ID no válido";
            return;
        }

        Direccion::actualizar(
            $id,
            $_POST['nombre'] ?? '',
            $_POST['direccion'] ?? '',
            $_POST['ciudad'] ?? '',
            $_POST['provincia'] ?? '',
            $_POST['cp'] ?? '',
            $_POST['pais'] ?? '',
            $_POST['telefono'] ?? ''
        );

        $this->redirect("/direccion");
    }

    //Eliminar
    public function eliminar() {
        Auth::requireLogin();

        $id = $this->getInt($_GET, 'id', 0);
        if ($id > 0) {
            Direccion::eliminar($id);
        }

        $this->redirect("/direccion");
    }
}
?>