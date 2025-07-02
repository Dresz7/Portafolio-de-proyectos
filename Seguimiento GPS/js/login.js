(() => {
  "use strict";
  const forms = document.querySelectorAll(".needs-validation");
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (e) => {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        } else {
          e.preventDefault();
          login();
        }
        form.classList.add("was-validated");
      },
      false
    );
  });
})();

function login() {
  Swal.fire({
    title: "AUTENTICANDO",
    html: `<center> Espere... </center>`,
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    timer: 500,
    didOpen: () => {
      Swal.showLoading();
      setTimeout(() => {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "arrendamientos/php/scripts/login.php");
        xhr.send(new FormData(loginForm));
        xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
            let res_php = xhr.responseText.split(",");
            let nombre = res_php[0];
            if (res_php[1] == "administrador" || res_php[1] == "estandar") {
              Swal.fire({
                icon: "success",
                title: "ACCESO CONCEDIDO",
                html: `<center> Bienvenido ${nombre} </center>`,
                buttonsStyling: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                customClass: {
                  confirmButton: "btn btn-success",
                },
              });
              document.getElementById("content").innerHTML = "";
              $("#content").load("arrendamientos/views/templates/menuContainer.php");
            } else {
              Swal.fire({
                icon: "error",
                title: "ERROR",
                html: `<center> ${xhr.responseText} </center>`,
                buttonsStyling: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                customClass: {
                  confirmButton: "btn btn-danger",
                },
              });
            }
          }
        };
      }, 750);
    },
  });
}
