$(document).ready(function() {

    

tablaGastos = $("#table_gastos");

modalNGasto = new bootstrap.Modal("#registrarGasto", {
  keyboard: false,
});
formNGasto = document.getElementById("nGasto");
btnNGasto = document.getElementById("btnMSubmit");

modalDates = new bootstrap.Modal("#set_dates", {
  keyboard: false,
});
formDates = document.getElementById("frm_ds");
btnDates = document.getElementById("frm_ds_submit");

tablaGastos.bootstrapTable({
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
	  fileName: 'Gastos Generales EstaquitasBajaRent', // Especifica el nombre de archivo deseado aquí
	  // Otras opciones de exportación específicas pueden ir aquí
	},
  columns: [
    [
      {
        title: `Gastos de la semana`,
        valign: "middle",
        align: "center",
        colspan: 4,
      },
    ],
    [{
      field: "idSucursal",
      title: "Sucursal",
      valign: "middle",
      align: "center",
      filterControl: 'select',
      sortable: true
    },
      {
        field: "fechaGasto",
        title: "Fecha",
        valign: "middle",
        align: "center",
        footerFormatter: "Totales:",
        sortable: true,
      },
      {
        field: "montoGasto",
        title: "Monto",
        valign: "middle",
        align: "center",
        footerFormatter: "totalFormatter",
        sortable: true
      },
      {
        field: "motivoGasto",
        title: "Motivo",
        valign: "middle",
        align: "center",
        footerFormatter: "-",
      },
    ],
  ]
});

ajaxGet("arrendamientos/php/querys/gastos_generales.php")
  .then((r) => {
    if (isJsonString(r)) {
      tablaGastos.bootstrapTable("refreshOptions", {
        data: JSON.parse(r),
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
    Swal.fire({
      icon: "error",
      title: "Algo Salió Mal",
      html: `${r}`,
      buttonsStyling: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      customClass: {
        confirmButton: "btn btn-danger",
      },
    });
  });

formNGasto.addEventListener("submit", function (e) {
  e.preventDefault();
  btnNGasto.disabled = true;

  ajaxPost("arrendamientos/php/scripts/insert_gasto_general.php", new FormData(nGasto))
    .then((r) => {
      if (parseInt(r, 10) === 0) {
        Swal.fire({
          icon: "success",
          title: "Gasto registrado",
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-success",
          },
        }).then((r) => {
          if (r.isConfirmed) {
            modalNGasto.hide();
            $("#contenedor-modulos").load(
              "arrendamientos/views/templates/gastos_generales.php"
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
        btnNGasto.disabled = false;
      }
    })
    .catch(function (r) {
      Swal.fire({
        icon: "error",
        title: "Error",
        html: `${r}`,
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        customClass: {
          confirmButton: "btn btn-danger",
        },
      });
    });
});

formDates.addEventListener("submit", function (e) {
  e.preventDefault();
  btnDates.disabled = true;
  let data = new FormData(frm_ds),
    fechaInicio = data.get("f_inicio"),
    fechaFinal = data.get("f_final");

  ajaxPost("arrendamientos/php/querys/gastos_generales.php", data)
    .then((r) => {
      if (isJsonString(r)) {
        modalDates.hide();
        tablaGastos.bootstrapTable("refreshOptions", {
          data: JSON.parse(r),
        });
        document.getElementById(
          "table_gastos"
        ).childNodes[1].childNodes[0].childNodes[0].childNodes[0].innerHTML = `Gastos de ${fechaInicio.slice(
          8
        )}/${fechaInicio.slice(5, 7)}/${fechaInicio.slice(
          0,
          4
        )} hasta ${fechaFinal.slice(8)}/${fechaFinal.slice(
          5,
          7
        )}/${fechaFinal.slice(0, 4)}`;
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
      formDates.reset();
      btnDates.disabled = false;
    })
    .catch(function (r) {
      Swal.fire({
        icon: "error",
        title: "Error",
        html: `${r}`,
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        customClass: {
          confirmButton: "btn btn-danger",
        },
      });
      btnDates.disabled = false;
    });
});

})