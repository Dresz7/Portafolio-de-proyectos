

$(document).ready(function() {

    
    $('#table_reporte').bootstrapTable({
        formatNoMatches: function () {
            return 'No se encontraron registros'; // Cambia el mensaje por el que desees en español
        }
    });

    $('#consultaForm').submit(function(event) {
        event.preventDefault();

        var formData = $(this).serialize();
     
        $.ajax({
            type: 'POST',
            url: 'arrendamientos/pruebasReportes.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                
                $('#table_reporte').bootstrapTable('destroy');

                function cargarDatosEnTabla(datos) {
                    var table = $('#table_reporte');

                    var columns = [
                        { field: 'deviceName', title: 'Nombre del dispositivo' },
                        { field: 'distance', title: 'Distancia', formatter: formatDistance },
                        { field: 'spentFuel', title: 'Combustible consumido', formatter: formatFuel }
                    ];

                    table.bootstrapTable({
                        columns: columns,
                        data: datos,
                        formatNoMatches: function() {
                            return 'No se encontraron registros';
                        }
                    });
                }

                function formatDistance(value) {
                    return value + ' Km';
                }

                function formatFuel(value) {
                    return value + ' L';
                }

                cargarDatosEnTabla(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert('Error en la solicitud: ' + error);
            }
        });
    });

    // Función para comprobar la selección de dispositivos o grupos
    function checkSelection() {
        var dispositivos = $('#dispositivos').val();
        var grupos = $('#grupos').val();

        // Verifica si al menos un dispositivo o grupo está seleccionado y hay fechas seleccionadas
        if ((dispositivos && dispositivos.length > 0) || (grupos && grupos.length > 0)) {
            $('#mostrarBtn').prop('disabled', false); // Habilita el botón si hay selección
        } else {
            $('#mostrarBtn').prop('disabled', true); // Deshabilita el botón si no hay selección
        }
    }

    // Llama a la función al cambiar los valores de los selects o los campos de fecha
    $('#dispositivos, #grupos').on('change', function() {
        checkSelection(); // Comprueba la selección al cambio
    });

    document.getElementById("exportar").addEventListener("click", () => {
        const fromDate = document.getElementById("from").value;
        const toDate = document.getElementById("to").value;

        const formattedFromDate = new Date(fromDate).toLocaleDateString('es-ES');
        const formattedToDate = new Date(toDate).toLocaleDateString('es-ES');

        const fileName = `Reporte_de_${formattedFromDate}_hasta_${formattedToDate}`;

      $("#table_reporte").tableExport({
        type: "pdf",
        fileName: fileName,
        jspdf: {
          orientation: "L",
          format: "legal",
          margins: { left: 10, right: 10, top: 20, bottom: 20 },
          autotable: {
            theme: "striped",
            styles: {
              fontSize: "12",
              tableWidth: "auto",
              cellPadding: 1,
              rowHeight: 14,
              overflow: "linebreak",
            },
          },
        },
      });
    });


    document.getElementById("exportarExcel").addEventListener("click", () => {
        const fromDate = document.getElementById("from").value;
        const toDate = document.getElementById("to").value;

        const formattedFromDate = new Date(fromDate).toLocaleDateString('es-ES');
        const formattedToDate = new Date(toDate).toLocaleDateString('es-ES');

        const fileName = `Reporte_de_${formattedFromDate}_hasta_${formattedToDate}`;

        $("#table_reporte").tableExport({
            type: "excel",
            fileName: fileName,
        });
    });

    // Inicializa el select como un elemento Select2
    $('#dispositivos').select2({
        placeholder: "Seleccione el/los dispositivo/s",
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

    // Inicializa el select como un elemento Select2
    $('#grupos').select2({
        placeholder: "Seleccione el/los grupo/s",
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
    
});