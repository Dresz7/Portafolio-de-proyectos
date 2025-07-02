<?php
include_once("../functions.php");

// Asume que $_POST['idArrendamiento'] y $_POST['idSucursalCambio'] están correctamente validados antes de usarlos.
$idArrendamiento = $_POST['idArrendamiento'];
$idSucursalCambio = $_POST['idSucursalCambio'];

// Prepara la sentencia SQL para actualizar el campo idSucursal del arrendamiento específico
$sql = "UPDATE arrendamientos SET idSucursal = ? WHERE id = ?";

// Preparar sentencia
$stmt = $conexion->prepare($sql);
if ($stmt === false) {
    die('Error al preparar la actualización: ' . $conexion->error);
}

// Vincula los parámetros a la sentencia SQL
$stmt->bind_param("ii", $idSucursalCambio, $idArrendamiento);

// Ejecutar la actualización
if ($stmt->execute()) {
    // Éxito en la actualización
    $response = ['respuesta' => 0, 'mensaje' => 'Actualización exitosa'];
} else {
    // Error en la actualización
    $response = ['respuesta' => 1, 'mensaje' => 'Error al actualizar: ' . $stmt->error];
}

// Cerrar la sentencia
$stmt->close();

// Codificar y devolver la respuesta como JSON
echo json_encode($response);

?>