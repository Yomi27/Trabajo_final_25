<?php

require_once __DIR__ . '/../config/database.php';

class Libro
{
    // Obtener un libro por id devolviendo null si no existe
    public static function obtenerPorId($id)
    {
        $db = Database::connect();

        $id = (int) $id;
        if ($id <= 0) return null;

        $stmt = $db->prepare("SELECT * FROM libro WHERE id = ?");
        $stmt->execute([$id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    // Crear un libro nuevo y devolver su id insertado
    public static function crear($titulo, $precio, $stock, $sinopsis, $portada, $idCategoria)
    {
        $db = Database::connect();

        $titulo = trim((string) $titulo);
        $precio = (float) $precio;
        $stock = (int) $stock;
        $sinopsis = ($sinopsis === null) ? null : (string) $sinopsis;
        $portada = ($portada === null) ? null : (string) $portada;
        $idCategoria = (int) $idCategoria;

        if ($titulo === '' || $idCategoria <= 0 || $precio < 0 || $stock < 0) {
            return 0;
        }

        $sql = "INSERT INTO libro (titulo, precio, stock, sinopsis, portada, id_categoria, activo)
                VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$titulo, $precio, $stock, $sinopsis, $portada, $idCategoria]);

        return (int) $db->lastInsertId();
    }

    // Actualizar datos básicos de un libro (título, precio y stock)
    public static function actualizar($id, $titulo, $precio, $stock)
    {
        $db = Database::connect();

        $id = (int) $id;
        $titulo = trim((string) $titulo);
        $precio = (float) $precio;
        $stock = (int) $stock;

        if ($id <= 0 || $titulo === '' || $precio < 0 || $stock < 0) {
            return false;
        }

        $stmt = $db->prepare("UPDATE libro SET titulo = ?, precio = ?, stock = ? WHERE id = ?");
        return $stmt->execute([$titulo, $precio, $stock, $id]);
    }

    // Actualizar todos los campos editables del libro desde administración
    public static function actualizarCompleto($id, $titulo, $precio, $stock, $sinopsis, $portada, $idCategoria)
    {
        $db = Database::connect();

        $id = (int) $id;
        $titulo = trim((string) $titulo);
        $precio = (float) $precio;
        $stock = (int) $stock;
        $sinopsis = ($sinopsis === null) ? null : (string) $sinopsis;
        $portada = ($portada === null) ? null : (string) $portada;
        $idCategoria = (int) $idCategoria;

        if ($id <= 0 || $titulo === '' || $idCategoria <= 0 || $precio < 0 || $stock < 0) {
            return false;
        }

        $sql = "UPDATE libro
                SET titulo = ?, precio = ?, stock = ?, sinopsis = ?, portada = ?, id_categoria = ?
                WHERE id = ?";
        $stmt = $db->prepare($sql);

        return $stmt->execute([$titulo, $precio, $stock, $sinopsis, $portada, $idCategoria, $id]);
    }

    // Eliminar un libro, baja logica
    public static function eliminar($id)
    {
        $db = Database::connect();

        $id = (int) $id;
        if ($id <= 0) return false;

        $stmt = $db->prepare("UPDATE libro SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function activar($id)
    {
        $db = Database::connect();

        $id = (int) $id;
        if ($id <= 0) return false;

        $stmt = $db->prepare("UPDATE libro SET activo = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Asignar un autor a un libro creando la relación en libro_autor
    public static function asignarAutor($idLibro, $idAutor)
    {
        $db = Database::connect();

        $idLibro = (int) $idLibro;
        $idAutor = (int) $idAutor;

        if ($idLibro <= 0 || $idAutor <= 0) return false;

        $stmt = $db->prepare("INSERT INTO libro_autor (id_libro, id_autor) VALUES (?, ?)");
        return $stmt->execute([$idLibro, $idAutor]);
    }

    // Reemplazar el autor de un libro eliminando la relación anterior y creando la nueva
    public static function actualizarAutor($idLibro, $idAutor)
    {
        $db = Database::connect();

        $idLibro = (int) $idLibro;
        $idAutor = (int) $idAutor;

        if ($idLibro <= 0 || $idAutor <= 0) return false;

        $stmt = $db->prepare("DELETE FROM libro_autor WHERE id_libro = ?");
        $stmt->execute([$idLibro]);

        $stmt = $db->prepare("INSERT INTO libro_autor (id_libro, id_autor) VALUES (?, ?)");
        return $stmt->execute([$idLibro, $idAutor]);
    }

    // Obtener el id del autor asociado a un libro (si existe relación)
    public static function obtenerAutorId($idLibro)
    {
        $db = Database::connect();

        $idLibro = (int) $idLibro;
        if ($idLibro <= 0) return null;

        $stmt = $db->prepare("SELECT id_autor FROM libro_autor WHERE id_libro = ? LIMIT 1");
        $stmt->execute([$idLibro]);

        $idAutor = $stmt->fetchColumn();
        return $idAutor ? (int) $idAutor : null;
    }

    // Obtener listado completo de libros con autor asociado (sin paginación)
    public static function obtenerTodos()
    {
        $db = Database::connect();

        $sql = "SELECT libro.*, autor.nombre AS autor_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                WHERE libro.activo = 1
                ORDER BY libro.id DESC";

        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar total de libros para paginación en listados
    public static function contarTotal()
    {
        $db = Database::connect();
        return (int) $db->query("SELECT COUNT(DISTINCT libro.id) FROM libro WHERE libro.activo = 1")->fetchColumn();
    }

    // Obtener libros paginados para panel admin incluyendo autor y categoría
    public static function obtenerPaginados($limite, $offset)
    {
        $db = Database::connect();

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                ORDER BY libro.id DESC
                LIMIT ? OFFSET ?";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalle de un libro con su autor y categoría para la vista de detalle
    public static function obtenerDetalle($id)
    {
        $db = Database::connect();

        $id = (int) $id;
        if ($id <= 0) return null;

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.id = ? AND libro.activo = 1
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    // Obtener novedades mostrando los últimos libros insertados con autor y categoría
    public static function obtenerNovedades($limite = 6)
    {
        $db = Database::connect();

        $limite = max(1, (int) $limite);

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.activo = 1
                ORDER BY libro.id DESC
                LIMIT ?";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar libros por texto en título, autor o categoría
    public static function buscar($texto)
    {
        $db = Database::connect();

        $texto = trim((string) $texto);
        $busqueda = '%' . $texto . '%';

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.activo = 1
                  AND (
                        libro.titulo LIKE ?
                     OR autor.nombre LIKE ?
                     OR categoria.nombre LIKE ?
                  )
                ORDER BY libro.titulo ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute([$busqueda, $busqueda, $busqueda]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener libros ordenados con paginación para la tienda (precio asc/desc o novedades)
    public static function obtenerOrdenados($limite, $offset, $orden)
    {
        $db = Database::connect();

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $orderBy = "libro.id DESC";
        if ($orden === 'precio_asc') $orderBy = "libro.precio ASC";
        if ($orden === 'precio_desc') $orderBy = "libro.precio DESC";

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.activo = 1
                ORDER BY $orderBy
                LIMIT ? OFFSET ?";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar libros aplicando ordenación por precio o por novedades
    public static function buscarOrdenado($texto, $orden)
    {
        $db = Database::connect();

        $texto = trim((string) $texto);
        $busqueda = '%' . $texto . '%';

        $orderBy = "libro.id DESC";
        if ($orden === 'precio_asc') $orderBy = "libro.precio ASC";
        if ($orden === 'precio_desc') $orderBy = "libro.precio DESC";

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.activo = 1
                  AND (
                        libro.titulo LIKE ?
                     OR autor.nombre LIKE ?
                     OR categoria.nombre LIKE ?
                  )
                ORDER BY $orderBy";

        $stmt = $db->prepare($sql);
        $stmt->execute([$busqueda, $busqueda, $busqueda]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener stock actual de un libro para validar compras
    public static function obtenerStock($idLibro)
    {
        $db = Database::connect();

        $idLibro = (int) $idLibro;
        if ($idLibro <= 0) return 0;

        $stmt = $db->prepare("SELECT stock FROM libro WHERE id = ? AND activo = 1");
        $stmt->execute([$idLibro]);

        $stock = $stmt->fetchColumn();
        return $stock !== false ? (int) $stock : 0;
    }

    // Restar stock cuando se confirma un pedido evitando stock negativo
    public static function restarStock($idLibro, $cantidad)
    {
        $db = Database::connect();

        $idLibro = (int) $idLibro;
        $cantidad = (int) $cantidad;

        if ($idLibro <= 0 || $cantidad <= 0) return false;

        $sql = "UPDATE libro
                SET stock = stock - ?
                WHERE id = ? AND activo = 1 AND stock >= ?";
        $stmt = $db->prepare($sql);

        return $stmt->execute([$cantidad, $idLibro, $cantidad]);
    }

    // Contar resultados de búsqueda para paginación en el panel admin
    public static function contarBusqueda($texto)
    {
        $db = Database::connect();

        $texto = trim((string) $texto);
        $busqueda = '%' . $texto . '%';

        $sql = "SELECT COUNT(DISTINCT libro.id)
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.activo = 1
                  AND (
                        libro.titulo LIKE ?
                     OR autor.nombre LIKE ?
                     OR categoria.nombre LIKE ?
                  )";

        $stmt = $db->prepare($sql);
        $stmt->execute([$busqueda, $busqueda, $busqueda]);

        return (int) $stmt->fetchColumn();
    }

    // Buscar libros paginados para admin por título, autor o categoría
    public static function buscarPaginados($texto, $limite, $offset)
    {
        $db = Database::connect();

        $texto = trim((string) $texto);
        $busqueda = '%' . $texto . '%';

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.titulo LIKE ?
                   OR autor.nombre LIKE ?
                   OR categoria.nombre LIKE ?
                ORDER BY libro.id DESC
                LIMIT ? OFFSET ?";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $busqueda, PDO::PARAM_STR);
        $stmt->bindValue(2, $busqueda, PDO::PARAM_STR);
        $stmt->bindValue(3, $busqueda, PDO::PARAM_STR);
        $stmt->bindValue(4, $limite, PDO::PARAM_INT);
        $stmt->bindValue(5, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener libros de una categoría concreta sin paginación
    public static function obtenerPorCategoria($idCategoria)
    {
        $db = Database::connect();

        $idCategoria = (int) $idCategoria;
        if ($idCategoria <= 0) return [];

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.id_categoria = ? AND libro.activo = 1
                ORDER BY libro.titulo ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute([$idCategoria]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar libros de una categoría para paginación en la tienda
    public static function contarPorCategoria($idCategoria)
    {
        $db = Database::connect();

        $idCategoria = (int) $idCategoria;
        if ($idCategoria <= 0) return 0;

        $stmt = $db->prepare("SELECT COUNT(*) FROM libro WHERE id_categoria = ? AND activo = 1");
        $stmt->execute([$idCategoria]);

        return (int) $stmt->fetchColumn();
    }

    // Obtener libros de una categoría con paginación y ordenación por precio o novedades
    public static function obtenerPorCategoriaPaginados($idCategoria, $limite, $offset, $orden = null)
    {
        $db = Database::connect();

        $idCategoria = (int) $idCategoria;
        if ($idCategoria <= 0) return [];

        $limite = max(1, (int) $limite);
        $offset = max(0, (int) $offset);

        $orderBy = "libro.id DESC";
        if ($orden === 'precio_asc') $orderBy = "libro.precio ASC";
        if ($orden === 'precio_desc') $orderBy = "libro.precio DESC";

        $sql = "SELECT libro.*,
                       autor.nombre AS autor_nombre,
                       categoria.nombre AS categoria_nombre
                FROM libro
                LEFT JOIN libro_autor ON libro.id = libro_autor.id_libro
                LEFT JOIN autor ON libro_autor.id_autor = autor.id
                LEFT JOIN categoria ON libro.id_categoria = categoria.id
                WHERE libro.id_categoria = ? AND libro.activo = 1
                ORDER BY $orderBy
                LIMIT ? OFFSET ?";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $idCategoria, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
