<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$rolSesion = $_SESSION['usuario']['rol'] ?? '';
$esAdmin    = ($rolSesion === 'admin');
$esEmpleado = ($rolSesion === 'empleado');
$esStaff    = ($esAdmin || $esEmpleado);

require_once __DIR__ . '/../../models/Categoria.php';
$categoriasMenu = Categoria::obtenerVisibles();

$urlActual = trim((string)($_GET['url'] ?? ''), '/');
$seccion = ($urlActual === '') ? 'home' : explode('/', $urlActual)[0];

$mostrarSidebar = in_array($seccion, ['home', 'libro'], true);
$categoriaSeleccionada = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
?>
<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
  <meta charset="UTF-8">
  <title>Librería</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/htdocs/css/estilo.css" rel="stylesheet">

</head>
<body class="d-flex flex-column h-100"> 

<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/">
    <img src="<?= BASE_URL ?>/css/logoBlanco.png" alt="Logo Librería" class="logo-navbar" width="120px">
    </a>



    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link <?= ($seccion === 'home') ? 'active' : '' ?>" href="<?= BASE_URL ?>/">Inicio</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($seccion === 'libro') ? 'active' : '' ?>" href="<?= BASE_URL ?>/libro">Libros</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($seccion === 'carrito') ? 'active' : '' ?>" href="<?= BASE_URL ?>/carrito">Carrito</a>
        </li>

        <?php if ($esStaff): ?>
          <li class="nav-item">
            <a class="nav-link <?= ($seccion === 'admin') ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin">
              <?= $esAdmin ? 'Panel admin' : 'Panel empleado' ?>
            </a>
          </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario'])): ?>
          <li class="nav-item">
            <a class="nav-link <?= ($seccion === 'perfil') ? 'active' : '' ?>" href="<?= BASE_URL ?>/perfil">Mi cuenta</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link <?= ($seccion === 'usuario') ? 'active' : '' ?>" href="<?= BASE_URL ?>/usuario/login">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($seccion === 'usuario') ? 'active' : '' ?>" href="<?= BASE_URL ?>/usuario/registro">Registro</a>
          </li>
        <?php endif; ?>

      </ul>

      <div class="d-flex align-items-center gap-3">
        <?php if (isset($_SESSION['usuario'])): ?>
          <span class="text-white-50 small">
            Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
          </span>
        <?php endif; ?>

        <form method="GET" action="<?= BASE_URL ?>/libro" class="d-flex" role="search">
          <input class="form-control form-control-sm me-2" type="search" name="buscar" placeholder="Buscar libros..." required>
          <button class="btn btn-outline-light btn-sm" type="submit">Buscar</button>
        </form>
      </div>
    </div>
  </div>
</nav>

<div class="container my-4 flex-grow-1">
  <div class="row g-3">

    <?php if ($mostrarSidebar): ?>
      <aside class="col-12 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="h6 fw-bold mb-3">Categorías</h3>

            <div class="list-group">
              <?php foreach ($categoriasMenu as $cat): ?>
                <?php
                  $idCat = (int)($cat['id'] ?? 0);
                  $activa = ($categoriaSeleccionada === $idCat);
                ?>
                <a href="<?= BASE_URL ?>/libro?categoria=<?= $idCat ?>"
                   class="list-group-item list-group-item-action <?= $activa ? 'active' : '' ?>">
                  <?= htmlspecialchars($cat['nombre'] ?? '') ?>
                </a>
              <?php endforeach; ?>
            </div>

          </div>
        </div>
      </aside>
    <?php endif; ?>

    <main class="<?= $mostrarSidebar ? 'col-12 col-lg-9' : 'col-12' ?>">
