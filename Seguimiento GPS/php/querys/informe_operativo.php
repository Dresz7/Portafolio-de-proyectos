<?php
include_once("../functions.php");

$now = (isset($_POST['f_informe_o']))
    ? new DateTime($_POST['f_informe_o'])
    : new DateTime();

$nowDt = $now->format("Y-m-d H:i:s");

$sql = "SELECT
            ar.id
            AS
                idArrendamiento,
            vh.id
            AS
                idVehiculo,
            concat(vh.marca, ' ', vh.linea, ' ', vh.modelo)
            AS
                unidad,
            vh.tarifa
            AS
                tarifa,
            CASE
                WHEN
                    vh.disponibilidad = 0
                THEN
                    'Funcionando'
                WHEN
                    vh.disponibilidad = 1
                THEN
                    'Funcionando'
                WHEN
                    vh.disponibilidad = 2
                THEN
                    'Fuera de servicio'
            END AS
                disponibilidad,
            CASE
                WHEN
                    ar.estadoArrendamiento = 1
                THEN
                    'Rentado'
                WHEN
                    ar.estadoArrendamiento = 2
                THEN
                    'Penalizado'
            END AS
                estadoArrendamiento,
            DATE_FORMAT(ar.fechaExpedicion, '%d-%m-%Y %H:%i')
                AS
                fechaExpedicion,
            DATE_FORMAT(ar.fechaVencimiento, '%d-%m-%Y %H:%i')
                AS
                fechaVencimiento,
            DATEDIFF(ar.fechaVencimiento, ar.fechaExpedicion)
                AS
                diasTotales,
            DATEDIFF('$nowDt', ar.fechaExpedicion)
                AS
                diasTranscurridos,
            DATEDIFF(ar.fechaVencimiento, '$nowDt')
                AS
                diasRestantes,
            FLOOR(ar.montoTotal/DATEDIFF(ar.fechaVencimiento, ar.fechaExpedicion))
                AS
                montoDiario
        FROM
            vehiculos vh
        LEFT JOIN
            arrendamientos ar
        ON
            ar.idVehiculo = vh.id
        AND
            ar.estadoArrendamiento != 0
        ORDER BY
            concat(vh.marca,' ',vh.linea,' ',vh.modelo)
        ASC";

$q = mysqli_query($conexion, $sql);

$jsonArray = array();

while($r = mysqli_fetch_array($q)){
    $estadoArrendamiento = ($r['estadoArrendamiento'] != NULL) ? $r['estadoArrendamiento'] : 'Parado';
    $fechaExpedicion = ($r['fechaExpedicion'] != NULL) ? $r['fechaExpedicion'] : '-';
    $fechaVencimiento = ($r['fechaVencimiento'] != NULL) ? $r['fechaVencimiento'] : '-';
    $diasTotales = ($r['diasTotales'] != NULL) ? $r['diasTotales'] : '-';
    $diasTranscurridos = ($r['diasTranscurridos'] != NULL) ? $r['diasTranscurridos'] : '-';
    $diasRestantes = ($r['diasRestantes'] != NULL) ? $r['diasRestantes'] : '-';
    $montoDiario = ($r['montoDiario'] != NULL) ? $r['montoDiario'] : '0';

    $jsonArray[] = array(
        'unidad' => $r['unidad'],
        'tarifa' => $r['tarifa'],
        'disponibilidad' => $r['disponibilidad'],
        'estadoArrendamiento' => $estadoArrendamiento,
        'fechaExpedicion' => $fechaExpedicion,
        'fechaEntrega' => $fechaVencimiento,
        'diasTotales' => $diasTotales,
        'diasTranscurridos' => $diasTranscurridos,
        'diasRestantes' => $diasRestantes,
        'montoDiario' => $montoDiario
    );
}

$jsonstring = json_encode($jsonArray);

echo $jsonstring;