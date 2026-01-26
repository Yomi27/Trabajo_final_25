<?php
//Solución para poder trabajar desde ambos sitios
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    require_once __DIR__ . '/database.local.php';
} else {
    require_once __DIR__ . '/database.prod.php';
}

class Database {

    private static $charset = "utf8mb4";

    public static function connect() {
        try {
            $dsn = "mysql:host=" . DatabaseConfig::$host .
                   ";dbname=" . DatabaseConfig::$db .
                   ";charset=" . self::$charset;

            $pdo = new PDO($dsn, DatabaseConfig::$user, DatabaseConfig::$pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            return $pdo;

        } catch (PDOException $e) {
            die("Error conexión BD: " . $e->getMessage());
        }
    }
}
