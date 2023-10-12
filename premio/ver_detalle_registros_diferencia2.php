
<?php //$db->debug=true;
?><link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php if ($diferencia=='delegacion'){

if (isset($_GET['fdesde'])) {
				$fdesde = $_GET['fdesde'];
		} else {
			if (isset($_POST['fdesde'])) {
				$fdesde = $_POST['fdesde'];
			} else {
				$fdesde =$_GET['fdesde'].' 00:00';
			}
		}

if (isset($_GET['fhasta'])) {
				$fhasta = $_GET['fhasta'];
		} else {
			if (isset($_POST['fhasta'])) {
				$fhasta = $_POST['fhasta'];
			} else {
				$fhasta =$_GET['fhasta'].' 23:59';
			}
		}




?>
<form id="form1" name="form1" method="post" onsubmit="ajax_post('conformar','premio/procesar_conformar_dif.php',this); return false;">

<input name="fdesde" type="hidden" value="<?php echo $fdesde; ?>" /> 
<input name="fhasta" type="hidden" value="<?php echo $fhasta; ?>" />
    <table width="90%" border="1" cellspacing="0" cellpadding="0">
      <tr>
        <td width="46%" align="center" class="td9Grande">Registros de Contabilidad</td>
        <td width="54%" align="center" class="td9Grande">Registros de Ganadores</td>
      </tr>
      <tr>
        <td valign="top" class="fondo1">
            <table width="100%" height="40" border="0" cellpadding="0" cellspacing="0">        
<tr>
                    <td width="15%" height="21" align="center" class="td9Grande">Fecha</td>
            <td width="22%" align="center" class="td9Grande"><div align="center">Monto</div></td>
            <td width="28%" align="center" class="td9Grande">Ver Detalle</td>
            <td width="35%" align="center" class="td9Grande">Ver Concepto</td>
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
                                              where a.cod_area=c.cod_area
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
                    
                    <td align="center" class="td2"><a href="#" onmouseover="ajax_showTooltip('premio/detalle_asiento_total_tooltip.php?jsfecha='+new Date()+'&asiento=<?php echo $rowconta->NRO_ASIENTO; ?>&fecha=<?php echo $rowconta->FECHA_VALOR; ?>&fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>',this);return false" onmouseout="ajax_hideTooltip()"><img src="image/xmag.gif" alt="ver" width="23" height="21" border="0" /></a></td>
                    <td align="center" class="td2">
                   <!-- <a href="#"  onclick="ajax_get('contenido','premio/detalle_asiento_tooltip.php','asiento=<?php echo $rowconta->NRO_ASIENTO; ?>&fecha=<?php echo $rowconta->FECHA_VALOR; ?>&fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>',this);return false" >--><a href="#" onmouseover="ajax_showTooltip('premio/detalle_asiento_tooltip.php?jsfecha='+new Date()+'&amp;asiento=<?php echo $rowconta->NRO_ASIENTO; ?>&amp;fecha=<?php echo $rowconta->FECHA_VALOR; ?>&amp;fdesde=<?php echo $fdesde; ?>&amp;fhasta=<?php echo $fhasta; ?>',this);return false" onmouseout="ajax_hideTooltip()"><img src="image/xmag.gif" alt="ver" width="23" height="21" border="0" /></a></td>
              </tr>
            <?php }?>
        </table></td>
        <td valign="top" class="fondo1"><table align="left" width="88%" border="0" cellspacing="0" cellpadding="0">
