<?php
include_once('../functions.php');
include_once('../../libs/fpdf/justification.php');

if (isset($_GET["fecha1"])){
        $fecha1 = $_GET["fecha"];
}
if (isset($_GET["fecha2"])){
        $fecha2 = $_GET["fecha2"];
}
/*
$idArrendamiento = $_GET['idArrendamiento'];
$idInfoPago = $_GET['idInfoPago'];

$sql = "SELECT pa.id, ip.fechaPago, CONCAT(cl.nombres, ' ', cl.apellidoPaterno, ' ', cl.apellidoMaterno) AS nombreCompleto,
                    ip.montoSaldado, ip.motivoPago, ip.metodoPago
            FROM pagos pa
            INNER JOIN arrendamientos ar ON ar.id = pa.idArrendamiento
            INNER JOIN info_pago ip ON ip.id = pa.idInformacionPago
            INNER JOIN clientes cl ON ar.idCliente = cl.id
            WHERE pa.idArrendamiento = $idArrendamiento AND pa.idInformacionPago = $idInfoPago
            LIMIT 1";
$informacion = mysqli_fetch_array(mysqli_query($conexion, $sql));
*/

//Inicio PDF
$pdf = new PDF();
$pdf->AddPage('L', 'A4');
//
$pdf->Line(10, 12, 285, 12);  //Horizontal Up
$pdf->Line(10, 12, 10, 193); //Vertical Left
$pdf->Line(285, 12, 285, 193); //Vertical Left
$pdf->Line(10, 193, 285, 193);  //Horizontal Up
//
$pdf->Line(10, 22, 285, 22);  //Horizontal Up
$pdf->Line(60, 12, 60, 193); //Vertical Left
//
//! Datos del reporte.

//! Fin reporte.
$pdf->Output();
//$pdf->Output('Recibo_' . $informacion['id'] . '.pdf', 'D');