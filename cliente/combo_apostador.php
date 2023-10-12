<?php session_start(); 
//include("db_conecta_adodb.inc.php");
include("../db_conecta_adodb.inc.php");
//include ("db_conecta_adodb.inc_produccion.php");
include_once ("../funcion.inc.php"); 

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
		$rs_apostador = $db -> Execute("select codigo, descripcion
										from(
													select initcap(apellido) || decode(nombre,'','',', ' || initcap(nombre)) as descripcion, max(id_cliente) as codigo
													from PLA_AUDITORIA.t_cliente
													where fecha_baja is null
													group by initcap(apellido) || decode(nombre,'','',', ' || initcap(nombre))
													--and id_casino=$casino
													order by descripcion)");				
		}
		catch (exception $e)
		{
		die ($db->ErrorMsg()); 
		}
 //if($casino<>100)
 //{
 armar_combo_todos($rs_apostador,'apostador','');
 /*}
 else
 {
 armar_combo($rs_apostador,'apostador','');
 }*/
	
?>
