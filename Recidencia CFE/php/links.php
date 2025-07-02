<?php  
session_start();
$link=$_POST['link'];
include("../conection/conexion.php");

$tconsultal="INSERT INTO convocatorias (`url`) VALUES ('$link');";
$consultal=mysqli_query($conn, $tconsultal);
echo $consultal;
?>