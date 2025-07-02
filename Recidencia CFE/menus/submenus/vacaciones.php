<?php  
include("../../conection/conexion.php");
//RPE
session_start(); 
if (empty($_SESSION['id'])) {
    header('location: ../index.html');
}


//consulta vacaciones 
$tconsulta="SELECT * FROM vacaciones order by nombre desc;";
$consulta=mysqli_query($conn, $tconsulta);
$total_solicitudes=mysqli_num_rows($consulta);
?>

<link rel="stylesheet" href="../css/styleMenuA.css">

<div class="form-group pb-2">
    <h2><p class="pmenu">Vacaciones</p></h2>
   <!-- <input type="text" class="form-control pull-right" id="search" placeholder="Escriba para buscar en la tabla..."> -->
   <div class="input-group">
   <div class="col-auto"><label class="col-form-label">RP</label></div>
   <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['rpe'] ?>" readonly></div>  
</div>
</div>

<div class="table-responsive tablescroll">
    <table class="table-bordered table pull-right table-striped" id="mytable" cellspacing="0" style="width: 100%;">
    <thead class="table-dark" style="position: sticky; top: 0; ">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellido</th>
            <th scope="col">Edad</th>
            <th scope="col">Centro de trabajo</th>
            <th scope="col">Fecha de Inicio</th>
            <th scope="col">Aceptación</th>


        </tr>
    </thead>
    <tbody>
        <?php if ($total_solicitudes>=1) { 
            while ($mostrar=mysqli_fetch_array($consulta)) {
            ?>
        <tr>
            <th scope="row"><?php echo $mostrar['idvacaciones'] ?></th>
            <td><?php echo $mostrar['nombre'] ?></td>
            <td><?php echo $mostrar['apellido'] ?></td>
            <td><?php echo $mostrar['edad'] ?></td>
            <td><?php echo $mostrar['centro_de_trabajo'] ?></td>
            <td><?php echo $mostrar['fecha_de_inicio'] ?></td>
            <td><?php echo $mostrar['aceptacion'] ?></td>
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