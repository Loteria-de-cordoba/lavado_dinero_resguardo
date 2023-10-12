<?php 
session_start();

include("../../db_conecta.inc.php");
include("../../funcion.inc.php");
include('../../jscalendar-1.0/calendario.php'); 

require("../header_listado.php"); 

//$DB->debug = true;

$id_empleado = validarAsignarParametros('id_empleado','1');
$periodo = validarAsignarParametros('periodo','1');
$mes = validarAsignarParametros('mes','1');

$planta = validarAsignarParametros('planta','1');
$legajo = validarAsignarParametros('legajo','1');

$sql_legajo = '';
if($legajo){
	$sql_legajo = ' and LEGAJO = ' . $legajo;
}

$sql_planta = '';
if($planta){
	$sql_planta = ' and ID_PLANTA = ' . $planta;
}


try { 
    $rs_datos = $DB->Execute("
SELECT 
	ID_EMPLEADO,
	FECHA_PAGO,
	PERIODO,
	MES,
	ID_PLANTA,
	LEGAJO,
	DESC_EMPLEADO,
	GAN_BRUTA_HAB_MES,
	GAN_BRUTA_TOTAL_MES,
	JUBILACION,
	OBRA_SOCIAL,
	SEGURO_VIDA,
	GASTOS_SEPELIO,
	CORREDORES,
	DONACIONES,
	AD_OBRASOCIAL,
	CUOTA_MED_ASIS,
	HON_MEDICOS,
	OTROS_DESCLEY,
	SINDICATO,
	SERV_DOMESTICO,
	PRESTAMOS_HIP,
	
	GAN_NO_REMU,
	GAN_NETA,
	
	GAN_NETA_MENSUAL,
	GAN_MESES_ANT,
	GAN_ACUM,
	GAN_NO_IMP,
	DED_ESPECIAL,
	DED_CONYUGE,
	DED_HIJO,
	OTRAS_CARGAS,
	GAN_IMP_ACUM,
	MONTO_FIJO,
	PORCENTAJE,
	RET_ACUM,
	RET_PRACTICADAS,
	RETENCION_ACTUAL,
	APLICA
FROM 
	rrhh.T_ACUM_IMPUESTO 
WHERE 
	PERIODO = ?
	and MES = ?
	
	$sql_planta
	$sql_legajo
	
ORDER BY 
	desc_empleado asc
	",array($periodo,$mes));
} catch (exception $e) {    
    die ($DB->ErrorMsg()); 
} 

$titulo = 'Informe impuesto a las ganancias';
$titulo2 = $mes . '/' . $periodo;

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->SetFillColor(194,194,194);

$salto_pagina=275;
$pri='NO';
$x= 10;

$pdf->SetFont('Arial','B',10);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,6,'',0,0,'C');

$pdf->Cell(15,6,'Periodo',1,0,'C',1);
$pdf->Cell(15,6,'Legajo',1,0,'C',1);
$pdf->Cell(80,6,'Nombre',1,0,'C',1);
$pdf->Cell(20,6,'Ret. actual',1,1,'C',1);

$y_line=$pdf->GetY(); 
$pdf->SetFillColor(240,240,240);	

while ($row = $rs_datos->FetchNextObject($toupper=true)) {
	
	$cant=$cant+1;
	
	if ($salto_pagina > 260) {
		
		$salto_pagina = 0;
		
		if ($pri=='NO') {
			$pdf->AddPage();
			$pdf->SetFillColor(194,194,194);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(25,6,'',0,0,'C');
			
			$pdf->Cell(15,6,'Periodo',1,0,'C',1);
			$pdf->Cell(15,6,'Legajo',1,0,'C',1);
			$pdf->Cell(80,6,'Nombre',1,0,'C',1);
			$pdf->Cell(20,6,'Ret. actual',1,1,'C',1);
			
		} else {
			$pri='NO';
		}
		
	}
	
	$y_line=$pdf->GetY();
	$salto_pagina = number_format($y_line,0,'.',',');
	
	$pdf->Cell(25,6,'',0,0,'C');
	$pdf->Cell(15,6,$row->PERIODO . '/' . $row->MES,0,0,'C');
	$pdf->Cell(15,6,str_pad(trim($row->LEGAJO),5,'0',STR_PAD_LEFT),0,0,'R');
	$pdf->Cell(80,6,$row->DESC_EMPLEADO,0,0,'L');
	$pdf->Cell(20,6,number_format($row->RETENCION_ACTUAL,'2','.',''),0,1,'R');
		
}

$pdf->Output();	

?>