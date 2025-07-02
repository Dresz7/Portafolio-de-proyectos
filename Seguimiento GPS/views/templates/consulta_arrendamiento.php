<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $idSucursal = $_SESSION['sucursal'];
} ?>
<main class="card m-3">
    <section class="card-header fs-4 fw-bold text-center">
        Consulta Arrendamientos - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?>
    </section>
    <section class="card-body text-center">
            <table
                class="table"
                id="table_arrendamiento"
                data-toggle="table"
                data-locale="es-MX"
                data-search="true"
			data-filter-control="true"
            data-show-search-clear-button="true"
            data-loading-template="loadingTemplate"
            data-show-footer="false"
            data-show-refresh="true"
  data-show-toggle="true"
  data-show-columns="true"
  data-show-export="true"
                data-row-style="rowStyle">
            </table>
    </section>
</main>

<main
    class="modal fade modal-xl"
    id="abonarModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="abonarModalLabel"
    aria-hidden="true">
    <section class="modal-dialog modal-fullscreen-lg-down">
        <section class="modal-content">
            <section class="modal-header">
                <h5
                    class="modal-title"
                    id="abonarModalLabel">
                    Abono de arrendamiento
                </h5>
                <button
                    type="button"
                    id="btn-close-modal"
                    class="btn-close"
                    aria-label="Close">
                </button>
            </section>
            <section class="modal-body">
                <form
                    method="post"
                    id="frm_abonar_arrendamiento"
                    class="row text-center">
                    <section class="col-md-12 my-2">
                    <label for="sucursalSelect" class="form-label">Sucursal</label>
                    <select class="form-select" id="sucursalSelect" name="sucursalSelect" required>
   
        </select>
</section>
                    <input
                        type="text"
                        id="idArrendamiento"
                        name="idArrendamiento"
                        hidden>
                    <input
                        type="text"
                        id="montoPendiente"
                        name="montoPendiente"
                        hidden>
                        <div class="row">
                            <section class="col-md-6 my-2">
                                <label
                                    for="monto_txt"
                                    class="form-label">
                                    Cantidad por abonar
                                </label>
                                <input
                                    type="text"
                                    id="montoAbono"
                                    name="montoAbono"
                                    pattern="[0-9]{3,5}"
                                    minlength="3"
                                    maxlength="5"
                                    required
                                    autocomplete="off"
                                    class="form-control">
                            </section>
                            <section class="col-md-6 my-2">
                                <label
                                    for="metodoPago"
                                    class="form-label">
                                    Método de pago
                                </label>
                                <select
                                    name="metodoPago"
                                    id="metodoPago"
                                    class="form-select"
                                    required>
                                    <option value="" selected disabled>SELECCIONE</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </section>
                        </div>
                        <div class="row">
                            <section
                                class="offset-md-3 col-md-6 my-2">
                                <button
                                    type="submit"
                                    id="faArrendamientoSubmit"
                                    class="btn btn-primary"
                                    disabled>
                                    Abonar
                                </button>
                            </section>
                        </div>
                </form>
            </section>
        </section>
    </section>
</main>

