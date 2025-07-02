<?php
include_once("../functions.php");

$curp = $_POST['curp'];

$sql = "SELECT id, curp, telefono, concat(nombres, ' ', apellidoPaterno, ' ', apellidoMaterno) AS nombreCompleto
FROM clientes WHERE curp = '$curp'";
$q = mysqli_query($conexion, $sql, MYSQLI_STORE_RESULT)
    or die('Intentalo más tarde o comunicate con un administrador');

if (mysqli_num_rows($q) == 0)
    die('El registro no existe');

if (mysqli_num_rows($q) > 1)
    die('El registro se encuentra duplicado');

$r = $q -> fetch_array(MYSQLI_ASSOC);
$jsonArray[] = array(
    'id' => $r['id'],
    'nombreCompleto' => $r['nombreCompleto'],
    'curp' => $r['curp'],
    'telefono' => $r['telefono']
);

exit(json_encode($jsonArray));