<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4" style="max-width: 760px;">
  <div class="card shadow-sm">
    <div class="card-body">

      <h2 class="h4 fw-bold mb-3">Mis datos</h2>

      <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($mensaje) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= BASE_URL ?>/index.php?url=usuario/actualizar">

        <div class="row g-3">

          <div class="col-12">
            <label class="form-label">DNI</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['dni'] ?? '') ?>" disabled>
          </div>

          <div class="col-12">
            <label for="password_actual" class="form-label">Contraseña actual</label>
            <input id="password_actual" type="password" name="password_actual" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre</label>
            <input id="nombre" type="text" name="nombre"
                   value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"
                   class="form-control" required>
          </div>

          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email"
                   value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                   class="form-control" required>
          </div>

          <div class="col-12">
            <label for="password" class="form-label">Nueva contraseña (opcional)</label>
            <input id="password" type="password" name="password" class="form-control">
          </div>

        </div>

        <div class="d-flex gap-2 mt-4">
          <button class="btn btn-primary" type="submit">Guardar cambios</button>
          <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/index.php?url=perfil/index">Cancelar</a>
        </div>

      </form>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>