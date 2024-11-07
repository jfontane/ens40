<?php
	set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
	
	include_once 'seguridadNivel2.php';
	include_once 'Sanitize.class.php';
	require_once "ProfesorDictaMateria.php";
		
	$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
	$idProfesor = (isset($_POST['profesor']) && $_POST['profesor']!=NULL)?SanitizeVars::INT($_POST['profesor']):false;
	
	$array_resultados = array();
	if ($idProfesor && $idMateria) {
		
		$objPDM = new ProfesorDictaMateria();
		$objPDM->deleteByProfesorByMateria($idProfesor,$idMateria);

		$array_resultados['codigo'] = 200;
      	$array_resultados['class'] = "success";
      	$array_resultados['mensaje'] = "La Materia fue desviculada del profesor exitosamente.";
      	$array_resultados['datos'] = [];
	} else {
		$array_resultados['codigo'] = 400;
      	$array_resultados['class'] = "danger";
      	$array_resultados['mensaje'] = "La Materia no ha podido desvicularse del profesor.";
      	$array_resultados['datos'] = [];	
		
	}
	echo json_encode($array_resultados);

?>
