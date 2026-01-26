<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="order-success">
  <h2 class="order-success__title">Pedido realizado correctamente</h2>

  <p class="order-success__text">
    Gracias por tu compra. Tu pedido ha sido guardado.
  </p>

  <a class="order-success__btn" href="<?= BASE_URL ?>/libro">
    Seguir comprando
  </a>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