<tr>
                <td width="20%" height="21" align="center" class="td9Grande">Fecha</td>
              <td width="26%" align="center" class="td9Grande">Monto</td>
          <td width="30%" align="left" class="td9Grande"><div align="center">Nota</div></td>
          <td width="24%" align="left" class="td9Grande"><div align="center">Conforma</div></td>
          </tr>
         <?php 
            try {$rsgana = $db->Execute("select valor_premio, to_char(fecha_alta,'dd/mm/yyyy') fecha, id_ganador, conformado 
                                              from PLA_AUDITORIA.t_ganador 
                                              where suc_ban = ?
											   and fecha_baja is  null 
											   and usuario_baja is  null
                                               and fecha_alta between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                              order by fecha_alta, valor_premio desc",
                                              array($_SESSION['suc_ban']));
                }
                catch (exception $e)
                {
                die ($db->ErrorMsg()); 
            }
            $fecha=''; 
			$i=0;
        while ($rowgana=$rsgana->FetchNextObject($toupper=true)) {
		$i=$i+1;?>
              <tr>
                <td align="center" height="21" class="td2"><?php if ($rowgana->FECHA!=$fecha) {echo $rowgana->FECHA; $fecha=$rowgana->FECHA; }else{ echo ' ';}?></td>
                <td align="right" class="td2"><?php echo '$ '.number_format($rowgana->VALOR_PREMIO,2,',','.'); ?></td>
                <td align="center" class="td2">
                <a href="#" onmouseover="ajax_showTooltip('premio/nueva_nota.php?jsfecha='+new Date()+'&amp;fdesde=<?php echo $fdesde; ?>&amp;fhasta=<?php echo $fhasta; ?>&amp;ganador=<?php echo $rowgana->ID_GANADOR; ?>',this);return false" onmouseout="ajax_hideTooltip()"><img src="image/xmag.gif" alt="ver" width="23" height="21" border="0" /></a>
                <a href="#" onclick="ajax_get('contenido','premio/nueva_nota.php','ganador=<?php echo $rowgana->ID_GANADOR; ?>&fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>');"><img src="image/app_48.png" alt="agregar nota"  width="23" height="21" border="0"/></a></td>
                <td align="center" class="td2">
<!--                <a href="#" onmouseover="ajax_showTooltip('premio/nueva_nota.php?jsfecha='+new Date()+'&amp;fdesde=<?php// echo $fdesde; ?>&amp;fhasta=<?php// echo $fhasta; ?>&amp;ganador=<?php// echo $rowgana->ID_GANADOR; ?>',this);return false" onmouseout="ajax_hideTooltip()"></a>
-->                <?php           //echo $rowgana->CONFORMADO;
      if($rowgana->CONFORMADO=='0') {
		 $chequeado="";
	  	 $habilitado=""; }
          else { 
		  		
		  		
		  		$chequeado='checked="checked"';
				$habilitado='disabled="disabled"';} 
				    ?>
                <input name="conformadif<?php echo $i; ?>"  type="checkbox" id="conformadif<?php echo $i; ?>" value="<?php echo $rowgana->ID_GANADOR; ?>" <?php echo $habilitado; ?> <?php echo $chequeado;?> />
                </td>
          </tr><?php   ?>
            
        <?php }?>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="fondo1_small"> <img src="image/24px-Crystal_Clear_app_kjobviewer.png" width="24" height="24" border="0" onclick="window.open('list/lista_registros_diferencia.php?fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>&cuentas=<?php echo $cuentas; ?>', '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')"/> Imprimir registros </td>
        <td align="right" class="fondo1"><div align="center">
          <input type="submit" name="Conformar2" id="Conformar2" value="Conformar"  />
           <tr>
      <td colspan="2" class="fondo1_left">
       
          <input type="hidden" name="conta" id="conta" value="<?php echo $i ?>" />
        
        <div id="detalle_conforme"></div>
      </td>
    </tr>
        </div></td>
      </tr>
    </table>
