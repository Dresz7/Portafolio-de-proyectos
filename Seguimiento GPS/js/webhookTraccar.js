markers = [];

// Referencia global al círculo actual para poder borrarlo o actualizarlo
var currentCircle = null;

var deviceData = [];
var selectedDeviceId = "all";
var markerCluster;
var groupColors = {};
// Objeto para almacenar los marcadores creados al inicializar el mapa
var initialMarkers = {};

if (typeof addedGroups === 'undefined') {
  var addedGroups = {};
}


$(".modal").draggable({
  handle: ".modal-header"
});

function LabelOverlay(position, text, map) {
  this.position = position;
  this.text = text;
  this.map = map;
  this.div = null;
  this.setMap(map);
}



$(document).ready(function () {

  let isStoresVisible = $('#storeVisibilitySwitch').is(':checked');


  // Filtros
  const selectedFilters = [];

  const filterButton = $("#filterButton");
  const filterOptions = $("#filterOptions");
  const filterSelect = $("#filterSelect");
  const selectAllFiltersButton = $("#selectAllFilters");
  const deselectAllFiltersButton = $("#deselectAllFilters");
  const searchInput = $("#searchInput");
  const selectorElement = $(".Selector");

  let filtersHeight = 0;
  let isExpanded = false;





  //animacion menu lateral
  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    $(this).toggleClass('active');
  });


  $.fn.select2.defaults.set("language", "es");
  // Inicializa el select como un elemento Select2
  $('#filterSelect').select2({
    placeholder: "Grupos",
    language: {
      errorLoading: function () {
        return "No se pudieron cargar los resultados"
      },
      inputTooLong: function (e) {
        var n = e.input.length - e.maximum
          , r = "Por favor, elimine " + n + " car";
        return r += 1 == n ? "ácter" : "acteres"
      },
      inputTooShort: function (e) {
        var n = e.minimum - e.input.length
          , r = "Por favor, introduzca " + n + " car";
        return r += 1 == n ? "ácter" : "acteres"
      },
      loadingMore: function () {
        return "Cargando más resultados…"
      },
      maximumSelected: function (e) {
        var n = "Sólo puede seleccionar " + e.maximum + " elemento";
        return 1 != e.maximum && (n += "s"),
          n
      },
      noResults: function () {
        return "No se encontraron resultados"
      },
      searching: function () {
        return "Buscando…"
      },
      removeAllItems: function () {
        return "Eliminar todos los elementos"
      }
    }
  });



  filterButton.click(function () {
    if (isExpanded) {
      // Restaura el valor original
      selectorElement.css("grid-template-rows", "5% 2% auto");
    } else {
      // Calcula y ajusta el valor de grid-template-rows
      setTimeout(function () {
        filtersHeight = filterOptions.outerHeight();
        const newRowsValue = `calc(5% + ${filtersHeight}px + 1%) 2% auto`;
        selectorElement.css("grid-template-rows", newRowsValue);
      }, 300);
    }
    isExpanded = !isExpanded;
  });

  filterSelect.on("change", function () {
    updateHeight();
    updateFilters();
  });

  selectAllFiltersButton.click(function () {
    filterSelect.find("option").prop("selected", true);
    filterSelect.trigger("change"); // Actualiza Select2
    updateHeight();
    updateFilters();
  });

  deselectAllFiltersButton.click(function () {
    filterSelect.find("option").prop("selected", false);
    filterSelect.trigger("change"); // Actualiza Select2
    updateHeight();
    updateFilters();
  });

  searchInput.on("input", function () {
    updateFilters();
  });

  function updateHeight() {
    // Calcula y ajusta el valor de grid-template-rows
    setTimeout(function () {
      filtersHeight = filterOptions.outerHeight();
      const newRowsValue = `calc(5% + ${filtersHeight}px + 1%) 2% auto`;
      selectorElement.css("grid-template-rows", newRowsValue);
    }, 100);
  }

  function updateFilters() {
    selectedFilters.length = 0;

    filterSelect.find("option:selected").each(function () {
      selectedFilters.push($(this).val());
    });

    $(".row").each(function (index, div) {
      const divType = $(div).data("device-type");
      const searchText = searchInput.val().toLowerCase();
      const name = $(div).text().toLowerCase();

      if (divType === "default") {
        // Mostrar el div "default" siempre
        $(div).show();
      } else if (
        (selectedFilters.length === 0 || selectedFilters.includes(divType)) &&
        (searchText === "" || name.includes(searchText))
      ) {
        $(div).show();
      } else {
        $(div).hide();
      }
    });
  }

  // Mostrar todos los divs al principio, incluido el div "default"
  $(".row").show();




});




function assignGroupColors(groups) {
  const predefinedColors = [
    '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd',
    '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf',
    '#1a9850', '#66bd63', '#a6d96a', '#d9ef8b', '#fee08b',
    '#fdae61', '#f46d43', '#d73027', '#a50026', '#ff0000'
  ];

  groups.forEach(function (group, index) {
    groupColors[group] = predefinedColors[index % predefinedColors.length];
  });
}


