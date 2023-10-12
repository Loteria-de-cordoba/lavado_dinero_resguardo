<?php session_start();
include_once ("../db_conecta_adodb.inc.php");
include_once ("../funcion.inc.php");

//print_r($_GET);
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />


<table width="79%" border="0" cellpadding="0" cellspacing="0">
  <tr >
    
    <td colspan="5" class="smallVerde"><?php echo $politico; ?>
      <select name="politico"  id="politico" class="small" onChange="if (politico.value=='SI'){
                        	ajax_get('persona','premio/politico_expuesto.php',''); return false; 
                            } else {
                            	ajax_get('persona','blanco.php',''); return false;
                                }">
        <option value="SELECCIONE" <?php if($row->POLITICO=="Seleccione") echo 'selected=selected;'?> >Seleccione</option>
        <option value="NO" <?php if($row->POLITICO=="NO") echo 'selected=selected;'?> >NO</option>
        <option value="SI" <?php if($row->POLITICO=="SI") echo 'selected=selected;'?> >SI</option>
        </select>
    </td>
    <?php if ($row->POLITICO=="SI"){?>
	<td width="72%" align="right" colspan="5" ><?php include('politico_expuesto.php')?></td>	
		<?php  } else {?>
    
    <td width="72%" align="right" colspan="5" ><div id="persona"></div></td> <?php }?>
  </tr>
  
</table>