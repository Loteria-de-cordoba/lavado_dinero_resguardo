<?php 
session_start(); 
include("../db_conecta_adodb.inc.php");
//include ("../funcion.inc.php");
// $db->debug=true;
//print_r($_GET);

try {
	$tuhermana = $db->Execute("select c.descripcion as cuenta , b.debe, b.haber
                         from conta_new.asiento_cabecera a, conta_new.asiento_detalle b, conta_new.cuenta c
                         where a.nro_asiento = b.nro_asiento
						 and a.nro_asiento = ?
                         and b.cod_cuenta = c.cod_cuenta",array($_GET['asiento']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
 

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<table border="0" cellspacing="1">
  <tr class="smallRojo2">
    <td align="center">Cuenta</td>
    <td align="center">Debe</td>
    <td align="center">Haber</td>
  </tr> <?php while ($row = $tuhermana->FetchNextObject($toupper=true)) { ?>
  <tr class="td2">
    <td width="500" align="left" ><?php echo $row->CUENTA; ?>&nbsp;</td>
     <td width="100" align="right" ><?php echo '$ '.number_format($row->DEBE,2,',','.') ?></td>
     <td width="100" align="right"><?php echo '$ '.number_format($row->HABER,2,',','.') ?></td>
  </tr>   
  <?php }; ?>
</table>
</td>
