<?php

//$ti="Registros Contables";

//$titulo2="entre el ."

require("header_listado.php"); 

 
//$db->debug=true;

//print_r($_GET);

$fdesde=$_GET['fecha'];
$fhasta=$_GET['fhasta'];

$titulo='Cantidad de Mov. Contables por Delegacion entre el '.$fdesde .'y '.$fhasta;

try{
	
	 $rs = $db->Execute("select  b.nombre as sucursal, count(*)movimientos, sum(a.total)TOTAL, b.suc_ban
						 from conta_new.asiento_cabecera a, adm.area c, juegos.sucursal b
						 where a.cod_area = c.cod_area
							  and c.suc_ban = b.suc_ban
							  and a.cod_area_vinculante is null
							  and b.suc_ban not in (1,60,62,63,64,65,66,67,73,79,80,81)
							  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
							  and ( upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%' )
						 group by  b.nombre, b.suc_ban
              			order by  b.suc_ban"); 
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','B',10);
$pdf->setx(40);
$pdf->Cell(50,6,'Sucursal',1,0,'C');
$pdf->Cell(30,6,'Movimientos',1,0,'C');
$pdf->Cell(40,6,'Total',1,1,'C');

 
		
$pdf->SetFont('Arial','',7);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 	
	$pdf->setx(40);
		
	$pdf->Cell(50,6,$row->SUCURSAL,1,0,'L');
	$pdf->Cell(30,6,$row->MOVIMIENTOS,1,0,'R');
	$pdf->Cell(40,6,'$ '.number_format($row->TOTAL,2,',','.'),1,1,'R');
	
	$acum=$acum+$row->TOTAL;
	$mov=$mov + $row->MOVIMIENTOS;
	 $y_line=$pdf->GetY();
	 $pdf->Line(40,$y_line,120,$y_line);
} 

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(170);
$pdf->Cell(40,7,'TOTAL $ '.number_format($acum,2,',','.'),0,1,'L');

$pdf->SetFont('Arial','B',10);

$pdf->setx(155);
$pdf->Cell(40,7,'Cantidad de Movimientos: '.$mov,0,0,'L');

$pdf->Output();

?>