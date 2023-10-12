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


	$fecha=$_GET['fecha'];
	$idid=$_REQUEST['idid'];
	
	
	
	//selecciono datos de este registro
try {
			$rs_datos = $db ->Execute("SELECT us.descripcion as apenom,
					 				us.funcion as novedad
					 FROM PLA_AUDITORIA.tipo_alerta US
					WHERE us.id_tipo_alerta=?
					", array($idid));
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
.style5 {font-size: 10px}
-->
  </style>
  <form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','alerta/procesar_modificar_tipo_alerta.php',this); return false;">
 	<?php //  if(!isset($_GET['cantidad']))
	//{?>
    <table width="62%" height="32" align="center" style="background-color:#999966">
<tr>
<td width="84%" align="left">Fecha Contable:<?php echo $fecha;?></td>
<td width="16%">
      <div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /><a href="#"  onclick="ajax_get('contenido','alerta/adm_tipo_alerta.php','fecha=<?php echo $fecha ?>')">Regresar</a></div></td>
</tr>
</table>



<table width="62%" height="53"  border="2" align="center">

<tr>
                    
                    <td align="center"  colspan="2"  style="background-color:#999966">MODIFICACION DE TIPOS DE ALERTAS</td>
    </tr>
      
    <tr>
     <td     scope="col" style="background-color:#999966">Descripcion:</td>
     <td width="331"><input type="text" name="apenom" id="apenom" style="width:400;" value="<?PHP echo $apenom;?>" /></td>
    </tr>
    <!--<tr>
      <td width="349"   scope="col" style="background-color:#999966">Cuit:</td>
      <td width="331"><input type="text" name="primedig" id="primedig" onblur="if(this.value.length!=2) {var alerta='Solo dos digitos '; alert(alerta);this.value='';return false;}"  style="width:20px;text-align:center;" />&nbsp;&nbsp;<input type="text" name="docu" id="docu" onblur="if(this.value.length!=8) {var alerta='Solo ocho digitos '; alert(alerta);this.value='';return false;}" style="text-align:center;"/>&nbsp;&nbsp;<input type="text" name="ultdig" id="ultdig" onblur="if(this.value.length!=1) {var alerta='Solo un digito '; alert(alerta);this.value='';return false;}"  style="width:20; text-align:center;"/></td>
    </tr>
    <tr >
      <td width="252"    scope="col" style="background-color:#999966">Sexo:</td>
      <td>&nbsp;</td>
    </tr>-->
    <tr>
      <td width="349"    scope="col" style="background-color:#999966">Funci&oacute;n:</td>
      <td width="331"><textarea name="novedad" class="small"  id="novedad"  rows="2" cols="120"/><?php echo $novedad;?></textarea></td>
      </tr>
      <tr>
        <td colspan="4" align="center" style="text-align:center; background-color:#999966"><div style="text-align:center;margin-top:25px;margin-bottom:15px;">
            <input name="fecha" id="fecha" type="hidden" value="<?php echo $fecha;?>"/>
            <input name="idid" id="idid" type="hidden" value="<?php echo $idid;?>"/>
             
          <input name="buttonx" class="smallTahoma" id="buttonx" style="font-size:11px;color:#333333;font:bold" value="Modificar" type="submit"/>
        </div></td></tr>
        </table>
  </form>
        
        