<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h2 class="fw-bold mb-0">Gestión de libros</h2>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">

      <form method="GET" action="<?= BASE_URL ?>/index.php" class="row g-2 align-items-center mb-3">
        <input type="hidden" name="url" value="admin/libros">

        <div class="col-12 col-lg">
          <input type="text" name="buscar" class="form-control"
                 placeholder="Buscar por título, autor o categoría..."
                 value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
        </div>

        <div class="col-12 col-lg-auto">
          <button type="submit" class="btn btn-dark w-100">Buscar</button>
        </div>

        <div class="col-12 col-lg-auto">
          <a href="<?= BASE_URL ?>/index.php?url=admin/libros" class="btn btn-outline-secondary w-100">Limpiar</a>
        </div>
      </form>

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <h5 class="fw-bold mb-0">Listado de libros</h5>
        <a href="<?= BASE_URL ?>/admin/crearLibro" class="btn btn-success">Añadir libro</a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width:70px;">ID</th>
              <th style="width:90px;">Portada</th>
              <th>Título</th>
              <th>Autor</th>
              <th>Categoría</th>
              <th style="min-width:260px;">Sinopsis</th>
              <th class="text-end" style="width:120px;">Precio</th>
              <th class="text-center" style="width:90px;">Stock</th>
              <th style="width:110px;">Estado</th>
              <th style="width:210px;">Acciones</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($libros)): ?>
              <?php foreach ($libros as $l): ?>
                <?php
                  $idLibro = (int)($l['id'] ?? 0);
                  $activo  = (int)($l['activo'] ?? 1) === 1;

                  $sinopsis = (string)($l['sinopsis'] ?? '');
                  $recorte  = mb_substr($sinopsis, 0, 150);
                ?>

                <tr>
                  <td><?= $idLibro ?></td>

                  <td>
                    <?php if (!empty($l['portada'])): ?>
                      <img
                        src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($l['portada']) ?>"
                        alt="Portada"
                        class="img-thumbnail"
                        style="width:70px; height:90px; object-fit:cover;"
                      >
                    <?php else: ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>

                  <td class="fw-semibold"><?= htmlspecialchars($l['titulo'] ?? '') ?></td>
                  <td><?= htmlspecialchars($l['autor_nombre'] ?? 'Sin autor') ?></td>
                  <td><?= htmlspecialchars($l['categoria_nombre'] ?? 'Sin categoría') ?></td>

                  <td class="small text-body-secondary">
                    <?= nl2br(htmlspecialchars($recorte)) ?><?= (mb_strlen($sinopsis) > 150) ? '...' : '' ?>
                  </td>

                  <td class="text-end fw-bold"><?= number_format((float)($l['precio'] ?? 0), 2) ?> €</td>
                  <td class="text-center fw-bold"><?= (int)($l['stock'] ?? 0) ?></td>

                  <td>
                    <?php if ($activo): ?>
                      <span class="badge text-bg-success">Activo</span>
                    <?php else: ?>
                      <span class="badge text-bg-secondary">Inactivo</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <a class="btn btn-sm btn-outline-primary"
                       href="<?= BASE_URL ?>/admin/editarLibro?id=<?= $idLibro ?>">Editar</a>

                    <?php if ($activo): ?>
                      <a class="btn btn-sm btn-outline-danger ms-1"
                         href="<?= BASE_URL ?>/admin/eliminarLibro?id=<?= $idLibro ?>"
                         onclick="return confirm('¿Seguro que quieres desactivar este libro?');">
                        Desactivar
                      </a>
                    <?php else: ?>
                      <a class="btn btn-sm btn-outline-secondary ms-1"
                         href="<?= BASE_URL ?>/admin/activarLibro?id=<?= $idLibro ?>"
                         onclick="return confirm('¿Seguro que quieres activar este libro?');">
                        Activar
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-muted">No hay libros para mostrar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if (!empty($totalPaginas) && $totalPaginas > 1): ?>
        <?php $qsBuscar = !empty($_GET['buscar']) ? '&buscar=' . urlencode($_GET['buscar']) : ''; ?>

        <nav class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
          <div>
            <?php if (!empty($pagina) && $pagina > 1): ?>
              <a class="btn btn-outline-secondary btn-sm"
                 href="<?= BASE_URL ?>/index.php?url=admin/libros&page=<?= (int)$pagina - 1 ?><?= $qsBuscar ?>">
                ← Anterior
              </a>
            <?php endif; ?>
          </div>

          <span class="text-muted small">
            Página <?= (int)($pagina ?? 1) ?> de <?= (int)$totalPaginas ?>
          </span>

          <div>
            <?php if (!empty($pagina) && $pagina < $totalPaginas): ?>
              <a class="btn btn-outline-secondary btn-sm"
                 href="<?= BASE_URL ?>/index.php?url=admin/libros&page=<?= (int)$pagina + 1 ?><?= $qsBuscar ?>">
                Siguiente →
              </a>
            <?php endif; ?>
          </div>
        </nav>
      <?php endif; ?>

      <div class="mt-3">
        <a class="btn btn-link text-decoration-none" href="<?= BASE_URL ?>/admin">← Volver al panel</a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
