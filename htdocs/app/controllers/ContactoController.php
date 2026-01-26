<?php

class ContactoController
{
    public function index()
    {
        $mensaje = null;

        // (Opcional) Si luego quieres procesar un formulario, lo harás aquí.
        require_once __DIR__ . '/../views/contacto/index.php';
    }
}