if (typeof google === "undefined" || typeof google.maps === "undefined") {



  // La API de Google Maps aún no está cargada, carga la API y luego inicializa el mapa
  // Google maps vars
  let map;

  const googleMapsApiKey = "AIzaSyBVgmBMxiVFWqCK1HNGRWHsNaLGEDEr2cg";
  const script = document.createElement("script");
  script.src = `https://maps.googleapis.com/maps/api/js?key=${googleMapsApiKey}&callback=initMap`;
  script.async = true;
  script.defer = true;

  script.onload = function () {
    // La API de Google Maps se ha cargado, ahora puedes inicializar tu mapa aquí

    initMap();
  };

  document.body.appendChild(script);
} else {
  // La API de Google Maps ya está cargada, inicializa el mapa directamente
  initMap();

}

async function initMap() {

   // Define un estilo para ocultar tiendas
   const hideStoresStyle = [
    {
      featureType: 'poi.business',
      stylers: [{ visibility: 'off' }]
    },
    {
      featureType: 'transit',
      elementType: 'labels.icon',
      stylers: [{ visibility: 'off' }]
    }
    // ... puedes agregar más estilos según necesites
  ];


  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 25.031984, lng: -111.673107 },
    zoom: 7.5,
    zoomControl: true,
    scaleControl: true,
    mapTypeControl: false,
    mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    },  // Aplicar estilos para ocultar tiendas si el checkbox está desactivado
    styles: hideStoresStyle


  });
 
    // Crear el botón usando clases de Bootstrap
    const mapTypeControlDiv = document.createElement('div');
    mapTypeControlDiv.classList.add('map-type-control','d-flex', 'flex-column', 'align-items-center', 'm-2');
    mapTypeControlDiv.style.right = '10px'; // Ajusta la distancia desde el borde derecho
    mapTypeControlDiv.style.top = '10px'; // Ajusta la distancia desde el borde superior
    
    const mapTypeControlUI = document.createElement('select');
    mapTypeControlUI.classList.add('form-select', 'form-select-sm');
    mapTypeControlUI.title = 'Cambiar tipo de mapa';
    
    // Opciones del selector
    const mapTypes = [
        {value: 'roadmap', text: 'Mapa de carreteras'},
        {value: 'satellite', text: 'Satélite'},
        {value: 'hybrid', text: 'Híbrido'},
        {value: 'terrain', text: 'Terreno'}
    ];

    // Agregar opciones al select
    mapTypes.forEach(type => {
        let option = document.createElement('option');
        option.value = type.value;
        option.text = type.text;
        mapTypeControlUI.appendChild(option);
    });

    mapTypeControlDiv.appendChild(mapTypeControlUI);

    // Posicionar el botón en el mapa
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(mapTypeControlDiv);

    mapTypeControlUI.addEventListener('change', () => {
      map.setMapTypeId(mapTypeControlUI.value);
  });

  LabelOverlay.prototype = new google.maps.OverlayView();
  
  LabelOverlay.prototype.onAdd = function() {
    var div = document.createElement('div');
    div.style.position = 'absolute';
    div.style.whiteSpace = 'nowrap';
    div.style.padding = '2px';
    // div.style.border = '1px solid black'; // Removido para quitar el borde del div
    // div.style.borderRadius = '3px'; // Removido ya que no hay borde
    div.style.fontSize = '14px';
    div.style.fontWeight = 'bold'; // Añadido para hacer el texto en negrita
    div.style.color = 'black';
    div.style.zIndex = '1000'; // Asegura que el z-index sea alto para que esté por encima del círculo
    // Añadir un contorno al texto para mejorar la legibilidad
    div.style.textShadow = '0px 0px 3px white, 0px 0px 3px white, 0px 0px 3px white, 0px 0px 3px white';
    div.innerHTML = this.text;
  
    this.div = div;
  
    var panes = this.getPanes();
    panes.overlayLayer.appendChild(div);
  };

LabelOverlay.prototype.draw = function() {
  var overlayProjection = this.getProjection();
  
  // Obtener la posición del marcador en píxeles
  var position = overlayProjection.fromLatLngToDivPixel(this.position);

  var div = this.div;
  
  // Añade la etiqueta al DOM para calcular sus dimensiones
  if (!this.div.parentNode) {
    var panes = this.getPanes();
    panes.overlayLayer.appendChild(div);
  }
  
  // Calcular la mitad de la anchura de la etiqueta
  var halfLabelWidth = div.offsetWidth / 2;
  
  // Calcular la posición X de manera que la etiqueta esté centrada sobre el marcador
  // Asumimos que el marcador es de 24x24 px (según tu SVG), así que el centro es 12 px
  div.style.left = (position.x - halfLabelWidth) + 'px'; // Ajustar según sea necesario

  // Ajustar la posición Y si es necesario
  div.style.top = position.y - 30 + 'px';
};

LabelOverlay.prototype.onRemove = function() {
  this.div.parentNode.removeChild(this.div);
  this.div = null;
};

LabelOverlay.prototype.updatePosition = function(position) {
  this.position = position;
  this.draw();
};

LabelOverlay.prototype.updateText = function(text) {
  this.text = text;
  this.div.innerHTML = this.text;
};

// Después de crear el mapa
mapTypeControlUI.value = map.getMapTypeId();

