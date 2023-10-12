<?php session_start();
include("db_conecta_adodb.inc.php");
//print_r($_GET);// die();
ComenzarTransaccion($db);
// $fecha = date("d/m/Y");
//print_r($_POST); die();
//$db->debug=true;
/*
try {
	$rs = $db->Execute("select lavado_dinero.SEQ_GANADOR.nextval as secuencia from dual");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
$row = $rs->FetchNextObject($toupper=true);
$secuencia = $row->SECUENCIA;
*/

if($_GET['modifica']==1){
	$dato=1;
} elseif($_GET['modifica']==0) {
	$dato=0;
	}

try {
	$db->Execute("update  casino.t_reg_cp 
					set datos=$dato
					where id_cp =?",
				 array($_GET['registro']));
					
}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}	
	
$fecha=$_GET['fdesde'];
$fhasta=$_GET['fhasta'];	
$casa=$_GET['casa'];
//echo $casa;

FinalizarTransaccion($db);
header ("location:detalle_casino_cp.php?fecha=$fecha&fhasta=$fhasta&cod_casa=$casa");
?>
