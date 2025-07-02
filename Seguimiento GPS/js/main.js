//TODO Variables
// Session timer
let segs = 0;
// OffCanvas Menu
const OffCanvas = new bootstrap.Offcanvas("#offcanvas");
// Element selector in offcanvas menu
const menuElements = document.querySelectorAll("[li_id]");
// Toast alert
let toastLiveExample, toastText;
//jQuery time
let current_fs, next_fs, previous_fs; //fieldsets
let left, opacity, scale; //fieldset properties which we will animate
let animating; //flag to prevent quick multi-click glitches
// Validate function
let fieldset, inputs, total;
// Week Days array
const days = [
  "Domingo",
  "Lunes",
  "Martes",
  "Miércoles",
  "Jueves",
  "Viernes",
  "Sábado",
];


var socket;
var isWebSocketOpen = false;

// url valid checker
/*function UrlExists(url) {
  const http = new XMLHttpRequest();
  http.open("HEAD", url, false);
  http.send();
  if (http.status != 404) 
  return true;
  else 
  return false;
}
*/
function UrlExists(url) {
  const http = new XMLHttpRequest();

  try {
    http.open("HEAD", url, false);
    http.send();

    // Verificar si el estado está en el rango 200-299 (éxito) o 300-399 (redirección)
    if (http.status >= 200 && http.status < 400) {
      return true;  // La URL existe
    } else {
      return false; // La URL no existe o hay un error
    }
  } catch (error) {
    // No imprimir nada en la consola
    return false;
  }
}

// Función para mostrar un SweetAlert de carga al server
function showLoadingAlert() {
  return new Promise((resolve) => {
    Swal.fire({
      title: 'Cargando',
      text: 'Conexión al servidor de rastreo cargando..',
      allowEscapeKey: false,
      allowOutsideClick: false,
      allowEnterKey: false,
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading();
        resolve();
      }
    });
  });
}


// Función para mostrar un SweetAlert de conexión exitosa
function showSuccessAlert() {
  Swal.fire({
    icon: 'success',
    title: 'Conexión establecida',
    text: 'La conexión al servidor de rastreo se ha establecido correctamente.',
    showConfirmButton: false, // Ocultar el botón de confirmación
    timer: 4000, // Duración en milisegundos (4 segundos)
  });
}

// Lógica para la conexión WebSocket
async function connectToWebSocket() {
  // Mostrar SweetAlert de carga
 // Mostrar SweetAlert de carga
 const loadingPromise = showLoadingAlert();


  // Crear el WebSocket antes de inicializar el mapa
  socket = new WebSocket("wss://traccarbcs.com:3000");
// Esperar a que la promesa de carga se resuelva
await loadingPromise;
  // Eventos del WebSocket
  socket.addEventListener("error", (event) => {
    console.error("Error en la conexión WebSocket:", event);

    // Mostrar SweetAlert en caso de error
    Swal.fire({
      icon: 'error',
      title: 'Error de conexión',
      text: 'La conexión WebSocket se cerró debido a un error del servidor WebSocket está caído.',
      showCancelButton: true,
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Recargar página',
    }).then((result) => {
      if (result.isConfirmed) {
        // Recargar la página sin cerrar la sesión del usuario
        location.reload();
      }
    });
  });

  socket.addEventListener("open", (event) => {
    console.log("Conexión WebSocket establecida correctamente.", event);
    // Marcar el estado del WebSocket como abierto
    isWebSocketOpen = true;

    // Mostrar SweetAlert de éxito
    showSuccessAlert();
  });

  socket.addEventListener("close", (event) => {
    console.log("Conexión WebSocket cerrada.", event);
    // Marcar el estado del WebSocket como cerrado
    isWebSocketOpen = false;

    // Verificar la razón del cierre y mostrar SweetAlert correspondiente si es necesario
    if (!event.wasClean) {
      console.error(`La conexión se cerró de manera inesperada o debido a un error, código: ${event.code}`);
      Swal.fire({
        icon: 'error',
        title: 'Error de conexión',
        text: 'La conexión con el servidor se cerró de manera inesperada debido a un error de desconexion con tu Internet. Intenta recargar la página.',
        showCancelButton: false,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Recargar página',
        allowOutsideClick: false,
        allowEscapeKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          // Recargar la página sin cerrar la sesión del usuario
          location.reload();
        }
      });
    }
  });
}



// click on element in menu
for (let i = 0; i < menuElements.length; i++) {
  menuElements[i].addEventListener("click", () => {
    let moduleActive = menuElements[i];
    for (let ii = 0; ii < menuElements.length; ii++) {
      menuElements[ii].children[0].classList.remove("moduleActive");
    }
    moduleName = moduleActive.attributes["li_id"].nodeValue;
    if (moduleName != undefined || moduleName != null) {
      Swal.fire({
        title: "Cargando",
        allowEscapeKey: false,
        allowOutsideClick: false,
        allowEnterKey: false,
        timer: 1000,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading();
          moduleActive.children[0].classList.add("moduleActive");

          if (moduleName == "webhookTraccar" && !isWebSocketOpen) {
   // Llamada a la función para conectar al WebSocket
connectToWebSocket();
           
        } else if (isWebSocketOpen && moduleName !== "webhookTraccar") {
            // Si estás en otro módulo y la conexión WebSocket está abierta y el módulo no es "webhookTraccar", ciérrala
            socket.close();
            isWebSocketOpen = false;
        }
        

          if (UrlExists(`arrendamientos/views/templates/${moduleName}.html`)) {
            $("#contenedor-modulos").load(`arrendamientos/views/templates/${moduleName}.html`);
          } else {
            $("#contenedor-modulos").load(`arrendamientos/views/templates/${moduleName}.php`);
          }

          OffCanvas.hide();
        },
      });
    }
  });
}

