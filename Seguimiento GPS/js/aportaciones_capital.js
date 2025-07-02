tablaAportaciones = $('#table_aportaciones');

modalNAportacion = new bootstrap.Modal('#registrarAportacion', {
	keyboard: false,
});
formNAportacion = document.getElementById('nAportacion');
btnNAportacion = document.getElementById('btnMacSubmit');

modalDatesAc = new bootstrap.Modal('#set_dates_ac', { keyboard: false });
formDatesAc = document.getElementById('frm_sd_ac');
btnDatesAc = document.getElementById('frm_sd_submit_ac');

tablaAportaciones.bootstrapTable({
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
	  fileName: 'Aportaciones de capital EstaquitasBajaRent', // Especifica el nombre de archivo deseado aquí
	  // Otras opciones de exportación específicas pueden ir aquí
	},
	columns: [
		[
			{
				title: 'Aportaciones de capital de la semana',
				valign: 'middle',
				align: 'center',
				colspan: 4,
			},
		],
		[
			{
				field: 'idSucursal',
				title: 'Sucursal',
				valign: 'center',
				filterControl: 'select',
				sortable:true,
				align: 'center'
			},
			{
				field: 'fechaAportacion',
				title: 'Fecha',
				valign: 'center',
				align: 'center',
				sortable:true,
				footerFormatter: '-',
			},
			{
				field: 'tipoAportacion',
				title: 'Medio de aportación',
				valign: 'middle',
				align: 'center',
				sortable:true,
				filterControl: 'select',
				footerFormatter: 'Totales',
			},
			{
				field: 'montoAportacion',
				title: 'Monto',
				valign: 'center',
				align: 'center',
				sortable:true,
				footerFormatter: 'totalFormatter',
			}
		],
	],
});

ajaxGet('arrendamientos/php/querys/aportaciones_capital.php')
	.then((r) => {
		if (isJsonString(r)) {
			tablaAportaciones.bootstrapTable('refreshOptions', {
				data: JSON.parse(r),
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

formNAportacion.addEventListener('submit', function (e) {
	e.preventDefault();
	btnNAportacion.disabled = true;

	ajaxPost(
		'arrendamientos/php/scripts/insert_aportacion_capital.php',
		new FormData(nAportacion)
	)
		.then((r) => {
			if (parseInt(r, 10) === 0) {
				Swal.fire({
					icon: 'success',
					title: 'Aportación de capital registrada',
					buttonsStyling: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customClass: {
						confirmButton: 'btn btn-success',
					},
				}).then((r) => {
					if (r.isConfirmed) {
						modalNAportacion.hide();
						$('#contenedor-modulos').load(
							'arrendamientos/views/templates/aportaciones_capital.php'
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
				btnNAportacion.disabled = false;
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
			btnNAportacion.disabled = false;
		});
});

formDatesAc.addEventListener('submit', function (e) {
	e.preventDefault();
	btnDatesAc.disabled = true;
	let data = new FormData(frm_sd_ac),
		fechaInicio = data.get('f_inicio_ac'),
		fechaFinal = data.get('f_final_ac');

	ajaxPost('arrendamientos/php/querys/aportaciones_capital.php', data)
		.then((r) => {
			if (isJsonString(r)) {
				modalDatesAc.hide();
				tablaAportaciones.bootstrapTable('refreshOptions', {
					data: JSON.parse(r),
				});
				document.getElementById(
					'table_aportaciones'
				).childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].innerHTML = `Aportaciones de ${fechaInicio.slice(
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
			formDatesAc.reset();
			btnDatesAc.disabled = false;
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
			btnDatesAc.disabled = false;
		});
});
