<?php session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_POST);
	//print_r($_GET);
	//die();
	//print_r($_SESSION);
	//$db->debug=true;
	//$array_fecha = FechaServer();
	//$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

	//$fecha_desde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	//$fecha_hasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
//armo combo sexo
//print_r($_REQUEST);
//obtengo variables
$fecha=$_REQUEST['fecha'];
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
<form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','alerta/procesar_alta_alerta.php',this); return false;">
  <table width="62%" height="32" align="center" style="background-color:#999966">
    <tr>
      <td width="84%" align="left">Fecha Contable:<?php echo $fecha;?></td>
      <td width="16%"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /><a href="#"  onclick="ajax_get('contenido','alerta/adm_tipo_alerta.php','fecha=<?php echo $fecha ?>')">Regresar</a></div></td>
    </tr>
  </table>
  <table width="62%" height="53"  border="2" align="center">
    <tr>
      <td align="center"  colspan="2"  style="background-color:#999966"> Agregar Tipo de ALERTAS</td>
    </tr>
    <tr>
      <td     scope="col" style="background-color:#999966">Descripcion :</td>
      <td width="331"><input type="text" name="apenom" id="apenom" style="width:212;" value="<?php echo $apenom;?>" /></td>
    </tr>
    <tr>
      <td width="349"    scope="col" style="background-color:#999966">Funci&oacute;n:</td>
      <td width="331"><textarea name="novedad" class="small"  id="novedad"  rows="2" cols="40"/><?php echo $novedad;?></textarea></td>
    </tr>
    <tr>
    <td colspan="4" align="center" style="text-align:center; background-color:#999966"><div style="text-align:center;margin-top:25px;margin-bottom:15px;">
          <input name="fecha" id="fecha" type="hidden" value="<?php echo $fecha;?>"/>
          <input name="buttonx" class="smallTahoma" id="buttonx" style="font-size:11px;color:#333333;font:bold" value="Agregar" type="submit"/>
        </div></td>
        
    </tr>
  </table>
</form>
