<?php 
session_start(); 
include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php");
$id=$_GET['id_archivo'];
//$db->debug = true; // si lo habilito muestra las transacciones en el navegador
 try {	$rs = $db->Execute("select archivo_blob, nombre_real
	from PLA_AUDITORIA.ARCHIVOS_PLA pd
	where id_archivos = ?",array($id));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
//$blob = $DB->BlobDecode( reset($rs->fields) ); 
$rowid=$rs->FetchNextObject($toupper=true);
$contenido = $rowid->ARCHIVO_BLOB;
$nombre = str_replace(' ','_',$rowid->NOMBRE_REAL);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Description: File Transfer"); 
header("Content-Type: application/force-download"); 
header("Content-Length: " . strlen($contenido)); 
header("Content-Disposition: attachment; filename=$nombre"); 
echo $contenido;
?> 