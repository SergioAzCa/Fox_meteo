<?php
//{"Luz:":62,"Temperatura:"27.70,"Humedad:"50.100,"Punto_Rocio:"16.35}
include 'conexion.php';
echo "Datos Obtenidos del NodeMCU1..... <br>";

if($_REQUEST){
	$var1 = $_REQUEST['datos'];
	$json=json_decode($var1,true);
	$Luz=  $json["Luz:"];
	$Lluvia=$json["Lluvia:"];
	$Temperatura=  $json["Temperatura:"];
	$Humedad=  $json["Humedad:"];
	$Rocio=  $json["Punto_Rocio:"];
}


pg_query($conn,"INSERT INTO pruebas_conn.datos (luz,json,fecha,tiempo,temperatura,humedad,rocio,lluvia) VALUES ('".$Luz."','".$var1."',CURRENT_DATE,CURRENT_TIME(0),'".$Temperatura."','".$Humedad."','".$Rocio."','".$Lluvia."')");
pg_close($conn);
?>
