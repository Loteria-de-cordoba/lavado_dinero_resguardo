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
//obtengo variables
$fecha=$_REQUEST['fecha'];
$primer=$_REQUEST['primedig'];
$docu=$_REQUEST['docu'];
$ultimo=$_REQUEST['ultdig'];
$apenom=$_REQUEST['apenom'];
$novedad=$_REQUEST['novedad'];
$cuit= substr($primer,0,2).substr($docu,0,8).substr($ultimo,0,1);
$sexo=$_REQUEST['sexo'];
$fecha_cedula=$_REQUEST['fecha_cedula'];
$fecha_vto=$_REQUEST['fecha_vto'];
 try {
 $rs_sexo = $db ->Execute("SELECT id_sexo as codigo, descripcion as descripcion 
 							FROM PLA_AUDITORIA.sexo	
							ORDER BY ID_SEXO				
					");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}

	$fecha=$_GET['fecha'];

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
<form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','denegado/procesar_alta_informado.php',this); return false;">
   <table width="70%" height="53"  border="2" align="center">
    <tr>
      <td align="center"  colspan="4" class="texto4_info"> ALTA DE CLIENTES A INFORMAR</td>
    </tr>
    <tr>
      <td     scope="col" class="td2_info">Apellido y Nombre :</td>
      <td width="285"><input type="text" name="apenom" id="apenom" style="width:248;" value="<?php echo $apenom;?>"/></td>
      <td width="246"   scope="col" class="td2_info">Nro. Documento:</td>
      <td width="278"><input type="text" name="docu" id="docu" onblur="if(this.value.length!=8) {var alerta='Solo ocho digitos '; alert(alerta);this.value='';return false;}" style="text-align:center; width:90; font-size:11px" value="<?php echo $docu;?>"/></td>
    </tr>
    <tr>
      <td width="241"    scope="col" class="td2_info">Observaci&oacute;n - Novedad:</td>
      <td width="285"><textarea name="novedad" class="small_info"  id="novedad"  rows="1" cols="42"/><?php echo $novedad;?></textarea></td>
      <td width="246"    scope="col"class="td2_info">Sexo:</td>
      <td width="278"><?php armar_combo($rs_sexo,'sexo',$sexo);?></td>
    </tr>
    <tr>
      <td colspan="3" align="center" style="text-align:center; background-color:#CCCCCC"><div style="text-align:center;margin-top:5px;margin-bottom:5px;">
          <input name="fecha" id="fecha" type="hidden" value="<?php echo $fecha;?>"/>
          <input name="fecha_novedad" id="fecha_novedad" type="hidden" value="<?php echo $fecha_novedad;?>"/>
          <input name="buttonx" class="small_infoTahoma" id="buttonx" style="font-size:11px;color:#333333;font:bold" value="Agregar" type="submit"/>
        </div></td>
      <td style="text-align:center; background-color:#CCCCCC"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cancelar" width="16" height="16" border="0" align="absbottom" /><a href="#"  onclick="ampliarImagen(); ajax_get('elimina','cliente/blanco.php','fecha=<?php echo $fecha ?>')">Cancelar</a></div></td>
    </tr>
  </table>
</form>
