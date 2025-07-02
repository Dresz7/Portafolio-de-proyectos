(tablaReporteOperativo = $("#table_informe_operativo")),
  (date = new Date()),
  (dayNumber = date.getDay()),
  (day = date.getDate() >= 10 ? `${date.getDate()}` : `0${date.getDate()}`),
  (hora = date.getHours() - 1),
  (hour = hora >= 10 ? `${hora}` : `0${hora}`),
  (min =
    date.getMinutes() >= 10 ? `${date.getMinutes()}` : `0${date.getMinutes()}`),
  (month =
    date.getMonth() + 1 >= 10
      ? `${date.getMonth() + 1}`
      : `0${date.getMonth() + 1}`),
  (strdate = `${
    days[dayNumber]
  } ${day}/${month}/${date.getFullYear()}, ${hour}:${min}`);

modalIoDate = new bootstrap.Modal("#define_fecha", {
  keyboard: false
});
formIoDate = document.getElementById("frm_d_io");
btnIoDate = document.getElementById("frm_d_io_submit");

tablaReporteOperativo.bootstrapTable("showLoading");
ajaxGet("arrendamientos/php/querys/informe_operativo.php")
  .then((r) => {
    if (isJsonString(r)) {
      tablaReporteOperativo.bootstrapTable("refreshOptions", {
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
tablaReporteOperativo.bootstrapTable("hideLoading");

tablaReporteOperativo.bootstrapTable({
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
        colspan: 10,
      },
    ],
    [
      {
        title: "Unidad",
        field: "unidad",
        footerFormatter: "Totales:",
        valign: "middle",
        align: "center",
      },
      {
        title: "Precio",
        field: "tarifa",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
      },
      {
        title: "Estado de unidad",
        field: "disponibilidad",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
      },
      {
        title: "Estatus",
        field: "estadoArrendamiento",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
      },
      {
        title: "Fecha de renta",
        field: "fechaExpedicion",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
      },
      {
        title: "Fecha de entrega",
        field: "fechaEntrega",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
      },
      {
        title: "Días de renta",
        field: "diasTotales",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
      },
      {
        title: "Días transcurridos",
        field: "diasTranscurridos",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
      },
      {
        title: "Días restantes",
        field: "diasRestantes",
        footerFormatter: "-",
        valign: "middle",
        align: "center",
        sortable: "true"
      },
      {
        title: "Ingreso diario",
        field: "montoDiario",
        footerFormatter: "totalFormatter",
        valign: "middle",
        align: "center",
      }
    ],
  ],
});

document.getElementById("exportar").addEventListener("click", () => {
  $("#table_informe_operativo").tableExport({
    type: "pdf",
    fileName: `InfOp_${strdate}`,
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
  $("#table_informe_operativo").tableExport({
    type: "excel",
    fileName: `InfOp_${strdate}`,
  });
});

formIoDate.addEventListener("submit", function (e) {
  e.preventDefault();
  btnIoDate.disabled = true;
  let data = new FormData(frm_d_io),
    fecha = data.get("f_informe_o");

  ajaxPost("arrendamientos/php/querys/informe_operativo.php", data)
    .then((r) => {
      if (isJsonString(r)) {
        modalIoDate.hide();
        tablaReporteOperativo
          .bootstrapTable("refreshOptions", {
            data: JSON.parse(r)
          });
        document.getElementById(
          "table_informe_operativo"
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
      formIoDate.reset();
      btnIoDate.disabled = false;
    }).catch(function (r) {
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
     }
     )
})