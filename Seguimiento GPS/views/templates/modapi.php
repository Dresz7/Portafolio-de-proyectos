<?php  
include '../../php/scripts/api.php';

$datosg = curl("groups");

$optionsg = filtrosg($datosg);

$datosd = curl("devices");

$options = filtrosd($datosd);

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
        form {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="card m-3">
    <div class="card-header fs-5 text-center fw-bold">
        Modificar unidades
    </div>
    <div class="card-body">
        <div class="mb-2">
            <div class="mb-3">
                <label for="id_label_single">
                    Dispositivos:
                    <select id="devices" name="devices" class="form-select">
                        <option value="ninguno">Seleccione un dispositivo</option>
                        <?php echo $options['devices']; 
                        ?>
                    </select>
                </label>
            </div>
            <form id="modForm" class="form-control">
                <input type="hidden" id="deviceId" name="deviceId">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Nombre del dispositivo:</label>
                        <input type="text" class="form-control" maxlength="50" id="name" name="name" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="imei" class="form-label">IMEI (15 caracteres):</label>
                        <input type="text" class="form-control" id="imei" maxlength="15" name="imei" pattern=".{15}" title="El IMEI debe tener 15 caracteres." required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="model" class="form-label">Modelo:</label>
                        <input type="text" class="form-control" maxlength="50" id="model" name="model">
                    </div>
                    <div class="col-md-6">
                        <label for="group" class="form-label">Grupo:</label>
                        <select class="form-control" id="group" name="group" required>
                            <option value="ninguno">Seleccione un grupo</option>
                            <option value="0">Sin grupo</option>
                            <?php echo $optionsg['groups']; ?>
                        </select>
                        <div id="group-error" style="color: red;"></div>
                    </div>
                </div> 

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Número de teléfono (10 dígitos):</label>
                        <input type="tel" class="form-control" id="phone" maxlength="10" name="phone" pattern="[0-9]{10}" title="El número de teléfono debe tener 10 dígitos." required>
                    </div>
                </div>

                <div class="row mb-3 mx-1">
                    <div class="col">    <button type="button" id="btnBorrar" class="btn btn-danger" disabled>Borrar Dispositivo</button></div>
                    <div class="col"><button type="submit" class="btn btn-success">Actualizar Dispositivo</button></div>
                </div> 
            </form>
        </div>
    </div>
</div>
<script src="arrendamientos/js/modapi.js"></script>
</body>
</html>