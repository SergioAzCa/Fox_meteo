<?php
//{"Luz:":62,"data":["datos_prueba",5.232323]}
include 'conexion.php';
$json_polar=[];



//LUZ
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//DATOS DE LUZ
$luz=[];
$media_luz_anteriores=[];
$fecha_anteriores=[];
$fecha=date("Y-m-d");
$result_luz = pg_query($conn,"select luz from pruebas_conn.datos where fecha = '".$fecha."' order by id");
if ($result_luz){
    while ($row_luz= pg_fetch_row($result_luz)) {
	  array_push($luz,$row_luz[0]);
	  
	}
	$json_luz = json_encode($luz);
}

/////DATOS LUZ DIAS ANTERIORES
$result_luz_anteriores = pg_query($conn,"select avg(luz),fecha from pruebas_conn.datos where fecha !='".$fecha."' group by fecha order by fecha desc");
if ($result_luz_anteriores){
    while ($row_luz_anteriores_datos= pg_fetch_row($result_luz_anteriores)) {
		array_push($media_luz_anteriores,$row_luz_anteriores_datos[0]);
		array_push($fecha_anteriores,$row_luz_anteriores_datos[1]);
	}
	
	$json_luz_anteriores = json_encode($media_luz_anteriores);
	$json_fecha_anteriores = json_encode($fecha_anteriores);
}


////DATOS LUZ ACTUAL
$result_luz_actual = pg_query($conn,"SELECT luz from  pruebas_conn.datos where fecha = '".$fecha."' order by id desc limit 1 ;");
if ($result_luz_actual){
    while ($row_luz_actual= pg_fetch_row($result_luz_actual)) {
		$luz_actual= $row_luz_actual[0];
		array_push($json_polar,$luz_actual);
	}
	
	$json_luz_actual = $luz_actual;
}

/////DATOS LUZ MEDIA
$result_media_luz = pg_query($conn,"SELECT avg(luz) from pruebas_conn.datos");
if ($result_media_luz){
    while ($row_luz= pg_fetch_row($result_media_luz)) {
		$media_luz= $row_luz[0];
	}
	$json_media_luz = $media_luz;
}
///////////////////////////////////////////////////////////////////////////////////////////
///TEMPERATURA HUMEDAD Y ROCIO
///////////////////////////////////////////////////////////////////////////////////////////
$temperatura=[];
$humedad=[];
$rocio=[];
$media_temperatura_anteriores=[];
$media_humedad_anteriores=[];
$media_rocio_anteriores=[];
$json_datos_polar=[];
///MEDIA TEMPERATURA
$result_media_temperatura = pg_query($conn,"SELECT avg(temperatura) from pruebas_conn.datos");
if ($result_media_temperatura){
    while ($row_temperatura_media= pg_fetch_row($result_media_temperatura)) {
		$media_temperatura= $row_temperatura_media[0];
	}
	$json_media_temperatura = $media_temperatura;
}
///MEDIA HUMEDAD
$result_media_humedad = pg_query($conn,"SELECT avg(humedad) from pruebas_conn.datos");
if ($result_media_humedad){
    while ($row_humedad_media= pg_fetch_row($result_media_humedad)) {
		$media_humedad= $row_humedad_media[0];
	}
	$json_media_humedad = $media_humedad;
}
////DATOS TEMPERATURA ACTUAL
$result_temperatura_actual = pg_query($conn,"SELECT temperatura from  pruebas_conn.datos where fecha = '".$fecha."' order by id desc limit 1 ;");
if ($result_temperatura_actual){
    while ($row_temperatura_actual= pg_fetch_row($result_temperatura_actual)) {
		$temperatura_actual= $row_temperatura_actual[0];
		array_push($json_polar,$temperatura_actual);
		
	}
	$json_temperatura_actual = $temperatura_actual;
}
////DATOS HUMEDAD ACTUAL
$result_humedad_actual = pg_query($conn,"SELECT humedad from  pruebas_conn.datos where fecha = '".$fecha."' order by id desc limit 1 ;");
if ($result_humedad_actual){
    while ($row_humedad_actual= pg_fetch_row($result_humedad_actual)) {
		$humedad_actual= $row_humedad_actual[0];
		array_push($json_polar,$humedad_actual);
	}
	$json_humedad_actual = $humedad_actual;
}
///////ROCIO ACTUAL
$result_rocio_actual = pg_query($conn,"SELECT rocio from  pruebas_conn.datos where fecha = '".$fecha."' order by id desc limit 1 ;");
if ($result_rocio_actual){
    while ($row_rocio_actual= pg_fetch_row($result_rocio_actual)) {
		$rocio_actual= $row_rocio_actual[0];
		array_push($json_polar,$rocio_actual);
	}
	$json_rocio_actual = $rocio_actual;
}


$result_temperatura = pg_query($conn,"select temperatura from pruebas_conn.datos where fecha = '".$fecha."' order by id");
if ($result_temperatura){
    while ($row_temperatura= pg_fetch_row($result_temperatura)) {
	  array_push($temperatura,$row_temperatura[0]);
	  
	}
	$json_temperatura = json_encode($temperatura);
}

