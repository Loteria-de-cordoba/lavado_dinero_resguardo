<?php session_start(); 

$archivo= "xml_temp"."/".$_GET['archivo'];
$archivo_destino= $_GET['archivo'];

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Description: File Transfer"); 
header("Content-Type: application/zip");
header("Content-Type: application/force-download"); 
header("Content-Disposition: attachment; filename=".$archivo_destino.";" );
header("Content-Transfer-Encoding: binary");
//header("Content-Length: ".filesize($archivo_destino));
readfile("$archivo");
/*$fp=fopen("$archivo", "r");
fpassthru($fp);
*/

?>