<!-- Modal Editar Arrendamiento -->
<div class="modal fade" id="modalEditarArrendamiento" tabindex="-1" aria-labelledby="modalEditarArrendamientoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarArrendamientoLabel">Editar Arrendamiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form
                method="POST"
                id="formulario_arrendamientoedit"
                class="row needs-validation"
                novalidate>
                <p class="fs-5 fw-bold mt-2 mb-1">
                    Información del cliente
                </p>
                <section class="col-md-6 my-1">
                    <label
                        class="form-label"
                        for="curp_cliente_txt">
                        Clave curp del cliente
                    </label>
                    <input
                        type="text"
                        class="form-control cliente1"
                        id="curp_cliente_txt_edit"
                        name="curp_cliente_txt_edit"
                        placeholder=""
                        pattern="[0-9A-Z]{18}"
                        minlength="18"
                        maxlength="18"
                        aria-describedby=""
                        required
                        autocomplete="off">
                </section>
                <input
                    type="hidden"
                    id="idClienteH"
                    class="cliente1"
                    name="idClienteH"
                    required>
                <section class="col-md-6 my-1">
                    <label
                        class="form-label"
                        for="nombres_cliente_txt">
                        Nombre completo del cliente
                    </label>
                    <section class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control cliente1"
                            id="nombres_cliente_txt_edit"
                            name="nombres_cliente_txt_edit"
                            placeholder=""
                            aria-describedby=""
                            readonly>
                    </section>
                </section>

                <p class="fs-5 fw-bold mt-2 mb-1">
                    Información del vehículo
                </p>
                <input
                    type="hidden"
                    id="id_vehiculo_txt"
                    name="id_vehiculo_txt">
                <section class="col-md-3 my-1">
                    <label for="serie_vehiculo_txt">
                        Número de serie
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="serie_vehiculo_txt_edit"
                        name="serie_vehiculo_txt_edit"
                        readonly="true">
                </section>
                <section class="col-md-9 my-1">
                    <label for="vhRef_txt">
                        Vehiculo
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nombre_completo_veh"
                        name="nombre_completo_veh"
                        readonly="true">
                </section>

                <p class="fs-5 fw-bold mt-2 mb-1">
                    Información del arrendamiento
                </p>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="f_expedicion_datetime">
                        Fecha de expedición
                    </label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="f_expedicion_datetime_edit"
                        name="f_expedicion_datetime_edit"
                        placeholder=""
                        pattern=""
                        minlength=""
                        maxlength=""
                        required="true"
                        autocomplete="off"
                        aria-describedby="">
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="f_vencimiento_datetime">
                        Fecha de vencimiento
                    </label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="f_vencimiento_datetime_edit"
                        name="f_vencimiento_datetime_edit"
                        placeholder=""
                        pattern=""
                        minlength=""
                        maxlength=""
                        required="true"
                        autocomplete="off"
                        aria-describedby="">
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="diasT">
                        Días totales
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="diasT"
                        name="diasT"
                        readonly>
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="tarifa_txt">
                        Tarifa por día ($MXN)
                    </label>
                    <section class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-currency-dollar"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            id="tarifa_txt_edit"
                            name="tarifa_txt_edit"
                            readonly>
                    </section>
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="monto_txt">
                        Monto total
                    </label>
                    <section class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-currency-dollar"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            id="monto_txt_edit"
                            name="monto_txt_edit"
                            placeholder="Define las fechas"
                            readonly>
                    </section>
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="iva">
                        ¿Agregar IVA(16%)?
                    </label>
                    <div id="iva">
                        <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="iva"
                            id="ivaY"
                            value="0">
                        <label
                            class="form-check-label"
                            for="ivaY">
                            Si
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="iva"
                            id="ivaN"
                            value="0"
                            checked>
                        <label
                            class="form-check-label"
                            for="ivaN">
                            No
                        </label>
                        </div>
                    </div>
                </section>
                <section class="offset-md-2 col-md-8 my-1">
                    <label
                        class="form-label"
                        for="descuentos">
                        ¿Agregar descuento?
                    </label>
                    <div id="descuentos">
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento"
                                id="desc0"
                                value="0"
                                checked>
                            <label
                                for="desc0"
                                class="form-check-label">
                                0%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento"
                                id="desc1"
                                value=".05">
                            <label
                                for="desc1"
                                class="form-check-label">
                                5%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento"
                                id="desc2"
                                value=".1">
                            <label
                                for="desc2"
                                class="form-check-label">
                                10%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento"
                                id="desc3"
                                value=".15">
                            <label
                                for="desc3"
                                class="form-check-label">
                                15%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento"
                                id="desc4"
                                value=".2">
                            <label
                                for="desc4"
                                class="form-check-label">
                                20%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento"
                                id="desc5"
                                value=".25">
                            <label
                                for="desc5"
                                class="form-check-label">
                                25%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento"
                                id="desc6"
                                value=".3">
                            <label
                                for="desc6"
                                class="form-check-label">
                                30%
                            </label>
                        </div>
                    </div>
                </section>
                            <section class="col-md-4 my-1">
             
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
    
</section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="odometro">
                        Kilometraje en odómetro
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="odometro"
                        name="odometro"
                        placeholder=""
                        pattern="[0-9]{3,6}"
                        minlength="3"
                        maxlength="6"
                        required="true"
                        autocomplete="off"
                        aria-describedby="">

                        
   
                </section>
                <section class="col-md-4 my-1">
