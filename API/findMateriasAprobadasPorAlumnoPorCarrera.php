<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');

require_once "seguridadNivel2.php";
require_once "SanitizeCustom.class.php";
require_once "AlumnoRindeMateria.php";
require_once "CarreraTieneMateria.php";



$id_alumno = (isset($_POST['alumno']))?SanitizeVars::INT($_POST['alumno']):false;
$id_carrera = (isset($_POST['carrera']))?SanitizeVars::INT($_POST['carrera']):false;

$arr_materias_aprobadas_en_carrera = $array_resultados = [];
if ($id_alumno && $id_carrera) {
   $arm = new AlumnoRindeMateria;
   $arr_materias_aprobadas = $arm->getMateriasRendidasByEstadoDetalle($id_alumno,"Aprobo");
   //var_dump($arr_materias_aprobadas);exit;
   $ctm = new CarreraTieneMateria;
   $arr_materia_en_carrera = $ctm->getMateriasByIdCarreraDetalle($id_carrera);
   //var_dump($arr_materia_en_carrera);exit;

   foreach ($arr_materia_en_carrera as $item_materia_de_carrera) {
      foreach ($arr_materias_aprobadas as $item_materia_aprobada) {
           if ($item_materia_de_carrera['id']==$item_materia_aprobada['idMateria']) {
             $arr_materias_aprobadas_en_carrera[] = $item_materia_aprobada;
           }
      }  
   }
   //var_dump($arr_materias_aprobadas_en_carrera);exit;

   $array_resultados['codigo'] = 200;
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $arr_materias_aprobadas_en_carrera;
   
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso el Alumno o el Profesor.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);







?>
