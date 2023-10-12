<?php session_start();
include("../db_conecta_adodb.inc.php");


//$db->debug=true;

ComenzarTransaccion($db);

$i=0;
while ($i<$_SESSION['cantidadroles']){

$i=$i+1;


$ganador=$_GET['id_ganador'];

//die();
		try {
			$db->Execute("delete from PLA_AUDITORIA.ARCHIVOS_PLA
						  where esquema='lavado_dinero' 
						  and tabla='t_ganador'
						  and id_tabla=?
						  and id_archivos=?", array($_GET['id_ganador'],$_GET['id_archivo']));
			}
	
		catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
	
		

	FinalizarTransaccion($db); 
	

$fdesde=$_GET['fdesde']; 
$fhasta=$_GET['fhasta'];
$suc_ban=$_GET['suc_ban'];
$conformado=$_GET['conformado'];	
	
	

if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO') || ($_SESSION['rol'.$i]=='LAVADO_DINERO_CONFORMA_TODO')){ 
	header ("location:adm_premio.php?id_ganador=$ganador&fdesde=$fdesde&fhasta=$fhasta&suc_ban=$suc_ban&conformado=$conformado");
} 	else {
		if(!isset($_GET['condicion']))//viene de una imagen comun
		{
			header ("location:adm_premio_administra.php?id_ganador=$ganador&fdesde=$fdesde&fhasta=$fhasta&suc_ban=$suc_ban&conformado=$conformado");
		}
		else
		{
			header ("location:lista_planillas.php");
		}
	}
}
?>