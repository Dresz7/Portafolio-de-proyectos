<?php
include_once("../functions.php");

$fechaInicio = (isset($_POST['fb_inicio']))
    ? $_POST['fb_inicio']
    : die("Fecha de inicio no recibida");

$fechaFinal = (isset($_POST['fb_final']))
    ? $_POST['fb_final']
    : die("Fecha de cierre no recibida");

$sql = "SELECT
        (SELECT
            SUM(montoSaldado)
        AS
            montoSaldado
        FROM
            info_pago
        WHERE
            metodoPago = 'efectivo'
        AND
            fechaPago
        BETWEEN
            '$fechaInicio 00:01:00' 
        AND
            '$fechaFinal 23:59:00')
        AS
            entradasEfectivo,
        (SELECT
            SUM(montoSaldado)
        AS
            montoSaldado
        FROM
            info_pago
        WHERE
            metodoPago = 'transferencia'
        AND
            fechaPago
        BETWEEN
            '$fechaInicio 00:01:00'
        AND
            '$fechaFinal 23:59:00')
        AS
            entradasTransferencia,
        (SELECT
            SUM(montoGasto)
        FROM
            gastos
        WHERE
            fechaGasto
        BETWEEN
            '$fechaInicio  00:01:00'
        AND
            '$fechaFinal 23:59:00')
        AS
            gastosGenerales,
        (SELECT
            SUM(montoGastoV)
        FROM
            gastosvehiculos
        WHERE
            fechaGastoV
        BETWEEN
            '$fechaInicio 00:01:00'
        AND
            '$fechaFinal 23:59:00')
        AS
            gastosVehiculos
        LIMIT
            1";

$query = mysqli_query($conexion, $sql);

$res = mysqli_fetch_array($query);

$jsonArray = array();

$res['entradasEfectivo'] = (is_null($res['entradasEfectivo']))
    ? 0
    : $res['entradasEfectivo'];

$res['entradasTransferencia'] = (is_null($res['entradasTransferencia']))
    ? 0
    : $res['entradasTransferencia'];

$res['gastosGenerales'] = (is_null($res['gastosGenerales']))
    ? 0
    : $res['gastosGenerales'];

$res['gastosVehiculos'] = (is_null($res['gastosVehiculos']))
    ? 0
    : $res['gastosVehiculos'];

$jsonArray[] = array (
    'concepto' => 'Ingresos Efectivo',
    'cantidad' => $res['entradasEfectivo'],
    'sumatoria' => ''
);

$jsonArray[] = array (
    'concepto' => 'Ingresos Transferencia',
    'cantidad' => $res['entradasTransferencia'],
    'sumatoria' => ''
);

$jsonArray[] = array(
    'concepto' => 'Total de ingresos',
    'cantidad' => '',
    'sumatoria' => $res['entradasEfectivo'] + $res['entradasTransferencia']
);

$jsonArray[] = array(
    'concepto' => 'Gastos Generales',
    'cantidad' => - $res['gastosGenerales'],
    'sumatoria' => ''
);

$jsonArray[] = array(
    'concepto' => 'Gastos de Mantenimiento y Reparacion',
    'cantidad' => - $res['gastosVehiculos'],
    'sumatoria' => ''
);

$jsonArray[] = array(
    'concepto' => 'Total de gastos',
    'cantidad' => '',
    'sumatoria' => - ($res['gastosVehiculos'] + $res['gastosGenerales'])
);

exit(json_encode($jsonArray));