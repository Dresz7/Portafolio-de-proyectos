window.onbeforeunload = function (e) {
  e = e || window.event;
  if (formIsDirty(document.forms["datos_vehiculos"])) {
    if (e) {
      e.returnValue = "You have unsaved changes.";
    }
  }
};

(() => {
  "use strict";
  const forms = document.querySelectorAll(".needs-validation");
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (e) {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        } else {
            e.preventDefault();
            ajaxPost("arrendamientos/php/scripts/insert_unidad.php", new FormData(datos_vehiculos))
              .then((r) => {
                if (parseInt(r,10) === 0) {
                  Swal.fire({
                    icon: "success",
                    title: "Unidad registrada",
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    customClass: {
                      confirmButton: "btn btn-success",
                    },
                  }).then((result) => {
                    if (result.isConfirmed) {
                      $("#contenedor-modulos").load(
                        "arrendamientos/views/templates/home.html"
                      );
                    }
                  });
                } else {
                  Swal.fire({
                    icon: "warning",
                    title: "ups",
                    html: `<center> ${r} </center>`,
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
        form.classList.add("was-validated");
      },
      false
    );
  });
})();