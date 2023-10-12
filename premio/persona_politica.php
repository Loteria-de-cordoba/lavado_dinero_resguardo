<?php session_start();
include_once ("../db_conecta_adodb.inc.php");
include_once ("../funcion.inc.php");

//print_r($_GET);
//echo $row->POLITICO;
//echo $_SESSION['xxx'];
//die();
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css"/>


<table width="79%" border="0" cellpadding="0" cellspacing="0">
 <!-- <?php //if($row->POLITICO=="SI") 
  //{?>
  <tr>
  		<td width="72%" align="right" colspan="10" style="text-decoration:underline" ><?php //echo "Haga clic en el combo para que se exhiban los detalles de Persona Politica";?></td>
  </tr>
  <?php //}?>-->
  <tr >
    
   <!--<td width="72%" align="right" colspan="5" ><?php //include('politico_expuesto.php')?></td>-->
		
    <td colspan="5" class="smallVerde">
      <select name="politico"  id="politico" class="small" onChange="if (politico.value=='SI'){
                        	ajax_get('persona','premio/politico_expuesto.php',''); return false; 
                            } else {
                            	ajax_get('persona','premio/blanco.php',''); return false;
                                }">
        <option value="SELECCIONE" <?php if($row->POLITICO=="Seleccione") echo 'selected=selected;'?> >Seleccione</option>
        <option value="NO" <?php if($row->POLITICO=="NO") echo 'selected=selected;'?> >NO</option>
        <option value="SI" <?php if($row->POLITICO=="SI") echo 'selected=selected;'?> >SI</option>
        </select>
    </td>	
    
   <!-- <td colspan="5" class="smallVerde">
   onfocus="if (politico.value=='SI'){
                        	ajax_get('persona','premio/politico_expuesto.php',''); return false; 
                            } else {
                            	ajax_get('persona','premio/blanco.php',''); return false;
                                }"
      <select name="politico"  id="politico" class="small" onChange="if (politico.value=='SI'){ <td width='72%' align='right' colspan='5'><div id='persona'><?php //include('politico_expuesto.php')?></div></td>
                        	} else {<td width='72%' align="right" colspan='5' ><div id='persona'><?php// include('blanco.php')?></div></td>}">
        <option value="SELECCIONE" <?php// if($row->POLITICO=="Seleccione") echo 'selected=selected;'?> >Seleccione</option>
        <option value="NO" <?php //if($row->POLITICO=="NO") echo 'selected=selected;'?> >NO</option>
        <option value="SI" <?php //if($row->POLITICO=="SI") echo 'selected=selected;'?> >SI</option>
        </select>
    </td-->
    <?php if($row->POLITICO=="SI" and $_SESSION['xxx']==0)
	{?>
     <td width="72%" align="right" colspan="5" ><div id="persona"><?php include('politico_expuesto.php')?></div></td>
     <?php
	 $_SESSION['xxx']=1;
	 }
	 else
	 {?>
    <td width="72%" align="right" colspan="5" ><div id="persona"></div></td>
    <?php }?> 
  </tr>
  
</table>