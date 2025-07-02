<?php
include_once('../functions.php');

// Verificar y obtener los parámetros POST
$fechaInicio = $_POST['feg_inicio'] ?? die("Fecha de inicio no recibida");
$fechaFinal = $_POST['feg_final'] ?? die("Fecha de cierre no recibida");
$sucursalSelect = $_POST['sucursalSelect'] ?? '';

// Preparar la cláusula WHERE para filtrar por sucursal si es necesario
$whereSucursal = '';
if ($sucursalSelect === '1' || $sucursalSelect === '2') {
    $whereSucursal = " AND idSucursal = '$sucursalSelect'";
}

// Preparar la consulta SQL agregando el filtro de sucursal y utilizando COALESCE
$sql = "SELECT
            COALESCE((SELECT SUM(montoSaldado)
             FROM info_pago
             WHERE metodoPago = 'efectivo'
             AND fechaPago BETWEEN '$fechaInicio 00:01:00' AND '$fechaFinal 23:59:00' $whereSucursal), 0) AS entradasEfectivo,
            COALESCE((SELECT SUM(montoSaldado)
             FROM info_pago
             WHERE metodoPago = 'transferencia'
             AND fechaPago BETWEEN '$fechaInicio 00:01:00' AND '$fechaFinal 23:59:00' $whereSucursal), 0) AS entradasTransferencia,
            COALESCE((SELECT SUM(montoGasto)
             FROM gastos
             WHERE fechaGasto BETWEEN '$fechaInicio 00:01:00' AND '$fechaFinal 23:59:00' $whereSucursal), 0) AS gastosGenerales,
            COALESCE((SELECT SUM(montoGastoV)
             FROM gastosvehiculos
             WHERE fechaGastoV BETWEEN '$fechaInicio 00:01:00' AND '$fechaFinal 23:59:00' $whereSucursal), 0) AS gastosVehiculos";

$query = mysqli_query($conexion, $sql);

// Verificar si la consulta fue exitosa
if (!$query) {
    die('Error en la consulta: ' . mysqli_error($conexion));
}

$res = mysqli_fetch_array($query);

// Preparar el arreglo de respuesta con los resultados obtenidos
$jsonArray = [
    ['concepto' => 'Ingresos:', 'cantidad' => '', 'sumatoria' => ''],
    ['concepto' => 'Por transferencia', 'cantidad' => $res['entradasTransferencia'], 'sumatoria' => ''],
    ['concepto' => 'En efectivo', 'cantidad' => $res['entradasEfectivo'], 'sumatoria' => ''],
    ['concepto' => 'Suman los ingresos', 'cantidad' => '', 'sumatoria' => $res['entradasEfectivo'] + $res['entradasTransferencia']],
    ['concepto' => 'Menos:', 'cantidad' => '', 'sumatoria' => ''],
    ['concepto' => 'Compras y gastos generales', 'cantidad' => -$res['gastosGenerales'], 'sumatoria' => ''],
    ['concepto' => 'Gastos de mantenimiento y reparación', 'cantidad' => -$res['gastosVehiculos'], 'sumatoria' => ''],
    ['concepto' => 'Total de gastos', 'cantidad' => '', 'sumatoria' => -($res['gastosVehiculos'] + $res['gastosGenerales'])],
];

// Devolver los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($jsonArray);
?>