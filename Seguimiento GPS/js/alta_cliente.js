window.onbeforeunload = function (e) {
  e = e || window.event;
  if (formIsDirty(document.forms["msform"])) {
    if (e) {
      e.returnValue = "You have unsaved changes.";
    }
  }
};

toastLiveExample = document.getElementById("liveToast");
toastText = document.getElementById("toast-text");

codigoPostal = document.getElementById("codigo_postal_txt");
asentamiento = document.getElementById("asentamiento_select");
ciudad = document.getElementById("ciudad_select");
municipio = document.getElementById("municipio_select");
estado = document.getElementById("estado_select");

//seccionElectoral = document.getElementById("seccion_electoral_txt");
//distritoElectoral = document.getElementById("distrito_electoral_txt");

submitForm = document.getElementById("msform");
btnSubmitForm = document.getElementById("fClienteSubmit");

formcc = document.getElementById("frm_cc_previa");
btnSubmitFormcc = document.getElementById("fccSubmit");

submitModal = document.getElementById("nDireccion");
btnSubmitModal = document.getElementById("fDirSubmit");

myModal = new bootstrap.Modal("#nuevaDireccionModal", {
  keyboard: false,
});

function validate(fsId) {
  let total,
    fieldset = document.getElementById(fsId);
  let inputs = Array.prototype.slice.call(
    fieldset.querySelectorAll("input, select"), 0);

  for (let i = 0; i < inputs.length; i++) {
    if (!inputs[i].checkValidity()) {
      let toast = new bootstrap.Toast(toastLiveExample);
      toastText.textContent = "";
      toastText.textContent = inputs[i].attributes["rechazado"].value;
      toast.show();
      return false;
    } else {
      total = i;
    }
  }
  if (total == inputs.length - 1) {
    return true;
  }
}

ajaxGet("arrendamientos/php/querys/secciones.php")
  .then((r) => {
    document.getElementById("seccionList").innerHTML = r;
  })
  .catch(function (r) {
    console.log(`Error: ${r}`);
  });

$(".next").click(function (e) {
  e.preventDefault();
  e.stopPropagation();
  current_fs = $(this).parent();

  if (validate(current_fs[0].id)) {
    if (animating) return false;
    animating = true;

    next_fs = $(this).parent().next();

    //activate next step on progressbar using the index of next_fs
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate(
      { opacity: 0 },
      {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale current_fs down to 80%
          scale = 1 - (1 - now) * 0.2;
          //2. bring next_fs from the right(50%)
          left = now * 50 + "%";
          //3. increase opacity of next_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({
            position: "initial",
          });
          next_fs.css({ left: left, opacity: opacity });
        },
        duration: 750,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: "easeInOutBack",
      }
    );
  }
});

$(".previous").click(function (e) {
  e.preventDefault();
  e.stopPropagation();

  current_fs = $(this).parent();

  if (validate(current_fs[0].id)) {
    if (animating) return false;
    animating = true;

    previous_fs = $(this).parent().prev();

    //de-activate current step on progressbar
    $("#progressbar li")
      .eq($("fieldset").index(current_fs))
      .removeClass("active");

    //show the previous fieldset
    previous_fs.show();
    //hide the current fieldset with style
    current_fs.animate(
      { opacity: 0 },
      {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale previous_fs from 80% to 100%
          scale = 0.8 + (1 - now) * 0.2;
          //2. take current_fs to the right(50%) - from 0%
          left = (1 - now) * 50 + "%";
          //3. increase opacity of previous_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({ left: left });
          previous_fs.css({
            transform: "scale(" + scale + ")",
            opacity: opacity,
          });
        },
        duration: 750,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: "easeInOutBack",
      }
    );
  }
});

codigoPostal.addEventListener("change", () => {
  let postData = new FormData();
  postData.append("cp", codigoPostal.value);

  ajaxPost("arrendamientos/php/querys/asentamiento.php", postData)
    .then((r) => {
        asentamiento.innerHTML = r;
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });

  ajaxPost("arrendamientos/php/querys/ciudad.php", postData)
    .then((r) => {
        ciudad.innerHTML = r;
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });

  ajaxPost("arrendamientos/php/querys/municipio.php", postData)
    .then((r) => {
        municipio.innerHTML = r;
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });

  ajaxPost("arrendamientos/php/querys/estado.php", postData)
    .then((r) => {
        estado.innerHTML = r;
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });
});

/*seccionElectoral.addEventListener("change", () => {
  cargarDistrito(seccionElectoral, distritoElectoral);
});
*/
btnSubmitForm.addEventListener("click", function (e) {
  e.preventDefault();
  e.stopPropagation();
  current_fs = $(this).parent();

  if (validate(current_fs[0].id)) {
    btnSubmitForm.disabled = true;
    ajaxPost("arrendamientos/php/scripts/insert_cliente.php", new FormData(msform))
      .then((r) => {
        if (parseInt(r, 10) === 0) {
          Swal.fire({
            icon: "success",
            title: "Cliente registrado",
            buttonsStyling: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              confirmButton: "btn btn-success",
            },
          }).then((r) => {
            if (r.isConfirmed) {
              $("#contenedor-modulos").load("arrendamientos/views/templates/home.html");
            }
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
          btnSubmitForm.disabled = false;
        }
      })
      .catch(function (r) {
        console.log(`Error: ${r}`);
      });
  };
});

formcc.addEventListener("submit", function (e) {
  e.preventDefault();
  e.stopPropagation();

  btnSubmitFormcc.disabled = true;
  ajaxPost("arrendamientos/php/querys/cliente.php", new FormData(formcc))
    .then((r) => {
      if (isJsonString(r)) {
        let infoT = JSON.parse(r);
        swal.fire({
          icon: "info",
          title: "Cliente registrado",
          html: `<center> Curp: </center>
                <center> ${infoT[0].curp} </center>
                <br>
                <center> Nombre: </center>
                <center> ${infoT[0].nombreCompleto} </center>
                <br>
                <center> Num. Tel.: </center>
                <center> ${infoT[0].telefono} </center>`,
          buttonsStyling: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
            confirmButton: "btn btn-primary",
          },
        });
        btnSubmitFormcc.disabled = false;
      } else if (r == "El registro no existe") {
        formcc.classList.add("d-none");
        submitForm.classList.remove("d-none");
        document.getElementById("curp_txt").value = document.getElementById("cc_text").value;
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
      }
    })
    .catch(function (e) {
      swal.fire({
        icon: "warning",
        title: "ups",
        text: `${e}`,
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

submitModal.addEventListener("submit", function (e) {
  e.preventDefault();
  btnSubmitModal.disabled = true;
  ajaxPost("arrendamientos/php/scripts/insert_domicilio.php", new FormData(nDireccion))
    .then((r) => {
      if (parseInt(r, 10) === 0) {
        Swal.fire({
          position: "bottom-end",
          icon: "success",
          title: "Dirección registrada",
          showConfirmButton: false,
          timer: 1000,
        });
        btnSubmitModal.disabled = false;
      } else {
        Swal.fire({
          position: "bottom-end",
          icon: "error",
          title: `${r}`,
          showConfirmButton: false,
          timer: 1000,
        });
        btnSubmitModal.disabled = false;
      }
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });
  //? Reinicia el campo código postal, selects y el formulario modal
  codigoPostal.value = "";
  asentamiento.innerHTML = "";
  ciudad.innerHTML = "";
  municipio.innerHTML = "";
  estado.innerHTML = "";
  submitModal.reset();
  myModal.hide();
});
