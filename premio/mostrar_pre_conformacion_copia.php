<?php 
session_start();
include("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//$db->debug=true;
//print_r($_GET);
//echo '<br>';
//print_r($_SESSION);
//echo '<br>LISTO<br>';
$fdesde=$_POST['fdesde'].' 00:00';
$fhasta=$_POST['fhasta'].' 23:59';


if (isset($_GET['fdesde'])) {
				$fdesde = $_GET['fdesde'];
			}
			else 
			{
			
				if (isset($_POST['fdesde'])) {
					$fdesde = $_POST['fdesde'];
				}
				 else {
					$fdesde = $_POST['fdesde'].' 00:00';
				}
			}
			
		if (isset($_GET['fhasta'])) {
				$fhasta = $_GET['fhasta'];
		} else {
			if (isset($_POST['fhasta'])) {
				$fhasta = $_POST['fhasta'];
			} else {
				$fhasta =$_POST['fhasta'].' 23:59';
			}
		}






$cuentas='665,642,611,482,483,719';
try {$rs_conforma_gana = $db->Execute("select sum(valor_premio) as total, count(valor_premio) as registros 
										from lavado_dinero.t_ganador 
										where suc_ban = ?
										and conformado=0
										and fecha_alta between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')",
										array($_SESSION['suc_ban']));
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
} 
while ($row_conforma_gana=$rs_conforma_gana->FetchNextObject($toupper=true)) {
	$suma_ganador=$row_conforma_gana->TOTAL;
	//echo 'Total_Gana : '.$row_conforma_gana->TOTAL.' - Registros: '.$row_conforma_gana->REGISTROS,'<br>';
}
if ($_SESSION['suc_ban']==1) {
		try {$rs_conforma_conta = $db->Execute("select (sum(b.debe)-sum(b.haber)) as total, count(b.debe) as registros 
											  from conta_new.asiento_cabecera a, conta_new.asiento_detalle b 
											  where a.nro_asiento=b.nro_asiento
											  and b.cod_cuenta in ($cuentas)
											  and a.cod_area_vinculante is null
											  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
											  and upper(a.concepto) like '%UIF%'");
			}
			
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
		} 
		$row_conforma_conta=$rs_conforma_conta->FetchNextObject($toupper=true);
		$suma_contabilidad=$row_conforma_conta->TOTAL;
		$diferencia='delegacion';
		//echo 'Total_Gana : '.$row_conforma_conta->TOTAL.' - Registros: '.$row_conforma_conta->REGISTROS.'<br>';		
} elseif (($_SESSION['suc_ban']==27)
	 or ($_SESSION['suc_ban']==23)
	 or ($_SESSION['suc_ban']==25) 
	 or ($_SESSION['suc_ban']==34) 
	 or ($_SESSION['suc_ban']==26) 
	 or ($_SESSION['suc_ban']==30) 
	 or ($_SESSION['suc_ban']==20)
	 or ($_SESSION['suc_ban']==21)  
	 or ($_SESSION['suc_ban']==31) 
	 or ($_SESSION['suc_ban']==24)
	 or ($_SESSION['suc_ban']==33)	
	 or ($_SESSION['suc_ban']==22)
	 or ($_SESSION['suc_ban']==32)){ 
	try {$rs_conforma_conta = $db->Execute("select  (sum(b.debe)-sum(b.haber)) as total, count(b.debe) as registros
											from conta_new.asiento_cabecera a, conta_new.asiento_detalle b, adm.area c
											where a.nro_asiento=b.nro_asiento
											and b.cod_cuenta in ($cuentas)
										  	and a.cod_area_vinculante=c.cod_area
										  	and c.suc_ban=?
										  	and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
										  	and upper(a.concepto) like '%UIF%'",
											array($_SESSION['suc_ban']));
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
		} 
		$row_conforma_conta=$rs_conforma_conta->FetchNextObject($toupper=true);
		$suma_contabilidad=$row_conforma_conta->TOTAL;
		$diferencia='delegacion';
} else{
	try {$rs_conforma_conta = $db->Execute("SELECT SUM(importe_plata) AS total,
											COUNT(importe_plata) AS registros
											FROM casino.t_reg_cp
											WHERE casa = upper(substr(?,8))
											AND conformado_uif = 0
											AND fecha BETWEEN to_date('$fdesde','DD/MM/YYYY HH24:MI') AND to_date('$fhasta','DD/MM/YYYY HH24:MI')
											AND importe_plata>=10000",
											array($_SESSION['area']));
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
		} 
		$row_conforma_conta=$rs_conforma_conta->FetchNextObject($toupper=true);
		$suma_contabilidad=$row_conforma_conta->TOTAL;
		$diferencia='casino';
}		
if ($suma_contabilidad==$suma_ganador) { ?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<form action="#" method="post" name="frmConformar" onsubmit="ajax_post('contenido','premio/procesar_conformar.php',this); return false;">
  <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2" align="center" class="textoAzulOscuroFondo">MONTOS A CONFORMAR</td>
    </tr>
    <tr>
      <td width="50%" align="center" class="td9Grande">CONTABILIDAD</td>
      <td width="50%" align="center" class="td9Grande">GANADORES</td>
    </tr>
    <tr>
      <td align="center" class="td2">$ <?php echo number_format($suma_contabilidad,2,',','.'); ?></td>
      <td align="center" class="td2">$ <?php echo number_format($suma_ganador,2,',','.'); ?></td>
    </tr>
    <tr>
      <td colspan="2" class="fondo1_left"><a href="#"  onclick="ajax_get('detalle_conforme','premio/ver_detalle_registros.php','fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>&cuentas=<?php echo $cuentas; ?>&diferencia=<?php echo $diferencia; ?>'); return false;">Ver Detalles</a><br />
      <div id="detalle_conforme"></div>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      	<input type="submit" name="button" id="button" value="Conformar todo" />
      	<input name="fechadesde" type="hidden" value="<?php echo $fdesde; ?>" />
      	<input name="fechahasta" type="hidden" value="<?php echo $fhasta; ?>" />       
      </td>
    </tr>
  </table>
</form>
<?php } else { ?>
  <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr class="textoAzulOscuroFondo">
      <td colspan="3" align="center" >MONTOS A CONFORMAR <strong>NO SON IGUALES</strong></td>
    </tr>
    <tr>
      <td width="33%" align="center" class="td9Grande">CONTABILIDAD</td>
      <td width="33%" align="center" class="td9Grande">GANADORES</td>
      <td width="34%" align="center" class="td9Grande">DIFERENCIA</td>
    </tr>
    <tr>
      <td align="center" class="td2">$ <?php echo number_format($suma_contabilidad,2,',','.'); ?></td>
      <td align="center" class="td2">$ <?php echo number_format($suma_ganador,2,',','.'); ?></td>
      <td align="center" class="td2">$ <?php echo number_format(abs($suma_contabilidad-$suma_ganador),2,',','.'); ?></td>
    </tr>
    <tr>
      <td colspan="3"><?php include("ver_detalle_registros_diferencia.php");?></td>
    </tr>
</table>
<?php }?>