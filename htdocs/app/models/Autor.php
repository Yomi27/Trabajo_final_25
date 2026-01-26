<?php

require_once __DIR__ . '/../config/database.php';

class Autor
{
    // Obtener listado completo de autores ordenados por nombre
    public static function obtenerTodos()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM autor ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un autor por id devolviendo null si no existe
    public static function obtenerPorId($id)
    {
        $db = Database::connect();

        $id = (int) $id;
        if ($id <= 0) return null;

        $stmt = $db->prepare("SELECT * FROM autor WHERE id = ?");
        $stmt->execute([$id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    // Crear un autor nuevo activándolo por defecto
    public static function crear($nombre)
    {
        $db = Database::connect();

        $nombre = trim((string) $nombre);
        if ($nombre === '') return false;

        $stmt = $db->prepare("INSERT INTO autor (nombre, activo) VALUES (?, 1)");
        return $stmt->execute([$nombre]);
    }

    // Actualizar el nombre de un autor existente
    public static function actualizar($id, $nombre)
    {
        $db = Database::connect();

        $id = (int) $id;
        $nombre = trim((string) $nombre);

        if ($id <= 0 || $nombre === '') return false;

        $stmt = $db->prepare("UPDATE autor SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }

    // Eliminar un autor por id de la base de datos
    public static function eliminar($id)
    {
        $db = Database::connect();

        $id = (int) $id;
        if ($id <= 0) return false;

        $stmt = $db->prepare("DELETE FROM autor WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Contar autores totales para paginación
    public static function contarTotal()
    {
        $db = Database::connect();
        return (int) $db->query("SELECT COUNT(*) FROM autor")->fetchColumn();
    }

    // Obtener autores paginados usando LIMIT y OFFSET
    public static function obtenerPaginados($limite, $offset)
    {
        $db = Database::connect();

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $sql = "SELECT * FROM autor ORDER BY nombre LIMIT ? OFFSET ?";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar resultados de una búsqueda por nombre
    public static function contarBusqueda($texto)
    {
        $db = Database::connect();

        $texto = trim((string) $texto);
        $busqueda = '%' . $texto . '%';

        $sql = "SELECT COUNT(*) FROM autor WHERE LOWER(nombre) LIKE LOWER(?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$busqueda]);

        return (int) $stmt->fetchColumn();
    }

    // Buscar autores por nombre con paginación y orden alfabético
    public static function buscarPaginados($texto, $limite, $offset)
    {
        $db = Database::connect();

        $texto = trim((string) $texto);
        $busqueda = '%' . $texto . '%';

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $sql = "SELECT * FROM autor
                WHERE LOWER(nombre) LIKE LOWER(?)
                ORDER BY nombre
                LIMIT ? OFFSET ?";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $busqueda, PDO::PARAM_STR);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
