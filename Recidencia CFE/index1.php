<?php  
session_start(); 
if (empty($_SESSION['id'])) {}else{
    header('location: php/filtro.php');
}
?>

<!DOCTYPE HTML>
<httml lang= "es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSS -->
		<link rel="stylesheet" type="text/css" href="bootstrap/bootstrap5.min.css">
        <!-- JS -->
        <script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>
        <script type="text/javascript" src="bootstrap/bootstrap5.min.js"></script>
        <script type="text/javascript" src="sweetalert2/sweetalert2.all.min.js"></script>
        <script type="text/javascript" src="js/login.js"></script>
		<title>Formularios en HTML 5</title>
    </head>
    <body> 	
    	<section class="vh-100" style="background-color: #1C6758;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center ">
                    <div class="col col-xl-10">
                        <div class="card" style="border-radius: 1rem;">
                            <div class="row g-0">
                                <div class="col-md-6 col-lg-5 d-none d-md-block">
                                    <img src="imagenes/login form.jpg" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                                </div>
                                <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                    <div class="card-body p-4 p-lg-5 text-black">

                                        <form method="POST" id="form_login">
                                            <div class="d-flex align-items-center mb-3 pb-1">
                                                <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                                <span class="h1 fw-bold mb-0"><img src="imagenes/logocfe.png" alt="Logo CFE" class="img-fluid col-md-3"></span>
                                            </div>
                                            <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Iniciar Sesión en su Cuenta</h5>
                                            <div class="form-outline mb-4">
                                                <input type="email" name="usuario" id="usuario" class="form-control form-control-lg" required="true"/>
                                                <label class="" for="usuario">Correo Electrónico</label>
                                            </div>
                                            <div class="form-outline mb-4">
                                                <input type="password" name="password" id="password" class="form-control form-control-lg" required="true" minlength="8"/>
                                                <label class="form-label" for="password">Contraseña</label>
                                            </div>
                                            <div class="pt-1 mb-4">
                                                <input class="btn btn-dark btn-lg btn-block" type="submit" name="" value="Iniciar Sesión">
                                            </div>
                                            <a class="small text-muted" href="#" id="MissPass">¿Se te olvidó tu contraseña?</a>
                                            <!-- <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="#!" style="color: #393f81;">Register here</a></p> -->
                                            <!-- <p>
                                                <a href="#!" class="small text-muted">Terms of use.</a>
                                                <a href="#!" class="small text-muted">Privacy policy</a>
                                            </p> -->
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>