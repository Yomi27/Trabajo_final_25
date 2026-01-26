<?php

require_once __DIR__ . '/../config/database.php';

class Pedido
{
    // Crear un pedido y devolver el id generado
    public static function crearPedido($dniUsuario, $direccionId, $total)
    {
        $db = Database::connect();

        $dniUsuario  = trim((string)$dniUsuario);
        $direccionId = (int)$direccionId;
        $total       = (float)$total;

        // total NO puede ser negativo, y normalmente tampoco debería ser 0
        if ($dniUsuario === '' || $direccionId <= 0 || $total <= 0) {
            return 0;
        }

        // Importante: estado_pago inicial
        $sql = "INSERT INTO pedido (id_usuario, id_direccion_envio, total, estado_pago)
                VALUES (?, ?, ?, 'pendiente')";
        $stmt = $db->prepare($sql);
        $stmt->execute([$dniUsuario, $direccionId, $total]);

        return (int)$db->lastInsertId();
    }

    // Añadir una línea a un pedido con libro, cantidad y precio unitario
    public static function agregarLinea($idPedido, $idLibro, $cantidad, $precio)
    {
        $db = Database::connect();

        $idPedido  = (int)$idPedido;
        $idLibro   = (int)$idLibro;
        $cantidad  = (int)$cantidad;
        $precio    = (float)$precio;

        if ($idPedido <= 0 || $idLibro <= 0 || $cantidad <= 0 || $precio < 0) {
            return false;
        }

        $sql = "INSERT INTO lineas_pedido (id_pedido, id_libro, cantidad, precio_unitario)
                VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);

        return $stmt->execute([$idPedido, $idLibro, $cantidad, $precio]);
    }

    // Obtener pedidos de un usuario ordenados por los más recientes
    public static function obtenerPedidosPorUsuario($dni)
    {
        $db = Database::connect();

        $dni = trim((string)$dni);
        if ($dni === '') return [];

        $stmt = $db->prepare("SELECT * FROM pedido WHERE id_usuario = ? ORDER BY id DESC");
        $stmt->execute([$dni]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un pedido por id devolviendo null si no existe
    public static function obtenerPedidoPorId($id)
    {
        $db = Database::connect();

        $id = (int)$id;
        if ($id <= 0) return null;

        $stmt = $db->prepare("SELECT * FROM pedido WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    // Obtener las líneas de un pedido con el título del libro y sus precios
    public static function obtenerLineasConLibros($idPedido)
    {
        $db = Database::connect();

        $idPedido = (int)$idPedido;
        if ($idPedido <= 0) return [];

        $sql = "SELECT lp.cantidad,
                       lp.precio_unitario,
                       libro.titulo
                FROM lineas_pedido lp
                JOIN libro ON lp.id_libro = libro.id
                WHERE lp.id_pedido = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$idPedido]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los pedidos para administración incluyendo email del usuario
    public static function obtenerTodos()
    {
        $db = Database::connect();

        $sql = "SELECT pedido.*, usuario.email
                FROM pedido
                JOIN usuario ON pedido.id_usuario = usuario.dni
                ORDER BY pedido.fecha_pedido DESC";

        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener pedidos paginados para administración
    public static function obtenerPaginados($limite, $offset)
    {
        $db = Database::connect();

        $limite = max(1, (int)$limite);
        $offset = max(0, (int)$offset);

        $sql = "SELECT pedido.*, usuario.email
                FROM pedido
                JOIN usuario ON pedido.id_usuario = usuario.dni
                ORDER BY pedido.fecha_pedido DESC
                LIMIT ? OFFSET ?";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar el total de pedidos en la base de datos para paginación
    public static function contarTotal()
    {
        $db = Database::connect();
        return (int)$db->query("SELECT COUNT(*) FROM pedido")->fetchColumn();
    }

    // Actualizar el estado logístico de un pedido (pendiente, enviado, etc.)
    // OJO: este campo es "estado" (logística), distinto de "estado_pago"
    public static function actualizarEstado($idPedido, $estado)
    {
        $db = Database::connect();

        $idPedido = (int)$idPedido;
        $estado   = trim((string)$estado);

        if ($idPedido <= 0 || $estado === '') return false;

        $stmt = $db->prepare("UPDATE pedido SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $idPedido]);
    }

    // Eliminar un pedido y sus líneas dentro de una transacción
    public static function eliminar($idPedido)
    {
        $db = Database::connect();

        $idPedido = (int)$idPedido;
        if ($idPedido <= 0) return false;

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("DELETE FROM lineas_pedido WHERE id_pedido = ?");
            $stmt->execute([$idPedido]);

            $stmt = $db->prepare("DELETE FROM pedido WHERE id = ?");
            $stmt->execute([$idPedido]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            return false;
        }
    }

    // Obtener datos completos de un pedido para admin incluyendo usuario y dirección
    public static function obtenerPedidoCompleto($idPedido)
    {
        $db = Database::connect();

        $idPedido = (int)$idPedido;
        if ($idPedido <= 0) return null;

        $sql = "SELECT p.*,
                       u.nombre AS nombre_usuario,
                       u.email,
                       d.direccion,
                       d.ciudad,
                       d.provincia,
                       d.codigo_postal
                FROM pedido p
                JOIN usuario u ON p.id_usuario = u.dni
                JOIN direcciones d ON p.id_direccion_envio = d.id
                WHERE p.id = ?
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([$idPedido]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    //STRIPE,
    public static function guardarStripeIntent($idPedido, $intentId)
    {
        $db = Database::connect();

        $idPedido = (int)$idPedido;
        $intentId = trim((string)$intentId);

        if ($idPedido <= 0 || $intentId === '') return false;

        $sql = "UPDATE pedido
                SET stripe_payment_intent = ?, estado_pago = 'pendiente'
                WHERE id = ?";
        $stmt = $db->prepare($sql);

        return $stmt->execute([$intentId, $idPedido]);
    }

    public static function marcarPagadoPorIntent($intentId)
    {
        $db = Database::connect();

        $intentId = trim((string)$intentId);
        if ($intentId === '') return false;

        $sql = "UPDATE pedido
                SET estado_pago = 'pagado'
                WHERE stripe_payment_intent = ?
                LIMIT 1";
        $stmt = $db->prepare($sql);

        return $stmt->execute([$intentId]);
    }

    public static function obtenerPorIntent($intentId)
{
    $db = Database::connect();

    $intentId = trim((string)$intentId);
    if ($intentId === '') return null;

    $stmt = $db->prepare("SELECT * FROM pedido WHERE stripe_payment_intent = ? LIMIT 1");
    $stmt->execute([$intentId]);

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    return $fila ?: null;
}


}

