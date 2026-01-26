<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
  $idLibro = (int)($libro['id'] ?? 0);
  $stock   = (int)($libro['stock'] ?? 0);
  $precio  = (float)($libro['precio'] ?? 0);

  $stockBadge = ($stock > 0) ? 'text-bg-success' : 'text-bg-danger';
  $stockText  = ($stock > 0) ? ($stock . ' uds') : 'Sin stock';
?>

<div class="container my-4" style="max-width: 980px;">

 <header class="card shadow-sm mb-3">
    <div class="card-body d-flex align-items-center">
        <img src="<?= BASE_URL ?>/css/logoNegro.png" alt="Logo Librería" class="logo-navbar me-4" width="250px">
        <div>
          <h2 class="h3 fw-bold mb-1"><?= htmlspecialchars($libro['titulo'] ?? '') ?></h2>
          <p class="text-muted mb-0">
            <?= htmlspecialchars($libro['autor_nombre'] ?? 'Desconocido') ?> ·
            <?= htmlspecialchars($libro['categoria_nombre'] ?? 'Sin categoría') ?>
          </p>
        </div>
    </div>
</header>

  <div class="card shadow-sm">
    <div class="card-body">

      <div class="row g-3 align-items-start">
        <div class="col-12 col-md-4">
          <?php if (!empty($libro['portada'])): ?>
            <img
              src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($libro['portada']) ?>"
              alt="Portada"
              class="img-fluid rounded border"
              style="aspect-ratio: 3 / 4; object-fit: cover;"
            >
          <?php else: ?>
            <div class="d-flex align-items-center justify-content-center text-muted border rounded"
                 style="aspect-ratio: 3 / 4; background:#f3f4f6;">
              Sin imagen
            </div>
          <?php endif; ?>
        </div>

        <div class="col-12 col-md-8">
          <div class="row g-2">

            <div class="col-12 col-sm-6">
              <div class="text-muted small">Autor</div>
              <div class="fw-semibold"><?= htmlspecialchars($libro['autor_nombre'] ?? 'Desconocido') ?></div>
            </div>

            <div class="col-12 col-sm-6">
              <div class="text-muted small">Categoría</div>
              <div class="fw-semibold"><?= htmlspecialchars($libro['categoria_nombre'] ?? 'Sin categoría') ?></div>
            </div>

            <div class="col-12 col-sm-6 mt-2">
              <div class="text-muted small">Precio</div>
              <div class="fw-bold fs-5"><?= number_format($precio, 2) ?> €</div>
            </div>

            <div class="col-12 col-sm-6 mt-2">
              <div class="text-muted small">Stock</div>
              <div>
                <span class="badge <?= $stockBadge ?>"><?= $stockText ?></span>
              </div>
            </div>

          </div>

          <div class="mt-3 d-flex flex-wrap gap-2">
            <?php if ($stock > 0): ?>
              <form method="POST" action="<?= BASE_URL ?>/carrito/agregar" class="m-0">
                <input type="hidden" name="id_libro" value="<?= $idLibro ?>">
                <button type="submit" class="btn btn-dark">Añadir al carrito</button>
              </form>
            <?php else: ?>
              <button class="btn btn-secondary" disabled>Sin stock</button>
            <?php endif; ?>

            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/libro">← Volver al catálogo</a>
          </div>
        </div>
      </div>

      <hr class="my-4">

      <h3 class="h5 fw-bold mb-2">Sinopsis</h3>
      <p class="text-body-secondary mb-0">
        <?= nl2br(htmlspecialchars($libro['sinopsis'] ?? 'Sin sinopsis disponible')) ?>
      </p>

    </div>
  </div>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
