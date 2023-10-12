<?php session_start();
include_once ("../db_conecta_adodb.inc.php");
include_once ("../funcion.inc.php");

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />


<table width="79%" border="0" cellpadding="0" cellspacing="0">
  <tr >
    
    
    <td colspan="5" class="smallVerde"><?php echo $id_tipo_pago; ?>
      <select name="id_tipo_pago"  id="id_tipo_pago" class="small" onChange="if (id_tipo_pago.value=='2'){
                        	ajax_get('cheque','premio/caja_texto_cheque.php',''); return false; 
                            } else {
                            	ajax_get('cheque','blanco.php',''); return false;
                                }">
        <option value="1" selected="selected" >Efectivo</option>
        <option value="2"  >Cheque</option>
        <option value="3"  >Transferencia</option>
        <option value="4"  >En Especie</option>
      </select>
    </td>
    <td width="72%" align="right" colspan="5" ><div id="cheque"></div></td>
  </tr>
  
</table>