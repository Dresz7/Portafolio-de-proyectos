<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
	$idSucursal = $_SESSION['sucursal'];
} ?>
<div class="card mt-3 mb-5">
  <div class="card-header fs-4 text-center fw-bold">Reporte de utilidades - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?></div>
  <div class="card-body">
    <section id="frm_eg_container" class="offset-md-2 col-md-8">
      <form
        method="post"
        id="eg_form"
        class="row"
        enctype="multipart/form-data"
      >
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
        <label for="feg_inicio" class="form-label"> Fecha de inicio </label>
        <input
          type="date"
          id="feg_inicio"
          name="feg_inicio"
          required="true"
          class="form-control my-1"
        />
        <label for="feg_final" class="form-label"> Fecha de inicio </label>
        <input
          type="date"
          id="feg_final"
          name="feg_final"
          required="true"
          class="form-control my-1"
        />
        <button
          type="submit"
          id="bg_submit"
          class="btn btn-primary offset-md-2 col-md-8 mt-3 mb-2"
        >
          Consultar
        </button>
      </form>
    </section>
    <section id="table_eg_container" class="d-none">
      <button type="button" id="btnBackEg" class="btn btn-primary my-1">
        Regresar
      </button>
      <table
        class="table"
        id="table_entradas_gastos"
        data-toggle="table"
        data-locale="es-MX"
        data-loading-template="loadingTemplate"
        data-show-footer="true"
        data-silent-sort="false"
      ></table>
    </section>
  </div>
</div>
<script src="arrendamientos/js/entradas_gastos.js"></script>
