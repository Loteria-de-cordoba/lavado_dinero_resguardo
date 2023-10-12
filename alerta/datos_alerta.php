<?php
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
$db->debug=true;
die('entre');
//print_r($_SESSION);
//$fecha=$_GET['fecha'];
//$fhasta=$_GET['fhasta'];
if(isset($_REQUEST['condicion']) and $_REQUEST['condicion']<>'')
	{
		$condicion=strtolower($_REQUEST['condicion']);
		//$condicion_descripcion="and lower(a.descripcion) like'%$descrip%'";
	}
	else
	{
		//$descrip="";
		$condicion="";
	
	}
/*$quefecha='';*/
try {
	$rs_consulta = $db->Execute("SELECT b.id_base idid,
				b.documento documento,
				b.descripcion nombre,
				b.id_tipo_alerta alert,
				ta.descripcion nombre_alerta  	 
				FROM PLA_AUDITORIA.base_alerta b,
					PLA_AUDITORIA.tipo_alerta ta
				  WHERE b.id_tipo_alerta=ta.id_tipo_alerta
				  $condicion					
				order by 4,2");
}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}	

require_once("header_listado.php"); 
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
	$pdf->Cell(190,8,'Administracion de Alertas Sistema PLA',0,1,'C',1);
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(25,8,'Tipo de Alerta',1,0,'C');
	$pdf->Cell(50,8,'Documento/Domicilio que Ocasiona el Alerta',1,0,'C');
	$pdf->Cell(125,8,'Alerta',1,1,'C');
	
	
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
	$pdf->Cell(190,8,'Administracion de Alertas Sistema PLA',0,1,'C',1);
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(25,8,'Tipo de Alerta',1,0,'C');
	$pdf->Cell(50,8,'Documento/Domicilio que Ocasiona el Alerta',1,0,'C');
	$pdf->Cell(125,8,'Alerta',1,1,'C');
	
	
	$pdf->SetFont('Arial','',5);
		
		 
 } 
 $pdf->SetX(10);
  if($row->NOMBRE_ALERTA<>$repetido)
  {
		  			 $pdf->Cell(25,5,$row->NOMBRE_ALERTA,1,0,'C');
					 $repetido=$row->NOMBRE_ALERTA;
		   }
		   else
		   {
		   	$pdf->Cell(25,5,'',1,0,'L');
		   }
		   
	$pdf->Cell(50,5,str_pad($row->DOCUMENTO,8,'0',STR_PAD_LEFT);,1,0,'C');
	$pdf->MultiCell(125,5,utf8_decode($row->NOMBRE),1,'L');
	

}

$pdf->Output();
?>