// Crear el switch usando clases de Bootstrap
const switchContainer = document.createElement('div');
switchContainer.style.display = 'flex'; // Activa Flexbox
switchContainer.style.backgroundColor = 'rgba(255, 255, 255, 0.7)'; // Fondo azul claro con opacidad
switchContainer.style.alignItems = 'center'; // Alinea los elementos verticalmente en el centro
switchContainer.style.justifyContent = 'start'; // Alinea los elementos horizontalmente al inicio
switchContainer.classList.add('form-check', 'form-switch', 'mt-2');
switchContainer.style.paddingLeft = '0';

const controlUI = document.createElement('input');
controlUI.type = 'checkbox';
controlUI.classList.add('form-check-input');
controlUI.id = 'storeVisibilitySwitch';
controlUI.style.cursor = 'pointer';

// Añade una etiqueta para el switch
const switchLabel = document.createElement('label');
switchLabel.classList.add('form-check-label');
switchLabel.style.padding = '5px'; // Padding
switchLabel.style.borderRadius = '5px'; // Bordes redondeados
switchLabel.setAttribute('for', 'storeVisibilitySwitch');
switchLabel.innerText = 'Mostrar/Ocultar Tiendas';
switchLabel.style.cursor = 'pointer';

switchContainer.appendChild(controlUI);
switchContainer.appendChild(switchLabel);
mapTypeControlDiv.appendChild(switchContainer);

// Posicionar el switch en el mapa
map.controls[google.maps.ControlPosition.TOP_RIGHT].push(mapTypeControlDiv);

// Almacenar el estado de visibilidad
let isStoresVisible = controlUI.checked;

controlUI.addEventListener('click', () => {
    isStoresVisible = controlUI.checked;
    map.setOptions({styles: isStoresVisible ? null : hideStoresStyle});
});

function createLegend() {
  const legendDiv = document.createElement('div');
  legendDiv.id = 'legend';
  legendDiv.style.padding = '10px';
  legendDiv.style.backgroundColor = 'white';
  legendDiv.style.boxShadow = '0 1px 2px rgba(0,0,0,.1)';

  const title = document.createElement('div');
  title.innerHTML = '<b>Leyenda</b>';
  legendDiv.appendChild(title);

  return legendDiv;
}

const legend = createLegend();
map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

  updateMapMarkers();

  try {

    let deviceInfoArray = await fetchMapData();
    // console.log(deviceInfoArray);
    deviceInfoArray.forEach((deviceInfo) => {
      // Actualiza la información del botón del dispositivo
      // updateDeviceButton(deviceInfo);
      // Actualiza la información en el modal

      const groupName = deviceInfo.groupName; // Agrega esta línea para definir groupName
      // Verifica si el grupo ya tiene un color asignado, si no, asigna uno nuevo
      if (!groupColors[groupName]) {
        // Asigna un color aleatorio al grupo
        groupColors[groupName] = getRandomColor();
      }
      // Verifica si el marcador ya existe en initialMarkers
      if (initialMarkers[deviceInfo.deviceId]) {
        // Actualiza la posición del marcador existente
        const marker = initialMarkers[deviceInfo.deviceId];
        marker.setPosition(new google.maps.LatLng(deviceInfo.latitude, deviceInfo.longitude));

        // Actualiza el contenido del infoWindow
        const infoWindow = marker.infoWindow;
        infoWindow.setContent(createInfoWindowContent(deviceInfo));

        // Actualiza la rotación del marcador para apuntar al curso correcto
        const rotation = parseFloat(deviceInfo.course);
        const adjustedRotation = (rotation % 360 + 360) % 360;
        const groupColor = groupColors[groupName];
        // Obtén el ícono del círculo predeterminado de Google Maps
        const svgString = `
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
          <g transform="rotate(${adjustedRotation}, 12, 12)">
            <circle cx="12" cy="12" r="10" fill="${groupColor}" />
            <path d="M12 2 L16.4 18 L12 14 L7.6 18 Z" fill="white" />
          </g>
        </svg>`;
      
      // Codifica el SVG como una URL de datos
      const svgUrl = "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svgString);
      
      // Crea el ícono con la URL del SVG
      const customIcon = {
        url: svgUrl,
        scaledSize: new google.maps.Size(24, 24), // Tamaño del ícono
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(12, 12) // Punto de anclaje del ícono
      };
      
          // Aplica el ícono combinado al marcador
        marker.setIcon(customIcon);

      } else {
        // Si el marcador no existe, créalo y almacénalo en initialMarkers
        const marker = createMarker(deviceInfo);
        initialMarkers[deviceInfo.deviceId] = marker;

      }
    });
  } catch (error) {
    console.error("Error inesperado", error);
  }

}

 hideStoresStyle = [
  {
      featureType: 'poi.business',
      stylers: [{visibility: 'off'}]
  },
  {
      featureType: 'transit',
      elementType: 'labels.icon',
      stylers: [{visibility: 'off'}]
  }
  // ... puedes agregar más estilos según necesites
];


