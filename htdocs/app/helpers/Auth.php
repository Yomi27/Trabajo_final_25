<?php

class Auth {

    // Comprueba si hay usuario logueado
    public static function check() {
        return isset($_SESSION['usuario']);
    }

    // Obliga a estar logueado
    public static function requireLogin() {
        if (!self::check()) {
            header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/usuario/login");
            exit;
        }
    }

    // Obliga a ser admin
    public static function requireAdmin() {
        self::requireLogin();

        if ($_SESSION['usuario']['rol'] !== 'admin') {
            echo "Acceso denegado. Solo administradores.";
            exit;
        }
    }
    
}
?>