<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h2 class="h4 fw-bold mb-4">Panel de administración</h2>

      <div class="list-group">

        <a href="<?= BASE_URL ?>/admin/libros" class="list-group-item list-group-item-action">
          Gestionar libros
        </a>

        <a href="<?= BASE_URL ?>/admin/autores" class="list-group-item list-group-item-action">
          Gestionar autores
        </a>

        <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
          <a href="<?= BASE_URL ?>/admin/usuarios" class="list-group-item list-group-item-action">
            Usuarios
          </a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/admin/pedidos" class="list-group-item list-group-item-action">
          Gestión de pedidos
        </a>

        <a href="<?= BASE_URL ?>/admin/categorias" class="list-group-item list-group-item-action">
          Gestión de categorías
        </a>

      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
