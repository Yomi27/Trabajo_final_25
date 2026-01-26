<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-5" style="max-width: 520px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">

      <h2 class="h4 fw-bold mb-3">Login</h2>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= BASE_URL ?>/index.php?url=usuario/validarLogin">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input id="password" type="password" name="password" class="form-control" required>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
          <button class="btn btn-primary" type="submit">Entrar</button>
          <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/libro">Volver</a>
        </div>
      </form>

      <div class="mt-3">
        <a class="link-secondary text-decoration-none"
           href="<?= BASE_URL ?>/index.php?url=usuario/recuperar">
          ¿Has olvidado tu contraseña?
        </a>
        <p class="mt-3"> ¿No tienes cuenta?
        <a href="<?= BASE_URL ?>/usuario/registro">Regístrate aquí</a></p>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
