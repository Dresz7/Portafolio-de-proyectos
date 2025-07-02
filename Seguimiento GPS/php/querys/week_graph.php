<?php
include_once('../functions.php');
$idSucursal = $_SESSION['sucursal'];

// Determinar fechas de inicio y fin de la semana
$staticstart = date('D') != 'Sun' ? date('Y-m-d', strtotime('last Sunday')) : date('Y-m-d');
$staticfinish = date('D') != 'Sat' ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');

// Inicializa los arrays para almacenar los datos
$jsonArray = ['LA PAZ' => [], 'SAN JOSE DEL CABO' => [], 'ACUMULADO' => []];
$acumuladoDiario = [];

$sucursales = $idSucursal == 0 ? [1, 2] : [$idSucursal];

foreach ($sucursales as $sucursal) {
    $dtStart = new DateTime($staticstart);
    
    while ($dtStart->format("Y-m-d") <= $staticfinish) {
        $domingo = $dtStart->format("Y-m-d");
        
        // Consultas para calcular los balances
        $whereSucursal = $sucursal == 0 ? "" : " AND idSucursal = $sucursal";
        $querySaldo = "SELECT SUM(montoSaldado) AS saldado FROM info_pago WHERE fechaPago BETWEEN '$domingo 00:00:01' AND '$domingo 23:59:59'$whereSucursal";
        $queryGastoV = "SELECT SUM(montoGastoV) AS gastov FROM gastosvehiculos WHERE fechaGastoV BETWEEN '$domingo 00:01:00' AND '$domingo 23:59:59'$whereSucursal";
        $queryGasto = "SELECT SUM(montoGasto) AS gasto FROM gastos WHERE fechaGasto BETWEEN '$domingo 00:00:01' AND '$domingo 23:59:59'$whereSucursal";
        
        // Ejecutar las consultas y procesar los resultados
        $saldo = mysqli_fetch_assoc(mysqli_query($conexion, $querySaldo))['saldado'] ?? 0;
        $gastoV = mysqli_fetch_assoc(mysqli_query($conexion, $queryGastoV))['gastov'] ?? 0;
        $gasto = mysqli_fetch_assoc(mysqli_query($conexion, $queryGasto))['gasto'] ?? 0;

        $balance = $saldo - $gastoV - $gasto;

        // Asignar el balance al array correspondiente
        if ($sucursal == 1) {
            $jsonArray['SAN JOSE DEL CABO'][] = $balance;
        } elseif ($sucursal == 2) {
            $jsonArray['LA PAZ'][] = $balance;
        }

        // Calcular el acumulado diario
        if (!isset($acumuladoDiario[$domingo])) {
            $acumuladoDiario[$domingo] = 0;
        }
        $acumuladoDiario[$domingo] += $balance;

        $dtStart->modify('+1 day');
    }
}

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($jsonArray);
?>