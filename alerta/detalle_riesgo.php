<?php /*COMPONENTE DEL RIESGO
* 08/01/2014
* PARODI VICTOR
* 
*/
session_start();
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
//print_r($_REQUEST);
//$db->debug = true;
$riesgo=$_REQUEST['riesgo'];
$incidencia=$_REQUEST['componente'];
$voy=$_REQUEST['voy'];
$abro=$_REQUEST['abro'];
$pesitos=$_REQUEST['pesitos'];
$cuit=$_REQUEST['cuit'];
$otrocuit=$_REQUEST['otrocuit'];
$fecha=$_REQUEST['fecha'];
$mensaje=$_REQUEST['mensaje'];
	/*if(isset($_REQUEST['vengomod']))
		{
			header("location:estadistico.php?mensaje=$mensaje&riesgo=$riesgo&voy=$voy&abro=$abro&pesitos=$pesitos&cuit=$cuit&otrocuit=$otrocuit&fecha=$fecha&componente=$incidencia&minimo=$minimo&maximo=$maximo");
		}*/
if($riesgo==1)
{
//obtengo el rango permitido de incidencia
try {
				 $rs_rank = $db ->Execute("select a.incidencia+1 as anterior,
											b.incidencia-1 as posterior
											from PLA_AUDITORIA.t_riesgo_sujeto a,
											PLA_AUDITORIA.t_riesgo_sujeto b
											where a.incidencia=(select max(incidencia)
																	from PLA_AUDITORIA.t_riesgo_sujeto
																	where incidencia<=?)
											and b.incidencia=(select min(incidencia)
																	from PLA_AUDITORIA.t_riesgo_sujeto
																	where incidencia>=?)
									",array($incidencia, $incidencia));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_rank =$rs_rank->FetchNextObject($toupper=true);
				$meinci=$row_rank->ANTERIOR;
				$mainci=$row_rank->POSTERIOR;
		
//obtengo la descripcion
				try {
				 $rs_detalle = $db ->Execute("select id_riesgo_sujeto as id,
				 								descripcion 
												from PLA_AUDITORIA.t_riesgo_sujeto
												where incidencia=? 
									",array($incidencia));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_detalle =$rs_detalle->FetchNextObject($toupper=true);
				$id=$row_detalle->ID;
				$descripcion=$row_detalle->DESCRIPCION;
?>

<table width="680"  border="1" align="center">
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="background-color:#999966; font:bold"><b>Modificaci&oacute;n del Componente del Riesgo Respecto al Sujeto</b></td>
  </tr>
  <tr>
    <td width="227" height="24" style="background-color:#FFFFCC; font:bold"  align="left"><b>Descripcion del Componente:</b></td>
    <td width="437" class="td_detalle" align="left"><textarea name="descripcion"  style="text-align:left" class="small"  id="descripcion"  cols="70"/>
      <?php echo trim($descripcion);?>
    </textarea></td>
  </tr>
  <tr>
    <td width="227" height="24"  align="left" style="background-color:#FFFFCC; font:bold"><b>Incidencia sobre total del Riesgo:</b></td>
    <input name="mainc" type="hidden" id="mainc" value="<?php echo $mainci;?>"/>
    <input name="meinc" type="hidden" id="meinc" value="<?php echo $meinci;?>"/>
    <td width="437" class="td_detalle"><input name="incidencia" id="incidencia"  type="text" style="text-align:right" value="<?php echo $incidencia;?>" onblur="if(this.value<estad.mainc.value || this.value>estad.meinc.value) {alert('Entre los valores '+estad.mainc.value+' y '+estad.meinc.value);this.value='<?php echo $incidencia;?>';};return false;"/>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "[Entre ".$mainci." y ".$meinci."]";?></td>
   	
    <!--input name="incide" type="hidden" value="<?php// echo $id_base;?>" />
      <input name="descrip" type="hidden" value="<?php// echo $descrip;?>" />
  <input name="id_estado" type="hidden" value="<?php// echo $descrip;?>" />-->
  </tr>
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="background-color:#FFFFCC; font:bold"><input name="Modificar" style="font-weight:bold" type="button" value="Modificar" onClick="if(document.getElementById('abro').value!=1)
{ajax_get('contenido','alerta/procesar_modificar_detriesgo.php','fecha=<?php echo $fecha;?>&incidencia='+estad.incidencia.value+'&descripcion='+estad.descripcion.value+'&id=<?php echo $id;?>&voy=<?php echo $voy;?>&riesgo=<?php echo $riesgo;?>&abro=<?php echo $abro;?>&cuit=<?php echo $cuit;?>&otrocuit=<?php echo $otrocuit;?>&pesitos=<?php echo $pesitos;?>');return true;} else {alert('Cierre Estadistico para Poder Modificar');return false;}"></td>
  </tr>
  <?php if(isset($_REQUEST['mensaje']))
	{?>
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="color:#FF0000"><?php echo strtoupper($_REQUEST['mensaje'])?></td>
  </tr>
  <?php }?>
</table>
<?php 
  }
else if($riesgo==2)
{
//obtengo el rango permitido de incidencia
try {
				 $rs_rank = $db ->Execute("select a.incidencia+1 as anterior,
											b.incidencia-1 as posterior
											from PLA_AUDITORIA.t_riesgo_VALOR a,
											PLA_AUDITORIA.t_riesgo_VALOR b
											where a.incidencia=(select max(incidencia)
																	from PLA_AUDITORIA.t_riesgo_VALOR
																	where incidencia<=?)
											and b.incidencia=(select min(incidencia)
																	from PLA_AUDITORIA.t_riesgo_VALOR
																	where incidencia>=?)
									",array($incidencia, $incidencia));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_rank =$rs_rank->FetchNextObject($toupper=true);
				$meinci=$row_rank->ANTERIOR;
				$mainci=$row_rank->POSTERIOR;
//obtengo detalles de este rango
try {
 				$rs_detalle = $db ->Execute("select id_riesgo_valor as id,
												descripcion,
												minimo,
												maximo  
 								from PLA_AUDITORIA.t_riesgo_VALOR
								where incidencia=?
					",array($incidencia));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_detalle =$rs_detalle->FetchNextObject($toupper=true);
				$descripcion=$row_detalle->DESCRIPCION;
				$id=$row_detalle->ID;
				$minimo=$row_detalle->MINIMO;
				$maximo=$row_detalle->MAXIMO;
				
//obtengo tope inferior
try {
 				$rs_tope_mi = $db ->Execute("select minimo 
 								from PLA_AUDITORIA.t_riesgo_VALOR
								where incidencia=?
					",array($meinci));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_tope_mi =$rs_tope_mi->FetchNextObject($toupper=true);
				$tope_minimo=$row_tope_mi->MINIMO;
				
//obtengo tope superior
try {
 				$rs_tope_ma = $db ->Execute("select maximo
 								from PLA_AUDITORIA.t_riesgo_VALOR
								where incidencia=?
					",array($mainci));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_tope_ma =$rs_tope_ma->FetchNextObject($toupper=true);
				$tope_maximo=$row_tope_ma->MAXIMO;
	
	//tabla
	?>
<table width="680"  border="1" align="center">
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="background-color:#999966; font:bold"><b>Modificaci&oacute;n del Componente del Riesgo Respecto al valor de la Operaci&oacute;n</b></td>
  </tr>
  <tr>
    <td width="227" height="24" style="background-color:#FFFFCC; font:bold"  align="left"><b>Descripcion del Componente:</b></td>
    <td width="437" class="td_detalle" align="left"><textarea name="descripcion" style="text-align:left" class="small"  id="descripcion"  cols="70"/>
      <?php echo trim($descripcion);?>
    </textarea></td>
  </tr>
  
  <tr>
    <td width="227" height="24"  align="left" style="background-color:#FFFFCC; font:bold"><b>Incidencia sobre total del Riesgo:</b></td>
    <input name="mainc" type="hidden" id="mainc" value="<?php echo $mainci;?>"/>
    <input name="meinc" type="hidden" id="meinc" value="<?php echo $meinci;?>"/>
    <input name="esminimo" type="hidden" id="esminimo" value="<?php echo $minimo;?>"/>
    <input name="esmaximo" type="hidden" id="esmaximo" value="<?php echo $maximo;?>"/>
    <input name="estopemin" type="hidden" id="estopemin" value="<?php echo $tope_minimo;?>"/>
    <input name="estopemax" type="hidden" id="estopemax" value="<?php echo $tope_maximo;?>"/>
    <td width="437" class="td_detalle"><input name="incidencia2" id="incidencia2"  type="text" style="text-align:right" value="<?php echo $incidencia;?>"  onblur="if(this.value<estad.mainc.value || this.value>estad.meinc.value) {alert('Entre los valores '+estad.mainc.value+' y '+estad.meinc.value);this.value='<?php echo $incidencia;?>';};return false;"/>      &nbsp;&nbsp;&nbsp;&nbsp;<?php echo "[Entre ".$mainci." y ".$meinci."]";?></td>
    <!--input name="incide" type="hidden" value="<?php// echo $id_base;?>" />
      <input name="descrip" type="hidden" value="<?php// echo $descrip;?>" />
  <input name="id_estado" type="hidden" value="<?php// echo $descrip;?>" />-->
  </tr>
  <tr>
  <td width="284" height="24"  align="left" style="background-color:#FFFFCC; font:bold"><b>Desde Valor($):</b></td>
  <td width="377" class="td_detalle"><input name="minimo" id="minimo" type="text" style="text-align:right"  value="<?php echo number_format($minimo,2,'.','');?>" onblur="if(parseFloat(this.value)>parseFloat(estad.maximo.value) || parseFloat(this.value)<parseFloat(estad.estopemax.value)) {alert('Entre los valores '+parseFloat(estad.estopemax.value)+' y '+parseFloat(estad.maximo.value));this.value='<?php echo $minimo;?>';};return false;"/>[Use punto decimal]</td>
  </tr>
  
  <tr>
  <td width="284" height="24"  align="left" style="background-color:#FFFFCC; font:bold"><b>Hasta Valor($):</b></td>
  <td width="377" class="td_detalle"><input name="maximo" id="maximo" type="text" style="text-align:right"  value="<?php echo number_format($maximo,2,'.','');?>" onblur="if(estad.estopemin.value=='') {estad.estopemin.value='Infinito';};if(parseFloat(this.value)<parseFloat(estad.minimo.value) || parseFloat(this.value)>parseFloat(estad.estopemin.value)) {alert('Entre los valores '+parseFloat(estad.minimo.value)+' y '+estad.estopemin.value);this.value='<?php echo $maximo;?>';};return false;"/>[Use punto decimal]</td>
  </tr>
  
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="background-color:#FFFFCC; font:bold"><input name="Modificar" style="font-weight:bold" type="button" value="Modificar" onClick="ajax_get('contenido','alerta/procesar_modificar_detriesgo.php','fecha=<?php echo $fecha;?>&incidencia='+estad.incidencia2.value+'&descripcion='+estad.descripcion.value+'&id=<?php echo $id;?>&voy=<?php echo $voy;?>&riesgo=<?php echo $riesgo;?>&abro=<?php echo $abro;?>&cuit=<?php echo $cuit;?>&otrocuit=<?php echo $otrocuit;?>&pesitos=<?php echo $pesitos;?>&minimo='+parseFloat(estad.minimo.value)+'&maximo='+parseFloat(estad.maximo.value))"></td>
  </tr>
  <?php if(isset($_REQUEST['mensaje']))
	{?>
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="color:#FF0000"><?php echo strtoupper($_REQUEST['mensaje'])?></td>
  </tr>
  <?php }?>
</table>
<?php 
}
else
{
//obtengo el rango permitido de incidencia
try {
				 $rs_rank = $db ->Execute("select a.incidencia+1 as anterior,
											b.incidencia-1 as posterior
											from PLA_AUDITORIA.t_riesgo_CARACT a,
											PLA_AUDITORIA.t_riesgo_CARACT b
											where a.incidencia=(select max(incidencia)
																	from PLA_AUDITORIA.t_riesgo_CARACT
																	where incidencia<=?)
											and b.incidencia=(select min(incidencia)
																	from PLA_AUDITORIA.t_riesgo_CARACT
																	where incidencia>=?)
									",array($incidencia, $incidencia));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_rank =$rs_rank->FetchNextObject($toupper=true);
				$meinci=$row_rank->ANTERIOR;
				$mainci=$row_rank->POSTERIOR;
try {
 				$rs_detalle = $db ->Execute("select id_riesgo_caract as id,
												descripcion,
												minimo,
												maximo  
 								from PLA_AUDITORIA.t_riesgo_CARACT
								where incidencia=?
					",array($incidencia));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_detalle =$rs_detalle->FetchNextObject($toupper=true);
				$descripcion=$row_detalle->DESCRIPCION;
				$id=$row_detalle->ID;
				$minimo=$row_detalle->MINIMO;
				$maximo=$row_detalle->MAXIMO;
				
//obtengo tope inferior
try {
 				$rs_tope_mi = $db ->Execute("select minimo 
 								from PLA_AUDITORIA.t_riesgo_CARACT
								where maximo=?
					",array($meinci));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_tope_mi =$rs_tope_mi->FetchNextObject($toupper=true);
				$tope_minimo=$row_tope_mi->MINIMO;
				
//obtengo tope superior
try {
 				$rs_tope_ma = $db ->Execute("select maximo
 								from PLA_AUDITORIA.t_riesgo_CARACT
								where minimo=?
					",array($mainci));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_tope_ma =$rs_tope_ma->FetchNextObject($toupper=true);
				$tope_maximo=$row_tope_ma->MAXIMO;
	//tabla
	?>
<table width="680"  border="1" align="center">
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="background-color:#999966; font:bold"><b>Modificaci&oacute;n del Componente del Riesgo Respecto a Cant. de Premios Obtenidos</b></td>
  </tr>
  <tr>
    <td width="284" height="24" style="background-color:#FFFFCC; font:bold"  align="left"><b>Descripcion del Componente:</b></td>
    <td width="377" class="td_detalle" align="left"><textarea name="descripcion" style="text-align:left" class="small"  id="descripcion"  cols="60"/>
      <?php echo trim($descripcion);?>
    </textarea></td>
  </tr>
  <tr>
    <td width="284" height="24"  align="left" style="background-color:#FFFFCC; font:bold"><b>Incidencia sobre total del Riesgo:</b></td>
   
   <input name="mainc" type="hidden" id="mainc" value="<?php echo $mainci;?>"/>
    <input name="meinc" type="hidden" id="meinc" value="<?php echo $meinci;?>"/>
     <input name="esminimo" type="hidden" id="esminimo" value="<?php echo $minimo;?>"/>
    <input name="esmaximo" type="hidden" id="esmaximo" value="<?php echo $maximo;?>"/>
     <input name="estopemin" type="hidden" id="estopemin" value="<?php echo $tope_minimo;?>"/>
    <input name="estopemax" type="hidden" id="estopemax" value="<?php echo $tope_maximo;?>"/>
    <td width="437" class="td_detalle"><input name="incidencia" id="incidencia"  type="text" style="text-align:right" value="<?php echo $incidencia;?>" onblur="if(this.value<estad.mainc.value || this.value>estad.meinc.value) {alert('Entre los valores '+estad.mainc.value+' y '+estad.meinc.value);this.value='<?php echo $incidencia;?>';};return false;"/>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "[Entre ".$mainci." y ".$meinci."]";?></td>
    <!--input name="incide" type="hidden" value="<?php// echo $id_base;?>" />
      <input name="descrip" type="hidden" value="<?php// echo $descrip;?>" />
  <input name="id_estado" type="hidden" value="<?php// echo $descrip;?>" />-->
  </tr>
  
  
  <tr>
  <td width="227" height="24"  align="left" style="background-color:#FFFFCC; font:bold"><b>Desde la Cantidad de Premios:</b></td>
  <td width="437" class="td_detalle"><input name="minimo" id="minimo" type="text" style="text-align:right"  value="<?php echo $minimo;?>" onblur="if(parseFloat(this.value)>parseFloat(estad.maximo.value) || parseFloat(this.value)<parseFloat(estad.esminimo.value)) {alert('Entre los valores '+parseFloat(estad.esminimo.value)+' y '+parseFloat(estad.maximo.value));this.value='<?php echo $minimo;?>';};return false;"/></td>
  </tr>
  <tr>
  <td width="227" height="24"  align="left" style="background-color:#FFFFCC; font:bold"><b>Hasta la Cantidad de Premios:</b></td>
  <td width="437" class="td_detalle"><input name="maximo" id="maximo" type="text" style="text-align:right"  value="<?php echo $maximo;?>" onblur="if(parseFloat(this.value)<parseFloat(estad.minimo.value) || parseFloat(this.value)>parseFloat(estad.esmaximo.value)) {alert('Entre los valores '+parseFloat(estad.minimo.value)+' y '+parseFloat(estad.esmaximo.value));this.value='<?php echo $maximo;?>';};return false;"/></td>
  </tr>
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="background-color:#FFFFCC; font:bold"><input name="Modificar" style="font-weight:bold" type="button" value="Modificar" onClick="ajax_get('contenido','alerta/procesar_modificar_detriesgo.php','fecha=<?php echo $fecha;?>&incidencia='+estad.incidencia.value+'&descripcion='+estad.descripcion.value+'&id=<?php echo $id;?>&voy=<?php echo $voy;?>&riesgo=<?php echo $riesgo;?>&abro=<?php echo $abro;?>&cuit=<?php echo $cuit;?>&otrocuit=<?php echo $otrocuit;?>&pesitos=<?php echo $pesitos;?>&minimo='+estad.minimo.value+'&maximo='+estad.maximo.value)"></td>
  </tr>
  <?php if(isset($_REQUEST['mensaje']))
	{?>
  <tr>
    <td  class="td_detalle" align="center" colspan="2" style="color:#FF0000"><?php echo strtoupper($_REQUEST['mensaje'])?></td>
  </tr>
  <?php }?>
</table>
<?php 

}
//echo $descripcion.'min'.$minimo.'max'.$maximo;
//die('entre');

?>