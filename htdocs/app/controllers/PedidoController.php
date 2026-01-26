<?php
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../config/stripe.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class PedidoController{
    // Obtener un entero seguro desde GET con valor por defecto
    private function getInt(string $key, int $default = 0): int
   {
        return isset($_GET[$key]) ? (int)$_GET[$key] : $default;
    }

    // Listar pedidos del usuario logueado
    public function index(){
        Auth::requireLogin();

        $dni = (string)($_SESSION['usuario']['dni'] ?? '');
        $pedidos = Pedido::obtenerPedidosPorUsuario($dni);

        require_once __DIR__ . '/../views/pedidos/index.php';
    }

    // Mostrar detalle de un pedido
    public function ver(){
        Auth::requireLogin();

        $id = $this->getInt('id', 0);
        if ($id <= 0) {
            echo "Pedido no válido";
            return;
        }

        $pedido = Pedido::obtenerPedidoPorId($id);
        if (!$pedido) {
            echo "Pedido no encontrado";
            return;
        }

        $dni = (string)($_SESSION['usuario']['dni'] ?? '');
        if (($pedido['id_usuario'] ?? '') !== $dni) {
            echo "Acceso denegado";
            return;
        }

        $lineas = Pedido::obtenerLineasConLibros($id);

        require_once __DIR__ . '/../views/pedidos/ver.php';
    }

    public function checkout()
    {
        Auth::requireLogin();
        require_once __DIR__ . '/../views/pedidos/checkout.php';
    }

    //Funciona!
    public function crearIntentoPago()
    {
        header('Content-Type: application/json; charset=utf-8');
        ini_set('display_errors', 0);

        if (!isset($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            exit;
        }

        $idPedido = (int)($_POST['id_pedido'] ?? 0);
        if ($idPedido <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Pedido inválido']);
            exit;
        }

        $pedido = Pedido::obtenerPedidoPorId($idPedido);
        if (!$pedido) {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido no encontrado']);
            exit;
        }

        $dni = (string)($_SESSION['usuario']['dni'] ?? '');
        if (($pedido['id_usuario'] ?? '') !== $dni) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            exit;
        }

        $total = (float)($pedido['total'] ?? 0);
        if ($total <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Total inválido']);
            exit;
        }

        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        $amount = (int)round($total * 100);

        try {
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Stripe error: ' . $e->getMessage()]);
            exit;
        }

        Pedido::guardarStripeIntent($idPedido, $intent->id);

        echo json_encode([
            'clientSecret' => $intent->client_secret,
            'publishableKey' => STRIPE_PUBLISHABLE_KEY
        ]);
        exit;
    }

    public function confirmacion(){
        Auth::requireLogin();

        $mensaje = '—';

        $intentId = trim((string)($_GET['payment_intent'] ?? ''));
        if ($intentId === '') {
            $mensaje = 'No se recibió el identificador del pago.';
            require_once __DIR__ . '/../views/pedidos/confirmacion.php';
            return;
        }

        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

        try {
            $intent = \Stripe\PaymentIntent::retrieve($intentId);
        } catch (\Exception $e) {
            $mensaje = 'No se pudo verificar el pago con Stripe.';
            require_once __DIR__ . '/../views/pedidos/confirmacion.php';
            return;
        }

        $pedido = Pedido::obtenerPorIntent($intentId);
        if (!$pedido) {
            $mensaje = 'Pedido no encontrado para este pago.';
            require_once __DIR__ . '/../views/pedidos/confirmacion.php';
            return;
        }

        $dni = (string)($_SESSION['usuario']['dni'] ?? '');
        if (($pedido['id_usuario'] ?? '') !== $dni) {
            $mensaje = 'Acceso denegado.';
            require_once __DIR__ . '/../views/pedidos/confirmacion.php';
            return;
        }

        if ($intent->status === 'succeeded') {
            Pedido::marcarPagadoPorIntent($intentId);
            $mensaje = 'Pago realizado correctamente ✅';
        } else {
            $mensaje = 'El pago no se completó (estado: ' . $intent->status . ').';
        }

        require_once __DIR__ . '/../views/pedidos/confirmacion.php';
    }
}
?>
