<?php 
session_start();
include("../jscalendar-1.0/calendario.php");
include("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//$db->debug=true;
//print_r($_POST);


if (isset($_POST['mes'])) {
	$mes = $_POST['mes'];
	} 
	else {
		$mes=1;
	}

if (isset($_POST['ano'])) {
	$ano = $_POST['ano'];
	} 
	else {
		$ano=2011;
	}


if (isset($_POST['mesg'])) {
	$mesg = $_POST['mesg'];
	} 
	else {
		$mesg=1;
	}

if (isset($_POST['anog'])) {
	$anog = $_POST['anog'];
	} 
	else {
		$anog=2011;
	}


if (isset($_POST['mes_desde'])) {
	$mes_desde = $_POST['mes_desde'];
	} 
	else {
		$mes_desde= 1;
	}
if (isset($_POST['mes_hasta'])) {
	$mes_hasta = $_POST['mes_hasta'];
	} 
	else {
		$mes_hasta=1;
	}

if (isset($_POST['ano_desde'])) {
	$ano_desde = $_POST['ano_desde'];
	} 
	else {
		$ano_desde=2011;
	}

if (isset($_POST['ano_hasta'])) {
	$ano_hasta = $_POST['ano_hasta'];
	} 
	else {
		$ano_hasta=2011;
	}



if (isset($_POST['nro_premio'])) {
	$nro_premio = $_POST['nro_premio'];
	} 
	else {
		$nro_premio=1;
	}



if (isset($_POST['mayores'])) {
	$mayores = $_POST['mayores'];
	$mayores =1;
	
	} else {
		$mayores = 0;
		
		}
			

if (isset($_POST['juego'])) {
	$juego = $_POST['juego'];
	} 
	else {
		$juego=1;
	}

	
 //$mes=$_POST['mes'];
 //$mesg=$_POST['mesg'];
 //$mes_desde=$_POST['mes_desde'];
 //$mes_hasta=$_POST['mes_hasta'];
 //$nro_premio=$_POST['nro_premio'];

$fecha='15/'.$mes_hasta.'/'.$ano_hasta;

//echo 'fecha'.$fecha;
//$db->debug=true;
try {
	$rs_dias_del_mes = $db->Execute("SELECT TO_CHAR(LAST_DAY(to_date('$fecha','dd/mm/yyyy')),'DD') dias FROM DUAL ");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}

$row = $rs_dias_del_mes->FetchNextObject($toupper=true);	
$dias=$row->DIAS;	

$fecha_desde='01'.'/'.$mes_desde.'/'.$ano_desde;


/*try {
	$rs_fecha_hasta = $db->Execute("select to_char(trunc(last_day(fecha_alta)),'dd/mm/yyyy') fecha_hasta
									from lavado_dinero.t_ganador
									where to_char(fecha_alta,'mm')=?
									and to_char(fecha_alta,'yyyy')=?", array($mes_hasta, $ano_hasta));
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
$row1 = $rs_fecha_hasta->FetchNextObject($toupper=true);
*/	
$fecha_hasta=$dias.'/'.$mes_hasta.'/'.$ano_hasta;



 
 
$_SESSION['sql_premios']="select gdor.id_ganador, mo.descripcion as moneda, gdor.valor_premio, gdor.concepto ,
						jue.juegos, gdor.domicilio_pago, gdor.fecha_alta, pago.descripcion, gdor.cuenta_bancaria_salida, 
            			gdor.cheque_nro, info.sucursal, gdor.nro_premio, pago.id_tipo_pago
						from lavado_dinero.t_ganador gdor, lavado_dinero.t_moneda mo, juegos.juegos jue, 
						lavado_dinero.t_tipo_pago pago, lavado_dinero.t_info_direcciones info
						where gdor.id_moneda=mo.id_moneda
						and gdor.juego= jue.id_juegos
						and gdor.id_tipo_pago= pago.id_tipo_pago
            			and info.suc_ban(+)= gdor.suc_ban
						and gdor.fecha_baja is null
						--and fecha_alta between to_date('01/04/2010 00:00:00','DD/MM/YYYY HH24:MI:SS') and to_date('30/04/2010 23:59:59','DD/MM/YYYY HH24:MI:SS')
						and to_char(gdor.fecha_alta, 'yyyy')= $ano
						and to_char(gdor.fecha_alta, 'mm')= $mes
						order by nro_premio, fecha_alta";
 
$_SESSION['sql_ganadores']="select gdor.id_ganador, td.descripcion, gdor.documento, gdor.apellido, gdor.nombre, gdor.nacionalidad, gdor.cuit,
						gdor.domicilio, lo.n_localidad, pro.n_provincia, pa.n_pais, gdor.profesion, gdor.cod_postal, gdor.calle, gdor.numero, gdor.piso, gdor.dpto,
						td.descripcion, gdor.documento2, gdor.apellido2, gdor.nombre2, gdor.fecha_alta, gdor.nro_premio
						from lavado_dinero.t_ganador  gdor, lavado_dinero.t_moneda mo, juegos.juegos jue, 
						lavado_dinero.t_tipo_pago pago, lavado_dinero.t_tipo_documento td, administrativo.t_localidades lo,
						administrativo.t_provincias pro, utilidades.t_paises pa
						where gdor.id_moneda=mo.id_moneda
						and gdor.fecha_baja is null
						and gdor.juego= jue.id_juegos
						and gdor.id_tipo_pago= pago.id_tipo_pago
						and gdor.id_tipo_documento=td.id_tipo_documento
						--and gdor.fecha_alta between to_date('01/03/2010 00:00:00','DD/MM/YYYY HH24:MI:SS') and to_date('31/03/2010 23:59:59','DD/MM/YYYY HH24:MI:SS')
						and to_char(gdor.fecha_alta, 'yyyy')= $anog
						and to_char(gdor.fecha_alta, 'mm')= $mesg
						and gdor.id_localidad= lo.id_localidad(+)
						and lo.id_provincia= pro.id_provincia(+)
						and pro.id_pais=pa.id_pais(+)
						order by gdor.nro_premio, gdor.fecha_alta";


/*$_SESSION['sql_consolidacion']="select gdor.id_ganador, mo.descripcion as moneda, gdor.valor_premio, gdor.concepto ,jue.juegos, gdor.domicilio_pago, gdor.fecha_alta,
									pago.descripcion, gdor.cuenta_bancaria_salida, gdor.cheque_nro, info.sucursal, gdor.nro_premio, pago.id_tipo_pago, td.descripcion,
									gdor.documento, gdor.apellido, gdor.nombre, gdor.nacionalidad, gdor.cuit,gdor.domicilio, lo.n_localidad, pro.n_provincia,
									pa.n_pais, gdor.profesion, gdor.cod_postal,td.descripcion, gdor.documento2, gdor.apellido2, gdor.nombre2, gdor.fecha_alta, gdor.nro_premio
								from lavado_dinero.t_ganador gdor, lavado_dinero.t_moneda mo, juegos.juegos jue, lavado_dinero.t_tipo_pago pago, 
									lavado_dinero.t_info_direcciones info, lavado_dinero.t_tipo_documento td, utilidades.t_localidades lo, utilidades.t_provincias pro, 
									utilidades.t_paises pa
								where gdor.id_moneda=mo.id_moneda
									and gdor.juego= jue.id_juegos
									and gdor.id_tipo_pago= pago.id_tipo_pago
							  		and info.suc_ban(+)= gdor.suc_ban
							  		and gdor.id_tipo_documento=td.id_tipo_documento
							  		and gdor.id_localidad= lo.id_localidad(+)
									and lo.id_provincia= pro.id_provincia(+)
									and pro.id_pais=pa.id_pais(+)
									and gdor.fecha_alta between $fecha_desde and $fecha_hasta
									
								order by gdor.nro_premio, gdor.fecha_alta";
*/
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style1 {color: #FF0033}
-->
</style>
<form action="#"   method="post" id="FrmBuscaFechaConformar" name="FrmBuscaFechaConformar"  >
<div id="conformar">
<table width="290" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="4" align="center" class="textoAzulOscuro">Datos de los Premio</td>
  </tr>
  <tr>
    <td width="100"><table border="0" cellspacing="0">
        <tr>
          <td width="98" class="small" align="center">Mes</td>
           <td width="98" class="small" align="center">A&ntilde;o</td>
        </tr>
        <tr>
          <td>  
            <select name="mes" id="mes"  onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;" >
              <option value="1" <?php   if ($_POST['mes']==1) echo 'selected=selected;'?> >enero</option>
              <option value="2"  <?php   if ($_POST['mes']==2) echo 'selected=selected;'?> >febrero</option>
              <option value="3" <?php   if ($_POST['mes']==3) echo 'selected=selected;'?> >marzo</option>
              <option value="4"  <?php   if ($_POST['mes']==4) echo 'selected=selected;'?> >abril</option>
              <option value="5"  <?php   if ($_POST['mes']==5) echo 'selected=selected;'?> >mayo</option>
              <option value="6"  <?php   if ($_POST['mes']==6) echo 'selected=selected;'?> >junio</option>
              <option value="7"  <?php   if ($_POST['mes']==7) echo 'selected=selected;'?> >julio</option>
              <option value="8"  <?php   if ($_POST['mes']==8) echo 'selected=selected;'?> >agosto</option>
              <option value="9"  <?php   if ($_POST['mes']==9) echo 'selected=selected;'?> >setiembre</option>
              <option value="10"  <?php   if ($_POST['mes']==10) echo 'selected=selected;'?> >octubre</option>
              <option value="11"  <?php   if ($_POST['mes']==11) echo 'selected=selected;'?> >noviembre</option>
              <option value="12"  <?php   if ($_POST['mes']==12) echo 'selected=selected;'?> >diciembre</option>
             </select>
        </td>
        <td><select name="ano" size="1" id="ano" onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;">
        <option value="2011" <?php   if ($_POST['ano']==2011) echo 'selected=selected;'?>>2011</option>
        <option value="2010" <?php   if ($_POST['ano']==2010) echo 'selected=selected;'?>>2010</option>
      </select></td>
        </tr>
      </table></td>
    <td width="132" valign="bottom"><a href="list/listado_premios_xls.php" target="_blank">
    <img src="image/Microsoft Office Excel.gif" width="25" height="25" border="0" /></a>
     <a href="list/listado_premios_xls.php" target="_blank" class="label">Generar Excel</a></td>
  </tr>
</table>


 <p>&nbsp;</p>
 <table width="290" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="4" align="center" class="textoAzulOscuro">Datos de los Ganadores</td>
  </tr>
  <tr>
    <td width="101"><table width="101" border="0" cellspacing="0">
        <tr>
          <td width="99" class="small" align="center">Mes</td>
       	<td width="98" class="small" align="center">A&ntilde;o</td>

        </tr>
        <tr>
          <td>  
            <select name="mesg" id="mesg"  onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;" >
              <option value="1" <?php   if ($_POST['mesg']==1) echo 'selected=selected;'?> >enero</option>
              <option value="2"  <?php  if ($_POST['mesg']==2) echo 'selected=selected;'?> >febrero</option>
              <option value="3" <?php   if ($_POST['mesg']==3) echo 'selected=selected;'?> >marzo</option>
              <option value="4"  <?php  if ($_POST['mesg']==4) echo 'selected=selected;'?> >abril</option>
              <option value="5"  <?php  if ($_POST['mesg']==5) echo 'selected=selected;'?> >mayo</option>
              <option value="6"  <?php  if ($_POST['mesg']==6) echo 'selected=selected;'?> >junio</option>
              <option value="7"  <?php  if ($_POST['mesg']==7) echo 'selected=selected;'?> >julio</option>
              <option value="8"  <?php  if ($_POST['mesg']==8) echo 'selected=selected;'?> >agosto</option>
              <option value="9"  <?php  if ($_POST['mesg']==9) echo 'selected=selected;'?> >setiembre</option>
              <option value="10"  <?php if ($_POST['mesg']==10) echo 'selected=selected;'?> >octubre</option>
              <option value="11"  <?php if ($_POST['mesg']==11) echo 'selected=selected;'?> >noviembre</option>
              <option value="12"  <?php if ($_POST['mesg']==12) echo 'selected=selected;'?> >diciembre</option>
             </select>
        </td>
        <td><select name="anog" size="1" id="anog" onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;">
        <option value="2011" <?php   if ($_POST['anog']==2011) echo 'selected=selected;'?>>2011</option>
        <option value="2010" <?php   if ($_POST['anog']==2010) echo 'selected=selected;'?>>2010</option>
      </select></td>
        </tr>
      </table></td>
    <td width="133" valign="bottom"><a href="list/listado_ganadores_xls.php" target="_blank">
    <img src="image/Microsoft Office Excel.gif" width="25" height="25" border="0" /></a>
     <a href="list/listado_ganadores_xls.php" target="_blank" class="label">Generar Excel</a></td>
    </tr>
</table>


 <p>&nbsp;</p>
 <table width="234" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
     <td colspan="4" align="center" class="textoAzulOscuro"><p>Generar N&ordm; de Premio</p>
      </td>
   </tr>
   <tr>
     <td width="101"><table width="101" border="0" cellspacing="0">
         <tr>
           <td width="99" class="small" align="center">Mes</td>
         </tr>
         <tr>
           <td><select name="nro_premio" id="nro_premio"  onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;" >
               <option value="1" <?php   if ($_POST['nro_premio']==1) echo 'selected=selected;'?> >enero</option>
               <option value="2"  <?php   if ($_POST['nro_premio']==2) echo 'selected=selected;'?> >febrero</option>
               <option value="3" <?php   if ($_POST['nro_premio']==3) echo 'selected=selected;'?> >marzo</option>
               <option value="4"  <?php   if ($_POST['nro_premio']==4) echo 'selected=selected;'?> >abril</option>
               <option value="5"  <?php   if ($_POST['nro_premio']==5) echo 'selected=selected;'?> >mayo</option>
               <option value="6"  <?php   if ($_POST['nro_premio']==6) echo 'selected=selected;'?> >junio</option>
               <option value="7"  <?php   if ($_POST['nro_premio']==7) echo 'selected=selected;'?> >julio</option>
               <option value="8"  <?php   if ($_POST['nro_premio']==8) echo 'selected=selected;'?> >agosto</option>
               <option value="9"  <?php   if ($_POST['nro_premio']==9) echo 'selected=selected;'?> >setiembre</option>
               <option value="10"  <?php   if ($_POST['nro_premio']==10) echo 'selected=selected;'?> >octubre</option>
               <option value="11"  <?php   if ($_POST['nro_premio']==11) echo 'selected=selected;'?> >noviembre</option>
               <option value="12"  <?php   if ($_POST['nro_premio']==12) echo 'selected=selected;'?> >diciembre</option>
             </select>
           </td>
         </tr>
        
     </table></td>																															

     <td width="133" valign="bottom"><a  target="_blank"> <img src="image/glass.png" alt="" width="25" height="25" border="0" /></a> <a href=" " target="_blank" class="label"> <input name="generar" type="button" id="generar" value="Generar" onclick="ajax_get('conformar','premio/procesar_generar_nro_premio.php','mes=<?php echo $_POST['nro_premio'] ?>')" /></a></td>
   </tr>
 
 </table>
 
  <p>&nbsp;</p>
 <table width="741" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="4" align="center" class="textoAzulOscuro">Generar Excel Consolidacion</td>
  </tr>
  <tr>
    <td width="561">
    	<table width="561" border="0" cellspacing="0">
        
        <tr>
          <td width="98" class="small" align="left">Mes Desde </td>
          <td width="66" class="small" align="left">A&ntilde;o Desde </td>
          <td width="98" class="small" align="left">Mes Hasta </td>
          <td width="82" class="small" align="left">A&ntilde;o Hasta </td>
          <td width="146" class="small" align="left">Mayores a 50000 </td>
          <td width="112" class="small" align="left">Juegos </td>
        </tr>
      <tr>
      <td>
      <select name="mes_desde" id="mes_desde"  onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;" >
        <option value="1" <?php    if ($_POST['mes_desde']==1) echo 'selected=selected;'?> >enero</option>
        <option value="2"  <?php   if ($_POST['mes_desde']==2) echo 'selected=selected;'?> >febrero</option>
        <option value="3" <?php    if ($_POST['mes_desde']==3) echo 'selected=selected;'?> >marzo</option>
        <option value="4"  <?php   if ($_POST['mes_desde']==4) echo 'selected=selected;'?> >abril</option>
        <option value="5"  <?php   if ($_POST['mes_desde']==5) echo 'selected=selected;'?> >mayo</option>
        <option value="6"  <?php   if ($_POST['mes_desde']==6) echo 'selected=selected;'?> >junio</option>
        <option value="7"  <?php   if ($_POST['mes_desde']==7) echo 'selected=selected;'?> >julio</option>
        <option value="8"  <?php   if ($_POST['mes_desde']==8) echo 'selected=selected;'?> >agosto</option>
        <option value="9"  <?php   if ($_POST['mes_desde']==9) echo 'selected=selected;'?> >setiembre</option>
        <option value="10"  <?php   if ($_POST['mes_desde']==10) echo 'selected=selected;'?> >octubre</option>
        <option value="11"  <?php   if ($_POST['mes_desde']==11) echo 'selected=selected;'?> >noviembre</option>
        <option value="12"  <?php   if ($_POST['mes_desde']==12) echo 'selected=selected;'?> >diciembre</option>
      </select>
      </td>
      
      <td><select name="ano_desde" size="1" id="ano_desde" onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;">
        <option value="2011" <?php   if ($_POST['ano_desde']==2011) echo 'selected=selected;'?>>2011</option>
        <option value="2010" <?php   if ($_POST['ano_desde']==2010) echo 'selected=selected;'?>>2010</option>
      </select></td>
      
      <td><select name="mes_hasta" id="mes_hasta"  onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;" >
        <option value="1" <?php    if ($_POST['mes_hasta']==1) echo 'selected=selected;'?> >enero</option>
        <option value="2"  <?php   if ($_POST['mes_hasta']==2) echo 'selected=selected;'?> >febrero</option>
        <option value="3" <?php    if ($_POST['mes_hasta']==3) echo 'selected=selected;'?> >marzo</option>
        <option value="4"  <?php   if ($_POST['mes_hasta']==4) echo 'selected=selected;'?> >abril</option>
        <option value="5"  <?php   if ($_POST['mes_hasta']==5) echo 'selected=selected;'?> >mayo</option>
        <option value="6"  <?php   if ($_POST['mes_hasta']==6) echo 'selected=selected;'?> >junio</option>
        <option value="7"  <?php   if ($_POST['mes_hasta']==7) echo 'selected=selected;'?> >julio</option>
        <option value="8"  <?php   if ($_POST['mes_hasta']==8) echo 'selected=selected;'?> >agosto</option>
        <option value="9"  <?php   if ($_POST['mes_hasta']==9) echo 'selected=selected;'?> >setiembre</option>
        <option value="10"  <?php   if ($_POST['mes_hasta']==10) echo 'selected=selected;'?> >octubre</option>
        <option value="11"  <?php   if ($_POST['mes_hasta']==11) echo 'selected=selected;'?> >noviembre</option>
        <option value="12"  <?php   if ($_POST['mes_hasta']==12) echo 'selected=selected;'?> >diciembre</option>
      </select>      </td>
      
      <td>
      <select name="ano_hasta" size="1" id="ano_hasta" onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;">
        <option value="2011" <?php   if ($_POST['ano_hasta']==2011) echo 'selected=selected;'?>>2011</option>
        <option value="2010" <?php   if ($_POST['ano_hasta']==2010) echo 'selected=selected;'?>>2010</option>
      
      </select>
      	
      
      </td>
      
      <td align="center">
      	<input name="mayores" id="mayores" type="checkbox" onclick="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;" <?php if($mayores==1){ $checked='checked=checked';} else { $checked='';}?> <?php echo $checked ?>/>
      
      </td>
      
      <td>
      	<select name="juego" size="1" id="juego" onchange="ajax_post('contenido','premio/elegir_mes.php',document.FrmBuscaFechaConformar);return false;">
        <option value="Todos" <?php   if ($_POST['juego']=='Todos') echo 'selected=selected;'?>>Todos</option>
        <option value="Casino" <?php   if ($_POST['juego']=='Casino') echo 'selected=selected;'?>>Casino</option>
        <option value="Otros Juegos" <?php   if ($_POST['juego']=='Otros Juegos') echo 'selected=selected;'?>>Otros Juegos</option>
      
      </select>
      
      </td>
      
      </tr>  
      </table>
      

    <td width="180" valign="bottom"><a href="list/listado_consolidado_xls.php?fecha_desde=<?php echo $fecha_desde; ?>&fecha_hasta=<?php echo $fecha_hasta; ?>&mayores=<?php echo $mayores; ?>&juego=<?php echo $juego; ?>" target="_blank">
    <img src="image/Microsoft Office Excel.gif" width="25" height="25" border="0" /></a>
     <a href="list/listado_consolidado_xls.php?fecha_desde=<?php echo $fecha_desde; ?>&fecha_hasta=<?php echo $fecha_hasta; ?>&mayores=<?php echo $mayores; ?>&juego=<?php echo $juego; ?>" target="_blank" class="label">Generar Excel</a></td>
     </tr>
</table>
 
 </div>  
</form>