$result_humedad= pg_query($conn,"select humedad from pruebas_conn.datos where fecha = '".$fecha."' order by id");
if ($result_humedad){
    while ($row_humedad= pg_fetch_row($result_humedad)) {
	  array_push($humedad,$row_humedad[0]);
	  
	}
	$json_humedad = json_encode($humedad);
}

$result_rocio= pg_query($conn,"select rocio from pruebas_conn.datos where fecha = '".$fecha."' order by id");
if ($result_rocio){
    while ($row_rocio= pg_fetch_row($result_rocio)) {
	  array_push($rocio,$row_rocio[0]);
	  
	}
	$json_rocio = json_encode($rocio);
}

/////DATOS TEMPERATURA DIAS ANTERIORES
$result_temperatura_anteriores = pg_query($conn,"select avg(temperatura),fecha from pruebas_conn.datos where fecha !='".$fecha."' group by fecha order by fecha desc");
if ($result_temperatura_anteriores){
    while ($row_temperatura_anteriores_datos= pg_fetch_row($result_temperatura_anteriores)) {
		array_push($media_temperatura_anteriores,$row_temperatura_anteriores_datos[0]);
	}
	$json_temperatura_anteriores = json_encode($media_temperatura_anteriores);
}

/////DATOS HUMEDAD DIAS ANTERIORES
$result_humedad_anteriores = pg_query($conn,"select avg(humedad),fecha from pruebas_conn.datos where fecha !='".$fecha."' group by fecha order by fecha desc");
if ($result_humedad_anteriores){
    while ($row_humedad_anteriores_datos= pg_fetch_row($result_humedad_anteriores)) {
		array_push($media_humedad_anteriores,$row_humedad_anteriores_datos[0]);
	}
	$json_humedad_anteriores = json_encode($media_humedad_anteriores);
}
/////DATOS ROCIO DIAS ANTERIORES
$result_rocio_anteriores = pg_query($conn,"select avg(rocio),fecha from pruebas_conn.datos where fecha !='".$fecha."' group by fecha order by fecha desc");
if ($result_luz_anteriores){
    while ($row_rocio_anteriores_datos= pg_fetch_row($result_rocio_anteriores)) {
		array_push($media_rocio_anteriores,$row_rocio_anteriores_datos[0]);
	}
	$json_rocio_anteriores = json_encode($media_rocio_anteriores);
}

//////////DATOS LLUVIA
$result_lluvia_actual = pg_query($conn,"SELECT lluvia from  pruebas_conn.datos where fecha = '".$fecha."' order by id desc limit 1 ;");
if ($result_lluvia_actual){
    while ($row_lluvia_actual= pg_fetch_row($result_lluvia_actual)) {
		$lluvia_actual= $row_lluvia_actual[0];
		//array_push($json_polar,$lluvia_actual);
	}
	$json_lluvia_actual = $lluvia_actual;
}


$result_lluvia= pg_query($conn,"select lluvia from pruebas_conn.datos where fecha = '".$fecha."' order by id");
if ($result_lluvia){
    while ($row_lluvia= pg_fetch_row($result_lluvia)) {
	  array_push($lluvia,$row_lluvia[0]);
	  
	}
	$json_lluvia = json_encode($lluvia);
}

$result_lluvia_anteriores = pg_query($conn,"select avg(lluvia),fecha from pruebas_conn.datos where fecha !='".$fecha."' group by fecha order by fecha desc");
if ($result_luz_anteriores){
    while ($row_lluvia_anteriores_datos= pg_fetch_row($result_lluvia_anteriores)) {
		array_push($media_lluvia_anteriores,$row_lluvia_anteriores_datos[0]);
	}
	$json_lluvia_anteriores = json_encode($media_lluvia_anteriores);
}


$json_datos_polar= json_encode($json_datos_polar) ;


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//DATOS DE TIEMPO
$tiempo=[];
$result_tiempo = pg_query($conn,"select tiempo from pruebas_conn.datos where fecha = '".$fecha."' order by id");
if ($result_tiempo){
    while ($row_tiempo = pg_fetch_row($result_tiempo)) {
	  array_push($tiempo,$row_tiempo[0]);
	  
	}
	$json_tiempo = json_encode($tiempo);
}
$json_datos_polar=json_encode($json_polar);
$datos_totales =$json_luz."%".$json_tiempo."%".$json_media_luz."%".$json_luz_anteriores."%".$json_fecha_anteriores."%".$json_luz_actual."%".$json_media_temperatura."%".$json_media_humedad."%".$json_temperatura_actual."%".$json_temperatura."%".$json_humedad."%".$json_rocio."%".$json_temperatura_anteriores."%".$json_humedad_anteriores."%".$json_rocio_anteriores."%".$json_humedad_actual."%".$json_datos_polar."%".$json_lluvia_actual."%".$json_lluvia."%".$json_lluvia_anteriores; 
pg_close($conn);

echo $datos_totales;

?>
