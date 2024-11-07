<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

require_once "Materia.php";
include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$arr_resultados = [];
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
$materia = new Materia();
if($search) {
        
        $arr_resultados = $materia->getMateriaByName($search);

        if ($arr_resultados) {
                foreach($arr_resultados as $val) {
                        $json[] = ['id'=>$val['id'], 'text'=>$val['nombre'].' ('.$val['id'].') - '.$val['carrera']];
                }
        }

} else {
        $json = [];   
}
echo json_encode($json);

?>