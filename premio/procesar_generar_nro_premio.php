<?php session_start();
include("../db_conecta_adodb.inc.php");
//$carpeta = session_id();

//$db->debug=true;

ComenzarTransaccion($db);
//print_r($_GET); die();





//$fdesde=$_POST['fdesde'];
//$fhasta=$_POST['fhasta'];




		try {
			$db->execute( "call PLA_AUDITORIA.p_asignar_numeracion_ganadores(?)", array ($_GET['mes'] ));
			
			
			/*$db->Execute("update PLA_AUDITORIA.t_ganador 
						  set  observacion=?
						  where id_ganador=? ", array($_GET['observacion'],$_GET['id_ganador']));*/
			}
	
		catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
	
		//$db->execute( call p_asignar_numeracion_ganadores(?), array ($_POST['nro_premio'] ))



	
	//$suc_ban= $_GET['suc_ban']; 
	//echo $suc_ban;
	//die();
	FinalizarTransaccion($db); 
	
header ("location:elegir_mes.php");?>
