<?php
include_once("../functions.php");

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$sql = "INSERT INTO gastosvehiculos (idVehiculo, montoGastoV, fechaGastoV, motivoGastoV,idSucursal)
        VALUES ('$vehiculoGasto', '$montoGasto', '$fechaGasto', '$motivoGasto','$sucursalSelect')";
$query = mysqli_query($conexion, $sql)
    or die('Operación fallida');

exit('0');