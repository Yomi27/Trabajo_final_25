<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
$idPedido = (int)($_GET['id'] ?? 0);

if ($idPedido <= 0) {
  echo '<div class="container my-4"><div class="alert alert-danger mb-0">Pedido no válido</div></div>';
  require_once __DIR__ . '/../layout/footer.php';
  return;
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$returnUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . BASE_URL . '/index.php?url=pedido/confirmacion';
?>

<div class="container my-4" style="max-width: 720px;">
  <div class="card shadow-sm">
    <div class="card-body">

      <h2 class="h4 fw-bold mb-1">Pagar con tarjeta</h2>
      <p class="text-muted mb-3">Pedido: <span class="fw-semibold">#<?= (int)$idPedido ?></span></p>

      <form id="payment-form" onsubmit="return false;">
        <div id="payment-element" class="border rounded p-3"></div>

        <button id="submit" type="button" class="btn btn-success mt-3" disabled>
          Pagar
        </button>

        <div id="error-message" class="text-danger mt-3"></div>
      </form>

    </div>
  </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
(async () => {
  const idPedido = <?= (int)$idPedido ?>;

  const btn = document.getElementById("submit");
  const errorBox = document.getElementById("error-message");

  btn.disabled = true;
  errorBox.textContent = "";

  let res;
  try {
    res = await fetch("<?= BASE_URL ?>/stripe_intent.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id_pedido=" + encodeURIComponent(idPedido)
    });
  } catch (e) {
    errorBox.textContent = "No se pudo conectar con el servidor.";
    return;
  }

  let data;
  try {
    data = await res.json();
  } catch (e) {
    const txt = await res.text();
    errorBox.textContent = "El servidor no devolvió JSON: " + txt;
    return;
  }

  if (!res.ok || data.error) {
    errorBox.textContent = data.error || ("Error HTTP " + res.status);
    return;
  }

  if (!data.publishableKey || !data.clientSecret) {
    errorBox.textContent = "Faltan datos de Stripe (publishableKey/clientSecret).";
    return;
  }

  const stripe = Stripe(data.publishableKey);
  const elements = stripe.elements({ clientSecret: data.clientSecret });

  const paymentElement = elements.create("payment");
  paymentElement.mount("#payment-element");

  btn.disabled = false;

  btn.addEventListener("click", async () => {
    btn.disabled = true;
    errorBox.textContent = "";

    const returnUrl = window.location.origin + "<?= BASE_URL ?>" + "/index.php?url=pedido/confirmacion";

    const { error } = await stripe.confirmPayment({
      elements,
      confirmParams: { return_url: returnUrl }
    });

    if (error) {
      errorBox.textContent = error.message || "Error al confirmar el pago.";
      btn.disabled = false;
    }
  });
})();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
