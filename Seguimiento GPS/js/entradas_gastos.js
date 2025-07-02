tablaEntradasGastos = $("#table_entradas_gastos");
formulario = document.getElementById("eg_form");

tablaEntradasGastos.bootstrapTable({
  columns: [
    [
      {
        title: "Utilidades",
        valign: "middle",
        align: "center",
        colspan: 3,
      },
    ],
    [
      {
        field: "concepto",
        title: "Concepto",
        align: "center",
        footerFormatter: "Utilidad:",
      },
      {
        field: "cantidad",
        title: "Cantidad",
        align: "center",
        formatter: "priceFormatter",
        footerFormatter: "totalFormatter",
      },
      {
        field: "sumatoria",
        title: "Sumatoria",
        align: "center",
        formatter: "priceFormatter",
        footerFormatter: "totalFormatter",
      },
    ],
  ],
});

document.getElementById("btnBackEg").addEventListener("click", function () {
  document.getElementById("frm_eg_container").classList.remove("d-none");
  document.getElementById("table_eg_container").classList.add("d-none");
});

formulario.addEventListener("submit", function (e) {
  e.preventDefault();
  e.stopPropagation();
  let data = new FormData(formulario);
  let fechaInicio = data.get("feg_inicio");
  let fechaFinal = data.get("feg_final");
  let sucursalSelect = data.get('sucursalSelect'); // Asegúrate de que el nombre del campo coincide exactamente con el nombre en tu formulario HTML

  ajaxPost("arrendamientos/php/querys/entradas_gastos.php", data)
    .then((r) => {
      if (isJsonString(r)) {
        document.getElementById("frm_eg_container").classList.add("d-none");
        document
          .getElementById("table_eg_container")
          .classList.remove("d-none");
        tablaEntradasGastos.bootstrapTable("showLoading");
        tablaEntradasGastos.bootstrapTable("refreshOptions", {
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
        document.querySelector(
          "#table_entradas_gastos > thead > tr:nth-child(1) > th > div.th-inner"
        ).innerHTML = `Utilidades del ${fechaInicio.slice(
          8
        )}/${fechaInicio.slice(5, 7)}/${fechaInicio.slice(
          0,
          4
        )} hasta ${fechaFinal.slice(8)}/${fechaFinal.slice(
          5,
          7
        )}/${fechaFinal.slice(0, 4)} - ${sucursalNombre}`;
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
});
