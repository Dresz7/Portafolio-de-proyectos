<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $idSucursal = $_SESSION['sucursal'];
} ?>
<div class="container-md">
    <div class="card m-3">
        <div class="card-header fs-4 fw-bold text-center">
            Registrar arrendamiento - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?>
        </div>
        <div class="card-body text-center">
            <form
                method="POST"
                id="formulario_arrendamiento"
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
                        id="curp_cliente_txt"
                        name="curp_cliente_txt"
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
                            id="nombres_cliente_txt"
                            name="nombres_cliente_txt"
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
                        id="serie_vehiculo_txt"
                        name="serie_vehiculo_txt"
                        readonly="true">
                </section>
                <section class="col-md-3 my-1">
                    <label for="marca_vehiculo_txt">
                        Marca
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="marca_vehiculo_txt"
                        name="marca_vehiculo_txt"
                        readonly="true">
                </section>
                <section class="col-md-3 my-1">
                    <label for="linea_vehiculo_txt">
                        Línea
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="linea_vehiculo_txt_edit"
                        name="linea_vehiculo_txt_edit"
                        readonly="true">
                </section>
                <section class="col-md-3 my-1">
                    <label for="modelo_vehiculo_txt">
                        Modelo
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="modelo_vehiculo_txt"
                        name="modelo_vehiculo_txt"
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
                        id="f_expedicion_datetime"
                        name="f_expedicion_datetime"
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
                        id="f_vencimiento_datetime"
                        name="f_vencimiento_datetime"
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
                            id="tarifa_txt"
                            name="tarifa_txt"
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
                            id="monto_txt"
                            name="monto_txt"
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
    </div>
</div>

<script defer src="arrendamientos/js/alta_arrendamiento.js"></script>