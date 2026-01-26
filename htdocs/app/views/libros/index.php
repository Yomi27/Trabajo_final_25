<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
  $buscar = trim((string)($_GET['buscar'] ?? ''));

  $orden = (string)($_GET['orden'] ?? '');
  $categoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

  $paginaActual = (int)($pagina ?? 1);
?>

<header class="card shadow-sm mb-3">
    <div class="card-body d-flex align-items-center">
        
        <img src="<?= BASE_URL ?>/css/logoNegro.png" alt="Logo Librería" class="logo-navbar me-4" width="250px">
        <div> 
          <h2 class="h3 fw-bold mb-1">Catálogo</h2>
          <p class="text-muted mb-0">Explora nuestros libros</p>
        </div>
    </div>
</header>

<?php if (empty($libros)): ?>
  <div class="card shadow-sm">
    <div class="card-body text-muted">
      No se encontraron libros.
    </div>
  </div>
<?php else: ?>

  <form class="d-flex flex-wrap align-items-center gap-2 mb-3" method="GET">
  <label class="form-label mb-0 fw-semibold">Ordenar por:</label>

  <select name="orden" class="form-select w-auto" onchange="this.form.submit()">
    <option value="">-- Sin orden --</option>
    <option value="novedades" <?= $orden === 'novedades' ? 'selected' : '' ?>>Novedades</option>
    <option value="precio_asc" <?= $orden === 'precio_asc' ? 'selected' : '' ?>>Precio más bajo</option>
    <option value="precio_desc" <?= $orden === 'precio_desc' ? 'selected' : '' ?>>Precio más alto</option>
  </select>

  <?php if ($buscar !== ''): ?>
    <input type="hidden" name="buscar" value="<?= htmlspecialchars($buscar) ?>">
  <?php endif; ?>

  <?php if ($categoria > 0): ?>
    <input type="hidden" name="categoria" value="<?= $categoria ?>">
  <?php endif; ?>

  <?php if (!empty($_GET['page'])): ?>
    <input type="hidden" name="page" value="<?= (int)$_GET['page'] ?>">
  <?php endif; ?>
</form>


  <div class="list-group shadow-sm">
    <?php foreach ($libros as $libro): ?>
      <?php
        $idLibro = (int)($libro['id'] ?? 0);
        $stock   = (int)($libro['stock'] ?? 0);
        $precio  = (float)($libro['precio'] ?? 0);

        if ($stock <= 0) {
          $badgeClass = 'text-bg-danger';
          $badgeText  = 'Sin stock';
        } elseif ($stock <= 3) {
          $badgeClass = 'text-bg-warning';
          $badgeText  = 'Últimas unidades';
        } else {
          $badgeClass = 'text-bg-success';
          $badgeText  = 'Disponible';
        }
      ?>

      <div class="list-group-item p-3">
        <div class="row g-3 align-items-start">

          <div class="col-12 col-md-auto">
            <div class="book-cover">
              <?php if (!empty($libro['portada'])): ?>
                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($libro['portada']) ?>" alt="Portada">
              <?php else: ?>
                <div class="book-cover--empty">Sin portada</div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col">
            <h3 class="h5 mb-1">
              <a class="link-dark text-decoration-none"
                 href="<?= BASE_URL ?>/libro/ver&id=<?= $idLibro ?>">
                <?= htmlspecialchars($libro['titulo'] ?? '') ?>
              </a>
            </h3>

            <div class="text-muted small mb-2">
              <strong>Autor:</strong> <?= htmlspecialchars($libro['autor_nombre'] ?? 'Sin autor') ?>
              · <strong>Categoría:</strong> <?= htmlspecialchars($libro['categoria_nombre'] ?? 'Sin categoría') ?>
            </div>

            <?php if (!empty($libro['sinopsis'])): ?>
              <p class="small text-body-secondary mb-2 book-synopsis">
                <?= nl2br(htmlspecialchars($libro['sinopsis'])) ?>
              </p>
            <?php endif; ?>

            <div class="d-flex flex-wrap align-items-center gap-2">
              <span class="fw-bold price"><?= number_format($precio, 2) ?> €</span>
              <span class="badge <?= $badgeClass ?>"><?= $badgeText ?></span>

              <div class="ms-auto">
                <?php if ($stock > 0): ?>
                  <form method="POST" action="<?= BASE_URL ?>/carrito/agregar" class="m-0">
                    <input type="hidden" name="id_libro" value="<?= $idLibro ?>">
                    <button type="submit" class="btn btn-dark btn-sm">Añadir al carrito</button>
                  </form>
                <?php else: ?>
                  <button class="btn btn-secondary btn-sm" disabled>Sin stock</button>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div>
      </div>
    <?php endforeach; ?>
  </div>

<?php endif; ?>

<?php if (!empty($totalPaginas) && (int)$totalPaginas > 1): ?>
  <?php
    $params = [];
    if ($categoria > 0) $params['categoria'] = $categoria;
    if ($orden !== '') $params['orden'] = $orden;
    if ($buscar !== '') $params['buscar'] = $buscar;

    $baseQuery = http_build_query($params);
    $baseQuery = $baseQuery ? ($baseQuery . '&') : '';
  ?>

  <nav class="mt-4">
    <ul class="pagination justify-content-center flex-wrap">

      <?php if ($paginaActual > 1): ?>
        <li class="page-item">
          <a class="page-link"
             href="<?= BASE_URL ?>/libro?<?= $baseQuery ?>page=<?= $paginaActual - 1 ?>">
            ⬅ Anterior
          </a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= (int)$totalPaginas; $i++): ?>
        <?php if ($i === $paginaActual): ?>
          <li class="page-item active" aria-current="page">
            <span class="page-link"><?= $i ?></span>
          </li>
        <?php else: ?>
          <li class="page-item">
            <a class="page-link"
               href="<?= BASE_URL ?>/libro?<?= $baseQuery ?>page=<?= $i ?>">
              <?= $i ?>
            </a>
          </li>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($paginaActual < (int)$totalPaginas): ?>
        <li class="page-item">
          <a class="page-link"
             href="<?= BASE_URL ?>/libro?<?= $baseQuery ?>page=<?= $paginaActual + 1 ?>">
            Siguiente ➡
          </a>
        </li>
      <?php endif; ?>

    </ul>
  </nav>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
