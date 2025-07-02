<?php
include_once("../functions.php");

$dt = new DateTime();
$now = $dt->format('Y-m-d H:i:s');
$nowd = $dt->format('Y-m-d');

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$sql = "INSERT INTO info_pago (montoSaldado, fechaPago, metodoPago, motivoPago, idSucursal)
        VALUES ($montoAbono, '$now', '$metodoPago', 'pago arrendamiento', '$sucursalSelect')";
$q = mysqli_query($conexion, $sql)
    or die('Información de pago no registrada');

$idInfoPago = mysqli_insert_id($conexion);

$sql = "INSERT INTO pagos (idArrendamiento, idInformacionPago)
        VALUES ($idArrendamiento, $idInfoPago)";
$q = mysqli_query($conexion, $sql)
    or die('Error: ' . mysqli_error($conexion));

$sql = "INSERT INTO logs (operacion, idUsuario) VALUES ('pago arrendamiento', $idUsuario)";
mysqli_query($conexion, $sql);

$q1 = mysqli_query($conexion, "SELECT montoTotal
        FROM arrendamientos
        WHERE id = $idArrendamiento")
    or die(mysqli_error($conexion));
$row = mysqli_fetch_array($q1);

$q2 = mysqli_query($conexion, "SELECT SUM(ip.montoSaldado) AS montoSaldado
        FROM pagos pa
        INNER JOIN info_pago ip ON pa.idInformacionPago = ip.id
        WHERE pa.idArrendamiento = $idArrendamiento")
    or die(mysqli_error($conexion));
if ($r2 = mysqli_fetch_array($q2)) {
    $row['montoTotal'] = ($r2['montoSaldado'] != null)
        ? $row['montoTotal'] - $r2['montoSaldado']
        : $row['montoTotal'];
}

$q3 = mysqli_query($conexion, "SELECT SUM(montoPenalizacion) AS montoPenalizacion
        FROM penalizaciones
        WHERE idArrendamiento = $idArrendamiento")
    or die(mysqli_error($conexion));
if ($r3 = mysqli_fetch_array($q3)) {
    $row['montoTotal'] = ($r3['montoPenalizacion'] != null)
        ? $row['montoTotal'] + $r3['montoPenalizacion']
        : $row['montoTotal'];
}

if ($row['montoTotal'] == 0) {
    mysqli_query($conexion, "UPDATE arrendamientos SET fechaEPago = '$nowd'
        WHERE id = $idArrendamiento");
}

$jsonString = json_encode(array(
    'respuesta' => '0',
    'idArrendamiento' => $idArrendamiento,
    'idInfoPago' => $idInfoPago
));

exit($jsonString);