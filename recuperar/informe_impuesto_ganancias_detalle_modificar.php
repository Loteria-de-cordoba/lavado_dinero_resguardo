<?php 
session_start();

include("../../db_conecta.inc.php");
include("../../funcion.inc.php");
include('../../jscalendar-1.0/calendario.php'); 

$id_empleado = validarAsignarParametros('id_empleado','1');
$periodo = validarAsignarParametros('periodo','1');
$mes = validarAsignarParametros('mes','1');
$guardar  = validarAsignarParametros('guardar','1');
$aplica  = validarAsignarParametros('aplica','1');
$retencion_actual  = validarAsignarParametros('retencion_actual','1');
$_pagi_pg  = validarAsignarParametros('_pagi_pg','null');

$planta = validarAsignarParametros('planta','1');
$legajo = validarAsignarParametros('legajo','1');

$msj_error = "";

//print_r($_REQUEST);

if($guardar){
	if($aplica){
		//guarda aplica
		try {
			$rs = $DB->Execute("UPDATE rrhh.T_ACUM_IMPUESTO
								 SET aplica = 'S'
								WHERE ID_EMPLEADO = ? and PERIODO = ? and MES = ?", array($id_empleado,$periodo,$mes));
		}catch (exception $e){ 
			die($DB->ErrorMsg());
		}
	} else {
		// guarda no aplica
		try {
			$rs = $DB->Execute("UPDATE rrhh.T_ACUM_IMPUESTO
								 SET aplica = 'N'
								WHERE ID_EMPLEADO = ? and PERIODO = ? and MES = ?", array($id_empleado,$periodo,$mes));
		}catch (exception $e){ 
			die($DB->ErrorMsg());
		}
	}
	
	if($retencion_actual){
	
		if(is_numeric($retencion_actual)){
			try {
				$rs = $DB->Execute("UPDATE rrhh.T_ACUM_IMPUESTO
									 SET retencion_actual = ?
									WHERE ID_EMPLEADO = ? and PERIODO = ? and MES = ?", array($retencion_actual,$id_empleado,$periodo,$mes));
			}catch (exception $e){ 
				die($DB->ErrorMsg());
			}
			
			$msj_error = "El cambio fue realizado con exito";
			
		} else {
			$msj_error = "Error: \"RETENCIONES A PRACTICAR\" debe ser un valor numerico";
		}
	}
	
}

try { 
    $rs_detalle = $DB->Execute("
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

$row = $rs_detalle->FetchNextObject($toupper=true);

?>

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo2 {font-size: 5%}
-->
</style>

<table width="70%"  border="0" align="center" style="margin-top:15px;margin-bottom:10px;">
<tr>
	<td colspan="10" align="center">
		<div align="Center" class="tdVerde" style="padding:8px;">
		<h3><Strong>Modificar calculo de las retenciones del impuesto a las ganancias de</Strong></h3>
		<h2><Strong>Leg <?php echo str_pad($row->LEGAJO,5,0,STR_PAD_LEFT) .' '. $row->DESC_EMPLEADO; ?> </Strong></h2>
		</div>  
	</td>
</tr>
</table>



<form  name="form1" method="post" action="#" onsubmit="ajax_post('contenido','calculo/informe_impuesto_ganancias_detalle_modificar.php',this); return false;">
<table width="70%"  border="0" align="center" style="margin-bottom:15px;">
    <tr>
        <td width="55%" align="right" valign="middle">
			<a href="#"><img width="16" height="16" border="0" onclick="window.open('calculo/informe_impuesto_ganancias_detalle_pdf.php?planta=<?php echo $row->ID_PLANTA; ?>&legajo=<?php echo $row->LEGAJO; ?>&periodo=<?php echo $periodo; ?>&mes=<?php echo $mes; ?>&id_empleado='+<?php echo $row->ID_EMPLEADO; ?>, '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')" title="Modificar" src="image/printer.png"></a>
		</td>
		<td width="45%" align="right" valign="middle">
			<a href="#" onClick="ajax_get('contenido','calculo/informe_impuesto_ganancias.php','planta=<?php echo $row->ID_PLANTA; ?>&legajo=<?php echo $row->LEGAJO; ?>&periodo=<?php echo $periodo; ?>&_pagi_pg=<?php echo $_pagi_pg; ?>&mes=<?php echo $mes; ?>'); return false;" style="text-decoration:none;">Volver<img width="16" height="16" border="0" title="Volver" src="image/undo.png" style="margin-left:5px;"></a>
		</td>
    </tr>
	
</table>
</form>



<form  name="form2" method="post" action="#" onsubmit="ajax_post('contenido','calculo/informe_impuesto_ganancias_detalle_modificar.php',this); return false;">
<table width=70% border="0" align="center" cellspacing="1" style="margin-bottom:50px;padding:5px;">
	
	<tr>
		<td colspan="2" align="center" style="color:#DA3014;"><?php echo $msj_error;?></td>
	</tr>
	
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left"><?php if($row->APLICA=='S'){echo 'APLICA';}else{echo 'NO APLICA';} ?></td><td align="center" ><input type="checkbox" name="aplica" id="aplica_<?php echo ID_EMPLEADO; ?>" <?php if($row->APLICA=='S'){echo ' checked="checked"';} ?> value="1">
			<input type="hidden" name="id_empleado" id="id_empleado" value="<?php echo $id_empleado; ?>" />
			<input type="hidden" name="periodo" id="periodo" value="<?php echo $periodo; ?>" />
			<input type="hidden" name="mes" id="mes" value="<?php echo $mes; ?>" />
			
		</td>
    </tr>
	
    <tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Ganancia Bruta Habitual del mes</td><td align="right" ><?php echo $row->GAN_BRUTA_HAB_MES;?></td>
    </tr>
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Ganancia por conceptos no habituales</td><td align="right" ><?php echo $row->GAN_BRUTA_HAB_MES;?></td>
    </tr>
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Ganancia Bruta total del mes</td><td align="right" ><?php echo $row->GAN_BRUTA_TOTAL_MES;?></td>
    </tr>
	
	<tr class="th6">
        <td width="88%" class="thpadding_titulo" align="left">Menos</td><td align="center" ></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Aportes a fondos de jubilacion (Nac. Prov. y Munc.)</td><td align="right" ><?php echo $row->JUBILACION;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Aportes a obras sociales del recibo de sueldo (no tiene tope)</td><td align="right" ><?php echo $row->OBRA_SOCIAL;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Primas de seguros de vida (en caso de muerte) Hasta 996,23 anual</td><td align="right" ><?php echo $row->SEGURO_VIDA;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Gastos de sepelio (por persona a cargo de titular) Hasta 996,23 anual</td><td align="right" ><?php echo $row->GASTOS_SEPELIO;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Corredores y viajantes (gastos estimados movilidad, viaticos, etc.)</td><td align="right" ><?php echo $row->CORREDORES;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Donaciones, partidos politicos e iglesia (hasta 5% ganancia neta)</td><td align="right" ><?php echo $row->DONACIONES;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Aportes a obra social (diferencia)</td><td align="right" ><?php echo $row->AD_OBRASOCIAL;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Cuotas medicos asistencial (tope 5% ganancia neta)</td><td align="right" ><?php echo $row->CUOTA_MED_ASIS;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Honorarios asistencia medica (hasta e 40% de lo gastado)</td><td align="right" ><?php echo $row->HON_MEDICOS;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Otros descuentos obligatorios (ley Nac. Prov. y Munic.)</td><td align="right" ><?php echo $row->OTROS_DESCLEY;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Cuota sindical (Anexo III res. gral. 1261)</td><td align="right" ><?php echo $row->SINDICATO;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Empleada domestica (se deduce la remuneracion)</td><td align="right" ><?php echo $row->SERV_DOMESTICO;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Intereses por prestamos hipotecarios (hasta 20.000 anuales)</td><td align="right" ><?php echo $row->PRESTAMOS_HIP;?></td>
    </tr>
	

	<tr class="th5">
        <td width="88%" class="thpadding_titulo" align="left">Mas</td><td align="center" ></td>
    </tr>
	<tr class="th5">
        <td width="88%" align="left" class="thpadding">Ganancias no remunerativas</td><td align="right" ><?php echo $row->GAN_NO_REMU;?></td>
    </tr>
	<tr class="th5">
        <td width="88%" align="left" class="thpadding">Ganancia neta</td><td align="right" ><?php echo $row->GAN_NETA;?></td>
    </tr>
	
	
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Ganancia neta total del mes</td><td align="right" ><?php echo $row->GAN_NETA_MENSUAL;?></td>
    </tr>
	
	
	<tr class="th5">
        <td width="88%" class="thpadding_titulo" align="left">Mas</td><td align="center" ></td>
    </tr>
	<tr class="th5">
        <td width="88%" align="left" class="thpadding">Ganancia neta total acumulada de meses anteriores</td><td align="right" ><?php echo $row->GAN_MESES_ANT;?></td>
    </tr>
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Ganancia neta total acumulada al mes</td><td align="right" ><?php echo $row->GAN_ACUM;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" class="thpadding_titulo" align="left">Menos</td><td align="center" ></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">ganancia no imponible</td><td align="right" ><?php echo $row->GAN_NO_IMP;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Deduccion especial</td><td align="right" ><?php echo $row->DED_ESPECIAL;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Cargas de familia, Esposa, Ingreso neto no mayor a 12960 anual</td><td align="right" ><?php echo $row->DED_CONYUGE;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Hijos, Hijastros</td><td align="right" ><?php echo $row->DED_HIJO;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Otros (nieto, bisnieto, suegro, nuera, hermano, etc.)</td><td align="right" ><?php echo $row->OTRAS_CARGAS;?></td>
    </tr>
	
	
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Ganancia neta sujeta a impuestos acumulados</td><td align="right" ><?php echo $row->GAN_IMP_ACUM;?></td>
    </tr>
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Monto fijo</td><td align="right" ><?php echo $row->MONTO_FIJO;?></td>
    </tr>
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Escala Art. 90</td><td align="right" ><?php echo $row->PORCENTAJE;?></td>
    </tr>
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">Importe bruto de retencion acumulada</td><td align="right" ><?php echo $row->RET_ACUM;?></td>
    </tr>
	<tr class="th6">
        <td width="88%" class="thpadding_titulo" align="left">Menos:</td><td align="center" ></td>
    </tr>
	<tr class="th6">
        <td width="88%" align="left" class="thpadding">Retenciones practicadas</td><td align="right" ><?php echo $row->RET_PRACTICADAS;?></td>
    </tr>
	<tr class="th5">
        <td width="88%" class="thpadding_titulo" align="left">Mas</td><td align="center" ></td>
    </tr>
	<tr class="th5">
        <td width="88%" align="left" class="thpadding">Retenciones en exceso</td><td align="center" ></td>
    </tr>
	
	
	
	<tr class="th2 thpadding_titulo">
        <td width="88%" class="thpadding_titulo" align="left">RETENCIONES A PRACTICAR O INGRESAR</td><td align="right" >
		<input type="text" name="retencion_actual" id="retencion_actual" style="text-align:right;" value="<?php echo $row->RETENCION_ACTUAL;?>" />
		</td>
    </tr>
	
	
	
	
</table>


<table width=70% border="0" align="center" cellspacing="1" style="margin-bottom:50px;">
	
	<tr class="thpadding_titulo">
        <td align="right" >
<?php
/*
<input name="volver" class="smallTahoma" id="volver" value="Volver" type="button" style="margin-left:5px;" onClick="ajax_get('contenido','calculo/informe_impuesto_ganancias.php','periodo=<?php echo $periodo; ?>&_pagi_pg=<?php echo $_pagi_pg; ?>&mes=<?php echo $mes; ?>'); return false;" />
*/
?>
		
			
			
			<input name="guardar" class="smallTahoma" id="guardar" value="Guardar" type="submit" style="margin-left:5px;"/>
			<input name="cancelar" class="smallTahoma" id="cancelar" value="Cancelar" type="reset"  onClick="ajax_get('contenido','calculo/informe_impuesto_ganancias.php','periodo=<?php echo $periodo; ?>&_pagi_pg=<?php echo $_pagi_pg; ?>&mes=<?php echo $mes; ?>'); return false;" style="margin-left:5px;"/>
			
			
		</td>
    </tr>
</table>

<input type="hidden" name="_pagi_pg" id="_pagi_pg" value="<?php echo $_pagi_pg; ?>" />

</form>
