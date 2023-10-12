<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
ComenzarTransaccion($db);
print_r($_POST);// die();
$db->debug=true;
$suc_ban=$_SESSION['suc_ban'];
$condicion_suc=" and suc_ban='$suc_ban'";
$fdesde=substr($_POST['fechadesde'],0,10);
$fhasta=substr($_POST['fechahasta'],0,10);
try {
	$db->Execute("update PLA_AUDITORIA.t_ganador 
				  set conformado='1', fecha_conforma=sysdate, usuario_conforma=?
				  where fecha_alta>=to_date(?,'dd/mm/yyyy') 
				  and fecha_alta<=to_date(?,'dd/mm/yyyy')
				  and id_ganador=?   
				  $condicion_suc",  array('DU'.$_SESSION['usuario'],$fdesde,$fhasta));
	}
	
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
	
/*try {
	$db->Execute("update casino.t_reg_cp 
				  set conformado_uif=1
				  where fecha BETWEEN to_date(?,'dd/mm/yyyy') AND to_date(?,'dd/mm/yyyy') 
				  AND importe_plata>=10000",
				  array($fdesde,$fhasta));
	}
	
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
	*/
	
	
	/*
	try {
	$row=$db->Execute("select to_char(fecha_conforma,'dd/mm/yyyy') as fecha_conforma from PLA_AUDITORIA.t_ganador 
				  		where to_char(fecha_conforma,'dd/mm/yyyy')=to_char(sysdate,'dd/mm/yyyy')
						and usuario_conforma=?
						$condicion_suc",
				  		array('DU'.$_SESSION['usuario']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		
	$consulta = $row->FetchNextObject($toupper=true);
	$fdesde=$consulta->FECHA_CONFORMA;
	$fhasta=$consulta->FECHA_CONFORMA;
	
	
	FinalizarTransaccion($db); 
	$conformado=1;*/
header ("location:premios_conformados.php?fecha=$fdesde&fhasta=$fhasta");?>