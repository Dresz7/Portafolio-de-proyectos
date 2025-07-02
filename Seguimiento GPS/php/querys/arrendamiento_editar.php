<?php
include_once("../functions.php");

// Asume que arrendamientoId viene de una solicitud AJAX
$arrendamientoId = isset($_GET['arrendamientoId']) ? $_GET['arrendamientoId'] : die("Arrendamiento ID es requerido.");

$sql = "SELECT 
ar.id, 
cl.curp,
CONCAT(cl.nombres, ' ', cl.apellidoPaterno, ' ', cl.apellidoMaterno) AS nombreCompleto, 
vh.id AS vhid, 
vh.serie,
CONCAT(vh.linea, ' ', vh.modelo, ' ', vh.color) AS vhRef,
DATE_FORMAT(ar.fechaExpedicion, '%d-%m-%Y %H:%i:%s') AS expedicion,
DATE_FORMAT(ar.fechaVencimiento, '%d-%m-%Y %H:%i:%s') AS vencimiento,
vh.tarifa,
ar.montoTotal, 
ar.idSucursal, 
ar.odometro, 
ar.idClienteAd,
clAd.curp AS curpAd,
CONCAT(clAd.nombres, ' ', clAd.apellidoPaterno, ' ', clAd.apellidoMaterno) AS nombreCompletoAdicional,
ar.estadoArrendamiento
FROM arrendamientos ar
INNER JOIN clientes cl ON cl.id = ar.idCliente
LEFT JOIN clientes clAd ON clAd.id = ar.idClienteAd
INNER JOIN vehiculos vh ON vh.id = ar.idVehiculo
WHERE ar.estadoArrendamiento != 0 AND ar.id = $arrendamientoId";

// Realizar la consulta
$q = mysqli_query($conexion, $sql) or die("Error: " . mysqli_error($conexion));

// Preparar los datos para la respuesta
$jsonArray = [];
if($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
    // Puedes calcular los montos saldados y penalizaciones aquí si es necesario
    // Añadir fila de datos al arreglo de respuesta
    $jsonArray = $row;
}

// Cerrar conexión a base de datos
mysqli_close($conexion);

// Devolver datos en formato JSON
echo json_encode($jsonArray);
?>