<?php
error_reporting(E_ALL ^ E_WARNING);

include_once("../functions.php");
$idSucursal = $_SESSION['sucursal'];
if (isset($_POST['date_sec'])) {
    $fechaRecibida = $_POST['date_sec'];
    $dateTime = new DateTime($fechaRecibida);
} else {
    $dateTime = new DateTime();
}

$nombreDia = $dateTime->format('D');
$diaSemana = $dateTime->format('w');

if ($nombreDia != 'Sun') {
    $diasDesdeDomingo = $diaSemana;
    $ultimoDomingo = clone $dateTime;
    $ultimoDomingo->sub(new DateInterval("P{$diasDesdeDomingo}D"));
    $domingo = $ultimoDomingo->format('Y-m-d');
} else {
    $domingo = $dateTime->format('Y-m-d');
}

if ($nombreDia != 'Sat') {
    $diasHastaSabado = 6 - $diaSemana;
    $proximoSabado = clone $dateTime;
    $proximoSabado->add(new DateInterval("P{$diasHastaSabado}D"));
    $sabado = $proximoSabado->format('Y-m-d');
} else {
    $sabado = $dateTime->format('Y-m-d');
}

$whereClause = $idSucursal == 0 ? "" : "AND idSucursal='$idSucursal'";

$sql = "SELECT idSucursal, tipoSc, montoSc, fechaSc 
        FROM saldo_caja 
        WHERE fechaSc BETWEEN '$domingo' AND '$sabado' 
        $whereClause 
        ORDER BY idSucursal, fechaSc";

$q = mysqli_query($conexion, $sql) or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

$jsonArray = array();

while ($r = mysqli_fetch_array($q)) {
    $idSuc = $r['idSucursal'];
    if (!isset($jsonArray[$idSuc])) {
        $jsonArray[$idSuc] = ['saldoInicial' => 0, 'saldoFinal' => 0, 'idSucursal' => $idSuc];
    }

    if ($r['tipoSc'] === 'inicial') {
        // Asumir que el primer saldo inicial encontrado es el correcto
        if ($jsonArray[$idSuc]['saldoInicial'] === 0) {
            $jsonArray[$idSuc]['saldoInicial'] = $r['montoSc'];
        }
    } elseif ($r['tipoSc'] === 'final') {
        // Actualizar siempre al último saldo final encontrado
        $jsonArray[$idSuc]['saldoFinal'] = $r['montoSc'];
    }
}

// Opcional: Restablecer índices del array si es necesario
$jsonArray = array_values($jsonArray);

// Convertir la información de la sucursal a texto
foreach ($jsonArray as $key => $value) {
    $nombreSucursal = $value['idSucursal'] == 1 ? "SAN JOSÉ DEL CABO" : ($value['idSucursal'] == 2 ? "LA PAZ" : "Otra Sucursal");
    $jsonArray[$key]['idSucursal'] = $nombreSucursal;
}

exit(json_encode($jsonArray));
?>