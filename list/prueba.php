<?php
$titulo='BALANCE';
require("header_listado.php"); 

if ($_GET['cod_juego']!=0) {
	$cod_juego = $_GET['cod_juego'];
	$condicion_juego = "and b.cod_juego in ($cod_juego)";
	} else {
	$cod_juego = 0;
	$condicion_juego = "";
}

//echo $_SESSION['suc_ban'];
try{
	
	 $rs = $db->Execute("select suc_ban, d.descripcion as des ,c.descripcion, concurso, sum(debe) as debe, sum(haber) as haber, sum(debe-haber) as saldo
 		from (select * from cuenta_corriente.movimiento_cabecera 
    	where cod_movimiento_cabecera in (Select min(cod_movimiento_cabecera) 
	                                   from cuenta_corriente.movimiento_cabecera 
	                                   where fecha_valor = to_date(?,'dd/mm/yyyy')
									   group by nro_liquidacion_bold)) a, cuenta_corriente.movimiento_detalle b, cuenta_corriente.juego c, cuenta_corriente.concepto d 
 	where a.cod_movimiento_cabecera = b.cod_movimiento_cabecera
 	and b.cod_juego = c.cod_juego
    and b.cod_concepto = d.cod_concepto
 	and suc_ban = ?
 	and a.activo = 'S'
 	and b.activo = 'S'
	$condicion_juego
 	and fecha_valor = to_date(?,'dd/mm/yyyy')
 	group by suc_ban, fecha_valor, c.descripcion,d.descripcion,concurso
	order by suc_ban, fecha_valor, c.descripcion",array($_SESSION['fecha'],$_SESSION['suc_ban'],$_SESSION['fecha'])); 
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,8,'',0,0);
$pdf->Cell(15,8,'Fecha',1,0,'C');
$pdf->Cell(30,8,'Juego',1,0,'C');
$pdf->Cell(20,8,'Concurso',1,0,'C');
$pdf->Cell(20,8,'Delegación',1,0,'C');
$pdf->Cell(45,8,'Concepto',1,0,'C');
$pdf->Cell(20,8,'Debe',1,0,'C');
$pdf->Cell(20,8,'Haber',1,1,'C');

		
$pdf->SetFont('Arial','',7);

while ($row = $rs->FetchNextObject($toupper=true)) {
	$pdf->Cell(10,7,'',0,0);
	$pdf->Cell(15,7,$_SESSION['fecha'],1,0,'R');
	$pdf->Cell(30,7,$row->DESCRIPCION,1,0,'L');
	$pdf->Cell(20,7,$row->CONCURSO,1,0,'R');
	$pdf->Cell(20,7,$row->SUC_BAN,1,0,'R');
	$pdf->Cell(45,7,$row->DES,1,0,'L');
	$pdf->Cell(20,7,number_format($row->DEBE,2,'.',','),1,0,'R');
	$debet+= $row->DEBE;
	$pdf->Cell(20,7,number_format($row->HABER,2,'.',','),1,1,'R');
	$habert+= $row->HABER;
	} 
$pdf->Cell(10,5,'',0,1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(10,8,'',0,0);
$pdf->Cell(130,8,'TOTAL',1,0,'L');
$pdf->Cell(20,8,number_format($debet,2,',','.'),1,0,'R');
$pdf->Cell(20,8,number_format($haber,2,',','.'),1,1,'R');

$pdf->Output();
?>