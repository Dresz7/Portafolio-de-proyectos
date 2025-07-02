tablaBalance = $('#table_balance_general');
formulario = document.getElementById('bg_form');

tablaBalance.bootstrapTable({
	columns: [
		[
			{
				title: 'Flujo de efectivo',
				valign: 'middle',
				align: 'center',
				colspan: 3,
			},
		],
		[
			{
				field: 'concepto',
				title: 'Concepto',
				align: 'center',
				footerFormatter: 'Saldo final:',
			},
			{
				field: 'cantidad',
				title: 'Cantidad',
				align: 'center',
				formatter: 'priceFormatter',
				footerFormatter: 'totalFormatter',
			},
			{
				field: 'sumatoria',
				title: 'Sumatoria',
				align: 'center',
				formatter: 'priceFormatter',
				footerFormatter: 'totalFormatter',
			},
		],
	],
});

document.getElementById('exportar').addEventListener('click', () => {
	let data = new FormData(formulario);
	let fechaInicio = data.get('fb_inicio');
	let fechaFinal = data.get('fb_final');
	let sucursalSelect = data.get('sucursalSelect');
	// Convertir fechas a formato dd-mm-yyyy
	fechaInicio = fechaInicio.split('-').reverse().join('-'); // Cambia '-' por el separador que prefieras, como '/'
	fechaFinal = fechaFinal.split('-').reverse().join('-');   // Cambia '-' por el separador que prefieras, como '/'

	let sucursalNombre = sucursalSelect === '1' ? "SAN_JOSÉ_DEL_CABO" : sucursalSelect === '2' ? "LA_PAZ" : "TODAS_LAS_SUCURSALES";
	let fileName = `BalanceGen_${fechaInicio}_${fechaFinal}_${sucursalNombre}`;
	// Para PDF
let encabezado = `${fechaInicio} al ${fechaFinal} - Sucursal ${sucursalNombre}`;

	$('#table_balance_general').tableExport({
		type: 'pdf',
		fileName: fileName,
		jspdf: {
			orientation: 'L',
			format: 'legal',
			margins: { left: 10, right: 10, top: 20, bottom: 20 },
			autotable: {
				theme: 'striped',
				styles: {
					fontSize: '12',
					tableWidth: 'auto',
					cellPadding: 1,
					rowHeight: 14,
					overflow: 'linebreak',
				}
			},
		},
	});
});

document.getElementById('exportarExcel').addEventListener('click', () => {
	let data = new FormData(formulario);
	let fechaInicio = data.get('fb_inicio');
	let fechaFinal = data.get('fb_final');
	let sucursalSelect = data.get('sucursalSelect');
	// Convertir fechas a formato dd-mm-yyyy
	fechaInicio = fechaInicio.split('-').reverse().join('-'); // Cambia '-' por el separador que prefieras, como '/'
	fechaFinal = fechaFinal.split('-').reverse().join('-');   // Cambia '-' por el separador que prefieras, como '/'
	let sucursalNombre = sucursalSelect === '1' ? "SAN_JOSÉ_DEL_CABO" : sucursalSelect === '2' ? "LA_PAZ" : "TODAS_LAS_SUCURSALES";

	let fileName = `BalanceGen_${fechaInicio}_${fechaFinal}_${sucursalNombre}`;
	

	$('#table_balance_general').tableExport({
		type: 'excel',
		fileName: fileName,
	});
});

document.getElementById('btnBackBg').addEventListener('click', function () {
	document.getElementById('frm_container').classList.remove('d-none');
	document.getElementById('table_container').classList.add('d-none');
});

formulario.addEventListener('submit', function (e) {
	e.preventDefault();
	e.stopPropagation();
	let data = new FormData(formulario);
	let fechaInicio = data.get('fb_inicio');
	let fechaFinal = data.get('fb_final');
	let sucursalSelect = data.get('sucursalSelect'); // Asegúrate de que el nombre del campo coincide exactamente con el nombre en tu formulario HTML


	ajaxPost('arrendamientos/php/querys/balance_general.php', data)
		.then((r) => {
			if (isJsonString(r)) {
				document.getElementById('frm_container').classList.add('d-none');
				document.getElementById('table_container').classList.remove('d-none');
				tablaBalance.bootstrapTable('showLoading');
				tablaBalance.bootstrapTable('refreshOptions', {
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
					'#table_balance_general > thead > tr:nth-child(1) > th > div.th-inner'
				).innerHTML = `Flujo de efectivo del ${fechaInicio.slice(8)}/${fechaInicio.slice(
					5,
					7
				)}/${fechaInicio.slice(0, 4)} hasta ${fechaFinal.slice(
					8
				)}/${fechaFinal.slice(5, 7)}/${fechaFinal.slice(0, 4)} - ${sucursalNombre}`;
			}
		})
		.catch(function (r) {
			Swal.fire({
				icon: 'error',
				title: 'Algo Salió Mal',
				html: `${r}`,
				buttonsStyling: false,
				allowOutsideClick: false,
				allowEscapeKey: false,
				allowEnterKey: false,
				customClass: {
					confirmButton: 'btn btn-warning',
				},
			});
		});
});
