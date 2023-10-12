<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '-'.$_GET['delegacion'];
require("header_listado.php"); 

//print_r($_GET);
//die();
//$db->debug=true;
//print_r($_SESSION);
$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];
$casino=$_GET['casino'];
		if($casino==0)
			{
				$condicion_casino='';
			}
			else
			{
				$condicion_casino="and b.id_casino='$casino'";
			}
$quefecha='';
	$rs_consulta = $db->Execute("select to_char(a.fecha,'dd/mm/yyyy') as fecha,
								 a.casino as id_casino,  a.novedad as descripcion,
								 b.n_casino as casino
								 from PLA_AUDITORIA.t_observa_casino a,
										casino.t_casinos b
								 where a.casino=b.id_casino
										and a.fecha between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
										$condicion_casino
								 ORDER BY  1 desc,2");
	


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
	$pdf->Cell(190,8,'Consulta de Observaciones Generales [Datos Resguardados]',0,1,'C',1);
	$pdf->Cell(190,8,'Registradas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(15,8,'Fecha',1,0,'C',1);
	$pdf->Cell(30,8,'Origen',1,0,'C',1);
	$pdf->Cell(146,8,'Observacion',1,1,'C',1);
	
	
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
	$pdf->Cell(190,8,'Consulta de Observacones Generales',0,1,'C',1);
	$pdf->Cell(190,8,'Registradas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(15,8,'Fecha',1,0,'C',1);
	$pdf->Cell(30,8,'Origen',1,0,'C',1);
	$pdf->Cell(146,8,'Observacion',1,1,'C',1);
	
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
	$pdf->Cell(30,5,$row->CASINO,1,0,'L');
	$pdf->MultiCell(146,5,$row->DESCRIPCION,1,'L');
	

}

$pdf->Output();
?>