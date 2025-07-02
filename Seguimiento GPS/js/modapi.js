$(document).ready(function() {
    // Inicializa el select como un elemento Select2
    $('#group').select2({
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

    $('#devices').select2({
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


    // Evento de cambio para el select de dispositivos
    $('#devices').change(function() {
        // Obtener el valor seleccionado
        var selectedDeviceId = $(this).val();

        // Limpiar todos los campos si es 'ninguno'
        if (selectedDeviceId == 'ninguno') {
            // Limpiar todos los campos
            $('#name, #imei, #model, #phone, #contact, #deviceId').val('');

            // Seleccionar automáticamente el grupo 'ninguno'
            $('#group').val('ninguno').trigger('change');

            // Desactiva el boton borrar
            $('#btnBorrar').prop('disabled', true);

            return;
        }

        // Realizar una solicitud AJAX para obtener los datos del dispositivo seleccionado
        $.ajax({
            url: 'arrendamientos/php/scripts/obtener_datos.php', // Ruta al archivo PHP que maneja la solicitud
            method: 'GET',
            dataType: 'json',
            success: function(datosd) {
                // Encontrar el dispositivo seleccionado en los datos recibidos
                var selectedDevice = datosd.find(device => device.id == selectedDeviceId);

                // Actualizar el valor del campo oculto (deviceId)
                $('#deviceId').val(selectedDeviceId);
                $('#name').val(selectedDevice.name);
                $('#imei').val(selectedDevice.uniqueId);
                $('#model').val(selectedDevice.model);
                $('#phone').val(selectedDevice.phone);
                $('#contact').val(selectedDevice.contact);
                $('#group').val(selectedDevice.groupId).trigger('change');

                // Activa el boton borrar
                $('#btnBorrar').prop('disabled', selectedDeviceId === 'ninguno');
            },
            error: function() {
                // Manejar errores si es necesario
                console.error('Error en la solicitud AJAX');
            }
        });
    });


    $('#modForm').submit(async function(event) {
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
                    const formData = $('#modForm').serialize();

                    // Realizar la solicitud
                    const response = await enviarSolicitud(formData);

                    setTimeout(() => {
                        loadingToast.close();

                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Dispositivo actualizado exitosamente.',
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
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al actualizar el dispositivo.'
                    });
                }
            }
        });
    });

    async function enviarSolicitud(formData) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                type: 'POST',
                url: 'arrendamientos/php/scripts/moddevice.php',
                data: formData,
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    // Evento de clic para el botón "Borrar Dispositivo"
    $('#modForm').on('click', '#btnBorrar', function() {
        var deviceNameToDelete = $('#name').val();
        // Mostrar un cuadro de diálogo de confirmación
        Swal.fire({
            title: `¿Estás seguro de que quieres borrar el dispositivo "${deviceNameToDelete}"?`,
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, borrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
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
                            const formData = $('#modForm').serialize();

                            // Realizar la solicitud
                            const response = await enviarDel(formData);

                            setTimeout(() => {
                                loadingToast.close();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: 'Dispositivo borrado exitosamente.',
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
                                $('#modForm').trigger('reset');
                                const deviceIdToDelete = $('#deviceId').val();
                                $('#devices option[value="' + deviceIdToDelete + '"]').remove();
                                $('#btnBorrar').prop('disabled', true);
                            }, 450);
                        } catch (error) {
                            console.error('Error en la solicitud:', error);
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un error al borrar el dispositivo.'
                            });
                        }
                    }
                });
            }
        });
    });

    async function enviarDel(formData) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                type: 'POST',
                url: 'arrendamientos/php/scripts/deldevice.php',
                data: formData,
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    $('#group').change(function() {
        var selectedGroup = document.getElementById('group').value;
        var groupError = document.getElementById('group-error');

        if (selectedGroup === 'ninguno') {
            groupError.textContent = 'Selecciona un grupo válido.';
            document.getElementById('group').setCustomValidity('No válido');
        } else {
            groupError.textContent = '';
            document.getElementById('group').setCustomValidity('');
        }
    });

});