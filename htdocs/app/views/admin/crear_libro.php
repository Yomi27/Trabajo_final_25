<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h2 class="h4 fw-bold mb-3">Nuevo libro</h2>

      <form method="POST" action="<?= BASE_URL ?>/admin/guardarLibro" enctype="multipart/form-data">
        <div class="row g-3">

          <div class="col-12 col-md-6">
            <label for="titulo" class="form-label fw-semibold">Título</label>
            <input id="titulo" type="text" name="titulo" class="form-control" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="id_autor" class="form-label fw-semibold">Autor</label>
            <select id="id_autor" name="id_autor" class="form-select" required>
              <option value="">Selecciona un autor</option>
              <?php if (!empty($autores)): ?>
                <?php foreach ($autores as $autor): ?>
                  <option value="<?= (int)($autor['id'] ?? 0) ?>">
                    <?= htmlspecialchars($autor['nombre'] ?? '') ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <div class="col-12">
            <label for="sinopsis" class="form-label fw-semibold">Sinopsis</label>
            <textarea id="sinopsis" name="sinopsis" class="form-control" rows="5"></textarea>
          </div>

          <div class="col-12 col-md-6">
            <label for="portada" class="form-label fw-semibold">Portada</label>
            <input id="portada" type="file" name="portada" accept="image/*" class="form-control">
          </div>

          <div class="col-12 col-md-3">
            <label for="precio" class="form-label fw-semibold">Precio (€)</label>
            <input id="precio" type="number" name="precio" step="0.01" min="0" class="form-control" required>
          </div>

          <div class="col-12 col-md-3">
            <label for="stock" class="form-label fw-semibold">Stock</label>
            <input id="stock" type="number" name="stock" min="0" class="form-control" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="id_categoria" class="form-label fw-semibold">Categoría</label>
            <select id="id_categoria" name="id_categoria" class="form-select" required>
              <option value="">Selecciona categoría</option>
              <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                  <option value="<?= (int)($cat['id'] ?? 0) ?>">
                    <?= htmlspecialchars($cat['nombre'] ?? '') ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
          <button class="btn btn-success" type="submit">Guardar libro</button>
          <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/admin/libros">Cancelar</a>
        </div>
      </form>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
