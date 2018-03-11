<?php
/* Conexion de Base de datos, partiendo del tipo de Base de Datos
  @var Crear un objeto de conexion  */
$param=array(
	'tipo'			=>	'mysql',
	'base_datos'	=>	'db_events',
	'servidor'		=>	'localhost',
	'usuario'		=>	'root',
	'password'		=>	''
);

$_SESSION['config']['bd'] = $param;
//MySQL
$bd = new BD();

?>