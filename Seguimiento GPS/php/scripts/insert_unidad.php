<?php
include_once("../functions.php");

foreach ($_POST as $nombre_campo => $valor) {
	eval("\${$nombre_campo}='{$valor}';");
}

$sql = "SELECT * FROM vehiculos WHERE serie = '$serie'";
$q = mysqli_query($conexion, $sql)
	or die('Error:' . mysqli_error($conexion));

if (mysqli_num_rows($q) != 0)
	die('El número de serie ' . $serie . ' ya se encuentra registrado');

$sql = "SELECT * FROM info_poliza WHERE numeroPoliza ='$numpoliza'";
$q = mysqli_query($conexion, $sql)
	or die('Error: ' . mysqli_error($conexion));

if (mysqli_num_rows($q) != 0)
	die('El número de póliza ' . $numpoliza . ' ya se encuentra registrado en un vehículo');

$sql = "INSERT INTO vehiculos (marca, linea, modelo, color, transmision, serie, placa, cilindros, capacidadCarga, tarifa)
    VALUES('$marca', '$linea', '$modelo', '$color', '$transmision', '$serie', '$placa', '$cilindros', '$carga', '$tarifa')";
$q = mysqli_query($conexion, $sql)
	or die('Vehículo no registrado');

$lastId1 = mysqli_insert_id($conexion);

$sql = "INSERT INTO info_poliza (numeroPoliza, expedicionPoliza, vencimientoPoliza, aseguradora)
    VALUES ('$numpoliza', '$f_expedicion', '$f_vencimiento', '$aseguradora')";
$q = mysqli_query($conexion, $sql)
	or die('Información de póliza no registrada');

$lastId2 = mysqli_insert_id($conexion);

$sql = "INSERT INTO polizas (idVehiculo, idInformacionPoliza)
		VALUES ('$lastId1','$lastId2')";
$q = mysqli_query($conexion, $sql)
	or die('Relación vehículo/póliza no establecida');

$sql = "INSERT INTO logs (operacion, idUsuario)
		VALUES ('alta vehículo', $idUsuario)";
mysqli_query($conexion, $sql);

exit('0');