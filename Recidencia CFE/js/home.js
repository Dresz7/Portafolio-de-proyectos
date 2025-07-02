$(document).ready(function () {
    
    $('#form_publicar').on('submit',function (event){
        event.preventDefault();
        // Swal.fire('publicar');
        var form = document.querySelector('form');
        var fd = new FormData( form );
        $.ajax({
        url: "../php/links.php",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function (datos) {
          if (datos==1) {
                // Swal.fire('Publicación agregada');
                location.reload();  
            }else{
                Swal.fire({
                            icon: 'error',
                            title: 'Atencion',
                            text: 'Ha ocurrido un error!',
                            //footer: '<a href="">Why do I have this issue?</a>'
                        })
            }
        }
      });
    });

});