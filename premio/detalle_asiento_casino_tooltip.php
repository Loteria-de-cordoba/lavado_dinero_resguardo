<?php 
session_start(); 
include("../db_conecta_adodb.inc.php");
//include ("../funcion.inc.php");
//$db->debug=true;
//print_r($_GET);
$asiento=$_GET['asiento'];
$fecha=$_GET['fecha'];
$fdesde=$_GET['fdesde'];
$fhasta=$_GET['fhasta'];

$condicion_asiento="and id_cp =$asiento";
//$cuentas='665,642,611,482,483,719';

try {
	$tuhermana = $db->Execute("SELECT importe_plata AS total, id_cp, to_char(fecha,'DD/MM/YYYY') as fecha
							  FROM casino.t_reg_cp
							  WHERE casa = upper(substr(?,8))
							  and conformado_uif=0
							  and id_cp=?
							  AND fecha BETWEEN to_date('$fdesde','DD/MM/YYYY HH24:MI') AND to_date('$fhasta','DD/MM/YYYY HH24:MI')
							  AND importe_plata>=10000",
							  array($_SESSION['area'], $asiento));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
 

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<table width="471" border="1">
  <tr>
    <td width="80" align="center" class="textoAzulOscuroFondo">Fecha</div></td>
    <td width="264" align="center" class="textoAzulOscuroFondo">Concepto</td>
    <td width="105" align="center" class="textoAzulOscuroFondo">Total</td>
  </tr> <?php while ($row = $tuhermana->FetchNextObject($toupper=true)) { ?>
  <tr>
 
    <td align="center" class="td2"><strong><?php echo $row->FECHA; ?>&nbsp;</strong></td>
    <td align="left" class="td2"><strong><?php echo utf8_encode($row->CONCEPTO); ?>&nbsp;</strong></td>
     <td align="center" class="td2"><strong><?php echo '$ '.number_format($row->TOTAL,2,',','.') ?></strong><strong>&nbsp;</strong></td>
  </tr>   <?php }; ?>
</table>
<div align="left" class="td2"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('conformar','premio/mostrar_pre_conformacion.php','fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>')">Regresar</a></div>
</td>
