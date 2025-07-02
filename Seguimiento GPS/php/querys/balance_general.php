<?php
include_once("../functions.php");

$sucursalselect = (isset($_POST['sucursalSelect']))  ? $_POST['sucursalSelect']: die("Sucursal no recibida");

$fechaInicio = (isset($_POST['fb_inicio']))
    ? $_POST['fb_inicio']
    : die("Fecha de inicio no recibida");

$fechaFinal = (isset($_POST['fb_final']))
    ? $_POST['fb_final']
    : die("Fecha de cierre no recibida");

$fechaInicio = $_POST['fb_inicio'] ?? die("Fecha de inicio no recibida");
$fechaFinal = $_POST['fb_final'] ?? die("Fecha de cierre no recibida");

// Calcula las fechas de inicio y fin de la semana
$dateTimeInicial = new DateTime($fechaInicio);
$dateTimeFinal = new DateTime($fechaFinal);
$domingo = $dateTimeInicial->format('D') != 'Sun' ? $dateTimeInicial->modify('last Sunday')->format('Y-m-d') : $dateTimeInicial->format('Y-m-d');
$sabado = $dateTimeFinal->format('D') != 'Sat' ? $dateTimeFinal->modify('next Saturday')->format('Y-m-d') : $dateTimeFinal->format('Y-m-d');

$whereSucursal = '';
if (in_array($sucursalselect, ['1', '2'])) {
    $whereSucursal = " AND idSucursal = $sucursalselect";
}

$sql = "SELECT
    SUM(CASE WHEN metodoPago = 'efectivo' THEN montoSaldado ELSE 0 END) AS entradasEfectivo,
    SUM(CASE WHEN metodoPago = 'transferencia' THEN montoSaldado ELSE 0 END) AS entradasTransferencia,
    (SELECT SUM(montoSalida) FROM salidas_capital WHERE fechaSalida BETWEEN '$fechaInicio 00:01:00' AND '$fechaFinal 23:59:00' $whereSucursal) AS salidasCapital,
    (SELECT SUM(montoGasto) FROM gastos WHERE fechaGasto BETWEEN '$fechaInicio 00:01:00' AND '$fechaFinal 23:59:00' $whereSucursal) AS gastosGenerales,
    (SELECT SUM(montoGastoV) FROM gastosvehiculos WHERE fechaGastoV BETWEEN '$fechaInicio 00:01:00' AND '$fechaFinal 23:59:00' $whereSucursal) AS gastosVehiculos,
    (SELECT SUM(montoAportacion) FROM aportaciones_capital WHERE fechaAportacion BETWEEN '$fechaInicio' AND '$fechaFinal' $whereSucursal) AS aportaciones,
    (SELECT SUM(montoSc) FROM saldo_caja WHERE tipoSc = 'inicial' AND fechaSc BETWEEN '$domingo' AND '$sabado' $whereSucursal) AS saldoInicial
FROM info_pago
WHERE fechaPago BETWEEN '$fechaInicio 00:01:00' AND '$fechaFinal 23:59:00' $whereSucursal;";

$query = mysqli_query($conexion, $sql);
$res = mysqli_fetch_array($query);

// Limpiar los datos nulos y convertirlos a 0
$campos = ['entradasEfectivo', 'entradasTransferencia', 'salidasCapital', 'gastosGenerales', 'gastosVehiculos', 'aportaciones', 'saldoInicial'];
foreach ($campos as $campo) {
    $res[$campo] = $res[$campo] ?? 0; // Usamos el operador de fusión de null de PHP 7+
}

// Calcular sumatorias específicas
$sumatoriaIngresos = $res['entradasEfectivo'] + $res['entradasTransferencia'] + $res['aportaciones'] + $res['saldoInicial'];
$totalGastos = $res['salidasCapital'] + $res['gastosGenerales'] + $res['gastosVehiculos'] + $res['entradasTransferencia'];

$jsonArray = [
    ['concepto' => 'Ingresos:', 'cantidad' => '', 'sumatoria' => ''],
    ['concepto' => 'Por transferencia', 'cantidad' => $res['entradasTransferencia'], 'sumatoria' => ''],
    ['concepto' => 'En efectivo', 'cantidad' => $res['entradasEfectivo'], 'sumatoria' => ''],
    ['concepto' => 'Financiamiento y aportaciones', 'cantidad' => $res['aportaciones'], 'sumatoria' => ''],
    ['concepto' => 'Saldo inicial semana', 'cantidad' => $res['saldoInicial'], 'sumatoria' => ''],
    ['concepto' => 'Suman los ingresos', 'cantidad' => '', 'sumatoria' => $sumatoriaIngresos],
    ['concepto' => '', 'cantidad' => '', 'sumatoria' => ''],
    ['concepto' => 'Menos:', 'cantidad' => '', 'sumatoria' => ''],
    ['concepto' => 'Transferencias', 'cantidad' => -$res['entradasTransferencia'], 'sumatoria' => ''],
    ['concepto' => 'Salidas de capital', 'cantidad' => -$res['salidasCapital'], 'sumatoria' => ''],
    ['concepto' => 'Compras y gastos generales', 'cantidad' => -$res['gastosGenerales'], 'sumatoria' => ''],
    ['concepto' => 'Gastos de mantenimiento y reparación', 'cantidad' => -$res['gastosVehiculos'], 'sumatoria' => ''],
    ['concepto' => 'Total de gastos y salidas', 'cantidad' => '', 'sumatoria' => -$totalGastos],
];



exit(json_encode($jsonArray));
