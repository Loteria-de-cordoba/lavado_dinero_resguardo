<?php


require("header_listado.php"); 

$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];

$titulo='Delegaciones sin Carga de Ganadores entre el '.$fecha .'y '.$fhasta;
//$db->debug=true;
try{
	
	 $rs = $db->Execute(" select b.nombre delegacion
						from lavado_dinero.t_ganador a, JUEGOS.sucursal b
						where a.suc_ban = b.suc_ban(+)
							and a.suc_ban  in(20,21,22,23,24,25,26,27,30,31,32,33,51)
							and a.fecha_baja is null
							and b.suc_ban not in (
											  select suc_ban
											  from lavado_dinero.t_ganador
											  where fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
											  )

						group by b.nombre"); 
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','B',10);
 $pdf->setx(70);
//$pdf->Cell(25,6,'Sucursal',1,0,'C');
$pdf->Cell(45,6,'Delegacion',1,1,'C');
//$pdf->Cell(60,6,'Apellido Y Nombre',1,0,'C');
//$pdf->Cell(30,6,'Juego',1,0,'C');
//$pdf->Cell(30,6,'Importe',1,1,'C');
 
		
$pdf->SetFont('Arial','',9);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 
	$pdf->setx(70);
	$pdf->Cell(45,7,$row->DELEGACION,1,1,'L');
	
	 
	} 

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(40);

	//$pdf->Cell(180,7,'Total:  $'.$acum,1,0,'R');
//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>