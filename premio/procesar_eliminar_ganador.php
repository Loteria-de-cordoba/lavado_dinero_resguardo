<?php session_start();
include("../db_conecta_adodb.inc.php");


//$db->debug=true;

//print_r($_GET);
//die();

ComenzarTransaccion($db);


$usuario='DU'.$_SESSION['usuario'];
$fecha = date("d/m/Y");




		try {
			$db->Execute("update PLA_AUDITORIA.t_ganador 
						  set usuario_baja=?, fecha_baja=to_date(?,'DD/MM/YYYY')
						  where id_ganador=? ", array($usuario,$fecha,$_GET['id_ganador']));
			}
	
		catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
	
		
$fdesde=$_GET['fdesde'];
$fhasta=$_GET['fhasta'];
$conformado=$_GET['conformado'];
	

FinalizarTransaccion($db); 
	
header ("location:adm_premio_administra.php?fecha=$fdesde&fhasta=$fhasta&conformado=$conformado");?>




