<?php
session_start();

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
define('BASE_URL', $base === '' ? '' : $base);

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/Auth.php';

$url = $_GET['url'] ?? '';
$url = trim($url, '/');

if ($url === '') {
    $controller = 'home';
    $action = 'index';
} else {
    $partes = explode('/', $url);
    $controller = $partes[0] ?? 'home';
    $action = $partes[1] ?? 'index';

    $controller = $controller ?: 'home';
    $action     = $action ?: 'index';
}

$controllerName = ucfirst($controller) . "Controller";
$controllerFile = __DIR__ . "/app/controllers/$controllerName.php";

if (!file_exists($controllerFile)) {
    echo "Controlador no encontrado";
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    echo "Clase controlador no encontrada";
    exit;
}

$obj = new $controllerName();

if (!method_exists($obj, $action)) {
    echo "Acción no encontrada";
    exit;
}

$obj->$action();
exit;
?>