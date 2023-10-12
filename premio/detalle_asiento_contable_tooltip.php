<?php session_start(); 
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
	$consulta = $db->Execute("select to_char(a.fecha_valor,'dd/mm/yyyy') as fecha, b.debe, b.haber, c.descripcion, e.nombre
								from conta_new.asiento_cabecera a, conta_new.asiento_detalle b, conta_new.modelo c, adm.area d, adm.sucursal e
								where a.cod_area_vinculante is null
								and a.cod_area = d.cod_area
								and d.suc_ban = e.suc_ban
								and a.nro_asiento = b.nro_asiento
							   and  a.cod_modelo = c.cod_modelo
								and a.fecha_valor = to_date('$fecha', 'dd/mm/yyyy')
								and (upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%' )
								$condicion_asiento
								order by fecha_valor, a.total desc
								");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
 

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<table width="670" border="1">
  <tr>
    <td width="78" align="center" class="textoAzulOscuroFondo">Fecha</div></td>
    <td width="111" align="center" class="textoAzulOscuroFondo">Area</td>
    <td width="231" align="center" class="textoAzulOscuroFondo">Modelo</td>
    <td width="113" align="center" class="textoAzulOscuroFondo">Debe</td>
    <td width="103" align="center" class="textoAzulOscuroFondo">Haber</td>
    
  </tr> <?php while ($row = $consulta->FetchNextObject($toupper=true)) { ?>
  <tr>
 
    <td align="center" class="td2"><strong><?php echo $row->FECHA; ?>&nbsp;</strong></td>
    <td align="left" class="td2"><strong><?php echo $row->NOMBRE; ?>&nbsp;</strong></td>
    <td align="left" class="td2"><strong><?php echo $row->DESCRIPCION; ?>&nbsp;</strong></td>
    <td align="right" class="td2"><strong><?php echo '$ '.number_format($row->DEBE,2,',','.'); ?>&nbsp;</strong></td>
     <td align="right" class="td2"><strong><?php echo '$ '.number_format($row->HABER,2,',','.') ?></strong><strong>&nbsp;</strong></td>
  </tr>   <?php }; ?>
</table>
<div align="left" class="td2"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('conformar','premio/mostrar_pre_conformacion.php','fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>')">Regresar</a></div>
</td>