// GET request
function ajaxGet(url) {
  return new Promise(function (resolve, reject) {
    const xhr = new XMLHttpRequest();
    xhr.onload = function () {
      resolve(this.responseText);
    };
    xhr.onerror = reject;
    xhr.open("GET", url);
    xhr.send();
  });
}

// POST request
function ajaxPost(url, data) {
  return new Promise(function (resolve, reject) {
    const xhr = new XMLHttpRequest();
    xhr.onload = function () {
      resolve(this.responseText);
    };
    xhr.onerror = reject;
    xhr.open("POST", url);
    xhr.send(data);
  });
}

// Clock
function currentTime() {
  let date = new Date(),
    day = date.getDate(),
    dayNumber = date.getDay(),
    month = date.getMonth() + 1,
    year = date.getFullYear(),
    hour = date.getHours() - 1,
    min = date.getMinutes(),
    sec = date.getSeconds(),
    session = "AM",
    clock,
    time,
    t;

  if (hour === 0) {
    hour = 12;
  }

  if (hour > 11) {
    hour = hour - 12;
    if (hour === 0) {
      hour = 12;
    }
    session = "PM";
  }

  day = day < 10 ? "0" + day : day;
  month = month < 10 ? "0" + month : month;
  hour = hour < 10 ? "0" + hour : hour;
  min = min < 10 ? "0" + min : min;
  sec = sec < 10 ? "0" + sec : sec;

  time = `${days[dayNumber]} ${day}/${month}/${year}, ${hour}:${min}:${sec} ${session}`;

  clock = document.getElementById("clock");
  if (clock != null) {
    clock.innerText = time;
  }

  t = setTimeout(() => {
    currentTime();
    segs += 1;
  }, 1000);

  if (segs >= 1440) {
    clearTimeout(t);
    Swal.fire({
      icon: "warning",
      title: "La sesión ha expirado",
      html: `<center> Serás redirigido a la pantalla principal </center>`,
      buttonsStyling: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      customClass: {
        confirmButton: "btn btn-warning",
      },
    }).then((r) => {
      if (r.isConfirmed) {
        window.location.reload();
      }
    });
  }
}

// POST distrito electoral from BD
function cargarDistrito(seccion, distrito) {
  const postData = new FormData();
  postData.append("seccion", seccion.value);
  ajaxPost("arrendamientos/php/querys/distrito.php", postData)
    .then((r) => {
      distrito.value = r;
    })
    .catch(function (r) {
      console.log(`Error: ${r}`);
    });
}

// loading template for bootstrap tables
function loadingTemplate() {
  return `<span class="loading-wrap">
  <span class="loading-text">
  CARGANDO
  </span>
  <span class="animation-wrap"><span class="animation-dot"></span></span>
  </span>`;
}

// detail formatter for bootstrap tables
function detailFormatter(index, row) {
  const html = [];
  $.each(row, function (key, value) {
    index++;
    html.push(`<p class="text-start"><b>${index} - ${key}: </b>${value}</p>`);
  });
  return html.join("");
}

// foter formatter to bootstrap tables
function totalFormatter(data) {
  let field = this.field;
  total = data
    .map(function (row) {
      return +row[field];
    })
    .reduce(function (sum, i) {
      return sum + i;
    }, 0)
    .toFixed(2);
  return total !== ""
    ? total < 0
      ? `<div class="text-danger">$${total}</div>`
      : `<div>$${total}</div>`
    : "";
}

function percentFormatter(value) {
  return `<div >${value}%</div>`;
}
//`<div>$${value}</div>`
function priceFormatter(value) {
  return value !== ""
    ? value < 0
      ? `<div class="text-danger">$${value}</div>`
      : `<div>$${value}</div>`
    : "";
}

// form as information before refresh or close page
function formIsDirty(form) {
  try {
    for (let i = 0; i < form.elements.length; i++) {
      let element = form.elements[i];
      let type = element.type;
      if (type == "checkbox" || type == "radio") {
        if (element.checked != element.defaultChecked) {
          return true;
        }
      } else if (
        type == "hidden" ||
        type == "password" ||
        type == "text" ||
        type == "textarea"
      ) {
        if (element.value != element.defaultValue) {
          return true;
        }
      } else if (type == "select-one" || type == "select-multiple") {
        for (let j = 0; j < element.options.length; j++) {
          if (
            element.options[j].selected != element.options[j].defaultSelected
          ) {
            return true;
          }
        }
      }
    }
    return false;
  } catch (error) {
    return false;
  }
}

function isJsonString(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}

currentTime();

ajaxGet("arrendamientos/php/querys/user_name.php")
  .then((r) => {
    if (isJsonString(r)) {
      let res = JSON.parse(r);
      if (res.tipoUsuario == "ADMINISTRADOR") {
        document.getElementById("ia_li").classList.remove("d-none");
        document.getElementById("io_li").classList.remove("d-none");
        document.getElementById("id_li").classList.remove("d-none");
        document.getElementById("if_li").classList.remove("d-none");
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



