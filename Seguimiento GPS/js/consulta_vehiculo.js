tablaVehiculos = $("#table_vehiculos");

modalEdicion = new bootstrap.Modal(document.getElementById("editModal"));
btnCloseEdicion = document.getElementById("btn-close-modal");
modalVehiculoForm = document.getElementById("frm_edit_vehiculo");
modalVehiculoFormBtn = document.getElementById("fcmVehiculoSubmit");
vehiculoInputs = Array.prototype.slice.call(
  modalVehiculoForm.querySelectorAll("input, select")
);

modalPoliza = new bootstrap.Modal(document.getElementById("polizaModal"));
btnClosePoliza = document.getElementById("btn-close-poliza");
modalPolizaForm = document.getElementById("frm_poliza_vehiculo");
modalPolizaFormBtn = document.getElementById("fcmPolizaSubmit");
polizaInputs = Array.prototype.slice.call(
  modalPolizaForm.querySelectorAll("input, select")
);

window.operateEvents = {
  "click .editar": function (e, value, row, index) {
    let map = new Map(Object.entries(row));
    vehiculoInputs.forEach((e) => {
      if (e.attributes["objref"]) {
        e.value = map.get(`${e.attributes["objref"].value}`);
      }
    });
    vehiculoInputs[11].value = 0;
    vehiculoInputs[13].value = 2;
    vehiculoInputs[12].value = 1;
    if (vehiculoInputs[10].value == 0) {
      vehiculoInputs[11].disabled = false;
      vehiculoInputs[12].disabled = true;
      vehiculoInputs[13].disabled = false;

      vehiculoInputs[11].checked = true;
    } else if (vehiculoInputs[10].value == 2) {
      vehiculoInputs[11].disabled = false;
      vehiculoInputs[12].disabled = true;
      vehiculoInputs[13].disabled = false;

      vehiculoInputs[13].checked = true;
    } else {
      vehiculoInputs[11].disabled = true;
      vehiculoInputs[12].disabled = false;
      vehiculoInputs[13].disabled = true;

      vehiculoInputs[12].checked = true;
    }
    modalEdicion.show();
  },
  "click .npoliza": function (e, value, row, index) {
    polizaInputs[0].value = row.id;
  },
};

function formatterEdit(value, row, index) {
  return `<a class="editar link-dark" href="javascript:void(0)" title="Editar registro"
            data-bs-toggle="modal" data-bs-target="#editModal">
            <i class="bi bi-pencil-square"></i>
            </a>`;
}

function formatterNPoliza(value, row, index) {
  return `<a class="npoliza link-dark" href="javascript:void(0)" title="Nueva póliza de seguro"
            data-bs-toggle="modal" data-bs-target="#polizaModal">
            <i class="bi bi-shield-lock"></i>
            </a>`;
}

tablaVehiculos.bootstrapTable({
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
    fileName: 'Lista de vehiculos EstaquitasBajaRent', // Especifica el nombre de archivo deseado aquí
    // Otras opciones de exportación específicas pueden ir aquí
  },
  columns: [
    {
      field: "marca",
      title: "Marca",
    },
    {
      field: "linea",
      title: "Linea",
    },
    {
      field: "modelo",
      title: "Modelo",
    },
    {
      field: "serie",
      title: "Número de Serie",
    },
    {
      field: "placa",
      title: "Matricula",
    },
    {
      field: "color",
      title: "Color",
    },
    {
      field: "diasArrendados",
      title: "Días Arrendados",
      sortable: true
    },
    {
      field: "razonArrendamiento",
      title: "% Arrendamiento",
      sortable: true,
      formatter: "percentFormatter",
    },
    {
      title: "Editar",
      formatter: "formatterEdit",
      events: "operateEvents",
    },
    {
      title: "Póliza",
      formatter: "formatterNPoliza",
      events: "operateEvents",
    },
  ],
});

btnCloseEdicion.addEventListener("click", function (e) {
  modalEdicion.hide();
  vehiculoInputs.forEach((e) => {
    e.value = "";
  });
});

btnClosePoliza.addEventListener("click", function (e) {
  modalPoliza.hide();
  polizaInputs.forEach((e) => {
    e.value = "";
  });
});

tablaVehiculos.bootstrapTable("showLoading");
ajaxGet("arrendamientos/php/querys/vehiculos.php")
  .then((r) => {
    if (isJsonString(r)) {
      tablaVehiculos.bootstrapTable("refreshOptions", {
        data: JSON.parse(r),
      });
    } else
      console.warn(r)
  })
  .catch(function (r) {
    console.error(r)
  });
tablaVehiculos.bootstrapTable("hideLoading");

modalVehiculoForm.addEventListener("submit", (e) => {
  e.preventDefault();
  modalVehiculoFormBtn.disabled = true;
  ajaxPost("arrendamientos/php/scripts/update_vehiculo.php", new FormData(frm_edit_vehiculo))
    .then((r) => {
      if (parseInt(r, 10) === 0) {
        Swal.fire({
          icon: "success",
          title: "Registro actualizado",
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-success",
          },
        }).then((r) => {
          if (r.isConfirmed) {
            modalEdicion.hide();
            $("#contenedor-modulos").load(
              "arrendamientos/views/templates/consulta_vehiculo.html"
            );
          }
        });
      } else {
        swal.fire({
          icon: "warning",
          title: "Registro no actualizado",
          text: `${r}`,
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-warning",
          },
        });
        modalVehiculoFormBtn.disabled = false;
      }
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });
});

modalPolizaForm.addEventListener("submit", (e) => {
  e.preventDefault();
  modalPolizaFormBtn.disabled = true;
  ajaxPost("arrendamientos/php/scripts/insert_poliza.php", new FormData(frm_poliza_vehiculo))
    .then((r) => {
      if (parseInt(r, 10) == 0) {
        Swal.fire({
          icon: "success",
          title: "Póliza registrada",
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-success",
          },
        }).then((r) => {
          if (r.isConfirmed) {
            modalPoliza.hide();
            $("#contenedor-modulos").load(
              "arrendamientos/views/templates/consulta_vehiculo.html"
            );
          }
        });
      } else {
        swal.fire({
          icon: "warning",
          title: "Póliza no registrada",
          text: `${r}`,
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-warning",
          },
        });
        modalPolizaFormBtn.disabled = false;
      }
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });
});
