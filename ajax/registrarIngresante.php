<?php

session_start();
set_include_path("../app/lib/".PATH_SEPARATOR."../app/models/");
require_once "SanitizeCustom.class.php";

require_once "Persona.php";
require_once "Alumno.php";
require_once "Usuario.php";
require_once "AlumnoEstudiaCarrera.php";

function enviarEmail($arr) {
   $valorParametro = base64_encode($arr['dni'].'&'.$arr['anio']);
   $url = "https://escuela40.net/comprobante.php?r=" . $valorParametro;
   $para = $arr['email'];
   $titulo = 'Escuela 40 Mariano Moreno - Inscripción Exitosa';

   $mensaje = '<html>'.
         '<head><title>HTML</title></head>'.
         '<body><h1>Datos de la Inscripcion</h1>'.
         'Por favor no elimine éste email'.
         '<hr>'.
         'Enviado desde la Escuela'.
         '<table>'.
         '<tr><th align="left">Apellido</th><td>'.$arr['apellido'].'</td></tr>'.
         '<tr><th align="left">Nombres</th><td>'.$arr['nombres'].'</td></tr>'.
         '<tr><th align="left">DNI</th><td>'.$arr['dni'].'</td></tr>'.
         '<tr><th align="left">Domiclio</th><td>'.$arr['domicilio'].'</td></tr>'.
         '<tr><th align="left">Telefono</th><td>'.$arr['telefono'].'</td></tr>'.
         '<tr><th colspan="2" align="center">Descargue el Comprobante <a href="'.$url.'" target="_blank">Aquí</a></td></tr>'.
         '</table>'.
         '</body>'.
         '</html>';
   $cabeceras = 'MIME-Version: 1.0' . "\r\n";
   $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
   $cabeceras .= 'From: noreply@escuela40.net';
   mail($para, $titulo, $mensaje, $cabeceras);
 
};


$array_carreras = array(1,2,4,5,6,9,10,11,12,14,15,16,17,18);
$array_genero = array('F','M','O');

$apellido = (isset($_POST['inputApellido']) && $_POST['inputApellido']!=NULL)?SanitizeVars::UTF8($_POST['inputApellido']):false;
$nombres = (isset($_POST['inputNombres']) && $_POST['inputNombres']!=NULL)?SanitizeVars::UTF8($_POST['inputNombres']):false;
$dni = (isset($_POST['inputDni']) && $_POST['inputDni']!=NULL)?SanitizeVars::INT($_POST['inputDni']):false;
$fecha_nacimiento = (isset($_POST['inputFechaNacimiento']) && $_POST['inputFechaNacimiento']!=NULL)?SanitizeVars::DATE($_POST['inputFechaNacimiento']):NULL;
$domicilio = (isset($_POST['inputDomicilio']) && $_POST['inputDomicilio']!=NULL)?SanitizeVars::STRING($_POST['inputDomicilio']):false;
$telefono_caracteristica = (isset($_POST['inputCelularCar']))?SanitizeVars::INT($_POST['inputCelularCar']):false;
$telefono_numero = (isset($_POST['inputCelularNum']))?SanitizeVars::INT($_POST['inputCelularNum']):false;
$email = (isset($_POST['inputEmail']) && $_POST['inputEmail']!=NULL)?SanitizeVars::EMAIL($_POST['inputEmail']):false;
$localidad_id = (isset($_POST['inputLocalidad']) && $_POST['inputLocalidad']!=NULL)?SanitizeVars::INT($_POST['inputLocalidad']):false;
$sexo = (isset($_POST['inputGenero'])&& $_POST['inputGenero']!=NULL && in_array(strtoupper($_POST['inputGenero']), $array_genero))?$_POST['inputGenero']:'O';
$carrera = (isset($_POST['inputCarrera'])&& $_POST['inputCarrera']!=NULL && in_array(strtoupper($_POST['inputCarrera']), $array_carreras))?$_POST['inputCarrera']:false;
$array_estado_civil = array(1,2,3,4,5,6);
$estado_civil = (isset($_POST['inputEstadoCivil'])&& $_POST['inputEstadoCivil']!=NULL && in_array(strtoupper($_POST['inputEstadoCivil']), $array_estado_civil))?$_POST['inputEstadoCivil']:false;
$ocupacion = (isset($_POST['inputOcupacion'])&& $_POST['inputOcupacion']!=NULL)?$_POST['inputOcupacion']:false;
$titulo = (isset($_POST['inputTitulo'])&& $_POST['inputTitulo']!=NULL)?$_POST['inputTitulo']:false;
$escuela = (isset($_POST['inputEscuela'])&& $_POST['inputEscuela']!=NULL)?$_POST['inputEscuela']:false;

