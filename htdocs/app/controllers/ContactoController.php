<?php

class ContactoController
{
    public function index()
    {
        $mensaje = null;

        require_once __DIR__ . '/../views/contacto/index.php';
    }
}
