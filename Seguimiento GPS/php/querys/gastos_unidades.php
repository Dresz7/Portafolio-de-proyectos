<?php
include_once("../functions.php");
// Recuperar el idSucursal si se ha proporcionado
$idSucursal = isset($_SESSION['sucursal']) ? $_SESSION['sucursal'] : null;


// Verificar si f_inicio y f_final están presentes en el POST, de lo contrario, aplicar lógica predeterminada
$fecha_inicio = isset($_POST['f_inicio_gu']) && !empty($_POST['f_inicio_gu']) 
    ? $_POST['f_inicio_gu'] 
    : (date('D') != 'Sun' ? date('Y-m-d', strtotime('last Sunday')) : date('Y-m-d'));

$fecha_final = isset($_POST['f_final_gu']) && !empty($_POST['f_final_gu']) 
    ? $_POST['f_final_gu'] 
    : (date('D') != 'Sat' ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d'));




// Preparar la cláusula WHERE para filtrar por sucursal si se ha especificado una
$whereSucursal = $idSucursal != '0' ? " AND idSucursal = '$idSucursal'" : "";

// Consulta SQL con el filtro de sucursal aplicado
$sql = "SELECT
            CONCAT(vh.marca, ' ', vh.linea, ' ', vh.modelo, ' ', vh.placa) AS unidad,
            ga.montoGastoV,
            DATE_FORMAT(ga.fechaGastoV, '%d-%m-%Y %H:%i') AS fechaGasto,
            ga.motivoGastoV,
            ga.idSucursal
        FROM
            gastosvehiculos ga
        INNER JOIN
            vehiculos vh ON vh.id = ga.idVehiculo
        WHERE
            ga.fechaGastoV BETWEEN '$fecha_inicio 00:01' AND '$fecha_final 23:59'
            $whereSucursal
        ORDER BY
            ga.fechaGastoV ASC";

$q = mysqli_query($conexion, $sql) or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

$jsonArray = [];

while ($r = mysqli_fetch_array($q)) {


    $jsonArray[] = [
        'unidad' => $r['unidad'],
        'montoGasto' => $r['montoGastoV'],
        'fechaGasto' => $r['fechaGasto'],
        'motivoGasto' => $r['motivoGastoV'],
        'idSucursal' => $r['idSucursal'] = $r['idSucursal'] == 1 ? "SAN JOSÉ DEL CABO" : ($r['idSucursal'] == 2 ? "LA PAZ" : $r['idSucursal'])
    ];
}

exit(json_encode($jsonArray));
?>