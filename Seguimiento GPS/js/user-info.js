ajaxGet("arrendamientos/php/querys/user_name.php")
  .then((r) => {
    if (r != 1) {
        let res = JSON.parse(r);
        document.getElementById("user-name").innerHTML = res.nombre;
        document.getElementById("user-role").innerHTML = res.tipoUsuario;
        if (UrlExists(`arrendamientos/images/users/${res.userName}.jpg`)) {
            document.getElementById("avatar").style.background = `url(arrendamientos/images/users/${res.userName}.jpg)`;
            document.getElementById("avatar").style.backgroundSize = "cover";
        }
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
  });