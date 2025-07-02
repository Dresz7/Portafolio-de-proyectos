<?php
// Recibe los datos del formulario
$deviceIds = isset($_POST['dispositivos']) ? (is_array($_POST['dispositivos']) ? $_POST['dispositivos'] : explode(',', $_POST['dispositivos'])) : [];
$groupIds = isset($_POST['grupos']) ? (is_array($_POST['grupos']) ? $_POST['grupos'] : explode(',', $_POST['grupos'])) : [];
$from = isset($_POST['from']) ? $_POST['from'] : '';
$to = isset($_POST['to']) ? $_POST['to'] : '';

// Función para formatear las fechas al formato deseado
function formatDate($date)
{
    return date('Y-m-d\TH:i:s\Z', strtotime($date));
}

$baseURL = 'https://traccarbcs.com/api/reports/summary';

// Formatea las fechas al formato requerido
$formattedFrom = formatDate($from);
$formattedTo = formatDate($to);

// Construye los parámetros dinámicos
$params = '';

if (!empty($groupIds)) {
    foreach ($groupIds as $groupId) {
        $params .= 'groupId=' . $groupId . '&';
    }
}

if (!empty($deviceIds)) {
    foreach ($deviceIds as $deviceId) {
        $params .= 'deviceId=' . $deviceId . '&';
    }
}

$params .= 'from=' . urlencode($formattedFrom) . '&to=' . urlencode($formattedTo);

$fullURL = $baseURL . '?' . rtrim($params, '&');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $fullURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Authorization: Basic ' . base64_encode('correo:contraseña');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

echo $result; // Esto imprimirá la respuesta JSON obtenida
?>