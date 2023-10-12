<?php session_start();
/* formulario de soporte a ESTADISTICO ANUAL ROS
* CANTIDAD DE REPORTES DE OPERACIONES SOSPECHOSAS
* 13/01/2014
* PARODI VICTOR
* 
*/
//echo "xget";
//print_r($_REQUEST);
//die();
//echo "xpost";
//print_r($_POST);
//print_r($_SESSION['permiso']);
//print $_SESSION['area'];
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
//$db->debug=true;
$ctrl=$_REQUEST['ff'];
try {
 				$rs_tope_mi = $db ->Execute("SELECT DECODE(COUNT(*),NULL,0,COUNT(*)) as cuenta
										FROM PLA_AUDITORIA.T_RIESGO_VALOR
										WHERE ? BETWEEN MINIMO AND MAXIMO
									",array($ctrl));
							}							
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
							}
				$row_tope_mi =$rs_tope_mi->FetchNextObject($toupper=true);
				$tope_minimo=$row_tope_mi->CUENTA;
if($tope_minimo==0)
{
?>
        <table border="2" id="tablita" name="tablita">
        <tr>
        <td><input name="oculto" id="oculto" type="hidden" value="1"/></td>
        <td style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF0000; display:block" id="celda" name="celda">Monto No Tabulado&nbsp;&nbsp;&nbsp;<input name="cambio" id="cambio" type="button" value="Inicializar Monto" onclick="llevar_a_cero('0');" /></td>
        </tr></table>
<?php }
else
{?>
	<input name="oculto" type="hidden" id="oculto" value="0"/>
<?php }?>

