<?php
require("header_listado.php"); 
//print_r($_GET);



if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				//$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
			} else {
		 
					if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
						$suc_ban = $_POST['suc_ban'];
						//$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
					} else {
						$suc_ban = 0;
						//$condicion_sucursal = "";
					}
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


//echo $_SESSION['suc_ban'];
if ($_SESSION['suc_ban']==1) {
			try {$rsconta = $db->Execute("select a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor, a.nro_asiento 
                                                  from conta_new.asiento_cabecera a
                                                  where a.cod_area_vinculante is null
                                                  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                                   and ( upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%' )
                                                  order by fecha_valor, a.total desc");
				}
				catch (exception $e)
				{
				die ($db->ErrorMsg()); 
			} 		
		} else {
			try {$rsconta = $db->Execute("SELECT (importe_plata + importe_impuesto) as TOTAL, id_cp, to_char(fecha,'DD/MM/YYYY') as fecha_valor
										  FROM casino.t_reg_cp
										  WHERE casa = upper(substr(?,8))
										  and conformado_uif=0
										  AND fecha BETWEEN to_date('$fdesde','DD/MM/YYYY HH24:MI') AND to_date('$fhasta','DD/MM/YYYY HH24:MI')
										  AND importe_plata>=10000
										  AND anulado='N'
										  order by fecha, total desc", array($_SESSION['area']));
											  
											 /* SELECT importe_plata AS total, id_cp,
												  to_char(fecha,'DD/MM/YYYY') as fecha_valor
												  FROM casino.t_reg_cp
												  WHERE casa = upper(substr(?,8))
												  and conformado_uif=0
												  AND fecha BETWEEN to_date('$fdesde','DD/MM/YYYY HH24:MI') AND to_date('$fhasta','DD/MM/YYYY HH24:MI')
												  AND importe_plata>=10000
												  order by fecha, total desc
											 */
											  
											  
											  
											  
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 
		}	
	
try {$rsgana = $db->Execute("select valor_premio, to_char(fecha_alta,'dd/mm/yyyy') fecha, id_ganador, apellido, nombre 
                                              from PLA_AUDITORIA.t_ganador 
                                              where suc_ban = ?
											  and conformado=0
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
$pdf->AddPage();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,8,'',0,0);
$pdf->Cell(60,8,'Registros de Caja Pública',1,0,'C');
$pdf->Cell(10,8,'',0,0,'C');
$pdf->Cell(100,8,'Registros de Ganadores',1,1,'C');

$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,8,'',0,0);
$pdf->Cell(30,8,'Fecha',1,0,'C');
//$pdf->Cell(20,8,'Nº Asiento',1,0,'C');
$pdf->Cell(30,8,'Monto',1,0,'C');
//$pdf->Cell(20,8,'Haber',1,0,'C');
$pdf->Cell(10,8,'',0,0,'C');
$pdf->Cell(25,8,'Fecha',1,0,'C');
$pdf->Cell(50,8,'Ganador',1,0,'C');
$pdf->Cell(25,8,'Monto',1,1,'C');
$y=$pdf->GetY();
//$x=$pdf->GetX();


		
$pdf->SetFont('Arial','',7);

while ($row = $rsconta->FetchNextObject($toupper=true)) {
	$pdf->Cell(10,7,'',0,0);
	
	$pdf->Cell(30,7,$row->FECHA_VALOR,1,0,'C');

	//$pdf->Cell(20,7,$row->NRO_ASIENTO,1,0,'C');
	$pdf->Cell(30,7,number_format($row->TOTAL,2,',','.'),1,1,'R');
	//$pdf->Cell(20,7,number_format($row->HABER,2,',','.'),1,1,'R');
	
	
	$debe+= $row->TOTAL;
}
	$x=$pdf->GetX()+85; 
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(10,7,'',0,0);
	$pdf->Cell(35,7,'TOTAL ',0,0,'R');
	$pdf->Cell(25,7,'$ '.number_format($debe,2,',','.'),0,1,'R');
	
	//$pdf->Cell(10,5,'',0,1);
//$pdf->SetX(600);
//
while ($row = $rsgana->FetchNextObject($toupper=true)) {

	$pdf->Setxy($x-5,$y);
	$pdf->SetFont('Arial','',7);
	
	$pdf->Cell(25,7,$row->FECHA,1,0,'C');
	$pdf->Cell(50,7,$row->APELLIDO.' '.$row->NOMBRE,1,0,'L');
	$pdf->Cell(25,7,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),1,1,'R');
	$haber+= $row->VALOR_PREMIO;	
	$y=$pdf->Gety(); 

}
$pdf->Setxy($x,$y);
$x=$pdf->GetX()+70; 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,7,'',0,0);
$pdf->Cell(60,7,'TOTAL',0,0,'R');
$pdf->Cell(30,7,'$ '.number_format($haber,2,',','.'),0,1,'C');



/*
$pdf->SetFont('Arial','B',8);
$pdf->Cell(10,8,'',0,0);
$pdf->Cell(110,8,'TOTAL',1,0,'L');
$pdf->Cell(20,8,number_format($debe,2,',','.'),1,0,'R');
$pdf->Cell(20,8,number_format($habert,2,',','.'),1,0,'R');
$pdf->Cell(20,8,number_format($saldo,2,',','.'),1,1,'R');
*/

//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>