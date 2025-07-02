<?php
include_once("../functions.php");

// Determinar las fechas de inicio y final basado en la entrada del usuario o en la lógica predeterminada
if (isset($_POST['f_inicio_sc']) && isset($_POST['f_final_sc'])) {
    $fecha_inicio = $_POST['f_inicio_sc'];
    $fecha_final = $_POST['f_final_sc'];
} else {
    // Establecer la fecha de inicio al último domingo y la fecha final al próximo sábado
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


// Consulta SQL con filtro de sucursal aplicado
$sql = "SELECT
            tipoSalida,
            montoSalida,
            DATE_FORMAT(fechaSalida, '%d-%m-%Y %H:%i') AS fechaSalida,
            motivoSalida,
            idSucursal
        FROM
            salidas_capital
        WHERE
            fechaSalida BETWEEN '$fecha_inicio 00:01' AND '$fecha_final 23:59'
            $whereSucursal
        ORDER BY
            fechaSalida ASC";

$q = mysqli_query($conexion, $sql) or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

$jsonArray = [];

while ($r = mysqli_fetch_array($q)) {

    $jsonArray[] = [
        'tipoSalida' => $r['tipoSalida'],
        'montoSalida' => $r['montoSalida'],
        'fechaSalida' => $r['fechaSalida'],
        'motivoSalida' => $r['motivoSalida'],
        'idSucursal' => $r['idSucursal'] == 1 ? "SAN JOSÉ DEL CABO" : ($r['idSucursal'] == 2 ? "LA PAZ" :$r['idSucursal'])
    ];
}

exit(json_encode($jsonArray));
?>