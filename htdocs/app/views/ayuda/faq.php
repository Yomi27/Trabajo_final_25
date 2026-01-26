<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm">
  <div class="card-body p-4">
    <h1 class="h4 fw-bold mb-3">FAQ</h1>
    <p class="text-muted mb-4">Resolvemos las dudas más frecuentes.</p>

    <div class="accordion" id="faqAcc">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
            ¿Cuánto tarda en llegar mi pedido?
          </button>
        </h2>
        <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#faqAcc">
          <div class="accordion-body">
            Normalmente entre 2 y 5 días laborables.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
            ¿Puedo devolver un libro?
          </button>
        </h2>
        <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body">
            Sí, dentro de los 14 días naturales desde la recepción y en buen estado.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
            ¿Cómo contacto con Goodread?
          </button>
        </h2>
        <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body">
            Desde la página de contacto del footer.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
