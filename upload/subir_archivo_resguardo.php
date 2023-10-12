<?php 
session_start();
include("../../ftp.inc.php");		
include("../funcion.inc.php");
include("../db_conecta_adodb.inc.php");
//print_r($_GET).'get';
//print_r($_POST).'post';
/* PRODUCCION: ConectarSSH_DB()*/
/* DESA: ConectarSSH_DB_DESA()*/
$id_ftp=ConectarSSH_DB_DESA(); 
/* PRODUCCION: '/directorios/util_archivos_bin/'*/
/* DESA: '/util_archivos_bin/'*/
$directorio = '/util_archivos_bin/';


$ftmp = $_FILES['adjunto']['tmp_name'];
$fname = $_FILES['adjunto']['name'];
//echo $ftmp;
//echo $fname;
if (!is_uploaded_file($ftmp)) {die('El archivo no pudo ser subido al servidor');}
move_uploaded_file($ftmp,$fname);

try {$rsid=$db->Execute("select lpad((PLA_AUDITORIA.seq_archivos_PLA.nextval),8,'0') as secuencia from dual");} 
		catch  (exception $e) 
	{ die($db->ErrorMsg());}
$rowid=$rsid->FetchNextObject($toupper=true);
$secuencia=$rowid->SECUENCIA;

$extensionarchivo=explode(".",$_FILES['adjunto']['name']);
$archivo_recurso = fopen($_FILES['adjunto']['name'], 'rb');


if ($extensionarchivo[1]=="txt") {
$formato  = FTP_ASCII;
} else 
{$formato  = FTP_BINARY;
}
$destino = $secuencia.'.'.$extensionarchivo[1];

if(!ssh2_scp_send($id_ftp, $_FILES['adjunto']['name'], $directorio.$destino, 0777)){
	unlink($archivo_recurso);
	die('Error enviando el archivo SSH');
}

try {
	$db->Execute("call PLA_AUDITORIA.SET_ARCHIVO_RESGUARDO(?,?,?,?,?,?,?,?)", array($_POST['id_tabla'], 
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
 
fclose($archivo_recurso);
unlink($_FILES['adjunto']['name']);


echo '                  Archivo cargado con Exito';


$id_ganador=$_POST['id_tabla'];
$imagen=1;