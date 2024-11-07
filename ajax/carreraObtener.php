<?php
set_include_path('../conexion/'.PATH_SEPARATOR.'../app/lib/');
//include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$array_resultados = array();
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
if($search) {
        
        $sql = "SELECT c.id, c.descripcion as nombre
                FROM carrera c
                WHERE (c.descripcion like '%$search%') and c.habilitacion_registro='Si'";
        $resultado = mysqli_query($conex,$sql);
        if (mysqli_num_rows($resultado)>0) {
                while($row = mysqli_fetch_assoc($resultado)){
                        $json[] = ['id'=>$row['id'], 'text'=>$row['nombre']];
                }
        };
} else {
        $json = [];   
}
echo json_encode($json);


