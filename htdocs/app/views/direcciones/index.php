<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4" style="max-width: 820px;">

  <div class="card shadow-sm">
    <div class="card-body">

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 class="h4 fw-bold mb-0">Mis direcciones</h2>

        <a class="btn btn-success"
           href="<?= BASE_URL ?>/index.php?url=direccion/crear">
          Añadir nueva dirección
        </a>
      </div>

      <?php if (empty($direcciones)): ?>
        <div class="alert alert-light border">
          No tienes direcciones guardadas.
        </div>
      <?php else: ?>

        <div class="list-group mb-3">
          <?php foreach ($direcciones as $d): ?>
            <?php $idDir = (int)($d['id'] ?? 0); ?>

            <div class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-3">

              <div>
                <div class="fw-semibold">
                  <?= htmlspecialchars($d['nombre_destinatario'] ?? '') ?>
                </div>

                <div class="text-muted small">
                  <div><?= htmlspecialchars($d['direccion'] ?? '') ?></div>
                  <div><?= htmlspecialchars($d['ciudad'] ?? '') ?> (<?= htmlspecialchars($d['codigo_postal'] ?? '') ?>)</div>
                  <div><?= htmlspecialchars($d['pais'] ?? '') ?></div>
                  <div><?= htmlspecialchars($d['telefono'] ?? '') ?></div>
                </div>
              </div>

              <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-sm btn-outline-primary"
                   href="<?= BASE_URL ?>/index.php?url=direccion/editar&id=<?= $idDir ?>">
                  Editar
                </a>

                <a class="btn btn-sm btn-outline-danger"
                   href="<?= BASE_URL ?>/index.php?url=direccion/eliminar&id=<?= $idDir ?>"
                   onclick="return confirm('¿Seguro que quieres eliminar esta dirección?');">
                  Eliminar
                </a>
              </div>

            </div>
          <?php endforeach; ?>
        </div>

      <?php endif; ?>

      <div>
        <a class="btn btn-outline-secondary"
           href="<?= BASE_URL ?>/index.php?url=perfil/index">
          ← Volver
        </a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
