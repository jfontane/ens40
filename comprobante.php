<?php

include('./conexion/conexion.php');

// Usa la clase fpdf para generar el comprobante pdf para presentar en bedelia

require('./lib/fpdf/fpdf.php');

// Recibe el dni del inscripto o ingresante 




//$arg = 'NDYwODI4MDUmMjAyMw==';
//$arg = base64_encode('46082805&2023');
//$argumentos = explode("&",base64_decode($arg));

$argumentos = explode("&",base64_decode($_REQUEST['r']));

$dni = $argumentos[0];
$anio_lectivo = $argumentos[1];

$sql = "SELECT c.descripcion as 'NombreCarrera', p.nombre as 'Nombre', p.apellido as 'Apellido', 
               p.dni as 'DNI', p.fechaNacimiento as FechaNacimiento, p.localidad as 'Localidad', 
               p.nacionalidad as 'Nacionalidad', p.domicilioCalle as 'Domicilio', 
               p.telefono as 'Celular', p.email as 'Email', ac.fecha_inscripcion as 'FechaInscripcion',
               p.estado_civil as 'EstadoCivil', p.ocupacion as 'Ocupacion',
               p.titulo as 'Titulo', p.titulo_expedido_por as 'Escuela'
         FROM persona p, alumno a, carrera c, alumno_estudia_carrera ac 
         WHERE p.dni = '$dni' AND 
               p.dni = a.dni AND 
               a.id = ac.idAlumno AND 
               ac.idCarrera = c.id AND 
               ac.anio = '$anio_lectivo'";

$resultado = mysqli_query($conex,$sql);  

$fila = "";
if ($resultado) {
   if (mysqli_num_rows($resultado)>0) {
        $fila = mysqli_fetch_assoc($resultado);               
   } else {
        die("Error (Cod.1): No se pudo generar el PDF.");
   }
} else {
    die("Error (Cod.2): No su pudo generar el PDF.");
};


//var_dump($fila);

class PDF extends FPDF {
// Cabecera de página

function Header()
{

// Logo
$this->Image('assets/img/LogoENS40.jpg', 55, 10 , 25, 25); 
// Arial bold 15 //TITULO
$this->SetFont('Arial', 'I', 8);
// Movernos a la derecha titulo
$this->Cell(50);
// Título
$this->Cell(106, 5, utf8_decode('Escuela Normal Superior "Mariano Moreno Nº 40'), 0, 1, 'C');
$this->Cell(195, 5, 'Nivel Superior - Tel. Fax. 03408-422447', 0, 1, 'C');
$this->Cell(196, 5, 'E-Mail: superiorbedelia40@yahoo.com.ar', 0, 1, 'C');
$this->Cell(210, 5,  utf8_decode('Juan M Bullo 1402 - 3070 - San Cristóbal (Santa Fe)'), 0, 1, 'C');
$this->Cell(192, 5,  utf8_decode('Sitio Web: https://ens40-sfe.infd.edu.ar'), 0, 0, 'C');
// Salto de línea
$this->Ln(3);
//$this-> Line(25,20,(210-25),20);

}

// Pie de página
function Footer() {
//Posición: a 1,5 cm del final

$this->SetY(-50);
// Arial italic 8
$this->SetFont('Arial', 'U', 8);
//Número de página
$this->Cell(10);
$this->Cell(59, 5,  utf8_decode('DOCUMENTACIÓN PARA PRESENTAR:'), 0, 1, 'L');

$this->SetFont('Arial', 'I', 8);
$this->Cell(10);
$this->Cell(130, 5, 'PARTIDA DE NACIMIENTO -  CERTIFICADO DE ESTUDIOS COMPLETOS NIVEL SECUNDARIO', 0, 1, 'L');
$this->Cell(10);
$this->Cell(68, 5, 'FOTOCOPIA DNI -  CERTIFICADO VECINDAD', 0, 1, 'L');
$this->Cell(10);
$this->Cell(65, 5, 'FOTO 4X4 -  CERTIFICADO BUENA SALUD', 0, 1, 'L');
$this->Cell(10);
$this->Cell(186, 5, utf8_decode('La inscripción se considerará definitiva y completa, cuando el ingresante presente toda la documentación que respalde la Declaración Jurada'), 0, 1, 'L');
$this->Cell(10);
$this->Cell(62, 5, utf8_decode('de los Artículos 6° y 8° del decreto 4199/15'), 0, 1, 'L');
$this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');

//$this-> Line(25,20,(210-25),20);
}

}

/*$fila = array("Apellido" => 'Fontanellaz', "Nombre"=>'javier hernan', 
              "FechaInscripcion"=>'2022-11-23',"FechaNacimiento"=>'06-02-1976',
              "NombreCarrera"=>'Tecnicatura en Desarrollo de Software',"Sexo"=>'Masculino',"DNI"=>'24912834',
              "Domicilio"=>'Espora 2278',"Localidad"=>'Santa Fe',"Email"=>'jfontane@santafe.gov.ar',"Celular"=>'342-23343434');*/