function selectDevice(DeviceId) {
   // Evitar que el evento del span 'Power' se propague al dispositivo padre
   event.stopPropagation();
    
   // Eliminar la clase 'active' de todos los botones de dispositivo
   $('.dispositivo').removeClass('active');

   // Añadir la clase 'active' al botón que fue clickeado
   $('.dispositivo[data-device-id="' + DeviceId + '"]').addClass('active');

  if (DeviceId === "all") {
    selectedDeviceId = "all";
    map.setCenter({ lat: 25.031984, lng: -111.673107 });
    map.setZoom(7.5);
    if (currentCircle) {
      currentCircle.setMap(null); // Elimina el círculo actual si existe
      currentCircle = null;
    }
    updateMapMarkers();
  } else {
    selectedDeviceId = DeviceId;
    const selectedMarker = initialMarkers[DeviceId];
    
    if (selectedMarker) {
      // Remueve el círculo actual si existe
      if (currentCircle) {
        currentCircle.setMap(null);
        currentCircle = null;
      }

      // Escucha por el evento 'idle' para saber cuando el desplazamiento ha terminado
      google.maps.event.addListenerOnce(map, 'idle', function(){
        // Dibuja el círculo solo después de que el mapa ha terminado de desplazarse
        drawCircle(selectedMarker);
      });

      // Desplaza el mapa a la nueva ubicación del dispositivo seleccionado
      map.panTo(selectedMarker.getPosition());
      map.setZoom(15); // Establece el nivel de zoom deseado
    }
    updateMapMarkers();
  }
}

function drawCircle(marker) {
  const groupName = marker.groupName; // Usa el groupName del marcador
  const groupColor = groupColors[groupName]; // Busca el color basado en groupName

  if (currentCircle) {
    currentCircle.setMap(null); // Elimina el círculo actual si existe
  }

  currentCircle = new google.maps.Circle({
    strokeColor: groupColor,
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: groupColor,
    fillOpacity: 0.35,
    map: map,
    center: marker.getPosition(),
    radius: 100 // Radio del círculo en metros
  });
}

function updateMapMarkers() {
  fetchMapData().then(function (data) {
    deviceData = data;

    var centerCoords = null;

    // Itera sobre los datos del dispositivo
    deviceData.forEach(function (coord) {
      var deviceId = coord.deviceId;
      var groupName = coord.groupName;

      // Verifica si el marcador ya existe en el mapa
      if (initialMarkers[deviceId]) {
        // Actualiza la posición del marcador existente
        const marker = initialMarkers[deviceId];
        marker.setPosition(new google.maps.LatLng(coord.latitude, coord.longitude));

        // Actualiza el contenido del infoWindow si es necesario
        const infoWindow = marker.infoWindow;
        const currentContent = infoWindow.getContent();
        const newContent = createInfoWindowContent(coord);
        if (currentContent !== newContent) {
          infoWindow.setContent(newContent);
        }

        // Actualiza el color del marcador según el groupName
        const markerIcon = marker.getIcon();
        markerIcon.fillColor = groupColors[groupName];
        marker.setIcon(markerIcon);

        if (deviceId == selectedDeviceId) {
          centerCoords = marker.getPosition();
        }


      }
    });

    // Si markerCluster ya existe, destrúyelo antes de crear uno nuevo
    if (markerCluster) {
      markerCluster.clearMarkers(); // Limpia los marcadores existentes
      markerCluster = null; // Marca el objeto como nulo
    }

    // Crea un nuevo MarkerClusterer con los marcadores actualizados
    markerCluster = new markerClusterer.MarkerClusterer({ map, markers: Object.values(initialMarkers) });

    if (centerCoords !== null) {
      smoothPanTo(centerCoords);
    }
  });
}


function degreesToCardinal(degrees) {
  const cardinals = ["N", "NE", "E", "SE", "S", "SO", "O", "NO", "N"];
  const index = Math.floor((degrees / 45) + 0.5);
  return cardinals[index];
}

function getArrowIcon(courseText) {
  const degrees = parseFloat(courseText); // Convertir texto a número
  if (isNaN(degrees)) {
    return ''; // Manejar si no se puede convertir a número
  }

  const arrow = "&#8593;"; // Flecha hacia arriba Unicode
  const rotation = `transform: rotate(${degrees}deg); display: inline-block; transform-origin: center;`;
  return `<span style="font-size: 20px; ${rotation}">${arrow}</span>`;
}

function getLatitudeDirection(latitude) {
  return latitude >= 0 ? 'N' : 'S';
}

function getLongitudeDirection(longitude) {
  return longitude >= 0 ? 'E' : 'O';
}

