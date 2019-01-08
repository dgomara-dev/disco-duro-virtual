<!-- Cerrar la sesión actual y volver al index -->

<?php
    include "./../../../seguridad/ficheros/funciones.php";
    inicioSesion();
    session_destroy();
    unset($_SESSION);
    header("Location: ./../index.php?mensaje=".urlencode("Se ha cerrado la sesión."));
    exit;
?>
