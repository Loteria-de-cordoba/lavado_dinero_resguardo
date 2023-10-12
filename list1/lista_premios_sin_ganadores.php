<?php

$titulo='Premios Pagados sin Registros de Ganadores';

require("header_listado.php"); 
//print_r($_GET);die();
$consulta= $_SESSION['sqlreporte'];
//$db->debug=true;



try{
	
	 $rs = $db->Execute("$consulta"); 
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
	
//$row = $rs->FetchNextObject($toupper=true);

$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

/*$pdf->SetFont('Arial','B',10);
$pdf->Cell(185,7,'Ref: Prevencion Lavado de Activos ',0,1,'R');*/

 
 

//$pdf->SetFont('Arial','U',10);
//$pdf->setx(10);

//$pdf->Cell(45,7,'Sr. Jefe de Casino de '.$row->CASA,0,1,'L');

$pdf->SetFont('Arial','',10);
//$pdf->Cell(10,7,'',0,0,'L');
																																																																								


/*
$pdf->Cell(0,7,'',0,1,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(20,7,'',0,0,'L');
$pdf->Cell(70,7,'Fecha: '.$row->FECHA,0,1,'L');
$pdf->Cell(20,7,'',0,0,'L');
$pdf->Cell(70,7,'Cajero: '.$row->NOMBRE,0,1,'L');
$pdf->Cell(20,7,'',0,0,'L');
$pdf->Cell(70,7,'Caja: '.$row->CAJA,0,1,'L');
$pdf->Cell(20,7,'',0,0,'L');
$pdf->Cell(70,7,'Moneda: '.$row->MONEDA,0,1,'L');
$pdf->Cell(20,7,'',0,0,'L');
$pdf->Cell(70,7,'Importe: '.'$'.number_format($row->IMPORTE_FICHA,2,',','.'),0,1,'L');

$pdf->SetFont('Arial','',10);
$pdf->Cell(10,7,'',0,1,'L');
$pdf->multiCell(0,7,'								Se observa que no se efectuó la carga de los datos exigidos por la U.I.F. ',0,'L');
$pdf->multiCell(0,7,'								Se solicita informe al respecto.',0,'L');


*/
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,7,'FECHA',1,0,'C');
$pdf->Cell(45,7,'CASA',1,0,'C');
$pdf->Cell(60,7,'CAJERO',1,0,'C');
$pdf->Cell(30,7,'CAJA',1,0,'C');
$pdf->Cell(30,7,'IMPORTE',1,1,'C');
 
		
$pdf->SetFont('Arial','',9);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 
	//$pdf->setx(10);
	
	$pdf->Cell(25,7,$row->FECHA,1,0,'C');
	$pdf->Cell(45,7,$row->CASA,1,0,'L');
	$pdf->Cell(60,7,$row->NOMBRE ,1,0,'L');
 	$pdf->Cell(30,7,$row->CAJA,1,0,'L');
	$pdf->Cell(30,7,'$'.number_format($row->IMPORTE_FICHA,2,',','.'),1,1,'R');
	// $acum=$acum+$row->VALOR_PREMIO;
	} 

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(40);
//$pdf->Cell(120,7,'TOTAL $ '.number_format($acum,2,',','.'),0,0,'R');
	//$pdf->Cell(180,7,'Total:  $'.$acum,1,0,'R');
//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>