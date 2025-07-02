<?php
include_once("../functions.php");

$now = new DateTime();
$nowDate = $now->format("Y-m-d");
$nowDt = $now->format("Y-m-d H:i:s");

$sql = "SELECT ar.id AS idArrendamiento,
        ar.estadoArrendamiento AS estadoArrendamiento,
        vh.id AS idVehiculo,
        concat(vh.marca,' ',vh.linea,' ',vh.modelo) AS unidad,
            CASE
            WHEN vh.disponibilidad = 0 THEN 'Disponible'
            WHEN vh.disponibilidad = 1 THEN 'Arrendado'
            WHEN vh.disponibilidad = 2 THEN 'Fuera de servicio'
            END AS disponibilidad,
        DATE_FORMAT(ar.fechaVencimiento, '%d-%m-%Y %H:%i') AS fechaEntrega,
        DATEDIFF(ar.fechaVencimiento, '$nowDt') AS diasRestantes,
        FLOOR(ar.montoTotal/DATEDIFF(ar.fechaVencimiento, ar.fechaExpedicion)) AS montoDiario,
        ar.deposito AS deposito
    FROM vehiculos vh
    LEFT JOIN arrendamientos ar ON ar.idVehiculo = vh.id AND ar.estadoArrendamiento != 0
    ORDER BY concat(vh.marca,' ',vh.linea,' ',vh.modelo) ASC";
$query = mysqli_query($conexion, $sql);

$jsonArray = array();

while ($row = mysqli_fetch_array($query)) {
    $idArrendamiento = ($row['idArrendamiento'] != null) ? $row['idArrendamiento'] : 0;
    $idVehiculo = $row['idVehiculo'];
    $estadoArrendamiento = ($row['estadoArrendamiento'] != null) ? $row['estadoArrendamiento'] : 0;
    $fechaEntrega = ($row['fechaEntrega'] != null) ? $row['fechaEntrega'] : '-';
    $diasRestantes = ($row['diasRestantes'] != null) ? $row['diasRestantes'] : '-';
    $montoDiario = ($row['montoDiario'] != null) ? $row['montoDiario'] : 0;
    $deposito = ($row['deposito'] == 1) ? 5000 : 0;

    $sql4 = "SELECT SUM(montoGastoV) AS gastosVehiculo FROM gastosvehiculos WHERE fechaGastoV
            BETWEEN '$nowDate 00:01:00' AND '$nowDate 23:59:00' AND idVehiculo = '$idVehiculo'";
    $query4 = mysqli_query($conexion, $sql4);
    $row4 = mysqli_fetch_array($query4);
    $gastosVehiculo = ($row4['gastosVehiculo'] != null) ? $row4['gastosVehiculo'] : 0;

    if ($estadoArrendamiento != 0) {
        $sql1 = "SELECT SUM(ip.montoSaldado) AS saldadoTransferencia FROM pagos pa
        INNER JOIN info_pago ip ON pa.idInformacionPago = ip.id
        WHERE pa.idArrendamiento = '$idArrendamiento' AND ip.motivoPago = 'pago arrendamiento' AND ip.metodoPago = 'transferencia'";
        $query1 = mysqli_query($conexion, $sql1);
        $row1 = mysqli_fetch_array($query1);
        $transferencia = ($row1['saldadoTransferencia'] != null) ? $row1['saldadoTransferencia'] : 0;

        $sql2 = "SELECT SUM(ip.montoSaldado) AS saldadoEfectivo FROM pagos pa
        INNER JOIN info_pago ip ON pa.idInformacionPago = ip.id
        WHERE pa.idArrendamiento = '$idArrendamiento' AND ip.motivoPago = 'pago arrendamiento' AND ip.metodoPago = 'efectivo'";
        $query2 = mysqli_query($conexion, $sql2);
        $row2 = mysqli_fetch_array($query2);
        $efectivo = ($row2['saldadoEfectivo'] != null) ? $row2['saldadoEfectivo'] : 0;

        $sql3 = "SELECT SUM(montoPenalizacion) AS montoPenalizacion FROM penalizaciones WHERE idArrendamiento = '$idArrendamiento'";
        $query3 = mysqli_query($conexion, $sql3);
        $row3 = mysqli_fetch_array($query3);
        $penalizacion = ($row3['montoPenalizacion'] != null) ? $row3['montoPenalizacion'] : 0;

        $jsonArray[] = array(
            'unidad' => $row['unidad'],
            'disponibilidad' => $row['disponibilidad'],
            'fechaEntrega' => $fechaEntrega,
            'diasRestantes' => $diasRestantes,
            'montoDiario' => $montoDiario,
            'deposito' => $deposito,
            'transferencia' => $transferencia,
            'efectivo' => $efectivo,
            'penalizacion' => $penalizacion,
            'montoCubierto' => $transferencia + $efectivo,
            'gastosVehiculo' => $gastosVehiculo
        );
    } else {
        $jsonArray[] = array(
            'unidad' => $row['unidad'],
            'disponibilidad' => $row['disponibilidad'],
            'fechaEntrega' => '-',
            'diasRestantes' => '-',
            'montoDiario' => 0,
            'deposito' => 0,
            'transferencia' => 0,
            'efectivo' => 0,
            'penalizacion' => 0,
            'montoCubierto' => 0,
            'gastosVehiculo' => $gastosVehiculo
        );
    }
}

$jsonstring = json_encode($jsonArray);

echo $jsonstring;
