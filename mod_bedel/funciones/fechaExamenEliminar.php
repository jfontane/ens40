<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

require_once "FechaExamen.php";
require_once 'Sanitize.class.php';
require_once "_seguridad.php";

$accion = (isset($_POST['accion']) && $_POST['accion']!=NULL)?SanitizeVars::STRING($_POST['accion']):false;
$fecha_examen_id = (isset($_POST['fecha_examen_id']) && $_POST['fecha_examen_id']!=NULL)?SanitizeVars::INT($_POST['fecha_examen_id']):false;

$entidad = "Fecha de Exámen";
$array_resultados = array();

if ($accion=='eliminar') {
      $objeto = new FechaExamen;
      $res = $objeto->delete($fecha_examen_id);

      if ($res) {
         $array_resultados['codigo'] = 100;
         $array_resultados['mensaje'] = "El registro de datos de la $entidad fue Eliminado Exitosamente.";
      } else {
         $errorNro =  mysqli_errno($conex);
         $array_resultados['codigo'] = 12;
         $array_resultados['mensaje'] = "Hubo un Error en la Eliminación de los datos de la $entidad. ";
      } 
      
} else {
            $array_resultados['codigo'] = 12;
            $array_resultados['mensaje'] = "Hubo un Error en la eliminación de la $entidad. ";
      }  

echo json_encode($array_resultados);


?>