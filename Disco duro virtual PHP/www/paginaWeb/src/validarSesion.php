<!-- Validar el usuario, fuera en la carpeta de seguridad -->

<?php
    include "./../../../seguridad/ficheros/datosBD.php";
    include "./../../../seguridad/ficheros/funciones.php";
    include "./../../../seguridad/ficheros/validarSesion-s.php";
    header("Location: ./../discoDuro.php");
?>