//die($fecha_nacimiento);

//Retornos de esta funcionalidad
// 100 (Atención: El Ingresante se Registrado Correctamente.)
// 1 (Error: El Apellido no esta Válido o Tiene Caracteres Prohibidos.)
// 2 (Error: los Nombres no son Válidos o Tienen Caracteres Prohibidos.)
// 3 (Error: El DNI no es Válida o Tiene Caracteres Prohibidos.)
// 4 (Error: La fecha de Nacimiento no Es Válida.)
// 5 (Error: El Género no Es Válida.)
// 6 (Error: El Numero del Celular no Es Válido.)
// 7 (Error: El Email no Es Válido.)

$estado_civil_desc = "";
if ($estado_civil==1) {
   $estado_civil_desc = "Soltero/a";
} else if ($estado_civil==2) {
   $estado_civil_desc = "Casado/a";
} else if ($estado_civil==3) {
   $estado_civil_desc = "Unión libre o unión de hecho";
} else if ($estado_civil==4) {
   $estado_civil_desc = "Divorciado/a";
} else if ($estado_civil==5) {
   $estado_civil_desc = "Separado/a";
} else if ($estado_civil==6) {
   $estado_civil_desc = "Viudo/a";
};

$hoy = date('Y-m-d'); 
$mes = date('m');
$anio = date('Y');
$anio_ingreso = $anio;
if ($mes>7) {
     $anio_ingreso++;
};



$respuesta = array();

//var_dump($apellido,$nombres,$dni,$fechaNacimiento,$sexo,$celular_numero,$celular_caracteristica,$email,$domicilio,$localidad,$carrera );exit;

