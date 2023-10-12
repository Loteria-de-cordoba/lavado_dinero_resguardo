<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php
//print_r($_GET);
//echo 'cargo '.$row->CARGO.'autoridad '.$row->AUTORIDAD.' INVOCADO '.$row->INVOCADO.' juridica '.$row->DENOMINACION_JURIDICA;
//die();
		if(isset($row->CARGO) && $row->CARGO<>NULL)
		{
			$cargo3=$row->CARGO;
		}
		else
		{
			$cargo3='';
		} 
		if(isset($row->AUTORIDAD) && $row->AUTORIDAD<>NULL)
		{
			$autoridad3=$row->AUTORIDAD;
		} 
		else
		{
			$autoridad3='';
		} 
		if(isset($row->INVOCADO) && $row->INVOCADO<>NULL)
		{
			$invocado=$row->INVOCADO;
		} 
		else
		{
			$invocado='';
		} 
		if(isset($row->DENOMINACION_JURIDICA) && $row->DENOMINACION_JURIDICA<>NULL)
		{
			$denominacion3=$row->DENOMINACION_JURIDICA;
		}
		else
		{
			$denominacion3='';
		}  
?>
<table width="771" border="1" cellpadding="0" cellspacing="0">
<tr>
	<td width="195" class="smallVerde" align="right">Cargo/Funcion/Jerarquia o Relacion</td>
    <td width="120" align="left"><input type="text" name="cargo" id="cargo" size="20" value="<?php echo $cargo3; ?>" /></td>
    <td width="283" class="smallVerde" align="right">Autoridad de Emision del doc.</td>
    <td width="163" align="left"><input type="text" name="autoridad" id="autoridad" size="20" value="<?php echo $autoridad3; ?>"/></td>
</tr>
<tr>
	<td width="195" class="smallVerde" align="right">Caracter Invocado</td>
    <td width="120" align="left"><select name="invocado"  id="invocado" class="small" >
        <option value="TITULAR" <?php if($row->INVOCADO=="TITULAR") echo 'selected=selected;'?> >TITULAR</option>
        <option value="REP. LEGAL" <?php if($row->INVOCADO=="REP. LEGAL") echo 'selected=selected;'?>  >REP. LEGAL</option>
         <option value="APODERADO" <?php if($row->INVOCADO=="APODERADO") echo 'selected=selected;'?> >APODERADO</option>
        </select></td>
    <td width="283" class="smallVerde" align="right">Denominacion de la persona juridica cuando actua un apoderado o representante legal</td>
    <td width="163" align="left"><input type="text" name="denominacion_juridica" id="denominacion_juridica" size="20" value="<?php echo $denominacion3; ?>"/></td>
</tr>

</table>