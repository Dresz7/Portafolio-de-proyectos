<?php
include_once("../functions.php");

$cp = $_POST['mcp_txt'];
$colonia = $_POST['mcolonia_txt'];
$ciudad = $_POST['mciudad_txt'];
$municipio = $_POST['mmunicipio_txt'];
$estado = $_POST['mestado_txt'];

$sql = "INSERT INTO domicilios (codigoPostal, asentamiento, ciudad, municipio, estado)
        VALUES ('$cp', '$colonia', '$ciudad', '$municipio', '$estado')";
$query = mysqli_query($conexion, $sql)
    or die("Dirección no registrada");

exit ("0");
