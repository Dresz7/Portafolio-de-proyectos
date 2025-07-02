<?php

function curl($metodo){

    
    // URL de la API de Traccar
    $api_url = 'https://traccarbcs.com/api/';

    // Inicia una sesión cURL
    $ch = curl_init($api_url . $metodo);

    
    // Deshabilita la verificación del certificado SSL (INSEGURO)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Especifica la ubicación de tu certificado .pem
//curl_setopt($ch, CURLOPT_CAINFO, "arrendamientos/server.pem");
  
    // Configura las opciones de cURL para hacer una solicitud GET
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . base64_encode('centralonappafa@gmail.com:onappafa2019')]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 10 segundos de tiempo máximo de ejecución
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 segundos de tiempo máximo de espera de conexión


    // Realiza la solicitud GET
    $response = curl_exec($ch);

    // Verifica errores de cURL
    if ($response === false) {
        echo 'Error cURL: ' . curl_error($ch);
    } else {
        // Decodifica la respuesta JSON
        $data = json_decode($response, true);

        // Verifica si la solicitud fue exitosa
        if ($data) {
            return $data;
        } else {
            echo 'Error al decodificar la respuesta JSON.';
        }
    }

    // Cierra la sesión cURL
    curl_close($ch);
}

function filtrosg($groupData) {
    $groups = '';
    $groupIdsAdded = array();

    // Iterar sobre los datos de grupos
    foreach ($groupData as $group) {
        $groupId = $group['id'];
        $groupName = $group['name'];

        // Comprobar si el groupId ya ha sido agregado
        if (!in_array($groupId, $groupIdsAdded)) {
            $groups .= '<option value="' . $groupId . '">' . $groupName . '</option>';

            // Agregar el groupId al arreglo de seguimiento
            $groupIdsAdded[] = $groupId;
        }
    }

    return array('groups' => $groups); // Devolver opciones generadas solo para grupos
}


function filtrosd($deviceData) {
    $devices = '';
    $deviceIdsAdded = array();

    // Iterar sobre los datos de dispositivos
    foreach ($deviceData as $device) {
        $deviceId = $device['id'];
        $deviceName = $device['name'];

        // Comprobar si el deviceId ya ha sido agregado
        if (!in_array($deviceId, $deviceIdsAdded)) {
            $devices .= '<option value="' . $deviceId . '">' . $deviceName . '</option>';

            // Agregar el deviceId al arreglo de seguimiento
            $deviceIdsAdded[] = $deviceId;
        }
    }

    return array('devices' => $devices); // Devolver opciones generadas solo para dispositivos
}

?>
