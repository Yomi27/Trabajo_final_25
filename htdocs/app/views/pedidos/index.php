<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4" style="max-width: 980px;">
  <div class="card shadow-sm">
    <div class="card-body">

      <h2 class="h4 fw-bold mb-3">Mis pedidos</h2>

      <?php if (empty($pedidos)): ?>
        <div class="alert alert-light border mb-0">
          No has realizado ningún pedido todavía.
        </div>
      <?php else: ?>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:120px;">ID Pedido</th>
                <th class="text-end" style="width:140px;">Total</th>
                <th style="width:180px;">Fecha</th>
                <th style="width:140px;">Estado</th>
                <th style="width:220px;">Acciones</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($pedidos as $p): ?>
                <?php
                  $idPedido = (int)($p['id'] ?? 0);
                  $estadoPago = (string)($p['estado_pago'] ?? 'pendiente');

                  $pagoBadge = ($estadoPago === 'pagado') ? 'text-bg-success' : 'text-bg-warning';
                  $pagoText  = ($estadoPago === 'pagado') ? 'Pagado' : 'Pendiente';
                ?>

                <tr>
                  <td class="fw-semibold">#<?= $idPedido ?></td>

                  <td class="text-end fw-bold">
                    <?= number_format((float)($p['total'] ?? 0), 2) ?> €
                  </td>

                  <td><?= htmlspecialchars($p['fecha_pedido'] ?? '—') ?></td>

                  <td>
                    <span class="badge <?= $pagoBadge ?>">
                      <?= htmlspecialchars($pagoText) ?>
                    </span>
                  </td>

                  <td>
                    <a class="btn btn-sm btn-outline-primary"
                       href="<?= BASE_URL ?>/index.php?url=pedido/ver&id=<?= $idPedido ?>">
                      Ver detalle
                    </a>

                    <?php if ($estadoPago !== 'pagado'): ?>
                      <a class="btn btn-sm btn-success ms-1"
                         href="<?= BASE_URL ?>/index.php?url=pedido/checkout&id=<?= $idPedido ?>">
                        Pagar
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/index.php?url=perfil/index">← Volver</a>
        </div>

      <?php endif; ?>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
