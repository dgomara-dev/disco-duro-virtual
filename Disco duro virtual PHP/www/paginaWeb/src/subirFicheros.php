<!-- Subir los ficheros introducidos en el formulario -->

<?php
    include "./../../../seguridad/ficheros/datosBD.php";
    include "./../../../seguridad/ficheros/funciones.php";
    inicioSesion();

    if (!isset($_FILES["ficheros"]) || !is_array($_FILES["ficheros"]["name"])) {
        header("Location: ./../discoDuro.php?mensaje=".urlencode("ERROR DE SERVIDOR: No se han podido recibir los ficheros."));
        exit;
    }
    $intentosSubida = count($_FILES["ficheros"]["name"]);

    if (!isset($_SESSION["usuario"])) {
        header("Location: ./../discoDuro.php?mensaje=".urlencode("ERROR DE SERVIDOR: No se han podido recibir el usuario."));
        exit;
    }
    $usuario = $_SESSION["usuario"];

    $canal = @mysqli_connect(IP, USUARIO, CLAVE, BD);
    if (!$canal) {
        echo "Ha ocurrido el error: ".mysqli_connect_errno()." ".mysqli_connect_error()."<br />";
        exit;
    }
    mysqli_set_charset($canal, "utf8");

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
 
    $sql1 = "INSERT INTO ficheros (id, nombre, tamanyo, tipo, usuario) values (?, ?, ?, ?, ?)";
    $consulta1 = mysqli_prepare($canal, $sql1);
    if (!$consulta1) {
        echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
        exit;
    }
    mysqli_stmt_bind_param($consulta1, "ssiss", $id_, $nombre_, $tamanyo_, $tipo_, $usuario_);

    $sql2 = "UPDATE usuarios SET cuota = ? WHERE usuario = ?";
    $consulta2 = mysqli_prepare($canal, $sql2);
    if (!$consulta2) {
        echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
        exit;
    }
    mysqli_stmt_bind_param($consulta2, "ss", $cuota_, $usuario_);
 
    $mensaje = "";
    $tamanyoTotal = 0;
    for ($i = 0; $i < $intentosSubida; $i ++) {
        switch ($_FILES["ficheros"]["error"][$i]) {   
            case UPLOAD_ERR_OK:
                $id_= uniqid("", true);
                $ficheroSubido = __ALMACEN__.$id_;
                if ($tamanyoTotal <= $cuota) {
                    $tamanyoTotal = $tamanyoTotal + $_FILES["ficheros"]["size"][$i];
                    $cuota = $cuota - $_FILES["ficheros"]["size"][$i];
                    if (move_uploaded_file($_FILES["ficheros"]["tmp_name"][$i], $ficheroSubido)) {
                        // $mensaje .= "Fichero \"".basename($_FILES["ficheros"]["name"][$i])."\" subido con éxito.";
                        $nombre_ = basename($_FILES["ficheros"]["name"][$i]);
                        $tamanyo_ = $_FILES["ficheros"]["size"][$i];
                        $tipo_ = $_FILES["ficheros"]["type"][$i];
                        $usuario_ = $usuario;
                        $cuota_ = $cuota;    
                        mysqli_stmt_execute($consulta1);
                        mysqli_stmt_execute($consulta2);
                    }
                    else {
                        $mensaje .= "Fichero \"".basename($_FILES["ficheros"]["name"][$i])."\" produce error desconocido 1.";
                    }    
                }
                else {
                     $mensaje .= "Fichero \"".basename($_FILES["ficheros"]["name"][$i])."\" excede el límite. "; 
                }
                break;
            case UPLOAD_ERR_NO_FILE:
                $mensaje .= "Fichero inexistente.";
                break;
            case UPLOAD_ERR_INI_SIZE: case UPLOAD_ERR_FORM_SIZE:
                $mensaje .= "Fichero \"".basename($_FILES["ficheros"]["name"][$i])."\" excede el límite. ";
                break;
            default:
                $mensaje .= "Fichero \"".basename($_FILES["ficheros"]["name"][$i])."\" produce error desconocido 2.";
                break;
        }     
    }

    mysqli_stmt_close($consulta1);
    mysqli_stmt_close($consulta2);
    mysqli_close($canal);
        
    header("Location: ./../discoDuro.php?mensaje=".urlencode($mensaje))
?>
