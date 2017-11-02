
var data=[];
var tiempo ;
var luz=[];
var temperatura=[];
var rocio=[];
var luz_datos1=[];
var json_polar =[];
var datos;
var media_luz;
var media_luz_anteriores;
var fechas_anteriores;                                                                                                                                                                                                                                                                            
var etiquetas = luz.length;
var dato_luz_actual;
var conteo_color=['rgba(255,250,205,0.5)','rgba(255,215,0, 0.2)'];
var par='rgba(255,250,205,0.5)';
var impar='rgba(255,215,0, 0.2)';
//// DEFINICION DE LOS GRAFICOS
var ctx_luz = $("#myChart_luz").get(0).getContext("2d");
var ctx_luz_anteriores = $("#myChart_luz_anteriores").get(0).getContext("2d");
var ctx_temperatura = $("#myChart_temperatura").get(0).getContext("2d");
var ctx_temperatura_anteriores= $("#myChart_temperatura_anteriores").get(0).getContext("2d");
var ctx_polar= $("#myChart_polar").get(0).getContext("2d");
var activo = false;
var activo1 = false;

///////DATOS INICIALES DE LOS GRAFICOS

///////////////LUZ//////////////////////////////////////////////////
var data_luz = {
			labels: ["DATOS"],
			datasets: [{
				label: "PORCENTAJE DE LUZ",
				lineTension: 0,
				fill:true,
				backgroundColor: "rgba(255,255,0,0.4)",
				borderColor: "rgba(0,0,0,0.5)",
				borderCapStyle: 'round',
				borderDash: [],
				borderDashOffset: 1.0,
				borderJoinStyle: 'miter',
				pointBorderColor: "rgba(0,0,0,1)",
				pointBackgroundColor: "#fff",
				pointBorderWidth: 1,
				pointHoverRadius: 5,
				pointHoverBackgroundColor: "rgba(255,255,0,0.5)",
				pointHoverBorderColor: "rgba(0,0,0,1)",
				pointHoverBorderWidth: 1,
				pointRadius: 0.1,
				pointHitRadius: 5,
				data: [0],
				spanGaps: true,
						}]
				};
	
	var data_luz_anteriores = {
		labels: ["DATOS ACUMULADOS"],
		datasets: [
			{
				label: "Datos acumulados Luz",
				backgroundColor: [0,0,0,1],
				borderWidth: 3,
				data: [0],
			}
		]
	};
/////////////TEMPERATURA /////////////////////////

