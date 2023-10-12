<?php session_start(); 
include("../funcion.inc.php");
include_once("../db_conecta_adodb.inc.php");
include("../jscalendar-1.0/calendario.php");
// print_r($_GET); 
// $db->debug = true;
$fdesde=$_GET['fdesde'];
$fhasta=$_GET['fhasta'];

 
 try {$rsnota = $db->Execute("select nota
                              from PLA_AUDITORIA.t_ganador 
                              where id_ganador=?",array($_GET['ganador']));
                }
                catch (exception $e)
                {
                die ($db->ErrorMsg()); 
            }
 
 $row=$rsnota->FetchNextObject($toupper=true);
 
 ?>
 
<link href="../estilo/pedidos.css" rel="stylesheet" type="text/css">

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<form ID="FORMULARIONOTA" name="FORMULARIONOTA" method="post" action="#" onsubmit="validar_nota('contenido','premio/procesar_grabar_nota.php',this); return false;">
  <table width="34%" height="155" border="0" align="center">

<tr>
	<td height="26" align="center" class="textoAzulOscuroFondo" colspan="3">Nueva Nota</td>
</tr>
<tr>
	<td height="87" colspan="3"><textarea name="nota" rows="5"  cols="50" class="textbox" id="nota"><?php echo $row->NOTA ?>  </textarea></tr>
<tr>
    <td width="110" height="28" align="center"  class="td"><div align="left" >
<a href="#" onclick="ajax_get('contenido','premio/mostrar_pre_conformacion.php','fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>');"><img src="image/regresar.png" border="0"  /></a>
<a href="#" onclick="ajax_get('contenido','premio/mostrar_pre_conformacion.php','fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>');">Regresar</a>
</div></td>
<td width="111" height="28" align="center" class="td"><input name="Aceptar" align="center" type="submit" class="smallTahomaRojo" id="Aceptar" value="Aceptar" /></td>
<td width="88" height="28"  align="center" class="td"><input type="hidden" name="idganador" id="idganador" value="<?php echo $_GET['ganador'] ?>" />
      <input type="hidden" name="fdesde" id="fdesde" value="<?php echo $_GET['fdesde'] ?>" />
      <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta'] ?>" /></td>
    </tr>
</table>

</form>



