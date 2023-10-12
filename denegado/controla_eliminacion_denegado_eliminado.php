<?php //print_r($_GET);
$idid=$_GET['idid'];
$fecha=$_GET['fecha'];
$fecha_novedad=$_GET['fecha_novedad'];
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<table width="45%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="texto4" scope="col">Elimina Definitivamente esta Cedula</a></td>
</tr>
  <tr valign="bottom" class="td8" >
	  <!--<td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','denegado/denegado_eliminar_grabar.php','idid=<?php// echo $_GET['idid'];?>&fecha=<?php// echo $fecha;?>'); return false;"></td> -->
      <td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','denegado/denegado_eliminar_definitivo.php','idid=<?php echo $idid;?>&fecha=<?php echo $fecha;?>&fecha_novedad=<?php echo $fecha_novedad;?>'); return false;"></td>      
      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('elimina','cliente/blanco.php',''); return false;"></td>     
    </tr>
</table>