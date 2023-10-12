<?php
 	session_start();
	//echo session_id();
		include("../jscalendar-1.0/calendario.php");
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	$fecha = date("d/m/Y");
	try {
			$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)");
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 	
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','premio/procesar_conformar.php',this); return false;">
  <table width="75%" border="1" align="center" cellspacing="0">
    <tr align="left">
      <td align="center" colspan="4"  scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/premios_conformados.php','')">Regresar</a></div></td>
    </tr>
    <tr> <td>
      <table width="100%" border="0" align="center" cellspacing="0">
         <tr>
            <td width="18%" class="td2" scope="col"><div align="center"><img src="image/s_okay.png"  width="16" height="16" /></div></td>
                <td  colspan="3" class="td2" scope="col" align="left"><div align="center"><a href="#"> CONFORMAR PREMIOS</a></div>
                </div></td>
        </tr>
        <tr>
          <td lign="right" class="texto5" scope="row"><div align="right">Delegación</div></td>
     
          <td  colspan="3" valign="middle" class="texto5" scope="col"><?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>
        </tr>
   	  <tr>
         <td align="right" class="texto5" scope="row"><div align="right">Fecha desde</div></td>
        <td width="24%"    valign="middle" class="texto5" scope="col"><?php  abrir_calendario('fechadesde','premio', $fecha); ?>&nbsp;</td>
        <td width="11%"  align="right" class="texto5" scope="row"><div align="right">Fecha hasta:</div></td>
        <td width="47%" valign="middle" class="texto5" scope="col"><?php  abrir_calendario('fechahasta','premio', $fecha); ?>&nbsp;</td>
   	 </tr>
 
        <tr align="left">
          <td align="center" colspan="4"   scope="row">&nbsp;</td>
        </tr>
        <tr align="left">
          <td align="center" colspan="4"  scope="row"><input name="button" type="submit" class="textoAzulOscuro" id="button" value="Conformar" /></td>
        </tr>
    
</table></td></tr></table>
</form>