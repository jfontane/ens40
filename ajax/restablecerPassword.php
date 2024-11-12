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
               $titulo    = 'Recuperacion de Clave de Ingreso';
               
               $imageUrl = "https://escuela40.net/public/img/encabezado_ens40_1.jpeg";
               $mensaje = "
                        <html>
                        <head>
                          <title>Clave nueva</title>
                          <style>
                            .header {
                              font-size: 24px;
                              font-weight: bold;
                              color: #333;
                              text-align: center;
                              margin-top: 20px;
                            }
                            .code {
                              font-size: 28px;
                              font-weight: bold;
                              color: #4CAF50;
                              text-align: center;
                            }
                            .content {
                              font-size: 16px;
                              color: #555;
                              text-align: center;
                            }
                            .container {
                              width: 100%;
                              max-width: 600px;
                              margin: 0 auto;
                              padding: 20px;
                              border: 1px solid #ddd;
                              border-radius: 8px;
                            }
                            .image {
                              width: 100%;
                              height: auto;
                            }
                          </style>
                        </head>
                        <body>
                          <div class='container'>
                            <img src='$imageUrl' alt='Encabezado' class='image'>
                            <p class='header'>La clave fue modificada.</p>
                            <p class='content'>Hola,</p>
                            <p class='content'>tu nueva clave es:</p>
                            <p class='code'>$valor</p>
                            <p class='content'>Introduce este c&oacute;digo en la aplicaci&oacute;n para Ingresar. </p>
                            <p class='content'>Recuerda volver a cambiar la clave una vez que ingresaste. </p>
                            <p class='content'>Saludos,<br>Tu equipo de soporte</p>
                          </div>
                        </body>
                        </html>
                        ";
               
               
               /*
               
               $mensaje   = "Se ha generado una nueva contrase&ntilde;a: <p style='font-size:40px;'>".$valor."</p></h1></strong>";
               $header = "Content-type: text/html; charset=".$encoding." \r\n";
               $header .= "From: NoResponder@escuela40.net  \r\n";
               $header .= "MIME-Version: 1.0 \r\n";*/
               
               // Encabezados para enviar correo en formato HTML
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: soporte@escuela40.net" . "\r\n";


               mail($para, $titulo, $mensaje, $headers);
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