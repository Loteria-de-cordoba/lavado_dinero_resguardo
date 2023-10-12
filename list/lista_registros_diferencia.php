<?php
require("header_listado.php"); 
//print_r($_GET);
 //$db->debug=true;

if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	} else {
			$suc_ban = 0;
			}



if (isset($_GET['fdesde'])) {
		$fdesde = $_GET['fdesde'];
} else {
	if (isset($_POST['fdesde'])) {
		$fdesde = $_POST['fdesde'];
	}
}


if (isset($_GET['fhasta'])) {
		$fhasta = $_GET['fhasta'];
} else {
	if (isset($_POST['fhasta'])) {
		$fhasta = $_POST['fhasta'];
	}
}

if (isset($_GET['cuentas'])) {
	$cuentas = $_GET['cuentas'];
} else {
	if (isset($_POST['cuentas'])) {
			$cuentas = $_POST['cuentas'];
	}
}
$fdesde_array=explode(" ",$fdesde);
$fhasta_array=explode(" ",$fhasta);
$titulo='Listado de registros desde '.$fdesde_array[0].' hasta '.$fhasta_array[0];

while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;

	if (($_SESSION['suc_ban']==1) and ($_SESSION['rol'.$i]!='ROL_LAVADO_DINERO_ADM_SIN_CC')and ($_SESSION['rol'.$i]!='ROL_LAVADO_DINERO_ADM_CASINO')and ($_SESSION['rol'.$i]!='ROL_LAVADO_DINERO_ADM_CONFORMA')) { 

			try {$rsconta = $db->Execute("select a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor, a.nro_asiento, rownum 
                                                  from conta_new.asiento_cabecera a
                                                  where a.cod_area_vinculante is null
                                                  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                                  and ( upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%')
                                                  order by rownum, fecha_valor, a.total desc");
				}
				catch (exception $e){die ($db->ErrorMsg()); } 		
		} else {
			try {$rsconta = $db->Execute("select a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor, a.nro_asiento, rownum 
                                              from conta_new.asiento_cabecera a, adm.area c
                                              where a.cod_area=c.cod_area
												  and c.suc_ban=?
												  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
												  and ( upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%')
											  order by rownum,fecha_valor, total desc",
                                              array($_SESSION['suc_ban']));
			}
			catch (exception $e){die ($db->ErrorMsg()); } 
		}	
}	
try {$rsgana = $db->Execute("select valor_premio, to_char(fecha_alta,'dd/mm/yyyy') fecha, id_ganador, conformado 
                                              from PLA_AUDITORIA.t_ganador 
                                              where suc_ban = ?
											  AND FECHA_BAJA IS NULL
												AND USUARIO_BAJA IS NULL
                                               and fecha_alta between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                              order by fecha_alta, valor_premio desc",
                                              array($_SESSION['suc_ban']));
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
		}



$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage(P);
$pdf->SetFont('Arial','B',9);

$pdf->Cell(75,8,'Registros de Contabilidad',1,0,'C');
$pdf->Cell(10,8,'',0,0,'C');
$pdf->Cell(105,8,'Registros de Ganadores',1,1,'C');

$pdf->SetFont('Arial','B',9);
$pdf->Cell(15,8,'Item',1,0,'C');
$pdf->Cell(20,8,'Fecha',1,0,'C');
$pdf->Cell(20,8,'Nº Asiento',1,0,'C');
$pdf->Cell(20,8,'Monto',1,0,'C');
//$pdf->Cell(15,8,'Y',1,0,'C');
//$pdf->Cell(20,8,'Haber',1,0,'C');
$pdf->Cell(10,8,'',0,0,'C');
$pdf->Cell(10,8,'Item',1,0,'C');
$pdf->Cell(20,8,'Fecha',1,0,'C');
$pdf->Cell(50,8,'Ganador',1,0,'C');
$pdf->Cell(25,8,'Monto',1,1,'C');

$y=$pdf->GetY();
		
$pdf->SetFont('Arial','',7);
$i=0;
while ($row = $rsconta->FetchNextObject($toupper=true)) {

	if($y>264){
	
$pdf->AddPage(P);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(75,8,'Registros de Contabilidad',1,0,'C');
$pdf->Cell(10,8,'',0,0,'C');
$pdf->Cell(105,8,'Registros de Ganadores',1,1,'C');

$pdf->SetFont('Arial','B',9);
$pdf->Cell(15,8,'Item',1,0,'C');
$pdf->Cell(20,8,'Fecha',1,0,'C');
$pdf->Cell(20,8,'Nº Asiento',1,0,'C');
$pdf->Cell(20,8,'Monto',1,0,'C');
//$pdf->Cell(15,8,'Y',1,0,'C');
//$pdf->Cell(20,8,'Haber',1,0,'C');
$pdf->Cell(10,8,'',0,0,'C');
$pdf->Cell(10,8,'Item',1,0,'C');
$pdf->Cell(20,8,'Fecha',1,0,'C');
$pdf->Cell(50,8,'Ganador',1,0,'C');
$pdf->Cell(25,8,'Monto',1,1,'C');

$y=$pdf->Gety();
		

		}	
	$pdf->SetFont('Arial','',7);	
	$pdf->Cell(15,7,$row->ROWNUM,1,0,'C');
	$pdf->Cell(20,7,$row->FECHA_VALOR,1,0,'C');
	$pdf->Cell(20,7,$row->NRO_ASIENTO,1,0,'C');
	$pdf->Cell(20,7,'$ '.number_format($row->TOTAL,2,',','.'),1,0,'R');
	
			
	$debe+= $row->TOTAL;
	
	$row = $rsgana->FetchNextObject($toupper=true);
	if(!is_null($row->FECHA)){
		$i=$i+1;
		$pdf->SetX($x+95);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,7,$i,1,0,'C');
		$pdf->Cell(20,7,$row->FECHA,1,0,'C');
		$pdf->Cell(50,7,$row->APELLIDO.' '.$row->NOMBRE,1,0,'L');
		$pdf->Cell(25,7,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),1,1,'R');
		
		$haber+= $row->VALOR_PREMIO;	
		$y=$pdf->Gety(); 
		
	} else {
		$pdf->Cell(10,7,'',0,0,'C');
		$pdf->Cell(20,7,$row->FECHA,0,0,'C');
		$pdf->Cell(50,7,$row->APELLIDO.' '.$row->NOMBRE,0,0,'L');
		$pdf->Cell(25,7,'',0,1,'R');
	
	}
	}
	$x=$pdf->GetX()+85; 
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(10,7,'',0,0);
	$pdf->Cell(35,7,'TOTAL ',0,0,'R');
	$pdf->Cell(25,7,'$ '.number_format($debe,2,',','.'),0,0,'R');
	
//$pdf->Cell(10,5,'',0,1);
//$pdf->SetX(600);
/*
while ($row = $rsgana->FetchNextObject($toupper=true)){
	
	$i=$i+1;
	$pdf->Setxy($x,$y);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(10,7,$i,1,0,'C');
	$pdf->Cell(20,7,$row->FECHA,1,0,'C');
	$pdf->Cell(50,7,$row->APELLIDO.' '.$row->NOMBRE,1,0,'L');
	$pdf->Cell(25,7,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),1,1,'R');
	$haber+= $row->VALOR_PREMIO;	
	$y=$pdf->Gety(); 

}*/
$pdf->Setxy($x,$y);
$x=$pdf->GetX()+70; 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,7,'',0,0);
$pdf->Cell(70,7,'TOTAL',0,0,'R');
$pdf->Cell(30,7,'$ '.number_format($haber,2,',','.'),0,0,'C');
$pdf->Output();
?>