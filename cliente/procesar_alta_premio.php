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

try {
	$rs_consulta = $db->Execute("select id_cliente as cliente, id_casino as casino
									from PLA_AUDITORIA.t_cliente
									where trim(lower(decode(nombre,null,apellido,apellido || ' ' || nombre)))=trim(lower(?))
									or (documento=? and id_tipo_documento=?)
									",array($_POST['apellido'], $_POST['documento'], $_POST['id_tipo_documento']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}

$row=$rs_consulta->FetchNextObject($toupper=true);
if($rs_consulta->RecordCount()<>0)
	{
	$cliente=$row->CLIENTE;
	$casinoxx=$row->CASINO;
	}
	//echo $cliente;
	//die();
if($cliente=='')
{



		if($_POST['apellido']<>'')
		{
		ComenzarTransaccion($db);
		try {
			$rs = $db->Execute("select PLA_AUDITORIA.SEQ_T_CLIENTE.nextval as secuencia from dual");
			}
			catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
		$row = $rs->FetchNextObject($toupper=true);
		$secuencia = $row->SECUENCIA;
		
		try {
			$db->Execute("insert into PLA_AUDITORIA.t_cliente (
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
								NULL,
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
			//$nombre=$_POST['nombre'];
			$apellido=$_POST['apellido'];
			$documento=$_POST['documento'];
			$mensaje='Debe Registrar como minimo Apellido y Nombre o Apodo del Apostador';
			header ("location:agregar_premio.php?casino=$casino&mensaje=$mensaje&apellido=$apellido&documento=$documento&fecha_inicio=$fecha&fhasta=$fhasta");
		}
	}
else
{
	//$nombre=$_POST['nombre'];
	$apellido=$_POST['apellido'];
	$documento=$_POST['documento'];
	$mensaje='Apelido y Nombre - Apodo o Documento REPETIDO - DEBE CAMBIARLO!!!';
	header ("location:agregar_premio.php?casino=$casino&mensaje=$mensaje&apellido=$apellido&documento=$documento&fecha_inicio=$fecha&fhasta=$fhasta");
}
//die('Proceso hecho');


?>