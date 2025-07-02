<?php
include_once("../functions.php");

$now = (isset($_POST['f_informe_a']))
    ? new DateTime($_POST['f_informe_a'])
    : new DateTime();

$nowDate = $now->format("Y-m-d");
$nowDt = $now->format("Y-m-d H:i:s");

$sql = "SELECT
            ar.id
            AS
                idArrendamiento,
            ar.estadoArrendamiento
            AS
                estadoArrendamiento,
            vh.id
            AS
                idVehiculo,
            concat(vh.marca, ' ', vh.linea, ' ', vh.modelo)
            AS
                unidad,
            vh.tarifa
            AS
                tarifa,
            vh.disponibilidad,
            ar.deposito,
            ar.montoTotal,
            DATE_FORMAT(ar.fechaEPago, '%d-%m-%Y')
            AS
                fechaEPago,
            DATE_FORMAT(ar.fechaExpedicion, '%Y-%m-%d')
            AS
                expedicion,
            DATE_FORMAT(DATE_ADD(ar.fechaVencimiento, INTERVAL 1 DAY), '%Y-%m-%d')
            AS
                vencimientoExt,
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
            concat(vh.marca, ' ', vh.linea, ' ', vh.modelo) ASC";

$q = mysqli_query($conexion, $sql);

$jsonArray = array();

while ($row = mysqli_fetch_array($q)) {
    $idArrendamiento = ($row['idArrendamiento'] != null) ? $row['idArrendamiento'] : 0;
    $idVehiculo = $row['idVehiculo'];
    $estadoArrendamiento = ($row['estadoArrendamiento'] != null) ? $row['estadoArrendamiento'] : 0;
    $montoDiario = ($row['montoDiario'] != null) ? $row['montoDiario'] : 0;
    $deposito = ($row['deposito'] == 1) ? 5000 : 0;

    $q1 = mysqli_query($conexion, "SELECT SUM(montoGastoV) AS gastosVehiculo FROM gastosvehiculos WHERE fechaGastoV
            BETWEEN '$nowDate 00:01:00' AND '$nowDate 23:59:00' AND idVehiculo = $idVehiculo");
    $gv = mysqli_fetch_array($q1);
    $gastosVehiculo = (!is_null($gv['gastosVehiculo'])) ? $gv['gastosVehiculo'] : 0;

    if ($estadoArrendamiento != 0) {
        $q2 = mysqli_query($conexion, "SELECT SUM(ip.montoSaldado) AS saldadoTransferencia FROM pagos pa
        INNER JOIN info_pago ip ON pa.idInformacionPago = ip.id
        WHERE pa.idArrendamiento = '$idArrendamiento' AND ip.motivoPago = 'pago arrendamiento' AND ip.metodoPago = 'transferencia'");
        $st = mysqli_fetch_array($q2);
        $transferencia = ($st['saldadoTransferencia'] != null) ? $st['saldadoTransferencia'] : 0;

        $q3 = mysqli_query($conexion, "SELECT SUM(ip.montoSaldado) AS saldadoEfectivo FROM pagos pa
        INNER JOIN info_pago ip ON pa.idInformacionPago = ip.id
        WHERE pa.idArrendamiento = '$idArrendamiento' AND ip.motivoPago = 'pago arrendamiento' AND ip.metodoPago = 'efectivo'");
        $se = mysqli_fetch_array($q3);
        $efectivo = ($se['saldadoEfectivo'] != null) ? $se['saldadoEfectivo'] : 0;

        $q4 = mysqli_query($conexion, "SELECT SUM(montoPenalizacion) AS montoPenalizacion FROM penalizaciones WHERE idArrendamiento = '$idArrendamiento'");
        $mp = mysqli_fetch_array($q4);
        $penalizacion = ($mp['montoPenalizacion'] != null) ? $mp['montoPenalizacion'] : 0;

        $montoTotal = $row['montoTotal'] + $penalizacion;
        $montoPagado = $efectivo + $transferencia;

        if ($row['diasRestantes'] == 0) {
            $montoxCubrir = $montoDiario * ($row['diasTranscurridos']);
            $montoDiario = 0;
        } else if($row['diasRestantes'] >= 1) {
            $montoxCubrir = $montoDiario * $row['diasTranscurridos'];
        } else if($row['diasRestantes'] <= -1){
            $montoxCubrir = $montoTotal;
            $montoDiario = $row['tarifa'];
        }

        if ($montoPagado < $montoxCubrir) {
            $xcobrar = $montoxCubrir - $montoPagado;
            $xpagar = 0;
        } else {
            $xcobrar = 0;
            $xpagar = $montoPagado - $montoxCubrir;
        }

        $jsonArray[] = array(
            'unidad' => $row['unidad'],
            'montoTotal' => $montoTotal,
            'deposito' => $deposito,
            'fechaEfPago' => $row['fechaEPago'],
            'transferencias' => $transferencia,
            'efectivo' => $efectivo,
            'xcobrar' => $xcobrar,
            'xpagar' => $xpagar,
            'montoDiario' => $montoDiario,
            'mantenimiento' => (-$gastosVehiculo),
            'ingresoNeto' => ($montoDiario - $gastosVehiculo),
            'disponibilidad' => $row['disponibilidad'],
            'estadoArrendamiento' => $estadoArrendamiento
        );
    } else {
        $jsonArray[] = array(
            'unidad' => $row['unidad'],
            'montoTotal' => 0,
            'deposito' => 0,
            'fechaEfPago' => '-',
            'transferencias' => 0,
            'efectivo' => 0,
            'xcobrar' => 0,
            'xpagar' => 0,
            'montoDiario' => 0,
            'mantenimiento' => (-$gastosVehiculo),
            'ingresoNeto' => (-$gastosVehiculo),
            'disponibilidad' => $row['disponibilidad'],
            'estadoArrendamiento' => $estadoArrendamiento
        );
    }
}

$jsonstring = json_encode($jsonArray);

echo $jsonstring;