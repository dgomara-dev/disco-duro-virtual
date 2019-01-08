<!-- Funciones de la páginas -->

<?php
    function inicioSesion() {
        session_name("SESION");
        session_cache_limiter("nocache");
        session_start();
    }

    function validado(&$usuario) {
        $validado = false;
        if (isset($_SESSION["validado"]) && $_SESSION["validado"]) {
            $validado = true;
            $usuario = $_SESSION["usuario"];
        }
        return $validado;
    }
?>