function createInfoWindowContent(coord) {
  const direction = degreesToCardinal(coord.course);
  const arrow = getArrowIcon(coord.course);
  const latitudeDirection = getLatitudeDirection(coord.latitude);
  const longitudeDirection = getLongitudeDirection(coord.longitude);

  return `
      <table class="table table-sm"> 
          <tr> 
              <th>Nombre:</th> 
              <td class="align-middle">${coord.name}</td> 
          </tr>
          <tr> 
          <tr> 
          <th>Grupo:</th> 
          <td>${coord.groupName}</td> 
      </tr> 
              <th>IMEI:</th> 
              <td>${coord.uniqueId}</td> 
          </tr>
          <tr> 
              <th>Velocidad:</th> 
              <td>${coord.speed} km/h</td> 
          </tr>
          <tr> 
              <th class="align-middle">Dirección:</th> 
              <td class="align-middle">${direction}.   ${arrow}</td>
          </tr>   
          <tr> 
              <th>Latitud:</th> 
              <td>${Math.abs(coord.latitude)} ${latitudeDirection}.</td> 
          </tr> 
          <tr> 
              <th>Longitud:</th> 
              <td>${Math.abs(coord.longitude)} ${longitudeDirection}.</td> 
          </tr> 
      </table>
  `;
}


// Función para limpiar marcadores
function clearMarkers() {
  for (var i = 0; i < markers.length; i++) {
    markers[i].labelOverlay.setMap(null); // Elimina la etiqueta
    markers[i].setMap(null);
  }
  markers = [];
}

function smoothPanTo(target) {
  if (map) {
    var latLng = new google.maps.LatLng(target.lat(), target.lng());
    map.panTo(latLng);
  }
}

// Función que actualiza los datos
async function fetchMapData() {
  try {
    const response = await fetch("arrendamientos/php/scripts/get_latest_data.php"); // Llama al php get_latest_data
    const data = await response.json(); // Codifica los datos en JSON
    return data;
  } catch (error) {
    console.error("Error fetching map data:", error);
    return [];
  }
}

function fetchDeviceIgnition(id) {
  return new Promise(async (resolve, reject) => {
    // Mostrar SweetAlert con tu plantilla de carga
    Swal.fire({
      title: 'Cargando',
      text: 'Cargando función..',
      allowEscapeKey: false,
      allowOutsideClick: false,
      allowEnterKey: false,
      timer: 20000,
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    try {
      const response = await fetch("arrendamientos/php/scripts/get_latest_data.php");
      const data = await response.json();

      // Verificar si data es un objeto
      if (typeof data === 'object' && data !== null) {
        // Buscar el objeto con el id específico
        const device = data.find(item => item.deviceId === id);

        // Verificar si se encontró el objeto
        if (device) {
          console.log('Dispositivo encontrado:', device);
          // Realizar cualquier operación que desees con el objeto encontrado

          // Cerrar SweetAlert después de obtener la respuesta
          Swal.close();

          // Resolver la promesa con el dispositivo
          resolve(device);
        } else {
          console.log(`Dispositivo con id ${id} no encontrado`);
          // Cerrar SweetAlert en caso de no encontrar el dispositivo
          Swal.close();
          reject(`Dispositivo con id ${id} no encontrado`);
        }
      } else {
        console.error('Los datos no son un objeto JSON válido');
        // Cerrar SweetAlert en caso de datos no válidos
        Swal.close();
        reject('Los datos no son un objeto JSON válido');
      }
    } catch (error) {
      console.error("Error fetching map data:", error);
      // Cerrar SweetAlert en caso de error
      Swal.close();
      reject(error);
    }
  });
}

// Plantilla de carga personalizada
function loadingTemplate() {
  return `<span class="loading-wrap">
    <span class="loading-text">CARGANDO</span>
    <span class="animation-wrap"><span class="animation-dot"></span></span>
  </span>`;
}


// Función para mostrar un Swal de confirmación con información del dispositivo y solicitud de contraseña
function showConfirmationSwal(operation, deviceName, imei) {
  return Swal.fire({
    title: 'Confirmar',
    html: `¿Estás seguro de que deseas ${operation} con el dispositivo?<br><br>
           Dispositivo: ${deviceName}<br>
           IMEI: ${imei}<br><br>`,
    input: 'password',
    inputAttributes: {
      placeholder: 'Ingresa la contraseña para continuar'
    },
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: `Sí, ${operation}`,
    cancelButtonText: 'Cancelar',
    preConfirm: (password) => {
      // Verificar la contraseña en el servidor antes de realizar la operación real
      return fetch('arrendamientos/php/scripts/hashed.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          password: password
        })
      })
        .then(response => response.json())
        .then(verifyResult => {
          if (verifyResult.success) {
            return password;  // Si la contraseña es válida, devolverla
          } else {
            Swal.showValidationMessage('La contraseña no es válida');
            return null;  // Indica que la contraseña no es válida
          }
        });
    }
  })
    .then((result) => {
      // La función preConfirm ya se encargó de la verificación, aquí puedes acceder directamente al valor
      return result.value;
    })
    .catch((error) => {
      console.error("Error:", error);
      return null; // Maneja el error según tus necesidades
    });
}


// Función para encender el dispositivo con una promesa y contraseña cifrada
async function encenderDispositivoPromise(id) {
  try {

    const response = await $.ajax({
      type: 'post',
      url: 'https://traccarbcs.com/api/commands/send',
      headers: {
        "Authorization": "Basic " + btoa("centralonappafa@gmail.com:onappafa2019")
      },
      contentType: "application/json",
      data: JSON.stringify({
        deviceId: id,
        type: "engineResume",
      })
    });

    // Verifica que el código de estado sea 202 o la propiedad 'success' sea verdadera
    if (typeof response === 'object' && response !== null) {
      Swal.fire({
        icon: 'success',
        title: 'Reestablecido',
        text: 'El corte de corriente del motor se ha reestablecido correctamente.',
      });

      // Espera 3 segundos antes de resolver la promesa
      return new Promise(resolve => setTimeout(resolve, 3000));
    } else {
      return Promise.reject('Hubo un problema desconocido, el dispositivo es de prueba o no tiene saldo');
    }

  } catch (error) {
    return Promise.reject('Hubo un problema al realizar la operación');
  }
}

