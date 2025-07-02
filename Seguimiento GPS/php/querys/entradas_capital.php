<?php
include_once("../functions.php");

$fechaInicio = $_POST['f_inicio'] ?? die("Fecha de inicio no recibida");
$fechaFinal = $_POST['f_final'] ?? die("Fecha de cierre no recibida");
$m_pago = $_POST['m_pago'] ?? die("Método de pago no recibido");
$sucursalSelect = $_POST['sucursalSelect'] ?? ''; // Asumiendo que se envía este valor desde el formulario

// Añadir cláusula WHERE adicional para sucursal si es necesario
$whereSucursal = '';
if (in_array($sucursalSelect, ['1', '2'])) {
    $whereSucursal = " AND ip.idSucursal = $sucursalSelect";
}

$sqlBase = "SELECT
                ip.idSucursal,
                ip.id,
                ip.montoSaldado,
                DATE_FORMAT(ip.fechaPago, '%d-%m-%Y %h:%i') AS fechaPago,
                ip.metodoPago,
                ip.motivoPago,
                ar.id AS idArrendamiento,
                CONCAT(vh.marca, ' ', vh.linea, ' ', vh.modelo, ' ', vh.color) AS vehiculo
            FROM
                info_pago ip
            INNER JOIN pagos pa ON pa.idInformacionPago = ip.id
            INNER JOIN arrendamientos ar ON ar.id = pa.idArrendamiento
            INNER JOIN vehiculos vh ON vh.id = ar.idVehiculo
            WHERE
                ip.fechaPago BETWEEN '$fechaInicio 00:00:01' AND '$fechaFinal 23:59:59'
                $whereSucursal";

$sql = $m_pago == '1'
    ? $sqlBase . " ORDER BY ip.fechaPago ASC"
    : $sqlBase . " AND ip.metodoPago = '$m_pago' ORDER BY ip.fechaPago ASC";

$q = mysqli_query($conexion, $sql) or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

$jsonArray = [];

while ($r = mysqli_fetch_array($q)) {
    $jsonArray[] = [
        'idSucursal' => $r['idSucursal'] = $r['idSucursal'] == 1 ? "SAN JOSÉ DEL CABO" : ($r['idSucursal'] == 2 ? "LA PAZ" : $r['idSucursal']),
        'idArrendamiento' => $r['idArrendamiento'],
        'idInfoPago' => $r['id'],
        'fechaPago' => $r['fechaPago'],
        'metodoPago' => ucfirst($r['metodoPago']),
        'montoSaldado' => $r['montoSaldado'],
        'motivoPago' => ucfirst($r['motivoPago']),
        'vehiculo' => $r['vehiculo']
    ];
}

exit(json_encode($jsonArray));