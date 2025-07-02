<?php
include_once("../functions.php");

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$sql = "SELECT * FROM vehiculos WHERE id = $idVehiculo";
$q = mysqli_query($conexion, $sql)
    or die('Error al ejecutar la operación');
if (mysqli_num_rows($q) != 1) {
    die('Existe más de un registro con el mismo id');
}
$r = mysqli_fetch_array($q);
$currentVin = $r['serie'];

$sql = "SELECT * FROM vehiculos WHERE serie = '$serie'";
$q = mysqli_query($conexion, $sql)
    or die('Error al ejecutar la operación');
$rs = mysqli_num_rows($q);

if ($rs == 1) {
    if ($currentVin != $serie) {
        die ('La serie indicada ya existe en un registro diferente');
    }
} else if ($rs > 1) {
    die('La serie indicada se encuentra en uso en más de un registro');
}
$sql = "UPDATE vehiculos
                SET
                serie = '$serie',
                placa = '$placa',
                marca = '$marca',
                linea = '$linea',
                modelo = $modelo,
                color = '$color',
                transmision = '$transmision',
                cilindros = '$cilindros',
                capacidadCarga = '$capacidadCarga',
                disponibilidad = '$radioOptions'
            WHERE id = '$idVehiculo'";
$q = mysqli_query($conexion, $sql)
    or die('Error al ejecutar la operación');

$sql = "INSERT INTO logs (operacion, idUsuario) VALUES ('edición vehículo', $idUsuario)";
mysqli_query($conexion, $sql);

exit("0");