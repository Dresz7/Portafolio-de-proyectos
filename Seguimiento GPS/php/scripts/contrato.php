<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require('../../libs/fpdf/cellfit.php');
//require('../../libs/fpdf/fpdf.php');
include_once('../functions.php');
//include_once('../../libs/fpdf/justification.php');

$id = $_GET['id'];

$sql = "SELECT ar.id, ar.idCliente, ar.idVehiculo, ar.fechaExpedicion, ar.fechaVencimiento, ar.montoTotal, ar.deposito, ar.idClienteAd, ar.idSucursal,
            CONCAT(cl.nombres, ' ', cl.apellidoPaterno, ' ', cl.apellidoMaterno) AS nombreCompleto,
            cl.telefono, cl.nombreEmpresa, cl.calle, dm.id, dm.asentamiento, dm.ciudad, dm.municipio, dm.estado,
            vh.marca, vh.linea, vh.modelo, vh.color, vh.transmision, vh.serie, vh.placa, vh.cilindros, vh.capacidadCarga, vh.tarifa, ip.numeroPoliza, ip.aseguradora,
            li.entidadLicencia, li.numeroLicencia, li.telefonoEmergencia
        FROM arrendamientos ar
                INNER JOIN clientes cl ON cl.id = ar.idCliente
                INNER JOIN domicilios dm ON dm.id = cl.idDomicilio
                INNER JOIN vehiculos vh ON vh.id = ar.idVehiculo
                INNER JOIN polizas po ON vh.id = po.idVehiculo
                INNER JOIN info_poliza ip ON ip.id = po.idInformacionPoliza
                INNER JOIN info_licencia li ON li.id = ar.idCliente
                WHERE ar.id = $id
                ORDER BY ip.id DESC
                LIMIT 1;";
$query = mysqli_query($conexion, $sql);
$dato = mysqli_fetch_assoc($query);
$id_adicional = $dato['idClienteAd'];

if ($id_adicional != 0) {
    $sql3 = "SELECT CONCAT(nombres, ' ', apellidoPaterno, ' ', apellidoMaterno) AS nombreCompleto FROM clientes WHERE id =$id_adicional ";
    $query3 = mysqli_query($conexion, $sql3);
    $dato3 = mysqli_fetch_assoc($query3);
    $nombreAdicional = utf8_dec(', y por ' . $dato3['nombreCompleto']);
} else {
    $nombreAdicional = '';
}

$sql2 = "SELECT DATEDIFF(fechaVencimiento, fechaExpedicion) FROM `arrendamientos` WHERE id = $id";
$query2 = mysqli_query($conexion, $sql2);
$dato2 = mysqli_fetch_assoc($query2);
$tarifaDiaria = round(($dato['montoTotal']) / ($dato2['DATEDIFF(fechaVencimiento, fechaExpedicion)']), 2);

$w = 190;
$h = 5;



$pdf = new FPDF_CellFit('L');
//TODO Hoja 1 del contrato
$pdf->AliasNbPages();
$pdf->AddPage('L', 'Legal');
$pdf->AddFont('Calibri','','Calibri-Regular.php');
$pdf->AddFont('Calibri','B','Calibri-Bold.php');

$pdf->Image('../../images/icons/LOGO2.jpg',10,8,24,24);
$pdf->Ln(22);

$pdf->ChapterBody('CONTRATO DE ARRENDAMIENTO DE AUTOMÓVIL','C','B');
$pdf->Ln(1);

