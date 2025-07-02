<?php 
session_start();
if (empty($_SESSION['no'])) {
	header('location: ../index.html');
}
if ($_SESSION['rol']=='admin') {
	header('location: ../menus/menuadmin.php');
}
if ($_SESSION['rol']=='user') {
	header('location: ../menus/menuprincial.php');
}
?>
