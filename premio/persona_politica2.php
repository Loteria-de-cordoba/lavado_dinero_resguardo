<?php session_start();
include_once ("../db_conecta_adodb.inc.php");
include_once ("../funcion.inc.php");

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />


<table width="79%" border="0" cellpadding="0" cellspacing="0">
  <tr >
      
    <td colspan="5" class="smallVerde"><?php echo $politico2; ?>
      <select name="politico2"  id="politico2" class="small" onChange="if (politico2.value=='SI'){
                        	ajax_get('persona2','premio/politico_expuesto2.php',''); return false; 
                            } else {
                            	ajax_get('persona2','blanco.php',''); return false;
                                }">
        <option value="NO" <?php if($row->POLITICO2=="NO") echo 'selected=selected;'?> >NO</option>
        <option value="SI" <?php if($row->POLITICO2=="SI") echo 'selected=selected;'?> >SI</option>
        </select>
    </td>
    <?php if ($row->POLITICO2=="SI"){?>
	<td width="72%" align="right" colspan="5" ><?php include('politico_expuesto2.php')?></td>	
		<?php  } else {?>
    
    <td width="72%" align="right" colspan="5" ><div id="persona2"></div></td> <?php }?>
  </tr>
  
</table>