<?php
$titulo='BALANCE POR CONCEPTOS';
require("header_listado.php"); 

if ($_GET['cod_juego']!=0) {
	$cod_juego = $_GET['cod_juego'];
	$condicion_juego = "and b.cod_juego in ($cod_juego)";
	} else {
	$cod_juego = 0;
	$condicion_juego = "";
}
if ($_GET['cod_concepto']!=0) {
	$cod_concepto = $_GET['cod_concepto'];
	$condicion_concepto = "and b.cod_concepto in ($cod_concepto)";
	} else {
	$cod_concepto = 0;
	$condicion_concepto = "";
}

//echo $_SESSION['suc_ban'];
try{
	
	 $rs = $db->Execute("select suc_ban, d.descripcion as des ,c.sigla as descripcion, concurso, sum(debe) as debe, sum(haber) as haber, sum(debe-haber) as saldo
 		from (select * from cuenta_corriente.movimiento_cabecera 
    	where cod_movimiento_cabecera in (Select min(cod_movimiento_cabecera) 
	                                   from cuenta_corriente.movimiento_cabecera 
	                                   where fecha_valor = to_date(?,'dd/mm/yyyy')
									   group by nro_liquidacion_bold)) a, cuenta_corriente.movimiento_detalle b, cuenta_corriente.juego c, cuenta_corriente.concepto d 
 	where a.cod_movimiento_cabecera = b.cod_movimiento_cabecera
 	and b.cod_juego = c.cod_juego
    and b.cod_concepto = d.cod_concepto
  	and a.activo = 'S'
 	and b.activo = 'S'
 	and fecha_valor = to_date(?,'dd/mm/yyyy')
	and b.suc_ban = ?
	$condicion_juego
	$condicion_concepto
 	group by suc_ban, fecha_valor, c.sigla,d.descripcion,concurso
	order by suc_ban, fecha_valor, c.sigla",array($_SESSION['fecha'],$_SESSION['fecha'],$_SESSION['suc_ban'])); 
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
$pdf->Cell(20,8,'Juego',1,0,'C');
$pdf->Cell(10,8,'Conc',1,0,'C');
$pdf->Cell(20,8,'Delegación',1,0,'C');
$pdf->Cell(45,8,'Concepto',1,0,'C');
$pdf->Cell(20,8,'Debe',1,0,'C');
$pdf->Cell(20,8,'Haber',1,0,'C');
$pdf->Cell(20,8,'Saldo',1,1,'C');

		
$pdf->SetFont('Arial','',7);

while ($row = $rs->FetchNextObject($toupper=true)) {
	$pdf->Cell(10,7,'',0,0);
	$pdf->Cell(15,7,$_SESSION['fecha'],1,0,'R');
	$pdf->Cell(20,7,$row->DESCRIPCION,1,0,'C');
	$pdf->Cell(10,7,$row->CONCURSO,1,0,'R');
	$pdf->Cell(20,7,$row->SUC_BAN,1,0,'R');
	$pdf->Cell(45,7,$row->DES,1,0,'L');
	$pdf->Cell(20,7,number_format($row->DEBE,2,'.',','),1,0,'R');
	$debet+= $row->DEBE;
	$pdf->Cell(20,7,number_format($row->HABER,2,'.',','),1,0,'R');
	$habert+= $row->HABER;
	$pdf->Cell(20,7,number_format($row->SALDO,2,'.',','),1,1,'R');
	$saldo+= $row->SALDO;
	} 
$pdf->Cell(10,5,'',0,1);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(10,8,'',0,0);
$pdf->Cell(110,8,'TOTAL',1,0,'L');
$pdf->Cell(20,8,number_format($debet,2,',','.'),1,0,'R');
$pdf->Cell(20,8,number_format($habert,2,',','.'),1,0,'R');
$pdf->Cell(20,8,number_format($saldo,2,',','.'),1,1,'R');


//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>