var data_temperatura = {
			labels: ["DATOS"],
			datasets: [{
					label: "Rocio",
					lineTension: 0,
					fill:true,
					backgroundColor: "rgba(102,255,255,0.9)",
					borderColor: "rgba(0,0,0,1)",
					borderCapStyle: 'round',
					borderDash: [],
					borderDashOffset: 1.0,
					borderJoinStyle: 'miter',
					pointBorderColor: "rgba(0,0,0,1)",
					pointBackgroundColor: "#fff",
					pointBorderWidth: 1,
					pointHoverRadius: 5,
					pointHoverBackgroundColor: "rgba(102,255,255,0.9)",
					pointHoverBorderColor: "rgba(0,0,0,1)",
					pointHoverBorderWidth: 1,
					pointRadius: 0.1,
					pointHitRadius: 5,
					data: [0],
					spanGaps: true,
						},{
					label: "Temperatura",
					lineTension: 0,
					fill:true,
					backgroundColor: "rgba(255,51,51,0.8)",
					borderColor: "rgba(0,0,0,1)",
					borderCapStyle: 'round',
					borderDash: [],
					borderDashOffset: 1.0,
					borderJoinStyle: 'miter',
					pointBorderColor: "rgba(0,0,0,1)",
					pointBackgroundColor: "#fff",
					pointBorderWidth: 1,
					pointHoverRadius: 5,
					pointHoverBackgroundColor: "rgba(255,51,51,0.5)",
					pointHoverBorderColor: "rgba(0,0,0,1)",
					pointHoverBorderWidth: 1,
					pointRadius: 0.1,
					pointHitRadius: 5,
					data: [0],
					spanGaps: true,
						},
				{
					label: "Humedad Relativa",
					lineTension: 0,
					fill:true,
					backgroundColor: "rgba(0,128,255,0.5)",
					borderColor: "rgba(0,0,0,1)",
					borderCapStyle: 'round',
					borderDash: [],
					borderDashOffset: 1.0,
					borderJoinStyle: 'miter',
					pointBorderColor: "rgba(0,0,0,1)",
					pointBackgroundColor: "#fff",
					pointBorderWidth: 1,
					pointHoverRadius: 5,
					pointHoverBackgroundColor: "rgba(0,128,255,0.5)",
					pointHoverBorderColor: "rgba(0,0,0,1)",
					pointHoverBorderWidth: 1,
					pointRadius: 0.1,
					pointHitRadius: 5,
					data: [0],
					spanGaps: true,
						}
					
						]
				};
				
	var data_temperatura_anteriores = {
		labels: ["DATOS ACUMULADOS"],
		datasets: [
			{
				label: "Datos acumulados Rocio",
				backgroundColor: "rgba(0,204,204,1)",
				borderColor: "rgba(0,0,0,1)",
				borderWidth: 3,
				data: [0],
			},
			{
				label: "Datos acumulados Temperatura",
				backgroundColor: "rgba(204,0,0,1)",
				borderColor: "rgba(0,0,0,1)",
				borderWidth: 3,
				data: [0],
			}
			,{
				label: "Datos acumulados Humedad",
				backgroundColor: "rgba(0,0,204,1)",
				borderColor: "rgba(0,0,0,1)",
				borderWidth: 3,
				data: [0],
			}
		]
	};
	
	/////////////////////////////////////POLAR
	  var chartColors = window.chartColors;
	    var color = Chart.helpers.color;
	data_polar = {
		labels: ['Luz','Temperatura','Humedad','Rocio'],
		datasets: [{
			label:['Luz','Temperatura','Humedad','Rocio'],
			data: [0,0,0,0],
			backgroundColor: [ "rgba(255,255,0,0.4)","rgba(255,51,51,0.8)","rgba(0,128,255,0.5)", "rgba(102,255,255,0.9)"],
			}]
		
	};
	
////////////////////////CREACION DE LOS GRAFICOS///////////////////////////////////////////////////////
			
var myChart = new Chart(ctx_luz, {
    type: 'line',
    data: data_luz,
     scales: {
		xAxes: [{
		  scaleLabel: {
			display: true,
			labelString: 'probability'
      }
    }]
  }

    
});

var myBarChart = new Chart(ctx_luz_anteriores, {
    type: 'bar',
    data: data_luz_anteriores,
});

var myChart_temperatura = new Chart(ctx_temperatura, {
    type: 'line',
    data: data_temperatura,
    options:{
		responsive:true
		}
       
});

var myBarChart_temperatura = new Chart(ctx_temperatura_anteriores, {
    type: 'bar',
    data: data_temperatura_anteriores,
});


var myChart_polar =  new Chart(ctx_polar, {
    type: 'polarArea',
    data: data_polar,
});
/////////////////////////////////// BOTONES 

