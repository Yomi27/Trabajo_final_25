<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <h2 class="fw-bold mb-3">Detalle del pedido #<?= (int)($pedido['id'] ?? 0) ?></h2>

  <div class="card shadow-sm">
    <div class="card-body">

      <?php
        $estado = (string)($pedido['estado'] ?? 'pendiente');
        $estadoMap = [
          'pendiente'  => 'text-bg-secondary',
          'procesando' => 'text-bg-info',
          'enviado'    => 'text-bg-primary',
          'entregado'  => 'text-bg-success',
          'cancelado'  => 'text-bg-danger',
        ];
        $estadoBadge = $estadoMap[$estado] ?? 'text-bg-secondary';
      ?>

      <div class="row g-3">
        <div class="col-12 col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="fw-bold mb-2">Cliente</h5>
              <div class="text-body-secondary">
                <div class="fw-semibold text-dark"><?= htmlspecialchars($pedido['nombre_usuario'] ?? '') ?></div>
                <div><?= htmlspecialchars($pedido['email'] ?? '') ?></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="fw-bold mb-2">Dirección de envío</h5>
              <div class="text-body-secondary">
                <div><?= htmlspecialchars($pedido['direccion'] ?? '') ?></div>
                <div>
                  <?= htmlspecialchars($pedido['codigo_postal'] ?? '') ?> - <?= htmlspecialchars($pedido['ciudad'] ?? '') ?>
                  (<?= htmlspecialchars($pedido['provincia'] ?? '') ?>)
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="fw-bold mb-2">Datos del pedido</h5>
              <div class="text-body-secondary">
                <div><strong class="text-dark">Fecha:</strong> <?= htmlspecialchars($pedido['fecha_pedido'] ?? '') ?></div>
                <div class="mt-1">
                  <strong class="text-dark">Estado:</strong>
                  <span class="badge <?= $estadoBadge ?>"><?= htmlspecialchars($estado) ?></span>
                </div>
                <div class="mt-1">
                  <strong class="text-dark">Total:</strong> <span class="fw-bold text-dark"><?= number_format((float)($pedido['total'] ?? 0), 2) ?> €</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <h5 class="fw-bold mt-4 mb-2">Libros comprados</h5>

      <?php if (empty($lineas)): ?>
        <div class="card">
          <div class="card-body text-muted">
            No hay productos.
          </div>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle" style="min-width: 720px;">
            <thead class="table-light">
              <tr>
                <th>Libro</th>
                <th class="text-center" style="width:130px;">Cantidad</th>
                <th class="text-end" style="width:170px;">Precio unitario</th>
                <th class="text-end" style="width:170px;">Subtotal</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($lineas as $l): ?>
                <?php
                  $cant = (int)($l['cantidad'] ?? 0);
                  $precio = (float)($l['precio_unitario'] ?? 0);
                  $sub = $cant * $precio;
                ?>
                <tr>
                  <td class="fw-semibold"><?= htmlspecialchars($l['titulo'] ?? '') ?></td>
                  <td class="text-center fw-bold"><?= $cant ?></td>
                  <td class="text-end"><?= number_format($precio, 2) ?> €</td>
                  <td class="text-end fw-bold"><?= number_format($sub, 2) ?> €</td>
                </tr>
              <?php endforeach; ?>
            </tbody>

            <tfoot class="table-light">
              <tr>
                <td colspan="3" class="text-end fw-bold">Total del pedido</td>
                <td class="text-end fw-bold"><?= number_format((float)($pedido['total'] ?? 0), 2) ?> €</td>
              </tr>
            </tfoot>
          </table>
        </div>
      <?php endif; ?>

      <div class="mt-3">
        <a class="btn btn-link text-decoration-none" href="<?= BASE_URL ?>/index.php?url=admin/pedidos">← Volver a pedidos</a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
