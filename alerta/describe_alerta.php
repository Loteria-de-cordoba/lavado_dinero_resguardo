<?php /*
* formulario de DETALLE DE ALERTAS
* 16/09/2013
* PARODI VICTOR
* 
*/
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
//print_r($_REQUEST);
//die('entre');
//$db->debug=true;
$id_tipo=$_REQUEST['id_tipo'];

try {
 $rs_observaciones = $db ->Execute("SELECT DECODE(funcion,NULL,'Sin Descripcion',funcion) as observaciones
 							FROM PLA_AUDITORIA.tipo_alerta
							WHERE ID_TIPO_ALERTA=?	
					",array($id_tipo));
			}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
$row_observa =$rs_observaciones->FetchNextObject($toupper=true);
$observaciones=$row_observa->OBSERVACIONES;
?>
<table width="831"  border="1" align="center">
<tr>
  <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>DESCRIPCION DEL ALERTA  <?php echo $_REQUEST['alerta'];?></b></td>
</tr>
<tr>
  <td colspan="4" height="24"  align="center" style="background-color:#FFFF99; font:Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold"><p><?php echo $observaciones;?></p></td> 
</tr>
</table>