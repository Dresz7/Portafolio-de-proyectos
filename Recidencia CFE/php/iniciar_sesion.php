<?php 
session_start();
$usuario=$_POST['usuario'];
$password=$_POST['password'];
include("../conection/conexion.php");

$tconsulta="SELECT * FROM cfe.usuarios where usuario ='$usuario'";
$consulta=mysqli_query($conn, $tconsulta);
$total_usuarios=mysqli_num_rows($consulta);
if ($total_usuarios==1) {
	$tconsulta2="select * from usuarios where usuario = '$usuario' and password = '$password'";
	$consulta2=mysqli_query($conn,$tconsulta2);
	$total_usuarios2=mysqli_num_rows($consulta2);
	$datos_usuario=mysqli_fetch_assoc($consulta2);
	if ($total_usuarios2==1) {
		$_SESSION['id'] = $datos_usuario['id'];
		$_SESSION['nombre'] = $datos_usuario['nombre'];
		$_SESSION['usuario'] = $datos_usuario['usuario'];
		$_SESSION['password'] = $datos_usuario['password'];
		$_SESSION['rol'] = $datos_usuario['rol'];
		$_SESSION['rpe'] = $datos_usuario['rpe'];
		$_SESSION['fecha_antiguedad'] = $datos_usuario['fecha_antiguedad'];
		$_SESSION['fecha_nacimiento'] = $datos_usuario['fecha_nacimiento'];
		$_SESSION['sexo'] = $datos_usuario['sexo'];
		$_SESSION['categoria'] = $datos_usuario['categoria'];
		$_SESSION['num_plaza'] = $datos_usuario['num_plaza'];
		$_SESSION['departamento'] = $datos_usuario['departamento'];
		$_SESSION['telefono'] = $datos_usuario['telefono'];
		$_SESSION['nss'] = $datos_usuario['nss'];
		$_SESSION['rfc'] = $datos_usuario['rfc'];
		$_SESSION['curp'] = $datos_usuario['curp'];
		$_SESSION['escolaridad'] = $datos_usuario['escolaridad'];
		$_SESSION['direccion'] = $datos_usuario['direccion'];
		$_SESSION['c.p'] = $datos_usuario['c.p'];
		$_SESSION['plaza_dejada'] = $datos_usuario['plaza_dejada'];
		$_SESSION['g.o'] = $datos_usuario['g.o'];
		$_SESSION['nivel'] = $datos_usuario['nivel'];
		$_SESSION['ultima_plaza'] = $datos_usuario['ultima_plaza'];
		$_SESSION['plaza_desde'] = $datos_usuario['plaza_desde'];
		$_SESSION['notas'] = $datos_usuario['notas'];
		
		if ($_SESSION['rol']=='admin') {
			//tomar datos necesarios
			$usuario = $datos_usuario['usuario'];
			$nombre = $datos_usuario['nombre'];
			date_default_timezone_set('America/Mazatlan');
			$hoy = date("Y-m-d H:i:s");

			//consultar el numero de registros
			$tconsulta="SELECT * FROM logs;";
			$consulta=mysqli_query($conn, $tconsulta);
			$total_solicitudes=mysqli_num_rows($consulta);

			//generar id
			$number = $total_solicitudes+1;
			$numberFormat = str_pad($number, 5, "0", STR_PAD_LEFT);  // Salida: "005"


		    $tconsultal="INSERT INTO logs (`ids`, `usuario`, `nombre`, `accion`, `fecha`) VALUES ('$numberFormat', '$usuario', '$nombre', 'Inicio sesion', '$hoy');";
		    $consultal=mysqli_query($conn, $tconsultal);
		}
		echo 3;
	}
	else{
		session_destroy();
		echo 1;
	}
}
else{
	echo 2;
}
?>