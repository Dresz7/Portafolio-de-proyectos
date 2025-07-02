
<?php
include_once("../functions.php");

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$dt_expedicion = new DateTime($f_expedicion_datetime);
$t_expedicion = $dt_expedicion->format('H:i:s');

$dt_vencimiento = new DateTime($f_vencimiento_datetime);
$t_vencimiento = $dt_vencimiento->format('H:i:s');

if ($t_expedicion != $t_vencimiento) {
    die("La hora de expedición y vencimiento deben mantenerse iguales");
}
$sql = "SELECT
            *
        FROM
            arrendamientos
        WHERE
            idVehiculo = '$id_vehiculo_txt'
        AND
            (estadoArrendamiento = 1 OR estadoArrendamiento = 2)";
$q = mysqli_query($conexion, $sql)
    or die('Error al realizar el registro');

if (mysqli_num_rows($q) != 0)
    die('La unidad no se encuentra disponible');

$sql = "SELECT
            *
        FROM
            vehiculos
        WHERE
            id = '$id_vehiculo_txt'
        AND
            disponibilidad = 0";
$q = mysqli_query($conexion, $sql)
    or die('Error al realizar el registro');

if (mysqli_num_rows($q) != 1)
    die('La unidad no se encuentra disponible');

$sql = "INSERT INTO
            arrendamientos
            (idCliente,
            idVehiculo,
            fechaExpedicion,
            fechaVencimiento,
            montoTotal,
            deposito,
            odometro,
            estadoArrendamiento,
            idClienteAd,
            idSucursal)
        VALUES
            ('$idClienteH',
            '$id_vehiculo_txt',
            '$f_expedicion_datetime',
            '$f_vencimiento_datetime',
            '$monto_txt',
            '1',
            '$odometro',
            '1',
            '$idClienteAdH',
            '$sucursalSelect')";
$q = mysqli_query($conexion, $sql)
    or die('Error al registrar arrendamiento');

$idArrendamiento = mysqli_insert_id($conexion);

$sql = "UPDATE
            vehiculos
        SET
            disponibilidad = 1
        WHERE
            id = $id_vehiculo_txt";
$q = mysqli_query($conexion, $sql)
    or die('Error al cambiar disponibilidad del vehículo');

$sql = "INSERT INTO
            logs
            (operacion,
            idUsuario)
        VALUES
            ('alta arrendamiento',
            $idUsuario)";
$q = mysqli_query($conexion, $sql);

$jsonString = json_encode(array(
    'respuesta' => 0,
    'idArrendamiento' => $idArrendamiento
));

exit($jsonString);