<?php session_start(); 
include("funcion.inc.php");
include_once("db_conecta_adodb.inc.php");
include("jscalendar-1.0/calendario.php");
// print_r($_GET); 
// $db->debug = true;?>
 
 <?php 
 
 try {$rsnota = $db->Execute("select nota
                              from casino.t_reg_cp 
                              where id_cp=?",array($_GET['registro']));
                }
                catch (exception $e)
                {
                die ($db->ErrorMsg()); 
            }
 
 $row=$rsnota->FetchNextObject($toupper=true);
 
 ?>
 
<link href="../estilo/pedidos.css" rel="stylesheet" type="text/css">

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
 <form ID="FORMULARIONOTA" name="FORMULARIONOTA" method="post" action="#" onsubmit="validar_nota('contenido','procesar_grabar_nota.php',this); return false;">


<table width="37%" height="155" border="1" align="center">

<tr>
	<td width="257" height="26" align="left" class="textoAzulOscuroFondo" style="">Nueva Nota</td>
</tr>
<tr> 
	  <tr><td height="87" colspan="3"><textarea name="nota" rows="5"  cols="40" class="textbox" id="nota"><?php echo $row->NOTA ?>  </textarea>
	</tr>
<tr>
    <td height="28" colspan="4" class="td" align="center"><input name="Aceptar" align="center" type="submit" class="smallTahomaRojo" id="Aceptar" value="Aceptar" />
    <input type="hidden" name="registro" id="registro" value="<?php echo $_GET['registro'] ?>" />
      <input type="hidden" name="fdesde" id="fdesde" value="<?php echo $_GET['fecha'] ?>" />
      <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta'] ?>" />
      <input type="hidden" name="casino" id="casino" value="<?php echo $_GET['cod_casa'] ?>" /></td>
    </tr>
</table>

</form>
<div align="left">
<a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fecha'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $_GET['cod_casa'] ?>');"><img src="image/regresar.png" border="0"  /></a>
<a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fecha'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $_GET['cod_casa'] ?>');">Regresar</a></div>