</section>
    
                <p class="fs-5 fw-bold mt-2 mb-1">
                    Información del chófer adicional
                </p>
                <section class="col-md-6 my-2">
                    <label
                        class="form-label"
                        for="curp_cliente_adicional_txt">
                        Clave curp cliente adicional
                    </label>
                    <input
                        type="text"
                        class="form-control cliente2"
                        id="curp_cliente_adicional_txt"
                        name="curp_cliente_adicional_txt"
                        placeholder=""
                        pattern="[0-9A-Z]{18}"
                        minlength="18"
                        maxlength="18"
                        autocomplete="off"
                        aria-describedby="">
                </section>
                <input
                    type="hidden"
                    class="cliente2"
                    id="idClienteAdH"
                    name="idClienteAdH"
                    value="0">
                <section class="col-md-6 my-2">
                    <label
                        class="form-label"
                        for="nombres_cliente_adicional_txt">
                        Nombre completo del cliente adicional
                    </label>
                    <section class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control cliente2"
                            id="nombres_cliente_adicional_txt"
                            name="nombres_cliente_adicional_txt"
                            readonly>
                            
                    </section>
                </section>
                <section class="offset-md-3 col-md-6 mt-2">
                    <button
                        type="submit"
                        id="fArrendamientoSubmit"
                        class="btn btn-success">
                        Enviar
                    </button>
                </section>
                
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarCambios">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Extender Arrendamiento -->
<div class="modal fade" id="modalExtenderArrendamiento" tabindex="-1" aria-labelledby="modalExtenderArrendamientoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalExtenderArrendamientoLabel">Extender Arrendamiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form
                method="POST"
                id="formulario_arrendamientoextend"
                class="row needs-validation"
                novalidate>
                <p class="fs-5 fw-bold mt-2 mb-1">
                    Información del cliente
                </p>
                <section class="col-md-6 my-1">
                    <label
                        class="form-label"
                        for="curp_cliente_txt">
                        Clave curp del cliente
                    </label>
                    <input
                        type="text"
                        class="form-control cliente1"
                        id="curp_cliente_txt_extend"
                        name="curp_cliente_txt_extend"
                        placeholder=""
                        pattern="[0-9A-Z]{18}"
                        minlength="18"
                        maxlength="18"
                        aria-describedby=""
                        required
                        autocomplete="off">
                </section>
                <input
                    type="hidden"
                    id="idClienteH"
                    class="cliente1"
                    name="idClienteH"
                    required>
                <section class="col-md-6 my-1">
                    <label
                        class="form-label"
                        for="nombres_cliente_txt">
                        Nombre completo del cliente
                    </label>
                    <section class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control cliente1"
                            id="nombres_cliente_txt_extend"
                            name="nombres_cliente_txt_extend"
                            placeholder=""
                            aria-describedby=""
                            readonly>
                    </section>
                </section>

                <p class="fs-5 fw-bold mt-2 mb-1">
                    Información del vehículo
                </p>
                <input
                    type="hidden"
                    id="id_vehiculo_txt"
                    name="id_vehiculo_txt">
                <section class="col-md-3 my-1">
                    <label for="serie_vehiculo_txt">
                        Número de serie
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="serie_vehiculo_txt_extend"
                        name="serie_vehiculo_txt_extend"
                        readonly="true">
                </section>
                <section class="col-md-9 my-1">
                    <label for="vhRef_txt">
                        Vehiculo
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nombre_completo_veh_extend"
                        name="nombre_completo_veh_extend"
                        readonly="true">
                </section>

                <p class="fs-5 fw-bold mt-2 mb-1">
                    Información del arrendamiento
                </p>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="f_expedicion_datetime">
                        Fecha de expedición
                    </label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="f_expedicion_datetime_extend"
                        name="f_expedicion_datetime_extend"
                        placeholder=""
                        pattern=""
                        minlength=""
                        maxlength=""
                        required="true"
                        autocomplete="off"
                        aria-describedby="">
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="f_vencimiento_datetime">
                        Fecha de vencimiento
                    </label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="f_vencimiento_datetime_extend"
                        name="f_vencimiento_datetime_extend"
                        placeholder=""
                        pattern=""
                        minlength=""
                        maxlength=""
                        required="true"
                        autocomplete="off"
                        aria-describedby="">
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="diasT_extend">
                        Días totales
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="diasT_extend"
                        name="diasT_extend"
                        readonly>
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="tarifa_txt">
                        Tarifa por día ($MXN)
                    </label>
                    <section class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-currency-dollar"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            id="tarifa_txt_extend"
                            name="tarifa_txt_extend"
                            readonly>
                    </section>
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="monto_txt">
                        Monto total
                    </label>
                    <section class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-currency-dollar"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            id="monto_txt_extend"
                            name="monto_txt_extend"
                            placeholder="Define las fechas"
                            readonly>
                    </section>
                </section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="iva">
                        ¿Agregar IVA(16%)?
                    </label>
                    <div id="iva">
                        <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="iva"
                            id="ivaY_extend"
                            value="0">
                        <label
                            class="form-check-label"
                            for="ivaY_extend">
                            Si
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="iva"
                            id="ivaN_extend"
                            value="0"
                            checked>
                        <label
                            class="form-check-label"
                            for="ivaN_extend">
                            No
                        </label>
                        </div>
                    </div>
                </section>
                <section class="offset-md-2 col-md-8 my-1">
                    <label
                        class="form-label"
                        for="descuentos_extend">
                        ¿Agregar descuento?
                    </label>
                    <div id="descuentos_extend">
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento_extend"
                                id="desc0"
                                value="0.00"
                                checked>
                            <label
                                for="desc0"
                                class="form-check-label">
                                0%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento_extend"
                                id="desc1"
                                value="0.05">
                            <label
                                for="desc1"
                                class="form-check-label">
                                5%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento_extend"
                                id="desc2"
                                value="0.10">
                            <label
                                for="desc2"
                                class="form-check-label">
                                10%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento_extend"
                                id="desc3"
                                value="0.15">
                            <label
                                for="desc3"
                                class="form-check-label">
                                15%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento_extend"
                                id="desc4"
                                value="0.20">
                            <label
                                for="desc4"
                                class="form-check-label">
                                20%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento_extend"
                                id="desc5"
                                value="0.25">
                            <label
                                for="desc5"
                                class="form-check-label">
                                25%
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                type="radio"
                                name="descuento_extend"
                                id="desc6"
                                value="0.30">
                            <label
                                for="desc6"
                                class="form-check-label">
                                30%
                            </label>
                        </div>
                    </div>
                </section>
                            <section class="col-md-4 my-1">
             
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
    
