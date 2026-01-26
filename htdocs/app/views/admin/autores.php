<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <h2 class="fw-bold mb-3">Gestión de autores</h2>

  <div class="card shadow-sm">
    <div class="card-body">

      <form method="GET" action="<?= BASE_URL ?>/index.php" class="row g-2 align-items-center mb-3">
        <input type="hidden" name="url" value="admin/autores">

        <div class="col-auto">
          <input type="text" name="buscar" class="form-control" placeholder="Buscar autor..."
                 value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
        </div>

        <div class="col-auto">
          <button type="submit" class="btn btn-dark">Buscar</button>
        </div>

        <div class="col-auto">
          <a href="<?= BASE_URL ?>/index.php?url=admin/autores" class="btn btn-outline-secondary">Limpiar</a>
        </div>
      </form>

      <h5 class="fw-bold mb-2">Nuevo autor</h5>

      <form method="POST" action="<?= BASE_URL ?>/admin/guardarAutor" class="row g-2 mb-3">
        <div class="col-auto flex-fill">
          <input type="text" name="nombre" class="form-control" placeholder="Nombre del autor..." required>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-dark">Añadir</button>
        </div>
      </form>

      <?php if (!empty($autorEditar)): ?>
        <div class="card border-warning mb-3">
          <div class="card-body">
            <h5 class="fw-bold">Editar autor</h5>

            <form method="POST" action="<?= BASE_URL ?>/index.php?url=admin/actualizarAutor" class="row g-2 align-items-center">
              <input type="hidden" name="id" value="<?= (int)($autorEditar['id'] ?? 0) ?>">

              <div class="col flex-fill">
                <input type="text" name="nombre" class="form-control"
                       value="<?= htmlspecialchars($autorEditar['nombre'] ?? '') ?>" required>
              </div>

              <div class="col-auto">
                <button type="submit" class="btn btn-success">Guardar</button>
              </div>

              <div class="col-auto">
                <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/index.php?url=admin/autores">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width:90px;">ID</th>
              <th>Nombre</th>
              <th style="width:200px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($autores)): ?>
              <?php foreach ($autores as $a): ?>
                <tr>
                  <td><?= (int)($a['id'] ?? 0) ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($a['nombre'] ?? '') ?></td>
                  <td>
                    <a class="btn btn-sm btn-outline-primary"
                       href="<?= BASE_URL ?>/index.php?url=admin/autores&editar=<?= (int)($a['id'] ?? 0) ?><?= !empty($_GET['buscar']) ? '&buscar=' . urlencode($_GET['buscar']) : '' ?>">
                      Editar
                    </a>

                    <a class="btn btn-sm btn-outline-danger ms-1"
                       href="<?= BASE_URL ?>/index.php?url=admin/eliminarAutor&id=<?= (int)($a['id'] ?? 0) ?>"
                       onclick="return confirm('¿Seguro que quieres eliminar este autor?');">
                      Eliminar
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="text-muted">No hay autores para mostrar.</td>
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
                 href="<?= BASE_URL ?>/index.php?url=admin/autores&page=<?= (int)$pagina - 1 ?><?= $qsBuscar ?>">
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
                 href="<?= BASE_URL ?>/index.php?url=admin/autores&page=<?= (int)$pagina + 1 ?><?= $qsBuscar ?>">
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
