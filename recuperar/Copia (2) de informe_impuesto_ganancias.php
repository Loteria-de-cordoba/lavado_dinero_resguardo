<?php 
session_start();

include("../../db_conecta.inc.php");
include("../../funcion.inc.php");
include('../../jscalendar-1.0/calendario.php'); 

/*
$controlabusqueda=0;
$h=0;
$nuevobene='';
$_SESSION['bene']='';
$_SESSION['canfranco']='';
$array_fecha = FechaServer();
$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"]; 
$partefecha=substr($fecha,6);
*/


$periodo = validarAsignarParametros('periodo','1');

try { 
    $rs_periodo = $DB->Execute("SELECT PERIODO as CODIGO,
      PERIODO AS DESCRIPCION
    FROM T_PERIODO_FISCAL
	ORDER BY PERIODO");
} catch (exception $e) {    
    die ($DB->ErrorMsg()); 
} 

$variables[0]=$periodo;
$_pagi_sql = "
SELECT ID_EMPLEADO,
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
  GAN_NO_REMUN,
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
FROM T_ACUM_IMPUESTO 
WHERE PERIODO = ?
ORDER BY desc_empleado
"; 
$_SESSION['sql']=$_pagi_sql;


$_pagi_cuantos =17; 
//OPCIONAL. Entero. Cantidad de registros que contendr? como m?ximo cada p?gina. Por defecto est? en 20.

$_pagi_conteo_alternativo=true;
//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto est? en false.

$_pagi_nav_num_enlaces=7;
//OPCIONAL Entero. Cantidad de enlaces a los n?meros de p?gina que se mostrar?n como m?ximo en la barra de navegaci?n.

$_pagi_nav_estilo="small";
//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginaci?n. Por defecto no se especifica estilo.

$_pagi_propagar[0]='periodo';
//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagar?n todas las que ya vengan por el url (GET).

include("../../paginator.inc.php"); 
$row = $_pagi_result->FetchNextObject($toupper=true);
$_pagi_result->MoveFirst();

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
		<h2><Strong>Informe impuesto a las ganancias</Strong></h2>
		</div>  
	</td>
</tr>
</table>

<form  name="form1" method="post" action="#" onsubmit="ajax_post('contenido','calculo/informe_impuesto_ganancias.php',this); return false;">

<table width="70%"  border="0" align="center" style="margin-bottom:15px;">
    <tr>
        <td width="24%" align="right" class="Dependencia2">
            Periodo:<strong> <?php armar_combo($rs_periodo,'periodo',$periodo); ?></strong>
        </td>
        <td width="16%">
            <input name="buscar" class="smallTahoma" id="buscar" value="Buscar" type="submit"/>
            
		</td>
        <td width="7%" align="right" valign="middle">
			<a href="#">
			<img width="20" height="20" border="0" onclick="window.open('calculo/informe_impuesto_ganancias_pdf.php?periodo='+document.getElementById('periodo').value, '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')" title="Reporte de Pantalla" src="image/Adobe Reader 7.png">
			</a>
		</td>
    </tr>
</table>

<table width=70% border="0" align="center" cellspacing="1">
<tr class="th4">
	<td colspan="10" class="th" align="center">Pagina <?php echo $_pagi_navegacion.' '.$_pagi_info ?></td>
</tr>
</table>

<table width=70% border="0" align="center" cellspacing="1">
    <tr align="center" class="th2">
        <td width="9%" align="center">Legajo</td>
		<td align="center" >Nombre</td>
        <td width="9%" align="center">Periodo</td>
        
        <td width="15%">Ganancia bruta mensual</td>
        <td width="15%">Ganancia bruta total mensual</td>
        <td width="15%">Ganancia neta mensual</td>
        <td width="15%">Acumulado</td>
        <td width="15%">Retencion Actual</td>
        <td width="5%">Ver retenciones</td>
    </tr>

<?php
while ($row = $_pagi_result->FetchNextObject($toupper=true)){ 
?>
    <tr class="td">
            
        <td align="center" valign="middle" class="td2"><?php echo str_pad(trim($row->LEGAJO),5,'0',STR_PAD_LEFT); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo $row->DESC_EMPLEADO; ?></td>
        <td align="center" valign="middle" class="td2"><?php echo $row->PERIODO.'/'.str_pad(trim($row->MES),2,'0',STR_PAD_LEFT); ?></td>
        
        <td align="right" valign="middle" class="td2"><?php echo number_format($row->GAN_BRUTA_HAB_MES,'2','.',''); ?></td>
        <td align="right" valign="middle" class="td2"><?php echo number_format($row->GAN_BRUTA_TOTAL_MES,'2','.',''); ?></td>
		
        <td align="right" valign="middle" class="td2"><?php echo number_format($row->GAN_NETA_MENSUAL,'2','.',''); ?></td>
        <td align="right" valign="middle" class="td2"><?php echo number_format($row->GAN_IMP_ACUM,'2','.',''); ?></td>
        <td align="right" valign="middle" class="td2"><?php echo number_format($row->RETENCION_ACTUAL,'2','.',''); ?></td>
		
        <td align="right" valign="middle" class="td2">
			<a href="#">
			<img width="20" height="20" border="0" onclick="window.open('calculo/informe_impuesto_ganancias_pdf.php?periodo='+document.getElementById('periodo').value, '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')" title="Reporte de Pantalla" src="image/ver.png">
			</a>
		</td>
    
    </tr>
<?php 
}
?>
</table>
	
</form>

<?php

/*
<td width="6%" align="center" valign="middle" class="td2">
	<span class="Estilo2">
	<a href="#" onClick="if (confirmSubmit('<?php echo "Eliminar concepto"; ?>')) { ajax_get('contenido','deducciones/abm_importe_del.php','periodo=<?php echo $row->ID_PERIODO;?>&concepto=<?php echo $row->ID_DEDUCCION;?>');}"><img src="image/C_DeleteState_md.png" title"Eliminar" width="16" height="16" border="0"></a>
	</span>
</td>
<td width="6%" align="center" valign="middle" class="td2">
	<span class="Estilo2">
	<a href="#" class="smallTahomaRojoNegrita">
	<img src="image/b_edit.png" title="Modificar" width="18" height="18" border="0" onclick="ajax_get('contenido','deducciones/abm_importe_mod.php','periodo=<?php echo $row->ID_PERIODO;?>&concepto=<?php echo $row->ID_DEDUCCION;?>&descripcion=<?php echo $row->DESCRIPCION;?>&monto=<?php echo $row->IMPORTE_ANUAL;?>&porcentaje=<?php echo $row->PORCENTAJE;?>&porcentajegn=<?php echo $row->PORC_GNETA;?>');" />
	</a>
	</span>
</td>
*/

?>