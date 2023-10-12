<?php session_start(); 
include ("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//print_r($_GET);
//$db->debug=true;
ComenzarTransaccion($db);
$apostador=$_GET['apostador'];
$casino=$_GET['casino'];
$fechita=$_GET['fechita'];
//$fhasta=$_GET['fechita'];
$condicion_casino="and no.id_casino_novedad='$casino'";
//echo $apostador.'....'.$casino.'...'.$fecha.'....'.$fhasta
//die();
if($_GET['apostador']==0)
{
	$condicion_apostador='';
}
else
{
	$condicion_apostador="and cc.id_cliente='$apostador'";
}
$condicion_fecha="and no.fecha_novedad = to_date('$fechita','dd/mm/yyyy')";
//$condicion_casino="and cc.id_casino='$casino'";
try {$recorrido=$db->Execute("select no.id_novedad as novedad
							from  PLA_AUDITORIA.t_novedades_cliente no,
							PLA_AUDITORIA.t_cliente cc
							where cc.id_cliente=no.id_cliente
								$condicion_fecha
								$condicion_casino
								$condicion_apostador
						");}
						catch(exception $e){die($db->ErrorMsg());}
//die();
while($recorre =$recorrido->FetchNextObject($toupper=true))
{
$novedad=$recorre->NOVEDAD;
//print_r($_POST);
//echo "<br><br><br>";
//die();
$array_fecha = FechaServer();
$usuario='DU'.$_SESSION['usuario'];	
//$fechita = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					//Elimino registros del detalle
					try {$db->Execute("update PLA_AUDITORIA.t_novedades_cliente
						set confirmado='S',
						usuario_conforma=? 
						WHERE id_novedad=?",array('DU'.$_SESSION['usuario'], $novedad));}
						catch(exception $e){die($db->ErrorMsg());} 
}	
FinalizarTransaccion($db);
//die('Finalizado');

header ("location:adm_novedad_explota.php?casino=$casino&fechita=$fechita&id_cliente=$apostador");
?>