<?php  
session_start();

include("../../conection/conexion.php");

//consulta logs
$tconsulta="SELECT * FROM convocatorias order by id desc;";
$consulta=mysqli_query($conn, $tconsulta);
$total_solicitudes=mysqli_num_rows($consulta); 
?>
<html>
<head>

    <script type="text/javascript" src="../js/home.js"></script>
    <script type="text/javascript" src="../jquery/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="../sweetalert2/sweetalert2.all.min.js"></script>

</head>
<body>
    <div align="center">
        <!-- admin -->
        <?php if ($_SESSION['rol']=='admin') { ?>
            <form action="" class="form-control" style="width: 678px;" id="form_publicar">
                <div><span><h3>Crear publicación</h3></span><hr></div>
                <div><input class="form-control" id="link" name="link" required type="url" placeholder="Introduce el link de la publicación"></input><hr></div>
                <div><button type="submit" class="btn btn-primary mb-3 form-control">Publicar</button></div>
            </form>
            <div class="">
                <!-- consulta convocatorias -->

                <!-- si hay convocatorias -->
                <?php if ($total_solicitudes>=1) { 
                    while ($mostrar=mysqli_fetch_array($consulta)) {
                ?>
                <div class="form-control m-4" style="width: 678px;">
                    <div align="right" style="width: 650px;">
                        <form method="POST" id="eliminar_<?php echo $mostrar['id'] ?>">
                            <div class="row">
                                <div class="col" align="right" style="color: #fe0000;" id="borrado_<?php echo $mostrar['id'] ?>"></div>
                                <div class="col-1">
                                    <input type="hidden" name="id" id="id" value="<?php echo $mostrar['id'] ?>">
                                    <button type="submit" id="sidebarCollapse" class="navbar-btn" style="background: #fe0000; width: 30px; height: 30px;">
                                        <span style="background: #FFFFFF;"></span>
                                        <span style="background: #FFFFFF;"></span>
                                        <span style="background: #FFFFFF;"></span>
                                    </button></div>
                                </div>
                        </form>
                        <script type="text/javascript">
                            $( document ).ready(function() {
                                $('#eliminar_<?php echo $mostrar['id'] ?>').on('submit', function(event){
                                    event.preventDefault();
                                    // Swal.fire("<?php echo $mostrar['id'] ?>");
                                    $.post("../php/eliminarc.php",$("#eliminar_<?php echo $mostrar['id'] ?>").serialize(),function(datos){
                                        // Swal.fire(datos);
                                        if (datos==1) {
                                            // location.reload();
                                            document.getElementById('borrado_<?php echo $mostrar['id'] ?>').innerHTML='Recarga para eliminar';
                                        }
                                        if (datos==2) {
                                            Swal.fire({
                                                    icon: 'error',
                                                    title: 'Atencion',
                                                    text: 'Ocurrio un error!',
                                                    //footer: '<a href="">Why do I have this issue?</a>'
                                                })
                                        }
                                    });
                                });
                            });
                        </script>
                    </div>
                    <div class="pb-4">
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v15.0" nonce="4TUFqicY"></script>
                         <div class="fb-post" 
                        data-href="<?php echo $mostrar['url'] ?>"
                        data-width="650"
                       ></div>
                    </div>
                </div>
                <?php
                    }  
                }
                //no hay convocatorias 
                if ($total_solicitudes==0) { ?>
                    <div id="fb-root"></div>
                    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v15.0" nonce="4TUFqicY"></script>
                     <div class="fb-post" 
                    data-href="https://www.facebook.com/20531316728/posts/10154009990506729/"
                    data-width="650"
                    ></div>
                <?php  } ?> 
            </div> 
            <!-- usuario normal -->
        <?php   }else{ ?>
            <div class="pt-4">
                <!-- si hay convocatorias -->
                <?php if ($total_solicitudes>=1) { 
                    while ($mostrar=mysqli_fetch_array($consulta)) {
                ?>
                    <div class="pb-4">
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v15.0" nonce="4TUFqicY"></script>
                         <div class="fb-post" 
                        data-href="<?php echo $mostrar['url'] ?>"
                        data-width="650"
                       ></div>
                    </div>
                <?php
                    }  
                }
                //no hay convocatorias
                if ($total_solicitudes==0) { ?>
                    <div id="fb-root"></div>
                    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v15.0" nonce="4TUFqicY"></script>
                     <div class="fb-post" 
                    data-href="https://www.facebook.com/20531316728/posts/10154009990506729/"
                    data-width="650"
                    ></div>
                <?php  } ?> 
            </div>
        <?php } ?>
    </div>
</body>
</html>

