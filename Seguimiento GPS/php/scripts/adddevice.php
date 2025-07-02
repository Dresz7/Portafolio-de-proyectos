<?php

try {
    // Establecer la zona horaria a America/Mazatlan
    date_default_timezone_set('America/Mazatlan');

    // Verificamos si hay datos en la solicitud POST
    if (!empty($_POST)) {

        // Obtener datos individualmente del formulario
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $uniqueId = isset($_POST['imei']) ? $_POST['imei'] : '';
        $lastUpdate = date('c');

        // Verificar si el valor de 'group' es "ninguno" o puede ser convertido a entero
        $groupId = isset($_POST['group']) && $_POST['group'] !== 'ninguno' && is_numeric($_POST['group']) ? (int)$_POST['group'] : null;

        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $model = isset($_POST['model']) ? $_POST['model'] : '';

        // Verificar si el valor de 'uniqueId' ya existe en la API de Traccar
        $urlVerificacion = "https://www.traccarbcs.com/api/devices?uniqueId=" . urlencode($uniqueId);

        // Configuración de la solicitud cURL
        $chVerificacion = curl_init($urlVerificacion);
        curl_setopt($chVerificacion, CURLOPT_RETURNTRANSFER, true);

        // Configurar las opciones para SSL
        curl_setopt($chVerificacion, CURLOPT_SSL_VERIFYPEER, false);

        // Configurar las cabeceras
        $headersVerificacion = array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode('centralonappafa@gmail.com:onappafa2019')
        );
        curl_setopt($chVerificacion, CURLOPT_HTTPHEADER, $headersVerificacion);

        // Ejecutar la solicitud cURL y obtener la respuesta
        $responseVerificacion = curl_exec($chVerificacion);

        // Verificar si hay errores en la verificación
        if (curl_errno($chVerificacion)) {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("status" => "error", "message" => 'Error en la solicitud de verificación cURL: ' . curl_error($chVerificacion)));
        } else {
            // Convertir la respuesta a un array
            $devices = json_decode($responseVerificacion, true);

            // Verificar si ya existe un dispositivo con el mismo 'uniqueId'
            if (!empty($devices)) {
                // El valor ya existe, manejar el error
                http_response_code(400); // Bad Request
                echo json_encode(array("status" => "error", "message" => "Ya hay un vehiculo registrado con ese IMEI."));
            } else {
                // El valor no existe, proceder con la inserción
                $new_device = array(
                    "name" => $name,
                    "uniqueId" => $uniqueId,
                    "lastUpdate" => $lastUpdate,
                    "groupId" => $groupId,
                    "phone" => $phone,
                    "model" => $model,
                );

                // Convertir datos a formato JSON
                $jsonData = json_encode($new_device);

                // URL del endpoint para agregar dispositivo
                $url = "https://www.traccarbcs.com/api/devices";

                // Configuración de la solicitud cURL
                $ch = curl_init($url);

                // Configurar la solicitud cURL para enviar datos JSON mediante POST
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
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

                // Verificar si hay errores
                if (curl_errno($ch)) {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(array("status" => "error", "message" => 'Error en la solicitud cURL: ' . curl_error($ch)));
                } else {
                    // Mostrar la respuesta del servidor
                    echo json_encode(array("status" => "success", "data" => $response));
                }

                // Cerrar la sesión cURL
                curl_close($ch);
            }
        }

        // Cerrar la sesión cURL de verificación
        curl_close($chVerificacion);

    } else {
        // Manejo de errores si no hay datos en la solicitud POST
        http_response_code(400); // Bad Request
        echo json_encode(array("status" => "error", "message" => "No se han proporcionado datos en la solicitud POST."));
    }
} catch (Exception $e) {
    echo json_encode(array("status" => "error", "message" => 'Excepción capturada: ' . $e->getMessage()));
}
?>
