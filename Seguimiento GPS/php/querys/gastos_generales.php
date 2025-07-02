<?php
include_once("../functions.php");

// Asumiendo que ya has iniciado sesión para usar $_SESSION
$idSucursal = $_SESSION['sucursal'];

// Verificar si f_inicio y f_final están presentes en el POST, de lo contrario, aplicar lógica predeterminada
$fecha_inicio = isset($_POST['f_inicio']) && !empty($_POST['f_inicio']) 
    ? $_POST['f_inicio'] 
    : (date('D') != 'Sun' ? date('Y-m-d', strtotime('last Sunday')) : date('Y-m-d'));

$fecha_final = isset($_POST['f_final']) && !empty($_POST['f_final']) 
    ? $_POST['f_final'] 
    : (date('D') != 'Sat' ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d'));

// Preparar la cláusula WHERE para filtrar por sucursal si se ha especificado una
$whereSucursal = $idSucursal != '0' ? " AND idSucursal = '$idSucursal'" : "";

// Consulta SQL con el filtro de sucursal aplicado
$sql = "SELECT
            idSucursal,
            montoGasto,
            DATE_FORMAT(fechaGasto, '%d-%m-%Y %H:%i') AS fechaGasto,
            motivoGasto
        FROM
            gastos
        WHERE 
            fechaGasto BETWEEN '$fecha_inicio 00:01' AND '$fecha_final 23:59'
            $whereSucursal
        ORDER BY fechaGasto ASC";

$q = mysqli_query($conexion, $sql) or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

$jsonArray = [];

while ($r = mysqli_fetch_array($q)) {
    // Traducir el idSucursal a nombre de sucursal para la respuesta JSON
    $nombreSucursal = $r['idSucursal'] == 1 ? "SAN JOSÉ DEL CABO" : ($r['idSucursal'] == 2 ? "LA PAZ" : "Otra");

    $jsonArray[] = [
        'montoGasto' => $r['montoGasto'],
        'fechaGasto' => $r['fechaGasto'],
        'motivoGasto' => $r['motivoGasto'],
        'idSucursal' => $nombreSucursal
    ];
}

exit(json_encode($jsonArray));
?>

