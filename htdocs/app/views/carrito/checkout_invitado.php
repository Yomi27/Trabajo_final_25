<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <h2 class="h4 fw-bold mb-3">Finalizar compra</h2>

  <div class="row g-4">
    <div class="col-12 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="h6 fw-bold">Comprar como invitado</h3>
          <p class="text-muted mb-3">Introduce tus datos para envío y confirmación.</p>

          <form method="POST" action="<?= BASE_URL ?>/carrito/confirmarInvitado">
            <div class="mb-2">
              <label class="form-label">Nombre</label>
              <input class="form-control" name="nombre" required>
            </div>

            <div class="mb-2">
              <label class="form-label">Email</label>
              <input class="form-control" type="email" name="email" required>
            </div>

            <div class="mb-2">
              <label class="form-label">Teléfono (opcional)</label>
              <input class="form-control" name="telefono">
            </div>

            <div class="mb-2">
              <label class="form-label">Dirección</label>
              <input class="form-control" name="direccion" required>
            </div>

            <div class="row g-2">
              <div class="col-6">
                <label class="form-label">CP</label>
                <input class="form-control" name="cp" required>
              </div>
              <div class="col-6">
                <label class="form-label">Ciudad</label>
                <input class="form-control" name="ciudad" required>
              </div>
            </div>

            <button class="btn btn-dark mt-3 w-100" type="submit">Continuar al pago</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="h6 fw-bold">¿Ya tienes cuenta?</h3>
          <p class="text-muted">Inicia sesión o regístrate para usar tus direcciones guardadas.</p>

          <div class="d-grid gap-2">
            <a class="btn btn-outline-dark" href="<?= BASE_URL ?>/usuario/login">Iniciar sesión</a>
            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/usuario/registro">Registrarme</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
