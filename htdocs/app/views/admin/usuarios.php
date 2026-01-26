<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <h2 class="fw-bold mb-3">Gestión de usuarios</h2>

  <div class="card shadow-sm">
    <div class="card-body">

      <div class="table-responsive">
        <table class="table table-hover align-middle" style="min-width: 980px;">
          <thead class="table-light">
            <tr>
              <th style="width:130px;">DNI</th>
              <th>Nombre</th>
              <th>Email</th>
              <th style="width:120px;">Rol</th>
              <th style="width:90px;">Activo</th>
              <th style="width:320px;">Cambiar rol</th>
              <th style="width:180px;">Acciones</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($usuarios)): ?>
              <?php foreach ($usuarios as $u): ?>
                <?php
                  $dni = (string)($u['dni'] ?? '');
                  $rol = (string)($u['rol'] ?? 'usuario');
                  $activo = !empty($u['activo']);

                  $rolMap = [
                    'admin'   => 'text-bg-danger',
                    'empleado'=> 'text-bg-info',
                    'usuario' => 'text-bg-secondary',
                  ];
                  $rolBadge = $rolMap[$rol] ?? 'text-bg-secondary';
                ?>

                <tr>
                  <td class="fw-semibold"><?= htmlspecialchars($dni) ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($u['nombre'] ?? '') ?></td>
                  <td><?= htmlspecialchars($u['email'] ?? '') ?></td>

                  <td>
                    <span class="badge <?= $rolBadge ?>"><?= htmlspecialchars($rol) ?></span>
                  </td>

                  <td>
                    <?php if ($activo): ?>
                      <span class="badge text-bg-success">Sí</span>
                    <?php else: ?>
                      <span class="badge text-bg-secondary">No</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <?php if ($dni !== ''): ?>
                      <div class="btn-group btn-group-sm" role="group" aria-label="Cambiar rol">
                        <?php if ($rol !== 'admin'): ?>
                          <a class="btn btn-outline-danger"
                             href="<?= BASE_URL ?>/index.php?url=admin/cambiarRol&dni=<?= urlencode($dni) ?>&rol=admin">
                            Hacer admin
                          </a>
                        <?php else: ?>
                          <button class="btn btn-outline-danger" disabled>Admin</button>
                        <?php endif; ?>

                        <?php if ($rol !== 'empleado'): ?>
                          <a class="btn btn-outline-primary"
                             href="<?= BASE_URL ?>/index.php?url=admin/cambiarRol&dni=<?= urlencode($dni) ?>&rol=empleado">
                            Hacer empleado
                          </a>
                        <?php else: ?>
                          <button class="btn btn-outline-primary" disabled>Empleado</button>
                        <?php endif; ?>

                        <?php if ($rol !== 'usuario'): ?>
                          <a class="btn btn-outline-secondary"
                             href="<?= BASE_URL ?>/index.php?url=admin/cambiarRol&dni=<?= urlencode($dni) ?>&rol=usuario">
                            Hacer usuario
                          </a>
                        <?php else: ?>
                          <button class="btn btn-outline-secondary" disabled>Usuario</button>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </td>

                  <td>
                    <?php if ($dni !== ''): ?>
                      <a class="btn btn-sm btn-outline-primary"
                         href="<?= BASE_URL ?>/index.php?url=admin/editarUsuario&dni=<?= urlencode($dni) ?>">
                        Editar
                      </a>

                      <a class="btn btn-sm btn-outline-danger ms-1"
                         href="<?= BASE_URL ?>/index.php?url=admin/eliminarUsuario&dni=<?= urlencode($dni) ?>"
                         onclick="return confirm('¿Seguro que quieres eliminar este usuario?');">
                        Desactivar
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>

              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-muted">No hay usuarios para mostrar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if (!empty($totalPaginas) && $totalPaginas > 1): ?>
        <?php $paginaActual = (int)($pagina ?? 1); ?>

        <nav class="mt-3">
          <ul class="pagination justify-content-center flex-wrap mb-0">

            <?php if ($paginaActual > 1): ?>
              <li class="page-item">
                <a class="page-link" href="<?= BASE_URL ?>/index.php?url=admin/usuarios&page=<?= $paginaActual - 1 ?>">⬅ Anterior</a>
              </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= (int)$totalPaginas; $i++): ?>
              <?php if ($i === $paginaActual): ?>
                <li class="page-item active" aria-current="page">
                  <span class="page-link"><?= $i ?></span>
                </li>
              <?php else: ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>/index.php?url=admin/usuarios&page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endif; ?>
            <?php endfor; ?>

            <?php if ($paginaActual < (int)$totalPaginas): ?>
              <li class="page-item">
                <a class="page-link" href="<?= BASE_URL ?>/index.php?url=admin/usuarios&page=<?= $paginaActual + 1 ?>">Siguiente ➡</a>
              </li>
            <?php endif; ?>

          </ul>
        </nav>
      <?php endif; ?>

      <div class="mt-3">
        <a class="btn btn-link text-decoration-none" href="<?= BASE_URL ?>/admin">Volver al panel</a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
