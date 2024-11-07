<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
require_once "_seguridad.php";

$alumno_dni = $_POST['alumno_dni'];

$array_resultados = array();
if ($alumno_dni) {
               
                
                        $sqlCambiarContrasenia = "UPDATE usuario 
                                                  SET pass='".md5($alumno_dni)."' 
                                                  WHERE dni='$alumno_dni' and idtipo=1";
                        //var_dump($sqlCambiarContrasenia);exit;
                        $resultadoCambiarPassword =  mysqli_query($conex, $sqlCambiarContrasenia);
                        $array_resultados['codigo'] = 100;
                        $array_resultados['mensaje'] = "La contrase&ntilde;a fue Modificada Exitosamente.";
                
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['mensaje'] = "Existen datos sin completar.";
}

echo json_encode($array_resultados);
?>
