<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <h2 class="fw-bold mb-3">Gestión de pedidos</h2>

  <div class="card shadow-sm">
    <div class="card-body">

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width:80px;">ID</th>
              <th>Usuario</th>
              <th style="width:160px;">Fecha</th>
              <th class="text-end" style="width:120px;">Total</th>
              <th style="width:120px;">Pago</th>
              <th style="width:140px;">Estado actual</th>
              <th style="width:240px;">Cambiar estado</th>
              <th style="width:200px;">Acciones</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($pedidos)): ?>
              <?php foreach ($pedidos as $p): ?>
                <?php
                  $idPedido = (int)($p['id'] ?? 0);
                  $estadoActual = (string)($p['estado'] ?? 'pendiente');
                  $estadoPago = (string)($p['estado_pago'] ?? 'pendiente');

                  $pagoBadge = ($estadoPago === 'pagado') ? 'text-bg-success' : 'text-bg-warning';

                  $estadoMap = [
                    'pendiente'  => 'text-bg-secondary',
                    'procesando' => 'text-bg-info',
                    'enviado'    => 'text-bg-primary',
                    'entregado'  => 'text-bg-success',
                    'cancelado'  => 'text-bg-danger',
                  ];
                  $estadoBadge = $estadoMap[$estadoActual] ?? 'text-bg-secondary';

                  $estados = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];
                ?>

                <tr>
                  <td><?= $idPedido ?></td>

                  <td><?= htmlspecialchars($p['email'] ?? '') ?></td>

                  <td><?= htmlspecialchars($p['fecha_pedido'] ?? '') ?></td>

                  <td class="text-end fw-bold">
                    <?= number_format((float)($p['total'] ?? 0), 2) ?> €
                  </td>

                  <td>
                    <span class="badge <?= $pagoBadge ?>">
                      <?= ($estadoPago === 'pagado') ? 'Pagado' : 'Pendiente' ?>
                    </span>
                  </td>

                  <td>
                    <span class="badge <?= $estadoBadge ?>">
                      <?= htmlspecialchars(ucfirst($estadoActual)) ?>
                    </span>
                  </td>

                  <td>
                    <form method="POST" action="<?= BASE_URL ?>/admin/actualizarEstadoPedido" class="d-flex gap-2 m-0">
                      <input type="hidden" name="id" value="<?= $idPedido ?>">

                      <select name="estado" class="form-select form-select-sm">
                        <?php foreach ($estados as $e): ?>
                          <option value="<?= $e ?>" <?= ($estadoActual === $e) ? 'selected' : '' ?>>
                            <?= ucfirst($e) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>

                      <button type="submit" class="btn btn-sm btn-success">Guardar</button>
                    </form>
                  </td>

                  <td>
                    <a class="btn btn-sm btn-outline-primary"
                       href="<?= BASE_URL ?>/index.php?url=admin/verPedido&id=<?= $idPedido ?>">
                      Ver
                    </a>

                    <a class="btn btn-sm btn-outline-danger ms-1"
                       href="<?= BASE_URL ?>/index.php?url=admin/eliminarPedido&id=<?= $idPedido ?>"
                       onclick="return confirm('¿Seguro que quieres eliminar este pedido?');">
                      Eliminar
                    </a>
                  </td>
                </tr>

              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-muted">No hay pedidos para mostrar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if (!empty($totalPaginas) && $totalPaginas > 1): ?>
        <?php
          $paginaActual = (int)($pagina ?? 1);
          if ($paginaActual < 1) $paginaActual = 1;
        ?>

        <nav class="mt-3">
          <ul class="pagination justify-content-center flex-wrap mb-0">

            <?php if ($paginaActual > 1): ?>
              <li class="page-item">
                <a class="page-link" href="<?= BASE_URL ?>/index.php?url=admin/pedidos&page=<?= $paginaActual - 1 ?>">⬅ Anterior</a>
              </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= (int)$totalPaginas; $i++): ?>
              <?php if ($i === $paginaActual): ?>
                <li class="page-item active" aria-current="page">
                  <span class="page-link"><?= $i ?></span>
                </li>
              <?php else: ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>/index.php?url=admin/pedidos&page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endif; ?>
            <?php endfor; ?>

            <?php if ($paginaActual < (int)$totalPaginas): ?>
              <li class="page-item">
                <a class="page-link" href="<?= BASE_URL ?>/index.php?url=admin/pedidos&page=<?= $paginaActual + 1 ?>">Siguiente ➡</a>
              </li>
            <?php endif; ?>

          </ul>
        </nav>
      <?php endif; ?>

      <div class="mt-3">
        <a class="btn btn-link text-decoration-none" href="<?= BASE_URL ?>/admin">← Volver al panel</a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
