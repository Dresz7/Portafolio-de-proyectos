tablaSalidas = $('#table_salidas');

modalNSalida = new bootstrap.Modal('#registrarSalida', { keyboard: false });
formNSalida = document.getElementById('nSalida');
btnNSalida = document.getElementById('btnMscSubmit');

modalDatesSc = new bootstrap.Modal('#set_dates_sc', { keyboard: false });
formDatesSc = document.getElementById('frm_sd_sc');
btnDatesSc = document.getElementById('frm_sd_submit_sc');

tablaSalidas.bootstrapTable({
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
	  fileName: 'Salidas de capital EstaquitasBajaRent', // Especifica el nombre de archivo deseado aquí
	  // Otras opciones de exportación específicas pueden ir aquí
	},
	columns: [
		[
			{
				title: 'Salidas de capital de la semana',
				valign: 'middle',
				align: 'center',
				colspan: 5,
			},
		],
		[
			{
				field: 'tipoSalida',
				title: 'Medio de salida',
				valign: 'middle',
				align: 'center',
				footerFormatter: 'Totales:',
			},
			{
				field: 'montoSalida',
				title: 'Monto',
				valign: 'middle',
				align: 'center',
				footerFormatter: 'totalFormatter',
			},
			{
				field: 'fechaSalida',
				title: 'Fecha',
				valign: 'middle',
				align: 'center',
				footerFormatter: '-',
			},
			{
				field: 'idSucursal',
				title: 'Sucursal',
				valign: 'middle',
				align: 'center'
			},
			{
				field: 'motivoSalida',
				title: 'Motivo',
				valign: 'middle',
				align: 'center',
				footerFormatter: '-',
			},
		],
	],
});

ajaxGet('arrendamientos/php/querys/salidas_capital.php')
	.then((r) => {
		if (isJsonString(r)) {
			tablaSalidas.bootstrapTable('refreshOptions', { data: JSON.parse(r) });
		} else {
			Swal.fire({
				icon: 'warning',
				title: 'ups',
				html: `${r}`,
				buttonsStyling: false,
				allowOutsideClick: false,
				allowEscapeKey: false,
				allowEnterKey: false,
				customClass: {
					confirmButton: 'btn btn-warning',
				},
			});
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
				confirmButton: 'btn btn-danger',
			},
		});
	});

formNSalida.addEventListener('submit', function (e) {
	e.preventDefault();
	btnNSalida.disabled = true;

	ajaxPost('arrendamientos/php/scripts/insert_salida_capital.php', new FormData(nSalida))
		.then((r) => {
			if (parseInt(r, 10) === 0) {
				Swal.fire({
					icon: 'success',
					title: 'Salida de capital registrada',
					buttonsStyling: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customClass: {
						confirmButton: 'btn btn-success',
					},
				}).then((r) => {
					if (r.isConfirmed) {
						modalNSalida.hide();
						$('#contenedor-modulos').load(
							'arrendamientos/views/templates/salidas_capital.php'
						);
					}
				});
			} else {
				Swal.fire({
					icon: 'warning',
					title: 'ups',
					html: `${r}`,
					buttonsStyling: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customClass: {
						confirmButton: 'btn btn-warning',
					},
				});
				btnNSalida.disabled = false;
			}
		})
		.catch(function (r) {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				html: `${r}`,
				buttonsStyling: false,
				allowOutsideClick: false,
				allowEscapeKey: false,
				allowEnterKey: false,
				customClass: {
					confirmButton: 'btn btn-danger',
				},
			});
			btnNSalida.disabled = false;
		});
});

formDatesSc.addEventListener('submit', function (e) {
	e.preventDefault();
	btnDatesSc.disabled = true;
	let data = new FormData(frm_sd_sc),
		fechaInicio = data.get('f_inicio_sc'),
		fechaFinal = data.get('f_final_sc');

	ajaxPost('arrendamientos/php/querys/salidas_capital.php', data)
		.then((r) => {
			if (isJsonString(r)) {
				modalDatesSc.hide();
				tablaSalidas.bootstrapTable('refreshOptions', {
					data: JSON.parse(r),
				});
				document.getElementById(
					'table_salidas'
				).childNodes[1].childNodes[0].childNodes[0].childNodes[0].childNodes[0].innerHTML = `Salidas de ${fechaInicio.slice(
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
					icon: 'warning',
					title: 'ups',
					html: `${r}`,
					buttonsStyling: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customClass: {
						confirmButton: 'btn btn-warning',
					},
				});
			}
			formDatesSc.reset();
			btnDatesSc.disabled = false;
		})
		.catch(function (r) {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				html: `${r}`,
				buttonsStyling: false,
				allowOutsideClick: false,
				allowEscapeKey: false,
				allowEnterKey: false,
				customClass: {
					confirmButton: 'btn btn-danger',
				},
			});
			btnDatesSc.disabled = false;
		});
});
