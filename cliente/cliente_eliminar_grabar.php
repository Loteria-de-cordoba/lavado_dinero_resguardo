<?php session_start(); 
include ("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//print_r($_GET);
//$db->debug=true;
//die();
//print_r($_POST);
//echo "<br><br><br>";
//die();
$array_fecha = FechaServer();
$usuario='DU'.$_SESSION['usuario'];	
$fechita = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					//Elimino registros del detalle
					try {$db->Execute("update PLA_AUDITORIA.t_cliente
						set fecha_baja=to_date(?,'dd/mm/yyyy'),
						USUARIO_BAJA=? 
						WHERE id_cliente= ?", array($fechita, $usuario,$_GET['id_cliente']));}
						catch(exception $e){die($db->ErrorMsg());} 	
FinalizarTransaccion($db);
//die('Finalizado');
$casino=$_GET['casino'];
$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];
header ("location:adm_cliente.php?casino=$casino&fecha=$fecha&fhasta=$fhasta");
?>