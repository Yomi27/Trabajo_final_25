<?php

require_once __DIR__ . '/../config/database.php';

class Categoria
{
    // Obtener todas las categorías ordenadas por nombre
    public static function obtenerTodas()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM categoria ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una categoría por id devolviendo null si no existe
    public static function obtenerPorId($id)
    {
        $db = Database::connect();

        $id = (int) $id;
        if ($id <= 0) return null;

        $stmt = $db->prepare("SELECT * FROM categoria WHERE id = ?");
        $stmt->execute([$id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }
    // Obtener categorías paginadas + contador de libros
public static function obtenerPaginadasConConteo($limite, $offset)
{
    $db = Database::connect();

    $limite = max(1, (int) $limite);
    $offset = max(0, (int) $offset);

    $sql = "SELECT c.id, c.nombre, c.visible,
                   COUNT(l.id) AS total_libros
            FROM categoria c
            LEFT JOIN libro l ON l.id_categoria = c.id
            GROUP BY c.id, c.nombre, c.visible
            ORDER BY c.nombre ASC
            LIMIT ? OFFSET ?";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $limite, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar categorías paginadas + contador de libros
public static function buscarPaginadasConConteo($texto, $limite, $offset)
{
    $db = Database::connect();

    $texto = trim((string) $texto);
    $busqueda = '%' . $texto . '%';

    $limite = max(1, (int) $limite);
    $offset = max(0, (int) $offset);

    $sql = "SELECT c.id, c.nombre, c.visible,
                   COUNT(l.id) AS total_libros
            FROM categoria c
            LEFT JOIN libro l ON l.id_categoria = c.id
            WHERE LOWER(c.nombre) LIKE LOWER(?)
            GROUP BY c.id, c.nombre, c.visible
            ORDER BY c.nombre ASC
            LIMIT ? OFFSET ?";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $busqueda, PDO::PARAM_STR);
    $stmt->bindValue(2, $limite, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



    
    // Crear una nueva categoría con nombre válido
    public static function crear($nombre)
    {
        $db = Database::connect();

        $nombre = trim((string) $nombre);
        if ($nombre === '') return false;

        $stmt = $db->prepare("INSERT INTO categoria (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }

    // Actualizar el nombre de una categoría existente
    public static function actualizar($id, $nombre)
    {
        $db = Database::connect();

        $id = (int) $id;
        $nombre = trim((string) $nombre);

        if ($id <= 0 || $nombre === '') return false;

        $stmt = $db->prepare("UPDATE categoria SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }

    // Eliminar una categoría por id de la base de datos
    public static function eliminar($id)
    {
        $db = Database::connect();

        $id = (int) $id;
        if ($id <= 0) return false;

        $stmt = $db->prepare("DELETE FROM categoria WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Obtener solo categorías visibles para mostrarlas en el menú
    public static function obtenerVisibles()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM categoria WHERE visible = 1 ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cambiar visibilidad de una categoría para controlar si aparece en el menú
    public static function cambiarVisible($id, $visible)
    {
        $db = Database::connect();

        $id = (int) $id;
        $visible = (int) $visible;

        if ($id <= 0 || !in_array($visible, [0, 1], true)) return false;

        $stmt = $db->prepare("UPDATE categoria SET visible = ? WHERE id = ?");
        return $stmt->execute([$visible, $id]);
    }

    // Contar categorías totales para paginación
    public static function contarTotal()
    {
        $db = Database::connect();
        return (int) $db->query("SELECT COUNT(*) FROM categoria")->fetchColumn();
    }

    // Obtener categorías paginadas usando LIMIT y OFFSET
    public static function obtenerPaginadas($limite, $offset)
    {
        $db = Database::connect();

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $sql = "SELECT * FROM categoria ORDER BY nombre LIMIT ? OFFSET ?";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar resultados de búsqueda por nombre para paginación
    public static function contarBusqueda($texto)
    {
        $db = Database::connect();

        $texto = trim((string) $texto);
        $busqueda = '%' . $texto . '%';

        $sql = "SELECT COUNT(*) FROM categoria WHERE LOWER(nombre) LIKE LOWER(?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$busqueda]);

        return (int) $stmt->fetchColumn();
    }

    // Buscar categorías por nombre con paginación y orden alfabético
    public static function buscarPaginadas($texto, $limite, $offset)
    {
        $db = Database::connect();

        $texto = trim((string) $texto);
        $busqueda = '%' . $texto . '%';

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $sql = "SELECT * FROM categoria
                WHERE LOWER(nombre) LIKE LOWER(?)
                ORDER BY nombre ASC
                LIMIT ? OFFSET ?";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $busqueda, PDO::PARAM_STR);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
