<?php session_start();
//echo "xget";
//print_r($_GET);
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

if($_REQUEST['cuit']==0)
{
//echo $_REQUEST['cuit']."cuit";
?>
<input name="otrocuit" type="text" style="text-align:right; font:bold;" id="otrocuit"/><b>&nbsp;&nbsp;[Nuevo]</b>
<?php }
		else
		{
		
			$cuit=$_REQUEST['cuit'];
								
		}
?>
