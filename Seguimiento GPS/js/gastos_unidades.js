tablaGastos = $("#table_gastos");

modalNGastoU = new bootstrap.Modal("#registrarGasto", {
  keyboard: false,
});
formNGastoU = document.getElementById("nGasto");
btnNGastoU = document.getElementById("btnMSubmit");

modalDatesU = new bootstrap.Modal("#set_dates_gu", {
  keyboard: false,
})
formDatesU = document.getElementById("frm_ds_gu");
btnDatesU = document.getElementById("frm_ds_gu_submit");

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
	  fileName: 'Gastos de Unidades EstaquitasBajaRent', // Especifica el nombre de archivo deseado aquí
	  // Otras opciones de exportación específicas pueden ir aquí
	},
  columns: [
    [
      {
        title: `Gastos de la semana`,
        valign: "middle",
        align: "center",
        colspan: 5,
      },
    ],
    [ {
      field: "idSucursal",
      title: "Sucursal",
      valign: "middle",
      align: "center",
      sortable: true
    },
      {
        field: "unidad",
        title: "Unidad",
        valign: "middle",
        align: "center",
        footerFormatter: "Totales:",
      },
      {
        field: "fechaGasto",
        title: "Fecha del gasto",
        valign: "middle",
        align: "center",
        footerFormatter: "-",
        sortable: true
      },
      {
        field: "montoGasto",
        title: "Monto del gasto",
        valign: "middle",
        align: "center",
        footerFormatter: "totalFormatter",
        sortable: true
      },
      {
        field: "motivoGasto",
        title: "Motivo del gasto",
        valign: "middle",
        align: "center",
        footerFormatter: "-",
      },
    ],
  ],
});

ajaxGet("arrendamientos/php/querys/gastos_unidades.php")
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
        confirmButton: "btn btn-warning",
      },
    });
  });

ajaxGet("arrendamientos/php/querys/unidades_gastos.php")
  .then((r) => {
    document.getElementById("vehiculoGasto").innerHTML = r;
  })
  .catch(function (r) {
    console.log(`${r}`);
  });

formNGastoU.addEventListener("submit", function (e) {
  e.preventDefault();
  btnNGastoU.disabled = true;

  ajaxPost("arrendamientos/php/scripts/insert_gasto_unidad.php", new FormData(nGasto))
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
            modalNGastoU.hide();
            $("#contenedor-modulos").load(
              "arrendamientos/views/templates/gastos_unidades.php"
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
        btnNGastoU.disabled = false;
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
});

formDatesU.addEventListener("submit", function (e) {
  e.preventDefault();
  btnDatesU.disabled = true;
  let data = new FormData(frm_ds_gu);
  let fechaInicio = data.get("f_inicio_gu");
  let fechaFinal = data.get("f_final_gu");

  ajaxPost("arrendamientos/php/querys/gastos_unidades.php", data)
    .then((r) => {
      if (isJsonString(r)) {
        modalDatesU.hide();
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
      formDatesU.reset();
      btnDatesU.disabled = false;
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
      btnDatesU.disabled = false;
     })
 })