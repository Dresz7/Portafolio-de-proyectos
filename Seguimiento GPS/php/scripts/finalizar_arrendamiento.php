<?php
include_once("../functions.php");

$id = $_POST['id'];
$vhid = $_POST['vhid'];

$dt = new DateTime();
$now = $dt->format('Y-m-d H:i:s');

$sql = "SELECT * FROM arrendamientos ar
        INNER JOIN vehiculos vh ON vh.id = ar.idVehiculo
        WHERE ar.id = '$id' AND vh.id = '$vhid'";
$q = mysqli_query($conexion, $sql)
    or die('Error al consultar');

if (mysqli_num_rows($q) != 1) {
    die('Registro no encontrado');
}

$sql = "UPDATE arrendamientos SET estadoArrendamiento = 0 WHERE id = $id;";
$sql .= "UPDATE vehiculos SET disponibilidad = 0 WHERE disponibilidad = 1 AND id = $vhid";
$multiquery = mysqli_multi_query($conexion, $sql)
    or die('Operación no realizada');

exit("0");