<?php //print_r($_REQUEST);
include_once("../db_conecta_adodb.inc.php");
$idid=$_REQUEST['id_id'];
$fecha=$_REQUEST['fecha'];

//Obtengo novedad final
try {
			$rs_datos = $db ->Execute("SELECT 	us.novedad_final as novedad
					 FROM lavado_dinero.informado_uif US
					WHERE us.id_informado=?
					", array($idid));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_datos =$rs_datos->FetchNextObject($toupper=true);
			$observa_final=$row_datos->NOVEDAD;

?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<form id="eli<?php echo $idid;?>" name="eli<?php echo $idid;?>" action="#" onsubmit="ajax_post('contenido','denegado/informado_eliminar_grabar.php',this); return false;">
<table width="50%"  align="center">
<tr>
	<td colspan="2" align="center" valign="bottom" class="texto4" scope="col">Elimina este Cliente a Informar</td>
</tr>
<?php
	if(!isset($_REQUEST['volver']))
	{?>
  <tr valign="bottom" class="td8" >
	  <!--<td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','informado/informado_eliminar_grabar.php','idid=<?php// echo $_GET['idid'];?>&fecha=<?php// echo $fecha;?>'); return false;"></td> -->
      <td colspan="2" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" style="font:bold;" id="acepta" type="button" value="Aceptar" onClick="ajax_get('dive_<?php echo $idid;?>','denegado/controla_eliminacion_informado.php','id_id=<?php echo $idid;?>&fecha=<?php echo $fecha;?>&volver='+this.value); return false;"></td>      
      <!--<td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ampliarImagen(); ajax_get('elimina','cliente/blanco.php',''); return false;"></td>     -->
    </tr>
  <?php }
  else
  	{?>  
    <tr>
      <td width="30%" class="td2"     scope="col">Observaci&oacute;n Final:</td>
      <td width="70%"><textarea name="observafinal<?php echo $idid;?>" class="small"  id="observafinal<?php echo $idid;?>" rows="1" cols="40"/><?php echo $observa_final;?></textarea></td>
    </tr>
    <tr>
        <td colspan="2" align="center" style="text-align:center; background-color:#CCCCCC">
     <input name="buttonx" class="smallTahoma" id="buttonx" style="font-size:11px;color:#333333;font:bold" value="Eliminar" type="button" onClick="ajax_get('contenido','denegado/informado_eliminar_grabar.php','id_id=<?php echo $idid;?>&observafinal='+eli<?php echo $idid;?>.observafinal<?php echo $idid;?>.value); return false;"></td>
    <?php }?>
</table>
</form>