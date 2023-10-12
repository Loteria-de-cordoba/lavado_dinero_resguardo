<?php session_start();
	include("../funcion.inc.php"); 
    include_once("../db_conecta_adodb.inc.php");
     header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
	header("Content-type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=excel.xls"); 
 //$db->debug=true;
//echo $_SESSION['sql_listado'] ;
 
try {
	$rs = $db->Execute($_SESSION['sql_premios']);
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
?>
<table width="50%" border="1" cellspacing="0">
  <tr class="th">
	    <td align="left"><div align="center">Nº de Premio</div></td>
        <td align="left"><div align="center">Moneda</div></td>
        <td align="left"><div align="center">Monto Total</div></td>
        <td align="left"><div align="center">Monto en Eftvo</div></td>
        <td align="left"><div align="center">Descripcion del Juego y Premio</div></td>
        <td align="left"><div align="center">Domicilio donde se adjudico el premio</div></td>
        <td align="left"><div align="center">Fecha de pago</div></td>
        <td align="left"><div align="center">Medio de Pago Bancario</div></td>
        <td align="left"><div align="center">Banco Emisor</div></td>
        <td align="left"><div align="center">Nº Cta. Emisora</div></td>
        <td align="left"><div align="center">Nº de Cheque</div></td>
        <td align="left"><div align="center">Banco Receptor</div></td>
        <td align="left"><div align="center">Nº Cta. Receptora</div></td>
        <td align="left"><div align="center">Tipo de Cta.</div></td>
        
 </tr>
  <?php while ($row = $rs->FetchNextObject($toupper=true)) {  ?>
<tr class="td">
 	<td align="left" valign="middle" class="td2"><?php echo $row->NRO_PREMIO; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo $row->MONEDA; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo round($row->VALOR_PREMIO); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo $row->VALOR_PREMIO; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->JUEGOS.', '.$row->CONCEPTO); ?></td>
    <!--<td align="left" valign="middle" class="td2"><?php// echo $row->CONCEPTO; ?></td>-->
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->DOMICILIO_PAGO); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo $row->FECHA_ALTA; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->DESCRIPCION); ?></td>
    <td align="left" valign="middle" class="td2"><?php if (($row->ID_TIPO_PAGO==2) || ($row->ID_TIPO_PAGO==3)) {echo 'BANCO DE LA PROVINCIA DE CORDOBA, SUCURSAL '. strtoupper($row->SUCURSAL);} ?></td>
    <td align="left" valign="middle" class="td2"><?php if (($row->ID_TIPO_PAGO==2)|| ($row->ID_TIPO_PAGO==3)) {echo $row->CUENTA_BANCARIA_SALIDA;} ?></td>
    <td align="left" valign="middle" class="td2"><?php if (($row->ID_TIPO_PAGO==2)|| ($row->ID_TIPO_PAGO==3)) {echo $row->CHEQUE_NRO; }?></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    
</tr>
<?php } ?>
</table>
