<?php
include 'conexion.php';


// DATOS TELEGRAM
$fecha_mensaje=date('d-m-Y');
$fecha_php=strtotime(date('d-m-Y H:i:s'));
$hora_php = date('H:i:s');
$fecha=date("Y-m-d H:i:s");

//LUZ
$result_luz_actual = pg_query($conn,"SELECT luz from  pruebas_conn.datos where fecha = '".$fecha."' order by id desc limit 1 ;");
if ($result_luz_actual){
    while ($row_luz_actual= pg_fetch_row($result_luz_actual)) {
		$luz_actual= $row_luz_actual[0];
	}
	$json_luz_actual = $luz_actual;
}
//TEMPERATURA
$result_temperatura_actual = pg_query($conn,"SELECT temperatura from  pruebas_conn.datos where fecha = '".$fecha."' order by id desc limit 1 ;");
if ($result_temperatura_actual){
    while ($row_temperatura_actual= pg_fetch_row($result_temperatura_actual)) {
		$temperatura_actual= $row_temperatura_actual[0];
		
		
	}
	$json_temperatura_actual = $temperatura_actual;
}
///LLUVIA
$result_lluvia_actual = pg_query($conn,"SELECT lluvia from  pruebas_conn.datos where fecha = '".$fecha."' order by id desc limit 1 ;");
if ($result_lluvia_actual){
    while ($row_lluvia_actual= pg_fetch_row($result_lluvia_actual)) {
		$lluvia_actual= $row_lluvia_actual[0];
		
	}
	$json_lluvia_actual = $lluvia_actual;
}
$result_fecha_actual = pg_query($conn,"SELECT fecha from  pruebas_conn.datos order by fecha desc  limit 1 ;");
if ($result_fecha_actual){
    while ($row_fecha_actual= pg_fetch_row($result_fecha_actual)) {
		$fecha_actual= $row_fecha_actual[0];
	}
	$json_fecha_actual = $fecha_actual;
}
//echo strtotime($fecha_actual);



$content=file_get_contents('https://api.telegram.org/bot401594833:AAHOr4Bqe81MCww77RfDiv0Zs7uzGbrINFk/getupdates');
$update=json_decode($content,TRUE);
$contador = count($update['result']) -1 ;
$message=$update['result'][$contador]['message'];
$nombre=$update['result'][$contador]['message']['from']['first_name'];
$chatId=$message['chat']['id'];
$text = $message['text'];
$text=strtolower($text);

$text_temperatura= 'temperatura';
$posicion_temperatura=strpos($text,$text_temperatura);
if ($posicion_temperatura > 0){
	$text1=substr($text,$posicion_temperatura,11);
	
}
$text_luz= 'luz ';
$posicion_luz=strpos($text,$text_luz);
if ($posicion_luz > 0){
	$text1=substr($text,$posicion_luz,4);
	
}
$text_lluvia= 'lloviendo';
$posicion_lluvia=strpos($text,$text_lluvia);
if ($posicion_lluvia > 0){
	$text1=substr($text,$posicion_lluvia,9);
	echo $text1;
}
$text_comoestas= 'como estas';
$posicion_estado=strpos($text,$text_comoestas);
echo $posicion_estado;
if ($posicion_estado > 0){
	$text1=substr($text,$posicion_estado,10);
	echo $text1;
}

if($text1 == "temperatura"){
	$content =("".$nombre." la temperatura es de ".$temperatura_actual." Grados ");
	echo $content;
	file_get_contents("https://api.telegram.org/bot401594833:AAHOr4Bqe81MCww77RfDiv0Zs7uzGbrINFk/sendMessage?chat_id=".$chatId."&text=".urlencode($content).$agregaTeclado."&parse_mode=HTML");

}

