<?php //print_r($_GET);
session_start();
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
$casino=$_GET['casino'];
$fecha=$_GET['fecha'];
// obtengo datos del CASINO
try {
	$rs_apostador = $db->Execute("SELECT  N_CASINO as datos
									FROM CASINO.T_CASINOS
									WHERE ID_CASINO=?", array($casino));}
								catch (exception $e){die ($db->ErrorMsg());} 	
	
	
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$soydelcasino=$row_apostador->DATOS;
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<table width="45%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="texto4" scope="col">Apertura de Fecha&nbsp;<?php echo $fecha.'   '.$soydelcasino?></a></td>
</tr>
  <tr valign="bottom" class="td8" >
	  <td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','cliente/abrir_dia_casino.php','casino=<?php echo $casino;?>&fecha=<?php echo $fecha;?>'); return false;"></td>      
      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('abredia','cliente/blanco.php',''); return false;"></td>     
    </tr>
</table>