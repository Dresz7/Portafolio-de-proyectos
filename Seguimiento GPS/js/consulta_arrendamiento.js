

tablaArrendamiento = $("#table_arrendamiento");

modalAbono = new bootstrap.Modal(document.getElementById("abonarModal"));
btnCloseModal = document.getElementById("btn-close-modal");

modalForm = document.getElementById("frm_abonar_arrendamiento");
abonoAll = Array.prototype.slice.call(
  modalForm.getElementsByTagName("input"),
  0
);
selects = Array.prototype.slice.call(
  modalForm.getElementsByTagName("select"),
  0
);
if (selects.length != 0) {
  abonoAll = abonoAll.concat(selects);
}
modalFormBtn = document.getElementById("faArrendamientoSubmit");

$('#tabla_penalizaciones').bootstrapTable({
  locale: 'es-MX',
  filterControlVisible:true,
  silentSort: false,
  filterControl: true, // Activa el control de filtro
  pagination: true,
  pageSize: 10, // Define cuántas filas deseas mostrar por página
  pageList: [10, 25, 50, 100],
  columns: 
  [
    {
      field: 'deposito',
      title: 'Monto',
      sortable: true,
      footerFormatter: totalTextFormatter // Función para calcular el total de la columna Monto

    },
    {
      field: 'fechapenalizacion',
      title: 'Fecha',
      sortable: true,
      footerFormatter: countRowsFormatter
    } 
  ],
  showFooter: true, // Asegúrate de habilitar el pie de página

  
});
// Función para calcular el total de la columna Monto
function totalTextFormatter(data) {
  let total = 0;
  data.forEach(function (row) {
    total += +(row.deposito || 0); // Asegúrate de que los valores sean numéricos
  });
  return `Monto penalizado: ${total.toFixed(2)}`; // Retorna el total formateado como deseas mostrarlo
}

function countRowsFormatter(data) {
  // Simplemente cuenta el número de filas (elementos en data) y retorna ese conteo
  return `Dias penalizados: ${data.length}`;
}

tablaArrendamiento.bootstrapTable("showLoading");
ajaxGet("arrendamientos/php/querys/arrendamientos.php")
  .then((r) => {
    if (isJsonString(r)) {
      let data = JSON.parse(r);

      // Ordenamos los datos aquí basándonos en el estado de arrendamiento u otro criterio
      data.sort((a, b) => {
        // Suponiendo que quieres que ciertos estados aparezcan al final
        if (a.estadoArrendamiento === '2' && b.estadoArrendamiento !== '2') return 1;
        if (b.estadoArrendamiento === '2' && a.estadoArrendamiento !== '2') return -1;
        return 0; // mantiene el orden original para otros casos
      });

      // Ahora actualizas la tabla con los datos ordenados
      tablaArrendamiento.bootstrapTable("refreshOptions", {
        data: data,
      });
    } else {
      // Manejo de errores o respuesta no esperada
      Swal.fire({
        icon: "warning",
        title: "ups",
        html: `${r}`,
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        customClass: {
          confirmButton: "btn btn-warning",
        },
      });
    }
  })
  .catch(function (r) {
    alert(`error: ${r}`);
  });
tablaArrendamiento.bootstrapTable("hideLoading");

function contratoFormatter(value, row, index) {
  return `<a class="editar link-dark" href="arrendamientos/php/scripts/contrato.php?${$.param({
    id: row.id,
  })}" title="Archivo contrato">
            <i class="bi bi-filetype-pdf"></i>
            </a>`;
}

function depositoFormatter(value, row, index) {
  return `<a class="link-dark" href="arrendamientos/php/scripts/recibo_pago.php?${$.param({
    idArrendamiento: row.id,
  })}" title="Recibo deposito">
            <i class="bi bi-filetype-pdf"></i>
            </a>`;
}

function terminarArrendamiento(value, row, index) {
  if (row.monto == 0) {
    return `<a class="terminar link-dark" href="javascript:void(0)" title="Finalizar arrendamiento">
              <i class="bi bi-bookmark-check"></i>
            </a>`;
  } else {
    return `Denegado`;
  }
}