$(document).ready(function(){
	
	$("#boton").click(function() {
		
		if (activo == false){
			 $( "#contenedor_oculto" ).slideUp();
			 activo = true;
		}else{
			$( "#contenedor_oculto" ).slideDown();
			activo = false;
			}
	});
	
	$("#boton1").click(function() {
		
		if (activo1 == false){
			 $( "#contenedor_oculto1" ).slideUp();
			 activo1 = true;
		}else{
			$( "#contenedor_oculto1" ).slideDown();
			activo1 = false;
			}
	});
	
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
				//Datos de Graficas
				var luz1=datos[0];
				luz =JSON.parse(luz1);
				var temperatura1=datos[9];
				temperatura=JSON.parse(temperatura1);
				var humedad1=datos[10];
				humedad=JSON.parse(humedad1);
				var rocio1=datos[11];
				rocio=JSON.parse(rocio1);
				//////////Datos de tiempo
				var tiempo1=datos[1];
				tiempo= JSON.parse(tiempo1);
				var fechas_anteriores1=datos[4];
				fechas_anteriores=JSON.parse(fechas_anteriores1);
				/////////Datos de Medias y actuales
				var media_luz_anteriores1=datos[3];
				media_luz_anteriores=JSON.parse(media_luz_anteriores1);
				var dato_luz_actual1= datos[5];
				dato_luz_actual=dato_luz_actual1;
				media_luz=datos[2];
				media_temperatura=datos[6];
				media_humedad=datos[7];
				temperatura_actual=datos[8];
				humedad_actual=datos[15];
				///////DATOS DE DIAS ANTERIORES
				var media_temperatura_anteriores1=datos[12];
				media_temperatura_anteriores=JSON.parse(media_temperatura_anteriores1);
				var media_humedad_anteriores1=datos[13];
				media_humedad_anteriores=JSON.parse(media_humedad_anteriores1);
				var media_rocio_anteriores1=datos[14];
				media_rocio_anteriores=JSON.parse(media_rocio_anteriores1);
				var json_polar_datos=datos[16];
				json_polar = JSON.parse(json_polar_datos);
				var dato_lluvia_actual1= datos[17];
				dato_lluvia_actual=dato_lluvia_actual1;
				
		}
		});
		return [luz,tiempo,media_luz,media_luz_anteriores,fechas_anteriores,dato_luz_actual,temperatura,humedad,rocio,media_temperatura,media_humedad,temperatura_actual,media_temperatura_anteriores,media_humedad_anteriores,media_rocio_anteriores,humedad_actual,json_polar,dato_lluvia_actual];
}

function telegram(){
	$.ajax({                
		url:   'telegram.php',
		type:  'post',
		async: false, 
		success:function (response) {
			console.log("TELEGRAMEANDO LOCO!");
		}
	});
}

 setInterval(function(){
	var datos = nuevodatos();
	telegram();
	$("#media_luz").text(datos[2]);
	$("#luz_actual_dato").text(datos[5]);
	$("#media_temperatura").text(datos[9]);
	$("#temperatura_actual_dato").text(datos[11]);
	$("#humedad_actual_dato").text(datos[15]);
	$("#dato_lluvia_actual").text(datos[17]);
	
	////LLUVIA
	if (datos[17]<35){
		console.log("NO LLUEVE");
		//$("dato_lluvia_actual").addClass("seco");
	}
	if (datos[17]>35 && datos[17] <75 ){
		console.log("LLUEVE POCO");
		//$("dato_lluvia_actual").addClass("poca_lluvia");
	}
	if (datos[17]>75){
		console.log("LLUEVE");
		//$("dato_lluvia_actual").addClass("lluvia_intensa");
	}
	
	/////COLORES PARA LOS DIAS ANTERIORES DE LUZ
	var conteo_luz= datos[4].length;
	var contador_color= conteo_color.length;
	if(contador_color < conteo_luz){
		for(i=0;i < conteo_luz;i++){
			if(i % 2 == 1){
				conteo_color.push(impar);
			}else if(i % 2 == 0){
				conteo_color.push(par);		
			};
		 };
	}
	
	myChart.data.labels=datos[1];
	myChart.data.datasets[0].data=datos[0];
	
	myBarChart.data.labels=datos[4];
	myBarChart.data.datasets[0].data=datos[3];
	myBarChart.data.datasets[0].backgroundColor=conteo_color;
	
	myChart_temperatura.data.labels=datos[1];
	myChart_temperatura.data.datasets[1].data=datos[6];
	myChart_temperatura.data.datasets[2].data=datos[7];
	myChart_temperatura.data.datasets[0].data=datos[8];
	
	myBarChart_temperatura.data.labels=datos[4];
	myBarChart_temperatura.data.datasets[0].data=datos[14];
	myBarChart_temperatura.data.datasets[1].data=datos[12];
	myBarChart_temperatura.data.datasets[2].data=datos[13];
	
	myChart_polar.data.datasets[0].data=datos[16];
	
    myChart.update();
    myBarChart.update();
    myChart_temperatura.update();
    myBarChart_temperatura.update();
    myChart_polar.update();

      
}, 20000 );


