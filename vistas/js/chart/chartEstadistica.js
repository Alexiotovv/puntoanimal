const urlajax = $('.pageEstadistica').attr("server");
// console.log("MI SERVE PAG ESTADISTICA: "+urlajax)

$(document).ready(function() {
    if(typeof(urlajax) != "undefined"){
        // console.log("MI SERVE: "+urlajax)
        chartEspecie();
        chartSexo();
        chartRaza();
    }
    
});

/*--- Cargar Total de cada Especies en chart */
function chartEspecie(){
    $.ajax({
        url: urlajax+"ajax/mascotaAjax.php",
        method: "POST",
        dataType: 'json',
        data: {
            "estvalor": "ConteoEspecie"
          },
        success: function(data) {
            // console.log(data);
            var especie = [];
            var total = [];
            var color = [];

            for (var i in data) {
                especie.push(data[i][0]+" ("+data[i][2]+")");
                total.push(data[i][2]);
                color.push(getRandomColorRgb(120,240,120,240,120,240));
            }

            var chartdata = {
                labels: especie,
                datasets: [{
                    label: especie,
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 2,
                    hoverBackgroundColor: color,
                    data: total
                }]
            };

            var mostrar = document.getElementById("myPieChartEspecie");

            var grafico = new Chart(mostrar, {
                type: 'doughnut',
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels:{
                            fontColor: '#858796',
                        }
                    }
                }
            });
        }
    });

}

/*--- Cargar total por sexo ---*/
function chartSexo(){
    $.ajax({
        url: urlajax+"ajax/mascotaAjax.php",
        dataType: 'json',
        method: "POST",
        data: {
            "estvalor": "ConteoSexo"
          },
        success: function(data) {
            // console.log(data);
            var sexo = [];
            var total = [];
            var color = ['rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)'];
            var bordercolor = ['rgba(255,99,132,1)','rgba(54, 162, 235, 1)'];

            for (var i in data) {
                total.push(data[i][1]);
                sexo.push(data[i][0]+" ("+data[i][1]+")");
            }
            
            var chartdata = {
                labels: sexo,
                datasets: [{
                    label: sexo,
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 2,
                    hoverBackgroundColor: color,
                    hoverBorderColor: bordercolor,
                    data: total
                }]
            };

            var mostrar = document.getElementById("myPieSexo");

            var grafico = new Chart(mostrar, {
                type: 'doughnut',
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels:{
                            fontColor: '#858796',
                        }
                    }
                }
            });
        }
    });
}

/*-- Cargar Total por cada raza --*/
function chartRaza(){
    $.ajax({
        url: urlajax+"ajax/mascotaAjax.php",
        method: "POST",
        dataType: 'json',
        data: {
            "estvalor": "ConteoRaza"
          },
        success: function(data) {
            // console.log(data);
            var raza = [];
            var total = [];
            var color = [];

            for (var i in data) {
                raza.push(data[i][0]);
                total.push(data[i][2]);
                color.push(getRandomColorRgb(120,240,120,240,120,240));
            }

            var chartdata = {
                labels: raza,
                datasets: [{
                    label: "Raza",
                    backgroundColor: color,
                    borderWidth: 1,
                    data: total
                }]
            };

            var mostrar = document.getElementById("myBarChartRaza");

            var grafico = new Chart(mostrar, {
                type: 'horizontalBar',
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: "Distribución por raza "
                    },
                    scales: {
                      xAxes: [{
                        ticks: {
                            beginAtZero: true
                        },

                      }],
                      yAxes: [{
                        stacked: true,
                        gridLines: {
                          color: "rgb(234, 236, 244)",
                          zeroLineColor: "rgb(234, 236, 244)",
                          drawBorder: false,
                          borderDash: [2],
                          zeroLineBorderDash: [2]
                        }
                       
                      }],
                    },
                    
                  }
            });
        }
    });
}

/*-- Generar color aleatorio todos claros */
function getRandomColorRgb(minR,maxR,minG,maxG,minB,maxB){
    colorR = Math.floor(Math.random()* maxR) + minR;
    colorG = Math.floor(Math.random()* maxG) + minG;
    colorB = Math.floor(Math.random()* maxB) + minB;
    var color = '';
     color = color.concat('rgb(',colorR,',',colorG,',',colorB,')');
     return color;
}

/* ---Imprimir PDF de Charts-----*/
$('.btn_imprimir_pdf').click(function(e) {
    // console.log("id de CHART:  "+$(this).val());
    var chart = $(this).val();

    var ncanvas = document.getElementById(""+chart+"");
    // crear imagen base64
    var img = ncanvas.toDataURL("image/png");
    // asignar en input 
    $(this).parent().children('#base64').val(img);
    // console.log(ncanvas);
    generarPoput();
});

/* ventana mostrar el PDF */
function generarPoput() {
  var ancho = 700;
  var alto = 800;
  // //calcular posicion x, y para centrar la ventana
  var x = parseInt((window.screen.width/2) - (ancho / 2));
  var y = parseInt((window.screen.height/2) - (alto / 2));
  window.open("about:blank","print_poput","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}
