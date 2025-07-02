tablaClientes = $("#table_clientes");
tablaClientesEl = document.getElementById("table_container");

modalEdicion = new bootstrap.Modal(document.getElementById("editModal"));
btnCloseModal = document.getElementById("btn-close-modal");

modalForm = document.getElementById("frm_edit_cliente");
clienteInfo = Array.prototype.slice.call(
  modalForm.querySelectorAll("input, select"), 0);

modalFormBtn = document.getElementById("fcmClienteSubmit");

queryForm = document.getElementById("frm_consulta_cliente");
datosCliente = queryForm.getElementsByTagName("input");
queryFormBtn = document.getElementById("fcClienteSubmit");

/*seccionElectoral = document.getElementById("seccion_electoral_txt");
distritoElectoral = document.getElementById("distrito_electoral_txt");*/

document.getElementById("curp_consulta")
  .addEventListener("keyup", function () {
    this.value = this.value.toUpperCase();
   });

function consultaInfoCliente(idCliente) {
  let postData = new FormData();
  postData.append("idCliente", idCliente);
  ajaxPost("arrendamientos/php/querys/cliente_todo.php", postData)
    .then((r) => {
      if (isJsonString(r)) {
        let resp = JSON.parse(r),
          map = new Map(Object.entries(resp[0]));
        clienteInfo.forEach((e) => {
          e.value = map.get(`${e.attributes["objref"].value}`);
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
      console.log(`Error: ${r}`);
    });
}

window.operateEvents = {
  "click .editar": function (e, value, row, index) {
    consultaInfoCliente(row.id);
    modalEdicion.show();
  },
};

function operateFormatter(value, row, index) {
  return `<a class="editar link-dark" href="javascript:void(0)" title="Editar Registro"
            data-bs-toggle="modal" data-bs-target="#editModal">
            <i class="bi bi-pencil-square"></i>
            </a>`;
}

tablaClientes.bootstrapTable({
  columns: [
    {
      field: "nombreCompleto",
      title: "Nombres",
    },
    {
      field: "curp",
      title: "Curp",
    },
    {
      field: "telefono",
      title: "Teléfono",
    },
    {
      title: "Editar",
      formatter: "operateFormatter",
      events: "operateEvents",
    },
  ],
});

/*ajaxGet("arrendamientos/php/querys/secciones.php")
  .then((r) => {
    document.getElementById("seccionList").innerHTML = r;
  })
  .catch(function (r) {
    console.log(`Error: ${r}`);
  });*/

btnCloseModal.addEventListener("click", function (e) {
  modalEdicion.hide();
  clienteInfo.forEach((e) => {
    e.value = "";
  });
});

queryForm.addEventListener("submit", function (e) {
  e.preventDefault();
  let postData = new FormData();
  postData.append("curp", datosCliente[0].value);
  queryFormBtn.disabled = true;
  ajaxPost("arrendamientos/php/querys/cliente.php", postData)
    .then((r) => {
      if (isJsonString(r)) {
        queryForm.classList.add("d-none");
        tablaClientesEl.classList.remove("d-none");
        tablaClientes.bootstrapTable("showLoading");
        setTimeout(function () {
          tablaClientes.bootstrapTable("refreshOptions", {
            data: JSON.parse(r),
          });
        }, 1000);
      } else {
        swal.fire({
          icon: "warning",
          title: "ups",
          text: `${r}`,
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-warning",
          },
        });
        queryFormBtn.disabled = false;
      }
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });
});

modalForm.addEventListener("change", () => {
  modalFormBtn.disabled = false;
});

/*seccionElectoral.addEventListener("change", () => {
  if (seccionElectoral.value != null) {
    cargarDistrito(seccionElectoral, distritoElectoral);
  }
});
*/
modalForm.addEventListener("submit", (e) => {
  e.preventDefault();
  modalFormBtn.disabled = true;

  ajaxPost("arrendamientos/php/scripts/update_cliente.php", new FormData(frm_edit_cliente))
    .then((r) => {
      if (parseInt(r, 10) === 0) {
        Swal.fire({
          icon: "success",
          title: "Registro actualizado",
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-success",
          },
        }).then((r) => {
          if (r.isConfirmed) {
            modalEdicion.hide();
            $("#contenedor-modulos").load(
              "arrendamientos/views/templates/consulta_cliente.html"
            );
          }
        });
      } else {
        swal.fire({
          icon: "warning",
          title: "Registro no actualizado",
          text: `${r}`,
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-warning",
          },
        });
        modalFormBtn.disabled = false;
      }
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });
});
