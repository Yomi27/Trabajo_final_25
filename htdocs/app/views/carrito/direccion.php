<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4" style="max-width: 720px;">

  <h2 class="fw-bold mb-3">Elige dirección de envío</h2>

  <form method="POST" action="<?= BASE_URL ?>/carrito/confirmar">
    
    <div class="list-group mb-3">

      <?php foreach ($direcciones as $d): ?>
        <label class="list-group-item d-flex gap-3 align-items-start">

          <input class="form-check-input mt-1" type="radio" name="direccion_id" value="<?= $d['id'] ?>" required>

          <div>
            <div class="fw-semibold"><?= htmlspecialchars($d['nombre_destinatario']) ?></div>

            <div class="text-muted small">
              <?= htmlspecialchars($d['direccion']) ?>,
              <?= htmlspecialchars($d['ciudad']) ?> (<?= htmlspecialchars($d['codigo_postal']) ?>),
              <?= htmlspecialchars($d['pais']) ?><br>
              <?= htmlspecialchars($d['telefono']) ?>
            </div>
          </div>

        </label>
      <?php endforeach; ?>

    </div>

    <div class="d-flex flex-wrap gap-2">
      <button type="submit" class="btn btn-success">
        Confirmar pedido
      </button>

      <a href="<?= BASE_URL ?>/index.php?url=carrito" class="btn btn-outline-secondary">
        ← Volver al carrito
      </a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
