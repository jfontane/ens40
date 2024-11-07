<?php
/**
 *  Archivo: config.php
 *	Utilidad: Este archivo define constantes necesarias para la conexion con la base de datos.
*/

///////////////////// BASE DE DATOS local ///////////////////////////////////////////
define('DB_TYPE', "mysql");
define('DB_HOST', "localhost");
define('DB_PORT', "3306");
define('DB_NAME', "uiakkdaq_escuela_1");
define('DB_USER', "admin");
define('DB_PASS', "usuario");
$MY_SECRET = 'MI_SECRETO_ESCONDIDO';
$url = 'https://'.$_SERVER["HTTP_HOST"].'/';

///////////////////////////////////////////////////////////////////////////////
?>
