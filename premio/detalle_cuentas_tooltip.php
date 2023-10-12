<?php 
session_start(); 
include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php");
//$db->debug=true;
//print_r($_GET);
$asiento=$_GET['asiento'];
$fecha=$_GET['fecha'];
$fdesde=$_GET['fdesde'];
$fhasta=$_GET['fhasta'];

$condicion_asiento="and a.nro_asiento =$asiento";
//$cuentas='665,642,611,482,483,719';


if (isset($_GET['fdesde'])) {
				$fdesde = $_GET['fdesde'];
		} else {
			if (isset($_POST['fdesde'])) {
				$fdesde = $_POST['fdesde'];
			} else {
				$fdesde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
			}
		}

if (isset($_GET['fhasta'])) {
				$fhasta = $_GET['fhasta'];
		} else {
			if (isset($_POST['fhasta'])) {
				$fhasta = $_POST['fhasta'];
			} else {
				$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
			}
		}





try {
	$tuhermana = $db->Execute("  SELECT a.total, TO_CHAR(a.fecha_valor,'DD/MM/YYYY') AS fecha_valor, d.cod_cuenta, a.concepto
   								 FROM conta_new.asiento_cabecera a , conta_new.asiento_detalle d
                                 WHERE a.nro_asiento = d.nro_asiento AND a.nro_asiento_vinculante IS NULL
								 AND a.concepto LIKE '%UIF%'
								 and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                 ORDER BY a.fecha_valor, a.total desc");
	      
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
 

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<table width="326" border="1">
  <tr>
    <td width="71" align="center" class="textoAzulOscuroFondo">Fecha</div></td>
    <td width="109" align="center" class="textoAzulOscuroFondo"> Nro Cuenta </td>
    <td width="124" align="center" class="textoAzulOscuroFondo">Total</td>
  </tr> <?php while ($row = $tuhermana->FetchNextObject($toupper=true)) { ?>
  <tr>
 
    <td align="center" class="td2"><strong><?php echo $row->FECHA_VALOR; ?>&nbsp;</strong></td>
    <td align="left" class="td2"><strong><?php echo utf8_encode($row->COD_CUENTA); ?>&nbsp;</strong></td>
     <td align="left" class="td2"><strong><?php echo '$ '.number_format($row->TOTAL,2,',','.') ?></strong><strong>&nbsp;</strong></td>
  </tr>   <?php }; ?>
</table>
<div align="left" class="td2"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('conformar','premio/premios_loteria_contabilidad.php','fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>')">Regresar</a></div>
</td>
