<?php
require_once __DIR__ . '/../models/Libro.php';
class HomeController {

    public function index() {

    // Últimos libros añadidos
    $novedades = Libro::obtenerNovedades(8);

    require_once __DIR__ . '/../views/home/index.php';
    }

}
?>