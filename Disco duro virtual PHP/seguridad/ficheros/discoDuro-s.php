<!-- Script previo a la utilización de la página discoDuro.php, si no se valida el usuario, nos echa fuera -->

<?php
    inicioSesion();
    $usuario = "";
    if (!validado($usuario)) {
        session_destroy();
        unset($_SESSION);
        header("Location: ./index.php?mensaje=".urlencode("Se ha cerrado la sesión."));
        exit;
    }
?>
