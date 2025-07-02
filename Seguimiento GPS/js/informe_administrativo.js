(tablaReporteAdministrativo = $("#table_informe_administrativo")),
  (date = new Date()),
  (dayNumber = date.getDay()),
  (day = date.getDate() >= 10 ? `${date.getDate()}` : `0${date.getDate()}`),
  (hora = date.getHours() - 1),
  (hour =
    hora >= 10
      ? `${hora}`
      : `0${hora}`),
  (min =
    date.getMinutes() >= 10
      ? `${date.getMinutes()}` :
      `0${date.getMinutes()}`),
  (month =
    date.getMonth() + 1 >= 10
      ? `${date.getMonth() + 1}`
      : `0${date.getMonth() + 1}`),
  (strdate = `${
    days[dayNumber]
  } ${day}/${month}/${date.getFullYear()}, ${hour}:${min}`);

modalIaDate = new bootstrap.Modal("#set_date", {
  keyboard: false
})
formIaDate = document.getElementById("frm_d_ia");
btnIaDate = document.getElementById("frm_d_ia_submit");

tablaReporteAdministrativo.bootstrapTable("showLoading");
ajaxGet("arrendamientos/php/querys/informe_administrativo.php")
  .then((r) => {
    if (isJsonString(r)) {
      tablaReporteAdministrativo.bootstrapTable("refreshOptions", {
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
tablaReporteAdministrativo.bootstrapTable("hideLoading");

tablaReporteAdministrativo.bootstrapTable({
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
        title: `${strdate}`,
        valign: "middle",
        align: "center",
        colspan: 11,
      },
    ],
    [
      {
        title: "Unidad",
        field: "unidad",
        footerFormatter: "Totales:",
        valign: "middle",
        align: "center",
        sortable: "true"
      },
      {
        title: "Monto total",
        field: "montoTotal",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
      {
        title: "Garantías",
        field: "deposito",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
      {
        title: "Fecha de pago",
        field: "fechaEfPago",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
        sortable: "true"
      },
      {
        title: "Transferencias",
        field: "transferencias",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
      {
        title: "Efectivo",
        field: "efectivo",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
      {
        title: "Por cobrar",
        field: "xcobrar",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
      {
        title: "Por devengar",
        field: "xpagar",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
      {
        title: "Ingreso diario",
        field: "montoDiario",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
      {
        title: "Mantenimiento",
        field: "mantenimiento",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
      {
        title: "Ingreso neto diario",
        field: "ingresoNeto",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      },
    ],
  ],
});

document.getElementById("exportar").addEventListener("click", () => {
  $("#table_informe_administrativo").tableExport({
    type: "pdf",
    fileName: `InfAdm_${strdate}`,
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
  $("#table_informe_administrativo").tableExport({
    type: "excel",
    fileName: `InfAdm_${strdate}`,
  });
});

formIaDate.addEventListener("submit", function (e) {
  e.preventDefault();
  btnIaDate.disabled = true;
  let data = new FormData(frm_d_ia),
    fecha = data.get("f_informe_a");

  ajaxPost("arrendamientos/php/querys/informe_administrativo.php", data)
    .then((r) => {
      if (isJsonString(r)) {
        modalIaDate.hide();
        tablaReporteAdministrativo
          .bootstrapTable("refreshOptions", {
          data: JSON.parse(r),
        });
        document.getElementById(
          "table_informe_administrativo"
        ).childNodes[1].childNodes[0].childNodes[0].childNodes[0].innerHTML = `${fecha.slice(
          8,
          10
        )}/${fecha.slice(5, 7)}/${fecha.slice(0, 4)}, ${fecha.slice(11)}`;
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
      formIaDate.reset();
      btnIaDate.disabled = false;
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
 })