function accionesFormatter(value, row, index) {
  let cambiarSucursalIcon = `<a class="cambiarsucursal link-dark" href="javascript:void(0)" title="Cambiar de sucursal">
                              <i class="bi bi-arrow-repeat"></i>
                            </a>`;
  let eliminarIcon = `<a class="eliminar link-dark" href="javascript:void(0)" title="Eliminar arrendamiento">
                        <i class="bi bi-trash"></i>
                      </a>`;
  let editararrendamiento = `<a class="editar link-dark" href="javascript:void(0)" title="Editar arrendamiento">
  <i class="bi bi-pencil-square"></i>
                    </a>`;
                    let penalizaciones = row.estadoArrendamiento==2?`<a class="penalizaciones link-dark" href="javascript:void(0)" title="Ver penalizaciones">
                    <i class="bi bi-exclamation-triangle"></i>
                                      </a>`:``
                     let extenderIcon = row.estadoArrendamiento!=3?`<a class="extender link-dark" href="javascript:void(0)" title="Extender el arrendamiento">
                                      <i class="bi bi-clock"></i>
                                                        </a>`:``
  return `${cambiarSucursalIcon} ${penalizaciones} ${editararrendamiento} ${extenderIcon}  ${eliminarIcon}`;
}

function abonoArrendamiento(value, row, index) {
  if (row.monto == 0) {
    return `Pagado`;
  } else {
    return `<a class="abonar link-dark" href="javascript:void(0)" title="Abonar">
            <i class="bi bi-piggy-bank-fill"></i>
          </a>`;
  }
}

function rowStyle(row, index) {
  if (row.estadoArrendamiento == 2) {
    return {
      classes: "bg-warning",
    };
  }
  return {
    css: {
      color: "black",
    },
  };
}

btnCloseModal.addEventListener("click", function (e) {
  modalAbono.hide();
  abonoAll.forEach((e) => {
    e.value = "";
  });
});

modalForm.addEventListener("change", () => {
  modalFormBtn.disabled = false;
});