$pdf->ChapterBody('En la ciudad de '.($dato['idSucursal'] == 1 ? "San José del Cabo" : ($dato['idSucursal'] == 2 ? "La Paz" : "La Sucursal")).', Baja California Sur, el ' . fechaCastellano($dato['fechaExpedicion']) . ', comparecen a celebrar contrato de arrendamiento de vehículo automotor, por una parte, el Sr. Enrique Alonso García Mundo, a quien en lo sucesivo se le denominará el "ARRENDADOR", y por la otra parte el (la) señor(a) ' . $dato['nombreCompleto'] . ', empleado de la empresa y o persona física, ' . $dato['nombreEmpresa'] . ', a quien para los efectos del presente contrato se le conocerá como el "ARRENDATARIO(A)" y al efecto se realizan las siguientes.
I. Declara el ARRENDADOR lo siguiente:', 'J','B');

$pdf->ChapterBody('1. Que es de nacionalidad mexicana, mayor de edad, con domicilio en Blvd. Benito Juárez 112 Esq. Hermenegildo Galeana, Ciudad Insurgentes, Baja California Sur.
2. Que es propietario de un vehículo que a continuación se describe:', 'J','');

$data = array(
    ' Marca:' => ' '.$dato['marca'],
    ' Línea:' => ' '.$dato['linea'],
    ' Modelo:' => ' '.$dato['modelo'],
    ' Color:' => ' '.$dato['color'],
    ' Transmisión:' => ' '.$dato['transmision'],
    ' Serie:' => ' '.$dato['serie'],
    ' Placa:' => ' '.$dato['placa'],
    ' Cilindros:' =>' '.$dato['cilindros'],
    ' Capacidad de carga:' => ' '.$dato['capacidadCarga'].' kg',
    ' Póliza de seguro:' =>  ' '.$dato['aseguradora'] . ': ' . $dato['numeroPoliza']
);



$pdf->tablavehiculo($data,'B');


$pdf->ChapterBody('3. Que la propiedad la acredita con la factura correspondiente.
4. Que el vehículo en materia del presente contrato lo tiene en posesión legal, se encuentra libre de todo gravamen y no existe a la fecha proceso jurisdiccional cuyo objeto sea la propiedad o posesión del citado mueble.
5. Que puede disponer legalmente del vehículo materia del presente contrato.
6. Que el vehículo materia de este contrato, cuenta con la totalidad de los permisos necesarios para circular incluyendo las verificaciones en materia ambiental.','J','');

$pdf->ChapterBody('II. Declara el ARRENDATARIO(A), lo siguiente:','J','B');

$pdf->ChapterBody('1. Que es de nacionalidad mexicana, mayor de edad, con domicilio en ' .$dato['calle'] . ', ' . $dato['asentamiento'] . ', ciudad/municipio de ' . $dato['ciudad'] . ', ' . $dato['municipio'] . '. Con número celular ' . $dato['telefono'] . ' y número de contacto en caso de emergencia ' . $dato['telefonoEmergencia'] . '.
2. Que es su deseo recibir y usar en ARRENDAMIENTO el vehículo propiedad del ARRENDADOR, mismo que se ha descrito en líneas precedentes, y que conoce las condiciones mecánicas y de uso en que se encuentra.
3. Que cuenta con licencia para conducir vigente otorgada por el estado de ' . $dato['entidadLicencia'] . ', con número de licencia ' . $dato['numeroLicencia'] . '.','J','');

$pdf->ChapterBody('III. Señalan las partes que de manera totalmente voluntaria comparecen a celebrar el presente contrato al tenor de las siguientes.
C L A U S U L A S:
PRIMERA. - OBJETO DEL CONTRATO.','J','B');

$pdf->ChapterBody('El ARRENDADOR entrega en arrendamiento al ARRENDATARIO(A) el VEHÍCULO AUTOMOTOR descrito en las declaraciones que preceden, aceptándolo este último en las condiciones normales, mecánicas y de carrocería consignadas en el inventario respectivo, mismo que se encuentra al final del presente documento y forma parte integrante del mismo, en lo sucesivo ANEXO 1. En virtud de lo anterior, se tiene al ARRENDATARIO(A) recibiendo el vehículo en las condiciones especificadas que anteceden por lo que, salvo defectos ocultos que tenga el vehículo, se obliga a pagar al ARRENDADOR a la terminación del presente contrato, el o los faltantes de accesorios y partes del vehículo que recibe en el momento de la entrega del mismo.','J','');

$pdf->ChapterBody('SEGUNDA. - TÉRMINO DEL CONTRATO.','J','B');

$pdf->ChapterBody('El término del arrendamiento será por ' . $dato2['DATEDIFF(fechaVencimiento, fechaExpedicion)'] . ' días contando a partir de la firma del presente documento a las ' . $hora = date('H:i:s', strtotime($dato['fechaExpedicion'])) . ' horas concluyendo precisamente el día ' . fechaCastellano($dato['fechaVencimiento']) . ', a las ' . $hora = date('H:i:s', strtotime($dato['fechaExpedicion'])) . ' horas. En virtud de lo anterior, el plazo máximo de vigencia del presente contrato será de ' . $dato2['DATEDIFF(fechaVencimiento, fechaExpedicion)'] . ' días, una vez terminado el cual, se deberá renovar por escrito y en caso de no hacerse, no se entenderá por renovado automáticamente por un plazo igual, obligándose el ARRENDATARIO(A) a cubrirlo a la entrega del vehículo.','J','');


$pdf->ChapterBody('TERCERA. - PAGO DE RENTAS Y ACCESORIOS.','J','B');

$pdf->ChapterBody('El ARRENDATARIO(A) pagará a el ARRENDADOR por concepto de renta la cantidad de $' . $tarifaDiaria . ' (' . number_words('' . ($tarifaDiaria) . '', "pesos", "y", "centavos") . ' 00/100 M.N.) DIARIOS, pago independiente de las prestaciones, sanciones e intereses que se pudiesen generar por el presente contrato. En virtud de lo anterior y de conformidad a los días contratados con la cláusula SEGUNDA que precede, el ARRENDATARIO(A) pagará al ARRENDADOR por concepto de renta la cantidad de $' . $dato['montoTotal'] . '.00 (' . number_words('' . ($dato['montoTotal']) . '', "pesos", "y", "centavos") . ' 00/100 M.N.). En el pago diario antes referido, se encuentra incluido el pago del SEGURO del vehículo, sin que se encuentre incluido, bajo ninguna circunstancia, el pago del deducible respectivo en el supuesto de ser necesario hacer uso del mismo.','J','');

$pdf->ChapterBody('CUARTA. - DEVOLUCIÓN DEL BIEN ARRENDADO.','J','B');

$pdf->ChapterBody('El ARRENDATARIO(A) se obliga a entregar en devolución el vehículo arrendado precisamente el día y hora señalado en la cláusula SEGUNDA del presente contrato en el domicilio:'.($dato['idSucursal'] == 1 ? " Dr. Ernesto Chánez Chávez 380, Ampliación el Zacatal, 23430, San José del Cabo, B.C.S." : ($dato['idSucursal'] == 2 ? " Blvd. Padre Eusebio Kino 1455, 23020, La Paz, B.C.S." : "La Sucursal")).' En caso de incumplimiento, el ARRENDATARIO(A) pagará una multa correspondiente al monto $' . $dato['tarifa'] . ' DIARIO COMO PENA CONVENCIONAL. La devolución del vehículo se realizará conforme a los términos y condiciones que en el ANEXO "A" le fueron entregadas, teniéndose el vehículo por recibido hasta en tanto se haya llenado el anexo "B" del presente contrato y que es parte integrante del mismo.','J','');

$pdf->ChapterBody('QUINTA. - DEL DESTINO DEL BIEN ARRENDADO.','J','B');

$pdf->ChapterBody('El vehículo arrendado se destinará única y exclusivamente para uso del ARRENDATARIO(A); dicho vehículo solo podrá ser manejado por el C.' . $dato['nombreCompleto'] . $nombreAdicional . '. Así también el ARRENDATARIO(A) no podrá otorgar a su vez dicho MUEBLE en arrendamiento o comodato, ni conceder el uso ni la posesión del mismo, bajo ninguna circunstancia o figura a terceras personas.','J','');

$pdf->ChapterBody('SEXTA. - DEL DEPÓSITO.','J','B');

$pdf->ChapterBody('El ARRENDATARIO(A) entregará en las oficinas del ARRENDADOR un depósito por la cantidad de $5000 (cinco mil pesos 00/100 M.N.), en garantía del cumplimiento fiel, puntual de todos y cada una de las obligaciones. El ARRENDADOR expedirá un recibo por concepto de dicho depósito que servirá como comprobante para que, una vez terminado el arrendamiento, sea devuelto o bien, aplicado al pago del saldo que de las obligaciones de carácter económico del ARRENDADOR. En virtud de lo anterior el ARRENDATARIO(A) faculta de manera expresa al ARRENDADOR para que disponga total o parcialmente el depósito antes referido, para cobrarles las prestaciones estipuladas, la reposición de faltantes, la reparación de defectos bajo el entendido de que, si la suma de depósito resultase insuficiente para cubrir dichos adeudos, el ARRENDADOR podrá ejercitar las acciones judiciales que considere necesarias para el reclamo del pago adeudo.','J','');

$pdf->Ln(10);

$pdf->ChapterBody('SÉPTIMA. - DE LOS PAGOS DE CONSUMIBLES.','J','B');

$pdf->ChapterBody('Los gastos erogados por concepto de consumibles del automóvil que se otorga en arrendamiento, así como los gastos derivados del mantenimiento normal del MUEBLE durante el uso del mismo serán a cargo del ARRENDATARIO(A), El vehículo se tendrá que devolver con la misma cantidad de combustible que se entregó, de no ser así se cobrara a razón de $30.00 (Treinta pesos 00/100 M.N.) cada litro faltante.','J','');

$pdf->ChapterBody('OCTAVA. - DE LOS OBJETOS EN EL VEHÍCULO.','J','B');

$pdf->ChapterBody('El ARRENDADOR no es responsable de objetos personales olvidados por el ARRENDATARIO(A) dentro del vehículo, ni el daño o demérito que pudiesen sufrir al ser transportados en el vehículo.','J','');

$pdf->ChapterBody('NOVENA. - DEL PAGO DE DEDUCIBLE Y MULTAS.','J','B');

$pdf->ChapterBody('El ARRENDATARIO(A) responderá del pago de derechos o multas que se generen por infracciones al reglamento de tránsito; así como del pago del deducible de la póliza de seguro, en caso de que se genere un accidente responsabilidad del conductor.
En cualquier caso, el ARRENDATARIO(A) se obliga a devolver el BIEN MUEBLE, materia del presente contrato en las condiciones en que lo recibe al ARRENDADOR, excepto en aquellos casos que el deterioro fuera una causa derivada del uso normal del vehículo automotor que nos ocupa.','J','');

$pdf->ChapterBody('DÉCIMA. - DE LAS OBLIGACIONES DE EL ARRENDATARIO(A).','J','B');

$pdf->ChapterBody('a) Conducir el vehículo con la licencia respectiva y vigente expedida por la autoridad competente.
b) No manejar el vehículo en estado de ebriedad o bajo influencia de drogas.
c) No subarrendar el vehículo.
d) Obedecer los reglamentos de tránsito federal, estatal o local.
e) Revisar el vehículo periódicamente en los niveles de aceite, líquido de frenos, gasolina, agua de radiador y presión de llantas.
f) Responder por daños que sufra el vehículo mientras el mismo se encuentre en posesión.
g) Responder por el pago de sanciones impuestas por cualquier autoridad en el uso del vehículo.
h) Responder por hechos y actos ilícitos efectuados con el vehículo o dentro del mismo.
i) Si durante la vigencia del contrato, el vehículo es objeto de daño o siniestro, tendrá que dar aviso de inmediato al ARRENDADOR y a las autoridades que debiesen conocer de tal contingencia en un término menor de 5 horas a partir de que tuvo conocimiento.
j) En caso de robo el ARRENDATARIO(A) deberá de dar aviso al "ARRENDADOR" y a las autoridades competentes en los términos que esta señale en un plazo no mayor a 8 horas que ocurrió el citado robo.','J','');