</section>
                <section class="col-md-4 my-1">
                    <label
                        class="form-label"
                        for="odometro">
                        Kilometraje en odómetro
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="odometro"
                        name="odometro"
                        placeholder=""
                        pattern="[0-9]{3,6}"
                        minlength="3"
                        maxlength="6"
                        required="true"
                        autocomplete="off"
                        aria-describedby="">

                        
   
                </section>
                <section class="col-md-4 my-1">
</section>
    
                <p class="fs-5 fw-bold mt-2 mb-1">
                    Información del chófer adicional
                </p>
                <section class="col-md-6 my-2">
                    <label
                        class="form-label"
                        for="curp_cliente_adicional_txt">
                        Clave curp cliente adicional
                    </label>
                    <input
                        type="text"
                        class="form-control cliente2"
                        id="curp_cliente_adicional_txt"
                        name="curp_cliente_adicional_txt"
                        placeholder=""
                        pattern="[0-9A-Z]{18}"
                        minlength="18"
                        maxlength="18"
                        autocomplete="off"
                        aria-describedby="">
                </section>
                <input
                    type="hidden"
                    class="cliente2"
                    id="idClienteAdH"
                    name="idClienteAdH"
                    value="0">
                <section class="col-md-6 my-2">
                    <label
                        class="form-label"
                        for="nombres_cliente_adicional_txt">
                        Nombre completo del cliente adicional
                    </label>
                    <section class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control cliente2"
                            id="nombres_cliente_adicional_txt"
                            name="nombres_cliente_adicional_txt"
                            readonly>
                            
                    </section>
                </section>
                <section class="offset-md-3 col-md-6 mt-2">
                    <button
                        type="submit"
                        id="fArrendamientoSubmit"
                        class="btn btn-success">
                        Enviar
                    </button>
                </section>
                
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarCambios">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Penalizaciones -->
<div class="modal fade" id="modalPenalizaciones" tabindex="-1" aria-labelledby="modalPenalizacionesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPenalizacionesLabel">Penalizaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <table
            id="tabla_penalizaciones"
            class="table"
            data-toggle="table"
            data-locale="es-MX"
            data-search="true"
			data-filter-control="true"
            data-show-search-clear-button="true"
            data-show-footer="true"
            data-show-refresh="true"
            data-show-toggle="true"
            data-show-columns="true"
            data-silent-sort="false"
            data-page-size="10"
            data-loading-template="loadingTemplate"
            data-show-footer="true"
            data-row-style="rowStyle">
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script>
 var idSucursalPHP = <?php echo json_encode($idSucursal); ?>

</script>
<script defer src="arrendamientos/js/consulta_arrendamiento.js"></script>
