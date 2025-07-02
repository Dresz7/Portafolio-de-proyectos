<?php
include_once('../functions.php');

$idAr = $_POST['idArrendamiento'];
$idVh = $_POST['idVehiculo'];

$qPagos = mysqli_query(
    $conexion,
    "SELECT
        *
    FROM
        pagos
    WHERE
        idArrendamiento = '$idAr'");

while ($row = mysqli_fetch_array($qPagos)) {
    $idIp = $row['idInformacionPago'];
    mysqli_query(
        $conexion,
        "DELETE FROM
            info_pago
        WHERE
            id = '$idIp';"
    )
    or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));
}

$sql = "DELETE FROM
            arrendamientos
        WHERE
            id = '$idAr';";

$sql .= "UPDATE
            vehiculos
        SET
            disponibilidad = 0
        WHERE
            id = $idVh;";

$sql .= "INSERT INTO
            logs
            (operacion,
            idUsuario)
        VALUES
            ('eliminación arrendamiento',
            $idUsuario);";

mysqli_multi_query($conexion, $sql)
    or die(mysqli_errno($conexion) . ": " . mysqli_error($conexion));

exit("0");