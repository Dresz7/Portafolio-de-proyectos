<?php
include_once("../functions.php");

// Establecer fechas de inicio y fin basadas en la entrada del usuario o en la lógica de fechas predeterminada
if (isset($_POST['f_inicio_ac']) && isset($_POST['f_final_ac'])) {
    $fecha_inicio = $_POST['f_inicio_ac'];
    $fecha_final = $_POST['f_final_ac'];
} else {
    $staticstart = date('D') != 'Sun' ? date('Y-m-d', strtotime('last Sunday')) : date('Y-m-d');
    $staticfinish = date('D') != 'Sat' ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');
    $fecha_inicio = $staticstart;
    $fecha_final = $staticfinish;
}

// Recuperar el idSucursal si se ha proporcionado
$idSucursal = isset($_SESSION['sucursal']) ? $_SESSION['sucursal'] : null;

$whereSucursal = '';
if (in_array($idSucursal, ['1', '2'])) {
    $whereSucursal = " AND idSucursal = $idSucursal";
}

// Preparar y ejecutar la consulta SQL con el filtro de sucursal aplicado
$sql = "SELECT
            tipoAportacion,
            montoAportacion,
            DATE_FORMAT(fechaAportacion, '%d-%m-%Y') AS fechaAportacion,
            idSucursal
        FROM
            aportaciones_capital
        WHERE
            fechaAportacion BETWEEN '$fecha_inicio' AND '$fecha_final'
            $whereSucursal
        ORDER BY
            fechaAportacion ASC";

$q = mysqli_query($conexion, $sql) or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

$jsonArray = [];

while ($r = mysqli_fetch_array($q)) {
    $jsonArray[] = [
        'tipoAportacion' => $r['tipoAportacion'],
        'montoAportacion' => $r['montoAportacion'],
        'fechaAportacion' => $r['fechaAportacion'],
        'idSucursal' => $r['idSucursal'] = $r['idSucursal'] == 1 ? "SAN JOSÉ DEL CABO" : ($r['idSucursal'] == 2 ? "LA PAZ" : $r['idSucursal'])
    ];
}

exit(json_encode($jsonArray));
?>