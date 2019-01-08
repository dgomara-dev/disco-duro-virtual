<!-- Validación del usuario -->

<?php
    $usuario = "";
    if (!isset($_POST["usuario"])) {
        header("Location: ./../index.php?mensaje=".urlencode("ERROR DE SERVIDOR: No se ha podido recibir el nombre de usuario."));
        exit;
    }
    $usuario = strip_tags(trim($_POST["usuario"]));

    $clave = "";
    if (!isset($_POST["clave"])) {
        header("Location: ./../index.php?mensaje=".urlencode("ERROR DE SERVIDOR: No se ha podido recibir la contraseña."));
        exit;
    }
    $clave = strip_tags(trim($_POST["clave"]));

    if (empty($usuario) || strlen($usuario)>20 || empty($clave) || strlen($clave)>20) {
        header("Location: ./../index.php?mensaje=".urlencode("El usuario no existe o la contraseña no es válida."));
        exit;
    }

    $canal = @mysqli_connect(IP, USUARIO, CLAVE, BD);
    if (!$canal) {
        echo "Ha ocurrido el error: ".mysqli_connect_errno()." ".mysqli_connect_error()."<br />";
        exit;
    }
    mysqli_set_charset($canal, "utf8");

    $sql = "SELECT clave FROM usuarios WHERE usuario = ?";
    $consulta = mysqli_prepare($canal, $sql);
    if (!$consulta) {
        echo "Ha ocurrido el error: ".mysqli_errno($canal)." ".mysqli_error($canal)."<br />";
        exit;	
    }
    mysqli_stmt_bind_param($consulta, "s", $usuario);

    mysqli_stmt_execute($consulta);
    mysqli_stmt_bind_result($consulta, $cclave);
    mysqli_stmt_fetch($consulta);
    
    if (!password_verify($clave, $cclave)) {
        header("Location: ./../index.php?mensaje=".urlencode("El usuario no existe o la contraseña no es válida."));
        exit;
    }
    
    mysqli_stmt_close($consulta);
    unset($consulta);
    mysqli_close($canal);

    inicioSesion();
    $_SESSION["validado"] = true;
    $_SESSION["usuario"] = $usuario;
?>
