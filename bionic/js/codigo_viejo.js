var data=[];
var tiempo ;
var luz=[];
var luz_datos1=[];
var datos;
var media_luz;
var media_luz_anteriores;
var fechas_anteriores;                                                                                                                                                                                                                                                                            
var etiquetas = luz.length;
//// DEFINICION DE LOS GRAFICOS
var ctx_luz = $("#myChart_luz").get(0).getContext("2d");
var ctx_luz_anteriores = $("#myChart_luz_anteriores").get(0).getContext("2d");

///////DATOS INICIALES DE LOS GRAFICOS
var data_luz = {
			labels: ["DATOS"],
			datasets: [{
            label: "PORCENTAJE DE LUZ",
            lineTension: 1,
            fill:true,
            backgroundColor: "rgba(245,19,19,0.4)",
            borderColor: "rgba(0,0,0,1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 1.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(0,0,0,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(245,19,19,0.5)",
            pointHoverBorderColor: "rgba(0,0,0,1)",
            pointHoverBorderWidth: 1,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [0],
            spanGaps: true,
						}]
				};
	

//////GRÁFICO DE BARRAS PARA LUZ
	var data_luz_anteriores = {
		labels: ["DATOS ACUMULADOS"],
		datasets: [
			{
				label: "Datos acumulados Luz",
				backgroundColor: [0,0,0,1],
				borderColor: [0,0,0,1],
				borderWidth: 3,
				data: [0],
			}
		]
	};

/////CREACION DE LOS GRAFICOS
			
var myChart = new Chart(ctx_luz, {
    type: 'line',
    data: data_luz,
     options: {
        responsive: true
    }
    
});

var myBarChart = new Chart(ctx_luz_anteriores, {
    type: 'bar',
    data: data_luz_anteriores,
});

//////////////////////////////////DATOS FRECUENCIA/////////////////////////////////////////////////////
function nuevodatos(){
$.ajax({                
	url:   'descargadatos.php',
	type:  'post',
	async: false, 
	success:function (response) {
			var json_datos = response;
			datos = json_datos.split('%');
			var luz1=datos[0];
			luz =JSON.parse(luz1);
			var tiempo1=datos[1]
			tiempo= JSON.parse(tiempo1);
			media_luz=datos[2];
			var media_luz_anteriores1=datos[3];
			media_luz_anteriores=JSON.parse(media_luz_anteriores1);
			var fechas_anteriores1=datos[4];
			fechas_anteriores=JSON.parse(fechas_anteriores1);
	}
	});
	return [luz,tiempo,media_luz,media_luz_anteriores,fechas_anteriores];
}



 setInterval(function(){
	 var datos = nuevodatos();
	 $("#media_luz").val(datos[2]);
	 /////COLORES PARA LOS DIAS ANTERIORES DE LUZ
	var conteo_luz= datos[4].length;
	var conteo_color=['rgba(255, 99, 132, 0.3)','rgba(54, 162, 235, 0.3)','rgba(153, 102, 255, 0.3)'];
	var par='rgba(255, 206, 86, 0.3)';
	var impar='rgba(75, 192, 192, 0.3)';
	
	for(i=0;i < conteo_luz;i++){
		if(conteo_luz % 2 == 1){
			conteo_color.push(impar);
		}else if(conteo_luz % 2 == 0){
			conteo_color.push(par);		
		};
	};
	myChart.data.labels=datos[1];
	myChart.data.datasets[0].data=datos[0];
	myBarChart.data.labels=datos[4];
	myBarChart.data.datasets[0].data=datos[3];
	myBarChart.data.datasets[0].backgroundColor=conteo_color;
	
    myChart.update();
    myBarChart.update();
    
}, 3000
);