window.clickEvent = {
  "click .abonar": function (e, value, row, index) {
  

    var sucursalEnTabla = row.idSucursal; // Asumiendo que 'idSucursal' identifica si es 'SAN JOSÉ DEL CABO' o 'LA PAZ'
    var selectSucursal = document.getElementById('sucursalSelect');
    selectSucursal.innerHTML = ''; // Limpiar opciones existentes

    // Asumiendo que '1' corresponde a 'SAN JOSÉ DEL CABO' y '2' a 'LA PAZ'
    if(sucursalEnTabla === 'SAN JOSÉ DEL CABO') {
        selectSucursal.add(new Option('SAN JOSÉ DEL CABO', '1'));
    } else if(sucursalEnTabla === 'LA PAZ') {
        selectSucursal.add(new Option('LA PAZ', '2'));
    }
 
    abonoAll[0].value = row.id;
    abonoAll[1].value = row.monto;
    modalAbono.show();
 
},
  "click .terminar": function (e, value, row, index) {
    let postData = new FormData(),
      htmlText = `<p class="fw-bold">Cliente:</p>
                  <p>${row.nombreCompleto}</p>
                  <p class="fw-bold">Serie/VIN:</p>
                  <p>${row.serie}</p>
                  <p class="fw-bold">Fecha de vencimiento</p>
                  <p>${row.fechaVencimiento}</p>
                  <p class="fw-bold text-danger">El depósito de $5000(MXN)
                  debe ser regresado integro en efectivo si no se presenta ningún
                  desperfecto en la unidad</p>
                  `;

    postData.append("id", row.id);
    postData.append("vhid", row.vhid);

    Swal.fire({
      title: "Información del arrendamiento",
      html: htmlText,
      showDenyButton: true,
      denyButtonText: "Cancelar",
      confirmButtonText: "Finalizar",
      buttonsStyling: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      customClass: {
        confirmButton: "btn btn-primary m-2",
        denyButton: "btn btn-warning m-2",
      },
    }).then((r) => {
      if (r.isConfirmed) {
        ajaxPost("arrendamientos/php/scripts/finalizar_arrendamiento.php", postData)
          .then((r) => {
            if (parseInt(r, 10) === 0) {
              Swal.fire({
                icon: "info",
                title: "Arrendamiento finalizado",
                buttonsStyling: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then((r) => {
                if (r.isConfirmed) {
                  $("#contenedor-modulos").load(
                    "arrendamientos/views/templates/consulta_arrendamiento.php"
                  );
                }
              });
            } else {
              Swal.fire({
                icon: "warning",
                title: "ups",
                html: `${r}`,
                buttonsStyling: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                customClass: {
                  confirmButton: "btn btn-warning",
                },
              });
            }
          })
          .catch(function (r) {
            console.log(`Error: ${r}`);
          });
      }
    });
  }, "click .cambiarsucursal": function (e, value, row, index) {
    ajaxGet("arrendamientos/php/querys/user_name.php")
    .then((r) => {
      if (isJsonString(r)) {
        let userInfo = JSON.parse(r);
        if (userInfo.tipoUsuario == "ADMINISTRADOR") {
          Swal.fire({
            icon: "question",
            title: `¿Cambiar arrendamiento de ${row.idSucursal} a la sucursal de ${row.idSucursal=="SAN JOSÉ DEL CABO"?"LA PAZ":"SAN JOSÉ DEL CABO"}?`,
            html: `<div style="text-align: left;">Se cambiará de sucursal el arrendamiento con los siguientes datos:</br></br><b>Arrendatario: </b>${row.nombreCompleto} </br> <b>Vehículo: </b>${row.vhRef} </br> <b>Serie: </b>${row.serie} </br></br>  <b>Nota:</b> los registros de los anteriores abonos se mantendrán en la sucursal anterior.</div>`,
            buttonsStyling: false,
            allowOutsideClick: false,
            allowEnterKey: false,
            allowEscapeKey: false,
            showCancelButton: true,
            confirmButtonText: "Si, Cambiar",
            cancelButtonText: "Cancelar",
            customClass: {
              confirmButton: "btn btn-danger m-2",
              cancelButton: "btn btn-secondary m-2",
            },
          }).then((r) => {
            if (r.isConfirmed) {
              data = new FormData();
              data.append("idArrendamiento", row.id);
              var sucursaldecambio= row.idSucursal=="SAN JOSÉ DEL CABO"?2:1
              data.append("idSucursalCambio",sucursaldecambio);
              ajaxPost("arrendamientos/php/scripts/cambiarsucursal_arrendamiento.php", data)
                .then((r) => {
                  const resultado = JSON.parse(r);

                  if (resultado.respuesta == 0) {
                    Swal.fire({
                      icon: "success",
                      title: `Exito`,
                      html:`Arrendamiento de ${row.nombreCompleto} cambiado a ${row.idSucursal=="SAN JOSÉ DEL CABO"?"LA PAZ":"SAN JOSÉ DEL CABO"} satisfactoriamente`,
                      buttonsStyling: false,
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      customClass: {
                        confirmButton: "btn btn-primary",
                      },
                    }).then((r) => {
                      if (r.isConfirmed) {
                        $("#contenedor-modulos").load(
                          "arrendamientos/views/templates/consulta_arrendamiento.php"
                        );
                      }
                    });
                  } else {
                    Swal.fire({
                      icon: "warning",
                      title: "Arrendamiento no actualizado",
                      text: `${r}`,
                      buttonsStyling: false,
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      customClass: {
                        confirmButton: "btn btn-warning",
                      },
                    });
                  }
                })
                .catch(function (e) {
                  Swal.fire({
                    icon: "warning",
                    title: "ups",
                    text: `${e}`,
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    customClass: {
                      confirmButton: "btn btn-warning",
                    },
                  });
                });
            } else {
              Swal.fire({
                icon: "info",
                title: "No se realizó ningún cambio",
                showConfirmButton: false,
                timer: 1000,
              });
            }
          });
        } else {
          Swal.fire({
            icon: "warning",
            title: `Denegado`,
            text: `Acción no permitida`,
            buttonsStyling: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              confirmButton: "btn btn-warning",
            },
          });
        }
      } else {
        Swal.fire({
          icon: "warning",
          title: "ups",
          text: `${r}`,
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-warning",
          },
        });
      }
    })
    .catch(function (e) {
      Swal.fire({
        icon: "warning",
        title: "ups",
        text: `${e}`,
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        customClass: {
          confirmButton: "btn btn-warning",
        },
      });
    });
  }, "click .editar": function (e, value, row, index) {
    // Obtén los IDs de arrendamiento y vehículo
    var arrendamientoId = row.id;

    // Hacer una solicitud AJAX al servidor para obtener los datos
    $.ajax({
        url: 'arrendamientos/php/querys/arrendamiento_editar.php', // URL de tu script PHP que devuelve los datos
        type: 'GET',
        data: {
            'arrendamientoId': arrendamientoId,
        },
        success: function(response) {
            // Asumiendo que la respuesta es un objeto JSON con los datos
            var data = JSON.parse(response);

            // Aquí asumimos que tienes inputs en tu modal para cada uno de los datos, como nombre, modelo, etc.
            $('#curp_cliente_txt_edit').val(data.curp);
            $('#nombres_cliente_txt_edit').val(data.nombreCompleto);
            $('#serie_vehiculo_txt_edit').val(data.serie);
            $('#nombre_completo_veh').val(data.vhRef);
            $('#marca_vehiculo_txt_edit').val(data.marca_vehiculo_txt_edit);
            
            var fecha = data.expedicion; // "01-03-2024 17:36:00"
            var partes = fecha.split(" ");
            var fechaPartes = partes[0].split("-");
            var hora = partes[1].substring(0, 5); // Extrae solo la hora y minuto
            
            // Reorganizar las partes para crear un formato compatible con ISO 8601 pero sin convertir a UTC
            var fechaISO = `${fechaPartes[2]}-${fechaPartes[1]}-${fechaPartes[0]}T${hora}`;
            
            // Establecer el valor del input sin usar toISOString()
            $('#f_expedicion_datetime_edit').val(fechaISO);
            
            // Repite para 'vencimiento'
            var fecha2 = data.vencimiento; // "02-03-2024 17:36:00"
            var partes2 = fecha2.split(" ");
            var fechaPartes2 = partes2[0].split("-");
            var hora2 = partes2[1].substring(0, 5); // Extrae solo la hora y minuto
            
            var fechaISO2 = `${fechaPartes2[2]}-${fechaPartes2[1]}-${fechaPartes2[0]}T${hora2}`;
            
            // Establecer el valor del input
            $('#f_vencimiento_datetime_edit').val(fechaISO2);

            function convertirFecha(fechaStr) {
              var partesFecha = fechaStr.split(" ")[0].split("-");
              var partesHora = fechaStr.split(" ")[1].split(":");
              // Formato aceptado por Date: YYYY-MM-DDTHH:MM:SS
              return new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0], partesHora[0], partesHora[1], partesHora[2]);
            }
            
            var fechaInicioStr = fecha;
            var fechaFinStr = fecha2;
            
            var fechaInicio = convertirFecha(fechaInicioStr);
            var fechaFin = convertirFecha(fechaFinStr);
            
            var diferencia = fechaFin - fechaInicio;
            var dias = diferencia / (1000 * 60 * 60 * 24);
            $('#diasT').val(dias);
            $('#tarifa_txt_edit').val(data.tarifa);
            $('#monto_txt_edit').val(data.monto);
            $('#monto_txt_edit').val(data.monto);


            // Muestra el modal
            $('#modalEditarArrendamiento').modal('show');
        },
        error: function(xhr, status, error) {
            console.error("Error al obtener los datos: ", error);
        }
    });
},  "click .penalizaciones": function (e, value, row, index) {
  $.ajax({
    url: 'arrendamientos/php/querys/penalizaciones.php',
    type: 'GET',
    data: {
        'arrendamientoId': row.id,
    },
    success: function(response) {
      var penalizaciones = JSON.parse(response);
      $('#tabla_penalizaciones').bootstrapTable('load', penalizaciones);
      $('#modalPenalizaciones').modal('show');
    },
    error: function(xhr, status, error) {
      console.error("Error al obtener los datos de penalizaciones: ", error);
    }
  });
},"click .extender": function (e, value, row, index) {
  // Obtén los IDs de arrendamiento y vehículo
  var arrendamientoId = row.id;

  // Hacer una solicitud AJAX al servidor para obtener los datos
  $.ajax({
      url: 'arrendamientos/php/querys/arrendamiento_extender.php', // URL de tu script PHP que devuelve los datos
      type: 'GET',
      data: {
          'arrendamientoId': arrendamientoId,
      },
      success: function(response) {
          // Asumiendo que la respuesta es un objeto JSON con los datos
          var data = JSON.parse(response);

          // Aquí asumimos que tienes inputs en tu modal para cada uno de los datos, como nombre, modelo, etc.
          $('#curp_cliente_txt_extend').val(data.curp);
          $('#nombres_cliente_txt_extend').val(data.nombreCompleto);
          $('#serie_vehiculo_txt_extend').val(data.serie);
          $('#nombre_completo_veh_extend').val(data.vhRef);
          $('#marca_vehiculo_txt_extend').val(data.marca_vehiculo_txt_edit);
          
          var fecha = data.expedicion; // "01-03-2024 17:36:00"
          var partes = fecha.split(" ");
          var fechaPartes = partes[0].split("-");
          var hora = partes[1].substring(0, 5); // Extrae solo la hora y minuto
          
          // Reorganizar las partes para crear un formato compatible con ISO 8601 pero sin convertir a UTC
          var fechaISO = `${fechaPartes[2]}-${fechaPartes[1]}-${fechaPartes[0]}T${hora}`;
          
          // Establecer el valor del input sin usar toISOString()
          $('#f_expedicion_datetime_extend').val(fechaISO);
          
          // Repite para 'vencimiento'
          var fecha2 = data.vencimiento; // "02-03-2024 17:36:00"
          var partes2 = fecha2.split(" ");
          var fechaPartes2 = partes2[0].split("-");
          var hora2 = partes2[1].substring(0, 5); // Extrae solo la hora y minuto
          
          var fechaISO2 = `${fechaPartes2[2]}-${fechaPartes2[1]}-${fechaPartes2[0]}T${hora2}`;
          
          // Establecer el valor del input
          $('#f_vencimiento_datetime_extend').val(fechaISO2);

          function convertirFecha(fechaStr) {
            var partesFecha = fechaStr.split(" ")[0].split("-");
            var partesHora = fechaStr.split(" ")[1].split(":");
            // Formato aceptado por Date: YYYY-MM-DDTHH:MM:SS
            return new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0], partesHora[0], partesHora[1], partesHora[2]);
          }
          
          var fechaInicioStr = fecha;
          var fechaFinStr = fecha2;
          
          var fechaInicio = convertirFecha(fechaInicioStr);
          var fechaFin = convertirFecha(fechaFinStr);
          
          var diferencia = fechaFin - fechaInicio;
          var dias = diferencia / (1000 * 60 * 60 * 24);
          $('#diasT_extend').val(dias);
          $('#tarifa_txt_extend').val(data.tarifa);
          $('#monto_txt_extend').val(data.montoTotal);
          
          let tarifaPorDia = parseInt(data.tarifa, 10); // Asegúrate de que sea un número
          let numeroDeDias = parseInt(dias, 10); // Asegúrate de que sea un número
          let montoTotalDado = parseInt(data.montoTotal, 10); // Asegúrate de que sea un número
      

      
        // Calcular el monto inicial (sin IVA ni descuento)
let montoInicial = tarifaPorDia * numeroDeDias;

let descuentos = [0, 0.05, 0.1, 0.15, 0.2, 0.25, 0.3];
let ivaAplicado = false;
let descuentoAplicado = 0;

// Intentar encontrar el descuento y el IVA que coincida con el montoTotalDado
descuentos.forEach(descuento => {
  let montoConDescuento = Math.floor(montoInicial * (1 - descuento)); // Uso de Math.floor aquí
  let montoConIVA = Math.floor(montoConDescuento * 1.16); // Y aquí

  // Asegúrate de comparar contra el monto total dado ajustado también a la parte entera
  if (montoTotalDado === Math.floor(montoConIVA)) {
      ivaAplicado = true;
      descuentoAplicado = descuento;
  } else if (montoTotalDado === Math.floor(montoConDescuento)) {
      descuentoAplicado = descuento;
  }
});

// Marcar el radio del IVA
if (ivaAplicado) {
    $('#ivaY_extend').prop('checked', true);
} else {
    $('#ivaN_extend').prop('checked', true);
}
console.log(descuentoAplicado);
// Marcar el radio del descuento
$(`input[name="descuento_extend"][value="${descuentoAplicado.toFixed(2)}"]`).prop('checked', true);
          console.log(`IVA Aplicado: ${ivaAplicado}, Descuento Aplicado: ${descuentoAplicado * 100}%`);



          // Muestra el modal
          $('#modalExtenderArrendamiento').modal('show');
      },
      error: function(xhr, status, error) {
          console.error("Error al obtener los datos: ", error);
      }
  });
}, "click .eliminar": function (e, value, row, index) {
    ajaxGet("arrendamientos/php/querys/user_name.php")
      .then((r) => {
        if (isJsonString(r)) {
          let userInfo = JSON.parse(r);
          if (userInfo.tipoUsuario == "ADMINISTRADOR") {
            Swal.fire({
              icon: "question",
              title: `¿Eliminar arrendamiento de ${row.vhRef}?`,
              text: `Toda la información relacionada con este arrendamiento
                    será eliminada y no se podrá recuperar`,
              buttonsStyling: false,
              allowOutsideClick: false,
              allowEnterKey: false,
              allowEscapeKey: false,
              showCancelButton: true,
              confirmButtonText: "Eliminar",
              cancelButtonText: "Cancelar",
              customClass: {
                confirmButton: "btn btn-danger m-2",
                cancelButton: "btn btn-secondary m-2",
              },
            }).then((r) => {
              if (r.isConfirmed) {
                data = new FormData();
                data.append("idArrendamiento", row.id);
                data.append("idVehiculo", row.vhid);
                ajaxPost("arrendamientos/php/scripts/eliminar_arrendamiento.php", data)
                  .then((r) => {
                    if (parseInt(r, 10) === 0) {
                      Swal.fire({
                        icon: "info",
                        title: "Arrendamiento eliminado",
                        buttonsStyling: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        customClass: {
                          confirmButton: "btn btn-primary",
                        },
                      }).then((r) => {
                        if (r.isConfirmed) {
                          $("#contenedor-modulos").load(
                            "arrendamientos/views/templates/consulta_arrendamiento.php"
                          );
                        }
                      });
                    } else {
                      Swal.fire({
                        icon: "warning",
                        title: "Arrendamiento no eliminado",
                        text: `${r}`,
                        buttonsStyling: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        customClass: {
                          confirmButton: "btn btn-warning",
                        },
                      });
                    }
                  })
                  .catch(function (e) {
                    Swal.fire({
                      icon: "warning",
                      title: "ups",
                      text: `${e}`,
                      buttonsStyling: false,
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      customClass: {
                        confirmButton: "btn btn-warning",
                      },
                    });
                  });
              } else {
                Swal.fire({
                  icon: "info",
                  title: "No se realizó ningún cambio",
                  showConfirmButton: false,
                  timer: 1000,
                });
              }
            });
          } else {
            Swal.fire({
              icon: "warning",
              title: `Denegado`,
              text: `Acción no permitida`,
              buttonsStyling: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false,
              customClass: {
                confirmButton: "btn btn-warning",
              },
            });
          }
        } else {
          Swal.fire({
            icon: "warning",
            title: "ups",
            text: `${r}`,
            buttonsStyling: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              confirmButton: "btn btn-warning",
            },
          });
        }
      })
      .catch(function (e) {
        Swal.fire({
          icon: "warning",
          title: "ups",
          text: `${e}`,
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-warning",
          },
        });
      });
  },
};

