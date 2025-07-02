<?php
include('../functions.php');

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$dateTime = new DateTime($set_date_sec);
$nombreDia = $dateTime->format('D');
$diaSemana = $dateTime->format('w');

//check the current day
if ($nombreDia != 'Sun') {
    $diasDesdeDomingo = $diaSemana;
    $ultimoDomingo = clone $dateTime;
    $ultimoDomingo->sub(new DateInterval("P{$diasDesdeDomingo}D"));
    $domingo = $ultimoDomingo->format('Y-m-d');
} else {
    $domingo = $dateTime->format('Y-m-d');
}

//always next saturday
if ($nombreDia != 'Sat') {
    $diasHastaSabado = (6 - $diaSemana) % 7;
    $proximoSabado = clone $dateTime;
    $proximoSabado->add(new DateInterval("P{$diasHastaSabado}D"));
    $sabado = $proximoSabado->format('Y-m-d');
} else {
    $sabado = $dateTime->format('Y-m-d');
}

$sql = "SELECT
            *
        FROM
            saldo_caja
        WHERE
            tipoSc = '$tipo_saldo_caja' AND idSucursal='$sucursalSelect'
        AND
        	fechaSc
        BETWEEN
            '$domingo'
        AND
            '$sabado';";
$q = mysqli_query($conexion, $sql) or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

if (mysqli_num_rows($q) != 0) {
    exit('El saldo ' . $tipo_saldo_caja . ' de la semana correspondiente ya esta registrado');
}

$sql = "INSERT INTO
            saldo_caja
            (tipoSc, montoSc, fechaSc,idSucursal)
            VALUES
            ('$tipo_saldo_caja', '$monto_sec', '$set_date_sec','$sucursalSelect');";

$sql .= "INSERT INTO
            logs
            (operacion, idUsuario)
        VALUES
            ('registro saldo $tipo_saldo_caja en caja', '$idUsuario');";

$q = mysqli_multi_query($conexion, $sql) or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

exit("0");