<?php


require("header_listado.php"); 

//print_r($_GET);
//$db->debug=true;

if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";
}

if (isset($_GET['conformado'])&& $_GET['conformado']==0 ) {
			$conformado = $_GET['conformado'];
			$condicion_conforma="and a.conformado ='$conformado'";
} elseif (isset($_GET['conformado'])&& $_GET['conformado']==1 ) {
				$conformado = $_GET['conformado'];
				$condicion_conforma="and a.conformado ='$conformado'";
}

if (isset($_GET['mayores'])) {
	$mayores = $_GET['mayores'];
	if($mayores ==1){
		$condicion_mayores="and a.valor_premio>50000";
	}elseif($mayores ==0) {
		$mayores=0;
		$condicion_mayores="";
	}
}

$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];

$titulo='Listado de Ganadores entre el '.$fecha .'y '.$fhasta.' - [Datos Resguardados]';
//$db->debug=true;
try{
	
	 $rs = $db->Execute("	select to_char(a.fecha_alta,'DD/MM/YYYY') as fecha,a.apellido, a.nombre, a.politico, a.ddjj, A.VALOR_PREMIO, b.nombre sucursal
							from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
							where a.suc_ban=b.suc_ban
								and a.fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
								and a.fecha_baja is null
							$condicion_sucursal
							$condicion_conforma
							$condicion_mayores
							order by fecha, a.suc_ban"); 
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(240,0);
$pdf->SetFont('Arial','B',10);
//$pdf->setx(70);

$pdf->Cell(30,6,'Fecha',1,0,'C');
$pdf->Cell(60,6,'Apellido Y Nombre',1,0,'C');
$pdf->Cell(30,6,'Politico',1,0,'C');
$pdf->Cell(20,6,'DDJJ',1,0,'C');
$pdf->Cell(40,6,'Sucursal',1,1,'C');
 
		
$pdf->SetFont('Arial','',9);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 
	//$pdf->setx(70);
	if($row->POLITICO=="SI")//si es politico va en rojo
				{
					$pdf->SetFont('Arial','B',9);
					$pdf->SetFillColor(240,0);
					$pdf->Cell(30,6,$row->FECHA,1,0,'C',1);
					$xx=utf8_decode($row->APELLIDO);
					$pdf->Cell(60,6,strtoupper(utf8_decode($xx).' '.$row->NOMBRE),1,0,'L',1);
					$pdf->Cell(30,6,$row->POLITICO,1,0,'C',1);
					$pdf->Cell(20,6,$row->DDJJ,1,0,'R',1);
					$pdf->Cell(40,6,$row->SUCURSAL,1,1,'L',1);
					$pdf->SetFont('Arial','',9);
				}
				else if($row->VALOR_PREMIO>=1000000)//si es premio>=1000000 va en naranja
				{
					$pdf->SetFont('Arial','B',9);
					$pdf->SetFillColor(240,100);
					$pdf->Cell(30,6,$row->FECHA,1,0,'C',1);
					$xx=utf8_decode($row->APELLIDO);
					$pdf->Cell(60,6,strtoupper(utf8_decode($xx).' '.$row->NOMBRE),1,0,'L',1);
					$pdf->Cell(30,6,$row->POLITICO,1,0,'C',1);
					$pdf->Cell(20,6,$row->DDJJ,1,0,'R',1);
					$pdf->Cell(40,6,$row->SUCURSAL,1,1,'L',1);
					$pdf->SetFont('Arial','',9);
				}
				else
				{
					$pdf->Cell(30,6,$row->FECHA,1,0,'C');
					$xx=utf8_decode($row->APELLIDO);
					$pdf->Cell(60,6,strtoupper(utf8_decode($xx).' '.$row->NOMBRE),1,0,'L');
					$pdf->Cell(30,6,$row->POLITICO,1,0,'C');
					$pdf->Cell(20,6,$row->DDJJ,1,0,'R');
					$pdf->Cell(40,6,$row->SUCURSAL,1,1,'L');
				}
	 
	} 

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(40);

	//$pdf->Cell(180,7,'Total:  $'.$acum,1,0,'R');
//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>