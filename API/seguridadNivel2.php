<?php
session_start();
//require_once "./conexion/config.php";
if (($_SESSION['tipoUsuario']!='2')&&($_SESSION['tipoUsuario']!='1')||(!isset($_SESSION['tipoUsuario']))) {
    session_destroy();
    header('location: https://escuela40.net/logout.php');
}



?>