if (count($fila)>0) {
    

$fechai =$fila['FechaInscripcion'];
$fi = explode('-',$fechai);
$fechan =$fila['FechaNacimiento'];
$fn = explode('-',$fechan);
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(200, 10, utf8_decode('SOLICITUD DE INSCRIPCIÓN'),  0, 1, 'C');
$pdf->Ln(3);

//ESTABLECIMIENTO
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(20, 10,'ESTABLECIMIENTO:', 0, 0, 'L');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(92, 10, utf8_decode('"ESCUELA NORMAL SUPERIOR Nº 40 "MARIANO MORENO"'),  0, 0, 'R');

//NIVEL
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(20, 10, 'NIVEL:',  0, 0, 'R');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(18, 10, 'TERCIARIO ',  0, 1, 'R');

//CICLO LECTIVO
$pdf->SetFont('Arial', 'U', 8);
$pdf->Cell(10);
$pdf->Cell(25, 10, 'CICLO LECTIVO:',  0, 0, 'R');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(25, 10, $anio_lectivo.' ',  0, 0, 'L');

//FECHA DE INSCRIPCION
$pdf->SetFont('Arial', 'U', 8);
$pdf->Cell(107, 10,utf8_decode('FECHA DE INSCRIPCIÓN:'),  0, 0, 'R'); 
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10,$fi[2].'/'.$fi[1].'/'.$fi[0],  0, 1, 'L');
$pdf->Ln(2);

//CARRERA
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(17, 10, 'CARRERA:',  0, 0, '');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, $fila['NombreCarrera'], 0, 0, 'L');


//Anio
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(112, 10, utf8_decode('AÑO:'),  0, 0, 'R');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(5, 10, utf8_decode('1°'),  0, 0, 'R');

//Division
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(150, 10, utf8_decode('DIVISIÓN: ÚNICA'),  0, 1, 'L');


//DATOS PERSONALES
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(10);
$pdf->Cell(180, 10, utf8_decode('DATOS PERSONALES'),  0, 1, 'L');

//NOMBRE Y APELLIDO
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(36, 10, 'APELLIDO Y NOMBRES:',  0, 0, '');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, utf8_decode(ucfirst($fila['Apellido']." ".$fila['Nombre'])), 0, 1, 'L');
//$pdf->Cell(1, 10, ucfirst($fila['Apellido']), 0, 1, 'L');

//DNI
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(27, 10, utf8_decode('Nº DOCUMENTO:'),  0, 0, '');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(56, 10, $fila['DNI'], 0, 0, 'L');

//FECHA DE NACIMIENTO
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(37, 10, 'FECHA DE NACIMIENTO:',  0, 0, '');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(37, 10,$fn[2].'/'.$fn[1].'/'.$fn[0], 0, 1, 'L');

//ESTADO CIVIL
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(24, 10, 'ESTADO CIVIL:',  0, 0, 'L');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(37, 10, ucfirst($fila['EstadoCivil']), 0, 1, 'L');

//DOMICILIO
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(31, 10, 'DOMICILIO ACTUAL:',  0, 0, 'L');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, utf8_decode(ucfirst($fila['Domicilio'])), 0, 1, '');

//LOCALIDAD DE RESIDENCIA:
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(20, 10, utf8_decode('LOCALIDAD:'),  0, 0, 'L');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, utf8_decode(ucfirst($fila['Localidad'])), 0, 0, '');

//TELÉFONO
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(91, 10, utf8_decode('TELÉFONO:'),  0, 0, 'R');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, $fila['Celular'], 0, 1, '');

//CORREO ELECTRONICO
$pdf->SetFont('Arial', 'U', 8);
$pdf->Cell(10);
$pdf->Cell(36, 10, utf8_decode('CORREO ELECTRÓNICO:'),  0, 0, 'L');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, $fila['Email'], 0, 1, '');

//TITULO NIVEL MEDIO
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(33, 10, 'TITULO NIVEL MEDIO:',  0, 0, 'L');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, utf8_decode(ucfirst($fila['Titulo'])), 0, 1, '');

//EXPEDIDO
//$pdf->Cell(85, 10, 'EXPEDIDO POR:',  0, 0, 'R');
//$pdf->SetFont('Arial', 'I', 8);
//$pdf->Cell(1, 10, utf8_decode(ucfirst($fila['Escuela'])), 0, 1, '');

//EXPEDIDO POR
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(25, 10, utf8_decode('EXPEDIDO POR:'),  0, 0, 'L');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, utf8_decode(ucfirst($fila['Escuela'])), 0, 1, '');

//OCUPACION
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(20, 10, utf8_decode('OCUPACIÓN:'),  0, 0, 'L');
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(1, 10, utf8_decode(ucfirst($fila['Ocupacion'])), 0, 1, '');
$pdf->Ln(5);

//FIRMA Y ACLARACION
$pdf->SetFont('Arial', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(220, 10, utf8_decode('FIRMA Y ACLARACIÓN:'),  0, 1, 'C');
$pdf->SetFont('Arial', 'I', 8);


$pdf->Output('I','ComprobanteInscripcionENS40.pdf');
//$pdf->Output();
} else {
    //header("Location:https://exercitati.net/recuperar.php" );
   
}
?>
   