// Función para apagar el dispositivo con una promesa y contraseña cifrada
async function apagarDispositivoPromise(id) {
  try {

    // Realizar la operación de apagado aquí
    const response = await $.ajax({
      type: 'post',
      url: 'https://traccarbcs.com/api/commands/send',
      headers: {
        "Authorization": "Basic " + btoa("centralonappafa@gmail.com:onappafa2019")
      },
      contentType: "application/json",
      data: JSON.stringify({
        deviceId: id,
        type: "engineStop",
      })
    });

    // Verifica si la respuesta es un objeto JSON
    if (typeof response === 'object' && response !== null) {
      Swal.fire({
        icon: 'success',
        title: 'Corriente al motor cortada',
        text: 'La corriente al motor se ha cortado correctamente.',
        timer: 3000,
        showConfirmButton: false,
      });

      return new Promise(resolve => setTimeout(resolve, 3000));
    } else {
      return Promise.reject('La respuesta no es un objeto JSON válido');
    }

  } catch (error) {
    return Promise.reject('Hubo un problema al realizar la operación');
  }
}

// Función para mostrar un Swal de carga
function showLoadingSwal(message) {
  return Swal.fire({
    title: message,
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
}




async function encenderOApagarDispositivo(id) {
  // Obtener la información del dispositivo
  const device = await fetchDeviceIgnition(id);

  // Verificar si se encontró el dispositivo
  if (!device) {
    Swal.fire('Error', 'No se pudo obtener información del dispositivo', 'error');
    return;
  }

  // Determinar la operación actual en función de ignition
  const operation = device.ignition ? 'bloquear el motor' : 'desbloquear el motor';

  // Mostrar el Swal de confirmación con la operación actual
  const confirmationResult = await showConfirmationSwal(operation, device.name, device.uniqueId);

  if (confirmationResult !== undefined && confirmationResult !== null) {
    // Mostrar el Swal de carga con el mensaje adecuado
    const loadingSwal = showLoadingSwal(`Cargando ${operation}...`);

    try {
      // Ejecutar la operación correspondiente
      if (device.ignition) {
        // Si ignition es true, ejecutar la función para apagar
        await apagarDispositivoPromise(id, confirmationResult);
      } else {
        // Si ignition es false, ejecutar la función para encender
        await encenderDispositivoPromise(id, confirmationResult);
      }
    } catch (error) {
      console.error("Ocurrió un error:", error);
      // Ocurrió un error, muestra un Swal de error
      Swal.fire('Error', 'Hubo un problema al realizar la operación', 'error');
    } finally {
      // Cierra el Swal de carga al finalizar la operación
      Swal.close();
    }
  } else {
    // El usuario canceló la operación o hubo un error
    Swal.fire('Operación cancelada', 'No se realizaron cambios', 'info');
  }
}



function updateModalInformation(deviceInfo) {
  const modalId = '#Modal' + deviceInfo.deviceId;
  const modalTitleElement = document.querySelector(modalId + ' .modal-title');
  const modalContentTable = document.querySelector(modalId + ' .modal-body table');

  const direction = degreesToCardinal(deviceInfo.course);
  const arrow = getArrowIcon(deviceInfo.course);
  const latitudeDirection = getLatitudeDirection(deviceInfo.latitude);
  const longitudeDirection = getLongitudeDirection(deviceInfo.longitude);

  if (modalTitleElement && modalContentTable) {
    // Actualiza el título del modal con el nombre del dispositivo
    modalTitleElement.textContent = deviceInfo.name;

    // Calcula la fila de la batería
    const batteryRow = deviceInfo.batteryLevel
      ? `<tr>
           <th>Batería:</th>
           <td>${deviceInfo.batteryLevel}%</td>
         </tr>`
      : '';

    // Calcula la fila del motor (encendido, apagado o no disponible)
    const ignitionStatusRow = `
      <tr>
        <th>Motor:</th>
        <td>${deviceInfo.ignition !== null ?
        deviceInfo.ignition
          ? 'Encendido'
          : 'Apagado'
        : 'No disponible'
      }</td>
      </tr>
    `;

    // Actualiza el contenido de la tabla con la nueva información
    modalContentTable.innerHTML = `
      <tr>
        <th>ID del dispositivo:</th>
        <td class="align-middle">${deviceInfo.deviceId}</td>
      </tr>
      <tr>
        <th>IMEI:</th>
        <td class="align-middle">${deviceInfo.uniqueId}</td>
      </tr>
      <tr>
        <th>Grupo:</th>
        <td class="align-middle">${deviceInfo.groupName}</td>
      </tr>
      <tr>
        <th>Latitud:</th>
        <td>${deviceInfo.latitude} ${latitudeDirection}.</td>
      </tr>
      <tr>
        <th>Longitud:</th>
        <td>${deviceInfo.longitude} ${longitudeDirection}.</td>
      </tr>
      <tr>
        <th>Curso:</th>
        <td>${deviceInfo.course}° ${direction}. ${arrow}</td>
      </tr>
      <tr>
        <th>Velocidad:</th>
        <td>${(deviceInfo.speed * 1.852).toFixed(2)} km/h - ${deviceInfo.speed} nudos</td>
      </tr>
      ${ignitionStatusRow}
      ${batteryRow}
    `;
  }
}


/*var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})*/

function updateIgnitionStatusImage(deviceInfo) {
  const ignitionStatus = deviceInfo.ignition;
  const imgElement = document.querySelector('.pwr[data-device-id="' + deviceInfo.deviceId + '"]');

  /*console.log('Device ID:', deviceInfo.deviceId);
  console.log('Ignition Status:', ignitionStatus);
  console.log('Image Element:', imgElement);*/

  if (imgElement) {
    // Almacena los atributos existentes
    const existingAlt = imgElement.alt;
    const existingTitle = imgElement.title;

    const newSrc = ignitionStatus ? 'arrendamientos/images/icons/on.svg' : 'arrendamientos/images/icons/off.svg';
    //console.log('New Src:', newSrc);

    // Actualiza la imagen
    imgElement.src = newSrc;
    imgElement.alt = 'power';
    imgElement.title = ignitionStatus ? 'Encendido' : 'Apagado';
    imgElement.setAttribute('data-bs-toggle', 'tooltip');
    imgElement.setAttribute('data-bs-placement', 'top');

    // Destruye el tooltip existente y crea uno nuevo
    const existingTooltip = bootstrap.Tooltip.getInstance(imgElement);
    if (existingTooltip) {
      existingTooltip.dispose();
    }

    // Crea un nuevo tooltip
    new bootstrap.Tooltip(imgElement);

    // Muestra el tooltip (puedes ajustar esto según tus necesidades)
    imgElement.setAttribute('data-bs-original-title', imgElement.title);
    imgElement.setAttribute('title', imgElement.title);
    imgElement.setAttribute('aria-label', imgElement.title);
    imgElement.setAttribute('data-bs-content', imgElement.title);
    imgElement.setAttribute('data-bs-trigger', 'hover focus');
  }
}

function updateDeviceButton(deviceInfo) { //EN DESARROLLO
  const deviceButtonElement = document.querySelector(`.dispositivo[data-device-id="${deviceInfo.deviceId}"]`);
  if (deviceButtonElement) {
    // Actualiza el nombre del dispositivo y otros detalles según sea necesario
    const deviceNameElement = deviceButtonElement.querySelector('.Nombre');
    const groupname = deviceButtonElement.querySelector('.Grupo');

    if (deviceNameElement) {
      deviceNameElement.textContent = deviceInfo.name;
      groupname.textContent = deviceInfo.groupName;
    }

  } else {

    deviceButtonElement.remove();

  }

}


socket.addEventListener("message", (event) => {

  try {
    let deviceInfoArray = JSON.parse(event.data);
    // console.log(deviceInfoArray);
    deviceInfoArray.forEach((deviceInfo) => {
      // Actualiza la información del botón del dispositivo
      // updateDeviceButton(deviceInfo);
      // Actualiza la información en el modal
      updateModalInformation(deviceInfo);
      // Verifica y establece la imagen de "on" y "off" según el estado de ignition
      updateIgnitionStatusImage(deviceInfo);
      const groupName = deviceInfo.groupName; // Agrega esta línea para definir groupName
      // Verifica si el grupo ya tiene un color asignado, si no, asigna uno nuevo
      if (!groupColors[groupName]) {
        // Asigna un color aleatorio al grupo
        groupColors[groupName] = getRandomColor();
      }
   

      // Verifica si el marcador ya existe en initialMarkers
      if (initialMarkers[deviceInfo.deviceId]) {
        // Actualiza la posición del marcador existente

        
        const marker = initialMarkers[deviceInfo.deviceId];
         // Actualiza la posición del marcador
         marker.setPosition(new google.maps.LatLng(deviceInfo.latitude, deviceInfo.longitude));
         marker.labelOverlay.updatePosition(marker.getPosition());
         marker.labelOverlay.updateText(deviceInfo.name); // Si el nombre del dispositivo puede cambiar
        // Actualiza el contenido del infoWindow
        const infoWindow = marker.infoWindow;
        infoWindow.setContent(createInfoWindowContent(deviceInfo));

      
       if (deviceInfo.deviceId == selectedDeviceId) {
        // Mueve el círculo al nuevo marcador
        if (currentCircle) {
          currentCircle.setCenter(marker.getPosition());
        } else {
          // Si no hay un círculo actual, dibuja uno nuevo
          drawCircle(marker);
        }
      }
        // Actualiza la rotación del marcador para apuntar al curso correcto
        const rotation = parseFloat(deviceInfo.course);
        const adjustedRotation = (rotation % 360 + 360) % 360;
        const groupColor = groupColors[groupName];
        //console.log(`Asignando color ${groupColor} a marcador con groupName ${groupName}`);
        const svgString = `
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
          <g transform="rotate(${adjustedRotation}, 12, 12)">
            <circle cx="12" cy="12" r="10" fill="${groupColor}" />
            <path d="M12 2 L16.4 18 L12 14 L7.6 18 Z" fill="white" />
          </g>
        </svg>`;
      // Codifica el SVG como una URL de datos
      const svgUrl = "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svgString);
      
      // Crea el ícono con la URL del SVG
      const customIcon = {
        url: svgUrl,
        scaledSize: new google.maps.Size(24, 24), // Tamaño del ícono
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(12, 12) // Punto de anclaje del ícono
      };
      
          // Aplica el ícono combinado al marcador
        marker.setIcon(customIcon);


      } else {
        // Si el marcador no existe, créalo y almacénalo en initialMarkers
        const marker = createMarker(deviceInfo);
        initialMarkers[deviceInfo.deviceId] = marker;

      }
    });
  } catch (error) {
    console.error("Error inesperado", error);
  }
});

// Función para obtener un color hexadecimal aleatorio
function getRandomColor() {
  const letters = "0123456789ABCDEF";
  let color = "#";
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}


function resetAddedGroups() {
  addedGroups = {};
}

function sanitizeGroupName(groupName) {
  return groupName.replace(/[^a-zA-Z0-9-]/g, '_');
}
function addToLegend(groupName, groupColor) {
  // Sanitizar el nombre del grupo para crear un ID válido
  const sanitizedGroupName = sanitizeGroupName(groupName);
  
  // Verifica si la leyenda ya existe en el DOM
  const existingLegendItem = document.querySelector(`#legend-item-${sanitizedGroupName}`);
  
  if (existingLegendItem) {
    // La leyenda ya existe, actualiza su color si es necesario
    const existingColorBox = existingLegendItem.querySelector('.color-box');
    if (existingColorBox && existingColorBox.style.backgroundColor !== groupColor) {
      existingColorBox.style.backgroundColor = groupColor;
    }
  } else {
    // La leyenda no existe, créala
    const legend = document.getElementById('legend');
    const colorBox = document.createElement('span');
    colorBox.className = 'color-box';
    colorBox.style.backgroundColor = groupColor;
    colorBox.style.width = '12px';
    colorBox.style.height = '12px';
    colorBox.style.display = 'inline-block';
    colorBox.style.marginRight = '8px';
    colorBox.style.border = '1px solid #ccc';

    const label = document.createElement('span');
    label.textContent = groupName;

    const item = document.createElement('div');
    item.id = `legend-item-${sanitizedGroupName}`; // Asigna un ID único basado en el nombre del grupo
    item.appendChild(colorBox);
    item.appendChild(label);

    legend.appendChild(item);

    // Marcar el grupo como añadido (opcional si no usas addedGroups para otra lógica)
    addedGroups[groupName] = true;
  }
}


function createMarker(coord) {
  
  const groupName = coord.groupName;

  // Obtener el color del grupo del objeto groupColors
  const groupColor = groupColors[groupName]; // Obtén el color directamente del objeto groupColors

  // Añadir a la leyenda si es necesario
addToLegend(coord.groupName, groupColors[coord.groupName]);

  const rotation = parseFloat(coord.course);
  const adjustedRotation = (rotation % 360 + 360) % 360; // Ajusta el ángulo al rango [0, 360)
  // Crea la etiqueta para el marcador
  var labelOverlay = new LabelOverlay(
    new google.maps.LatLng(coord.latitude, coord.longitude),
    coord.name, // Texto de la etiqueta
    map
  );

  const svgString = `
  <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
    <g transform="rotate(${adjustedRotation}, 12, 12)">
      <circle cx="12" cy="12" r="10" fill="${groupColor}" />
      <path d="M12 2 L16.4 18 L12 14 L7.6 18 Z" fill="white" />
    </g>
  </svg>`;

// Codifica el SVG como una URL de datos
const svgUrl = "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svgString);

// Crea el ícono con la URL del SVG
const customIcon = {
  url: svgUrl,
  scaledSize: new google.maps.Size(24, 24), // Tamaño del ícono
  origin: new google.maps.Point(0, 0),
  anchor: new google.maps.Point(12, 12) // Punto de anclaje del ícono
};

// Crea el marcador con el ícono personalizado
const marker = new google.maps.Marker({
  position: {
    lat: parseFloat(coord.latitude),
    lng: parseFloat(coord.longitude),
  },
  groupName: coord.groupName, // Asegúrate de incluir esta propiedad
  map: map,
  icon: customIcon
});


  // Asocia la etiqueta con el marcador
  marker.labelOverlay = labelOverlay;

  marker.infoWindow = new google.maps.InfoWindow({
    content: createInfoWindowContent(coord),
  });

  marker.addListener("click", function () {
    marker.infoWindow.open(map, marker);
  });

  marker.addListener("mouseover", function () {
    marker.infoWindow.open(map, marker);
  });

  marker.addListener("mouseout", function () {
    marker.infoWindow.close();
  });


  return marker;
}


