<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
	$idSucursal = $_SESSION['sucursal'];
} ?>
<div class="card m-3">
    <div class="card-header fs-4 text-center fw-bold">Entradas de capital - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?></div>
    <div class="card-body">
    <section id="frm_container" class="offset-md-2 col-md-8">
        <form
            method="post"
            id="ds_form"
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
                for="f_inicio"
                class="form-label">
                Fecha de inicio
            </label>
            <input
                type="date"
                id="f_inicio"
                name="f_inicio"
                required="true"
                class="form-control my-1">
            <label
                for="f_final"
                class="form-label">
                Fecha de cierre
            </label>
            <input
                type="date"
                id="f_final"
                name="f_final"
                required="true"
                class="form-control my-1">
            <label
                for="m_pago"
                class="form-label">
                Método de pago
            </label>
            <select
                name="m_pago"
                id="m_pago"
                class="form-select my-1"
                required="true">
                <option value="" selected disabled>Seleccione</option>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia</option>
                <option value="1">Efectivo & Transferencia</option>
            </select>
            <button
                type="submit"
                id="ds_submit"
                class="btn btn-primary offset-md-2 col-md-8 mt-3 mb-2">
                Consultar
            </button>
        </form>
    </section>
    <section id="tb_container" class="d-none">
        <button id="btn_ec_back" class="btn btn-primary col-md-3 mb-2">Regresar</button>
        <table
            class="table"
            id="table_entradas_cap"
            data-locale="es-MX"
            data-search="true"
			data-filter-control="true"
            data-show-search-clear-button="true"
            data-show-footer="true"
            data-show-refresh="true"
  data-show-toggle="true"
  data-show-columns="true"
  data-show-export="true"
            data-loading-template="loadingTemplate"
            data-silent-sort="false">
        </table>
    </section>
    </div>
</div>
<script src="arrendamientos/js/entradas_capital.js"></script>