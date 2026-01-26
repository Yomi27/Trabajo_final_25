<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h2 class="h4 fw-bold mb-3">Editar libro</h2>

      <form method="POST" action="<?= BASE_URL ?>/admin/actualizarLibro" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int)($libro['id'] ?? 0) ?>">
        <input type="hidden" name="portada_actual" value="<?= htmlspecialchars($libro['portada'] ?? '') ?>">

        <div class="row g-3">

          <div class="col-12 col-md-6">
            <label for="titulo" class="form-label fw-semibold">Título</label>
            <input id="titulo" type="text" name="titulo" class="form-control"
                   value="<?= htmlspecialchars($libro['titulo'] ?? '') ?>" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="id_autor" class="form-label fw-semibold">Autor</label>
            <select id="id_autor" name="id_autor" class="form-select" required>
              <option value="">Selecciona un autor</option>
              <?php foreach ($autores as $autor): ?>
                <?php $idAutor = (int)($autor['id'] ?? 0); ?>
                <option value="<?= $idAutor ?>" <?= ($idAutor === (int)$autorActual) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($autor['nombre'] ?? '') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 col-md-6">
            <label for="id_categoria" class="form-label fw-semibold">Categoría</label>
            <select id="id_categoria" name="id_categoria" class="form-select" required>
              <option value="">Selecciona categoría</option>
              <?php foreach ($categorias as $cat): ?>
                <?php $idCat = (int)($cat['id'] ?? 0); ?>
                <option value="<?= $idCat ?>" <?= ($idCat === (int)($libro['id_categoria'] ?? 0)) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($cat['nombre'] ?? '') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 col-md-3">
            <label for="precio" class="form-label fw-semibold">Precio (€)</label>
            <input id="precio" type="number" step="0.01" min="0" name="precio"
                   class="form-control"
                   value="<?= htmlspecialchars($libro['precio'] ?? '') ?>" required>
          </div>

          <div class="col-12 col-md-3">
            <label for="stock" class="form-label fw-semibold">Stock</label>
            <input id="stock" type="number" min="0" name="stock"
                   class="form-control"
                   value="<?= htmlspecialchars($libro['stock'] ?? '') ?>" required>
          </div>

          <div class="col-12">
            <label for="sinopsis" class="form-label fw-semibold">Sinopsis</label>
            <textarea id="sinopsis" name="sinopsis" class="form-control" rows="5"><?= htmlspecialchars($libro['sinopsis'] ?? '') ?></textarea>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label fw-semibold">Portada actual</label>
            <div>
              <?php if (!empty($libro['portada'])): ?>
                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($libro['portada']) ?>"
                     class="img-thumbnail"
                     style="max-width: 160px;">
              <?php else: ?>
                <span class="text-muted">Sin portada</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <label for="portada" class="form-label fw-semibold">Cambiar portada</label>
            <input id="portada" type="file" name="portada" accept="image/*" class="form-control">
          </div>

        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
          <button class="btn btn-success" type="submit">Guardar cambios</button>
          <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/admin/libros">Cancelar</a>
        </div>
      </form>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
