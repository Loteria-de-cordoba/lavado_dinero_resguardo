<?php session_start();
include("db_conecta_adodb.inc.php");
include ("funcion.inc.php");
$permiso=0;
try{$rs=$db->Execute ("select sysdate from dual");
	}
	catch  (exception $e) 
	{ 
	die(MensajeBase($db->ErrorMsg()));
	} 
	
?>