<?php
if($_GET['conformado']==1){
$conformado="Conformados";
} else {
$conformado="No Conformado";
}

$titulo='Premios Pagados '.$conformado;
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
$pdf->Cell(40,6,'Sucursal',1,0,'C');
$pdf->Cell(55,6,'Apellido Y Nombre',1,0,'C');
$pdf->Cell(25,6,'Juego',1,0,'C');
$pdf->Cell(25,6,'Importe',1,0,'C');
$pdf->Cell(25,6,'DDJJ',1,1,'C');
 
		
$pdf->SetFont('Arial','',9);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 
	$pdf->setx(10);
	$pdf->Cell(20,7,$row->FECHA,1,0,'C');
	$pdf->Cell(40,7,$row->CASA,1,0,'L');
	$xx=utf8_decode($row->APELLIDO);
	$pdf->Cell(55,7,utf8_decode($xx).', '.$row->NOMBRE ,1,0,'L');
 	$pdf->Cell(25,7,$row->JUEGOS,1,0,'L');
	$pdf->Cell(25,7,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),1,0,'R');
	$pdf->Cell(25,7,$row->DDJJ,1,1,'R');
	 $acum=$acum+$row->VALOR_PREMIO;
	} 

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(40);
$pdf->Cell(120,7,'TOTAL $ '.number_format($acum,2,',','.'),0,0,'R');
	//$pdf->Cell(180,7,'Total:  $'.$acum,1,0,'R');
//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>