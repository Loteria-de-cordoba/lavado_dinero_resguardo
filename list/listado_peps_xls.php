<?php session_start();
	include("../funcion.inc.php"); 
    include_once("../db_conecta_adodb.inc.php");
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
	header("Content-type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=excel.xls");

//print_r($_POST);
//print_r($_GET);
//die('llegue');
//$db->debug=true;
$toti=0;
if ($_GET['mayores']==1) {
	$condicion_mayores="and gdor.valor_premio>50000";

	
	} if ($_GET['mayores']==0) {
		$condicion_mayores="";
		
		}

if ($_GET['juego']=='Casino') {
	$condicion_juego="and jue.id_juegos=25";
	$titulojuego=' DE CASINOS';
}
else if ($_GET['juego']=='Todos') {
	$condicion_juego="and jue.id_juegos in (1,3,4,5,9,10,13,18,20,21,22,23,24,25)";
	$titulojuego='';
} 
else {
		$condicion_juego="and jue.id_juegos in (1,3,4,5,9,10,13,18,20,21,22,23,24)";
		$titulojuego=' DE OTROS JUEGOS';
		}


$fecha_desde=$_GET['fecha_desde'];
$fecha_hasta=$_GET['fecha_hasta'];
$condicion="and gdor.fecha_alta between to_date('$fecha_desde','dd/mm/yyyy') and  to_date('$fecha_hasta','dd/mm/yyyy')";

//$db->debug=true;

try {
	$rs = $db->Execute("select  DISTINCT
								gdor.apellido || ', ' || gdor.nombre as nomape,
								td.descripcion || ' Nro. ' || gdor.documento as documento,
								gdor.cuit,
								gdor.calle,
								gdor.numero,
								gdor.domicilio,
								lo.n_localidad,
								pro.n_provincia,
								pa.n_pais,
								gdor.valor_premio,
								jue.juegos, 
								gdor.cheque_nro,
								pago.descripcion medio_pago,
								gdor.fecha_alta,
								gdor.nro_premio,
								LPAD(gdor.ddjj,4,'0') AS DDJJ
								from lavado_dinero.t_ganador gdor, 
									lavado_dinero.t_moneda mo, juegos.juegos jue,
									lavado_dinero.t_tipo_pago pago, 
									lavado_dinero.t_info_direcciones info,
									lavado_dinero.t_tipo_documento td, 
									administrativo.t_localidades lo, 
									administrativo.t_provincias pro, 
									utilidades.t_paises pa
								where gdor.id_moneda=mo.id_moneda
									and gdor.fecha_baja is null
									and gdor.juego= jue.id_juegos
									and gdor.id_tipo_pago= pago.id_tipo_pago
							  		and info.suc_ban(+)= gdor.suc_ban
							  		and gdor.id_tipo_documento=td.id_tipo_documento
							  		and gdor.id_localidad= lo.id_localidad(+)
									and lo.id_provincia= pro.id_provincia(+)
									and pro.id_pais=pa.id_pais(+)
									and upper(gdor.politico)='SI'
									$condicion
									$condicion_mayores
									$condicion_juego
								order by gdor.fecha_alta,gdor.nro_premio ");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
?>
<table width="60%" border="0" cellspacing="0">
    <tr class="th" height="50">
    	<td align="center" colspan="15" style="font-size:24px; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif; height:auto"><p>[Datos Resguardados]<br /> GANADORES PEP's <?PHP echo $titulojuego;?> </p></td>
    </tr>
    <tr class="th" height="50">
        <td align="center" colspan="15" style="font-size:24px; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif"><p>ENTRE EL <?PHP echo $fecha_desde;?> Y EL <?PHP echo $fecha_hasta;?></p></td>	
    </tr>
</table>
<table width="50%" border="2" cellspacing="0">
  <tr class="th" style="background-color:#99FF99; font-weight:bold">
   		<td align="left"><div align="center">Apellido y Nombre</div></td>
        <td align="left"><div align="center">Tipo Y Nº Documento</div></td>
        <td align="left"><div align="center">CUIT/CUIL/CDI</div></td>
        <td align="left"><div align="center">Calle y Nº</div></td>
        <td align="left"><div align="center">Piso</div></td>
        <td align="left"><div align="center">Depto</div></td>
        <td align="left"><div align="center">Código Postal</div></td>
        <td align="left"><div align="center">Localidad</div></td>
        <td align="left"><div align="center">Provincia</div></td>
        <td align="left"><div align="center">País</div></td>
        <td align="left"><div align="center">Monto Total</div></td>
        <td align="left"><div align="center">Juego</div></td>
        <td align="left"><div align="center">Medio de Pago Bancario</div></td>
        <td align="left"><div align="center">Fecha de pago</div></td>
        <td align="left"><div align="center">DDJJ</div></td>        
 </tr>
  <?php while ($row = $rs->FetchNextObject($toupper=true)) { $i++;
    
  if($row->CALLE==''){
			  $domicilio=utf8_encode($row->DOMICILIO);
			  }else {
			  	$domicilio=utf8_encode($row->CALLE .' '.$row->NUMERO);
			  }
    
   ?>
<tr class="td">
        <td align="left" valign="middle" class="td2"><?php echo strtoupper(utf8_decode($row->NOMAPE)); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo $row->DOCUMENTO; ?></td>
        <td align="center" valign="middle" class="td2"><?php echo $row->CUIT; ?></td>
        <td align="left" valign="middle" class="td2"><?php $xx=utf8_decode($domicilio);echo strtoupper(utf8_decode($xx)); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo $row->PISO; ?></td>
        <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->DPTO); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->COD_POSTAL); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->N_LOCALIDAD); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->N_PROVINCIA); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->N_PAIS); ?></td>
        <td align="right" valign="middle" class="td2"><?php echo number_format($row->VALOR_PREMIO,2,',','.'); ?></td>
     	<td align="left" valign="middle" class="td2"><?php echo strtoupper($row->JUEGOS); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo strtoupper($row->MEDIO_PAGO); ?></td>
        <td align="center" valign="middle" class="td2"><?php echo $row->FECHA_ALTA; ?></td>
        <td align="right" valign="middle" class="td2"><?php echo $row->DDJJ; ?></td>   
        <!--<td align="right" valign="middle" class="td2"><?php// echo str_pad($row->DDJJ,4, '0',STR_PAD_LEFT); ?></td>  --> 
     
    
    

