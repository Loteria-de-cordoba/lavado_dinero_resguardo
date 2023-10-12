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

$condicion_asiento="and a.nro_asiento =$asiento";
//$cuentas='665,642,611,482,483,719';

try {
	$consulta = $db->Execute("select a.total, a.concepto, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor 
                         from conta_new.asiento_cabecera a
                         where a.cod_area_vinculante is null
                         and a.fecha_valor = to_date('$fecha', 'dd/mm/yyyy')
                         and (upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%' )
						 $condicion_asiento
                         order by fecha_valor, a.total desc");
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
  </tr> <?php while ($row = $consulta->FetchNextObject($toupper=true)) { ?>
  <tr>
 
    <td align="center" class="td2"><strong><?php echo $row->FECHA_VALOR; ?>&nbsp;</strong></td>
    <td align="left" class="td2"><strong><?php echo utf8_encode($row->CONCEPTO); ?>&nbsp;</strong></td>
     <td align="center" class="td2"><strong><?php echo '$ '.number_format($row->TOTAL,2,',','.') ?></strong><strong>&nbsp;</strong></td>
  </tr>   <?php }; ?>
</table>
<div align="left" class="td2"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('conformar','premio/mostrar_pre_conformacion.php','fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>')">Regresar</a></div>
</td>
