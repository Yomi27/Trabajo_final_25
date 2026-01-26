<?php
session_start();

ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

// Capturar fatals y excepciones y devolver JSON (para que el fetch no se quede vacío)
set_exception_handler(function($e){
  http_response_code(500);
  echo json_encode(['error' => 'EXCEPTION: ' . $e->getMessage()]);
  exit;
});

set_error_handler(function($severity, $message, $file, $line){
  http_response_code(500);
  echo json_encode(['error' => "ERROR: $message in $file on line $line"]);
  exit;
});

register_shutdown_function(function(){
  $err = error_get_last();
  if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
    http_response_code(500);
    echo json_encode(['error' => "FATAL: {$err['message']} in {$err['file']} on line {$err['line']}"]);
    exit;
  }
});

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/config/stripe.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/models/Pedido.php';

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

$intent = \Stripe\PaymentIntent::create([
  'amount' => $amount,
  'currency' => 'eur',
]);

// Guardar el intent en BD
Pedido::guardarStripeIntent($idPedido, $intent->id);

echo json_encode([
  'clientSecret' => $intent->client_secret,
  'publishableKey' => STRIPE_PUBLISHABLE_KEY
]);
exit;
