<!-- Página del disco duro -->

<?php
    include "./../../seguridad/ficheros/datosBD.php";
    include "./../../seguridad/ficheros/funciones.php";
    include "./../../seguridad/ficheros/discoDuro-s.php";

    $mensaje = "";
    if (isset($_GET["mensaje"])) {
	   $mensaje = strip_tags(trim($_GET["mensaje"]));
    }

    $usuario = $_SESSION["usuario"];

    $canal = @mysqli_connect(IP, USUARIO, CLAVE, BD);
    if (!$canal) {
        echo "Ha ocurrido el error: ".mysqli_connect_errno()." ".mysqli_connect_error()."<br />";
        exit;
    }
    mysqli_set_charset($canal, "utf8");

    $sql = "SELECT nombre, cuota FROM usuarios WHERE usuario = ?";
    $consulta = mysqli_prepare($canal, $sql);
    if (!$consulta) {
        echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
        exit;
    }
    mysqli_stmt_bind_param($consulta, "s", $usuario_);
    $usuario_ = $usuario;
    mysqli_stmt_execute($consulta);
    mysqli_stmt_bind_result($consulta, $nombreUsuario, $cuota);
    mysqli_stmt_fetch($consulta);
    unset($consulta);

    //$formatoCuota = number_format(($cuota / pow(2, 10)), 0, ".", ",");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Archivos - Disco Duro Virtual en PHP</title>
    <meta charset="UTF-8" />
    <link rel="icon" href="./img/favicon.png" />
    <link rel="stylesheet" type="text/css" href="./css/discoDuro.css" />
</head>

<body>
    <header>
        <h3>¡Bienvenido, <?=$nombreUsuario?>!</h3>
    </header>
    <aside>
        <a href="./discoDuro.php">
            <img src="./img/logo.png" alt="Disco Duro Virtual en PHP">
        </a>
        <table>
            <tr>
                <td>Usuario:</td>
                <td><?=$usuario?></td>
            </tr>
            <tr>
                <td>Cuota:</td>
                <td><?=$cuota?> bytes</td>
            </tr>
        </table>
        <form method="post" action="./src/cerrarSesion.php">
            <button type="submit" id="btnCerrarSesion">✘ Cerrar sesión</button>
        </form>
    </aside>
    <section>
        <?php
            $sql = "SELECT id, nombre, tamanyo FROM ficheros WHERE usuario = ? ORDER BY nombre";
            $consulta = mysqli_prepare($canal, $sql);
            if (!$consulta) {
                echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
                exit;
            }
            mysqli_stmt_bind_param($consulta, "s", $usuario_);
            $usuario_ = $usuario;
            mysqli_stmt_execute($consulta);
            mysqli_stmt_bind_result($consulta, $id, $nombre, $tamanyo);        
            $cont = 0;
            while (mysqli_stmt_fetch($consulta)) {
                if ($cont == 0) {
                    echo "<table cellspacing='0'>
                            <tr>
                                <th>Nombre</th>
                                <th>Tamaño</th>
                            </tr>";
                }
                echo "<tr>
                        <td style='width:200px'>$nombre</td>
                        <td style='width:200px'>$tamanyo bytes</td>
                        <td style='width:60px'>
                            <form method='post' action='./src/descargarFicheros.php'>
                                <input type='hidden' name='id' value='$id' />
                                <input type='image' src='./img/descargar.png' />
                            </form>
                        </td>
                        <td style='width:60px'>
                            <form method='post' action='./src/borrarFicheros.php'>
                                <input type='hidden' name='id' value='$id' />
                                <input type='image' src='./img/borrar.png' />
                            </form>
                        </td>
                    </tr>";
                $cont++;
            }
            if ($cont == 0) {
                echo "<table>
                        <tr>
                            <th>No hay ficheros.</th>
                        </tr>";
            }
            echo "</table>";
            mysqli_stmt_close($consulta);
            unset($consulta);
        ?>
        <p id="mensaje">&nbsp;<?=$mensaje?></p>
        <form method="post" action="./src/subirFicheros.php" enctype="multipart/form-data">
            <table cellspacing="0">
                <tr>
                    <th>Subida de ficheros</th>
                </tr>
                <tr>
                    <td>Fichero(s):<input type="hidden" name="MAX_FILE_SIZE" value="<?=$cuota?>" /></td>
                    <td><input type="file" name="ficheros[]" multiple="multiple" /></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:center;"><input type="submit" id="btnSubirFicheros" value="⇑ Subir" id="btnSubir" /></td>
                </tr>
            </table>
        </form>       
    </section>
</body>

</html>
