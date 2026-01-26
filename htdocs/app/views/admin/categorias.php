<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <h2 class="fw-bold mb-3">Gestión de categorías</h2>

  <div class="card shadow-sm">
    <div class="card-body">

      <h5 class="fw-bold mb-2">Nueva categoría</h5>
      <form method="POST" action="<?= BASE_URL ?>/admin/crearCategoria" class="row g-2 mb-3">
        <div class="col flex-fill">
          <input type="text" name="nombre" class="form-control" placeholder="Nombre de la categoría..." required>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-dark">Añadir</button>
        </div>
      </form>

      <?php if (!empty($categoriaEditar)): ?>
        <div class="card border-warning mb-3">
          <div class="card-body">
            <h5 class="fw-bold">Editar categoría</h5>

            <form method="POST" action="<?= BASE_URL ?>/admin/actualizarCategoria" class="row g-2 align-items-center">
              <input type="hidden" name="id" value="<?= (int)($categoriaEditar['id'] ?? 0) ?>">

              <div class="col flex-fill">
                <input type="text" name="nombre" class="form-control"
                       value="<?= htmlspecialchars($categoriaEditar['nombre'] ?? '') ?>" required>
              </div>

              <div class="col-auto">
                <button type="submit" class="btn btn-success">Guardar</button>
              </div>

              <div class="col-auto">
                <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/admin/categorias">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      <?php endif; ?>

      <h5 class="fw-bold mb-2">Listado de categorías</h5>

      <div class="table-responsive">
        <table class="table table-hover align-middle" style="min-width: 860px;">
          <thead class="table-light">
            <tr>
              <th style="width:90px;">ID</th>
              <th>Nombre</th>
              <th class="text-center" style="width:110px;">Libros</th>
              <th style="width:160px;">Visible en barra</th>
              <th style="width:240px;">Acciones</th>
              <th style="width:140px;">Eliminar</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($categorias)): ?>
              <?php foreach ($categorias as $c): ?>
                <?php
                  $idCat = (int)($c['id'] ?? 0);
                  $visible = !empty($c['visible']);
                  $totalLibros = (int)($c['total_libros'] ?? 0);
                  $qsBuscar = !empty($_GET['buscar']) ? '&buscar=' . urlencode($_GET['buscar']) : '';
                ?>

                <tr>
                  <td><?= $idCat ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($c['nombre'] ?? '') ?></td>

                  <td class="text-center fw-bold">
                    <?= $totalLibros ?>
                  </td>

                  <td>
                    <?php if ($visible): ?>
                      <span class="badge text-bg-success">Sí</span>
                    <?php else: ?>
                      <span class="badge text-bg-secondary">No</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <a class="btn btn-sm btn-outline-primary"
                       href="<?= BASE_URL ?>/index.php?url=admin/categorias&editar=<?= $idCat ?><?= $qsBuscar ?>">
                      Editar
                    </a>

                    <?php if ($visible): ?>
                      <a class="btn btn-sm btn-outline-secondary ms-1"
                         href="<?= BASE_URL ?>/index.php?url=admin/cambiarVisibleCategoria&id=<?= $idCat ?>&visible=0"
                         onclick="return confirm('¿Ocultar esta categoría del menú lateral?');">
                        Ocultar
                      </a>
                    <?php else: ?>
                      <a class="btn btn-sm btn-outline-secondary ms-1"
                         href="<?= BASE_URL ?>/index.php?url=admin/cambiarVisibleCategoria&id=<?= $idCat ?>&visible=1"
                         onclick="return confirm('¿Mostrar esta categoría en el menú lateral?');">
                        Mostrar
                      </a>
                    <?php endif; ?>
                  </td>

                  <td>
                    <a class="btn btn-sm btn-outline-danger"
                       href="<?= BASE_URL ?>/index.php?url=admin/eliminarCategoria&id=<?= $idCat ?>"
                       onclick="return confirm('¿Eliminar categoría?');">
                      Eliminar
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-muted">No hay categorías para mostrar.</td>
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
                 href="<?= BASE_URL ?>/index.php?url=admin/categorias&page=<?= (int)$pagina - 1 ?><?= $qsBuscar ?>">
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
                 href="<?= BASE_URL ?>/index.php?url=admin/categorias&page=<?= (int)$pagina + 1 ?><?= $qsBuscar ?>">
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
