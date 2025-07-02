
<?php
header("Server-Timing: db;desc=\"Base de Datos\";dur=53, app;desc=\"Lógica de la Aplicación\";dur=47.2");

// Establecer la codificación de caracteres UTF-8
header('Content-Type: text/html; charset=utf-8');

include '../../php/scripts/api.php';

$datos = getData();

$mapHtml = generateMapHtml($datos);
echo $mapHtml;

// echo '<pre>';
// print_r($datos);
// echo '</pre>';


function degreesToCardinal($degrees) {
    $cardinals = ["N", "NE", "E", "SE", "S", "SO", "O", "NO", "N"];
    $index = floor(($degrees / 45) + 0.5);
    return $cardinals[$index];
}

function getArrowIcon($courseText) {
    $degrees = floatval($courseText); // Convertir texto a número
    if (!is_numeric($degrees)) {
        return ''; // Manejar si no se puede convertir a número
    }

    $arrow = "&#8593;"; // Flecha hacia arriba Unicode
    $rotation = "transform: rotate(${degrees}deg); display: inline-block; transform-origin: center;";
    return "<span style='font-size: 20px; ${rotation}'>${arrow}</span>";
}

function getLatitudeDirection($latitude) {
    return $latitude >= 0 ? 'N' : 'S';
}

function getLongitudeDirection($longitude) {
    return $longitude >= 0 ? 'E' : 'O';
}

