$(document).ready(function () {

    $('#MissPass').on('click', function () {
        Swal.fire(
          '¿Has olvidó tu contraseña?',
          'Consulta con un administrador para solucionar tu problema',
          'question'
        )
    });

    //Inicio sesion
    $('#form_login').on('submit', function(event){
        event.preventDefault();
        // Swal.fire('Iniciaste sesion');
        
        $.post("php/iniciar_sesion.php",$("#form_login").serialize(),function(datos){
            
                if (datos==3) {
                    var url = "php/filtro.php"; 
                    $(location).attr('href',url);           }
                else{
                    if (datos==2) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Atencion',
                            text: 'Usuario incorrecto!',
                            //footer: '<a href="">Why do I have this issue?</a>'
                        })
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Atencion',
                            text: 'Password incorrecto!',
                            //footer: '<a href="">Why do I have this issue?</a>'
                        })
                        // Swal.fire(datos);
                    }
                }
            });

    });

});