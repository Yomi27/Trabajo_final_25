<?php

class PerfilController {

    public function index() {
        Auth::requireLogin();
        require_once __DIR__ . '/../views/perfil/index.php';
    }
}
?>