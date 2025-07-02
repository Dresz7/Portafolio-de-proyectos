tablaSaldoCaja = $('#table_saldo_caja');

modalNSaldoCaja = new bootstrap.Modal('#registrarSaldoCaja', {
	keyboard: false,
});
modalSec = document.getElementById('registrarSaldoCaja');
btnSiec = document.getElementById('btnNSiec');
btnSfec = document.getElementById('btnNSfec');
formNSaldoCaja = document.getElementById('nSaldoCaja');
btnNSaldoCaja = document.getElementById('btnMsecSubmit');

modalDatesSec = new bootstrap.Modal('#set_dates_sec', { keyboard: false });
formDatesSec = document.getElementById('frm_sd_sec');
btnDatesSec = document.getElementById('frm_sd_submit_sec');

tablaSaldoCaja.bootstrapTable({
	columns: [
		[
			{
				title: 'Saldo en caja de la semana',
				valign: 'middle',
				align: 'center',
				colspan: 3,
			},
		],
		[
			{
				field: 'saldoInicial',
				title: 'Saldo Inicial',
				valign: 'middle',
				align: 'center',
			},
			{
				field: 'saldoFinal',
				title: 'Saldo Final',
				valign: 'middle',
				align: 'center',
			},
			{
				field: 'idSucursal',
				title: 'Sucursal',
				valign: 'middle',
				align: 'center',
			}
		],
	],
});

btnSiec.addEventListener('click', function (e) {
	modalSec.querySelector('.modal-title').textContent =
		'Registrar saldo inicial en caja';
	document.getElementById('tipo_saldo_caja').value = 'inicial';
});

btnSfec.addEventListener('click', function (e) {
	modalSec.querySelector('.modal-title').textContent =
		'Registrar saldo final en caja';
	document.getElementById('tipo_saldo_caja').value = 'final';
});

ajaxGet('arrendamientos/php/querys/saldo_caja.php')
	.then((r) => {
		if (isJsonString(r)) {
			tablaSaldoCaja.bootstrapTable('refreshOptions', { data: JSON.parse(r) });
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

formNSaldoCaja.addEventListener('submit', function (e) {
	e.preventDefault();
	btnNSaldoCaja.disabled = true;

	ajaxPost('arrendamientos/php/scripts/insert_saldo_caja.php', new FormData(nSaldoCaja))
		.then((r) => {
			if (parseInt(r, 10) === 0) {
				Swal.fire({
					icon: 'success',
					title: 'Saldo en caja registrado',
					buttonsStyling: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customClass: {
						confirmButton: 'btn btn-success',
					},
				}).then((r) => {
					if (r.isConfirmed) {
						modalNSaldoCaja.hide();
						$('#contenedor-modulos').load('arrendamientos/views/templates/saldo_caja.php');
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
				btnNSaldoCaja.disabled = false;
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

function numWeekYear(date) {
	const inicioYr = new Date(date.getFullYear(), 0, 1);
	const diaSemanaInicioYr = inicioYr.getDay();
	const diasTranscurridos = Math.floor(
		(date - inicioYr) / (24 * 60 * 60 * 1000)
	);
	let numWeek = Math.ceil((diasTranscurridos + diaSemanaInicioYr + 1) / 7);
	if (diaSemanaInicioYr !== 0 && date.getDay() < diaSemanaInicioYr) {
		numWeek--;
	}
	return numWeek;
}

formDatesSec.addEventListener('submit', function (e) {
	e.preventDefault();
	btnDatesSec.disabled = true;
	let data = new FormData(frm_sd_sec),
		fechaDefinida = data.get('date_sec');
	let fecha = new Date(fechaDefinida);
	let numeroSemana = numWeekYear(fecha);

	ajaxPost('arrendamientos/php/querys/saldo_caja.php', data)
		.then((r) => {
			if (isJsonString(r)) {
				modalDatesSec.hide();
				tablaSaldoCaja.bootstrapTable('refreshOptions', {
					data: JSON.parse(r),
				});
				document.getElementById(
					'table_saldo_caja'
				).childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0].innerHTML = `Saldo en caja ${fechaDefinida.slice(
					8
				)}/${fechaDefinida.slice(5, 7)}/${fechaDefinida.slice(
					0,
					4
				)}, semana ${numeroSemana} del año`;
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
			formDatesSec.reset();
			btnDatesSec.disabled = false;
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
			btnDatesSec.disabled = false;
		});
});
