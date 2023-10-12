<?php session_start();?>
<?php include($_SESSION['conexionBase']);?>
<?php 
try {
	$db->Execute("insert into superusuario.sistemas (id_sistema, descripcion, esquema, url) 
												 (select nvl(max(id_sistema),0)+1, ?, ?, ? from superusuario.sistemas)",
	array(strtoupper($_POST['descripcion']),strtoupper($_POST['esquema']),$_POST['url']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
header ("location: adm_sistemas.php");
?>