tablaIngresosD = $("#table_ingresos_diarios");
form_id = document.getElementById("did_form");

tablaIngresosD.bootstrapTable({
  locale: 'es-MX',
  filterControlVisible:true,
  silentSort: false,
  filterControl: true, // Activa el control de filtro
  pagination: true,
  pageSize: 10, // Define cuántas filas deseas mostrar por página
  pageList: [10, 25, 50, 100],
  columns: [
    [
      {
        title: "Ingresos diarios",
        valign: "middle",
        align: "center",
        colspan: 5,
      },
    ],
    [
      {
        field: "fecha",
        title: "Fecha",
        align: "center",
        sortable: "true",
        footerFormatter: "Totales:",
      },
      {
        field: "ingresoDiario",
        title: "Ingreso Diario",
        align: "center",
        footerFormatter: "totalFormatter",
      },
      {
        field: "gastoUnidades",
        title: "Gastos de unidades",
        align: "center",
        footerFormatter: "totalFormatter",
      },
      {
        field: "gastoGeneral",
        title: "Gastos Generales",
        align: "center",
        footerFormatter: "totalFormatter"
      },
      {
        field: "ingresoNeto",
        title: "Ingreso",
        align: "center",
        footerFormatter: "totalFormatter",
      },
    ],
  ],
});

form_id.addEventListener("submit", function (e) {
  e.preventDefault();
  e.stopPropagation();
  let data = new FormData(did_form),
    fechaInicio = data.get("fecha_inicio"),
    fechaFinal = data.get("fecha_final");
    let sucursalSelect = data.get('sucursalSelect'); // Asegúrate de que el nombre del campo coincide exactamente con el nombre en tu formulario HTML


    fechaInicio.slice(11, 16) == fechaFinal.slice(11, 16) &&
    fechaInicio != fechaFinal

    ajaxPost("arrendamientos/php/querys/ingresos_diarios.php", data)
      .then((r) => {
        /* if (isJsonString(r)) {
          console.log(JSON.parse(r));
        } else {
          console.log(r);
        } */
        if (isJsonString(r)) {
          form_id.classList.remove("row");
          document.getElementById("frm_container").classList.add("d-none");
          document.getElementById("tb_container").classList.remove("d-none");
          tablaIngresosD.bootstrapTable("showLoading");
          setTimeout(() => {
            tablaIngresosD.bootstrapTable("refreshOptions", {
              data: JSON.parse(r),
            });
            let sucursalNombre = ''; // Variable para almacenar el nombre de la sucursal
            if (sucursalSelect == '1') {
              sucursalNombre = "Sucursal - SAN JOSÉ DEL CABO";
            } else if (sucursalSelect == '2') {
              sucursalNombre = "Sucursal - LA PAZ";
            } else if (sucursalSelect == '0') {
              sucursalNombre = "Todas las sucursales";
            }
            document.getElementById(
              "table_ingresos_diarios"
            ).childNodes[1].childNodes[0].childNodes[0].childNodes[0].innerHTML = `Ingresos de ${fechaInicio.slice(
              8,
              10
            )}/${fechaInicio.slice(5, 7)}/${fechaInicio.slice(
              0,
              4
            )} ${fechaInicio.slice(11, 16)} hasta ${fechaFinal.slice(
              8,
              10
            )}/${fechaFinal.slice(5, 7)}/${fechaFinal.slice(
              0,
              4
            )} ${fechaFinal.slice(11, 16)}  - ${sucursalNombre}`;
          }, 1000);
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
});

document.getElementById("exportar").addEventListener("click", () => {
  $("#table_ingresos_diarios").tableExport({
    type: "pdf",
    fileName: `ingresosDiarios`,
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
  $("#table_ingresos_diarios").tableExport({
    type: "excel",
    fileName: `IngresosDiarios`,
  });
});

document.getElementById("set_dates_btn").addEventListener("click", () => {
  document.getElementById(
    "table_ingresos_diarios"
  ).childNodes[1].childNodes[0].childNodes[0].childNodes[0].innerHTML = "...";
  form_id.classList.add("row");
  document.getElementById("tb_container").classList.add("d-none");
  document.getElementById("frm_container").classList.remove("d-none");
});
