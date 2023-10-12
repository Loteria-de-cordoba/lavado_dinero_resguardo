<?php session_start();
include("../funcion.inc.php");
include ("../db_conecta_oracle_adodb.inc.php");
 //$db->debug = true; // si lo habilito muestra las transacciones en el navegador
##Con procedimiento tambien anda


$idpedido= $_GET['NROPEDIDO'];


 try {
	$db->Execute(" DELETE FROM utilidades.T_ARCHIVOS
      where id_archivos =? and id_tabla=? and tabla='T_SPP_DETALLE_PEDIDO' ",array($_GET['id'],$_GET['id_tabla']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	} 


header("location: ../pp/altapedprov.php?NROPEDIDO=$idpedido");
?>