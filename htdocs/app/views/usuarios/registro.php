<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-5" style="max-width: 560px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">

      <h2 class="h4 fw-bold mb-3">Registro</h2>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= BASE_URL ?>/index.php?url=usuario/guardarRegistro">

        <div class="mb-3">
          <label for="dni" class="form-label">DNI</label>
          <input id="dni" type="text" name="dni" class="form-control" required
                 value="<?= htmlspecialchars($_POST['dni'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre</label>
          <input id="nombre" type="text" name="nombre" class="form-control" required
                 value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" name="email" class="form-control" required
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input id="password" type="password" name="password" class="form-control" required>
        </div>

        <div class="d-flex gap-2 mt-3">
          <button type="submit" class="btn btn-primary">Registrarse</button>
          <a href="<?= BASE_URL ?>/usuario/login" class="btn btn-outline-secondary">Volver</a>
        </div>

      </form>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
