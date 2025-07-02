<?php
// Incluir el contenido de api.php
include 'api.php';

// Obtener tus datos, asegúrate de tener la lógica para obtener $datosd
$datosd = curl("devices");

// Devolver los datos como respuesta JSON
echo json_encode($datosd);
?>