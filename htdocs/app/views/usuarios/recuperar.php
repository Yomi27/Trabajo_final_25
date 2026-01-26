<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-5" style="max-width: 520px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">

      <h2 class="h4 fw-bold mb-3">Recuperar contraseña</h2>

      <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($mensaje) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= BASE_URL ?>/usuario/procesarRecuperacion">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" name="email" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
          Recuperar contraseña
        </button>
      </form>

      <?php if (!empty($debugLink)): ?>
        <div class="alert alert-warning mt-4">
          <strong>Modo local (XAMPP):</strong><br>
          Usa este enlace para probar:
          <div class="mt-2">
            <a href="<?= htmlspecialchars($debugLink) ?>" class="link-break-all">
              <?= htmlspecialchars($debugLink) ?>
            </a>
          </div>
        </div>
      <?php endif; ?>

      <div class="mt-3">
        <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/usuario/login">
          ← Volver al login
        </a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
