<?php 
session_start();

include("../../db_conecta.inc.php");

//$DB->debug = true;
//print_r($_REQUEST);
include("../../funcion.inc.php");
include('../../jscalendar-1.0/calendario.php'); 

$periodo = validarAsignarParametros('periodo','1');
$mes = validarAsignarParametros('mes','1');
$planta = validarAsignarParametros('planta','1');
$legajo = validarAsignarParametros('legajo','1');//1 devuelve null

$_pagi_pg  = validarAsignarParametros('_pagi_pg','null');
$guardar  = validarAsignarParametros('guardar','1');
$aplica  = validarAsignarParametros('aplica','1');

$ids_de_empleados_listados  = validarAsignarParametros('ids_de_empleados_listados','1');
$ids_de_empleados_listados  = urldecode($ids_de_empleados_listados);
$ids_de_empleados_listados  = unserialize($ids_de_empleados_listados);



// print_r($_REQUEST);
//echo "<br>_pagi_pg:$_pagi_pg";
/*
echo "<br>ids_de_empleados_listados: $ids_de_empleados_listados";
echo "<br><br>request";
print_r($_REQUEST);
echo "<br>idel:end";
*/

if($guardar){
	if($ids_de_empleados_listados && is_array($ids_de_empleados_listados) && count($ids_de_empleados_listados > 0)){
		foreach($ids_de_empleados_listados as $idel){
			
			//echo "<br>idel: $idel";
			
			try {
				$rs = $DB->Execute("UPDATE rrhh.T_ACUM_IMPUESTO
									SET aplica = 'N'
									WHERE ID_EMPLEADO = ? and PERIODO = ? and MES = ?", array($idel,$periodo,$mes));
			}catch (exception $e){ 
				die($DB->ErrorMsg());
			}
		}
	}//else{ echo "<br>else de idel: $ids_de_empleados_listados";}
	if($aplica && is_array($aplica) && count($aplica > 0)){
		//guarda aplica
		foreach($aplica as $apl){
			try {
				$rs = $DB->Execute("UPDATE rrhh.T_ACUM_IMPUESTO
									SET aplica = 'S'
									WHERE ID_EMPLEADO = ? and PERIODO = ? and MES = ?", array($apl,$periodo,$mes));
			}catch (exception $e){ 
				die($DB->ErrorMsg());
			}
		}
	}
}


try { 
    $rs_periodo = $DB->Execute("
	SELECT 
		PERIODO as CODIGO,
		PERIODO AS DESCRIPCION
    FROM 
		rrhh.T_PERIODO_FISCAL
	ORDER BY 
		PERIODO");
} catch (exception $e) {    
    die ($DB->ErrorMsg()); 
} 

try { 
    $rs_mes = $DB->Execute("
	SELECT DISTINCT
		MES as CODIGO,
		MES AS DESCRIPCION
    FROM 
		rrhh.T_ACUM_IMPUESTO
	ORDER BY 
		MES");
} catch (exception $e) {    
    die ($DB->ErrorMsg()); 
}

try { 
    $rs_planta = $DB->Execute("
	SELECT DISTINCT
		ID_PLANTA as CODIGO,
		DESCRIPCION AS DESCRIPCION
    FROM 
		rrhh.T_PLANTA
	ORDER BY 
		DESCRIPCION");
} catch (exception $e) {    
    die ($DB->ErrorMsg()); 
}

//echo '....'.$legajo;
$sql_legajo = '';
if(isset($_REQUEST['legajo']) and $legajo<>NULL){
	$sql_legajo = ' and LEGAJO = ' . $legajo;
}
else
{
	$sql_legajo = '';
}
$sql_planta = '';
if($planta and $planta<>0 and $planta<>''){
	$sql_planta = ' and ID_PLANTA = ' . $planta;
}else {
	$planta = '';
}


$variables[0]=$periodo;
$variables[1]=$mes;

//$variables[2]=$planta;
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
FROM rrhh.T_ACUM_IMPUESTO 
WHERE 
	 --aplica='N'
	PERIODO = ?
	and MES = ?
	
	$sql_legajo 
	$sql_planta
	
ORDER BY  desc_empleado asc
"; 
$_SESSION['sql']=$_pagi_sql;

$_pagi_cuantos =12; 
//OPCIONAL. Entero. Cantidad de registros que contendr? como m?ximo cada p?gina. Por defecto est? en 20.

$_pagi_conteo_alternativo=true;
//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto est? en false.

$_pagi_nav_num_enlaces=7;
//OPCIONAL Entero. Cantidad de enlaces a los n?meros de p?gina que se mostrar?n como m?ximo en la barra de navegaci?n.

$_pagi_nav_estilo="small";
//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginaci?n. Por defecto no se especifica estilo.

$_pagi_propagar[0] = 'periodo';
$_pagi_propagar[1] = 'mes';
$_pagi_propagar[2] = 'planta';
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

<table width="72%"  border="0" align="center" style="margin-top:5px;margin-bottom:10px;">
<tr>
	<td colspan="10" align="center">
		<div align="Center" class="tdVerde" style="padding:3px;">
		<h2><Strong>Informe impuesto a las ganancias</Strong></h2>
		</div>  
	</td>
</tr>
</table>

<form  name="form1" method="post" action="#" onsubmit="ajax_post('contenido','calculo/informe_impuesto_ganancias.php',this); return false;">

<table width="71%"  border="0" align="center" style="margin-bottom:5px;">
<tr>
        <td width="85%" align="right" class="Dependencia2">
            Periodo:<span  style="margin-right:5px;"> <?php armar_combo($rs_periodo,'periodo',$periodo); ?></span>
            Mes:<strong> <?php //armar_combo($rs_mes,'mes',$mes); ?></strong>
			
<select class="small" id="mes" name="mes" style="margin-right:15px;">
				<option value="1" <?php if($mes == '1'){ echo 'SELECTED';} ?>>1</option>
				<option value="2" <?php if($mes == '2'){ echo 'SELECTED';} ?>>2</option>
				<option value="3" <?php if($mes == '3'){ echo 'SELECTED';} ?>>3</option>
				<option value="4" <?php if($mes == '4'){ echo 'SELECTED';} ?>>4</option>
				<option value="5" <?php if($mes == '5'){ echo 'SELECTED';} ?>>5</option>
				<option value="6" <?php if($mes == '6'){ echo 'SELECTED';} ?>>6</option>
				<option value="7" <?php if($mes == '7'){ echo 'SELECTED';} ?>>7</option>
				<option value="8" <?php if($mes == '8'){ echo 'SELECTED';} ?>>8</option>
				<option value="9" <?php if($mes == '9'){ echo 'SELECTED';} ?>>9</option>
				<option value="10" <?php if($mes == '10'){ echo 'SELECTED';} ?>>10</option>
				<option value="11" <?php if($mes == '11'){ echo 'SELECTED';} ?>>11</option>
				<option value="12" <?php if($mes == '12'){ echo 'SELECTED';} ?>>12</option>
			</select>
			
			Legajo: <input type="text" id="legajo" name="legajo" value="<?php echo $legajo; ?>" class="smallTahoma" />
			Planta: <span  style="margin-right:5px;"> <?php armar_combo_todos($rs_planta,'planta',$planta); ?></span>
			
        </td>
  <td width="10%" align="center">
        <input name="buscar" class="smallTahoma" id="buscar" value="Buscar" type="submit" style="margin-right:5px;"/>
		</td>
  <td width="5%" align="left" valign="middle">
			<a href="#">
			<img width="16" height="16" border="0" onclick="window.open('calculo/informe_impuesto_ganancias_pdf.php?planta='+document.getElementById('planta').value+'&legajo='+document.getElementById('legajo').value+'&periodo='+document.getElementById('periodo').value+'&mes='+document.getElementById('mes').value, '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')" title="Reporte de Pantalla" src="image/printer.png">			</a>		</td>
             <td width="6%" ><span class="Estilo2"><a href="#" class="smallTahomaRojoNegrita"><a href="#" class="smallTahomaRojoNegrita"><img src="image/Excel-Document.png" title="Reporte en Excel" width="24" height="24" border="0" onclick="window.open('calculo/informe_impuesto_excel.php?planta='+document.getElementById('planta').value+'&legajo='+document.getElementById('legajo').value+'&periodo='+document.getElementById('periodo').value+'&mes='+document.getElementById('mes').value, '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')" /></a></span></td>
    </tr>
</table>
</form>


<form  name="form2" method="post" action="#" onsubmit="ajax_post('contenido','calculo/procesar_asignar_items_seleccionados.php',this); return false;">

<input type="hidden" name="id_empleado" id="id_empleado" value="<?php echo $id_empleado; ?>" />
<input type="hidden" name="periodo" id="periodo" value="<?php echo $periodo; ?>" />
<input type="hidden" name="mes" id="mes" value="<?php echo $mes; ?>" />
<input type="hidden" name="_pagi_pg" id="_pagi_pg" value="<?php echo $_pagi_pg; ?>" />
<input type="hidden" name="id_planta" id="id_planta" value="<?php echo $planta; ?>" />
<input type="hidden" name="descripcion" id="descripcion" value="<?php echo $legajo; ?>" />
<input type="hidden" name="legajo" id="legajo" value="<?php echo $legajo; ?>" />
			
<table width=71% border="0" align="center" cellspacing="1">
				  
                  <!--<tr><td colspan="2" align="center"><input type="checkbox" name="op"  id="op" value="sel" onclick="if(op.checked) {seleccionar_todo(form2)} else {deseleccionar_todo(form2)}">Todos/Ninguno<br></td></tr>
					
                  
                  <tr><td colspan="2" align="center"><input style="text-align:center; width:160px;background:#CCCCFF;" name="marcar" value="Seleccionar Todos" type="button" onclick="seleccionar_todo(form2)" /></td>
                  <td colspan="2" align="center"><input style="text-align:center;width:160px;background:#CCCCFF;" name="marcar" value="Quitar Seleccion a Todos" type="button" onclick="deseleccionar_todo(form2)" /></td>
                  </tr>-->
<tr class="th4">
	<td colspan="10" class="th" align="center">Pagina <?php echo $_pagi_navegacion.' '.$_pagi_info ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="op" id="op" value="sel" onclick="if(op.checked) {seleccionar_todo(form2)} else {deseleccionar_todo(form2)}" />Todos/Ninguno</td>
</tr>
</table>

<table width=72% border="0" align="center" cellspacing="1" style="margin-bottom:50px;">
<tr align="center" class="th2">
        <td width="88" align="center">Legajo</td>
	  <td width="235" align="center" >Nombre</td>
      <td width="71" align="center">Periodo</td>
      <td width="104">Retencion Actual</td>
      <td width="98">Ver detalle</td>
      <td width="86">Impacta Haberes
      <input name="guardar" class="smallTahoma" id="guardar" value="Ejecutar" type="submit" style="margin-left:5px;"/></td>
    </tr>
<?php

$ids_de_empleados_listados = array();

while ($row = $_pagi_result->FetchNextObject($toupper=true)){ 
	array_push($ids_de_empleados_listados, $row->ID_EMPLEADO);
?>
    <tr class="td">
        <td align="center" valign="middle" class="td2"><?php echo str_pad(trim($row->LEGAJO),5,'0',STR_PAD_LEFT); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo $row->DESC_EMPLEADO; //$row->ID_EMPLEADO . ' - ' . ?></td>
        <td align="center" valign="middle" class="td2"><?php echo $row->PERIODO.'/'.str_pad(trim($row->MES),2,'0',STR_PAD_LEFT); ?></td>
        <td align="right" valign="middle" class="td2"><?php echo number_format($row->RETENCION_ACTUAL,'2','.',''); ?></td>
        <td align="center" valign="middle" class="td2" style="padding-left:5px;padding-right:5px;width:100px;">
		
			<a href="#" onClick="ajax_get('contenido','calculo/informe_impuesto_ganancias_detalle.php','planta=<?php echo $row->ID_PLANTA; ?>&legajo=<?php echo $row->LEGAJO; ?>&id_empleado=<?php echo $row->ID_EMPLEADO; ?>&periodo=<?php echo $row->PERIODO; ?>&mes=<?php echo $row->MES; ?>&_pagi_pg=<?php echo $_pagi_pg; ?>&descripcion=<?php echo $legajo;?>'); return false;"><img width="16" height="16" border="0" title="Ver Detalle" src="image/ver.png"></a>
			
			<a href="#" onClick="ajax_get('contenido','calculo/informe_impuesto_ganancias_detalle_modificar.php','planta=<?php echo $row->ID_PLANTA; ?>&legajo=<?php echo $row->LEGAJO; ?>&id_empleado=<?php echo $row->ID_EMPLEADO; ?>&periodo=<?php echo $row->PERIODO; ?>&mes=<?php echo $row->MES; ?>&_pagi_pg=<?php echo $_pagi_pg; ?>'); return false;"><img width="16" height="16" border="0" title="Modificar" src="image/edit.png"></a>
			
			<a href="#"><img width="16" height="16" border="0" onclick="window.open('calculo/informe_impuesto_ganancias_detalle_pdf.php?planta=<?php echo $row->ID_PLANTA; ?>&legajo=<?php echo $row->LEGAJO; ?>&periodo='+document.getElementById('periodo').value+'&mes='+document.getElementById('mes').value+'&id_empleado='+<?php echo $row->ID_EMPLEADO; ?>, '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')" title="Modificar" src="image/printer.png"></a>		</td>
		<?php if($row->APLICA=='N')
		{?>
			<td align="center" valign="middle" class="td2"><input type="checkbox" name="aplica[]" id="aplica_<?php echo $row->ID_EMPLEADO; ?>" <?php if($row->APLICA=='S'){echo ' checked="checked"';} ?> value="<?php echo $row->ID_EMPLEADO; ?>" /></td>
        <?PHP
		}
		else
		{
		?>
        	<td align="center" valign="middle" class="td2">Asignado</td>
        <?php
		}
		?>
    </tr>
<?php 
}
?>
</table>
<input type="hidden" name="ids_de_empleados_listados" id="ids_de_empleados_listados" value="<?php echo urlencode(serialize($ids_de_empleados_listados)); ?>" />
</form>
