<?php

require_once __DIR__ . '/../models/Libro.php';

class LibroController{
    // Obtener un entero seguro desde GET con valor por defecto
    private function getInt(string $key, int $default = 0){
        return isset($_GET[$key]) ? (int) $_GET[$key] : $default;
    }

    // Listar libros con filtros (búsqueda/categoría) y paginación
    public function index(){
        $buscar = trim($_GET['buscar'] ?? '');
        $orden = $_GET['orden'] ?? null;
        $categoria = (int) ($_GET['categoria'] ?? 0);

        $porPagina = 6;
        $pagina = $this->getInt('page', 1);
        if ($pagina < 1) $pagina = 1;

        $offset = ($pagina - 1) * $porPagina;

        $libros = [];
        $total = 0;
        $totalPaginas = 1;

        if ($categoria > 0) {
            $total = Libro::contarPorCategoria($categoria);
            $totalPaginas = (int) ceil($total / $porPagina);
            $libros = Libro::obtenerPorCategoriaPaginados($categoria, $porPagina, $offset, $orden);
        } elseif ($buscar !== '') {
            $libros = Libro::buscarOrdenado($buscar, $orden);
            $total = count($libros);
            $totalPaginas = 1;
            $pagina = 1;
            $offset = 0;
        } else {
            $total = Libro::contarTotal();
            $totalPaginas = (int) ceil($total / $porPagina);
            $libros = Libro::obtenerOrdenados($porPagina, $offset, $orden);
        }

        if ($totalPaginas < 1) $totalPaginas = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        require_once __DIR__ . '/../views/libros/index.php';
    }

    // Mostrar los detalles de un libro por id y cargar su vista
    public function ver(){
        $id = $this->getInt('id', 0);

        if ($id <= 0) {
            echo "Libro no encontrado";
            return;
        }

        $libro = Libro::obtenerDetalle($id);

        if (!$libro) {
            echo "Libro no encontrado";
            return;
        }

        require_once __DIR__ . '/../views/libros/ver.php';
    }
}
?>
