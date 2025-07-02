<?php
include_once("../functions.php");
error_reporting(E_ALL ^ E_WARNING);
$now = new DateTime();
$nowString = $now -> format('Y-m-d h:i:s');
$nowDate = $now->format('Y-m-d');

$sql = "SELECT
            vh.*
        FROM
            vehiculos vh
        ORDER BY
            CONCAT(vh.marca, ' ', vh.linea, ' ', vh.modelo)
        ASC";
$query = mysqli_query($conexion, $sql, MYSQLI_STORE_RESULT)
    or die;

$json = array();

function diasTranscurridos($con, $idV, $nS) {
    $dates = []; // Inicializa $dates como un array vacío al principio de la función

    $q1_1 = mysqli_query(
        $con,
        "SELECT
                id,
                fechaExpedicion AS eR,
                fechaVencimiento AS vR
            FROM
                arrendamientos
            WHERE
                idVehiculo = $idV
            AND
                fechaExpedicion < '$nS';"
    );

    while ($row1_1 = mysqli_fetch_array($q1_1)) {
        $startDT = new DateTime($row1_1['eR']);
        $endDt = new DateTime($row1_1['vR']);
        $period = new DatePeriod($startDT, new DateInterval('P1D'), $endDt);
        foreach ($period as $date) {
            $fecha = $date->format("Y-m-d H:i:s");
            if ($fecha < $nS) {
                $dates[] = array($fecha);
            }
        }
    }

    $conteo = is_null($dates)
        ? 0
        : count($dates);

    return $conteo;
}

while($row = $query -> fetch_array(MYSQLI_ASSOC)){
    $idVehiculo = $row['id'];
    $diasArrendados = diasTranscurridos($conexion, $idVehiculo, $nowString);

    $qFR = mysqli_query(
        $conexion,
        "SELECT
                DATEDIFF('$nowString', fechaExpedicion)
                AS primerRegistro
            FROM
                arrendamientos
            WHERE
                idVehiculo = $idVehiculo;"
    );
    $qR = mysqli_fetch_array($qFR);


// Asegúrate de que $qR['primerRegistro'] está definido antes de usarlo
$primerRegistro = isset($qR['primerRegistro']) ? $qR['primerRegistro'] : 0;

// Usa $primerRegistro en lugar de $qR['primerRegistro'] directamente
$razonArrendamiento = $primerRegistro == 0
    ? 0
    : round(($diasArrendados / $primerRegistro * 100), 0);

    $json[] = array(
        'id' => $row['id'],
        'marca' => $row['marca'],
        'linea' => $row['linea'],
        'modelo' => $row['modelo'],
        'color' => $row['color'],
        'transmision' => $row['transmision'],
        'serie' => $row['serie'],
        'placa' => $row['placa'],
        'cilindros' => $row['cilindros'],
        'capacidadCarga' => $row['capacidadCarga'],
        'disponibilidad' => $row['disponibilidad'],
        'tarifa' => $row['tarifa'],
        'diasArrendados' => $diasArrendados,
        'razonArrendamiento' => $razonArrendamiento
    );
    unset($razonArrendamiento, $diasArrendados);
}

$conexion->close();
$jsonstring = json_encode($json, JSON_INVALID_UTF8_SUBSTITUTE);
$jsonstring = preg_replace('/\\\t/', '', $jsonstring);

exit($jsonstring);