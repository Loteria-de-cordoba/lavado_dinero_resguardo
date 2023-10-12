<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '-'.$_GET['delegacion'];
require("header_listado.php"); 

//print_r($_GET);

//$db->debug=true;
//print_r($_SESSION);
$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];
if(isset($_REQUEST['descrip']) and $_REQUEST['descrip']<>'')
	{
		$descrip=strtolower($_REQUEST['descrip']);
		$condicion_descripcion="and lower(a.descripcion) like'%$descrip%'";
	}
	else
	{
		$descrip="";
		$condicion_descripcion="";
	
	}
$quefecha='';
	$rs_consulta = $db->Execute("select to_char(a.fecha,'dd/mm/yyyy') as fecha, a.hora,  a.descripcion
									 from PLA_AUDITORIA.t_auditoria_externa a
									 where a.fecha between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
									 $condicion_descripcion
									 ORDER BY  a.fecha,2");
	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();


//GETx;

$pdf->ln(-10);
//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line); 

$pdf->Ln(-10);
$pdf->SetFillColor(240,240,240);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(190,8,'Control de Acceso al Sistema P.L.A.[Datos Resguardados]',0,1,'C',1);
	$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(15,8,'Fecha',1,0,'C');
	$pdf->Cell(176,8,'Descripcion de la Tarea',1,1,'C');
	
	
	$pdf->SetFont('Arial','',5);
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
 $estoy=$pdf->GetY();
 if($estoy>265)
 {
 	$estoy=0;
		 $pdf->Addpage();
		 $pdf->Ln(-10);	
		//$pdf->Ln(-10);
$pdf->SetFillColor(240,240,240);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(190,8,'Control de Acceso al Sistema P.L.A.[Datos Resguardados]',0,1,'C',1);
	$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(15,8,'Fecha',1,0,'C');
	$pdf->Cell(176,8,'Descripcion de la Tarea',1,1,'C');
	
	
	$pdf->SetFont('Arial','',5);
		
		 
 } 
 $pdf->SetX(10);
 if($row->FECHA<>$quefecha or $estoy==0)
		   {
		  			 $pdf->Cell(15,5,$row->FECHA,1,0,'C');
		   }
		   else
		   {
		   	$pdf->Cell(15,5,'',1,0,'L');
		   }
		   $quefecha=$row->FECHA;
	
	$pdf->MultiCell(176,5,utf8_decode($row->DESCRIPCION),1,'L');
	

}

$pdf->Output();
?>