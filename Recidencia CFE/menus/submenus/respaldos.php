<?php  

?>
<script type="text/javascript" src="../jquery/jquery-3.6.0.js"></script>
<script type="text/javascript" src="../js/respaldos.js"></script>
<script type="text/javascript" src="../sweetalert2/sweetalert2.all.min.js"></script>

<h2><p class="pmenu">Backup</p></h2>

<form action="" id="form_importar">
    <div class="row">
        <div class="col-12" align="center">
            <div class="trans"> 
                <button class="btn btn-primary col-4" style="transform: skewX( -22deg );" id="exportar" type="button" >
                    <div style="transform: skewX( 22deg );">Exportar</div>
                </button>
                <button class="btn btn-info col-4" style="transform: skewX( -22deg );" id="importar" type="submit">
                    <div style="transform: skewX( 22deg );">Importar</div>
                </button> 
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-12 py-3" align="center">
            <div class="col-5">
                <input class="form-control" type="file" name="sql" id="sql" required>
            </div>
        </div>
    </div>
</form>