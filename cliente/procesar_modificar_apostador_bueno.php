<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
$fecha= date("d/m/Y");
//print_r($_POST);
//die();
$casino=$_POST['casino'];
$fecha=$_POST['fecha_inicio'];
$fhasta=$_POST['fhasta'];

//obtengo fecha
try {
$rs_fecha1 = $db ->Execute("select to_char(sysdate,'dd/mm/yyyy') as fecha1 from dual");}
											catch (exception $e){die ($db->ErrorMsg());} 
					$row_fecha1 =$rs_fecha1->FetchNextObject($toupper=true);
					if($rs_fecha1->RecordCount()<>0)
					{
					$fecha1=$row_fecha1->FECHA1;
					}
//$db->debug=true;
/*if($_POST['documento']<>'' && $_POST['apellido']<>'' && $_POST['nombre']<>'')
{*/
ComenzarTransaccion($db);
try {
	$db->Execute("update lavado_dinero.t_cliente
							   set id_tipo_documento=?,
							    documento=?,
							    apellido=?, 
							    nombre=?,
							    nacionalidad=?, 
								domicilio=?,
								 profesion=?,
								 cuit=?,
								 sexo=?,
								estado_civil=?,
								 telefono=?,
								 email=?,
								 fecha_modifica=to_date(?,'dd/mm/yyyy'),
								 observacion=?,
								 usuario_modifica=?,
								 id_casino=?,
								 COD_POSTAL=?, 
								 localidad=?,
								 provincia=?
						where id_cliente=?",array($_POST['id_tipo_documento'],
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
						$fecha1,
						$_POST['observacion'],
						'DU'.$_SESSION['usuario'],
						$_POST['casino'],
						$_POST['cod_postal'],
						$_POST['localidad'],
						$_POST['provincia'],
						$_POST['id_cliente']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
FinalizarTransaccion($db);
header ("location:adm_cliente.php?casino=$casino&fecha=$fecha&fhasta=$fhasta");

?>