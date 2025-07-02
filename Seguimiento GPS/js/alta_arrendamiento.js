document.getElementById("id_vehiculo_txt").value = VehiculoArrendar.id;
document.getElementById("serie_vehiculo_txt").value = VehiculoArrendar.serie;
document.getElementById("marca_vehiculo_txt").value = VehiculoArrendar.marca;
document.getElementById("linea_vehiculo_txt").value = VehiculoArrendar.linea;
document.getElementById("modelo_vehiculo_txt").value = VehiculoArrendar.modelo;
document.getElementById("tarifa_txt").value = VehiculoArrendar.tarifa;

document
  .querySelectorAll(
    "#serie_vehiculo_txt, #marca_vehiculo_txt ,#linea_vehiculo_txt, #modelo_vehiculo_txt, #tarifa_txt"
  )
  .forEach((e) => {
    e.classList.add("border-success");
  });

submitForm = document.getElementById("formulario_arrendamiento");
btnSubmitForm = document.getElementById("fArrendamientoSubmit");

inputsArrendamiento = Array.prototype.slice.call(
  submitForm.getElementsByTagName("input, select"),
  0
);

document
  .getElementById("curp_cliente_txt")
  .addEventListener("keyup", function () {
    this.value = this.value.toUpperCase();
  });

document
  .getElementById("curp_cliente_adicional_txt")
  .addEventListener("keyup", function () {
    this.value = this.value.toUpperCase();
  });

document.getElementById("iva").addEventListener("change", () => {
  document.getElementById("desc0").checked = true;
  let monto = document.getElementById("monto_txt"),
    monto_base = document.getElementById("ivaN"),
    monto_imp = document.getElementById("ivaY");
  if (monto_imp.checked == true) {
    monto.value = isNaN(monto_imp.value)
      ? `Rango de fechas no válido`
      : Math.round(monto_imp.value);
  } else {
    monto.value = isNaN(monto_base.value)
      ? `Rango de fechas no válido`
      : Math.round(monto_base.value);
  }
});

document.getElementById("descuentos").addEventListener("change", (e) => {
  let monto = document.getElementById("monto_txt"),
    iva = document.getElementById("ivaY"),
    noIva = document.getElementById("ivaN"),
    montoSeleccionado = iva.checked ? iva.value : noIva.value;
  if (document.getElementById("desc0") == e.target) {
    monto.value = iva.checked ? Math.round(iva.value) : Math.round(noIva.value);
  } else {
    monto.value = isNaN(montoSeleccionado * e.target.value)
      ? `Rango de fechas no válido`
      : Math.round(montoSeleccionado - montoSeleccionado * e.target.value);
  }
});

function curpChange() {
  let datosCliente = [],
    postData = new FormData();
  datosCliente[0] = document.getElementsByClassName("cliente1");
  datosCliente[1] = document.getElementsByClassName("cliente2");
  datosCliente.forEach((e) => {
    e[0].addEventListener("change", function () {
      if (e[0].value.length == 18) {
        postData.append("curp", e[0].value);
        ajaxPost("arrendamientos/php/querys/cliente.php", postData)
          .then((r) => {
            if (isJsonString(r)) {
              resp = JSON.parse(r);
              e[1].value = resp[0].id;
              e[2].value = resp[0].nombreCompleto;
              e[2].classList.remove("border-warning", "border-danger");
              e[2].classList.add("border-success");
            } else {
              e[1].value = 0;
              e[2].value = r;
              e[2].classList.remove("border-warning", "border-success");
              e[2].classList.add("border-danger");
            }
          })
          .catch(function (r) {
            console.log(`Error: ${r}`);
          });
      } else {
        e[1].value = 0;
        e[2].value = `La clave curp consta de 18 caracteres`;
        e[2].classList.remove("border-danger", "border-success");
        e[2].classList.add("border-warning");
      }
    });
  });
}

function fechasArrendamiento() {
  let fecha = [],
    date = [],
    diasTotales = document.getElementById("diasT"),
    montoTotal = document.getElementById("monto_txt"),
    tarifa = document.getElementById("tarifa_txt"),
    tarifaFija = Number.parseInt(tarifa.value, 10);
  fecha[0] = document.getElementById("f_expedicion_datetime");
  fecha[1] = document.getElementById("f_vencimiento_datetime");
  date[0] = "";
  date[1] = "";

  for (let i = 0; i < fecha.length; i++) {
    fecha[i].addEventListener("change", function () {
      try {
        let isoString = new Date(fecha[i].value).toISOString();
        date[i] = new Date(isoString.slice(0, -1));
      } catch (e) {
        let isoString = "a";
        date[i] = new Date(isoString.slice(0, -1));
      }
      if (date[0] != "" && date[1] != "") {
        let diff = Math.abs(date[1] - date[0]);
        let diffDays = Math.ceil(diff / (1000 * 60 * 60 * 24));
        if (isNaN(diffDays)) {
          diasTotales.classList.add("border-danger");
          diasTotales.value = `Rango de fechas no válido`;
          tarifa.classList.add("border-danger");
          tarifa.value = `Rango de fechas no válido`;
          montoTotal.classList.add("border-danger");
          montoTotal.value = `Rango de fechas no válido`;
        } else {
          diasTotales.classList.remove("border-danger");
          diasTotales.classList.remove("border-warning");
          diasTotales.classList.add("border-success");
          diasTotales.value = `${diffDays} días`;

          tarifa.classList.remove("border-danger");
          tarifa.classList.add("border-success");
          if (diffDays >= 0 && diffDays < 7) {
            tarifa.value = Number.parseInt(tarifaFija * 1, 10);
          } else if (diffDays > 6 && diffDays < 30) {
            tarifa.value = Number.parseInt(tarifaFija * 0.9, 10);
          } else {
            tarifa.value = Number.parseInt(tarifaFija * 0.8);
          }

          montoTotal.classList.remove("border-danger");
          montoTotal.classList.add("border-success");
          montoTotal.value = Number.parseInt(tarifa.value * diffDays, 10);
          document.getElementById("ivaN").value = Math.round(
              Number.parseInt(montoTotal.value, 10)
            );
          document.getElementById("ivaY").value = Math.round(
              Number.parseInt(montoTotal.value * 1.16, 10)
            );
          document.getElementById("ivaN").checked = true;
          document.getElementById("desc0").checked = true;
        }
      } else {
        diasTotales.classList.remove("border-success");
        diasTotales.classList.add("border-warning");
        diasTotales.value = "Rango de fechas necesario";
      }
    });
  }
}

