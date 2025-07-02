<?php
include_once("../functions.php");

$idCliente = $_POST['idCliente'];

$sql = "SELECT cli.id, cli.nombres, cli.apellidoPaterno, cli.apellidoMaterno, cli.telefono, cli.curp, cli.calle, cli.idDomicilio, cli.nombreEmpresa,
                dom.codigoPostal, dom.asentamiento, dom.ciudad, dom.municipio, dom.estado,
                il.fechaExpedicion, il.fechaVencimiento, il.entidadLicencia, il.numeroLicencia, il.telefonoEmergencia
                FROM clientes cli
                INNER JOIN domicilios dom ON dom.id = cli.idDomicilio
                INNER JOIN info_licencia il ON cli.id = il.id
                WHERE cli.id = $idCliente";
$q = mysqli_query($conexion, $sql)
    or die('Consulta no realizada');

if (mysqli_num_rows($q) != 1)
    die('Registro inexistente o duplicado');

$r = mysqli_fetch_array($q);

$jsonArray[] = array(
    'id' => $r['id'],
    'nombres' => $r['nombres'],
    'apellidoPaterno' => $r['apellidoPaterno'],
    'apellidoMaterno' => $r['apellidoMaterno'],
    'telefono' => $r['telefono'],
    'curp' => $r['curp'],
    'calle' => $r['calle'],
    'idDomicilio' => $r['idDomicilio'],
    'nombreEmpresa' => $r['nombreEmpresa'],
    'codigoPostal' => $r['codigoPostal'],
    'asentamiento' => $r['asentamiento'],
    'ciudad' => $r['ciudad'],
    'municipio' => $r['municipio'],
    'estado' => $r['estado'],
   /* 'claveElector' => $r['claveElector'],
    'seccion' => $r['seccion'],
    'distrito' => $r['distrito'],*/
    'fechaExpedicion' => $r['fechaExpedicion'],
    'fechaVencimiento' => $r['fechaVencimiento'],
    'entidadLicencia' => $r['entidadLicencia'],
    'numeroLicencia' => $r['numeroLicencia'],
    'telefonoEmergencia' => $r['telefonoEmergencia']
);

exit(json_encode($jsonArray));
