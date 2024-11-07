<?php
require_once('Sanitize.class.php');

abstract class SanitizeCustom extends SanitizeVars {

  
  public static function APELLIDO_NOMBRES($string, $min='2', $max=''){
      $patron = "/^[a-zA-ZñÑ áéíóúÁÉÍÓÚñÑäëïöüÄËÏÖÜ]*$/";
      if (preg_match($patron, $string)) {
      $len = strlen($string);
      if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
      return FALSE;
      return $string;
    } else return FALSE;
  }

  public static function DOCUMENTO_CUIL($string, $min='', $max=''){
    $string = preg_replace("/[^0-9]/", "", $string);
    $len = strlen($string);
    if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max))) return FALSE;
    return $string;
  }

  public static function DOMICILIO($string, $min='2', $max=''){
    $patron = "/^[a-zA-Z0-9ñÑ áéíóúÁÉÍÓÚñÑäëïöüÄËÏÖÜ°.]*$/";
    if (preg_match($patron, $string)) {
      $len = strlen($string);
      if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max))) return FALSE;
      return $string;
    } else return FALSE;
  }

}//-- end CLASS
