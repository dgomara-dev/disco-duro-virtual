<!-- Descargar los ficheros guardados en el disco duro -->

<?php
    include "./../../../seguridad/ficheros/datosBD.php";

    if (!isset($_POST["id"])){
        header("Location: ./../discoDuro.php?mensaje=".urlencode("ERROR DE SERVIDOR: No se ha podido recibir el ID del fichero a descargar."));
        exit;
    }
    $id = $_POST["id"];

    $canal = @mysqli_connect(IP, USUARIO, CLAVE, BD);
    if (!$canal){
        echo "Ha ocurrido el error: ".mysqli_connect_errno()." ".mysqli_connect_error()."<br />";
        exit;
    }
    mysqli_set_charset($canal, "utf8");

    $sql = "SELECT nombre, tamanyo, tipo FROM ficheros WHERE id = ?";
    $consulta = mysqli_prepare($canal, $sql);
    if (!$consulta) {
        echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
        exit;
    }
    mysqli_stmt_bind_param($consulta, "s", $id_);
    mysqli_stmt_bind_result($consulta, $nombre_, $tamanyo_, $tipo_);
    $id_ = $id;
    mysqli_stmt_execute($consulta);
    mysqli_stmt_store_result($consulta);
    $num = mysqli_stmt_num_rows($consulta);

    if ($num != 1) {
        header("Location: ./../discoDuro.php");
        exit;
    }
    mysqli_stmt_fetch($consulta);

    header("Content-disposition: attachment; filename = $nombre_");
    header("Content-type: $tipo_");
    readfile(__ALMACEN__.$id);

    mysqli_stmt_close($consulta);
    mysqli_close($canal);
?>