var columns = [
  {
    field: "idSucursal",
    title: "Sucursal",
  },
  {
    field: "nombreCompleto",
    title: "Nombres",
  },
  {
    field: "vhRef",
    title: "Unidad",
  },
  {
    field: "serie",
    title: "Serie Vehículo",
  },
  {
    field: "fechaVencimiento",
    title: "Vencimiento",
  },
  {
    field: "monto",
    title: "Monto por cubrir",
  },
  {
    title: "Pagar",
    formatter: "abonoArrendamiento",
    events: "clickEvent",
  },
  {
    title: "Contrato",
    formatter: "contratoFormatter",
  },
  {
    title: "Deposito",
    formatter: "depositoFormatter",
  },
  {
    title: "Finalizar",
    formatter: "terminarArrendamiento",
    events: "clickEvent",
  }
];

// Aquí verificamos si idSucursalPHP es "0" y, de ser así, añadimos la columna de acciones
if (idSucursalPHP == "0") {
  columns.push({
    title: "Acciones",
    formatter: "accionesFormatter",
    events: "clickEvent",
  });
}

tablaArrendamiento.bootstrapTable({
  locale: 'es-MX',
  filterControlVisible:true,
  silentSort: false,
  filterControl: true, // Activa el control de filtro
  pagination: true,
  pageSize: 10, // Define cuántas filas deseas mostrar por página
  pageList: [10, 25, 50, 100],
  showExport: true, // Muestra el botón de exportar
  exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'], // Define los tipos de exportación disponibles
  exportDataType: 'all', // Puedes cambiar a 'basic' para exportar la página actual
  exportOptions: {
    fileName: 'Lista de arrendamientos EstaquitasBajaRent', // Especifica el nombre de archivo deseado aquí
    // Otras opciones de exportación específicas pueden ir aquí
  },
  columns: columns,
});


