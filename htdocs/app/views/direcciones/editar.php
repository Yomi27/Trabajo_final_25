<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4" style="max-width: 720px;">
  <div class="card shadow-sm">
    <div class="card-body">

      <h2 class="h4 fw-bold mb-3">Editar dirección</h2>

      <form method="POST" action="<?= BASE_URL ?>/index.php?url=direccion/actualizar">
        <input type="hidden" name="id" value="<?= (int)($direccion['id'] ?? 0) ?>">

        <div class="row g-3">

          <div class="col-12">
            <label for="nombre" class="form-label fw-semibold">Nombre destinatario</label>
            <input id="nombre" type="text" name="nombre" class="form-control"
                   value="<?= htmlspecialchars($direccion['nombre_destinatario'] ?? '') ?>" required>
          </div>

          <div class="col-12">
            <label for="direccion" class="form-label fw-semibold">Dirección</label>
            <input id="direccion" type="text" name="direccion" class="form-control"
                   value="<?= htmlspecialchars($direccion['direccion'] ?? '') ?>" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="ciudad" class="form-label fw-semibold">Ciudad</label>
            <input id="ciudad" type="text" name="ciudad" class="form-control"
                   value="<?= htmlspecialchars($direccion['ciudad'] ?? '') ?>" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="provincia" class="form-label fw-semibold">Provincia</label>
            <input id="provincia" type="text" name="provincia" class="form-control"
                   value="<?= htmlspecialchars($direccion['provincia'] ?? '') ?>">
          </div>

          <div class="col-12 col-md-6">
            <label for="cp" class="form-label fw-semibold">Código postal</label>
            <input id="cp" type="text" name="cp" class="form-control"
                   value="<?= htmlspecialchars($direccion['codigo_postal'] ?? '') ?>">
          </div>

          <div class="col-12 col-md-6">
            <label for="pais" class="form-label fw-semibold">País</label>
            <input id="pais" type="text" name="pais" class="form-control"
                   value="<?= htmlspecialchars($direccion['pais'] ?? '') ?>">
          </div>

          <div class="col-12">
            <label for="telefono" class="form-label fw-semibold">Teléfono</label>
            <input id="telefono" type="text" name="telefono" class="form-control"
                   value="<?= htmlspecialchars($direccion['telefono'] ?? '') ?>">
          </div>

        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
          <button class="btn btn-success" type="submit">Guardar cambios</button>
          <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/index.php?url=direccion">Cancelar</a>
        </div>
      </form>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
