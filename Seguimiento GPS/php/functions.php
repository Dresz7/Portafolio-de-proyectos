<?php
include_once("../connection/connection.php");

date_default_timezone_set('America/Mazatlan');

session_start();

arrendamientosVencidos($conexion);

if (!isset($_SESSION['idUsuario']) || $_SESSION['idUsuario'] == "") {
    die("La sesión ha expirado, recarga la página");
}

$idUsuario = $_SESSION['idUsuario'];

function utf8_dec(string $string): string
{
    $s = (string) $string;
    $len = \strlen($s);

    for ($i = 0, $j = 0; $i < $len; ++$i, ++$j) {
        switch ($s[$i] & "\xF0") {
            case "\xC0":
            case "\xD0":
                $c = (\ord($s[$i] & "\x1F") << 6) | \ord($s[++$i] & "\x3F");
                $s[$j] = $c < 256 ? \chr($c) : '?';
                break;

            case "\xF0":
                ++$i;
                // no break

            case "\xE0":
                $s[$j] = '?';
                $i += 2;
                break;

            default:
                $s[$j] = $s[$i];
        }
    }

    return substr($s, 0, $j);
}

function number_words($valor, $desc_moneda, $sep, $desc_decimal)
{
    $arr = explode(".", $valor);
    $entero = $arr[0];
    if (isset($arr[1])) {
        $decimos = strlen($arr[1]) == 1 ? $arr[1] . '0' : $arr[1];
    }
    $fmt = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
    if (is_array($arr)) {
        $num_word = ($arr[0] >= 1000000) ? "{$fmt->format($entero)} de $desc_moneda" : "{$fmt->format($entero)} $desc_moneda";
        if (
            isset($decimos) && $decimos > 0
        ) {
            $num_word .= " $sep {$fmt->format($decimos)} $desc_decimal";
        }
    }
    return $num_word;
}

function fechaCastellano($fecha)
{
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    return $nombredia . " " . $numeroDia . " de " . $nombreMes . " de " . $anio;
}

function arrendamientosVencidos($con)
{
    $now = new DateTime();
    $nowF = $now->format('Y-m-d H:i:s');
    $q = mysqli_query($con, "SELECT * FROM arrendamientos WHERE DATE_ADD(fechaVencimiento, INTERVAL 1 DAY) <= '$nowF'
                                    AND estadoArrendamiento = 1");
    while ($row = mysqli_fetch_array($q)) {
        $aId = $row['id'];
        $vId = $row['idVehiculo'];
        mysqli_query($con, "UPDATE arrendamientos SET estadoArrendamiento = 2 WHERE id = $aId");
        mysqli_query($con, "UPDATE vehiculos SET disponibilidad = 1 WHERE id = $vId");
    }
    penalizar($con);
}

function penalizar($conexion)
{
    $now = new DateTime();
    $nowString = $now->format('Y-m-d H:i:s');
    $nowDate = $now->format('Y-m-d');

    $qF = mysqli_query($conexion, "SELECT id, DATE_ADD(fechaVencimiento, INTERVAL 1 DAY) AS vR,
            DATE_FORMAT(fechaVencimiento, '%H:%i:%s') AS vRT FROM arrendamientos
            WHERE estadoArrendamiento = 2 AND DATE_ADD(fechaVencimiento, INTERVAL 1 DAY) <= '$nowString'");

    while ($row = mysqli_fetch_array($qF)) {
        $idArr = $row['id'];
        $qT = mysqli_query($conexion, "SELECT vh.tarifa FROM arrendamientos ar INNER JOIN
                            vehiculos vh ON ar.idVehiculo = vh.id WHERE ar.id = $idArr");
        $rT = mysqli_fetch_array($qT);
        $tarifaDiaria = $rT['tarifa'];
        $startDateTime = new DateTime($row['vR']);
        $nowDateTime = new DateTime($nowDate . ' ' . $row['vRT'] . ' +1 day');
        $period = new DatePeriod($startDateTime, new DateInterval('P1D'), $nowDateTime);
        foreach ($period as $date) {
            $f = $date->format("Y-m-d H:i:s");
            if ($f < $nowString)
                $dates[] = $f;
        }
        foreach ($dates as $key => $dt) {
            $qP = mysqli_query($conexion, "SELECT idArrendamiento, fechaPenalizacion FROM penalizaciones
                    WHERE idArrendamiento = $idArr AND fechaPenalizacion = '$dt'");
            if (mysqli_num_rows($qP) == 0) {
                mysqli_query($conexion, "INSERT INTO penalizaciones(idArrendamiento, fechaPenalizacion, montoPenalizacion)
                    VALUES ('$idArr', '$dt' , '$tarifaDiaria')");
            }
        }
        unset($dates);
    }
}