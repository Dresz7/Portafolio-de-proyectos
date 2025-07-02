<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<div class="card m-3 w-50">
    <div class="card-header fs-4 fw-bold text-center">
        Registro de clientes - <?php 
            echo $_SESSION['sucursal'] === "0" ? "Administrador" : 
                 ($_SESSION['sucursal'] === "1" ? "SAN JOSÉ" : 
                 ($_SESSION['sucursal'] === "2" ? "LA PAZ" : "Sucursal Desconocida"));
        ?>
    </div>
    <div class="card-body text-center">
        <form
            method="post"
            id="frm_cc_previa"
            class="row">
            <section class="offset-md-2 col-md-8 my-2">
                <label for="cc_text" class="form-label">
                    Introduce la clave curp del cliente
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="cc_text"
                    name="curp"
                    pattern="[0-9A-Z]{18}"
                    minlength="18"
                    maxlength="18"
                    required
                    autocomplete="off"
                    aria-describedby="ccInfo">
                <section
                    id="ccInfo"
                    class="form-text">
                    Caracteres en mayúsculas y números aceptados.
                </section>
            </section>
            <section class="offset-md-3 col-md-6 mt-2">
                <button
                    type="submit"
                    class="btn btn-success"
                    id="fccSubmit">
                    Consultar
                </button>
            </section>
        </form>
        <form
            id="msform"
            class="d-none"
            method="post"
            enctype="multipart/form-data">
            <!-- progressbar -->
            <ul id="progressbar" class="d-none d-md-block">
                <li class="active">personal</li>
                <li>domicilio</li>
                <!--<li>ine / ife</li> -->
                <li>licencia</li>
            </ul>
            <!-- fieldsets -->
            <fieldset id="fs1_clientes">
                <label
                    for="curp_txt"
                    class="form-label">
                    Clave curp
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="curp_txt"
                    name="curp_txt"
                    placeholder="Ingresa la información"
                    pattern="[0-9A-Z]{18}"
                    minlength="18"
                    maxlength="18"
                    required
                    autocomplete="off"
                    rechazado="Clave curp no válida"/>
                <label
                    for="nombre_txt"
                    class="form-label mt-2">
                    Nombres de pila
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="nombre_txt"
                    name="nombre_txt"
                    placeholder="Ingresa la información"
                    pattern="[a-zA-ZñáéíóúÑÁÉÍÓÚ\s]{2,30}"
                    minlength="2"
                    maxlength="30"
                    required
                    autocomplete="off"
                    rechazado="Nombres de pila no aceptados"/>
                <label
                    for="aPaterno_txt"
                    class="form-label mt-2">
                    Primer apellido
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="aPaterno_txt"
                    name="aPaterno_txt"
                    placeholder="Ingresa la información"
                    pattern="[a-zA-ZñáéíóúÑÁÉÍÓÚ\s]{2,30}"
                    minlength="2"
                    maxlength="30"
                    required
                    autocomplete="off"
                    rechazado="Primer apellido no aceptado"/>
                <label
                    for="aMaterno_txt"
                    class="form-label mt-2">
                    Segundo apellido
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="aMaterno_txt"
                    name="aMaterno_txt"
                    placeholder="Ingresa la información"
                    pattern="[a-zA-ZñáéíóúÑÁÉÍÓÚ\s]{0,30}"
                    minlength="0"
                    maxlength="30"
                    autocomplete="off"
                    rechazado="Segundo apellido no aceptado"/>
                <label
                    for="telefono_txt"
                    class="form-label mt-2">
                    Número de teléfono
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="telefono_txt"
                    name="telefono_txt"
                    placeholder="Ingresa la información"
                    pattern="[0-9]{10}"
                    minlength="10"
                    maxlength="10"
                    required
                    autocomplete="off"
                    rechazado="Número de teléfono no aceptado"/>
                <label
                    for="empresa_txt"
                    class="form-label mt-2">
                    Nombre de empresa
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="empresa_txt"
                    name="empresa_txt"
                    placeholder="Ingresa la información"
                    pattern="[0-9a-zA-ZñáéíóúÑÁÉÍÓÚ.\s]{3,45}"
                    minlength="3"
                    maxlength="45"
                    required
                    autocomplete="off"
                    rechazado="Nombre de empresa no aceptado"/>
                <button
                    type="button"
                    name="next"
                    class="btn btn-primary mt-3 col-4
                    next action-button">
                    siguiente
                </button>
            </fieldset>

            <fieldset id="fs2_clientes">
                <label
                    for="calle_txt"
                    class="form-label mt-2">
                    Nombre y número de la calle o vialidad
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="calle_txt"
                    name="calle_txt"
                    placeholder="Ingresa la información"
                    pattern="[0-9a-zA-ZñáéíóúÑÁÉÍÓÚ\s\/#]{3,60}"
                    minlength="3"
                    maxlength="60"
                    required
                    autocomplete="off"
                    rechazado="Nombre y número de calle no
                    aceptados"/>
                <div class="row mt-2">
                    <label
                        for="codigo_postal_txt"
                        class="form-label">
                        Código postal
                    </label>
                    <div class="col-6">
                        <input
                            type="text"
                            class="form-control my-1"
                            id="codigo_postal_txt"
                            name="codigo_postal_txt"
                            placeholder="Ingresa la información"
                            pattern="[0-9]{5}"
                            minlength="5"
                            maxlength="5"
                            required
                            autocomplete="off"
                            rechazado="Código postal no aceptado"/>
                    </div>
                    <div class="offset-1 col-5">
                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#nuevaDireccionModal">
                            Nueva dirección
                        </button>
                    </div>
                </div>
                <label
                    for="asentamiento_select"
                    class="form-label mt-2">
                    Asentamiento o colonia
                </label>
                <select
                    class="form-select my-1"
                    name="asentamiento_select"
                    id="asentamiento_select"
                    required
                    rechazado="Asentamiento rechazado">
                    <option value="" disabled selected>Código postal
                        requerido</option>
                </select>
                <label
                    for="ciudad_select"
                    class="form-label mt-2">
                    Ciudad o localidad
                </label>
                <select
                    class="form-select my-1"
                    name="ciudad_select"
                    id="ciudad_select"
                    required
                    rechazado="Ciudad rechazada">
                    <option value="" disabled selected>Código postal
                        requerido</option>
                </select>
                <label
                    for="municipio_select"
                    class="form-label mt-2">
                    Municipio o alcaldía
                </label>
                <select
                    class="form-select my-1"
                    name="municipio_select"
                    id="municipio_select"
                    required
                    rechazado="Municipio rechazado">
                    <option value="" disabled selected>Código postal
                        requerido</option>
                </select>
                <label
                    for="estado_select"
                    class="form-label mt-2">
                    Entidad federativa o estado
                </label>
                <select
                    class="form-select my-1"
                    name="estado_select"
                    id="estado_select"
                    required
                    rechazado="Entidad federativa rechazada">
                    <option value="" disabled selected>Código postal
                        requerido</option>
                </select>
                <button
                    type="button"
                    name="previous"
                    class="btn btn-warning col-4 me-2 mt-3
                    previous action-button">
                    anterior
                </button>
                <button
                    type="button"
                    name="next"
                    class="btn btn-primary col-4 mt-3
                    next action-button">
                    siguiente
                </button>
            </fieldset>

          <!--  <fieldset id="fs3_clientes">
                <label
                    for="clave_elector_txt"
                    class="form-label">
                    Clave de elector
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="clave_elector_txt"
                    name="clave_elector_txt"
                    placeholder="Ingresa la información"
                    pattern="[0-9A-Z]{18}"
                    minlength="18"
                    maxlength="18"
                    required
                    autocomplete="off"
                    rechazado="Clave de elector no aceptada"/>
                <label
                    for="seccion_electoral_txt"
                    class="form-label mt-2">
                    Sección electoral
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="seccion_electoral_txt"
                    name="seccion_electoral_txt"
                    list="seccionList"
                    placeholder="Ingresa la información"
                    pattern="[0-9]{4}"
                    minlength="4"
                    maxlength="4"
                    required
                    autocomplete="off"
                    rechazado="Sección electoral no aceptada"/>
                <datalist id="seccionList">
                </datalist>
                <label
                    for="distrito_electoral_txt"
                    class="form-label mt-2">
                    Distrito electoral
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="distrito_electoral_txt"
                    name="distrito_electoral_txt"
                    placeholder="Ingresa la información"
                    pattern="[0-9]{3}"
                    minlength="3"
                    maxlength="3"
                    required
                    autocomplete="off"
                    rechazado="Distrito electoral no aceptado"/>
                <button
                    type="button"
                    name="previous"
                    class="btn btn-warning col-4 me-2 mt-3
                    previous action-button">
                    anterior
                </button>
                <button
                    type="button"
                    name="next"
                    class="btn btn-primary col-4 mt-3
                    next action-button">
                    siguiente
                </button>
            </fieldset>-->

            <fieldset id="fs4_clientes">
                <label
                    for="no_licencia_txt"
                    class="form-label">
                    Número de licencia de conducir
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="no_licencia_txt"
                    name="no_licencia_txt"
                    placeholder="Ingresa la información"
                    pattern="[0-9A-Z]{6,15}"
                    minlength="6"
                    maxlength="15"
                    required
                    autocomplete="off"
                    rechazado="Número de licencia rechazado"/>
                <label
                    for="entidad_licencia_select"
                    class="form-label mt-2">
                    Entidad federativa que expidió licencia de conducir
                </label>
                <select
                    class="form-select my-1"
                    name="entidad_licencia_select"
                    id="entidad_licencia_select"
                    required
                    rechazado="Entidad de licencia rechazada">
                    <option value="" disabled selected>Seleccione</option>
                    <option value="Aguascalientes">Aguascalientes</option>
                    <option value="Baja California">Baja California</option>
                    <option value="Baja California Sur">Baja California Sur</option>
                    <option value="Campeche">Campeche</option>
                    <option value="Chiapas">Chiapas</option>
                    <option value="Chihuahua">Chihuahua</option>
                    <option value="Ciudad de México">Ciudad de México</option>
                    <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                    <option value="Colima">Colima</option>
                    <option value="Durango">Durango</option>
                    <option value="Guanajuato">Guanajuato</option>
                    <option value="Guerrero">Guerrero</option>
                    <option value="Hidalgo">Hidalgo</option>
                    <option value="Jalisco">Jalisco</option>
                    <option value="Estado de México">Estado de México</option>
                    <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                    <option value="Morelos">Morelos</option>
                    <option value="Nayarit">Nayarit</option>
                    <option value="Nuevo León">Nuevo León</option>
                    <option value="Oaxaca">Oaxaca</option>
                    <option value="Puebla">Puebla</option>
                    <option value="Querétaro">Querétaro</option>
                    <option value="Quintana Roo">Quintana Roo</option>
                    <option value="San Luis Potosí">San Luis Potosí</option>
                    <option value="Sinaloa">Sinaloa</option>
                    <option value="Sonora">Sonora</option>
                    <option value="Tabasco">Tabasco</option>
                    <option value="Tamaulipas">Tamaulipas</option>
                    <option value="Tlaxcala">Tlaxcala</option>
                    <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                    <option value="Yucatán">Yucatán</option>
                    <option value="Zacatecas">Zacatecas</option>
                </select>
                <label
                    for="telefono_emergencia_txt"
                    class="form-label mt-2">
                    Teléfono de emergencia
                </label>
                <input
                    type="text"
                    class="form-control my-1"
                    id="telefono_emergencia_txt"
                    name="telefono_emergencia_txt"
                    placeholder="Ingresa la información"
                    pattern="[0-9]{10}"
                    minlength="10"
                    maxlength="10"
                    required
                    autocomplete="off"
                    rechazado="Teléfono de emergencia no
                    aceptado">
                <label
                    for="f_expedicion_licencia_txt"
                    class="form-label mt-2">
                    Fecha de expedición de licencia de conducir
                </label>
                <input
                    type="date"
                    class="form-control my-1"
                    id="f_expedicion_licencia_txt"
                    name="f_expedicion_licencia_txt"
                    required
                    min="2015-01-01"
                    max="2035-01-01"
                    autocomplete="off"
                    rechazado="Fecha de expedición rechazada"/>
                <label
                    for="f_vencimiento_licencia_txt"
                    class="form-label mt-2">
                    Fecha de vencimiento de licencia de conducir
                </label>
                <input
                    type="date"
                    class="form-control my-1"
                    id="f_vencimiento_licencia_txt"
                    name="f_vencimiento_licencia_txt"
                    required
                    min="2015-01-01"
                    max="2035-01-01"
                    autocomplete="off"
                    rechazado="Fecha de vencimiento rechazada">
                <button
                    type="button"
                    name="previous"
                    class="btn btn-warning col-4 me-2 mt-3
                    previous action-button">
                    anterior
                </button>
                <button
                    type="submit"
                    id="fClienteSubmit"
                    class="btn btn-success col-4 mt-3
                    action-button">
                    enviar
                </button>
            </fieldset>
        </form>
    </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0
    end-0 p-3 mb-3">
    <div id="liveToast" class="toast" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="toast-header text-bg-danger
            border-0">
            <strong class="me-auto">Advertencia</strong>
            <button type="button" class="btn-close"
                data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
        <div id="toast-text" class="toast-body
            text-bg-danger border-0">
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="nuevaDireccionModal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="nuevaDireccionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="nuevaDireccionLabel">Registrar
                    nueva dirección</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form
                    id="nDireccion"
                    method="post"
                    enctype="multipart/form-data">
                    <label
                        for="mcp_txt"
                        class="form-label">Código postal</label>
                    <input
                        type="text"
                        class="form-control my-1"
                        id="mcp_txt"
                        name="mcp_txt"
                        placeholder="Ingresa la información"
                        pattern="[0-9]{5}"
                        minlength="5"
                        maxlength="5"
                        required
                        autocomplete="off">
                    <label
                        for="mcolonia_txt"
                        class="form-label mt-2">
                        Nombre de la colonia o asentamiento
                    </label>
                    <input
                        type="text"
                        class="form-control my-1"
                        id="mcolonia_txt"
                        name="mcolonia_txt"
                        placeholder="Ingresa la información"
                        pattern="[0-9a-zA-ZñáéíóúÑÁÉÍÓÚ\s\/]{3,30}"
                        minlength="3"
                        maxlength="30"
                        required
                        autocomplete="off"/>
                    <label
                        for="mciudad_txt"
                        class="form-label mt-3">Nombre de la ciudad o localidad</label>
                    <input
                        type="text"
                        class="form-control my-1"
                        id="mciudad_txt"
                        name="mciudad_txt"
                        placeholder="Ingresa la información"
                        pattern="[0-9a-zA-ZñáéíóúÑÁÉÍÓÚ\s\/]{3,30}"
                        minlength="3"
                        maxlength="30"
                        required
                        autocomplete="off"/>
                    <label
                        for="mmunicipio_txt"
                        class="form-label mt-3">Nombre del municipio o alcaldía</label>
                    <input
                        type="text"
                        class="form-control my-1"
                        id="mmunicipio_txt"
                        name="mmunicipio_txt"
                        placeholder="Ingresa la información"
                        pattern="[0-9a-zA-ZñáéíóúÑÁÉÍÓÚ\s\/]{3,30}"
                        minlength="3"
                        maxlength="30"
                        required
                        autocomplete="off"/>
                    <label
                        for="mestado_txt"
                        class="form-label mt-3">Nombre del estado o entidad
                        federativa</label>
                    <input
                        type="text"
                        class="form-control my-1"
                        id="mestado_txt"
                        name="mestado_txt"
                        placeholder="Ingresa la información"
                        pattern="[a-zA-ZñáéíóúÑÁÉÍÓÚ\s\/]{3,30}"
                        minlength="3"
                        maxlength="30"
                        required
                        autocomplete="off"/>
                    <button
                        type="submit"
                        id="fDirSubmit"
                        class="btn btn-primary offset-4 col-4 mt-3">
                        Registrar
                    </button>
                </form>
            </div>
        </div>
    </div>

<script src="arrendamientos/js/alta_cliente.js"></script>