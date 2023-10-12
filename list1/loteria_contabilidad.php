<?php
while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;

	if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC'){	
		$ti="Registros Contables en Delegaciones";
	} else {
		$ti="Registros Contables";
	}
}
//$ti="Registros Contables";
$titulo="Registros Contables en Delegaciones";
//$titulo2="entre el ."

require("header_listado.php"); 

 $consulta= $_SESSION['sqlreporte'];
//$db->debug=true;

//print_r($_GET);




try{
	
	 $rs = $db->Execute("$consulta"); 
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','B',10);
 $pdf->setx(10);
$pdf->Cell(20,6,'Fecha',1,0,'C');
$pdf->Cell(30,6,'Sucursal',1,0,'C');
//$pdf->Cell(45,6,'Operador',1,0,'C');
$pdf->Cell(125,6,'Concepto',1,0,'C');
$pdf->Cell(20,6,'Importe',1,1,'C');
 
		
$pdf->SetFont('Arial','',7);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 	
	$pdf->setx(10);
	$h1=$pdf->gety();
	$pdf->Cell(20,6,$row->FECHA_VALOR,0,0,'C');
	
	$pdf->Cell(30,6,$row->SUCURSAL,0,0,'L');
	//$pdf->Cell(45,6,$row->OPERADOR ,1,0,'L');
 	$pdf->multiCell(125,6,$row->CONCEPTO,0,'J',0);
	$h2=$pdf->gety();
	if($h1==$h2){
	$pdf->setxy(185,$h1);
	$pdf->Cell(20,6, '$ '.number_format($row->TOTAL,2,',','.'),0,1,'R');
	} else {
		$pdf->setxy(185,$h2-6);
		$pdf->Cell(20,6, '$ '.number_format($row->TOTAL,2,',','.'),0,1,'R');
	}
	 $acum=$acum+$row->TOTAL;
	 $sum=$sum+1;
	 $y_line=$pdf->GetY();
	 $pdf->Line(10,$y_line,205,$y_line);
} 

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(170);
$pdf->Cell(40,7,'TOTAL $ '.number_format($acum,2,',','.'),0,1,'L');

$pdf->SetFont('Arial','B',10);

$pdf->setx(155);
$pdf->Cell(40,7,'Cantidad de Movimientos: '.$sum,0,0,'L');

$pdf->Output();

?>