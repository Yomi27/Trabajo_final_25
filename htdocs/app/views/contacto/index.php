<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm">
  <div class="card-body p-4">
    <h1 class="h4 fw-bold mb-3">Contacto</h1>
    <p class="text-muted mb-4">
      ¿Tienes alguna duda? Escríbenos y te responderemos lo antes posible.
    </p>

    <div class="row g-3">
      <div class="col-12 col-lg-6">
        <form method="POST" action="<?= BASE_URL ?>/contacto">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input class="form-control" name="nombre" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mensaje</label>
            <textarea class="form-control" name="mensaje" rows="4" required></textarea>
          </div>
          <button class="btn btn-dark" type="submit">Enviar</button>
        </form>
      </div>

      <div class="col-12 col-lg-6">
        <div class="p-3 rounded-3 bg-light border">
          <h2 class="h6 fw-bold">Goodread</h2>
          <p class="mb-1"><strong>Email:</strong> soporte@goodread.com</p>
          <p class="mb-1"><strong>Horario:</strong> L–V 9:00–18:00</p>
          <p class="mb-0 text-muted">Te responderemos en 24–48h laborables.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
