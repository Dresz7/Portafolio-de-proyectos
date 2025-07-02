<?php  

?>
<script> 
//Menú de distribución
    //Comité mixto de productividad
    $('#comitemixtoprod').on('click',function (event){
/*      $(".active").removeClass("active");
    $("#suministroM").addClass("active") 
    alert('Diste click en comité mixto de productividad');*/
    $('#contenidodis').empty();
    $('#contenidodis').load('submenus/comitemixtoproddis.php');
});
//Capacitación
$('#capacitacion').on('click',function (event){    
/*     $(".active").removeClass("active");
    $("#Navbarnav").addClass("active")
     alert('Diste click en capacitación');*/
     $('#contenidodis').empty();
     $('#contenidodis').load('submenus/capacitaciondis.php');
 });
//Seguridad e higiene
 $('#seguridadehig').on('click',function (event){    
/*     $(".active").removeClass("active");
    $("#Navbarnav").addClass("active")  
     alert('Diste click en seguridad e higiene');*/
     $('#contenidodis').empty();
     $('#contenidodis').load('submenus/seguridadehigdis.php');
 });  
</script>
<div>
    <nav class="navbar navbar-expand-lg navbar-light navbars">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Distribución</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fas fa-align-justify"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" id="comitemixtoprod"href="#" >Comité mixto de productividad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="capacitacion" href="#">Capacitación</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="seguridadehig" href="#">Seguridad e higiene</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="contenido-general" id="contenidodis">
                
</div>
</div>