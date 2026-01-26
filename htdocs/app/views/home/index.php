<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section class="container my-4">

<header class="card shadow-sm mb-3">
    <div class="card-body d-flex align-items-center">
        
        <img src="<?= BASE_URL ?>/css/logoNegro.png" alt="Logo Librería" class="logo-navbar me-4" width="250px">
        <div> 
          <h2 class="h3 fw-bold mb-1">Novedades</h2>
          <p class="text-muted mb-0">Últimos libros añadidos</p>
        </div>
    </div>
</header>

  <?php if (!empty($novedades)): ?>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
      <?php foreach ($novedades as $libro): ?>
        <?php
          $idLibro = (int)($libro['id'] ?? 0);
          $titulo  = (string)($libro['titulo'] ?? '');
          $autor   = (string)($libro['autor_nombre'] ?? '');
          $precio  = (float)($libro['precio'] ?? 0);
          $portada = (string)($libro['portada'] ?? '');
        ?>

        <div class="col">
          <article class="card h-100 shadow-sm">

            <a href="<?= BASE_URL ?>/libro/ver&id=<?= $idLibro ?>" class="text-decoration-none">
              <?php if (!empty($portada)): ?>
                <img
                  src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($portada) ?>"
                  class="card-img-top"
                  alt="Portada de <?= htmlspecialchars($titulo ?: 'libro') ?>"
                  style="aspect-ratio: 3 / 4; object-fit: cover;"
                >
              <?php else: ?>
                <div
                  class="d-flex align-items-center justify-content-center text-muted border-bottom"
                  style="aspect-ratio: 3 / 4; background:#f3f4f6;"
                >
                  Sin portada
                </div>
              <?php endif; ?>
            </a>

            <div class="card-body d-flex flex-column">
              <h3 class="h6 fw-bold mb-1">
                <a class="link-dark text-decoration-none"
                   href="<?= BASE_URL ?>/libro/ver&id=<?= $idLibro ?>">
                  <?= htmlspecialchars($titulo) ?>
                </a>
              </h3>

              <?php if ($autor !== ''): ?>
                <p class="text-muted small mb-3"><?= htmlspecialchars($autor) ?></p>
              <?php else: ?>
                <p class="text-muted small mb-3">&nbsp;</p>
              <?php endif; ?>

              <div class="mt-auto d-flex align-items-center justify-content-between gap-2">
                <span class="fw-bold"><?= number_format($precio, 2) ?> €</span>

                <form method="POST" action="<?= BASE_URL ?>/carrito/agregar" class="m-0">
                  <input type="hidden" name="id_libro" value="<?= $idLibro ?>">
                  <button type="submit" class="btn btn-dark btn-sm">Añadir</button>
                </form>
              </div>
            </div>

          </article>
        </div>

      <?php endforeach; ?>
    </div>

  <?php else: ?>

    <div class="card shadow-sm">
      <div class="card-body text-muted">
        No hay novedades para mostrar.
      </div>
    </div>

  <?php endif; ?>

</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
