<?php  
include("../../conection/conexion.php");

//consulta logs
$tconsulta="SELECT * FROM logs order by fecha desc;";
$consulta=mysqli_query($conn, $tconsulta);
$total_solicitudes=mysqli_num_rows($consulta);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script>
 // Write on keyup event of keyword input element
 $(document).ready(function(){
 $("#search").keyup(function(){
 _this = this;
 // Show only matching TR, hide rest of them
 $.each($("#mytable tbody tr"), function() {
 if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
 $(this).hide();
 else
 $(this).show();
 });
 });
});
</script>

<link rel="stylesheet" href="../css/styleMenuA.css">

<div class="form-group pb-2">
    <h2><p class="pmenu">Logs</p></h2>
    <input type="text" class="form-control pull-right" id="search" placeholder="Escriba para buscar en la tabla..." style="width: 100%;">
</div>

<div class="table-responsive tablescrolly">
    <table class="table-bordered table pull-right table-striped" id="mytable" cellspacing="0" style="width: 100%;">
    <thead class="table-dark" style="position: sticky; top: 0; ">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Correo</th>
            <th scope="col">Nombre</th>
            <th scope="col">Accion</th>
            <th scope="col">Fecha y Hora</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($total_solicitudes>=1) { 
            while ($mostrar=mysqli_fetch_array($consulta)) {
            ?>
        <tr>
            <th scope="row"><?php echo $mostrar['ids'] ?></th>
            <td><?php echo $mostrar['usuario'] ?></td>
            <td><?php echo $mostrar['nombre'] ?></td>
            <td><?php echo $mostrar['accion'] ?></td>
            <td><?php echo $mostrar['fecha'] ?></td>
        </tr>
        <?php
            }  
        } 
        if ($total_solicitudes==0) { ?>
        <tr>
            <th scope="row"></th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php  } ?> 
    </tbody>
</table>
</div>