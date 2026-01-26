<?php
//Modelos
require_once __DIR__ . '/../config/database.php';

class Direccion {

    //Obtener direccion por usuario
    public static function obtenerPorUsuario(string $dni): array {
        $db = Database::connect();

        $sql = "SELECT * FROM direcciones WHERE dni_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$dni]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Obtener por ID
    public static function obtenerPorId(int $id): ?array {
        $db = Database::connect();

        $sql = "SELECT * FROM direcciones WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    //Crear
    public static function crear(
        string $dni,
        string $nombre,
        string $direccion,
        string $ciudad,
        string $provincia,
        string $cp,
        string $pais,
        string $telefono
    ): bool {
        $db = Database::connect();

        $sql = "INSERT INTO direcciones
                (dni_usuario, nombre_destinatario, direccion, ciudad, provincia, codigo_postal, pais, telefono)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            $dni, $nombre, $direccion, $ciudad, $provincia, $cp, $pais, $telefono
        ]);
    }

    //Actualizar
    public static function actualizar(
        int $id,
        string $nombre,
        string $direccion,
        string $ciudad,
        string $provincia,
        string $cp,
        string $pais,
        string $telefono
    ): bool {
        $db = Database::connect();

        $sql = "UPDATE direcciones
                SET nombre_destinatario = ?,
                    direccion = ?,
                    ciudad = ?,
                    provincia = ?,
                    codigo_postal = ?,
                    pais = ?,
                    telefono = ?
                WHERE id = ?";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            $nombre, $direccion, $ciudad, $provincia, $cp, $pais, $telefono, $id
        ]);
    }

    //Eliminar
    public static function eliminar(int $id): bool {
        $db = Database::connect();

        $sql = "DELETE FROM direcciones WHERE id = ?";
        $stmt = $db->prepare($sql);

        return $stmt->execute([$id]);
    }
}
?>