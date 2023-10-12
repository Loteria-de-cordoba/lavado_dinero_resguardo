<?php session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	
	//print_r($_REQUEST);
	//die();
	//print_r($_SESSION);
	//$db->debug=true;
	//$array_fecha = FechaServer();
	//$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	//$fecha_desde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	//$fecha_hasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	$fecha=$_REQUEST['fecha'];
	$id_id=$_REQUEST['id_id'];
	//$fecha_cedula=$_REQUEST['fecha_cedula'];
	//$fecha_vto=$_REQUEST['fecha_vto'];
	//selecciono datos de este registro
try {
			$rs_datos = $db ->Execute("SELECT us.descripcion as apenom,
					 				us.novedad as novedad
					 FROM PLA_AUDITORIA.informado_uif US
					WHERE us.id_informado=?
					", array($id_id));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_datos =$rs_datos->FetchNextObject($toupper=true);
			$apenom=$row_datos->APENOM; 
			$novedad=$row_datos->NOVEDAD;
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	color: #006600;
	font-family: Arial, Helvetica, sans-serif;
}
.style5 {
	font-size: 10px
}
-->
</style>
<form id="modi<?php echo $id_id;?>" name="modi<?php echo $id_id;?>" action="#" onsubmit="ajax_post('contenido','denegado/procesar_modificar_informado.php',this); return false;">
  <table width="72%" height="53"  border="2" align="center">
    <tr>
      <td align="center"  colspan="4"  class="texto4_info">MODIFICACION DE CLIENTES A INFORMAR</td>
    </tr>
    <tr>
      <td width="238" class="td2_info"     scope="col">Apellido y Nombre:</td>
      <td width="173"><input type="text" name="apenom<?php echo $id_id;?>" id="apenom<?php echo $id_id;?>" style="width:110;" value="<?PHP echo $apenom;?>" /></td>
      <td width="194"    scope="col" class="td2_info">Observaci&oacute;n - Novedad:</td>
      <td width="313"><textarea name="novedad<?php echo $id_id;?>" class="small_info"  id="novedad<?php echo $id_id;?>"  rows="1" cols="42"/><?php echo $novedad;?></textarea></td>
    </tr>
    <tr>
      <td colspan="4" align="center" style="text-align:center; background-color:#CCCCCC"><div style="text-align:center;margin-top:5px;margin-bottom:5px;">
        <input name="fecha" id="fecha" type="hidden" value="<?php echo $fecha;?>"/>
        <input name="idid" id="idid" type="hidden" value="<?php echo $id_id;?>"/>
        <input name="buttonx" class="small_infoTahoma" id="buttonx" style="font-size:11px;color:#333333;font:bold" value="Modificar" type="button" onClick="ajax_get('contenido','denegado/procesar_modificar_informado.php','id_id=<?php echo $id_id;?>&fecha=<?php echo $fecha;?>&novedad='+modi<?php echo $id_id;?>.novedad<?php echo $id_id;?>.value+'&apenom='+modi<?php echo $id_id;?>.apenom<?php echo $id_id;?>.value); return false;"></td>
      <!--<td style="text-align:center; background-color:#CCCCCC"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cancelar" width="16" height="16" border="0" align="absbottom" /><a href="#"  onclick="ampliarImagen(); ajax_get('div2_'+<?php// echo $id_id;?>,'cliente/blanco.php','fecha=<?php// echo $fecha ?>')">Cancelar</a></div></td>-->
    </tr>
  </table>
</form>
