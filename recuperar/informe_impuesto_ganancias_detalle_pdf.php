<?php 
session_start();

include("../../db_conecta.inc.php");
include("../../funcion.inc.php");
include('../../jscalendar-1.0/calendario.php'); 

require("../header_listado.php"); 

$id_empleado = validarAsignarParametros('id_empleado','1');
$periodo = validarAsignarParametros('periodo','1');
$mes = validarAsignarParametros('mes','1');


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
	and ID_EMPLEADO = ?
ORDER BY 
	desc_empleado
	",array($periodo,$mes,$id_empleado));
} catch (exception $e) {    
    die ($DB->ErrorMsg()); 
} 

$titulo3 = $mes . '/' . $periodo;

$pdf=new PDF('P');
$pdf->AliasNbPages();

$salto_pagina=275;
$pri='NO';

$pdf->SetFont('Arial','B',11);
$y_line=$pdf->GetY(); 
$pdf->SetFillColor(240,240,240);	



$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(130,5,'Ganancia Bruta Habitual del mes',0,0,'L',0);
$pdf->Cell(15,5,'',1,0,'C',1);


$y_line=$pdf->GetY(); 
$pdf->SetFillColor(240,240,240);	



while ($row = $rs_datos->FetchNextObject($toupper=true)) {
	
	//$titulo = "Detalle del calculo de impuesto a las ganancias  " . str_pad(trim($row->LEGAJO),5,0,STR_PAD_LEFT) . ' ' . trim($row->DESC_EMPLEADO);
	$titulo = "Detalle del calculo de impuesto a las ganancias";
	$titulo2 = str_pad(trim($row->LEGAJO),5,0,STR_PAD_LEFT) . ' ' . trim($row->DESC_EMPLEADO);
	
	
	
	
	if ($salto_pagina > 260) {
		
		$salto_pagina = 0;
		
		if ($pri=='NO') {
			$pdf->AddPage();
			$pdf->SetFillColor(194,194,194);
			$pdf->SetFont('Arial','B',8);
			
			$pdf->Cell(20,5,'',0,0,'C');
			if($row->APLICA == 'N'){
				$pdf->Cell(130,5,'NO APLICA',0,1,'L',0);
			} else {
				$pdf->Cell(130,5,'APLICA',0,1,'L',0);
			}
			$pdf->Cell(20,5,'',0,1,'C');
			$pdf->Cell(20,5,'',0,1,'C');
			
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancia Bruta Habitual del mes',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_BRUTA_HAB_MES,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancia por conceptos no habituales',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_BRUTA_HAB_MES,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancia Bruta total del mes',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_BRUTA_TOTAL_MES,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Menos',0,0,'L',0);
			$pdf->Cell(15,5,'',0,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Aportes a fondos de jubilacion (Nac. Prov. y Munc.)',0,0,'L',0);
			$pdf->Cell(15,5,$row->JUBILACION,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Aportes a obras sociales del recibo de sueldo (no tiene tope)',0,0,'L',0);
			$pdf->Cell(15,5,$row->OBRA_SOCIAL,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Primas de seguros de vida (en caso de muerte) Hasta 996,23 anual',0,0,'L',0);
			$pdf->Cell(15,5,$row->SEGURO_VIDA,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Gastos de sepelio (por persona a cargo de titular) Hasta 996,23 anual',0,0,'L',0);
			$pdf->Cell(15,5,$row->GASTOS_SEPELIO,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Corredores y viajantes (gastos estimados movilidad, viaticos, etc.)',0,0,'L',0);
			$pdf->Cell(15,5,$row->CORREDORES,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Donaciones, partidos politicos e iglesia (hasta 5% ganancia neta)',0,0,'L',0);
			$pdf->Cell(15,5,$row->DONACIONES,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Aportes a obra social (diferencia)',0,0,'L',0);
			$pdf->Cell(15,5,$row->AD_OBRASOCIAL,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Cuotas medicos asistencial (tope 5% ganancia neta)',0,0,'L',0);
			$pdf->Cell(15,5,$row->CUOTA_MED_ASIS,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Honorarios asistencia medica (hasta e 40% de lo gastado)',0,0,'L',0);
			$pdf->Cell(15,5,$row->HON_MEDICOS,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Otros descuentos obligatorios (ley Nac. Prov. y Munic.)',0,0,'L',0);
			$pdf->Cell(15,5,$row->OTROS_DESCLEY,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Cuota sindical (Anexo III res. gral. 1261)',0,0,'L',0);
			$pdf->Cell(15,5,$row->SINDICATO,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Empleada domestica (se deduce la remuneracion)',0,0,'L',0);
			$pdf->Cell(15,5,$row->SERV_DOMESTICO,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Intereses por prestamos hipotecarios (hasta 20.000 anuales)',0,0,'L',0);
			$pdf->Cell(15,5,$row->PRESTAMOS_HIP,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Mas',0,0,'L',0);
			$pdf->Cell(15,5,'',0,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancias no remunerativas',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_NO_REMU,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancia NETA',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_NETA,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancia neta total del mes',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_NETA_MENSUAL,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Mas',0,0,'L',0);
			$pdf->Cell(15,5,'',0,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancia neta total acumulada de meses anteriores',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_MESES_ANT,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancia neta total acumulada al mes',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_ACUM,1,1,'R');
			
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Menos',0,0,'L',0);
			$pdf->Cell(15,5,'',0,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'ganancia no imponible',0,0,'L',0);
			$pdf->Cell(15,5,$row->GAN_NO_IMP,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Deduccion especial',0,0,'L',0);
			$pdf->Cell(15,5,$row->DED_ESPECIAL,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Cargas de familia, Esposa, Ingreso neto no mayor a 12960 anual',0,0,'L',0);
			$pdf->Cell(15,5,$row->DED_CONYUGE,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Hijos, Hijastros',0,0,'L',0);
			$pdf->Cell(15,5,$row->DED_HIJO,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Otros (nieto, bisnieto, suegro, nuera, hermano, etc.)',0,0,'L',0);
			$pdf->Cell(15,5,$row->OTRAS_CARGAS,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'',0,0,'L',0);
			$pdf->Cell(15,5,'',0,1,'C');
			
			
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Ganancia neta sujeta a impuestos acumulados',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->GAN_IMP_ACUM,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Monto fijo',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->MONTO_FIJO,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Escala Art. 90',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->PORCENTAJE,1,1,'R');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Importe bruto de retencion acumulada',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->RET_ACUM,1,1,'R');
			
			
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Menos',0,0,'L',0);
			$pdf->Cell(15,5,'',0,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Retenciones practicadas',0,0,'L',0);
			$pdf->Cell(15,5,$row->RET_PRACTICADAS,1,0,'R');
			$pdf->Cell(15,5,'',1,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Mas',0,0,'L',0);
			$pdf->Cell(15,5,'',0,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'Retenciones en exceso',0,0,'L',0);
			$pdf->Cell(15,5,'',0,1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(130,5,'RETENCIONES A PRACTICAR O INGRESAR',0,0,'L',0);
			$pdf->Cell(15,5,'',1,0,'C');
			$pdf->Cell(15,5,$row->RETENCION_ACTUAL,1,1,'R');
			
			
			
			
			
		} else {
			$pri='NO';
		}
		
	}
	
	$y_line=$pdf->GetY();
	$salto_pagina = number_format($y_line,0,'.',',');
}

$pdf->Output();	



?>