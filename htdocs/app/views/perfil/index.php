<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4" style="max-width: 980px;">
  <div class="card shadow-sm">
    <div class="card-body">

      <h2 class="h4 fw-bold mb-1">Mi cuenta</h2>
      <p class="text-muted mb-4">Gestiona tus datos, pedidos y direcciones.</p>

      <div class="row g-3">

        <div class="col-12 col-md-6">
          <a class="card h-100 text-decoration-none" href="<?= BASE_URL ?>/index.php?url=usuario/datos">
            <div class="card-body">
              <div class="fw-bold mb-1 text-dark">Mis datos</div>
              <div class="text-muted small">Editar nombre, email y contraseña</div>
            </div>
          </a>
        </div>

        <div class="col-12 col-md-6">
          <a class="card h-100 text-decoration-none" href="<?= BASE_URL ?>/index.php?url=pedido">
            <div class="card-body">
              <div class="fw-bold mb-1 text-dark">Mis pedidos</div>
              <div class="text-muted small">Ver el historial y detalles de compras</div>
            </div>
          </a>
        </div>

        <div class="col-12 col-md-6">
          <a class="card h-100 text-decoration-none" href="<?= BASE_URL ?>/index.php?url=direccion">
            <div class="card-body">
              <div class="fw-bold mb-1 text-dark">Mis direcciones</div>
              <div class="text-muted small">Añadir o editar direcciones de envío</div>
            </div>
          </a>
        </div>

        <div class="col-12 col-md-6">
          <a class="card h-100 text-decoration-none border border-danger"
             href="<?= BASE_URL ?>/index.php?url=usuario/logout"
             onclick="return confirm('¿Seguro que quieres cerrar sesión?');">
            <div class="card-body">
              <div class="fw-bold mb-1 text-danger">Cerrar sesión</div>
              <div class="text-muted small">Salir de tu cuenta en este dispositivo</div>
            </div>
          </a>
        </div>

      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
