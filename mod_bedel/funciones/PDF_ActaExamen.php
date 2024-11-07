<?php
set_include_path('../../app/lib/'.PATH_SEPARATOR.'../../conexion/');
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/');
require_once('AlumnoRindeMateriaDetalle.php');
require_once('Carrera.php');
require_once('Materia.php');
require_once('ActasPdf.class.php');

// create new PDF document
$pdf = new ActasPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Escuela Normal Superior 40 - Mariano Moreno');
$pdf->SetTitle('Acta Volante');
$pdf->SetSubject('Examenes Finales');
$pdf->SetKeywords('Acta, Volante, Finales');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', 'BI', 12);

// add a page
$pdf->AddPage();

$parametros=$_GET['parametros'];
$arrCarreraTurnoLlamado=explode('_',$parametros);
$idCarrera=$arrCarreraTurnoLlamado[0];
$idCalendario=$arrCarreraTurnoLlamado[1];
$llamado=$arrCarreraTurnoLlamado[2];
$idMateria=$arrCarreraTurnoLlamado[3];

$alumnos_rinden_materia = new AlumnoRindeMateriaDetalle();
$ARRAY_ALUMNOS_RINDEN_MATERIA = $alumnos_rinden_materia->getAlumnosByIdMateriaByIdCalendarioDetalle($idMateria,$idCalendario,$llamado);

$carrera = new Carrera();
$carrera_nombre = $carrera->getCarreraById($idCarrera)['descripcion_corta'];

$materia = new Materia();
$materia_nombre = $materia->getMateriaById($idMateria)['nombre'];
$materia_anio = $materia->getMateriaById($idMateria)['anio'];
$materia_anio_nombre = "";
if ($materia_anio==1) $materia_anio_nombre=' PRIMERO';
else if ($materia_anio==2) $materia_anio_nombre=' SEGUNDO';
else if ($materia_anio==3) $materia_anio_nombre=' TERCERO';
else if ($materia_anio==4) $materia_anio_nombre=' CUARTO';

$pdf->Ln(15);
//Arial bold 15
$pdf->SetFont('courier','B',10);
    //Movernos a la derecha
   //   $this->Cell(80);
    //T�tulo
$pdf->SetY(25);    
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(190,7,'ACTA VOLANTE DE EVALUACIONES',0,1,'R',true);

$pdf->Ln(1);
$pdf->SetFillColor(204,204,204);
$pdf->SetTextColor(0,0,0);
// Segundo Grupo
    
$dd=date('d');
$mm=date('m');
$yy=date('Y');

$pdf->SetX(11);
$pdf->SetFillColor(255,255,255);	
$pdf->Cell(52,7,'EVALUACIONES DE ALUMNOS:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(105,7,'REGULARES FINALES',0,0,'L',TRUE);
$pdf->SetFont('courier','B',10);
$pdf->Cell(10,7,'DIA',1,0,'L',TRUE);
$pdf->Cell(10,7,'MES',1,0,'L',TRUE);
$pdf->Cell(10,7,'AÑO',1,1,'L',TRUE);
$pdf->SetX(11);
$pdf->Cell(25,7,'ASIGNATURA:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(132,7,$materia_nombre,0,0,'L',TRUE);
$pdf->Cell(10,9,$dd,1,0,'L',TRUE);
$pdf->Cell(10,9,$mm,1,0,'L',TRUE);
$pdf->Cell(10,9,$yy,1,1,'L',TRUE);

$pdf->SetX(11);
$pdf->SetFont('courier','B',10);
$pdf->Cell(18,7,'CARRERA:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(65,7,$carrera_nombre,0,0,'L',false);
$pdf->SetFont('courier','B',10);
$pdf->Cell(8,7,'AÑO:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(25,7,$materia_anio_nombre,0,0,'L',false);
$pdf->SetFont('courier','B',10);
$pdf->Cell(20,7,'DIVISION:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(16,7,'UNICA',0,0,'L',false);
$pdf->SetFont('courier','B',10);
$pdf->Cell(14,7,'TURNO:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(18,7,'VESPERTINO',0,1,'L',false);

$pdf->SetX(11);
$pdf->SetFont('courier','',8);
$pdf->Cell(15,10,'Orden',1,0,'C',true);
$pdf->Cell(15,10,'Permiso',1,0,'C',true);
$pdf->Cell(32,10,'DOC. DE IDENTIDAD',1,0,'C',true);
$pdf->Cell(68,10,'APELLIDO Y NOMBRES',1,0,'C',true);
$pdf->Cell(30,10,'EVALUACIONES',1,0,'C',true);
$pdf->Cell(30,10,'OBSERVACIONES',1,1,'C',true);
$cant_desaprobados = $cant_ausentes = $i = 0;
foreach ($ARRAY_ALUMNOS_RINDEN_MATERIA as $item) {
    if ($item['condicion']!='Promocion') {
        $i++;
        $pdf->SetX(11);
        if ($item['nota']==-1) {$nota='-';$determ='AUSENTE';$cant_ausentes++;}
        elseif ($item['nota']==0) {$nota='';$determ='';}
        elseif ($item['nota']==1) {$nota='1';$determ='(Uno)';$cant_desaprobados++;}
        elseif ($item['nota']==2) {$nota='2';$determ='(Dos)';$cant_desaprobados++;}
        elseif ($item['nota']==3) {$nota='3';$determ='(Tres)';$cant_desaprobados++;}
        elseif ($item['nota']==4) {$nota='4';$determ='(Cuatro)';$cant_desaprobados++;}
        elseif ($item['nota']==5) {$nota='5';$determ='(Cinco)';$cant_desaprobados++;}
        elseif ($item['nota']==6) {$nota='6';$determ='(Seis)';$cant_aprobados++;}
        elseif ($item['nota']==7) {$nota='7';$determ='(Siete)';$cant_aprobados++;}
        elseif ($item['nota']==8) {$nota='8';$determ='(Ocho)';$cant_aprobados++;}
        elseif ($item['nota']==9) {$nota='9';$determ='(Nueve)';$cant_aprobados++;}
        elseif ($item['nota']==10) {$nota='10';$determ='(Diez)';$cant_aprobados++;}
    
        $pdf->Cell(15,5,$i,1,0,'C',false);
        $pdf->Cell(15,5,'',1,0,'R',false);
        $pdf->Cell(32,5,$item['dni'],1,0,'C',false);
        $pdf->Cell(68,5,$item['apellido'].', '.$item['nombre'],1,0,'L',false);
        $pdf->Cell(15,5,$nota,1,0,'C',false);
        $pdf->Cell(15,5,$determ,1,0,'C',false);
        $pdf->Cell(30,5,$item['condicion'],1,1,'R',false);
    }
};


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('ActaVolante.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+