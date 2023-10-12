<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
$fecha = date("d/m/Y");
//print_r($_POST);
//die();

//$db->debug=true;
$casino=$_POST['casino'];
$fecha=$_POST['fecha_inicio'];
$fhasta=$_POST['fhasta'];

if ($_SESSION['suc_ban']==72){
	$sucursal=81;
} else {
	$sucursal=$_SESSION['suc_ban'];
}

//echo('ENTRA!!');

if($_POST['documento']<>'' && $_POST['apellido']<>'' && $_POST['nombre']<>'')
{
ComenzarTransaccion($db);
try {
	$rs = $db->Execute("select lavado_dinero.SEQ_T_CLIENTE.nextval as secuencia from dual");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
$row = $rs->FetchNextObject($toupper=true);
$secuencia = $row->SECUENCIA;

try {
	$db->Execute("insert into lavado_dinero.t_cliente (
													   id_tipo_documento,
													   documento,
													   apellido, 
													   nombre,
													    nacionalidad, 
														domicilio,
														profesion,
														cuit,
														sexo,
														estado_civil,
														telefono,
														email,
														fecha_alta,
														observacion,
														usuario,
														id_casino,
														id_cliente,
														COD_POSTAL, 
														localidad,
														provincia
														)							
													   
				  values (?,?,?,?,?,?,?,?,?,?,?,?,to_date(?,'DD/MM/YYYY'),?,?,?,?,?,?,?)",
				  array($_POST['id_tipo_documento'],
				  		$_POST['documento'],
						$_POST['apellido'],
						$_POST['nombre'],
						$_POST['nacionalidad'],
						$_POST['lugar_nacimiento'],
						$_POST['profesion'],
						$_POST['cuit'],
						$_POST['sexo'],
						$_POST['estado_civil'],
						$_POST['telefono'],
						$_POST['email'],
						$_POST['fecha'],
						$_POST['observacion'],
						'DU'.$_SESSION['usuario'],
						$_POST['casino'],
						$secuencia,
						$_POST['cod_postal'],
						$_POST['localidad'],
						$_POST['provincia_memo']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}


FinalizarTransaccion($db);
header ("location:adm_cliente.php?casino=$casino&fecha=$fecha&fhasta=$fhasta");
}
else
{
	$nombre=$_POST['nombre'];
	$apellido=$_POST['apellido'];
	$documento=$_POST['documento'];
	$mensaje='Debe Registrar como minimo Nombre, Apellido y Documento del Apostador';
	header ("location:agregar_premio.php?casino=$casino&mensaje=$mensaje&nombre=$nombre&apellido=$apellido&documento=$documento&fecha_inicio=$fecha&fhasta=$fhasta");
}
//die('Proceso hecho');


?>