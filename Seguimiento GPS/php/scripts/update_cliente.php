<?php
include_once("../functions.php");
$idUsuario = $_SESSION['idUsuario'];
foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$sql = "SELECT * FROM clientes WHERE id = $idCliente";
$query = mysqli_query($conexion, $sql)
    or die('Error al ejecutar la operación');
if (mysqli_num_rows($query) != 1) {
    die('Existe más de un registro con el mismo id');
}
$row = mysqli_fetch_array($query);
$currentCurp = $row['curp'];

$sql = "SELECT * FROM clientes WHERE curp = '$curp_txt'";
$query = mysqli_query($conexion, $sql)
    or die('Error al ejecutar la operación');
$rows = mysqli_num_rows($query);

if ($rows == 1) {
    if ($currentCurp != $curp_txt) {
        die('La curp indicada existe en un registro diferente');
    }
} else if ($rows > 1) {
    die('La curp indicada se encuentra en uso en más de un registro');
}

$sql = "UPDATE clientes cl
            INNER JOIN info_licencia il
            ON cl.id = il.id
                SET
                cl.curp = '$curp_txt',
                cl.nombreEmpresa = '$empresa_txt',
                cl.nombres = '$nombre_txt',
                cl.apellidoPaterno = '$aPaterno_txt',
                cl.apellidoMaterno = '$aMaterno_txt',
                cl.telefono = '$telefono_txt',
                cl.calle = '$calle_txt',
                il.numeroLicencia = '$no_licencia_txt',
                il.entidadLicencia = '$entidad_licencia_select',
                il.telefonoEmergencia = '$telefono_emergencia_txt'
            WHERE cl.id = $idCliente
            AND il.id = $idCliente";
$query = mysqli_query($conexion, $sql)
    or die('Error al ejecutar la operación');

$sql = "INSERT INTO logs (operacion, idUsuario) VALUES ('edición cliente', $idUsuario)";
mysqli_query($conexion, $sql);

exit("0");