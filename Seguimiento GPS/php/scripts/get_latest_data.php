<?php
include 'api.php';

$array1 = curl("positions");
$array2 = curl("devices");
$array3 = curl("groups");

// Crear un mapeo de dispositivos basado en el valor [id] en el segundo array
$deviceMap = array();
foreach ($array2 as $device) {
    $deviceMap[$device['id']] = ['name' => $device['name'], 'groupId' => $device['groupId'], 'uniqueId' => $device['uniqueId']];
}

// Crear un mapeo de grupos basado en el valor [id] en el tercer array
$groupMap = array();
foreach ($array3 as $group) {
    $groupMap[$group['id']] = $group['name'];
}

// Agregar [name], [groupId], y [groupName] al primer array basado en los mapeos
foreach ($array1 as &$device) {
    $deviceId = $device['deviceId'];
    if (isset($deviceMap[$deviceId])) {
        $device['name'] = $deviceMap[$deviceId]['name'];
        $device['groupId'] = $deviceMap[$deviceId]['groupId'];
        $device['uniqueId'] = $deviceMap[$deviceId]['uniqueId'];
        
 // Verificar si existe un groupName para el groupId en el mapeo de grupos
        $device['groupName'] = isset($groupMap[$device['groupId']]) ? $groupMap[$device['groupId']] : 'Ninguno';
        
    }
}


// print_r($array1);

$Data = array();

foreach ($array1 as $item) {
    $speed_knots = $item['speed'];
    $speed = $speed_knots * 1.852; // Conversión de nudos a km/h

    $dataItem = array(
        'deviceId' => $item['deviceId'],
        'name' => $item['name'],
        'id' => $item['id'],
        'uniqueId' => $item['uniqueId'],
        'groupName' => $item['groupName'],
        'latitude' => $item['latitude'],
        'longitude' => $item['longitude'],
        'course' => $item['course'],
        'speed' => $speed,
        'batteryLevel' => isset($item['attributes']['batteryLevel']) ? $item['attributes']['batteryLevel'] : null,
        'ignition' => isset($item['attributes']['ignition']) ? $item['attributes']['ignition'] : null
    );

    $Data[] = $dataItem;
}


header('Content-Type: application/json; charset=utf-8');
echo json_encode($Data);
?>