if($text1 == "luz "){
	$content =("".$nombre."  el porcentaje de luz ahora mismo es de ".$json_luz_actual." ");
	echo $content;
	file_get_contents("https://api.telegram.org/bot401594833:AAHOr4Bqe81MCww77RfDiv0Zs7uzGbrINFk/sendMessage?chat_id=".$chatId."&text=".urlencode($content).$agregaTeclado."&parse_mode=HTML");

}
if($text1 == "lloviendo"){
	if($json_lluvia_actual < 30.0){
		$mensaje_lluvia= "".$nombre." de verdad me estas preguntando si llueve, tú has visto el cielo!!!";
	}else{
		$mensaje_lluvia="Ves llamando a Noe, ".$nombre." !!!!";
	}
	$content =("".$mensaje_lluvia." ");
	echo $content;
	file_get_contents("https://api.telegram.org/bot401594833:AAHOr4Bqe81MCww77RfDiv0Zs7uzGbrINFk/sendMessage?chat_id=".$chatId."&text=".urlencode($content).$agregaTeclado."&parse_mode=HTML");

}
if($text1 == "como estas"){
	
		$mensaje= "".$nombre." Gracias por preguntar, Yo simplemente soy un BOT y no tengo sentimientos!!";
	
	$content =("".$mensaje."");
	echo $content;
	file_get_contents("https://api.telegram.org/bot401594833:AAHOr4Bqe81MCww77RfDiv0Zs7uzGbrINFk/sendMessage?chat_id=".$chatId."&text=".urlencode($content).$agregaTeclado."&parse_mode=HTML");

}


if($hora_php > '23:55:00' AND $hora_php < '23:55:30'){	
		//$fecha_envio= strtotime(date('Y-m-d')." 23:58:50");
	    $dif_noche = abs($fecha_envio - $fecha_php);
	    $mensaje = "Buenas noches Sergio son las ".$hora_php." de un ".$fecha_mensaje." y hace una temperatura de ".$temperatura_actual." Grados. Que duermas bien";
		if ($dif_noche > 0 AND $dif_noche < 30){
			bot_vilcum($mensaje);
		}
	}

if($hora_php > '23:50:00' ){
	if($hora_php < '23:50:30' ){
	    $mensaje = "Buenas noches Paula son las ".$hora_php." de un ".$fecha_mensaje." y hace una temperatura de ".$temperatura_actual." Grados. Que duermas bien y recuerda que tienes un novio que te quiere con locura";
		$dif_noche = abs($fecha_envio - $fecha_php);
		if ($dif_noche > 0 AND $dif_noche < 30){
			bot_vilcum_pau($mensaje);
		}
		//bot_vilcum($mensaje);
		
	}}
	
if($hora_php > '07:55:00' AND $hora_php < '07:55:30'){
		//$fecha_envio= strtotime(date('Y-m-d')." 07:30:59");
	    $dif_dia = abs($fecha_envio - $fecha_php);
		$mensaje =  "Buenas días Sergio son las".$hora_php." De un ".$fecha_php." Que tengas un buen día";		
		$mensaje1 = "Buenas días Paula son las".$hora_php." De un ".$fecha_php." Que tengas un buen día";		
		if ($dif_dia > 0 AND $dif_dia < 30){
			bot_vilcum($mensaje);
			bot_vilcum_pau($mensaje1);
		}
	}
$rango_temperatura=(float)30.00;

if($json_temperatura_actual > $rango_temperatura){
		$result_fecha_envio = pg_query($conn,"SELECT fecha from  pruebas_conn.datos_envio WHERE dato = 'temperatura' order by fecha desc limit 1;");
		if ($result_fecha_envio){
			while ($row_fecha_envio= pg_fetch_row($result_fecha_envio)) {
				$fecha_envio= $row_fecha_envio[0];
			}
			$json_fecha_envio= $fecha_envio;
		}
		$fecha_envio= strtotime($json_fecha_envio);
		echo $fecha_envio."  ".$fecha_php;
		if (($fecha_envio + 1800) < $fecha_php){
			$fecha_envio= pg_query($conn,"INSERT INTO pruebas_conn.datos_envio (dato,fecha,valor) VALUES ('temperatura',CURRENT_TIMESTAMP,".$json_temperatura_actual.");");
			$mensaje = ''.$nombre.' ME DERRITOOOOOOOOOOO AYUDAMEEEE '.$json_temperatura_actual.'!!!';
			bot_vilcum($mensaje);
			echo "ENVIADO";
		}
	}
	
