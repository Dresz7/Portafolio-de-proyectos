<?php
include_once("../functions.php");
$idSucursal = $_SESSION['sucursal'];

// Determina si se aplica filtro de sucursal
$whereSucursal = isset($idSucursal) && $idSucursal != 0 ? " AND ar.idSucursal = $idSucursal" : "";

$sql = "SELECT 
            ar.id, 
            vh.id AS vhid, 
            CONCAT(vh.linea, ' ', vh.modelo, ' ', vh.color) AS vhRef, 
            DATE_FORMAT(ar.fechaVencimiento, '%d-%m-%Y %H:%i:%s') AS vencimiento, 
            ar.montoTotal, ar.idSucursal,
            ar.estadoArrendamiento,
            CONCAT(cl.nombres, ' ', cl.apellidoPaterno, ' ', cl.apellidoMaterno) AS nombreCompleto, 
            vh.serie
        FROM arrendamientos ar
        INNER JOIN clientes cl ON cl.id = ar.idCliente
        INNER JOIN vehiculos vh ON vh.id = ar.idVehiculo
        WHERE ar.estadoArrendamiento != 0 $whereSucursal";

$q = mysqli_query($conexion, $sql) or die("Error: " . mysqli_error($conexion));

$jsonArray = [];

while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
    $idArrendamiento = $row['id'];

    // Obtiene la suma de los montos saldados
    $sqlPagos = "SELECT SUM(ip.montoSaldado) AS montoSaldado
                 FROM pagos pa
                 INNER JOIN info_pago ip ON pa.idInformacionPago = ip.id
                 WHERE pa.idArrendamiento = $idArrendamiento";
    $resultPagos = mysqli_query($conexion, $sqlPagos) or die(mysqli_error($conexion));
    $montoSaldado = mysqli_fetch_array($resultPagos)['montoSaldado'] ?? 0;

    // Obtiene la suma de las penalizaciones
    $sqlPenalizaciones = "SELECT SUM(montoPenalizacion) AS montoPenalizacion
                          FROM penalizaciones
                          WHERE idArrendamiento = $idArrendamiento";
    $resultPenalizaciones = mysqli_query($conexion, $sqlPenalizaciones) or die(mysqli_error($conexion));
    $montoPenalizacion = mysqli_fetch_array($resultPenalizaciones)['montoPenalizacion'] ?? 0;

    // Ajusta el monto total considerando los pagos y penalizaciones
    $montoFinal = $row['montoTotal'] - $montoSaldado + $montoPenalizacion;

    $jsonArray[] = [
        'id' => $row['id'],
        'vhid' => $row['vhid'],
        'nombreCompleto' => $row['nombreCompleto'],
        'fechaVencimiento' => $row['vencimiento'],
        'monto' => $montoFinal,
        'estadoArrendamiento' => $row['estadoArrendamiento'],
        'serie' => $row['serie'],
        'vhRef' => $row['vhRef'],
        'idSucursal' => $row['idSucursal'] = $row['idSucursal'] == 1 ? "SAN JOSÉ DEL CABO" : ($row['idSucursal'] == 2 ? "LA PAZ" : $row['idSucursal'])
    ];
}

mysqli_close($conexion);

exit(json_encode($jsonArray));
?>