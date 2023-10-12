<?php session_start(); 
include("../funcion.inc.php");
include_once("../db_conecta_adodb.inc.php");
include("../jscalendar-1.0/calendario.php");
//print_r($_GET); 
// $db->debug = true;?>
 
<?php  
$fecha=$_GET['fdesde'];
$fhasta=$_GET['fhasta'];
$casa=$_GET['casa'];

//print_r($_GET);  
 
 try {$rsnota = $db->Execute("select observacion_anulado 
 							  from casino.t_reg_cp 
							  WHERE  observacion_anulado IS NOT NULL and anulado='S' and cod_mov_caja=?", array($_GET['cod_mov_caja']));
                }
                catch (exception $e)
                {
                die ($db->ErrorMsg()); 
            }
 
 $row=$rsnota->FetchNextObject($toupper=true);
 
 ?>
 
<link href="../estilo/pedidos.css" rel="stylesheet" type="text/css">

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<form ID="FORMULARIONOTA" name="FORMULARIONOTA" method="post" action="#" onsubmit=" ">


<table width="58%" height="155" border="1" align="center">

<tr>
	<td height="26" align="center" class="textoAzulOscuroFondo" style=""> Nota de Anulaci&oacute;n</td>
</tr>
<tr> 
	  <tr>
    <td width="559" height="87" colspan="2"><textarea name="anulado" rows="6"  cols="90" class="textbox" id="anulado"><?php echo $row->OBSERVACION_ANULADO?>  </textarea>    </tr>
<tr>
    <td height="28" colspan="3" class="td" align="center"><a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $casa  ?>');"><img src="image/regresar.png" border="0"  /></a>
      <a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $casa ?>');">Regresar</a>
      
      <input type="hidden" name="anulado" id="anulado" value="<?php echo $_GET['ANULADO'] ?>" />
  
</table>

</form>
