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
DATEDIFF(ar.fechaVencimiento, ar.fechaExpedicion) AS dias_rentado,
vh.tarifa,
ar.montoTotal, 
ar.idSucursal, 
ar.odometro, 
ar.idClienteAd,
clAd.curp AS curpAd,
CONCAT(clAd.nombres, ' ', clAd.apellidoPaterno, ' ', clAd.apellidoMaterno) AS nombreCompletoAdicional,
ar.estadoArrendamiento,
(SELECT COUNT(*) FROM penalizaciones pe WHERE pe.idArrendamiento = ar.id) AS dias_penalizados,
pe.montoPenalizacion, pe.fechaPenalizacion
FROM arrendamientos ar
INNER JOIN clientes cl ON cl.id = ar.idCliente
LEFT JOIN clientes clAd ON clAd.id = ar.idClienteAd
INNER JOIN vehiculos vh ON vh.id = ar.idVehiculo
INNER JOIN penalizaciones pe ON pe.idArrendamiento=ar.id
WHERE ar.estadoArrendamiento = 2 AND ar.id=$arrendamientoId";

// Realizar la consulta
$q = mysqli_query($conexion, $sql) or die("Error: " . mysqli_error($conexion));

// Preparar los datos para la respuesta
$jsonArray = array();
while ($row = mysqli_fetch_array($q)) {
    $jsonArray[] = array(
        'fechapenalizacion' => $row['fechaPenalizacion'],
        'deposito' => $row['montoPenalizacion'],
        'diasp' => $row['dias_penalizados']
    );
}

// Cerrar conexión a base de datos
mysqli_close($conexion);

$jsonstring = json_encode($jsonArray);

echo $jsonstring;
?>