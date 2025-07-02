<?php  

include '../../php/scripts/api.php';

$datos = getData();

$options = filtros($datos);

function filtros($deviceData) {
    $devices = '';
    $deviceIdsAdded = array();
    
    $groups = '';
    $groupIdsAdded = array();

    // Iterar sobre los datos de dispositivos
    foreach ($deviceData as $key => $device) {
        $deviceId = $device['deviceId'];
        $deviceName = $device['name'];
        $groupId = $device['groupId'];
        $groupName = isset($device['groupName']) ? $device['groupName'] : "Ninguno";

        // Comprobar si el deviceId ya ha sido agregado
        if (!in_array($deviceId, $deviceIdsAdded)) {
            $devices .= '<option value="' . $deviceId . '">' . $deviceName . '</option>';

            // Agregar el deviceId al arreglo de seguimiento
            $deviceIdsAdded[] = $deviceId;
        }

        // Comprobar si el groupId ya ha sido agregado
        if (!in_array($groupId, $groupIdsAdded)) {
            $groups .= '<option value="' . $groupId . '">' . $groupName . '</option>';

            // Agregar el groupId al arreglo de seguimiento
            $groupIdsAdded[] = $groupId;
        }
    }

    return array('devices' => $devices, 'groups' => $groups); // Devolver opciones generadas para dispositivos y grupos
}




// Función para obtener los datos de la api
function getData() {
    $array1 = curl("positions");
    $array2 = curl("devices");
    $array3 = curl("groups");

    // Crear un mapeo de dispositivos basado en el valor [id] en el segundo array
    $deviceMap = array();
    foreach ($array2 as $device) {
        $deviceMap[$device['id']] = ['name' => $device['name'], 'groupId' => $device['groupId']];
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
            
            // Verificar si existe un groupName para el groupId en el mapeo de grupos
            if (isset($groupMap[$device['groupId']])) {
                $device['groupName'] = $groupMap[$device['groupId']];
            }
        }
    }

    return $array1;
}
?>



    <style>
        form {
            display: flex;
            align-items: center;
        }
    </style>

<div class="card m-3">
    <div class="card-header fs-5 text-center fw-bold">
        Reportes
    </div>
    <div class="card-body">
        <div class="mb-2">
            <form id="consultaForm">
                <div class="row align-items-end">
                    <div class="col-auto mt-1" style="max-width: 450px;">
                        <label for="id_label_multiple">
                            Dispositivos
                            <select id="dispositivos" name="dispositivos[]" class="form-select" multiple style="width: 100%;">
                                <?php echo $options['devices']; 
                                ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-auto mt-1" style="max-width: 350px;">
                        <label for="id_label_multiple">
                            Grupos
                            <select id="grupos" name="grupos[]" class="form-select" multiple style="width: 100%;">
                                <?php echo $options['groups'];  
                                ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-auto mt-1">
                        <label for="id_label_single">
                            Desde
                            <input type="datetime-local" id="from" name="from" class="form-select" required max="<?php echo date('Y-m-d\TH:i'); ?>" value="<?php echo date('Y-m-d\T00:00'); ?>">
                        </label>
                    </div>
                    <div class="col-auto mt-1">
                        <label for="id_label_single">
                            Hasta
                            <input type="datetime-local" id="to" name="to" class="form-select" required value="<?php echo date('Y-m-d\T23:59'); ?>">
                        </label>
                    </div>
                    <div class="col-auto mt-1">
                        <button type="submit" id="mostrarBtn" class="btn btn-primary" disabled>Mostrar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="mb-2">
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <button type="button" id="exportar" class="btn btn-success w-100">
                        <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
                    </button>
                </div>
             
                <div class="col-6 col-md-6 col-lg-6">
                    <button type="button" id="exportarExcel" class="btn btn-success w-100">
                        <i class="bi bi-file-earmark-excel"></i> Descargar en Excel
                    </button>
                </div>
            </div>
        </div>
        <table
            id="table_reporte"
            class="table"
            data-toggle="table"
            data-locale="es-MX"
            data-silent-sort="false"
            data-page-size="10"
            data-loading-template="loadingTemplate">
            <thead>
                <tr>
                    <th data-field="deviceName" data-sortable="true">Nombre del dispositivo</th>
                    <th data-field="distance" data-sortable="true">Distancia</th>
                    <th data-field="spentFuel" data-sortable="true">Combustible consumido</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script src="arrendamientos/js/Reportes.js"></script>