</form>
<?php } else {?>

<form id="form2" name="form2" method="post" onsubmit="ajax_post('conformar','premio/procesar_conformar_casino_dif.php',this); return false;">


<input name="fdesde" type="hidden" value="<?php echo $fdesde; ?>" /> 
<input name="fhasta" type="hidden" value="<?php echo $fhasta; ?>" />

	    
    <table width="79%" border="1" align="center" cellspacing="0" cellpadding="0">
      <tr>
        <td width="54%" align="center" class="td9Grande">Registros de Caja P&uacute;blico</td>
        <td width="46%" align="center" class="td9Grande">Registros de Ganadores</td>
      </tr>
      <tr>
        <td valign="top" class="fondo1">
            <table width="101%" height="40" border="0" cellpadding="0" cellspacing="0">        
<tr>
                    <td width="17%" height="21" align="center" class="td9Grande">Fecha</td>
            <td width="26%" align="center" class="td9Grande">Monto Pagado</td>
                     <td width="26%" align="center" class="td9Grande">Impuesto</td>
            <td width="31%" align="center" class="td9Grande">Importe Ficha</td>
          <!--<td width="31%" align="left" class="td9Grande">Ver Detalle</td>-->
              </tr>
            <?php 	
                    try {$rsconta = $db->Execute("SELECT importe_plata AS total, importe_impuesto as IMPUESTO , id_cp, importe_ficha ,
  												TO_CHAR(fecha,'DD/MM/YYYY') AS fecha_valor
  												FROM casino.t_reg_cp
												  WHERE casa = upper(substr(?,8))
												  --and conformado_uif=0
												  and (anulado <>'S' or anulado is null)
												  AND fecha BETWEEN to_date('$fdesde 00:00:00','DD/MM/YYYY HH24:MI:SS') AND to_date('$fhasta 23:59:59','DD/MM/YYYY HH24:MI:SS')
												  AND importe_ficha>=10000
												  order by fecha, total desc",
												  array($_SESSION['area']));
                    }
                    catch (exception $e)
                    {
                    die ($db->ErrorMsg()); 
                    } 		
            $fecha='';	
             while ($rowconta=$rsconta->FetchNextObject($toupper=true)) {?>      
                  <tr>
                    <td align="right" height="19" class="td2"><?php if ($rowconta->FECHA_VALOR!=$fecha) {echo $rowconta->FECHA_VALOR; $fecha=$rowconta->FECHA_VALOR;}else{echo ' '; } ?></td>
                    <td align="center" class="td2"><?php echo '$ '.number_format($rowconta->TOTAL,2,',','.'); ?></td>
                    <td class="td2" align="center">
                    <!--<a href="#" onmouseover="ajax_showTooltip('premio/detalle_asiento_casino_tooltip.php?jsfecha='+new Date()+'&amp;asiento=<?php echo $rowconta->ID_CP; ?>&amp;fdesde=<?php echo $fdesde; ?>&amp;fhasta=<?php echo $fhasta; ?>',this);return false" ><img src="image/xmag.gif" alt="ver" width="23" height="21" border="0" /></a>-->
                    <?php echo '$ '.number_format($rowconta->IMPUESTO,2,',','.'); ?></td>
                    <td align="center" class="td2"><?php echo '$ '.number_format($rowconta->IMPORTE_FICHA,2,',','.'); ?></td>
              </tr>
            <?php }?>
        </table></td>
        <td valign="top" class="fondo1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
                <td width="30%" height="21" align="center" class="td9Grande">Fecha</td>
            <td width="28%" align="center" class="td9Grande">Monto</td>
            <td width="42%" align="right" class="td9Grande"><div align="center">Nota</div></td>
            
            <td width="42%" align="right" class="td9Grande">Conforma</td>
      </tr>
         <?php 
if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "and suc_ban in ($suc_ban)";
			} else {
		 
					if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
						$suc_ban = $_POST['suc_ban'];
						$condicion_sucursal = "and suc_ban in ($suc_ban)";
					} else {
						$suc_ban=$_SESSION['suc_ban'];
						$condicion_sucursal = "and suc_ban in ($suc_ban)";		
					}
			}
			
	if ($suc_ban==72){
	//$suc_ban=81;
	$condicion_sucursal = "and (suc_ban in ($suc_ban) or suc_ban=81)";	
}		 
		 
            try {$rsgana = $db->Execute("select valor_premio, to_char(fecha_alta,'DD/MM/YYYY') as fecha, id_ganador, conformado 
                                              from PLA_AUDITORIA.t_ganador 
                                              where fecha_alta between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
                                              and fecha_baja is not null 
											  and usuario_baja is not null
											  $condicion_sucursal
											  order by fecha, valor_premio desc");
                }
                catch (exception $e)
                {
                die ($db->ErrorMsg()); 
            }
            $fecha=''; 
        while ($rowgana=$rsgana->FetchNextObject($toupper=true)) {
		$i=$i+1;?>
              <tr>
                <td align="center" height="21" class="td2"><?php if ($rowgana->FECHA!=$fecha) {echo $rowgana->FECHA; $fecha=$rowgana->FECHA; }else{ echo ' ';}?></td>
                <td align="right" class="td2"><?php echo '$ '.number_format($rowgana->VALOR_PREMIO,2,',','.'); ?></td>
                <td align="center" class="td2">
                <a href="#" onmouseover="ajax_showTooltip('premio/nueva_nota.php?jsfecha='+new Date()+'&amp;fdesde=<?php echo $fdesde; ?>&amp;fhasta=<?php echo $fhasta; ?>&amp;ganador=<?php echo $rowgana->ID_GANADOR; ?>',this);return false" onmouseout="ajax_hideTooltip()"><img src="image/xmag.gif" alt="ver" width="23" height="21" border="0" /></a>
                <a href="#" onclick="ajax_get('contenido','premio/nueva_nota.php','ganador=<?php echo $rowgana->ID_GANADOR; ?>&fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>');"><img src="image/app_48.png" alt="agregar nota"  width="23" height="21" border="0"/></a></td>
                <td align="center" class="td2">
                <?php          // echo $rowgana->CONFORMADO;
      if($rowgana->CONFORMADO=='0') {
		  $ganador=$rowgana->ID_GANADOR;
		  $condicion_ganador="and id_ganador=$ganador";
		  try {
				$rsdatos = $db->Execute("select count(*) as cantidad
										from PLA_AUDITORIA.t_ganador
										where (cuit is null or cod_postal is NULL)
										and fecha_baja is not null 
										and usuario_baja is not null
										$condicion_ganador");
			  }
			  catch (exception $e){
					die ($db->ErrorMsg()); 
			  }
		$rowcantidad=$rsdatos->FetchNextObject($toupper=true);
			if ($rowcantidad->CANTIDAD==0){
				$chequeado="";
				$habilitado=""; }
				else
					{	// cuando faltan cargar datos en la alta del ganador!
						$chequeado="";
						$habilitado='disabled="disabled"';					
					}
		}
             else { $chequeado='checked="checked"';
					$habilitado='disabled="disabled"';
					} 
						?>
                <input name="conformadif<?php echo $i; ?>"  type="checkbox" id="conformadif<?php echo $i; ?>" value="<?php echo $rowgana->ID_GANADOR; ?>" <?php echo $habilitado; ?> <?php echo $chequeado;?> title="seleccionar" /></td>
            </tr>
            
        <?php }?>
        </table></td>
      </tr>
      <tr>
        <td height="64" align="right" class="fondo1_small"> <img src="image/24px-Crystal_Clear_app_kjobviewer.png" width="24" height="24" border="0" onclick="window.open('list/lista_registros_diferencia_casino.php?fdesde=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>&cuentas=<?php echo $cuentas; ?>', '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')"/> Imprimir registros </td>
        <td align="right" class="fondo1">
        
          <label>
          </label>
          <label class="td2">
          <div align="center">
            <input type="submit" name="Conformar" id="Conformar" value="Conformar" />
            <span class="fondo1_left">
            <input type="hidden" name="conta2" id="conta2" value="<?php echo $i ?>" />
          </span></div>
          </label>
               </td>
      </tr>
    </table>
</form>
<?php }?>