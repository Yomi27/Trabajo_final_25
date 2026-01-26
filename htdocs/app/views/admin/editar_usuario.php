<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h2 class="h4 fw-bold mb-3">Editar usuario</h2>

      <form method="POST" action="<?= BASE_URL ?>/index.php?url=admin/actualizarUsuario">
        <input type="hidden" name="dni" value="<?= htmlspecialchars($usuario['dni'] ?? '') ?>">

        <div class="row g-3">

          <div class="col-12 col-md-6">
            <label class="form-label fw-semibold">DNI</label>
            <input type="text" class="form-control"
                   value="<?= htmlspecialchars($usuario['dni'] ?? '') ?>" disabled>
          </div>

          <div class="col-12 col-md-6">
            <label for="nombre" class="form-label fw-semibold">Nombre</label>
            <input id="nombre" type="text" name="nombre" class="form-control"
                   value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input id="email" type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="rol" class="form-label fw-semibold">Rol</label>
            <select id="rol" name="rol" class="form-select">
              <?php $rolActual = (string)($usuario['rol'] ?? 'usuario'); ?>
              <option value="usuario"  <?= $rolActual === 'usuario' ? 'selected' : '' ?>>Usuario</option>
              <option value="empleado" <?= $rolActual === 'empleado' ? 'selected' : '' ?>>Empleado</option>
              <option value="admin"    <?= $rolActual === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
          </div>

          <div class="col-12 col-md-6">
            <label for="activo" class="form-label fw-semibold">Activo</label>
            <select id="activo" name="activo" class="form-select">
              <option value="1" <?= !empty($usuario['activo']) ? 'selected' : '' ?>>Sí</option>
              <option value="0" <?= empty($usuario['activo']) ? 'selected' : '' ?>>No</option>
            </select>
          </div>

        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
          <button class="btn btn-success" type="submit">Guardar cambios</button>
          <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/index.php?url=admin/usuarios">Cancelar</a>
        </div>
      </form>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
