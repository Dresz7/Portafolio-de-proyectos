<?php
// session_start(); 
// if (empty($_SESSION['id'])) {
//     header('location: ../index.php');
// }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Menú de las secretarías CFE</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap5.min.css">
    <link rel="stylesheet" href="../css/styleMenuG.css">

    <!-- JS -->
    <script type="text/javascript" src="../fontawesome/solid.js"></script>
    <script defer src="../fontawesome/fontawesome.js"></script>
    <script type="text/javascript" src="../jquery/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="../jquery/popper.min.js"></script>
    <script type="text/javascript" src="../bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="../bootstrap/bootstrap5.min.js"></script>
    <script type="text/javascript" src="../sweetalert2/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="../js/submenus.js"></script>
    <script type="text/javascript" src="../js/menuprincial.js"></script>

</head>
<body>
    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>CFE</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Menú de Secretarías</p>
                <li id="TrabajoM">
                    <a href="#TrabajoSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Trabajo</a>
                    <ul class="collapse list-unstyled" id="TrabajoSubmenu">
                        <li>
                            <a href="#" id="vacaciones">Vacaciones</a>
                        </li>
                        <li>
                            <a href="#" id="jubilaciones">Jubilaciones</a>
                        </li>
                        <li>
                            <a href="#" id="guardiasemerg">Guardias para emergencias</a>
                        </li>
                        <li>
                            <a href="#" id="capacitaciones">Capacitaciones</a>
                        </li>
                        <li>
                            <a href="#" id="rolestemporales">Roles temporales</a>
                        </li>
                    </ul>
                </li>

                <li id="FinanzasM">
                    <a href="#FinanzasSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Finanzas</a>
                    <ul class="collapse list-unstyled" id="FinanzasSubmenu">
                        <li>
                            <a href="#" id="estadosfinancieros">Estados Financieros</a>
                        </li>
                        <li>
                            <a href="#" id="informesmensuales">Informes mensuales</a>
                        </li>
                    </ul>
                </li>
                
                <li id="PrevSocialM">
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Previsión Social</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#" id="citasmedicas">Citas Médicas</a>
                        </li>
                        <li>
                            <a href="#" id="traslados">Traslados</a>
                        </li>
                        <li>
                            <a href="#" id="expedientes">Expedientes</a>
                        </li>
                    </ul>
                </li>              
            </ul>

            <ul class="list-unstyled CTAs">
                    <!--
                        class = "article"
                        class = "download"   
                    -->
                <li>
                    <a href="../php/logout.php" class="download">Cerrar Sesión</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light navbarp">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="navbar-btn" id="nommenu"></div>

                    <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" id="Home" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modal_usuario">Usuario</a>
                            </li>
                            <?php if ($_SESSION['rol']=='admin') { echo "<li class='nav-item'>
                                    <a class='nav-link' id='Home' href='menuadmin.php'>Administrador</a>
                                </li>";?>
                                
                            <?php   } ?>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <div class="contenido-general" id="contenido">
                
            </div>
        </div>
    </div>

    <!-- Modal usuario -->
    <div class="modal fade" id="modal_usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold" id="exampleModalLabel">Información Personal de Usuario</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    <!-- Form general --> 
                    <form action="" class="form-control">
                        <div class="text-center p-2"><h5>Información General</h5></div>
                        <div class="row g-3 align-items-center p-2">
                            <div class="col-auto"><label class="col-form-label">Nombre Completo</label></div>
                            <div class="col"><input class="form-control" type="text" value="<?php echo $_SESSION['nombre'] ?>" readonly></div>
                        </div>
                        <div class="row g-3 align-items-center p-2">
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Correo</label></div>
                                    <div class="col"><input class="form-control" type="text" value="<?php echo $_SESSION['usuario'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Contraseña</label></div>
                                    <div class="col">
                                        <div class="input-group">
                                            <input ID="txtPassword" type="Password" Class="form-control" value="<?php echo $_SESSION['password'] ?>" readonly>
                                            <div class="input-group-append">
                                                <button id="show_password" class="btn btn-primary" type="button" ><span class="fa fa-eye-slash icon"></span> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row g-3 align-items-center p-2">
                            <div class="col-auto"><label class="col-form-label">Direccion</label></div>
                            <div class="col"><input class="form-control" type="text" value="<?php echo $_SESSION['direccion'] ?>" readonly></div>
                        </div>
                        <div class="row g-3 align-items-center px-2 pb-2">
                            <div class="col">
                                <div class="row"> 
                                    <div class="col-auto"><label class="col-form-label">Telefono</label></div>
                                    <div class="col-12"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['telefono'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Codigo Postal</label></div>
                                    <div class="col-12"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['c.p'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Escolaridad</label></div>
                                    <div class="col-12"><input class="form-control" type="text" value="<?php echo $_SESSION['escolaridad'] ?>" readonly></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center p-2">
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Fecha de Naciemiento</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['fecha_nacimiento'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">CURP</label></div>
                                    <div class="col-auto"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['curp'] ?>" readonly></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center p-2">
                            <div class="col">
                                <div class="row"> 
                                    <div class="col-auto"><label class="col-form-label">RFC</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['rfc'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Numero Seguro Social</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['nss'] ?>" readonly></div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Form empleado -->
                    <form action="" class="form-control">
                        <div class="text-center p-2"><h5>Información de Empleado</h5></div>
                        <div class="row g-3 align-items-center px-2 pb-2">
                            <div class="col">
                                <div class="row"> 
                                    <div class="col-auto"><label class="col-form-label">Categoria</label></div>
                                    <div class="col-12"><input class="form-control" type="text" value="<?php echo $_SESSION['categoria'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">No. Plaza</label></div>
                                    <div class="col-12"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['num_plaza'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Departamento</label></div>
                                    <div class="col-12"><input class="form-control" type="text" value="<?php echo $_SESSION['departamento'] ?>" readonly></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center p-2">
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Plaza Desde</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['plaza_desde'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Antiguedad</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['fecha_antiguedad'] ?>" readonly></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center px-2 p-2">
                            <div class="col">
                                <div class="row"> 
                                    <div class="col-auto"><label class="col-form-label">Nivel</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['nivel'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">R.P.E</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['rpe'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">G.O</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['g.o'] ?>" readonly></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center p-2">
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Ultima Plaza</label></div>
                                    <div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['ultima_plaza'] ?>" readonly></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto"><label class="col-form-label">Plaza que Dejo</label></div>
                                    <div class="col"><input class="form-control" type="text" value="<?php echo $_SESSION['plaza_dejada'] ?>" readonly></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center p-2">
                            <div class="col">
                                <div class="input-group">
                                    <span class="input-group-text">Notas</span>
                                    <textarea class="form-control" aria-label="Notas" readonly><?php 
                                    echo $_SESSION['notas'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </form>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>