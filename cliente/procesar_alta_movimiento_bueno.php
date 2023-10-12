<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();

$fecha = date("d/m/Y");
//print_r($_POST);
//die();

$db->debug=true;
//$novedad=$_POST['novedad'];
$fecha=$_POST['fecha_pago'];
if(isset($_POST['apostador1']) && $_POST['apostador1']<>0)
{
$apostador=$_POST['apostador1'];
}
else
{
	$apostador=$_POST['apostador'];
}
//echo $apostador;
//die();
$casino=$_POST['casino'];
if(isset($_POST['cheque_nro']) && $_POST['cheque_nro']<>'')
{
	$cheque_nro=$_POST['cheque_nro'];
}
else
{
	$cheque_nro=NULL;
}
$monto=$_POST['valor_premio'];
//nuevos campos
$fic_ing=$_POST['mficing'];
$fic_ret=$_POST['mficret'];
$observacion=$_POST['observamov'];
$monper=$_POST['monper'];

if($monto<>0)
{
ComenzarTransaccion($db);
try {
	$rs = $db->Execute("select lavado_dinero.SEQ_T_NOVEDAD.nextval as secuencia from dual");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
$row = $rs->FetchNextObject($toupper=true);
$secuencia = $row->SECUENCIA;

if($_POST['sexo']=='Fichaje')
{
try {
	$db->Execute("insert into lavado_dinero.t_novedades_cliente (
													   id_novedad, id_cliente, fecha_novedad,
													   fichaje, acierto, usuario, cheque_nro,
													   id_casino_novedad,
													   mon_ing_fic,
													   mon_fic_ret,
													   mon_perdido,
													   observa_mov
														)							
													   
				  values (?,?,to_date(?,'DD/MM/YYYY'),?,?,?,?,?,?,?,?,?)",
				  array($secuencia,
						 $apostador,
						 $fecha,
						 $monto,
						 NULL,
						 'DU'.$_SESSION['usuario'],
						 NULL,
						 $casino,
						 $fic_ing,
						$fic_ret,
						$monper,
						$observacion
							
						 ));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
}
else
{
try {
		$db->Execute("insert into lavado_dinero.t_novedades_cliente (
													   id_novedad, id_cliente, fecha_novedad,
													   fichaje, acierto, usuario, cheque_nro,
													   id_casino_novedad,
													     mon_ing_fic,
													   mon_fic_ret,
													   mon_perdido,
													   observa_mov
														)							
													   
				  values (?,?,to_date(?,'DD/MM/YYYY'),?,?,?,?,?,?,?,?,?)",
				  array($secuencia,
						 $apostador,
						 $fecha,
						 NULL,
						 $monto,
						 'DU'.$_SESSION['usuario'],
						 $cheque_nro,
						 $casino,
						 $fic_ing,
						$fic_ret,
						$monper,
						$observacion));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
}


//die('ESTA OPERACION NO SE GRABO... INTENTELO ');*/
FinalizarTransaccion($db);
}
//die('Proceso hecho');
//$casino=$_POST['casino'];
//$apostador=$_POST['apostador'];
//$fhasta=$_POST['fhasta'];
header ("location:adm_novedad.php?casino=$casino&apostador=$apostador");
?>