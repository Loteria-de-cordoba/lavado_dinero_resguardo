<?php session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_POST);
	//print_r($_GET);
	//print_r($_SESSION);
	//$db->debug=true;
	$array_fecha = FechaServer();
	$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

	$fecha_desde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	$fecha_hasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php

/*if ($_SESSION['suc_ban']==76){
	$_SESSION['suc_ban']=80;
}
*/
$nombre='';
$apellido='';
$documento='';
$abm='1';

try {
	$rs_tipo_documento = $db->Execute("SELECT id_tipo_documento AS codigo,  descripcion
										FROM PLA_AUDITORIA.t_tipo_documento
										WHERE id_tipo_documento NOT IN (2,3,4)");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
	
try {
	$rs_tipo_documento2 = $db->Execute("select id_tipo_documento as codigo, descripcion from PLA_AUDITORIA.t_tipo_documento where id_tipo_documento <> 2 and id_tipo_documento <> 3 ");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}

	
try {
	$rs_moneda = $db->Execute("select id_moneda as codigo, descripcion from PLA_AUDITORIA.t_moneda");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
try {
	$rs_tipo_pago = $db->Execute("select id_tipo_pago as codigo, descripcion from PLA_AUDITORIA.t_tipo_pago ");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
$mostrar_juego=0;
//if ($_SESSION['suc_ban']==1 || $_SESSION['suc_ban']==27|| $_SESSION['suc_ban']==23|| $_SESSION['suc_ban']==25|| $_SESSION['suc_ban']==34|| $_SESSION['suc_ban']==26|| $_SESSION['suc_ban']==30|| $_SESSION['suc_ban']==20|| $_SESSION['suc_ban']==21|| $_SESSION['suc_ban']==31|| $_SESSION['suc_ban']==24|| $_SESSION['suc_ban']==33|| $_SESSION['suc_ban']==22|| $_SESSION['suc_ban']==32){
	try {
		$rs_juego = $db->Execute("select id_juegos as codigo,juegos as descripcion from juegos.juegos where activo = 1 order by 2 ");
		}
		catch  (exception $e) 
		{ 
		die($db->ErrorMsg());
		}
    $juegos=25;
	$mostrar_juego=2;
//}

try {
	$rs_suc_ban = $db->Execute("select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
	
try {
	$rs_cod_ticket = $db->Execute("SELECT casa as codigo, cod_mov_caja as descripcion
								FROM casino.t_reg_cp a
								WHERE importe_ficha >= 10000
								and anulado like 'N'
								and fecha_alta between to_date('$fecha_desde','DD/MM/YYYY HH24:MI') and to_date('$fecha_hasta','DD/MM/YYYY HH24:MI')
								and cargado=0
								and cod_casa=?
								ORDER BY cod_mov_caja", array($_SESSION['suc_ban']));
	
	
	/*select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));*/
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
	

if (isset($_GET['documento'])) {
	$documento = $_GET['documento'];
	//$condicion_documento="and g.documento = '$documento'";
	}
	else 
		{
			if (isset($_POST['documento'])) {
				$documento = $_POST['documento'];
				//$condicion_documento="and g.documento = '$documento'";
				} 
			else {
				$documento = "";
				//$condicion_documento="and g.documento = '$documento'";
			}
		}
		
if (isset($_POST['casino'])) {
			$casino= $_POST['casino'];
			
		}elseif (isset($_GET['casino'])) {
			$casino = $_GET['casino'];
		}	

if (isset($_POST['nombre'])) {
			$nombre= $_POST['nombre'];
			
		}elseif (isset($_GET['nombre'])) {
			$nombre = $_GET['nombre'];
		}	

if (isset($_POST['apellido'])) {
			$apellido= $_POST['apellido'];
			
		}elseif (isset($_GET['apellido'])) {
			$apellido = $_GET['apellido'];
		}	
//llevo a delegacion
	if($casino==0)
	{
		$casino=100;
	}		
if (isset($_POST['fecha_inicio'])) {
			$fecha_inicio= $_POST['fecha_inicio'];
			
		}elseif (isset($_GET['fecha_inicio'])) {
			$fecha_inicio = $_GET['fecha_inicio'];
		}		
if (isset($_POST['fhasta'])) {
			$fhasta= $_POST['fhasta'];
			
		}elseif (isset($_GET['fhasta'])) {
			$fhasta = $_GET['fhasta'];
		}	
/*		
try {
	$rs_consulta = $db->Execute("select g.id_ganador,g.id_tipo_documento, g.documento, g.apellido, g.nombre, g.nacionalidad, g.domicilio,g.cheque_nro, 
								g.profesion, g.valor_premio, g.id_moneda, m.descripcion, g.concepto, g.juego, g.id_tipo_pago, to_char(g.fecha_alta,'DD/MM/YYYY') as fecha_alta, 
								g.domicilio_pago, g.cuenta_bancaria_salida, g.documento2, g.apellido2, g.nombre2, g.id_localidad,
                				td.descripcion, lo.n_localidad, pro.n_provincia, pa.n_pais, pa.id_pais, pro.id_provincia
								from PLA_AUDITORIA.t_ganador g, PLA_AUDITORIA.t_tipo_documento td, 
								PLA_AUDITORIA.t_moneda m, PLA_AUDITORIA.t_tipo_pago tp,
                				administrativo.t_paises pa, administrativo.t_provincias pro, administrativo.t_localidades lo                
								where g.id_tipo_documento = td.id_tipo_documento
								and g.id_moneda= m.id_moneda
								and g.id_tipo_pago= tp.id_tipo_pago
                				and g.id_localidad= lo.id_localidad(+)
                				and lo.id_provincia= pro.id_provincia(+)
                				and pro.id_pais= pa.id_pais(+)
								$condicion_documento");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}

$row=$rs_consulta->FetchNextObject($toupper=true);
if ($row->ID_LOCALIDAD==0){
    $pais=''; 
	$cod_pais=0;
    $provincia=''; 
	$cod_provincia=0;
    $localidad=''; 
	$cod_localidad=0;
    $check='checked=checked';
} else {
	$pais=$row->N_PAIS; 
	$cod_pais=$row->ID_PAIS;
	$provincia=$row->N_PROVINCIA; 
	$cod_provincia=$row->ID_PROVINCIA ;
    $localidad=$row->N_LOCALIDAD ; 
	$cod_localidad=$row->ID_LOCALIDAD;
	$check='';
}


*/


$row1 = $rs_suc_ban->FetchNextObject($toupper=true);
?>

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />    
  <style type="text/css">
<!--
.style1 {
	color: #006600;
	font-family: Arial, Helvetica, sans-serif;
}
.style5 {font-size: 10px}
-->
  </style>
  <form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','cliente/procesar_alta_premio.php',this); return false;">
 <table width="80%" border="0" align="center" cellspacing="0">
 
  	  	<tr align="left"><td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_cliente.php','casino=<?php echo $casino;?>')">Regresar</a></div></td></tr>
  		<tr><td align="left" class="texto6" scope="row">
    	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        	    <tr>
            	    <td width="1%" valign="top" scope="col"><img src="image/My-Docs.png" width="40" height="40" /></td>
                    <td align="left" scope="col">&nbsp;</td>
       	      </tr>
            </table>      
        </td>
    </tr>
    <tr>
    	<td align="left" scope="row">&nbsp;</td>
    </tr>
    <tr>
    	<td align="left" class="texto6" scope="row">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
       			<tr>
               	  <td width="1%" scope="col"><table width="100%" border="0" cellspacing="1" cellpadding="0">
        				<tr align="left">
              		<td colspan="7" scope="row">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="textoRojo" scope="col">&nbsp;</td>
                    <td align="center" class="small_derecha" scope="col">
                    <a href="#" onclick="ajax_get('contenido','cliente/agregar_premio.php','&documento=<?php echo $documento;?>&casino=<?php echo $casino;?>'); return false;"><img src="image/24px-Crystal_Clear_action_filenew.png" border="0" alt="Formulario Original" width="16" height="16" />  Blanquear Formulario</a></td>
                  </tr>
                  
                  
                  <tr>
                  
                    <td width="20" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></td>
                    <td align="center" class="textoAzulOscuroFondo" scope="col">INGRESE DATOS  DEL APOSTADOR</td>
                     <input type="hidden" name="casino" id="casino" value="<?php echo $casino; ?>"/>
                     <?php if(isset($_POST['fecha_inicio']))
					 {?>
                     <input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $_POST['fecha_inicio']; ?>" />
                     <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_POST['fhasta']; ?>" />
                     <?php
					 }
					 else
					 {?>
                     <input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $_GET['fecha_inicio']; ?>" />
                     <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta']; ?>" />
					 <?php }?>
                  </tr>
                  <?php if(isset($_GET['mensaje'])){?>
     <tr>
          <td colspan="9" style="border-color:#6600FF;border:solid" align="center" valign="bottom" class="smallRojo" scope="col"><div id="mensajito"><?php echo $_GET['mensaje'];?></div></td>
    </tr>
    <?php }?>
              	</table></td>
            	</tr>
                 <tr>
              <td align="left" class="smallVerde" scope="row" >Apellido y Nombre/Apodo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td class="td_detalle" colspan="3"><input name="apellido" type="text" class="small" id="apellido" size="100" maxlength="100" value="<?php echo $apellido;?>" onchange="ajax_get('apoapo','cliente/controla_apodo.php','casino=<?php echo $casino?>&apodo='+this.value+'&fecha_inicio=<?php echo $fecha_inicio;?>&fhasta=<?php echo $fhasta;?>'); form1.documento.focus();" />
              <!--<td colspan="3" class="smallVerde" >Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="nombre" type="text" class="small" id="nombre" size="50" maxlength="50" value="<?php// echo $nombre;?>"/></td>
              <td class="td_detalle">&nbsp;</td>
              <td class="td_detalle">&nbsp;</td>-->
               <div id="apoapo">&nbsp;</div></td>
              <td  align="right" class="smallRojo" colspan="5">Apellido y nombre separado con espacio</td>
            </tr>
                    <tr>
              <td width="14%" align="right" class="smallVerde" scope="row"><div align="left">Tipo de documento:</div></td>
              <td class="td_detalle"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',''); ?></td>
              <td colspan="5" align="left" class="td_detalle"><span class="smallVerde">Nro. Documento</span><span class="smallRojo">:
                  <input name="documento" type="text" id="documento" class="small" size="13" maxlength="13" value="<?php echo $documento;?>"  onchange="ajax_get('docudocu','cliente/controla_docu.php','casino=<?php echo $casino?>&documento='+this.value+'&tipo='+form1.id_tipo_documento.value+'&fecha_inicio=<?php echo $fecha_inicio;?>&fhasta=<?php echo $fhasta;?>&abm=<?php echo $abm;?>&apodo='+form1.apellido.value); form1.fecha.focus();"/>
              </span>
              <div id="docudocu">&nbsp;</div></td>
              </tr>
               
                
<tr>
                          <td align="left" class="smallVerde" scope="row">Fecha de Alta:</td>
                          <td width="22%" align="left" class="smallVerde"><?php  abrir_calendario('fecha','nacimiento', $fecha);?>                          </td>
                <td width="9%" align="left" class="smallVerde">Domicilio:</td>
        <td colspan="2" align="left" class="smallVerde">
                          <input name="lugar_nacimiento" type="text" class="small" id="lugar_nacimiento" size="50" maxlength="50" />                          </td>
                          <td width="3%" class="smallVerde" align="right">Sexo:</td>
                          <td width="9%" class="texto5">
                          <select name="sexo" id="sexo" class="small">
                          	<option value="Masculino">Masculino</option>
                             <option value="Femenino">Femenino</option>
                          </select>                          </td>
                    </tr>
           
                      <tr>
                        <td align="left" class="smallVerde" scope="row">CUIT/CUIL/CDI:&nbsp;&nbsp;</td>
                        <td colspan="6" class="smallRojo"><input name="cuit" type="text" class="small" id="cuit" size="13" maxlength="13" />
                          Sin punto ni guion<span class="smallRojo">
                            <label><a href="#" class="smallRojo"  onclick="window.open ('http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx','anses')";><strong>verificar cuit</strong></a></label>
                            </span>
                          <label> </label>
                          <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></td>
                      </tr>
            
            
            <tr align="left">
              <td align="left" class="smallVerde" scope="row">Nacionalidad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td colspan="6" align="right" class="texto5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  
                  <td width="11%"><span class="td_detalle">
                    <input name="nacionalidad" type="text" class="small" id="nacionalidad" value="Argentina" size="20" maxlength="20" />
                  </span></td>
                  <td width="18%">&nbsp;</td>
                  <td width="7%" align="right" class="smallVerde">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Profesion:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td width="64%" class="smallRojo" align="left"> <input name="profesion" type="text" class="small" id="profesion" size="30" maxlength="50" />
                 Profesion / Actividad desarrollada / Oficio</td>
                  </tr>
              </table></td>
              </tr>
           
            <tr align="left">
           <!-- <td align="right" class="smallVerde" scope="row">DDJJ Nro:</td>-->
              <td colspan="9" scope="row" class="smallVerde"><!--<input name="ddjj" class="small" type="text"  id="ddjj" size="3" maxlength="4" />-->Estado civil:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="estado_civil" class="small" type="text"  id="estado_civil" size="20" maxlength="20" />
                <span class="smallVerde">Telefono:</span>                <input name="telefono" class="small" type="text" id="telefono" size="20" maxlength="20" />
                <span class="smallVerde">Email:
                <input name="email" class="small" type="text" id="email" size="50" maxlength="50"/>
                </span></td></tr>
                  <tr align="left">
                  <td colspan="9" scope="row" class="smallVerde">Observacion:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="observacion" class="small" type="text" id="observacion" size="100" maxlength="100"/>
              </td>
              
              </tr>
            <tr align="left"><td colspan="7">
                 <table width="100%" border="1" cellspacing="0">
                <tr align="center">
                  <td width="39%" align="center" valign="middle" class="smallVerde"><div align="center">Provincia</div></td>
                  <td width="35%" class="smallVerde"><div align="center">Localidad</div></td>
                  <td width="14%" class="smallVerde" colspan="2">Codigo Postal</td>
                  <td width="12%" class="smallVerde">Extranjero</td>
                </tr>
                <tr>
                  <td >
                    <div align="center">
                       <input name="button3" type="image" id="button3" src="image/folder_16.png" alt="Seleccionar provincia" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/provincias_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_pais.value,this);  return false; }"/>
                       <input name="provincia" type="text" class="tdVerde" id="provincia" value="Cordoba" readonly="yes"/>
                       <input type="hidden" name="cod_provincia" id="cod_provincia" value="6"/>
                       <input name="provincia_memo" type="hidden" id="provincia_memo" value="Cordoba" />
                       <input name="cod_provincia_memo" type="hidden" id="cod_provincia_memo" value="6" />
                       <input type="hidden" name="cod_pais" id="cod_pais" value="1"/>
                        <input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio; ?>" />
                     <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $fhasta; ?>" />
                     </div></td>
                  <td>
                      <div align="center">
                        <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/localidades_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia.value,this);  return false; } "/>
                        <input name="localidad" type="text" class="tdVerde" id="localidad" readonly="yes" value="Capital"/>
                        <input type="hidden" name="cod_localidad" id="cod_localidad" value="22121"/>
                        <input name="localidad_memo" type="hidden" id="localidad_memo" value="Capital" />
                        <input name="cod_localidad_memo" type="hidden" id="cod_localidad_memo" value="22121" />
                      </div></td>
                  <td align="center" colspan="2"><span class="texto3">
                    <input name="cod_postal" type="text" align="center" class="small" id="cod_postal" size="15" maxlength="13" />
                    <span class="texto4Totales"><span class="smallRojo">
                    <label><a href="#" class="smallRojo"  onclick="window.open ('http://www.correoargentino.com.ar/consulta_cpa/cons2.php','')";><strong>verificar CP</strong></a></label>
                    </span>
                    <label> </label>
                    <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></span></td>
                  <td align="center"><input type="checkbox"  name="chk_extranjero" id="chk_extranjero" onclick="if (this.checked){
                  																												form1.provincia.value=''; form1.cod_provincia.value=0;
                                                                                                                                form1.localidad.value=''; form1.cod_localidad.value=0;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 } else {
                                                                                                                 				form1.provincia.value=form1.provincia_memo.value; form1.cod_provincia.value=form1.cod_provincia_memo.value;
                                                                                                                                form1.localidad.value=form1.localidad_memo.value; form1.cod_localidad.value=form1.cod_localidad_memo.value;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 }" />				  </td>
                </tr>
                </table>
                </td>
                </tr>
                
              </table> </td> 
            </tr>
            
            <tr align="left">
              <td align="center" scope="row" colspan="8"><input name="button" type="submit" class="textoAzulOscuro" id="button" value="Guardar" /></td>
            </tr>
            <tr align="left">
              <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_cliente.php','casino=<?php echo $casino;?>')">Regresar</a></div></td>
            </tr>
             </table>             	  
       			</form>