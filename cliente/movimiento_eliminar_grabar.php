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
					try {$db->Execute("update PLA_AUDITORIA.t_novedades_cliente
						set USUARIO_BAJA=? 
						WHERE id_novedad= ?", array($usuario,$_GET['id_novedad']));}
						catch(exception $e){die($db->ErrorMsg());} 	
FinalizarTransaccion($db);
//die('Finalizado');
$apostador=$_GET['id_apostador'];
$casino=$_GET['casino'];
$fechita=$_GET['fecha'];
//$fhasta=$_GET['fhasta'];

header ("location:adm_novedad_explota.php?casino=$casino&fechita=$fechita&id_cliente=$apostador");
?>