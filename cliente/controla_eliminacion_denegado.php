<?php //print_r($_GET);
$casino=$_GET['casino'];
$fecha=$_GET['fecha'];
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<table width="45%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="texto4" scope="col">Anula este Movimiento</a></td>
</tr>
  <tr valign="bottom" class="td8" >
	  <td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','cliente/casino_novedad_eliminar_grabar.php','id_id=<?php echo $_GET['id_id'];?>&casino=<?php echo $casino;?>&fecha=<?php echo $fecha;?>'); return false;"></td>      
      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('elimina','cliente/blanco.php',''); return false;"></td>     
    </tr>
</table>