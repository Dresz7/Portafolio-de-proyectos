<?php
include_once('../functions.php');

foreach ($_POST as $nombre_campo => $valor) {
    eval("\${$nombre_campo}='{$valor}';");
}

$sql = "INSERT INTO
            aportaciones_capital
            (tipoAportacion, montoAportacion, fechaAportacion, idSucursal)
            VALUES
            ('$tAportacion', '$montoAportacion', '$fechaAportacion', '$sucursalSelect');";
$sql .= "INSERT INTO
            logs
            (operacion, idUsuario)
            VALUES
            ('registro aportacion capital', '$idUsuario');";
$query = mysqli_multi_query($conexion, $sql)
    or die('Operación fallida');

exit("0");
