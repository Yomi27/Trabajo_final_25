<?php

class LegalController{
    public function privacidad(){
        require_once __DIR__ . '/../views/legal/privacidad.php';
    }

    public function cookies(){
        require_once __DIR__ . '/../views/legal/cookies.php';
    }

    public function terminos(){
        require_once __DIR__ . '/../views/legal/terminos.php';
    }
}
?>
