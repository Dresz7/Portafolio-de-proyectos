<?php
include_once("../functions.php");

$seccion = $_POST['seccion'];

$sql = "SELECT distrito FROM seccionesbcs WHERE seccion = '$seccion'";
$q = mysqli_query($conexion, $sql)
    or die('000');

$distrito = (mysqli_num_rows($q) == 1)
    ? mysqli_fetch_array($q)
    : die('000');

exit($distrito['distrito']);