if($json_lluvia_actual > 20.0000 ){
		$result_fecha_envio = pg_query($conn,"SELECT fecha from  pruebas_conn.datos_envio WHERE dato = 'lluvia' order by fecha asc limit 1;");
		if ($result_fecha_envio){
			while ($row_fecha_envio= pg_fetch_row($result_fecha_envio)) {
				$fecha_envio= $row_fecha_envio[0];
			}
			$json_fecha_envio= $fecha_envio;
		}
		$fecha_envio= strtotime($json_fecha_envio);
		echo $fecha_envio."  ".$fecha_php;
		if (($fecha_envio + 1800) < $fecha_php){
			$fecha_envio= pg_query($conn,"INSERT INTO pruebas_conn.datos_envio (dato,fecha,valor) VALUES ('lluvia',CURRENT_TIMESTAMP,".$json_temperatura_actual.");");
			$mensaje = ''.$nombre.' CORRE A RECOGER LA ROPA INSENSATOOOOOO !!!!';
			bot_vilcum($mensaje);
			echo "ENVIADO";
		}	
	}
	
if($json_temperatura_actual < 5.0000 AND $hora_php > '00:10:59' AND $hora_php < '00:00:00'){
		$result_fecha_envio = pg_query($conn,"SELECT fecha from  pruebas_conn.datos_envio WHERE dato = 'temperatura' order by fecha asc limit 1;");
		if ($result_fecha_envio){
			while ($row_fecha_envio= pg_fetch_row($result_fecha_envio)) {
				$fecha_envio= $row_fecha_envio[0];
			}
			$json_fecha_envio= $fecha_envio;
		}
		$fecha_envio= strtotime($json_fecha_envio);
		echo $fecha_envio."  ".$fecha_php;
		if (($fecha_envio + 1800) < $fecha_php){
			$fecha_envio= pg_query($conn,"INSERT INTO pruebas_conn.datos_envio (dato,fecha,valor) VALUES ('temperatura',CURRENT_TIMESTAMP,".$json_temperatura_actual.");");
			$mensaje = ''.$nombre.' WINTER IS COMMING !!!!';
			bot_vilcum($mensaje);
			echo "ENVIADO";
		}	
	}












function bot_vilcum($envio){

$selIdConver=334674720;
$tokenTelegram = '401594833:AAHOr4Bqe81MCww77RfDiv0Zs7uzGbrINFk';
$sitioWeb = "https://api.telegram.org/bot".$tokenTelegram."/sendMessage";
$url = $sitioWeb."?chat_id=".$selIdConver."&text=".urlencode($envio).$agregaTeclado."&parse_mode=HTML";
$jsonEnviado = file_get_contents($url);
$jsonEnviado = json_decode($jsonEnviado, TRUE);


}

function bot_vilcum_pau($envio){

$selIdConver=446878731;
$tokenTelegram = '401594833:AAHOr4Bqe81MCww77RfDiv0Zs7uzGbrINFk';
$sitioWeb = "https://api.telegram.org/bot".$tokenTelegram."/sendMessage";
$url = $sitioWeb."?chat_id=".$selIdConver."&text=".urlencode($envio).$agregaTeclado."&parse_mode=HTML";
$jsonEnviado = file_get_contents($url);
$jsonEnviado = json_decode($jsonEnviado, TRUE);


}

function setInterval($f,$milliseconds)
{
	$seconds = (int)$milliseconds/1000;
	while(true)
	{
		$f();
		sleep($seconds);
		}
	
}
pg_close($conn);
?>
