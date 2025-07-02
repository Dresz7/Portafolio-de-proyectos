<?php
include_once("../connection/connection.php");

session_start();

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE userName = '$username'");
$row =  mysqli_fetch_array($query);

if (mysqli_num_rows($query) == 1) {
    if ($password == $row['password']) {
        $_SESSION['idUsuario'] = $row['id'];
        $_SESSION['userName'] = $row['userName'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['tipoUsuario'] = $row['tipoUsuario'];
        $_SESSION['sucursal'] = $row['idSucursal'];
        $conexion->close();
        echo $row['userName'] . ",";
        ($row['tipoUsuario'] == "ADMINISTRADOR")
            ? exit("administrador")
            : exit("estandar");
    } else {
        $conexion->close();
        die("Contraseña incorrecta");
    }
} else {
    $conexion->close();
    die("Usuario no registrado");
}