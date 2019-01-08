<!-- Borrar los ficheros guardados en el disco duro -->

<?php
    include "./../../../seguridad/ficheros/datosBD.php";
    include "./../../../seguridad/ficheros/funciones.php";
    inicioSesion();

    if (!isset($_POST["id"])){
        header("Location: ./../discoDuro.php?mensaje=".urlencode("ERROR DE SERVIDOR: No se ha podido recibir el ID del fichero a borrar."));
        exit;
    }
    $id = $_POST["id"];

    if (!isset($_SESSION["usuario"])) {
        header("Location: ./../discoDuro.php?mensaje=".urlencode("ERROR DE SERVIDOR: No se han podido recibir el usuario."));
        exit;
    }
    $usuario = $_SESSION["usuario"];

    $canal = @mysqli_connect(IP, USUARIO, CLAVE, BD);
    if (!$canal){
        echo "Ha ocurrido el error: ".mysqli_connect_errno()." ".mysqli_connect_error()."<br />";
        exit;
    }
    mysqli_set_charset($canal, "utf8");

    $sql = "SELECT tamanyo FROM ficheros WHERE id = ?";
    $consulta = mysqli_prepare($canal, $sql);
    if (!$consulta) {
        echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
        exit;
    }
    mysqli_stmt_bind_param($consulta, "s", $id_);
    $id_ = $id;
    mysqli_stmt_execute($consulta);
    mysqli_stmt_bind_result($consulta, $tamanyo);
    mysqli_stmt_fetch($consulta);
    unset($consulta);

    $sql = "SELECT cuota FROM usuarios WHERE usuario = ?";
    $consulta = mysqli_prepare($canal, $sql);
    if (!$consulta) {
        echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
        exit;
    }
    mysqli_stmt_bind_param($consulta, "s", $usuario_);
    $usuario_ = $usuario;
    mysqli_stmt_execute($consulta);
    mysqli_stmt_bind_result($consulta, $cuota);
    mysqli_stmt_fetch($consulta);
    unset($consulta);

    if (unlink(__ALMACEN__.$id)) {
        $sql = "DELETE FROM ficheros WHERE id = ?";
        $consulta = mysqli_prepare($canal, $sql);
        if (!$consulta) {
            echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
            exit;
        }
        mysqli_stmt_bind_param($consulta, "s", $id_);
        $id_ = $id;
        mysqli_stmt_execute($consulta);
        unset($consulta);
        
        $cuota = $cuota + $tamanyo;
        
        $sql = "UPDATE usuarios SET cuota = ? WHERE usuario = ?";
        $consulta = mysqli_prepare($canal, $sql);
        if (!$consulta) {
            echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
            exit;
        }
        mysqli_stmt_bind_param($consulta, "is", $cuota_, $usuario_);
        $cuota_ = $cuota;
        $usuario_ = $usuario;
        mysqli_stmt_execute($consulta);
        unset($consulta);     
    }
    else {
        die("ERROR FATAL: No se ha podido eliminar el fichero.");
    }

    mysqli_stmt_close($consulta);
    mysqli_close($canal);
        
    // header("Location: ./../discoDuro.php?mensaje=".urlencode("Fichero borrado."));
    header("Location: ./../discoDuro.php");
?>