// Funcion que agrega las opciones al select
function generateMapHtml($deviceData) {

    $deviceButtons = 
    '<div class="row mt-n1" data-device-type="default">
        <span class="dispositivod btn btn-outline-primary rounded-0 rounded-top" onclick="selectDevice(\'all\')">
            <div class="Nombre">Todos</div>
            <div class="id">Actualizar ubicaciones</div>
        </span>   
    </div>';

    $modals ='';

    $filters ='';
    
    $totalDevices = count($deviceData);

    $number = 0;

    $groupIdsAdded = array();

    // Agrega un botón o tarjeta por cada dispositivo
    foreach ($deviceData as $key => $device) {
        $deviceId = $device['deviceId'];
        $deviceName = $device['name'];
        $uniqueId = $device['uniqueId'];
        if (isset($device['groupName'])) {
            $groupName = $device['groupName'];
        } else {
            $groupName = "Ninguno";
        }
        $groupId = $device['groupId'];
        $latitude = $device['latitude'];
        $longitude = $device['longitude'];
        $course = $device['course'];
        $speed_knots = $device['speed'];
        $speed = $speed_knots * 1.852;// Conversión de nudos a km/h
        $batteryLevel = isset($device['attributes']['batteryLevel']) ? $device['attributes']['batteryLevel'] : '';

        
        // Verifica si es la última tarjeta de dispositivo
        $roundedClass = ($key == $totalDevices - 1) ? 'rounded-0 rounded-bottom' : 'rounded-0';

        if (isset($device['attributes']['ignition'])) {
            // El índice 'ignition' existe en $device['attributes']
        
            $ignitionValue = $device['attributes']['ignition'];
        
            if ($ignitionValue == 1) {
                $power = '<img class="pwr" data-device-id="' . $device['deviceId'].'"  src="arrendamientos/images/icons/on.svg" alt="power" data-bs-toggle="tooltip" data-bs-placement="top" title="Encendido">';
                $engine = 'encenderOApagarDispositivo(' . $device['deviceId'] . ')';
                $motor = 'Encendido';
            } else {
                $power = '<img class="pwr" data-device-id="' . $device['deviceId'].'" src="arrendamientos/images/icons/off.svg" alt="power" data-bs-toggle="tooltip" data-bs-placement="top" title="Apagado">';
                $engine = 'encenderOApagarDispositivo(' . $device['deviceId'] . ')';
                $motor = 'Apagado';
            }
        } else {
            // El índice 'ignition' no existe en $device['attributes']
            // Puedes manejar esta situación según tus necesidades
            $power = '';
            $engine = '';
            $motor = 'No disponible';
        }

        if ($batteryLevel) {
            $bateria = '<tr>
                            <th>Bateria:</th>
                            <td>'.$batteryLevel.'%</td>
                        </tr>';
        } else {
            $bateria = ''; 
        }
        

        $deviceButtons .= 
        '<div class="row" data-device-type="' . $groupName . '">
            <div class="dispositivo btn btn-outline-primary ' . $roundedClass . '" data-device-id="' . $deviceId . '" onclick="selectDevice(\'' . $deviceId . '\')">
                <div class="Nombre" data-full-text="' . $deviceName . '">' . $deviceName . '</div>
                <div class="id">' . $groupName . '</div>
                <div class="Power"><span onclick="event.stopPropagation(); ' . $engine . '">' . $power . '</span></div>
                <div class="Detalles" data-bs-toggle="modal" data-bs-target="#Modal'.$deviceId.'">
                    <img src="arrendamientos/images/icons/menu.svg" alt="mas">
                </div>
            </div>       
        </div>';

        $direction = degreesToCardinal($course);
        $arrow = getArrowIcon($course);
        $latitudeDirection = getLatitudeDirection($latitude);
        $longitudeDirection = getLongitudeDirection($longitude);

        $modals .= 
        '<div class="modal  fade" id="Modal'.$deviceId.'" tabindex="-1"  aria-labelledby="Modal'.$deviceId.'" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <p class="h5 modal-title" id="Modal'.$deviceId.'">' . $deviceName . '</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body scroll" style="max-height: 350px; overflow-y: auto;"> <!-- Establece un máximo de 7 filas y agrega un scroll si hay más -->
                        <table class="table table-sm"> 
                            <tr> 
                                <th>ID del dispositivo:</th> 
                                <td class="align-middle" >'.$deviceId.'</td> 
                            </tr>
                            <tr> 
                                <th>IMEI:</th> 
                                <td class="align-middle" >'.$uniqueId.'</td> 
                            </tr>
                            <tr>
                                <th>Grupo:</th> 
                                <td class="align-middle" >'.$groupName.'</td> 
                            </tr> 
                            <tr> 
                                <th>Latitud:</th> 
                                <td>'.abs($latitude).' '.$latitudeDirection.'.</td> 
                            </tr> 
                            <tr> 
                                <th>Longitud:</th> 
                                <td>'.abs($longitude).' '.$longitudeDirection.'.</td> 
                            </tr>  
                            <tr> 
                                <th>Curso:</th> 
                                <td>'.$course.'° '.$direction.'.   '.$arrow.'</td> 
                            </tr>
                             <tr> 
                                <th>Velocidad:</th> 
                                <td>'.$speed.' km/h - '.$speed_knots.' nudos</td> 
                            </tr>
                            <tr> 
                                <th>Motor:</th> 
                                <td>'.$motor.'</td> 
                            </tr> 
                            '.$bateria.' 
                        </table>
                    </div>
                </div>
            </div>
        </div>';

        // Comprobar si el groupId ya ha sido agregado
        if (!in_array($groupId, $groupIdsAdded)) {
            $filters .= 
            '<option value="'.$groupName.'">'.$groupName.'</option>';

            // Agregar el groupId al arreglo de seguimiento
            $groupIdsAdded[] = $groupId;

            $number++;
        }
    }
                

// Función para generar el código HTML con el mapa de Google Maps
    $mapHtml = <<<HTML
 <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">-->
    <link rel="stylesheet" href="arrendamientos/css/webhook.css">
    <div class="card m-3 w-100">
  <div class="card-header fs-4 text-center fw-bold">Rastreo de unidades</div>
  <div class="card-body">
<div class="Contenido">
    <div class="Mapa">
        <div id="map-container">
            <div id="map" class="skeleton-map"></div>
        </div>
    </div>
    <div id="sidebar" class="Selector form-control">
        <div class="Busqueda">
            <div class="input-group mb-1">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar">
                <div class="input-group-append">
                    <button id="filterButton" class="btn btn-outline-primary btn-sm collapsed" data-bs-toggle="collapse" data-bs-target="#filterOptions" aria-expanded="false" aria-controls="filterOptions">
                        <div class="input-group">
                            <div class="col-8">Filtro</div>
                            <div class="col-2"><img src="arrendamientos/images/icons/filter.svg" alt="filtro"></div>
                        </div>
                    </button>
                </div>
            </div>
            <div id="filterOptions" class="p-2 collapse form-control">
                <div>
                    <select id="filterSelect" class="" multiple style="width: 100%" lang="es">
                        $filters
                    </select>
                    <hr class="my-2"> <!-- Línea de separación -->
                    
                    <div class="btn-group d-flex justify-content-center" role="group" aria-label="filtros">
                        <button id="selectAllFilters" class="btn btn-outline-success btn-sm">Todos</button>
                        <button id="deselectAllFilters" class="btn btn-outline-danger btn-sm">Quitar Todos</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="deviceButtons" class="Dispositivos form-control scroll">
            $deviceButtons 
        </div>
    </div>
     <div id="cerrar" class="Cerrar">
         <button type="button" id="sidebarCollapse" class="navbar-btn rounded">
            <span></span>
            <span></span>
            <span></span>
        </button>
     </div>
</div>
</div>
</div>
$modals

<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <script async src="arrendamientos/js/webhookTraccar.js"  defer></script>
   <!-- <script src="js/webhook.js"></script>-->
    <!-- Api de google maps -->
   <!-- <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVgmBMxiVFWqCK1HNGRWHsNaLGEDEr2cg&callback=initMap"
      defer
    ></script>-->

    <script  async src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js" defer></script>

HTML;

    return $mapHtml;
}

// Función para obtener los datos de la api
function getData() {
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
            if (isset($groupMap[$device['groupId']])) {
                $device['groupName'] = $groupMap[$device['groupId']];
            }
        }
    }

    return $array1;
}
?>

