<?php
session_start();
set_include_path('../app/lib/'.PATH_SEPARATOR.'../app/models/');

require_once "Persona.php";
require_once "Usuario.php";

include_once 'SanitizeCustom.class.php';


function rellenar($val) {
    $cantidad_digitos = strlen($val."");
    $cantidad_ceros = 6 - $cantidad_digitos;
    $str_ceros = "";
    
    for($i=0;$i<$cantidad_ceros;$i++) {
        $str_ceros .= '0';
    }
    
    $valor_final = $str_ceros.$val;
    return $valor_final;
    
}

function generarCodigo() {
    $val = rand(1,999999);
    return rellenar($val);
}

$perfil = $_POST['inputPerfil'];
$email = $_POST['inputEmail'];
$codigo = $_POST['inputCodigo'];

//die($_SESSION['security_code'].'-'.$perfil.'-'.$email.'-'.$codigo);


$arr_resultado = [];

if ($perfil && $email && $codigo) {
    if ($_SESSION['security_code']==strtoupper($codigo)) {
        
        
        $persona = new Persona();
        $objusu = new Usuario();
        $arr_datos_persona = $persona->getPersonaByEmail($email); //var_dump($arr_datos_persona);exit;
        $persona_id = $arr_datos_persona['id'];
        $arr_usu = $objusu->getUsuarioByTipoByIDPersona($perfil,$persona_id);//var_dump($arr_usu);exit;//($perfil,$usuario);
        $id_usu = $arr_usu['id'];

        if ($email==$arr_datos_persona['email']) {
            $valor = generarCodigo();
            //$id_usuario = $arr_usu['id'];
            //die('acaaa');
            //var_dump($id_usu,$valor);die;
            $res = $objusu->setPasswordById($id_usu,$valor);
            if ($res) {
               $arr_resultado['codigo'] = 200;
               $arr_resultado['perfil'] = $perfil;
               $arr_resultado['mensaje'] = 'Una nueva contrase&ntilde;a ha sido enviada a su correo electronico.';
               $arr_resultado['class'] = 'success';
               $para      = $email;
               $titulo    = 'Recuperacion de Contrase&ntilde;a';
               $mensaje   = "Se ha generado una nueva contrase&ntilde;a: <p style='font-size:40px;'>".$valor."</p></h1></strong>";
               $header = "Content-type: text/html; charset=".$encoding." \r\n";
               $header .= "From: NoResponder@escuela40.net  \r\n";
               $header .= "MIME-Version: 1.0 \r\n";
               //mail($para, $titulo, $mensaje, $header);
            } else {
                $arr_resultado['codigo'] = 501;
                $arr_resultado['mensaje'] = 'Ocurrio un Error.';
                $arr_resultado['class'] = 'danger';
            }
        } else {
            $arr_resultado['codigo'] = 502;
            $arr_resultado['mensaje'] = 'El Email que ingresa NO coincide con el que tiene registrado.';
            $arr_resultado['class'] = 'danger';
        }

    } else {
        $arr_resultado['codigo'] = 503;
        $arr_resultado['mensaje'] = 'El código ingresado no es correcto.';
        $arr_resultado['class'] = 'danger';
    }
    
} else {
    $arr_resultado['codigo'] = 504;
    $arr_resultado['mensaje'] = 'Debe completar todos los campos de texto.';
    $arr_resultado['class'] = 'danger';
}

echo json_encode($arr_resultado);







?>