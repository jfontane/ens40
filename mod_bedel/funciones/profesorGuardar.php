<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');
require_once "Sanitize.class.php";

require_once "Persona.php";
require_once "Profesor.php";
require_once "Usuario.php";
require_once "_seguridad.php";


$apellido = (isset($_POST['apellido']) && $_POST['apellido']!=NULL)?SanitizeVars::UTF8($_POST['apellido']):false;
$nombres = (isset($_POST['nombres']) && $_POST['nombres']!=NULL)?SanitizeVars::UTF8($_POST['nombres']):false;
$dni = (isset($_POST['dni']) && $_POST['dni']!=NULL)?SanitizeVars::INT($_POST['dni']):false;
$fecha_nacimiento = (isset($_POST['fecha_nacimiento']) && $_POST['fecha_nacimiento']!=NULL)?SanitizeVars::DATE($_POST['fecha_nacimiento']):NULL;
$domicilio = (isset($_POST['domicilio']) && $_POST['domicilio']!=NULL)?SanitizeVars::STRING($_POST['domicilio']):false;
$telefono_caracteristica = (isset($_POST['telefono_caracteristica']))?SanitizeVars::INT($_POST['telefono_caracteristica']):false;
$telefono_numero = (isset($_POST['telefono_numero']))?SanitizeVars::INT($_POST['telefono_numero']):false;
$email = (isset($_POST['email']) && $_POST['email']!=NULL)?SanitizeVars::EMAIL($_POST['email']):false;
$localidad_id = (isset($_POST['localidad_id']) && $_POST['localidad_id']!=NULL)?SanitizeVars::INT($_POST['localidad_id']):false;



//var_dump($_POST);die;
//die($apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono_caracteristica.'-'.$telefono_numero.'-'.$email.'-'.$localidad_id.'-'.$fecha_nacimiento);

$array_resultados = array();

$profesor = new Profesor();
$persona = new Persona();
$usuario = new Usuario();
$persona_res = $usuario_res = $profesor_res = "";

if ($apellido && $nombres && $dni && $domicilio && $fecha_nacimiento && 
    $telefono_caracteristica && $telefono_numero && $email && $localidad_id) {
    
    

    /*$arr_per = ["dni"=>"35772995", "apellido"=>"Fonta", "nombres"=>"Javier Hernan", 
               "fecha_nacimiento"=>"2020-02-01", "localidad_id"=>"1401", "domicilio"=>"Espora 2278", 
               "email"=>"jfontane@frsf.com.ar", "telefono_caracteristica"=>"342", "telefono_numero"=>"4604140", "genero"=>"M", 
               "estado_civil"=>"Soltero", "ocupacion"=>"Estudiante", 
               "titulo"=>"Bachiller en informatica", "titulo_expedido_por"=>"Escuela de comercio", 
               "observaciones"=>"Ninguna observacion"];*/
    
    $datos_persona = $persona->getPersonaByDni($dni);  
    $persona_id = $datos_persona['id'];
    if ($persona_id) {
      $persona_res = $persona->save(["id"=>$persona_id,"dni"=>$dni,"apellido"=>$apellido,"nombres"=>$nombres,
                                    "fecha_nacimiento"=>$fecha_nacimiento,"domicilio"=>$domicilio,
                                    "telefono_caracteristica"=>$telefono_caracteristica, "telefono_numero"=>$telefono_numero,
                                    "email"=>$email,"localidad_id"=>$localidad_id]);
    } else {
      $persona_res = $persona->save(["dni"=>$dni,"apellido"=>$apellido,"nombres"=>$nombres,
                                    "fecha_nacimiento"=>$fecha_nacimiento,"domicilio"=>$domicilio,
                                    "telefono_caracteristica"=>$telefono_caracteristica, "telefono_numero"=>$telefono_numero,
                                    "email"=>$email,"localidad_id"=>$localidad_id,"genero"=>"O"]);
    };

    $datos_usuario = $usuario->getUsuarioByTipoByDni(1,$dni);
    $usuario_id = $datos_usuario['id'];
    if ($usuario_id) {
      $usuario_res = $usuario->save(["id"=>$usuario_id,"nombre"=>$dni, "dni"=>$dni, 
                                     "idTipo"=>1, "idRol"=>3, "idPersona"=>$persona_res]);
    } else {
      $usuario_res = $usuario->save(["nombre"=>$dni, "dni"=>$dni, "idTipo"=>2,
                                 "password"=>md5($dni), "idRol"=>3,"idPersona"=>$persona_res]);
    };

    $datos_profesor = $profesor->getProfesorByDni($dni);
    $profesor_id = $datos_profesor['id'];
    if ($profesor_id) {
      $profesor_res = $profesor->save(["id"=>$profesor_id,"dni"=>$dni, "apellido"=>$apellido, "nombre"=>$nombres,"idPersona"=>$persona_res]);
    } else {
      $profesor_res = $profesor->save(["dni"=>$dni, "apellido"=>$apellido, "nombre"=>$nombres, "idPersona"=>$persona_res]);
    };

    if ($persona_res && $usuario_res && $profesor_res) {
      $array_resultados['codigo'] = 100;
      $array_resultados['mensaje'] = "El Profesor fue Actualizado.";  
    } else {
      $array_resultados['codigo'] = 9;
      $array_resultados['mensaje'] = "Ocurrió un Error.";  
    }

} else {
   $array_resultados['codigo'] = 10;
   $array_resultados['mensaje'] = "Existen Datos Obligatorios que no se han ingresados.";  
}


echo json_encode($array_resultados);

exit;

?>
