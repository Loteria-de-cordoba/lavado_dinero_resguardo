<?php session_start();
include_once("db_conecta_adodb.inc.php");
include_once("funcion.inc.php");
?>
<?php	
try {
    $rs_totales = $db->Execute("select
				sum(decode(nvl(juegos.f_inicio_banco(b.suc_ban, b.nro_agen),0), 0, null, importe)) as bancarizado,
				sum(decode(nvl(juegos.f_inicio_banco(b.suc_ban, b.nro_agen),0), 0, importe, null)) as no_bancarizado
				from cuenta_corriente.t_banco_cabecera a, cuenta_corriente.t_banco_detalle b, cuenta_corriente.concepto d 
				where a.id_banco_cabecera = b.id_banco_cabecera 
				and b.cod_concepto = d.cod_concepto 
				and a.fecha_valor = to_date(?,'DD/MM/YYYY')
				and b.cod_concepto in (?)
				$condicion_sucursal	
				UNION
				select
				sum(decode(nvl(juegos.f_inicio_banco(b.suc_ban, b.nro_agen),0), 0, null, importe)) as bancarizado,
				sum(decode(nvl(juegos.f_inicio_banco(b.suc_ban, b.nro_agen),0), 0, importe, null)) as no_bancarizado
				from cuenta_corriente.t_banco_cabecera a, cuenta_corriente.t_banco_detalle b, cuenta_corriente.concepto d 
				where a.id_banco_cabecera = b.id_banco_cabecera 
				and b.cod_concepto = d.cod_concepto 
				and a.fecha_valor = to_date(?,'DD/MM/YYYY')
				and b.cod_concepto in (?)
				$condicion_sucursal	
				UNION
				select
				Sum(decode(nvl(juegos.f_inicio_banco(b.suc_ban, b.nro_agen),0), 0, null, importe)) as bancarizado,
				sum(decode(nvl(juegos.f_inicio_banco(b.suc_ban, b.nro_agen),0), 0, importe, null)) as no_bancarizado
				from cuenta_corriente.t_banco_cabecera a, cuenta_corriente.t_banco_detalle b, cuenta_corriente.concepto d 
				where a.id_banco_cabecera = b.id_banco_cabecera 
				and b.cod_concepto = d.cod_concepto 
				and a.fecha_valor = to_date(?,'DD/MM/YYYY')
				$condicion_sucursal	",array($_SESSION['fecha'],50,$_SESSION['fecha'],51,$_SESSION['fecha']));
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 
try {
    $rs_sucursal = $db -> Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33)");
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 
?>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
<table border="0" align="center">
  <tr >
    <td width="50%" align="center" class="td9Grande">Totales</td>
    <td width="25%" align="center" class="td9Grande">Bancarizado</td>
    <td width="25%" align="center" class="td9Grande">No Bancarizado</td>
  </tr>
  <tr >
    <td align="left" class="texto3Totales">Total Saldos Acreedores (Pagos)</td>
    <td align="right" class="texto3Totales"><?php $row_totales = $rs_totales->FetchNextObject($toupper=true); echo number_format($row_totales->BANCARIZADO,2,',','.');?></td>
    <td align="right" class="texto3Totales"><?php echo number_format($row_totales->NO_BANCARIZADO,2,',','.');?></td>
  </tr>
  
  <tr>
    <td align="left" class="texto3Totales">Total Saldos Deudores (cobros)</td>
    <td align="right" class="texto3Totales" ><?php $row_totales = $rs_totales->FetchNextObject($toupper=true); echo number_format($row_totales->BANCARIZADO,2,',','.');?></td>
    <td align="right" class="texto3Totales" ><?php echo number_format($row_totales->NO_BANCARIZADO,2,',','.');?></td>
  </tr>
  <tr>
    <td align="left" class="texto3FondoRosa">Saldo Total</td>
    <td align="right" class="texto3FondoRosa" ><?php $row_totales = $rs_totales->FetchNextObject($toupper=true); echo number_format($row_totales->BANCARIZADO,2,',','.');?></td>
    <td align="right" class="texto3FondoRosa" ><?php echo number_format($row_totales->NO_BANCARIZADO,2,',','.');?></td>
  </tr>
  <tr class="td9Grande">
    <td colspan="3" align="center" class="td9Grande">Selecciones Delegacion a Ingresar al Archivo</td>
  </tr>
  <tr>
    <td align="center" class="texto3FondoRosa">
	<form id="form000348" name="form000348" method="post" action="#">
    <table width="70%" border="1" cellpadding="0" cellspacing="0">
      <tr align="center" class="td4">
        <td scope="col">Delegacion</td>
        <td scope="col">Envia Banco</td>
      </tr>
      <?php while ($row = $rs_sucursal->FetchNextObject($toupper=true)){?>
      <tr class="small">
        <td scope="col"><?php echo $row->DESCRIPCION; ?>
          <input type="hidden" name="sucursal<?php echo $rs_sucursal->CurrentRow(); ?>" id="sucursal<?php echo $rs_sucursal->CurrentRow(); ?>" value="<?php echo $row->CODIGO; ?>"/>
          <label></label></td>
        <td align="center" scope="col"><label>
          <input name="seleccion<?php echo $rs_sucursal->CurrentRow(); ?>" type="checkbox" id="seleccion<?php echo $rs_sucursal->CurrentRow(); ?>" value="1" />
        </label></td>
      </tr>
      <?php } ?>
      <tr class="small">
        <td colspan="2" align="center" scope="col">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <th align="center" scope="col"><label><img src="image/Export.png" width="128" height="128" /></label></th>
            </tr>
            <tr>
              <td align="center"><span class="texto3"><a href="#" onclick="if (validar_seleccion_sucursal(cantidad)==true) {
              													if (confirmSubmitSinValor('Desea Generar el archivo de PREMIOS para enviar a Bancor de las siguientes delagaciones ? '+seleccion_sucursal_coma(cantidad))) {
                                                                    /*ajax_get('contenido','AUDExportarArchivoBancor.php','suc_ban='+seleccion_sucursal_coma2(cantidad));*/
                                                                    location.href = 'AUDExportarArchivoBancorPREMIOS.php?suc_ban='+seleccion_sucursal_coma(cantidad);

                                                                }
                                                                } else {
                                                                    //alert('false');
                                                                    return false;
                                                                }" class="small">Descargar Archivo de PREMIOS para Bancor</a></span></td>
            </tr>
            <tr>
              <td><input type="hidden" name="cantidad" id="cantidad" value="<?php echo $rs_sucursal->RowCount(); ?>"/></td>
            </tr>
          </table>        </td>
        </tr>
    </table>
    </form>    </td>
    <td width="50%" colspan="2" align="center" class="texto3FondoRosa">	
    <?php $rs_sucursal->MoveFirst();?>
    <form id="form000349" name="form000349" method="post" action="#">
    <table width="70%" border="1" cellpadding="0" cellspacing="0">
      <tr align="center" class="td4">
        <td scope="col">Delegacion</td>
        <td scope="col">Envia Banco</td>
      </tr>
      <?php while ($row = $rs_sucursal->FetchNextObject($toupper=true)){?>
      <tr class="small">
        <td scope="col"><?php echo $row->DESCRIPCION; ?>
          <input type="hidden" name="sucursal<?php echo $rs_sucursal->CurrentRow(); ?>" id="sucursal<?php echo $rs_sucursal->CurrentRow(); ?>" value="<?php echo $row->CODIGO; ?>"/>
          <label></label></td>
        <td align="center" scope="col"><label>
          <input name="seleccion<?php echo $rs_sucursal->CurrentRow(); ?>" type="checkbox" id="seleccion<?php echo $rs_sucursal->CurrentRow(); ?>" value="1" />
        </label></td>
      </tr>
      <?php } ?>
      <tr class="small">
        <td colspan="2" align="center" scope="col">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <th align="center" scope="col"><label><img src="image/Export.png" width="128" height="128" /></label></th>
            </tr>
            <tr>
              <td align="center"><p class="texto3"><a href="#" onclick="if (validar_seleccion_sucursal2(cantidad)==true) {
              													if (confirmSubmitSinValor('Desea Generar el archivo de RECAUDACION para enviar a Bancor de las siguientes delagaciones ? '+seleccion_sucursal_coma2(cantidad))) {
                                                                    /*ajax_get('contenido','AUDExportarArchivoBancor.php','suc_ban='+seleccion_sucursal_coma2(cantidad));*/
                                                                    location.href = 'AUDExportarArchivoBancorRECAUDACION.php?suc_ban='+seleccion_sucursal_coma2(cantidad);

                                                                }
                                                                } else {
                                                                    //alert('false');
                                                                    return false;
                                                                }" class="small">Descargar Archivo de RECAUDACION para Bancor (diferido 24 HS)</a></p>                </td>
            </tr>
            <tr>
              <td><input type="hidden" name="cantidad" id="cantidad" value="<?php echo $rs_sucursal->RowCount(); ?>"/></td>
            </tr>
          </table>        </td>
        </tr>
    </table>
    </form>    </td>
  </tr>
  <tr>
    <td align="center" class="texto3FondoRosa"><span class="small"><a href="http://www.bancor.com.ar/" target="_blank"><img src="image/header_left2.png" alt="Abrir Pagina Bancor" width="414" height="57" border="0" /></a></span></td>
    <td colspan="2" align="center" class="texto3FondoRosa">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="texto3FondoRosa"><span class="small">Click para Abrir vinculo de subida de Archivo</span></td>
    <td colspan="2" align="center" class="texto3FondoRosa">&nbsp;</td>
  </tr>
</table>
