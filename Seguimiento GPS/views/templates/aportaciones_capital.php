<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
	$idSucursal = $_SESSION['sucursal'];
} ?>
<div class="card m-3">
	<div class="card-header fs-4 text-center fw-bold">
		Aportaciones y financiamientos - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?>
	</div>
	<div class="card-body row">
		<div class="hstack gap-3">
			<button
				type="button"
				class="btn btn-success my-1"
				data-bs-toggle="modal"
				data-bs-target="#registrarAportacion"
			>
				Registrar Aportación
			</button>
			<button
				type="button"
				data-bs-toggle="modal"
				data-bs-target="#set_dates_ac"
				class="btn btn-primary ms-auto"
			>
				Rango personalizado
			</button>
		</div>
		<table
			class="table"
			id="table_aportaciones"
			data-toggle="table"
			data-locale="es-MX"
            data-search="true"
			data-filter-control="true"
            data-show-search-clear-button="true"
            data-show-footer="true"
            data-show-refresh="true"
  data-show-toggle="true"
  data-show-columns="true"
  data-show-export="true"
			data-silent-sort="false"
		></table>
	</div>
</div>
<!-- Modal Fechas -->
<div
	class="modal fade"
	id="set_dates_ac"
	data-bs-backdrop="static"
	data-bs-keyboard="false"
	tabindex="-1"
	aria-labelledby="set_dates_acLabel"
	aria-hidden="true"
>
	<div class="modal-dialog modal-fullscreen-sm-down">
		<div class="modal-content">
			<div class="modal-header">
				<div
					id="set_dates_acLabel"
					class="modal-title fs-5 fw-bold"
				>
					Define el rango de fechas
				</div>
				<button
					type="button"
					data-bs-dismiss="modal"
					aria-label="close"
					class="btn-close"
				></button>
			</div>
			<div class="modal-body">
				<form
					id="frm_sd_ac"
					method="post"
					enctype="multipart/form-data"
				>
					<label
						for="f_inicio_ac"
						class="form-label"
					>
						Fecha de inicio
					</label>
					<input
						type="date"
						name="f_inicio_ac"
						class="form-control my-1"
						required
					/>
					<label
						for="f_final_ac"
						class="form-label"
					>
						Fecha de cierre
					</label>
					<input
						type="date"
						name="f_final_ac"
						class="form-control my-1"
						required
					/>
					<button
						type="submit"
						id="frm_sd_submit_ac"
						class="btn btn-primary offset-md-4 col-md-4 mt-3"
					>
						Consultar
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Modal Registrar Aportación -->
<div
	class="modal fade"
	id="registrarAportacion"
	data-bs-backdrop="static"
	data-bs-keyboard="false"
	tabindex="-1"
	aria-labelledby="registrarAportacionLabel"
	aria-hidden="true"
>
	<div class="modal-dialog modal-fullscreen-sm-down">
		<div class="modal-content">
			<div class="modal-header">
				<div
					class="modal-title fs-5 fw-bold"
					id="registrarAportacionLabel"
				>
					Registrar nueva aportación de capital
				</div>
				<button
					type="button"
					class="btn-close"
					data-bs-dismiss="modal"
					aria-label="Close"
				></button>
			</div>
			<div class="modal-body">
				<form
					id="nAportacion"
					method="post"
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
    echo '<option value="1">SAN JOSÉ DEL CABO</option>';
    echo '<option value="2">LA PAZ</option>';
}
?>
				</select>
					<label
						for="tAportacion"
						class="form-label"
					>
						Tipo de aportación
					</label>
					<select
						class="form-select"
						name="tAportacion"
						id="tAportacion"
						required
					>
						<option
							value=""
							selected
							disabled
						>
							Selecciona
						</option>
						<option value="efectivo">Efectivo</option>
						<option value="transferencia">Transferencia</option>
					</select>
					<label
						for="montoAportacion"
						class="form-label"
					>
						Monto
					</label>
					<input
						type="text"
						name="montoAportacion"
						id="montoAportacion"
						class="form-control my-1"
						pattern="[0-9]{3,5}"
						minlength="3"
						maxlength="5"
						required
						autocomplete="off"
					/>
					<label
						for="fechaAportacion"
						class="form-label mt-2"
					>
						Fecha de aportación
					</label>
					<input
						type="date"
						class="form-control my-1"
						id="fechaAportacion"
						name="fechaAportacion"
						required
						autocomplete="off"
					/>
					<button
						type="submit"
						id="btnMacSubmit"
						class="btn btn-primary offset-md-4 col-md-4 mt-3"
					>
						Registrar
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="arrendamientos/js/aportaciones_capital.js"></script>