modalForm.addEventListener("submit", (e) => {
  e.preventDefault();
  modalFormBtn.disabled = true;
  montoPendiente = parseInt(abonoAll[1].value);
  montoPago = parseInt(e.srcElement[2].value);
  if (montoPendiente < montoPago) {
    Swal.fire({
      icon: "warning",
      title: "Ups",
      html: `<center> La cantidad a pagar no puede
                      ser mayor al monto pendiente </center>`,
      buttonsStyling: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      customClass: {
        confirmButton: "btn btn-warning",
      },
    });
    modalFormBtn.disabled = false;
  } else {
    ajaxPost("arrendamientos/php/scripts/pago_arrendamiento.php", new FormData(frm_abonar_arrendamiento))
      .then((r) => {
        if (isJsonString(r)) {
          let res_php = JSON.parse(r);
          if (parseInt(res_php.respuesta, 10) === 0) {
            Swal.fire({
              icon: "success",
              title: "Pago registrado",
              confirmButtonText: `<a class="link-light text-reset text-decoration-none" href="arrendamientos/php/scripts/recibo_pago.php?${$.param(
                {
                  idArrendamiento: res_php.idArrendamiento,
                  idInfoPago: res_php.idInfoPago,
                }
              )}"> OK </a>`,
              buttonsStyling: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false,
              customClass: {
                confirmButton: "btn btn-success",
              },
            }).then((result) => {
              if (result.isConfirmed) {
                modalAbono.hide();
                $("#contenedor-modulos").load(
                  "arrendamientos/views/templates/consulta_arrendamiento.php"
                );
              }
            });
          }
        } else {
          Swal.fire({
            icon: "warning",
            title: "Pago no registrado",
            html: `<center> ${r} </center>`,
            buttonsStyling: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              confirmButton: "btn btn-warning",
            },
          });
          modalFormBtn.disabled = false;
        }
      })
      .catch(function (r) {
        console.log(`Error: ${r}`);
      });
  }
});
