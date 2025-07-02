$('#addForm').submit(async function(event) {
    event.preventDefault();

    const loadingToast = Swal.fire({
        title: 'Cargando',
        html: `<center> ESPERE... </center>`,
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        timer: 750,
        didOpen: async () => {
            Swal.showLoading();

            try {
                // Obtener datos del formulario utilizando serialize
                const formData = $('#addForm').serialize();

                // Realizar la solicitud
                const response = await enviarSolicitud(formData);

                setTimeout(() => {
                    loadingToast.close();

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Dispositivo agregado exitosamente.',
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: "btn btn-success",
                            popup: "animate__animated animate__fadeInDownBig animate__fast",
                        },
                        showClass: {
                            popup: "animate__animated animate__fadeInDownBig animate__fast",
                        },
                        hideClass: {
                            popup: "animate__animated animate__fadeOutUpBig animate__fast",
                        },
                        timer: 1500,
                        timerProgressBar: true
                    });
                }, 450);
            } catch (error) {
                console.error('Error en la solicitud:', error);
                loadingToast.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al agregar el dispositivo: ' + error
                });
            }
        }
    });
});

async function enviarSolicitud(formData) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'POST',
            url: 'arrendamientos/php/scripts/adddevice.php',
            data: formData,
            success: function(response) {
                var responseData = JSON.parse(response);
                if (responseData.status === "success") {
                    resolve(responseData.data);
                } else {
                    reject(responseData.message);
                }
            },
            error: function(xhr, status, error) {
                // También puedes manejar errores HTTP aquí
                var errorMessage = error;
                try {
                    // Intentar analizar la respuesta JSON del servidor para obtener un mensaje de error
                    var responseJSON = JSON.parse(xhr.responseText);
                    errorMessage = responseJSON.message;
                } catch (e) {
                    // No se pudo analizar la respuesta JSON, usar el mensaje de error predeterminado
                }
                reject(errorMessage);
            }
        });
    });
}

// Inicializa el select como un elemento Select2
$('#group').select2({
    placeholder: "Selecciona un grupo",
    language: {
      errorLoading: function() {
            return "No se pudieron cargar los resultados"
        },
        inputTooLong: function(e) {
            var n = e.input.length - e.maximum
              , r = "Por favor, elimine " + n + " car";
            return r += 1 == n ? "ácter" : "acteres"
        },
        inputTooShort: function(e) {
            var n = e.minimum - e.input.length
              , r = "Por favor, introduzca " + n + " car";
            return r += 1 == n ? "ácter" : "acteres"
        },
        loadingMore: function() {
            return "Cargando más resultados…"
        },
        maximumSelected: function(e) {
            var n = "Sólo puede seleccionar " + e.maximum + " elemento";
            return 1 != e.maximum && (n += "s"),
            n
        },
        noResults: function() {
            return "No se encontraron resultados"
        },
        searching: function() {
            return "Buscando…"
        },
        removeAllItems: function() {
            return "Eliminar todos los elementos"
        }
    }
});