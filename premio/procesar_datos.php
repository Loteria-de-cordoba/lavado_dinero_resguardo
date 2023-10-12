<?php session_start();
include("../db_conecta_adodb.inc.php");
//$carpeta = session_id();

//$db->debug=true;

ComenzarTransaccion($db);
//print_r($_GET); die();


		try {
			$db->Execute("update PLA_AUDITORIA.t_ganador 
						  set  observacion=?
						  where id_ganador=? ", array($_GET['observacion'],$_GET['id_ganador']));
			}
	
		catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
	
		
$fecha=$_GET['fdesde'];
$fhasta=$_GET['fhasta'];
$conformado=$_GET['conformado'];
$suc_ban= $_GET['suc_ban']; 

FinalizarTransaccion($db); 
 $_SESSION['bandera']=1;
$i=0;
while ($i<$_SESSION['cantidadroles']){

$i=$i+1;

	
//if ($_SESSION['permiso'] == 'ADM_CONFORMA' || $_SESSION['permiso'] == 'ADM_CASINO'){
if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA')||($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO')||($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC')||($_SESSION['rol'.$i]=='LAVADO_DINERO_CONFORMA_TODO')){
	header ("location:adm_premio.php?fecha=$fecha&fhasta=$fhasta&suc_ban=$suc_ban&conformado=$conformado");

}
	else {

		header ("location:adm_premio_administra.php?fecha=$fecha&fhasta=$fhasta&conformado=$conformado&suc_ban=$suc_ban");
	}
}
?>



