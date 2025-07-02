<?php  
// session_start(); 
// if (empty($_SESSION['id'])) {
//     header('location: ../index.php');
// }
// if ($_SESSION['rol']!='admin') {
//     session_destroy();
//     header('location: ../index.php');
// }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Menú administrador</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../bootstrap/bootstrap5.min.css">
    <link rel="stylesheet" href="../css/styleMenuG.css">
    <link rel="stylesheet" href="../css/styleMenuA.css">
    <!-- JS -->
    <script type="text/javascript" src="../fontawesome/solid.js"></script>
    <script defer src="../fontawesome/fontawesome.js"></script>
    <script type="text/javascript" src="../jquery/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="../jquery/popper.min.js"></script>
    <script type="text/javascript" src="../bootstrap/bootstrap5.min.js"></script>
    <script type="text/javascript" src="../bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/submenus.js"></script>
    

</head>
<body>
    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>CFE</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Menú administrador</p>
                <li id="MinutasM">
                    <a href="#MinutasSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Minutas</a>
                    <ul class="collapse list-unstyled" id="MinutasSubmenu">
                        <li>
                            <a href="#" id="suministrobasico">Suministro Básico</a>
                        </li>
                        <li>
                            <a href="#" id="distribucion">Distribución</a>
                        </li>
                        <li>
                            <a href="#" id="transmision">Transmisión</a>
                        </li>
                        <li>
                            <a href="#" id="generacion">Generación</a>
                        </li>
                    </ul>
                </li>

                <li id="HerramientasM">
                    <a href="#HerramientasSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Herramientas</a>
                    <ul class="collapse list-unstyled" id="HerramientasSubmenu">
                        <li>
                            <a href="#" id="logs">Logs</a>
                        </li>
                        <li>
                            <a href="#" id="respaldos">Backup</a>
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

                    <div class="navbar-btn " id="nommenu"></div>

                    <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" id="Home" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="menuprincial.php">Regresar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <div class="contenido-general" id="contenido">
                
            </div>
        </div>
    </div>

</body>
</html>