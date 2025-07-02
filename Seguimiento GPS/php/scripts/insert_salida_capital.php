<?php
include_once('../functions.php');

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$sql = "INSERT INTO
            salidas_capital
            (tipoSalida, montoSalida, fechaSalida, motivoSalida, idSucursal)
            VALUES
            ('$tSalida', '$montoSalida', '$fechaSalida', '$motivoSalida', '$sucursalSelect');";
$sql .= "INSERT INTO
            logs
            (operacion, idUsuario)
            VALUES
            ('registro salida capital', '$idUsuario');";
$query = mysqli_multi_query($conexion, $sql)
    or die('Operación fallida');

exit("0");