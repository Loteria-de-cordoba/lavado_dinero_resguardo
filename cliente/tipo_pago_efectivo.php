<?php //session_start();
//include_once ("../db_conecta_adodb.inc.php");
//include_once ("../funcion.inc.php");
//print_r($_GET);
//print_r($_POST); 
//$mov='form1.tipomov.value';
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />


<table width="79%" border="0" cellpadding="0" cellspacing="0">
  <tr >
    <td colspan="2" class="smallVerde">
      <select name="id_tipo_pago"  id="id_tipo_pago" class="small">
        
        <option value="1" selected="selected" >Efectivo</option>
        <!--<option value="2"  >Cheque</option>
        <option value="3"  >Transferencia</option>
        <option value="4"  >En Especie</option>-->
      </select>
    </td>
    <td width="72%" align="right" colspan="5" ><div id="cheque"></div></td>
  </tr>
  
</table>