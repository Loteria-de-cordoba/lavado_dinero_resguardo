<?php 
session_start();

//print_r($_GET).'get';
//print_r($_POST).'post';

/*
function ConectarFTP()
		{
		$servidor = "172.16.50.100";
		$puerto = 21;
		$timeout = 50;
		$user = "oracle10";
		$pass = "oracle10";
		//Obtiene un manejador del Servidor FTP
		$id_ftp=ftp_connect($servidor, $puerto, $timeout);
		//Se loguea al Servidor FTP
		ftp_login($id_ftp, $user, $pass); 
		//Devuelve el manejador a la funci�n
		return $id_ftp; 
		}*/
include("../../ftp.inc.php");		
		
include("../funcion.inc.php");
include("../db_conecta_adodb.inc.php");

$ftmp = $_FILES['adjunto']['tmp_name'];
$fname = $_FILES['adjunto']['name'];
if (!is_uploaded_file($ftmp)) {die('El archivo no pudo ser subido al servidor');}
move_uploaded_file($ftmp,$fname);

try {$rsid=$db->Execute("select lpad((utilidades.seq_archivos.nextval),8,'0') as secuencia from dual");} 
		catch  (exception $e) 
	{ die($db->ErrorMsg());}
$rowid=$rsid->FetchNextObject($toupper=true);
$secuencia=$rowid->SECUENCIA;

$extensionarchivo=explode(".",$_FILES['adjunto']['name']);
$id_ftp=ConectarFtpOracle(); 
ftp_chdir($id_ftp, "/util_archivos_bin");
$archivo_recurso = fopen($_FILES['adjunto']['name'], 'rb');
if ($extensionarchivo[1]=="txt") {
$formato  = FTP_ASCII;
} else 
{$formato  = FTP_BINARY;
}
$destino = $secuencia.'.'.$extensionarchivo[1];
if (ftp_fput($id_ftp, $destino , $archivo_recurso, $formato)) {
} else {
}
  
/* for ($i=1;$i<=$_POST['cantidad'];$i++)
{
if (isset($_POST["$i"]))
{*/
 

 try {
	$db->Execute("call utilidades.SET_ARCHIVO(?,?,?,?,?,?,?,?)", array($_POST['id_tabla'], 
                                         $_POST['esquema'],
                                         $_POST['tabla'],
                                         '/util_archivos_bin',
                                         $_FILES['adjunto']['name'],
                                         $secuencia,
                                         $extensionarchivo[1],
					                     'A'));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	} 
 // }
 //}
fclose($archivo_recurso);
unlink($_FILES['adjunto']['name']);

echo '                  Archivo cargado con Exito';

//if ($_SESSION['permiso']=='OPERADOR'||$_SESSION['permiso']=='OP_UNICO'){
$id_ganador=$_POST['id_tabla'];
$imagen=1;
//header("location:upload.php?id_ganador=$id_ganador&imagen=$imagen");
//}





 //header("location:../pp/mensaje_archivo_cargado.php.php?NROPEDIDO=$idpedido");
// echo "<script>window.location.href='ajax_get(\'contenido\',\'../pp/altapedprov.php\',\'NROPEDIDO=$idpedido\')';
 ?>