<?php session_start(); 
//include("db_conecta_adodb.inc.php");
include_once ("db_conecta_adodb.inc.php");
//include ("db_conecta_adodb.inc_produccion.php");
include_once ("funcion.inc.php"); 

//$db->debug=True; 
//print_r($_GET);
//print_r($_POST);
$casino=$_GET['casino'];

 /*if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_suc_ban = "and b.suc_ban in ($suc_ban)";
	} else {
		$suc_ban = 0;
		$condicion_suc_ban = "";
		} */
 
 try {
		$rs_casino = $db -> Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino not in(2,13)
										union 
										select 100 as codigo,
										'No pertenece a Casino' as descripcion
										from dual
										--order by codigo desc");				
		}
		catch (exception $e)
		{
		die ($db->ErrorMsg()); 
		}
 armar_combo_todos($rs_casino,'casino',$casino);
	
?>
