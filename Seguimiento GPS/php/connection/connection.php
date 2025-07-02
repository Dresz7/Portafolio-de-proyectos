<?php
$conexion = new mysqli("localhost", "root", "", "BajaRent");
$conexion->set_charset("utf8");

mysqli_query($conexion, "SET NAMES 'utf8'");

if ($conexion->connect_errno) {
    echo "FALLO AL CONECTAR A LA BD: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
}
