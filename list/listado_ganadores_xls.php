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

try {
	$rs = $db->Execute($_SESSION['sql_ganadores']);
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}

?>
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td  align="center" class="textoAzulOscuro" style="font-size:36px"><b>Datos Resguardados</b></td>
  </tr>
</table>
<table width="50%" border="1" cellspacing="0">
  <tr class="th">
	    <td align="left"><div align="center">Nº de Premio</div></td>
        <td align="left"><div align="center">CUIT/CUIL/CDI</div></td>
        <td align="left"><div align="center">Tipo Documento</div></td>
        <td align="left"><div align="center">Nº Documento</div></td>
        <td align="left"><div align="center">Apellido y Nombre</div></td>
        <td align="left"><div align="center">País Origen</div></td>
        <td align="left"><div align="center">Calle y Nº</div></td>
        <td align="left"><div align="center">Piso</div></td>
        <td align="left"><div align="center">Depto</div></td>
        <td align="left"><div align="center">Código Postal</div></td>
        <td align="left"><div align="center">Localidad</div></td>
        <td align="left"><div align="center">Provincia</div></td>
        <td align="left"><div align="center">País</div></td>
        <td align="left"><div align="center">Profesión</div></td>
        <td align="left"><div align="center"></div></td>
        <td align="left"><div align="center">CUIT/CUIL/CDI</div></td>
        <td align="left"><div align="center">Tipo Documento</div></td>
        <td align="left"><div align="center">Nº de Documento</div></td>
        <td align="left"><div align="center">Apellido y Nombre</div></td>
        <td align="left"><div align="center">País Origen</div></td>
        <td align="left"><div align="center">Calle y Nro</div></td>
        <td align="left"><div align="center">Piso</div></td>
        <td align="left"><div align="center">Depto</div></td>
        <td align="left"><div align="center">Codigo Postal</div></td>
        <td align="left"><div align="center">Localidad</div></td>
        <td align="left"><div align="center">Provincia</div></td>
        <td align="left"><div align="center">País</div></td>
        <td align="left"><div align="center">Profesión</div></td>
        
 </tr>
  <?php while ($row = $rs->FetchNextObject($toupper=true)) {
  if($row->CALLE==''){
			  $domicilio=utf8_encode($row->DOMICILIO);
			  }else {
			  	$domicilio=utf8_encode($row->CALLE .' '.$row->NUMERO);
			  }
$tipo_doc=utf8_decode($row->DESCRIPCION);
$apellido=utf8_decode($row->APELLIDO);
    ?>
<tr class="td">
 	<td align="left" valign="middle" class="td2"><?php echo $row->NRO_PREMIO; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo $row->CUIT; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper(utf8_decode($tipo_doc)); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo $row->DOCUMENTO; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper(utf8_decode($apellido.' '.$row->NOMBRE)); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->NACIONALIDAD); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper(utf8_decode($domicilio)); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo $row->PISO; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->DPTO); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->COD_POSTAL); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->N_LOCALIDAD); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->N_PROVINCIA); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->N_PAIS); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper(utf8_encode($row->PROFESION)); ?></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"><?php echo $row->ID_TIPO_DOCUEMENTO2; ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->DOCUEMENTO2); ?></td>
    <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->APELLIDO2.' '.$row->NOMBRE2); ?></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    
</tr>
<?php } ?>
</table>
