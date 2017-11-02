<?php
/*header('Content-Type: text/html; charset=utf-8');*/
$host=  'localhost';
$usuario = 'postgres';
$contrasenya = 'pg';
$puerto = '5432';
$bbdd = 'datos';
$conn = pg_connect("dbname=".$bbdd." user=".$usuario." host=".$host." password=".$contrasenya." port=".$puerto) or die ("fallo en la conexión");
?>
 
