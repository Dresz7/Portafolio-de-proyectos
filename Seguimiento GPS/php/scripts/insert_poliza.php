<?php
include_once('../functions.php');

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$query = mysqli_query($conexion, "SELECT * FROM info_poliza WHERE numeroPoliza = '$numeroPoliza'");

if (mysqli_num_rows($query) != 0) {
    die('Número de póliza ya registrado');
}

$sql = "INSERT INTO info_poliza(numeroPoliza, expedicionPoliza, vencimientoPoliza, aseguradora)
        VALUES ('$numeroPoliza', '$f_expedicion_poliza', '$f_vencimiento_poliza', '$aseguradora')";
$query = mysqli_query($conexion, $sql)
    or die(mysqli_error($conexion));

$poliza_id = mysqli_insert_id($conexion);

$sql = "INSERT INTO polizas(idVehiculo, idInformacionPoliza)
        VALUES  ('$idVehiculoP', '$poliza_id')";
$query = mysqli_query($conexion, $sql)
    or die(mysqli_error($conexion));

exit("0");