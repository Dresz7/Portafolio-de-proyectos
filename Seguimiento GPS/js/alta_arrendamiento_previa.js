tablaVehiculos = $("#table_vehiculos");

window.operateEvents = {
  "click .arrendar": function (e, value, row, index) {
    Swal.fire({
      icon: "question",
      title: `¿Arrendar ${row.marca} ${row.linea} ${row.modelo}?`,
      text: `Recuerda que tienes que registrar al arrendatario
              y chófer adicional antes del arrendamiento`,
      buttonsStyling: false,
      allowOutsideClick: false,
      allowEnterKey: false,
      allowEscapeKey: false,
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: "Continuar",
      denyButtonText: "Registrar chófer",
      cancelButtonText: "Cancelar",
      customClass: {
        confirmButton: "btn btn-success m-2",
        denyButton: "btn btn-primary m-2",
        cancelButton: "btn btn-secondary m-2",
      },
    }).then((r) => {
      if (r.isConfirmed) {
        VehiculoArrendar = {
          id: row.id,
          marca: row.marca,
          linea: row.linea,
          modelo: row.modelo,
          serie: row.serie,
          tarifa: row.tarifa,
        };
        $("#contenedor-modulos").load(
          "arrendamientos/views/templates/alta_arrendamiento.php"
        );
      } else if (r.isDenied) {
        $("#contenedor-modulos").load("arrendamientos/views/templates/alta_cliente.php");
      } else {
        Swal.fire({
          position: "center",
          icon: "info",
          title: "No se realizó ningún cambio",
          showConfirmButton: false,
          timer: 1000,
        });
      }
    });
  },
  "click .rentado": function (e, value, row, index) {
    swal.fire({
      icon: "warning",
      title: "Vehículo no disponible",
      text: `El vehículo ${row.marca} ${row.linea} ${row.modelo}
            se encuentra arrendado actualmente`,
      buttonsStyling: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      customClass: {
        confirmButton: "btn btn-warning",
      },
    });
  },
  "click .noDisponible": function (e, value, row, index) {
    swal.fire({
      icon: "error",
      title: "Vehículo no disponible",
      text: `El vehículo ${row.marca} ${row.linea} ${row.modelo}
              se encuentra fuera de servicio`,
      buttonsStyling: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      customClass: {
        confirmButton: "btn btn-danger",
      },
    });
  },
};

tablaVehiculos.bootstrapTable({
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
    fileName: 'Lista de vehiculos para renta EstaquitasBajaRent', // Especifica el nombre de archivo deseado aquí
    // Otras opciones de exportación específicas pueden ir aquí
  },
  columns: [
    {
      field: "marca",
      title: "Marca",
    },
    {
      field: "linea",
      title: "Línea",
    },
    {
      field: "modelo",
      title: "Modelo",
    },
    {
      field: "serie",
      title: "Número de serie",
    },
    {
      field: "placa",
      title: "Número de placa",
    },
    {
      field: "tarifa",
      title: "Tarifa (día)",
    },
    {
      title: "Arrendar",
      formatter: "operateFormatter",
      events: "operateEvents",
    },
  ],
});

function operateFormatter(value, row, index) {
  if (row.disponibilidad == 0) {
    return [
      `<a class="arrendar link-dark" href="javascript:void(0)" title="Arrendar">
      <i class="bi bi-car-front-fill"></i>
      </a>`,
    ];
  } else if (row.disponibilidad == 1) {
    return [
      `<a class="rentado link-dark disabled" href="javascript:void(0)" title="Rentado">
      <i class="bi bi-car-front-fill"></i>
      </a>`,
    ];
  } else {
    return [
      `<a class="noDisponible link-dark disabled" href="javascript:void(0)" title="Fuera de servicio">
      <i class="bi bi-car-front-fill"></i>
      </a>`,
    ];
  }
}

function rowStyle(row, index) {
  if (row.disponibilidad == 1) {
    return {
      classes: "bg-warning",
    };
  } else if (row.disponibilidad == 2) {
    return {
      classes: "bg-danger",
    };
  }
  return {
    css: {
      color: "black",
    },
  };
}

tablaVehiculos.bootstrapTable("showLoading");
ajaxGet("arrendamientos/php/querys/vehiculos.php")
  .then((r) => {
    let data = JSON.parse(r);
// Ordenar los datos basado en la disponibilidad
data.sort((a, b) => {
  // Priorizar los vehículos totalmente disponibles (disponibilidad == 0)
  if (a.disponibilidad == 0 && b.disponibilidad != 0) return -1;
  if (b.disponibilidad == 0 && a.disponibilidad != 0) return 1;
  
  // Luego, cualquier otro caso que no sea 1 ni 2 (si aplica)
  if (a.disponibilidad != 1 && a.disponibilidad != 2 && (b.disponibilidad == 1 || b.disponibilidad == 2)) return -1;
  if (b.disponibilidad != 1 && b.disponibilidad != 2 && (a.disponibilidad == 1 || a.disponibilidad == 2)) return 1;
  
  // Finalmente, mover explicitamente al final los que tienen disponibilidad 1 o 2
  if (a.disponibilidad == 1 || a.disponibilidad == 2) return 1;
  if (b.disponibilidad == 1 || b.disponibilidad == 2) return -1;
  
  // En caso de igualdad, mantener el orden original o aplicar otro criterio aquí si es necesario
  return 0;
});

    // Ahora actualiza la tabla con los datos ordenados
    tablaVehiculos.bootstrapTable("refreshOptions", {
      data: data,
    });
  })
  .catch(function (r) {
    console.log(`Error: ${r}`);
  });
tablaVehiculos.bootstrapTable("hideLoading");