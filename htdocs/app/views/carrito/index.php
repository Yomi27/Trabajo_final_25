<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">

  <header class="card shadow-sm mb-3">
    <div class="card-body">
      <h2 class="h3 fw-bold mb-1">Mi carrito</h2>
      <p class="text-muted mb-0">Revisa tus productos antes de continuar</p>
    </div>
  </header>

  <?php if (empty($carrito)): ?>

    <div class="card shadow-sm">
      <div class="card-body">
        <h3 class="h5 fw-bold mb-3">Tu carrito está vacío</h3>
        <a class="btn btn-dark" href="<?= BASE_URL ?>/libro">Ver libros</a>
      </div>
    </div>

  <?php else: ?>

    <div class="card shadow-sm">
      <div class="card-body">

        <div class="table-responsive">
          <table class="table align-middle table-hover">
            <thead class="table-light">
              <tr>
                <th>Libro</th>
                <th class="text-end">Precio</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Acciones</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>

            <tbody>
              <?php $total = 0; ?>
              <?php foreach ($carrito as $item): ?>
                <?php
                  $precio   = (float)($item['precio'] ?? 0);
                  $cantidad = (int)($item['cantidad'] ?? 0);
                  $subtotal = $precio * $cantidad;
                  $total   += $subtotal;
                  $id       = (int)($item['id'] ?? 0);
                ?>

                <tr>
                  <td class="fw-semibold">
                    <?= htmlspecialchars($item['titulo'] ?? '') ?>
                  </td>

                  <td class="text-end">
                    <?= number_format($precio, 2) ?> €
                  </td>

                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Cantidad">
                      <a class="btn btn-outline-secondary"
                         href="<?= BASE_URL ?>/carrito/disminuir&id=<?= $id ?>"
                         aria-label="Quitar 1">−</a>

                      <span class="btn btn-outline-secondary disabled">
                        <?= $cantidad ?>
                      </span>

                      <a class="btn btn-outline-secondary"
                         href="<?= BASE_URL ?>/carrito/aumentar&id=<?= $id ?>"
                         aria-label="Añadir 1">+</a>
                    </div>
                  </td>

                  <td class="text-center">
                    <a class="btn btn-outline-danger btn-sm"
                       href="<?= BASE_URL ?>/carrito/eliminar&id=<?= $id ?>"
                       onclick="return confirm('¿Eliminar este artículo del carrito?')">
                      Eliminar
                    </a>
                  </td>

                  <td class="text-end fw-bold">
                    <?= number_format($subtotal, 2) ?> €
                  </td>
                </tr>

              <?php endforeach; ?>
            </tbody>

            <tfoot>
              <tr class="table-light">
                <td colspan="4" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold"><?= number_format($total, 2) ?> €</td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-between mt-3">
          <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/libro">Seguir comprando</a>

          <form method="GET" action="<?= BASE_URL ?>/carrito/direccion" class="m-0">
            <button type="submit" class="btn btn-success">Continuar y elegir dirección</button>
          </form>
        </div>

      </div>
    </div>

  <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
