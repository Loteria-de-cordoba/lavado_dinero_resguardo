<?php
//$titulo='INFORME';
require("header_listado.php"); 
	
$rs_juego = $db->Execute("select descripcion from cuenta_corriente.juego where cod_juego = ?", array ($_SESSION['juego']));	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

 ////////////////////////////
 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(10,8,'',0,0);
$pdf->Cell(170,8,'AGENCIA '.$_SESSION['agencia'],1,1,'C');
$pdf->Cell(180,8,'',0,1);
///////////////////////
$pdf->Cell(10,8,'',0,0);
while ($row = $rs_juego->FetchNextObject($toupper=true)) {
$pdf->Cell(170,8,'MOVIMIENTOS DIARIOS DE '.$row->DESCRIPCION.' AL '.$_SESSION['fecha'],1,1,'C');
}
$pdf->Cell(180,8,'',0,1);
/////////////////////////


$pdf->SetFont('Arial','B',9);
$pdf->Cell(25,8,'',0,0);
$pdf->Cell(50,8,'Concepto',1,0,'C');
$pdf->Cell(25,8,'Concurso',1,0,'C');
$pdf->Cell(30,8,'Debe',1,0,'C');
$pdf->Cell(30,8,'Haber',1,1,'C');
////////////////////////////

try{
	 		$rs = $db->Execute("select d.descripcion as des, concurso, sum(debe) as debe, sum(haber) as haber
 			from (select * from cuenta_corriente.movimiento_cabecera 
    			 where cod_movimiento_cabecera in (Select min(cod_movimiento_cabecera) 
	                                   				from cuenta_corriente.movimiento_cabecera 
	                                  				where fecha_valor = to_date(?,'dd/mm/yyyy')
									   				group by nro_liquidacion_bold)
													) a, cuenta_corriente.movimiento_detalle b, cuenta_corriente.juego c, 
													cuenta_corriente.concepto d
 			where a.cod_movimiento_cabecera = b.cod_movimiento_cabecera
			and b.nro_agen= ?
 			and b.cod_juego = c.cod_juego
			and c.cod_juego = ?
    		and b.cod_concepto = d.cod_concepto
 			and suc_ban = ?
 			and a.activo = 'S'
 			and b.activo = 'S'
 			and fecha_valor = to_date(?,'dd/mm/yyyy')
 			group by d.descripcion, concurso",array($_SESSION['fecha'],$_SESSION['agencia'], $_SESSION['juego'],$_SESSION['delegacion'],$_SESSION['fecha'])); 
			}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
		

while ($row = $rs->FetchNextObject($toupper=true)) {
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,7,'',0,0);
$pdf->Cell(50,7, $row->DES,1,0,'L');
$pdf->Cell(25,7, $row->CONCURSO,1,0,'C');
$pdf->Cell(30,7, number_format($row->DEBE,2,',','.'),1,0,'R');
$pdf->Cell(30,7, number_format($row->HABER,2,',','.'),1,1,'R');
}

$pdf->Output();
?>