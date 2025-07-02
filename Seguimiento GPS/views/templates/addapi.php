<?php  
include '../../php/scripts/api.php';

$datosg = curl("groups");

$optionsg = filtrosg($datosg);

?>
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
            Agregar unidades
        </div>
        <div class="card-body">
            <div class="mb-2">
                <form id="addForm" class="form-control">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Nombre del dispositivo:</label>
                            <input type="text" class="form-control" maxlength="50" id="name" name="name" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="imei" class="form-label">IMEI (15 caracteres):</label>
                            <input type="text" class="form-control" id="imei" name="imei" maxlength="15" pattern=".{15}" title="El IMEI debe tener 15 caracteres." required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="model" class="form-label">Modelo:</label>
                            <input type="text" class="form-control" maxlength="50" id="model" name="model">
                        </div>
                        <div class="col-md-6">
                            <label for="group" class="form-label">Grupos:
                                <select id="group" name="group" class="form-select">
                                    <option value="ninguno">Sin grupo</option>
                                    <?php echo $optionsg['groups']; ?>
                                </select>
                            </label>
                        </div>
                    </div> 

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Número de teléfono (10 dígitos):</label>
                            <input type="tel" class="form-control" id="phone" name="phone" maxlength="10" pattern="[0-9]{10}" title="El número de teléfono debe tener 10 dígitos." required>
                        </div>
                    </div>

                    <div class="row mb-3 mx-1">
                        <button type="submit" class="btn btn-primary">Agregar Dispositivo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="arrendamientos/js/addapi.js"></script>
</body>
</html>