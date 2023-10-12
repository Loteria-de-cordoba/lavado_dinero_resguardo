<?php 
session_start();
//print_r($_GET);
include("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//$db->debug=true;
$fdesde=$_GET['fdesde'];
$fhasta=$_GET['fhasta'];
$cuentas=$_GET['cuentas'];
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php if ($_GET['diferencia']=='delegacion') {?>    
	<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="50%" align="center" class="td9Grande">Registros de Contabilidad</td>
        <td align="center" class="td9Grande">Registros de Ganadores</td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">        
      <tr>
                <td align="center" class="td9Grande">Fecha</td>
                <td align="center" class="td9Grande">Monto</td>
                <td align="center" class="td9Grande">Ver Detalle</td>
      </tr>
        <?php 
            if ($_SESSION['suc_ban']==1) {
                    try {$rsconta = $db->Execute("select a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor, a.nro_asiento 
                                                  from conta_new.asiento_cabecera a 
                                                  where a.cod_area_vinculante is null
                                                  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                                  and upper(a.concepto) like '%UIF%'
                                                  order by fecha_valor, a.total desc");
                    }
                    catch (exception $e)
                    {
                    die ($db->ErrorMsg()); 
                    } 		
                } else {
                    try {$rsconta = $db->Execute("select a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor, a.nro_asiento 
                                              from conta_new.asiento_cabecera a, adm.area c 
                                              where a.cod_area_vinculante=c.cod_area
                                              and c.suc_ban=?
                                              and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                              and upper(a.concepto) like '%UIF%'",
                                              array($_SESSION['suc_ban']));
									  
						}
                    catch (exception $e)
                    {
                    die ($db->ErrorMsg()); 
                    } 
                }	
            $fecha='';	
         while ($rowconta=$rsconta->FetchNextObject($toupper=true)) {?>      
            <tr>
                    <td align="center" height="19" class="td2"><?php if ($rowconta->FECHA_VALOR!=$fecha) {echo $rowconta->FECHA_VALOR; $fecha=$rowconta->FECHA_VALOR;}else{echo ' '; } ?></td>
                    <td align="right" class="td2">
                    <input type="hidden" name="MONTO" value=<?php echo $rowconta->TOTAL; ?> />
                    <?php echo '$ '.number_format($rowconta->TOTAL,2,',','.') ?></a></td>
                    
                    <td align="center" class="td2">
                   <!-- <a href="#"  onclick="ajax_get('contenido','premio/detalle_asiento_tooltip.php','asiento=<?php echo $rowconta->NRO_ASIENTO; ?>&fecha=<?php echo $rowconta->FECHA_VALOR; ?>&fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>',this);return false" >-->
                    
                    <a href="#" onmouseover="ajax_showTooltip('premio/detalle_asiento_tooltip.php?jsfecha='+new Date()+'&asiento=<?php echo $rowconta->NRO_ASIENTO; ?>&fecha=<?php echo $rowconta->FECHA_VALOR; ?>&fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>',this);return false" onmouseout="ajax_hideTooltip()"><img src="image/xmag.gif" alt="ver" width="23" height="21" border="0" /></a></td>
              </tr>
        <?php }?>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
                <td align="center" class="td9Grande">Fecha</td>
                <td align="center" class="td9Grande">Monto</td>
            </tr>
         <?php 
            try {$rsgana = $db->Execute("select valor_premio, to_char(fecha_alta,'DD/MM/YYYY') as fecha 
                                              from PLA_AUDITORIA.t_ganador 
                                              where suc_ban = ?
											  and conformado=0
                                              and fecha_alta between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                              order by fecha, valor_premio desc",
                                              array($_SESSION['suc_ban']));
                }
                catch (exception $e)
                {
                die ($db->ErrorMsg()); 
            }
            $fecha=''; 
        while ($rowgana=$rsgana->FetchNextObject($toupper=true)) {?>
              <tr>
                <td align="center" class="td2"><?php if ($rowgana->FECHA!=$fecha) {echo $rowgana->FECHA; $fecha=$rowgana->FECHA; }else{ echo ' ';}?></td>
                <td align="right" class="td2"><?php echo '$ '.number_format($rowgana->VALOR_PREMIO,2,',','.'); ?></td>
            </tr>
        <?php }?>
        </table></td>
      </tr>
    </table>
<?php } else {?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%" align="center" class="td9Grande">Registros de Caja P&uacute;blico</td>
        <td align="center" class="td9Grande">Registros de Ganadores</td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">        
      <tr>
                <td align="center" class="td9Grande">Fecha</td>
                <td align="center" class="td9Grande">Monto</td>
            </tr>
        <?php 
            try {$rsconta = $db->Execute("SELECT importe_plata AS total,
										  to_char(fecha,'DD/MM/YYYY') as fecha_valor
										  FROM casino.t_reg_cp
										  WHERE casa = upper(substr(?,8))
										  and conformado_uif=0
										  AND fecha BETWEEN to_date('$fdesde','DD/MM/YYYY HH24:MI') AND to_date('$fhasta','DD/MM/YYYY HH24:MI')
										  AND importe_plata>=10000",
										  array($_SESSION['area']));
            }
            catch (exception $e)
            {
            die ($db->ErrorMsg()); 
            } 			
        $fecha='';	
         while ($rowconta=$rsconta->FetchNextObject($toupper=true)) {?>      
              <tr>
                <td align="center" class="td2"><?php if ($rowconta->FECHA_VALOR!=$fecha) {echo $rowconta->FECHA_VALOR; $fecha=$rowconta->FECHA_VALOR;}else{echo ' '; } ?></td>
                <td align="right" class="td2"><?php echo '$ '.number_format($rowconta->TOTAL,2,',','.'); ?></td>
            </tr>
        <?php }?>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
                <td align="center" class="td9Grande">Fecha</td>
                <td align="center" class="td9Grande">Monto</td>
            </tr>
         <?php 
            try {$rsgana = $db->Execute("select valor_premio, to_char(fecha_alta,'dd/mm/yyyy') fecha, id_ganador, conformado 
                                              from PLA_AUDITORIA.t_ganador 
                                              where suc_ban = ?
                                               and fecha_alta between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                              order by fecha_alta, valor_premio desc",
                                              array($_SESSION['suc_ban']));
                }
                catch (exception $e)
                {
                die ($db->ErrorMsg()); 
            }
            $fecha=''; 
        while ($rowgana=$rsgana->FetchNextObject($toupper=true)) {?>
              <tr>
                <td align="center" class="td2"><?php if ($rowgana->FECHA!=$fecha) {echo $rowgana->FECHA; $fecha=$rowgana->FECHA; }else{ echo ' ';}?></td>
                <td align="right" class="td2"><?php echo '$ '.number_format($rowgana->VALOR_PREMIO,2,',','.'); ?></td>
            </tr>
        <?php }?>
        </table></td>
      </tr>
    </table>
<?php }?>