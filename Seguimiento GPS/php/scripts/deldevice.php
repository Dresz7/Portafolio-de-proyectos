<?php
try {
    // Establecer la zona horaria a America/Mazatlán
    date_default_timezone_set('America/Mazatlan');

    // Verificar si hay datos en la solicitud POST
    if (!empty($_POST)) {
        // Obtener el ID del dispositivo de la solicitud POST
        $deviceId = isset($_POST['deviceId']) ? $_POST['deviceId'] : '';

        // Verificar si se proporcionó el ID del dispositivo
        if (empty($deviceId)) {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "No se proporcionó el ID del dispositivo."));
            exit;
        }

        // URL del endpoint para eliminar dispositivo
        $url = "https://www.traccarbcs.com/api/devices/{$deviceId}";

        // Configuración de la solicitud cURL
        $ch = curl_init($url);

        // Configurar la solicitud cURL para realizar una petición DELETE
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Configurar las opciones para SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Configurar las cabeceras
        $headers = array(
            'Authorization: Basic ' . base64_encode('centralonappafa@gmail.com:onappafa2019')
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Ejecutar la solicitud cURL y obtener la respuesta
        $response = curl_exec($ch);

        // Verificar si hay errores
        if (curl_errno($ch)) {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("message" => 'Error en la solicitud cURL: ' . curl_error($ch)));
        } else {
            // Mostrar la respuesta del servidor
            echo $response;
        }

        // Cerrar la sesión cURL
        curl_close($ch);

    } else {
        // Manejo de errores si no hay datos en la solicitud POST
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "No se han proporcionado datos en la solicitud POST."));
    }
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}
?>