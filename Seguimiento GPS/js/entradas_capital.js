tablaEntradas = $("#table_entradas_cap");
formulario = document.getElementById("ds_form");

function recipeFormatter(value, row, index) {
  return `<a class="link-dark" href="arrendamientos/php/scripts/recibo_pago.php?${$.param({
    idArrendamiento: row.idArrendamiento,
    idInfoPago: row.idInfoPago,
  })}" title="Volver a descargar archivo recibo">
            <i class="bi bi-filetype-pdf"></i>
          </a>`;
}

tablaEntradas.bootstrapTable({
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
	  fileName: 'Entradas de capital EstaquitasBajaRent', // Especifica el nombre de archivo deseado aquí
	  // Otras opciones de exportación específicas pueden ir aquí
	},
  columns: [
    [
      {
        title: `Movimientos`,
        valign: "middle",
        align: "center",
        colspan: 7,
      },
    ],
    [{
      field: "idSucursal",
      title: "Sucursal",
      align: "center",
      filterControl: 'select'
    },
      {
        field: "vehiculo",
        title: "Unidad",
        align: "center",
        footerFormatter: "Totales:",
      },
      {
        field: "fechaPago",
        title: "Fecha de pago",
        align: "center",
        footerFormatter: "-",
        sortable: "true",
      },
      {
        field: "montoSaldado",
        title: "Cantidad",
        align: "center",
        footerFormatter: "totalFormatter",
        sortable: "true"
      },
      {
        field: "motivoPago",
        title: "Motivo",
        align: "center",
        footerFormatter: "-",
      },
      {
        field: "metodoPago",
        title: "Medio",
        footerFormatter: "-",
        align: "center",
        filterControl: 'select'
      },
      {
        title: "Recibo",
        formatter: "recipeFormatter",
        align: "center",
        footerFormatter: "-",
      },
    ],
  ],
});

formulario.addEventListener("submit", function (e) {
  e.preventDefault();
  e.stopPropagation();
  let data = new FormData(formulario);
	let fechaInicio = data.get('f_inicio');
	let fechaFinal = data.get('f_final');
	let sucursalSelect = data.get('sucursalSelect'); // Asegúrate de que el nombre del campo coincide exactamente con el nombre en tu formulario HTML

  ajaxPost("arrendamientos/php/querys/entradas_capital.php", new FormData(ds_form))
    .then((r) => {
      if (isJsonString(r)) {
        formulario.classList.remove("row");
        document.getElementById("frm_container").classList.add("d-none");
        document.getElementById("tb_container").classList.remove("d-none");
        tablaEntradas.bootstrapTable("showLoading");
   
          tablaEntradas.bootstrapTable("refreshOptions", {
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
     //   console.log(`Movimientos del ${fechaInicio.slice(8)}/${fechaInicio.slice(5,7)}/${fechaInicio.slice(0, 4)} hasta ${fechaFinal.slice(8)}/${fechaFinal.slice(5, 7)}/${fechaFinal.slice(0, 4)} - ${sucursalNombre}`);
				document.querySelector(
					'#table_entradas_cap > thead > tr:nth-child(1) > th > div.th-inner'
				).innerHTML = `Movimientos del ${fechaInicio.slice(8)}/${fechaInicio.slice(5,7)}/${fechaInicio.slice(0, 4)} hasta ${fechaFinal.slice(8)}/${fechaFinal.slice(5, 7)}/${fechaFinal.slice(0, 4)} - ${sucursalNombre}`;
			
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
});

document.getElementById("btn_ec_back").addEventListener("click", function (e) {
  document.getElementById("tb_container").classList.add("d-none");
  formulario.classList.add("row");
  document.getElementById("frm_container").classList.remove("d-none");
});
