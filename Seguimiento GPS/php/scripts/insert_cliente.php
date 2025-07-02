<?php
include_once("../functions.php");

$idUsuario = $_SESSION['idUsuario'];
$idSucursal = $_SESSION['sucursal'];

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$sql = "SELECT id FROM domicilios WHERE codigoPostal = '$codigo_postal_txt'
    AND asentamiento = '$asentamiento_select' AND ciudad = '$ciudad_select'
    AND municipio = '$municipio_select' AND estado = '$estado_select'";
$q = mysqli_query($conexion, $sql)
    or die('La información del domicilio no es válida');

$r = mysqli_fetch_array($q);
$idDomicilio = $r['id'];

$sql = "SELECT * FROM clientes WHERE curp = '$curp_txt'";
$q = mysqli_query($conexion, $sql)
    or die('Operación fallida');

if (mysqli_num_rows($q) >= 1) {
    die('El cliente con clave curp: ' . $curp_txt . ' ya se encuentra registrado');
}

$sql = "INSERT INTO clientes (nombres, apellidoPaterno, apellidoMaterno, telefono, curp, calle, idDomicilio, idSucursal, nombreEmpresa)
        VALUES  ('$nombre_txt', '$aPaterno_txt', '$aMaterno_txt', '$telefono_txt', '$curp_txt', '$calle_txt', '$idDomicilio', '$idSucursal', '$empresa_txt')";
$q = mysqli_query($conexion, $sql)
    or die('Error al registrar al cliente');

$lastId = mysqli_insert_id($conexion);

/*$sql = "SELECT * FROM seccionesbcs WHERE seccion = $seccion_electoral_txt";
$q = mysqli_query($conexion, $sql);

if(mysqli_num_rows($q) == 0){
    $sql = "INSERT INTO seccionesbcs (seccion, distrito) VALUES ('$seccion_electoral_txt','$distrito_electoral_txt')";
    $q = mysqli_query($conexion, $sql);
}

$sql = "INSERT INTO info_electoral
        VALUES ('$lastId', '$clave_elector_txt', '$seccion_electoral_txt')";
$q = mysqli_query($conexion, $sql)
    or die('Información electoral no registrada');*/

$sql = "INSERT INTO info_licencia
        VALUES ('$lastId', '$f_expedicion_licencia_txt', '$f_vencimiento_licencia_txt', '$entidad_licencia_select', '$no_licencia_txt', '$telefono_emergencia_txt')";
$q = mysqli_query($conexion, $sql)
    or die('Información de licencia no registrada');

$sql = "INSERT INTO logs (operacion, idUsuario)
        VALUES ('alta Cliente', '$idUsuario')";
$q = mysqli_query($conexion, $sql);

exit ('0');