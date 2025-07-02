$(document).ready(function () {

    //animacion menu lateral
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('active');
    });

    //carga del menu home por defecto
    $('#contenido').load('submenus/home.php');


    //Menu superior

    //Home
    $('#Home').on('click',function (event){
        $(".active").removeClass("active");
        $("#Home").addClass("active")

        $('#contenido').empty();
        $('#contenido').load('submenus/home.php');

        $('#nommenu').empty();
        });

    
    //Menus lateral

    //Secretaría de trabajo

    //Vacaciones
    $('#vacaciones').on('click',function (event){
        $(".active").removeClass("active");
        $("#TrabajoM").addClass("active")

        //alert('Diste click en vacaciones');
        $('#contenido').empty();
        $('#contenido').load('submenus/vacaciones.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/trabajo.html');
        });

    //Jubulaciones
    $('#jubilaciones').on('click',function (event){
        $(".active").removeClass("active");
        $("#TrabajoM").addClass("active")

        //alert('Diste click en jubilaciones');
        $('#contenido').empty();
        $('#contenido').load('submenus/jubilaciones.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/trabajo.html');
        });

    //Guardias
    $('#guardiasemerg').on('click',function (event){
        $(".active").removeClass("active");
        $("#TrabajoM").addClass("active")

        //alert('Diste click en guardias para emergencias');
        $('#contenido').empty();
        $('#contenido').load('submenus/guardiasemerg.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/trabajo.html');
        });  

    //Capacitaciones
    $('#capacitaciones').on('click',function (event){
        $(".active").removeClass("active");
        $("#TrabajoM").addClass("active")

        //alert('Diste click en capacitaciones');
        $('#contenido').empty();
        $('#contenido').load('submenus/capacitaciones.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/trabajo.html');
        });

        //Roles     
    $('#rolestemporales').on('click',function (event){
        $(".active").removeClass("active");
        $("#TrabajoM").addClass("active")

        //alert('Diste click en roles temporales');
        $('#contenido').empty();
        $('#contenido').load('submenus/rolestemporales.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/trabajo.html');
        });             


    //Secretaría de finanzas    

    //Estados Financieros
    $('#estadosfinancieros').on('click',function (event){
        $(".active").removeClass("active");
        $("#FinanzasM").addClass("active")

        //alert('Diste click en estados financieros');
        $('#contenido').empty();
        $('#contenido').load('submenus/estadosfinancieros.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/finanzas.html');
    });

    //Informes
    $('#informesmensuales').on('click',function (event){
        $(".active").removeClass("active");
        $("#FinanzasM").addClass("active")

        //  alert('Diste click en informes mensuales');
        $('#contenido').empty();
        $('#contenido').load('submenus/informesmensuales.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/finanzas.html');
    });


    //Secretaría de previsión social

    //Citas
    $('#citasmedicas').on('click',function (event){
        $(".active").removeClass("active");
        $("#PrevSocialM").addClass("active")

        //alert('Diste click en citas médicas');
        $('#contenido').empty();
        $('#contenido').load('submenus/citasmedicas.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/prevensionsocial.html');
    });

    //Expedientes
    $('#expedientes').on('click',function (event){
        $(".active").removeClass("active");
        $("#PrevSocialM").addClass("active")

        //alert('Diste click en expedientes');
        $('#contenido').empty();
        $('#contenido').load('submenus/expedientes.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/prevensionsocial.html');

    });

    //Traslados
    $('#traslados').on('click',function (event){
        $(".active").removeClass("active");
        $("#PrevSocialM").addClass("active")
        
        $('#contenido').empty();
        $('#contenido').load('submenus/traslados.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/prevensionsocial.html');

    });

    //Menu admin

    //Minutas

    //Suministro basico
    $('#suministrobasico').on('click',function (event){
        $(".active").removeClass("active");
        $("#MinutasM").addClass("active")
        
        $('#contenido').empty();
        $('#contenido').load('submenus/suministrobasico.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/minutas.html');
    });

    //Distribución
    $('#distribucion').on('click',function (event){
        $(".active").removeClass("active");
        $("#MinutasM").addClass("active")
        
        $('#contenido').empty();
        $('#contenido').load('submenus/distribucion.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/minutas.html');
    });

    //Transmisión
    $('#transmision').on('click',function (event){
        $(".active").removeClass("active");
        $("#MinutasM").addClass("active")
        
        $('#contenido').empty();
        $('#contenido').load('submenus/transmision.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/minutas.html');
    });

    //Generación
    $('#generacion').on('click',function (event){
        $(".active").removeClass("active");
        $("#MinutasM").addClass("active")
        
        $('#contenido').empty();
        $('#contenido').load('submenus/generacion.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/minutas.html');
    });

    //Herramientas

    //Logs
    $('#logs').on('click',function (event){
        $(".active").removeClass("active");
        $("#HerramientasM").addClass("active")
        
        $('#contenido').empty();
        $('#contenido').load('submenus/logs.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/herramientas.html');
    });

    //Respaldos
    $('#respaldos').on('click',function (event){
        $(".active").removeClass("active");
        $("#HerramientasM").addClass("active")
        
        $('#contenido').empty();
        $('#contenido').load('submenus/respaldos.php');

        $('#nommenu').empty();
        $('#nommenu').load('../nombres/herramientas.html');
    });
});