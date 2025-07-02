<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
	$idSucursal = $_SESSION['sucursal'];
} ?>
<div class="card m-3">
    <div class="card-header fs-4 text-center fw-bold">Gastos generales - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?></div>
    <div class="card-body row">
        <div class="d-flex justify-content-center gap-2">
            <button
                type="button"
                class="btn btn-success my-1"
                data-bs-toggle="modal"
                data-bs-target="#registrarGasto">
                Registrar Gasto
            </button>

            <button
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#set_dates"
                class="btn btn-primary ms-auto">
                Rango personalizado
            </button>
        </div>
        <table
            id="table_gastos"
            data-toggle="table"
            data-locale="es-MX"
            data-search="true"
			data-filter-control="true"
            data-show-search-clear-button="true"
            data-loading-template="loadingTemplate"
            data-show-footer="true"
            data-show-refresh="true"
  data-show-toggle="true"
  data-show-columns="true"
  data-show-export="true"
            data-silent-sort="false">
        </table>
    </div>
</div>
<!-- Modal Fechas -->
<div
    class="modal fade"
    id="set_dates"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="set_datesLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <div
                    id="set_datesLabel"
                    class="modal-title fs-5 fw-bold">
                    Define el rango de fechas
                </div>
                <button
                    type="button"
                    data-bs-dismiss="modal"
                    aria-label="close"
                    class="btn-close">
                </button>
            </div>
            <div class="modal-body">
                <form
                    id="frm_ds"
                    method="post"
                    enctype="multipart/form-data">
                    <label for="f_inicio" class="form-label">
                        Fecha de inicio
                    </label>
                    <input
                        type="date"
                        name="f_inicio"
                        class="form-control my-1"
                        required>
                    <label for="f_final" class="form-label">
                        Fecha de cierre
                    </label>
                    <input
                        type="date"
                        name="f_final"
                        class="form-control my-1"
                        required>
                    <button
                        type="submit"
                        id="frm_ds_submit"
                        class="btn btn-primary offset-md-4 col-md-4 mt-3">
                        Consultar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Registrar Gasto -->
<div
    class="modal fade"
    id="registrarGasto"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="registrarGastoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-bold" id="registrarGastoLabel">
                    Registrar nuevo gasto
                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form
                    id="nGasto"
                    method="post"
                    enctype="multipart/form-data">
                    <label for="sucursalSelect" class="form-label">Sucursal</label>
				<select class="form-select" id="sucursalSelect" name="sucursalSelect" required>
                // Condiciones para mostrar las opciones
				<?php	
if ($idSucursal == 1) {
    // Si el id de la sucursal es 1, mostrar solo SAN JOSÉ DEL CABO
    echo '<option value="1">SAN JOSÉ DEL CABO</option>';
} elseif ($idSucursal == 2) {
    // Si el id de la sucursal es 2, mostrar solo LA PAZ
    echo '<option value="2">LA PAZ</option>';
} elseif ($idSucursal == 0) {
    // Si el id de la sucursal es 0, mostrar ambas opciones
	echo '<option value="" selected disabled> Selecciona</option>';
    echo '<option value="1">SAN JOSÉ DEL CABO</option>';
    echo '<option value="2">LA PAZ</option>';
}
?>
				</select>
                    <label
                        for="montoGasto"
                        class="form-label">
                        Monto
                    </label>
                    <input
                        type="text"
                        name="montoGasto"
                        id="montoGasto"
                        class="form-control my-1"
                        pattern="[0-9]{2,5}"
                        minlength="2"
                        maxlength="5"
                        required
                        autocomplete="off">
                    <label
                        for="fecha"
                        class="form-label mt-2">
                        Fecha del gasto
                    </label>
                    <input
                        type="datetime-local"
                        class="form-control my-1"
                        id="fechaGasto"
                        name="fechaGasto"
                        required
                        autocomplete="off">
                    <label
                        for="motivoGasto"
                        class="form-label">
                        Motivo del gasto
                    </label>
                    <input
                        type="text"
                        name="motivoGasto"
                        id="motivoGasto"
                        class="form-control my-1"
                        pattern="[0-9a-zA-ZñáéíóúÑÁÉÍÓÚ\s]{5,120}"
                        minlength="5"
                        maxlength="120"
                        required
                        autocomplete="off">
                    <button
                        type="submit"
                        id="btnMSubmit"
                        class="btn btn-primary offset-md-4 col-md-4 mt-3">
                        Registrar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="arrendamientos/js/gastos_generales.js"></script>
