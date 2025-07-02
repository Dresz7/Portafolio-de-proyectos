<?php

try {
    // Establecer la zona horaria a America/Mazatlan
    date_default_timezone_set('America/Mazatlan');

    // Verificamos si hay datos en la solicitud POST
    // if (!empty($_POST)) {
        // Obtener el ID del dispositivo de la solicitud POST
        $deviceId = isset($_POST['deviceId']) ? $_POST['deviceId'] : '';

        // Verificar si se proporcionó el ID del dispositivo
        if (empty($deviceId)) {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "No se proporcionó el ID del dispositivo."));
            exit;
        }

        // Obtener datos individualmente del formulario
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $uniqueId = isset($_POST['imei']) ? $_POST['imei'] : '';
        $lastUpdate = date('c');

        // Verificar si el valor de 'group' es "ninguno" o puede ser convertido a entero
        $groupId = isset($_POST['group']) && $_POST['group'] !== 'ninguno' && is_numeric($_POST['group']) ? (int)$_POST['group'] : null;

        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $model = isset($_POST['model']) ? $_POST['model'] : '';

        // Datos del dispositivo a modificar
        $modified_device = array(
            "id" => $deviceId,
            "name" => $name,
            "uniqueId" => $uniqueId,
            "lastUpdate" => $lastUpdate,
            "groupId" => $groupId,
            "phone" => $phone,
            "model" => $model
        );

        // Convertir datos a formato JSON
        $jsonData = json_encode($modified_device);

        // URL del endpoint para modificar dispositivo
        $url = "https://www.traccarbcs.com/api/devices/{$deviceId}";

        // Configuración de la solicitud cURL
        $ch = curl_init($url);

        // Configurar la solicitud cURL para enviar datos JSON mediante PUT
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Configurar las opciones para SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Configurar las cabeceras
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode('centralonappafa@gmail.com:onappafa2019')
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Ejecutar la solicitud cURL y obtener la respuesta
        $response = curl_exec($ch);

        // Obtener el código de estado HTTP de la respuesta
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Verificar si hay errores
        if (curl_errno($ch)) {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("message" => 'Error en la solicitud cURL: ' . curl_error($ch)));
        } else {
             // Verificar si la respuesta fue un código de estado 400
            if ($httpCode === 400) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => 'Respuesta del servidor: Código de estado 400 - Bad Request'));
            } else {
                // Mostrar la respuesta del servidor
                echo $response;
            }
        }

        // Cerrar la sesión cURL
        curl_close($ch);

    // } else {
    //     // Manejo de errores si no hay datos en la solicitud POST
    //     http_response_code(400); // Bad Request
    //     echo json_encode(array("message" => "No se han proporcionado datos en la solicitud POST."));
    // }
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}
?>
