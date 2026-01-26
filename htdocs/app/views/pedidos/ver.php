<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4" style="max-width: 880px;">
  <div class="card shadow-sm">
    <div class="card-body">

      <h2 class="h4 fw-bold mb-2">
        Detalle del pedido #<?= (int)($pedido['id'] ?? 0) ?>
      </h2>

      <p class="fw-semibold mb-3">
        Total: <?= number_format((float)($pedido['total'] ?? 0), 2) ?> €
      </p>

      <div class="table-responsive mb-3">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Libro</th>
              <th class="text-center">Cantidad</th>
              <th class="text-end">Precio unitario</th>
              <th class="text-end">Subtotal</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($lineas)): ?>
              <?php foreach ($lineas as $l): ?>
                <?php
                  $cantidad = (int)($l['cantidad'] ?? 0);
                  $pu = (float)($l['precio_unitario'] ?? 0);
                  $sub = $cantidad * $pu;
                ?>
                <tr>
                  <td class="fw-semibold"><?= htmlspecialchars($l['titulo'] ?? '') ?></td>
                  <td class="text-center fw-bold"><?= $cantidad ?></td>
                  <td class="text-end"><?= number_format($pu, 2) ?> €</td>
                  <td class="text-end fw-bold"><?= number_format($sub, 2) ?> €</td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-muted">No hay líneas en este pedido.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php
        $estadoPago = (string)($pedido['estado_pago'] ?? 'pendiente');
        $idPedido = (int)($pedido['id'] ?? 0);
      ?>

      <div class="d-flex flex-wrap gap-2">
        <?php if ($estadoPago !== 'pagado'): ?>
          <a class="btn btn-success"
             href="<?= BASE_URL ?>/index.php?url=pedido/checkout&id=<?= $idPedido ?>">
            Pagar
          </a>
        <?php endif; ?>

        <a class="btn btn-outline-secondary"
           href="<?= BASE_URL ?>/index.php?url=pedido">
          ← Volver a mis pedidos
        </a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
