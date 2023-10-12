<?php //session_start();
include('adodb/adodb-exceptions.inc.php'); 
include('adodb/adodb.inc.php'); 
error_reporting(E_ERROR);
$db = NewADOConnection("oci8po"); //oracle 9.2 o superior
//$db = NewADOConnection("oci8"); //oracle 8i 0 9i
//$db = NewADOConnection("oracle"); //oracle 7
$rnum=rand(0,99999999);
//echo $_SESSION['usuario'];

/*
try {
	$db->Connect("(DESCRIPTION =
					(ADDRESS =
				(PROTOCOL = TCP)
					(HOST = 172.16.50.18)
					(PORT = 1521)
					(HASH = '.$rnum.')
				 )
			(CONNECT_DATA =(SERVER = DEDICATED)
      (SERVICE_NAME = test))
				 )",'superusuario', 'esquema');
	}
	catch  (exception $e) 
	{ 
	die("<a href=\"../cau/\">Su sesion en el sistema ha expirado, por favor vuelava a ingresar</a><br>".$db->ErrorMsg());
	}
*/
try {$db->Connect("(DESCRIPTION =
					(ADDRESS =
				(PROTOCOL = TCP)
					(HOST = nscentral-scan.loteriadecordoba.com.ar)
					(PORT = 1521)
					(HASH = '.$rnum.')
				 )
			(CONNECT_DATA =(SERVER = DEDICATED)
      (SERVICE_NAME = CENTRAL))
				 )", 'superusuario', 'esquema'); 
} catch  (exception $e) { 
	die($db->ErrorMsg()."<br><br><a href=\"../cau/index.php\">Regresar a pagina anterior.</a><br><br>");
}
	
	
	
//$db = NewADOConnection('mysql'); 
//$db->Connect("localhost"/*Server*/, "delicity"/*User*/, "delicity"/*PASS*/, "delicity"/*Base*/);# M'soft style data retrieval with binds 
//try {
//	$db->Execute("SET CHARACTER SET 'utf8'");
//	}
//	catch  (exception $e) 
//	{ 
//	die($db->ErrorMsg());
//	}
//$db->charSet = 'utf-8';//oracle
//$db->debug = true; // si lo habilito muestra las transacciones en el navegador
//$db->LogSQL(); // turn on logging (solo utilizarlo cuando el sistema esta estable porque no muestra los errores de mysql) // solo para mantenimiento
function ComenzarTransaccion($db) {
   $db->StartTrans();
}
function FinalizarTransaccion($db) {
   $db->CompleteTrans($autoComplete=true);
}
?>