<!-- 	<td align="left" valign="middle" class="td2"><?php// echo $i; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->NRO_PREMIO; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->CUIT; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper(utf8_decode($row->DESCRIPCION)); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->DOCUMENTO; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper(utf8_decode($row->APELLIDO.' '.$row->NOMBRE)); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->NACIONALIDAD); ?></td>
    <td align="left" valign="middle" class="td2"><?php// $xx=utf8_decode($domicilio);echo strtoupper(utf8_decode($xx)); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->PISO; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->DPTO); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->COD_POSTAL); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->N_LOCALIDAD); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->N_PROVINCIA); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->N_PAIS); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->PROFESION); ?></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->ID_TIPO_DOCUEMENTO2; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->DOCUEMENTO2); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->APELLIDO2.' '.$row->NOMBRE2); ?></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->NRO_PREMIO; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->MONEDA; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo round($row->VALOR_PREMIO); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->VALOR_PREMIO; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->JUEGOS.', '.$row->CONCEPTO); ?></td>
    <!--<td align="left" valign="middle" class="td2"><?php// echo $row->CONCEPTO; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->DOMICILIO_PAGO); ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo $row->FECHA_ALTA; ?></td>
    <td align="left" valign="middle" class="td2"><?php// echo strtoupper($row->MEDIO_PAGO); ?></td>
    <td align="left" valign="middle" class="td2"><?php// if (($row->ID_TIPO_PAGO==2) || ($row->ID_TIPO_PAGO==3)) {echo 'BANCO DE LA PROVINCIA DE CORDOBA, SUCURSAL '. strtoupper($row->SUCURSAL);} ?></td>
    <td align="left" valign="middle" class="td2"><?php// if (($row->ID_TIPO_PAGO==2)|| ($row->ID_TIPO_PAGO==3)) {echo $row->CUENTA_BANCARIA_SALIDA;} ?></td>
    <td align="left" valign="middle" class="td2"><?php// if (($row->ID_TIPO_PAGO==2)|| ($row->ID_TIPO_PAGO==3)) {echo $row->CHEQUE_NRO; }?></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>
    <td align="left" valign="middle" class="td2"></td>-->
    
</tr>
<?php 
$toti=$toti+1;
} ?>
</table>
<table width="60%" border="0" cellspacing="0">
    <tr class="th" height="50">
    	<td align="center" colspan="15" style="font-size:24px; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif"><p>TOTAL DE GANADORES--------><?PHP echo $toti;?></p></td>
    </tr>
</table>
