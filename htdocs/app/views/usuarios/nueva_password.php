<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-5" style="max-width: 520px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">

      <h2 class="h4 fw-bold mb-3">Nueva contraseña</h2>

      <?php if (!empty($mensaje)): ?>
        <div class="alert alert-danger">
          <?= htmlspecialchars($mensaje) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= BASE_URL ?>/usuario/guardarNuevaPassword">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="mb-3">
          <label for="password" class="form-label">Nueva contraseña</label>
          <input id="password"
                 type="password"
                 name="password"
                 class="form-control"
                 required
                 minlength="6">
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Guardar contraseña</button>
        </div>
      </form>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
