ajaxGet("arrendamientos/php/querys/week_graph.php")
  .then((r) => {
    if (isJsonString(r)) {
      const data = JSON.parse(r);
      const datasets = [];
    

      if (data['LA PAZ']) {
        datasets.push({
          label: 'LA PAZ',
          data: data['LA PAZ'],
          lineTension: 0.25,
          backgroundColor: "transparent",
          borderColor: "#007bff",
          borderWidth: 4,
          pointBackgroundColor: "#007bff",
        });
      }
      
      if (data['SAN JOSE DEL CABO']) {
        datasets.push({
          label: 'SAN JOSÉ DEL CABO',
          data: data['SAN JOSE DEL CABO'],
          lineTension: 0.25,
          backgroundColor: "transparent",
          borderColor: "#dc3545",
          borderWidth: 4,
          pointBackgroundColor: "#dc3545",
        });
      }

      const ctx = document.getElementById("myChart");
      const myChart = new Chart(ctx, {
          type: "line",
          data: {
              labels: days, // Asume que `days` es un array con las etiquetas del eje X
              datasets: datasets // Tus conjuntos de datos
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          callback: function(value) {
                              return '$' + value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + ' MXN';
                          }
                      }
                  }]
              },
              tooltips: {
                  mode: 'index',
                  intersect: false,
                  callbacks: {
                      // Personaliza el texto del tooltip para mostrar la suma
                      footer: function(tooltipItems, data) {
                          let suma = 0;
                          tooltipItems.forEach(function(tooltipItem) {
                              suma += tooltipItem.yLabel;
                          });
                          return 'Utilidad Neta: $' + suma.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + ' MXN';
                      }
                  }
              },
              legend: {
                  display: true
              },
              // Asegúrate de tener la configuración necesaria para el plugin datalabels si lo estás utilizando
              plugins: {
                  datalabels: {
                      align: 'end',
                      anchor: 'end',
                      font: {
                          weight: 'bold',
                          size: 12
                      },
                      formatter: function(value) {
                          return '$' + value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + ' MXN';
                      }
                  }
              }
          }
      });
    } else {
      // Manejo de errores o respuestas no JSON
    }
  })
  .catch(function (r) {
    console.log(`Error: ${r}`);
  });