curpChange();
fechasArrendamiento();

(function () {
  "use strict";
  //? Obtener todos los formularios a los que queremos aplicar estilos de validación de Bootstrap personalizados
  let forms = document.querySelectorAll(".needs-validation");
  //? Bucle sobre ellos y evitar el envío
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (e) {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        } else {
          var selectElement = document.getElementById("sucursalSelect");
var selectedIndex = selectElement.selectedIndex; // Obtiene el índice del <option> seleccionado
var selectedText = selectElement.options[selectedIndex].text; // Obtiene el texto del <option> seleccionado
          e.preventDefault();
          btnSubmitForm.disabled = true;
          const regex = /T/gi;

          let choferAdTxt = () => {
            if (document.getElementById("idClienteAdH").value == 0) {
              return `<p class="fw-bold text-danger">Importante: Para cualquier arrendamiento
          es obligatorio un depósito de $5000(MXN) en efectivo</p>`;
            } else {
              return `<p class="fw-bold">Chófer adicional:</p>
          <p>${
            document.getElementById("nombres_cliente_adicional_txt").value
          }</p>
          <p class="fw-bold text-danger">Importante: Para cualquier arrendamiento
          es obligatorio un depósito de $5000(MXN) en efectivo</p>`;
            }
          };

          let dts_str = `${
              document.getElementById("f_expedicion_datetime").value
            }, ${document.getElementById("f_vencimiento_datetime").value}`,
            htmlText = `<p class="fw-bold">Cliente:</p>
          <p>${document.getElementById("nombres_cliente_txt").value}</p>
          <p class="fw-bold">Vehículo:</p>
          <p>${VehiculoArrendar.marca} ${VehiculoArrendar.linea} ${
              VehiculoArrendar.modelo
            }</p>
          <p class="fw-bold">Expedición / Vencimiento:</p>
          <p>${dts_str.replaceAll(regex, " ")}</p>
          <p class="fw-bold">Monto a cubrir</p>
          <p>$ ${document.getElementById("monto_txt").value}</p>
          <p class="fw-bold">Kilometraje:</p>
          <p>${document.getElementById("odometro").value} km</p>
          <p class="fw-bold">Sucursal:</p>
          <p>${selectedText}</p>
          ${choferAdTxt()}`;

          Swal.fire({
            title: "Información por registrar",
            html: htmlText,
            showDenyButton: true,
            denyButtonText: "Cancelar",
            confirmButtonText: "Registrar",
            buttonsStyling: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              confirmButton: "btn btn-success m-2",
              denyButton: "btn btn-warning m-2",
            },
          }).then((r) => {
            if (r.isConfirmed) {
              ajaxPost(
                "arrendamientos/php/scripts/insert_arrendamiento.php",
                new FormData(formulario_arrendamiento)
              )
                .then((r) => {
                  let res_php = isJsonString(r) ? JSON.parse(r) : r;
                  if (res_php.respuesta == 0) {
                    inputsArrendamiento.forEach((e) => {
                      e.value = "";
                    });
                    Swal.fire({
                      icon: "success",
                      title: "Arrendamiento registrado",
                      buttonsStyling: false,
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      confirmButtonText: `<a class="link-light text-reset text-decoration-none" href="arrendamientos/php/scripts/recibo_pago.php?${$.param(
                        {
                          idArrendamiento: res_php.idArrendamiento,
                        }
                      )}"> OK </a>`,
                      customClass: {
                        confirmButton: "btn btn-success",
                      },
                    }).then((r) => {
                      if (r.isConfirmed) {
                        $("#contenedor-modulos").load(
                          "arrendamientos/views/templates/home.html"
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
                    btnSubmitForm.disabled = false;
                  }
                })
                .catch(function (r) {
                  alert(`error: ${r}`);
                });
            } else {
              btnSubmitForm.disabled = false;
            }
          });
        }
        form.classList.add("was-validated");
      },
      false
    );
  });
})();
