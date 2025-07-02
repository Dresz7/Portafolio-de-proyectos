$(document).ready(function(){

    $('#exportar').on('click',function (event){
        // Swal.fire('exportar');
        var url = "../php/exportar.php"; 
        $(location).attr('href',url);
        //window.open('../php/exportar.php', '_blank'); 
    });

    $('#form_importar').on('submit',function (event){
        event.preventDefault();
        //Swal.fire('importar');
        var form = document.querySelector('form');
        var fd = new FormData( form );
        $.ajax({
        url: "../php/importar.php",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function (datos) {
          if (datos==1) {
                Swal.fire('Base de datos restaurada con éxito');  
            }else{
                Swal.fire(datos); 
            }
        }
      });
    });

});