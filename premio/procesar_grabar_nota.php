<?php session_start();
include("../db_conecta_adodb.inc.php");
//$carpeta = session_id();

//$db->debug=true;

ComenzarTransaccion($db);
//print_r($_POST); die();





//$fdesde=$_POST['fdesde'];
//$fhasta=$_POST['fhasta'];


//for ($i=1;$i<=$_POST['conta'];$i++)
//{
//echo ('Entra for'.$i.'<br>');
		//if (isset($_POST['conformadif'.$i]))
		//{

		try {
			$db->Execute("update PLA_AUDITORIA.t_ganador 
						  set  nota=?
						  where id_ganador=? ", array($_POST['nota'],$_POST['idganador']));
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
	}*/
	
	
	
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
	*/
	$fdesde=$_POST['fdesde'];
	$fhasta=$_POST['fhasta'];
	
	//die();
	FinalizarTransaccion($db); 
	
header ("location:mostrar_pre_conformacion.php?fdesde=$fdesde&fhasta=$fhasta");?>




