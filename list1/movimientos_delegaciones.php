<?php


require("header_listado.php"); 

$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];

$titulo='Cantidad de Ganadores por Delegacion entre el '.$fecha .' y '.$fhasta;
//$db->debug=true;
try{
	
	 $rs = $db->Execute("select a.suc_ban, b.nombre,
						(select count(*)
						from PLA_AUDITORIA.t_ganador z
						where z.suc_ban=a.suc_ban
						AND z.fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
						 and z.fecha_baja is null
						) as movimientos,
						(select count(*)
						from PLA_AUDITORIA.t_ganador z
						where z.conformado=0
						and z.suc_ban=a.suc_ban
						AND z.fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
						 and z.fecha_baja is null
						) as no_conformados,
						(select count(*)
						from PLA_AUDITORIA.t_ganador z
						where z.conformado=1
						and z.suc_ban=a.suc_ban
						AND z.fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
						 and z.fecha_baja is null
						) as conformados,
						(SELECT COUNT(*)
						  FROM conta_new.asiento_cabecera x, adm.area y
						  where x.cod_area = y.cod_area
							  and y.suc_ban = a.suc_ban
							  and x.cod_area_vinculante is null
							  and ( upper(x.concepto) like '%UIF%' or upper(x.concepto) like '%U.I.F.%' )
						      and x.fecha_valor BETWEEN to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') AND to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
						  ) AS contabilidad
						from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
						where a.suc_ban= b.suc_ban
						and a.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33)
						AND a.fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
						 and a.fecha_baja is null
						group by a.suc_ban, b.nombre
						order by a.suc_ban"); 
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','B',10);
// $pdf->setx(70);
$pdf->Cell(45,6,'Sucursal',1,0,'C');
$pdf->Cell(35,6,'Ganadores',1,0,'C');
$pdf->Cell(35,6,'Conformados',1,0,'C');
$pdf->Cell(35,6,'No Conformados',1,0,'C');
$pdf->Cell(35,6,'Contabilidad',1,1,'C');

$mov=0;
$conf=0;
$no_conf=0; 
		
$pdf->SetFont('Arial','',9);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 
	//$pdf->setx(70);
	$pdf->Cell(45,6,$row->NOMBRE,1,0,'L');
	$pdf->Cell(35,6,$row->MOVIMIENTOS,1,0,'R');
	$pdf->Cell(35,6,$row->CONFORMADOS,1,0,'R');
	$pdf->Cell(35,6,$row->NO_CONFORMADOS,1,0,'R');
	$pdf->Cell(35,6,$row->CONTABILIDAD,1,1,'R');
	
	$mov=$mov + $row->MOVIMIENTOS;
	$conf=$conf+$row->CONFORMADOS;
	$no_conf=$no_conf+$row->NO_CONFORMADOS;
	$conta=$conta+ $row->CONTABILIDAD;
	} 


//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->SetFont('Arial','B',10);
//$pdf->Cell(180,7,' ',0,1,'R');
//$pdf->setx(170);
$pdf->Cell(45,7,'TOTALES ',0,0,'L');
$pdf->Cell(35,7,$mov,0,0,'R');
$pdf->Cell(35,7,$conf,0,0,'R');
$pdf->Cell(35,7,$no_conf,0,0,'R');
$pdf->Cell(35,7,$conta,0,1,'R');


	//$pdf->Cell(180,7,'Total:  $'.$acum,1,0,'R');
//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>