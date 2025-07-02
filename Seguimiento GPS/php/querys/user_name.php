<?php
include_once("../functions.php");

if (isset($_SESSION['userName']) && isset($_SESSION['nombre']) && isset($_SESSION['tipoUsuario']) ){
    $jsonstring = json_encode(array(
        'userName' => $_SESSION['userName'],
        'nombre' => $_SESSION['nombre'],
        'tipoUsuario' => $_SESSION['tipoUsuario']
    ));
    exit($jsonstring);
}

die("Sesión expirada, recarga la página");