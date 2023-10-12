<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />


<table width="771" border="1" cellpadding="0" cellspacing="0">
<tr>
	<td width="195" class="smallVerde" align="right">Cargo/Funcion/Jerarquia o Relacion</td>
    <td width="120" align="left"><input type="text" name="cargo2" id="cargo2" size="20" value="<?php echo $row->CARGO2; ?>" /></td>
    <td width="283" class="smallVerde" align="right">Autoridad de Emision del doc.</td>
    <td width="163" align="left"><input type="text" name="autoridad2" id="autoridad2" size="20" value="<?php echo $row->AUTORIDAD; ?>" /></td>
</tr>
<tr>
	<td width="195" class="smallVerde" align="right">Caracter Invocado</td>
    <td width="120" align="left"><select name="invocado2"  id="invocado2" class="small" >
       <option value="TITULAR" <?php if($row->INVOCADO2=="TITULAR") echo 'selected=selected;'?> >TITULAR</option>
        <option value="REP. LEGAL" <?php if($row->INVOCADO2=="REP. LEGAL") echo 'selected=selected;'?>  >REP. LEGAL</option>
         <option value="APODERADO" <?php if($row->INVOCADO2=="APODERADO") echo 'selected=selected;'?> >APODERADO</option>
        </select></td>
    <td width="283" class="smallVerde" align="right">Denominacion de la persona juridica cuando actua un apoderado o representante legal</td>
    <td width="163" align="left"><input type="text" name="denominacion_juridica2" id="denominacion_juridica2" size="20" value="<?php echo $row->DENOMINACION_JURIDICA2; ?>"/></td>
</tr>

</table>