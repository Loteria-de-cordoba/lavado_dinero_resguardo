<?php //print_r($_GET);?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<table width="45%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="texto4" scope="col">Elimina este Movimiento No Confirmado</a></td>
</tr>
  <tr valign="bottom" class="td8" >
	  <td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','cliente/movimiento_eliminar_grabar.php','id_novedad=<?php echo $_GET['id_novedad'];?>&id_apostador=<?php echo $_GET['apostador'];?>&casino=<?php echo $_GET['casino'];?>&fecha=<?php echo $_GET['fechita']; ?>&fhasta=<?php echo $_GET['fechita']; ?>'); return false;"></td>      
      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('elimina','cliente/blanco.php',''); return false;"></td>     
    </tr>
</table>