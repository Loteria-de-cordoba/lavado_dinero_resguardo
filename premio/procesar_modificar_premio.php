<?php session_start();
include("../db_conecta_adodb.inc.php");
//print_r($_POST);
//print_r($_GET);
//echo "test";
//die();

if($_POST['valor_premio']>=50001)
{
$carpeta = session_id();
ComenzarTransaccion($db);
$fecha = date("d/m/Y");
//print_r($_POST['cod_localidad']); die();
//$db->debug=true;
/*
try {
	$rs = $db->Execute("select PLA_AUDITORIA.SEQ_GANADOR.nextval as secuencia from dual");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
$row = $rs->FetchNextObject($toupper=true);
$secuencia = $row->SECUENCIA;
*/

try {
	$db->Execute("update  PLA_AUDITORIA.t_ganador 
					set id_tipo_documento=?,
						documento=?,
						cuit=?,
						apellido=?, 
						nombre=?,
						nacionalidad=?,
						domicilio=?,
						valor_premio=?,
						juego=?,
						concepto=?,
						id_moneda=?,
						id_tipo_pago=?,
						domicilio_pago=?,
						cuenta_bancaria_salida=?,
						apellido2=?,
						nombre2=?,
						id_tipo_documento2=?,
						documento2=?,
						profesion=?,
						fecha_alta=to_date(?, 'dd/mm/yyyy'),
						id_localidad=?,
						cod_postal=?,
						cheque_nro=?,
						sorteo_nro=?,
						nro_ticket=?,
					   usuario_modifica=?
				  where id_ganador =?",
				 array($_POST['id_tipo_documento'],
				 $_POST['documento'],
				  $_POST['cuit'],				 
				 utf8_decode($_POST['apellido']),
				 utf8_decode($_POST['nombre']),
				 utf8_decode($_POST['nacionalidad']),
				 utf8_decode($_POST['domicilio']),
				 $_POST['valor_premio'],
				 $_POST['juego'],
				 utf8_decode($_POST['concepto']),
				 $_POST['id_moneda'],
				 $_POST['id_tipo_pago'],
				 utf8_decode($_POST['domicilio_pago']),
				 utf8_decode($_POST['cuenta_bancaria']),
				 utf8_decode($_POST['apellido2']),
				 utf8_decode($_POST['nombre2']),
				 $_POST['id_tipo_documento2'],
				 $_POST['documento2'],
				 utf8_decode($_POST['profesion']),
				 $_POST['fecha'],
				 $_POST['cod_localidad'],
				 $_POST['cod_postal'],
				 utf8_decode($_POST['cheque_nro']),
				 $_POST['sorteo_nro'],
				 $_POST['nro_ticket'],
				 'DU'.$_SESSION['usuario'],
				 $_POST['id_ganador']));
	
}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}	
	
	
	
	
	
	
	
/*	
	"insert into PLA_AUDITORIA.t_ganador (id_ganador, 
													   id_tipo_documento,
													   documento,
													   apellido, 
													   nombre, 
													   nacionalidad, 
													   domicilio, 
													   valor_premio, 
													   concepto, 
													   id_moneda, 
													   id_tipo_pago, 
													   domicilio_pago, 
													   cuenta_bancaria_salida, 
													   apellido2, 
													   nombre2, 
													   id_tipo_documento2, 
													   documento2, 
													   profesion,
													   suc_ban,
													   id_localidad,fecha) 
				  values (?,?,?,initcap(?),initcap(?),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,$fecha)",
				  array($secuencia,
				  		$_POST['id_tipo_documento'],
				  		$_POST['documento'],
						$_POST['apellido'],
						$_POST['nombre'],
						$_POST['nacionalidad'],
						$_POST['domicilio'],
						$_POST['valor_premio'],
						$_POST['concepto'],
						$_POST['id_moneda'],
						$_POST['id_tipo_pago'],
						$_POST['domicilio_pago'],
						$_POST['cuenta_bancaria'],
						$_POST['apellido2'],
						$_POST['nombre2'],
						$_POST['id_tipo_documento2'],
						$_POST['documento2'],
						$_POST['profesion'],
						$_SESSION['suc_ban'],
						$_POST['cod_localidad']));*/
	
/*	
function ConectarFTP()
		{
		$servidor = "172.16.0.100";
		$puerto = 21;
		$timeout = 50;
		$user = "oracle10";
		$pass = "oracle10";
		//Obtiene un manejador del Servidor FTP
		$id_ftp=ftp_connect($servidor, $puerto, $timeout);
		//Se loguea al Servidor FTP
		ftp_login($id_ftp, $user, $pass); 
		//Devuelve el manejador a la funci?n
		return $id_ftp; 
		}
		
$id_ftp=ConectarFTP(); 

$files=scandir("../upload_ajax/upload/$carpeta");
for ($i=0;$i<count($files);$i++) { 
	if ($files[$i]!='.' && $files[$i]!='..'){
		ftp_chdir($id_ftp, "/home/util_archivos_bin");
		$extensionarchivo=explode(".",$files[$i]);
		if ($extensionarchivo[1]=="txt") {
			$formato  = FTP_ASCII;
		} else {
			$formato  = FTP_BINARY;
		}
		try {$rsid=$db->Execute("select lpad((utilidades.seq_archivos.nextval),8,'0') as secuencia from dual");} 
		catch  (exception $e) 
		{ die($db->ErrorMsg());}
		$rowid=$rsid->FetchNextObject($toupper=true);
		$secuencia_utilidad=$rowid->SECUENCIA;
		$destino = $secuencia_utilidad.'.'.$extensionarchivo[1];
		$archivo = fopen("../upload_ajax/upload/".$carpeta."/".$files[$i], 'rb');
		//echo ('Archivo: '.$archivo.' - Extenxion: '.$extensionarchivo[1].' - Formato: '.$formato.' - Destino: '.$destino);
		if (ftp_fput($id_ftp, $destino, $archivo, $formato)) {} else {die('Error en el envio');}
		try {
			$db->Execute("call utilidades.set_archivo (?,?,?,?,?,?,?,?)",
					  array($secuencia,'PLA_AUDITORIA','t_ganador','/home/util_archivos_bin',$files[$i],$secuencia_utilidad,
					  		$extensionarchivo[1],'A'));
			}
			catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
	}
}

function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
}
	
deleteDirectory("../upload_ajax/upload/$carpeta");
*/
FinalizarTransaccion($db);
}

$fdesde=$_POST['fdesde'];
$fhasta=$_POST['fhasta'];
$conformado=$_POST['conformado'];
$casa=$_POST['casa'];



if ($_SESSION['permiso']=='ADMINISTRA') {
$_SESSION['bandera']=1;
header ("location:adm_premio_administra.php?fecha=$fdesde&fhasta=$fhasta&casa=$casa&conformado=$conformado");}
 else
	header ("location:adm_premio.php");
?>