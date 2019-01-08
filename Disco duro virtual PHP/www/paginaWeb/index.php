<!-- Página principal de login -->

<?php
    include "./../../seguridad/ficheros/funciones.php";
    include "./../../seguridad/ficheros/index-s.php";
    $mensaje = "";
    if (isset($_GET["mensaje"])) {
	   $mensaje = strip_tags(trim($_GET["mensaje"]));
    }    
?>

<!DOCTYPE html>
<html>

<head>
    <title>Iniciar sesión - Disco Duro Virtual en PHP</title>
    <meta charset="UTF-8" />
    <link rel="icon" href="./img/favicon.png" />
    <link rel="stylesheet" type="text/css" href="./css/index.css" />
</head>

<body>
    <header>
        <a href="./index.php">
            <img src="./img/logo.png" alt="Disco Duro Virtual en PHP">
            <img src="./img/titulo.png" alt="Disco Duro Virtual en PHP">
        </a>
    </header>
    <section>
        <article>
            <img src="./img/nube.jpg" alt="Almacenamiento en la nube">
        </article>
        <article>
            <h3>Iniciar sesión</h3>
            <p id="mensaje"><?=$mensaje?></p>
            <form method="post" action="./src/validarSesion.php" autocomplete="off">
                <input type="text" placeholder="Usuario" name="usuario" maxlength="20" size="20" required="required" />
                <input type="password" placeholder="Contraseña" name="clave" maxlength="20" size="20" required="required" />
                <button type="submit">Iniciar sesión</button>
            </form>
        </article>
    </section>
    <footer>
        <p>
            "Disco duro virtual en PHP"<br />
            DAW 2 - Desarrollo Web en Entorno Servidor<br />
            I.E.S. Virgen del Espino (Soria)<br />
            Enero 2019
        </p>
    </footer>
</body>

</html>
