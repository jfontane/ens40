<?php

//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODOS LOS ALUMNOS DE UN ALUMNO POR ID ALUMNO                                 **
//***************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');

require_once "seguridadNivel2.php";
require_once "SanitizeCustom.class.php";
require_once "AlumnoTipoCursado.php";

$array_resultados = [];

$objeto = new AlumnoTipoCursado;
$arr_formas_cursado = $objeto->getAllAlumnoTipoCursado();

if (is_array($arr_formas_cursado)) {
   $array_resultados['codigo'] = 200;
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $arr_formas_cursado;
} else {
   $array_resultados['codigo'] = 500;
   $array_resultados['mensaje'] = "Error 500: Hubo un error en la consulta.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);


?>