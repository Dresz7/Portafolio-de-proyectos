<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
include_once('../functions.php');
include_once('../../libs/fpdf/justification.php');

$idArrendamiento = $_GET['idArrendamiento'];

if (isset($_GET['idArrendamiento']) && isset($_GET['idInfoPago'])) {
        $idInfoPago = $_GET['idInfoPago'];
        $sql = "SELECT
                        pa.id, ip.fechaPago,
                        CONCAT(cl.nombres, ' ', cl.apellidoPaterno, ' ', cl.apellidoMaterno)
                        AS
                        nombreCompleto,
                        ip.montoSaldado,
                        ip.motivoPago,
                        ip.metodoPago, ar.idSucursal
                FROM
                        pagos pa
                INNER JOIN
                        arrendamientos ar
                ON
                        ar.id = pa.idArrendamiento
                INNER JOIN
                        info_pago ip
                ON
                        ip.id = pa.idInformacionPago
                INNER JOIN
                        clientes cl
                ON
                        ar.idCliente = cl.id
                WHERE
                        pa.idArrendamiento = $idArrendamiento
                AND
                        pa.idInformacionPago = $idInfoPago
                LIMIT 1";
        $informacion = mysqli_fetch_array(mysqli_query($conexion, $sql));
        $localidad = $informacion['idSucursal'];
} else if(isset($_GET['idArrendamiento']) && !isset($_GET['idInfoPago'])) {
        $sql = "SELECT
                        ar.id, ar.fechaExpedicion,
                        CONCAT(cl.nombres, ' ', cl.apellidoPaterno, ' ', cl.apellidoMaterno)
                        AS
                        nombreCompleto , ar.idSucursal
                FROM
                        arrendamientos ar
                INNER JOIN
                        clientes cl
                ON
                        ar.idCliente = cl.id
                WHERE
                        ar.id = $idArrendamiento";
        $info_pre = mysqli_fetch_array(mysqli_query($conexion, $sql));
        $informacion['id'] = "AR-" . $info_pre['id'];
        $informacion['fechaPago'] = $info_pre['fechaExpedicion'];
        $informacion['nombreCompleto'] = $info_pre['nombreCompleto'];
        $informacion['montoSaldado'] = 5000;
        $informacion['motivoPago'] = "Depósito arrendamiento";
        $informacion['metodoPago'] = "Efectivo";
        $localidad = $info_pre['idSucursal'];
}

//! Inicio PDF
$pdf = new PDF();
$pdf->AddPage('L', 'A4');
//! colores de la celda
$pdf->SetFillColor(255, 255, 255);
$pdf->SetDrawColor(0, 0, 0);
$pdf->Cell(50);
//! Celdas contenedoaras
$pdf->RoundedRect(59, 10, 172.5, 142, 5, '1234', 'DF');
$pdf->Image('../../images/icons/LOGO2.jpg', 150, 11, 36, 18);
//! Celda para el número de recibo
$pdf->SetXY(194, 15);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255, 16, 16);
$pdf->RoundedRect(193.5, 12, 27, 12, 5, '1234', 'DF');
$pdf->MultiCell(35, 7, utf8_dec('Nº ' . $informacion['id']), 0, 'l');
//! Datos
$pdf->SetXY(65, 12);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(255, 16, 16);
$pdf->MultiCell(35, 13, 'RECIBO', 0, 'l');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(70, 23);
$pdf->MultiCell(0, 5, utf8_dec('Lugar y fecha de expedición'), 0, "L");
$pdf->SetXY(72, 30);

$pdf->MultiCell(0, 5, 'En '.($localidad  == 1 ? utf8_dec( "San José del Cabo,") : ( $localidad  == 2 ? "La Paz," : "La Sucursal,")).' Baja California Sur a, ' . utf8_dec(fechaCastellano($informacion['fechaPago'])) . '', 0, "L"); // A
$pdf->SetXY(70, 45);
$pdf->MultiCell(0, 5, utf8_dec('Recibí por parte de,             ' . $informacion['nombreCompleto'] . ''), 0, "L");
$pdf->SetXY(170, 52);
$pdf->MultiCell(0, 5, utf8_dec('la cantidad de  $' . $informacion['montoSaldado'] . '.00'), 0, "L");
//! Cantidad en letra
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(70, 62);
$pdf->MultiCell(0, 5, utf8_dec('Cantidad en letra.'), 0, "L");
$pdf->SetFillColor(255, 16, 16);
$pdf->RoundedRect(70, 72, 150, 12, 5, '1234', 'DF');
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(120, 76);
$pdf->MultiCell(0, 5, ucfirst(utf8_dec('' . number_words('' . ($informacion['montoSaldado']) . '', "pesos", "y", "centavos") . ' 00/100 M.N.')), 0, "L");
//! CONCEPTO.
$pdf->SetXY(70, 89);
$pdf->MultiCell(0, 5, utf8_dec('Por concepto :'), 0, "L");
$pdf->SetXY(120, 89);
$pdf->MultiCell(0, 5, ucfirst(utf8_dec($informacion['motivoPago'])), 0, "L");
//! Método de pago.
$pdf->SetXY(70, 105);
$pdf->MultiCell(0, 5, utf8_dec('Método de pago :'), 0, "L");
$pdf->SetXY(120, 105);
$pdf->MultiCell(0, 5, ucfirst(utf8_dec($informacion['metodoPago'])), 0, "L");
//! Datos de firma y nombre de quien recibe el pago
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(120, 142);
$pdf->MultiCell(0, 5, utf8_dec('Nombre y firma de quien recibe.'), 0, "L");
//! Lineas del recibo
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(218, 35, 70, 35);  //Horizontal 1
$pdf->Line(218, 50, 108, 50);  //Horizontal 2
$pdf->Line(171, 57, 71, 57);  //Horizontal 3
$pdf->Line(218, 57, 199, 57);  //Horizontal 4
$pdf->Line(218, 94, 100, 94);  //Horizontal 5
$pdf->Line(70, 100, 218, 100);  //Horizontal 6
$pdf->Line(105, 110, 218, 110);  //Horizontal 7
$pdf->Line(70, 116, 218, 116);  //Horizontal 8
$pdf->Line(100, 140, 190, 140);  //Horizontal 9

$pdf->Output('Recibo_' . $informacion['id'] . '.pdf', 'D');

?>