//var_dump($apellido,$nombres,$dni,$fecha_nacimiento,$sexo,$telefono_numero,$telefono_caracteristica,$email,$domicilio,$localidad_id,$carrera);
//exit;
if (!$apellido || !$nombres || !$dni || !$fecha_nacimiento || !$sexo || !$telefono_numero || !$telefono_caracteristica || !$email ||
    !$domicilio || !$localidad_id ||  !$carrera ) {

    $band = false;
    if (!$apellido && !$band) {
       $respuesta['estado'] = 1;
       $respuesta['info'] = 'El Apellido no es Válido o Tiene Caracteres Prohibidos.';
       $band = true;
    };

    if (!$nombres && !$band) {
       $respuesta['estado'] = 2;
       $respuesta['info'] = 'Los Nombres no son Válidos o Tienen Caracteres Prohibidos.';
       $band = true;
    };

    if (!$dni && !$band) {
       $respuesta['estado'] = 3;
       $respuesta['info'] = 'El DNI no es Válido o Tiene Caracteres Prohibidos.';
       $band = true;
    };

    if (!$fecha_nacimiento && !$band) {
       $respuesta['estado'] = 4;
       $respuesta['info'] = 'La fecha de Nacimiento no Es Válida.';
       $band = true;
    };

    if (!$sexo && !$band) {
       $respuesta['estado'] = 5;
       $respuesta['info'] = 'El Género no Es Válido.';
       $band = true;
    };

    if ((!$telefono_caracteristica || !$telefono_numero) && !$band) {
       $respuesta['estado'] = 6;
       $respuesta['info'] = 'El Numero del Celular no Es Válido.';
       $band = true;
    };

    if (!$email && !$band) {
       $respuesta['estado'] = 7;
       $respuesta['info'] = 'El Email no Es Válido.';
       $band = true;
    };

    if (!$domicilio && !$band) {
       $respuesta['estado'] = 8;
       $respuesta['info'] = 'El Domicilio no Es Válido.';
       $band = true;
    };

    if (!$localidad_id && !$band) {
       $respuesta['estado'] = 9;
       $respuesta['info'] = 'La Localidad no Es Válido.';
       $band = true;
    };

    if (!$carrera && !$band) {
       $respuesta['estado'] = 11;
       $respuesta['info'] = 'La Carrera no Es Válida.';
       $band = true;
    };

} else {
   $alumno = new Alumno();
   $persona = new Persona();
   $usuario = new Usuario();
   $alumnoEstudiaCarrera = new AlumnoEstudiaCarrera();
   
   $persona_res = $usuario_res = $alumno_res = $alumno_carrera_res = "";
   // *** SE CREA LA PERSONA ***
   $datos_persona = $persona->getPersonaByDni($dni);  
   $persona_id = $datos_persona['id'];
   if ($persona_id) {
      $persona_res = $persona->save(["id"=>$persona_id,"dni"=>$dni,"apellido"=>$apellido,"nombres"=>$nombres,
                                    "fecha_nacimiento"=>$fecha_nacimiento,"domicilio"=>$domicilio,
                                    "telefono_caracteristica"=>$telefono_caracteristica, "telefono_numero"=>$telefono_numero,
                                    "email"=>$email,"localidad_id"=>$localidad_id,"estado_civil"=>$estado_civil_desc, "ocupacion"=>$ocupacion, 
                                    "titulo"=>$titulo, "titulo_expedido_por"=>$escuela,  
                                    "observaciones"=>"Ninguna observacion"]);
   } else {
      $persona_res = $persona->save(["dni"=>$dni,"apellido"=>$apellido,"nombres"=>$nombres,
                                    "fecha_nacimiento"=>$fecha_nacimiento,"domicilio"=>$domicilio,
                                    "telefono_caracteristica"=>$telefono_caracteristica, "telefono_numero"=>$telefono_numero,
                                    "email"=>$email,"localidad_id"=>$localidad_id,"genero"=>"O","estado_civil"=>$estado_civil_desc, 
                                    "ocupacion"=>$ocupacion, "titulo"=>$titulo, "titulo_expedido_por"=>$escuela, 
                                    "observaciones"=>"Ninguna observacion"]);
    };

   //die('aca 0');
   // *** SE CREA EL USUARIO ***
   $datos_usuario = $usuario->getUsuarioByTipoByDni(1,$dni);
   $usuario_id = $datos_usuario['id'];
   if ($usuario_id) {
      $usuario_res = $usuario->save(["id"=>$usuario_id,"nombre"=>$dni, "dni"=>$dni, 
                                     "idTipo"=>1, "idRol"=>4, "idPersona"=>$persona_res]);
   } else {
      //var_dump(["nombre"=>$dni, "dni"=>$dni, "idTipo"=>1,
      //"password"=>md5($dni), "idRol"=>4,"idPersona"=>$persona_id]);exit;
      $usuario_res = $usuario->save(["nombre"=>$dni, "dni"=>$dni, "idTipo"=>1,
                                 "password"=>md5($dni), "idRol"=>4,"idPersona"=>$persona_res]);
                                 
   };
   // *** SE CREA EL ALUMN0 ***
   $datos_alumno = $alumno->getAlumnoByDni($dni);
   //var_dump($datos_alumno);exit;
   $alumno_id = $datos_alumno['id'];
   if ($alumno_id) {
      $alumno_res = $alumno->save(["id"=>$alumno_id,"dni"=>$dni, "apellido"=>$apellido, "nombre"=>$nombres, "anio_ingreso"=>$anio_ingreso,
                                   "debe_titulo"=>'No', "habilitado"=>'Si',"idPersona"=>$persona_res]);
   } else {
      $alumno_res = $alumno->save(["dni"=>$dni, "apellido"=>$apellido, "nombre"=>$nombres, "anio_ingreso"=>$anio_ingreso,
                                   "debe_titulo"=>'No', "habilitado"=>'Si',"idPersona"=>$persona_res]);
   };

   // *** SE CREA EL VINCULO ENTRE ALUMN0 Y CARRERA ***
   $alumno_carrera_res = $alumnoEstudiaCarrera->save(["idAlumno"=>$alumno_res, "idCarrera"=>$carrera , "anio"=>$anio_ingreso, "mesa_especial"=>'No',"fecha_inscripcion"=>date('Y-m-d')]);
   
   // *** ARMO EL ARREGLO DE RETORNO ***
   if ($persona_res && $usuario_res && $alumno_res && $alumno_carrera_res) {
      $array_resultados['codigo'] = 100;
      $array_resultados['mensaje'] = "El Alumno fue Actualizado.";  
   } else {
      $array_resultados['codigo'] = 9;
      $array_resultados['mensaje'] = "Ocurrió un Error.";  
   }

};

echo json_encode($array_resultados);

 ?>
