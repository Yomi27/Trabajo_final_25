<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4" style="max-width: 720px;">
  <div class="card shadow-sm">
    <div class="card-body">

      <h2 class="h4 fw-bold mb-2">Resultado del pago</h2>

      <p class="text-muted mb-4"><?= htmlspecialchars($mensaje ?? '—') ?></p>

      <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/index.php?url=pedido">
        ← Volver a mis pedidos
      </a>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
