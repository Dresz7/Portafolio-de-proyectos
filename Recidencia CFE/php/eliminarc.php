<?php  
session_start();
$id=$_POST['id'];
include("../conection/conexion.php");

$tconsultal="DELETE FROM convocatorias WHERE (`id` = '$id');";
$consultal=mysqli_query($conn, $tconsultal);
echo $consultal;
?>