$pdf->ChapterBody('En dado caso de no cumplir con lo señalado en el punto i) y j) será sancionada con una pena convencional de 30 días de arrendamiento.
DÉCIMA PRIMERA. - ','J','B');

$pdf->ChapterBody('Las partes se someten expresamente para todo lo relacionado con el cumplimiento del presente contrato a las leyes del estado de Baja California Sur, y se someten a los tribunales establecidos en Ciudad de Constitución, B.C.S., renunciando expresamente a cualquier fuero que les pudiera corresponder por razones de sus domicilios, presentes o futuros.
Leído que fue a las partes y enterados de la totalidad de sus alcances legales, firman éstas de conformidad. '.($dato['idSucursal'] == 1 ? "San José del Cabo" : ($dato['idSucursal'] == 2 ? "La Paz" : "La Sucursal")).', B.C.S., el '. fechaCastellano($dato['fechaExpedicion']) . '.','J','');
$pdf->Ln(4);
$pdf->LeyendaBody('El arrendador será directamente responsable de los daños causados por él o cualquier persona designada por él si se encuentran en estado de ebriedad o bajo la influencia de cualquier otra sustancia.','J','');

$pdf->ChapterBody('
NIVEL DE COMBUSTIBLE:             PESO MAXIMO DE CARGA: '. $dato['capacidadCarga'] . ' Kgs','C','B');

// Obtén la posición Y actual
$currentY = $pdf->GetY();

// Ajusta las coordenadas Y de la imagen según el espacio ocupado por el texto
$imageY = $currentY + 2; // Ajusta según tus necesidades


$pdf->Image('../../images/contrato/fuel_indicator.jpg', 140, $imageY, 25, 15);

$pdf->Image('../../images/contrato/kg_indicator.jpg', 195, $imageY, 20, 13);
$pdf->Ln(20);

$pdf->ChapterBody('              1. GATO.
              2. CRUCETA.
              3. LLANTA REFACCION DE CARGA.
              4. ESPEJOS RETROVISORES.
              5. TARJETA DE CIRCULACION ORIGINAL.','J','');

$pdf->ChapterBody('              OBSERVACIONES Y DAÑOS:.','J','B');

$pdf->ChapterBody('











 ','J','B');

$pdf->Ln(70);


// Obtén la posición Y actual
$currentY = $pdf->GetY();

// Ajusta las coordenadas Y de la imagen según el espacio ocupado por el texto
$imageY = $currentY + 2; // Ajusta según tus necesidades

$pdf->Image('../../images/contrato/observations.jpg', 245, 10, 100, 100);

$dataf = array(
    'ENRIQUE ALONSO GARCÍA MUNDO' => 'ARRENDADOR',
    $dato['nombreCompleto'] => 'ARRENDATARIO(A)'
);
$pdf->Ln(10);
$pdf->tablafirmas($dataf,'');


//! Salida del arcivo PDF
$pdf->Output('' . utf8_dec($dato['nombreCompleto'] . '_contrato.pdf'), 'D');
$pdf->Output();
?>