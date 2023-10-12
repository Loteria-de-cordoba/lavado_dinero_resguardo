<?php session_start();
include("../db_conecta_adodb.inc.php");
//$carpeta = session_id();

//$db->debug=true;

ComenzarTransaccion($db);

		try {
			$db->Execute("update PLA_AUDITORIA.t_ganador 
						  set  nota_observacion=?
						  where id_ganador=? ", array($_POST['nota'],$_POST['idganador']));
			}
	
		catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
	
		$fecha=$_POST['fecha'];
	$fhasta=$_POST['fhasta'];
	$conformado=$_POST['conformado'];
	$suc_ban= $_POST['suc_ban']; 
	//die();
	FinalizarTransaccion($db); 
	
while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;
	
	
	
if (($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC')||($_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO')){
	
			header ("location:adm_premio.php?fecha=$fecha&fhasta=$fhasta&suc_ban=$suc_ban");
	}
    else {
	
			header ("location:adm_premio_administra.php?fecha=$fecha&fhasta=$fhasta&conformado=$conformado&suc_ban=$suc_ban");
	}
}	
	?>


