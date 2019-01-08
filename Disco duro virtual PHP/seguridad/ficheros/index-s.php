<!-- Script previo a la utilización de la página index.php, si ya hay un usuario validado, nos manda a discoDuro.php -->

<?php
    inicioSesion();
    $usuario = "";
    if (validado($usuario)) {
        header("Location: ./discoDuro.php");
        exit;
    }
?>
