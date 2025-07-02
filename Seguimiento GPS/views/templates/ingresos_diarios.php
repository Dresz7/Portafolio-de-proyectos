<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
	$idSucursal = $_SESSION['sucursal'];
} ?>
<div class="card m-3 mb-5">
    <div class="card-header fs-4 text-center fw-bold">Ingresos diarios - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?></div>
    <div class="card-body">
    <section id="frm_container" class="offset-md-2 col-md-8">
        <form
            method="post"
            id="did_form"
            class="row"
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
	echo '<option value="0">TODAS LAS SUCURSALES</option>';
    echo '<option value="1">SAN JOSÉ DEL CABO</option>';
    echo '<option value="2">LA PAZ</option>';
}
?>
				</select>
            <label
                for="fecha_inicio"
                class="form-label">
                Fecha de inicio
            </label>
            <input
                type="date"
                id="fecha_inicio"
                name="fecha_inicio"
                required="true"
                class="form-control my-1">
            <label
                for="fecha_final"
                class="form-label">
                Fecha de cierre
            </label>
            <input
                type="date"
                id="fecha_final"
                name="fecha_final"
                required="true"
                class="form-control my-1">
            <button
                type="submit"
                id="did_submit"
                class="btn btn-primary offset-md-2 col-md-8 mt-3 mb-2">
                Consultar
            </button>
        </form>
    </section>
    <section id="tb_container" class="d-none">
        <div class="hstack gap-1">
            <button
                type="button"
                id="exportar"
                class="btn btn-success me-lg-1">
                    <i class="bi bi-file-earmark-pdf">PDF</i>
            </button>
            <button
                type="button"
                id="exportarExcel"
                class="btn btn-success">
                <i class="bi bi-file-earmark-excel">Excel</i>
            </button>
            <button
                id="set_dates_btn"
                type="button"
                class="btn btn-primary ms-auto mb-2">
                Seleccionar fechas
            </button>
        </div>
        <table
            class="table"
            id="table_ingresos_diarios"
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
            data-silent-sort="false">
        </table>
    </section>
    </div>
</div>
<script src="arrendamientos/js/ingresos_diarios.js"></script>