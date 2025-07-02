<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
	$idSucursal = $_SESSION['sucursal'];
} ?>
<div class="card m-3">
	<div class="card-header fs-4 text-center fw-bold">Saldo en caja - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?></div>
	<div class="card-body row">
		<div class="hstack gap-3">
			<button
				id="btnNSiec"
				type="button"
				class="btn btn-success my-1"
				data-bs-toggle="modal"
				data-bs-target="#registrarSaldoCaja"
			>
				Registrar Saldo Inicial
			</button>
			<button
				id="btnNSfec"
				type="button"
				class="btn btn-danger my-1"
				data-bs-toggle="modal"
				data-bs-target="#registrarSaldoCaja"
			>
				Registrar Saldo Final
			</button>
			<button
				type="button"
				class="btn btn-primary ms-auto"
				data-bs-toggle="modal"
				data-bs-target="#set_dates_sec"
			>
				Seleccionar fecha
			</button>
		</div>
		<table
			class="table"
			id="table_saldo_caja"
			data-toggle="table"
			data-locale="es-MX"
			data-loading-template="loadingTemplate"
			data-show-footer="false"
			data-silent-sort="false"
		></table>
	</div>
</div>
<!-- Modal Seleccionar Semana -->
<div
	class="modal fade"
	id="set_dates_sec"
	data-bs-backdrop="static"
	data-bs-keyboard="false"
	tabindex="-1"
	aria-labelledby="set_dates_secLabel"
	aria-hidden="true"
>
	<div class="modal-dialog modal-fullscreen-sm-down">
		<div class="modal-content">
			<div class="modal-header">
				<div
					id="set_dates_secLabel"
					class="modal-title fs-5 fw-bold"
				>
					Define la fecha
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
					id="frm_sd_sec"
					method="post"
					enctype="multipart/form-data"
				>
					<label
						for="date_sec"
						class="form-label"
					>
						Selecciona la fecha de la semana
					</label>
					<input
						type="date"
						name="date_sec"
						class="form-control my-1"
						required
					/>
					<button
						type="submit"
						id="frm_sd_submit_sec"
						class="btn btn-primary offset-md-4 col-md-4 mt-3"
					>
						Consultar
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Modal Registrar Saldo en Caja -->
<div
	class="modal fade"
	id="registrarSaldoCaja"
	data-bs-backdrop="static"
	data-bs-keyboard="false"
	tabindex="-1"
	aria-labelledby="registrarSaldoCajaLabel"
	aria-hidden="true"
>
	<div class="modal-dialog modal-fullscreen-sm-down">
		<div class="modal-content">
			<div class="modal-header">
				<div
					class="modal-title fs-5 fw-bold"
					id="registrarSaldoCajaLabel"
				>
					Registrar saldo en caja
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
					id="nSaldoCaja"
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
						for="tipo_saldo_caja"
						class="form-label"
					>
						Saldo a registrar
					</label>
					<input
						name="tipo_saldo_caja"
						id="tipo_saldo_caja"
						type="text"
						class="form-control my-1"
						readonly>
					<label
						for="set_date_sec"
						class="form-label"
					>
						Fecha de registro
					</label>
					<input
						type="date"
						name="set_date_sec"
						class="form-control my-1"
						required
					/>
					<label
						for="monto_sec"
						class="form-label"
					>
						Monto en caja
					</label>
					<input
						type="text"
						name="monto_sec"
						class="form-control my-1"
						pattern="[0-9]{3,5}"
						minlength="3"
						maxlength="5"
						required
						autocomplete="off"
					/>
					<button
						type="submit"
						id="btnMsecSubmit"
						class="btn btn-primary offset-md-4 col-md-4 mt-3"
					>
						Registrar
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="arrendamientos/js/saldo_caja.js"></script>
