<?php
//die('entre');
require_once("header_listado.php");
//print_r($_GET);
//$db->debug=true;
//print_r($_SESSION);
//$fecha=$_GET['fecha'];
//$fhasta=$_GET['fhasta'];
/*$quefecha='';*/
//try {
	$rs_consulta = $db->Execute("SELECT b.id_tipo_alerta idid,
				B.descripcion as descripcion,
				b.funcion as funcion					
				FROM PLA_AUDITORIA.tipo_alerta b							
				order by 2");
/*}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}*/	


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
	$pdf->Cell(190,8,'MATRIZ DE RIESGO - ADMINISTRACION DE TIPO DE ALERTAS '.$_GET['fecha'],0,1,'C',1);
	$pdf->Cell(190,8,'[Datos  Resguardados]',0,1,'C',1);
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(35,8,'Descripcion',1,0,'C');
	$pdf->Cell(145,8,'Funcion',1,1,'C');
	/*$pdf->Cell(35,8,'Dato que Ocasiona el Alerta',1,0,'C');
	$pdf->Cell(98,8,'Alerta',1,1,'C');*/
	
	
	$pdf->SetFont('Arial','',6);
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
	$pdf->Cell(190,8,'MATRIZ DE RIESGO - ADMINISTRACION DE TIPO DE ALERTAS '.$_GET['fecha'],0,1,'C',1);
	$pdf->Cell(190,8,'[Datos  Resguardados]',0,1,'C',1);
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(35,8,'Descripcion',1,0,'C');
	$pdf->Cell(145,8,'Funcion',1,1,'C');
	$pdf->SetFont('Arial','',6);		 
 } 
 $pdf->SetX(10);
  
	$pdf->Cell(35,5,$row->DESCRIPCION,1,0,'L');		  
	$pdf->MultiCell(145,5,utf8_decode($row->FUNCION),1,'L');
}

$pdf->Output();
?>