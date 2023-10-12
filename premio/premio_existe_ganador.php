<?php
 	session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_POST);
	//print_r($_GET);
	//$db->debug=true;
	$array_fecha = FechaServer();
	$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php
try {
	$rs_tipo_documento = $db->Execute("select id_tipo_documento as codigo, descripcion from PLA_AUDITORIA.t_tipo_documento
	                                   where id_tipo_documento <> 2 and id_tipo_documento <> 3 ");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
	

	
	
	
	
try {
	$rs_moneda = $db->Execute("select id_moneda as codigo, descripcion from PLA_AUDITORIA.t_moneda ");
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
	$mostrar_juego=1;
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
	

/*if (isset($_GET['documento'])) {
	$documento = $_GET['documento'];
	$condicion_documento="and g.documento = '$documento'";
	}
	else 
		{
			if (isset($_POST['documento'])) {
				$documento = $_POST['documento'];
				$condicion_documento="and g.documento = '$documento'";
				} 
			else {
				$documento = "1";
				$condicion_documento="and g.documento = '$documento'";
			}
		}
		
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
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />    
  <style type="text/css">
<!--
.style6 {font-size: 12px}
.style7 {font-weight: bold; color: #FF0000; font-family: tahoma;}
-->
  </style>
<form name="form1" id="form1" method="post" action="#" onSubmit="validar_ganador('contenido','premio/procesar_alta_premio.php',this); return false;">
          <table width="80%" border="0" align="center" cellspacing="0">
            <tr align="left">
              <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','')">Regresar</a></div></td>
            </tr>
            <tr>
              <td align="left" class="texto6" scope="row">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="1%" valign="top" scope="col"><img src="image/My-Docs.png" width="40" height="40" /></td>
                    <td align="left" scope="col"><iframe name="adjunto" frameborder="0" id="adjunto" width="100%" src="upload_ajax/index.php">&nbsp;</iframe></td>
                  </tr>
              </table>      
              </td>
            </tr>
        	<tr>
              <td align="left" scope="row"></td>
            </tr>
        	<tr>
              <td align="left" class="texto6" scope="row">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="1%" scope="col"><table width="100%" border="0" cellspacing="1" cellpadding="0">
        
            <tr align="left">
              <td colspan="6" scope="row">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="textoRojo" scope="col">&nbsp;</td>
                    <td align="center" class="small_derecha" scope="col">
                    <a href="#" onclick="ajax_get('contenido','premio/agregar_premio.php',''); return false;"><img src="image/24px-Crystal_Clear_action_filenew.png" border="0" alt="Alta de datos personales" width="16" height="16" />  Blanquear Formulario</a></td>
                  </tr>
                  <tr>
                    <td width="20" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></td>
                    <td align="center" class="td4" scope="col"><a href="#">DATOS PERSONALES DEL GANADOR</a></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td width="15%" align="right" class="texto5" scope="row"><div align="right">Tipo de Doc (1) </div></td>
              <td width="2%" class="td_detalle"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',''); ?>&nbsp;</td>
              <td width="10%" class="texto5">Fecha Alta</td>
              <td colspan="3" class="texto5"><span class="td_detalle"><span class="td2">
                <?php  abrir_calendario('fecha','premio', $fecha); ?>
              </span></span></td>
              </tr>
            <tr valign="top">
              <td align="right" class="texto5" scope="row">Documento (1) </td>
              <td colspan="5" class="td_detalle"><input name="documento" type="text" class="small_derecha" id="documento" size="13" maxlength="13"   />
                <label>
                <!--<input type="button" name="validardni" id="validardni" value="Validar Ganador" onclick="ajax_post('contenido','premio/agregar_premio.php',this); return false;"/> -->
                </label>              
                <span class="texto4Totales">(1) Identificacion del cliente (Tipo de documento y numero y en caso de existir CUIT; CUIL; CDI)</span></td>
            </tr>
            
            <tr>
              <td align="right" class="texto5" scope="row">CUIT/CUIL:</td>
              <td colspan="5" class="td_detalle"><span class="texto4Totales">
                <input name="cuit" type="text" class="small" id="cuit" size="13" maxlength="13" value="<?php echo $row->CUIT; ?>" />
(2) Sin punto ni guion</span> <span class="smallRojo">
<label><a href="#" class="smallRojo"  onclick="window.open ('http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx','anses')";><strong>verificar cuit</strong></a></label>
</span> <span class="texto4Totales">
<label> </label>
<img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span><span class="texto4Totales"></span></td>
            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Nombre</td>
              <td colspan="3" scope="row"><span class="td_detalle">
                <input name="nombre" type="text" class="small" id="nombre" size="50" maxlength="50" />
              </span></td>
              <td width="12%" scope="row"><div align="right"><span class="td_detalle"><span class="texto5">Apellido</span></span></div></td>
              <td width="33%" scope="row"><span class="td_detalle">
                <input name="apellido" type="text" class="small" id="apellido" size="50" maxlength="50" />
              </span></td>
              </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Nacionalidad</td>
              <td colspan="5" align="right" class="texto5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  
                  <td width="18%"><span class="td_detalle">
                    <input name="nacionalidad" type="text" class="small" id="nacionalidad" value="Argentina" size="20" maxlength="20" />
                  </span></td>
                  <td width="11%" align="right" class="texto5">Profesion</td>
                  <td width="27%"><span class="texto4Totales"><span class="td_detalle">
                    <input name="profesion" type="text" class="small" id="profesion" size="50" maxlength="50" />
                  </span></span></td>
                  <td width="28%"><span class="texto4Totales"><span class="td_detalle">Profesion / Actividad desarrollada</span></span></td>
                </tr>
              </table></td>
              </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Domicilio Real</td>
              <td colspan="5" scope="row"><span class="td_detalle">
                <input name="domicilio" type="text" class="small" id="domicilio" size="100" maxlength="100" />
              </span></td>
            </tr>
            <tr align="left"><td colspan="6">
                 <table width="100%" border="1" cellspacing="0">
                <tr align="center">
                  <td width="34%" align="center" valign="middle" class="texto5"><div align="center">Provincia</div></td>
                  <td width="32%" class="texto5"><div align="center">Localidad</div></td>
                  <td width="25%" class="texto5">Codigo Postal</td>
                  <td width="9%" class="texto5">Extranjero</td>
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
                     </div></td>
                  <td>
                      <div align="center">
                        <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/localidades_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia.value,this);  return false; } "/>
                        <input name="localidad" type="text" class="tdVerde" id="localidad" readonly="yes" value="Capital"/>
                        <input type="hidden" name="cod_localidad" id="cod_localidad" value="22121"/>
                        <input name="localidad_memo" type="hidden" id="localidad_memo" value="Capital" />
                        <input name="cod_localidad_memo" type="hidden" id="cod_localidad_memo" value="22121" />
                      </div></td>
                  <td><span class="texto3">
                    <input name="cod_postal" type="text" align="center" class="small" id="cod_postal" size="15" maxlength="13" value="<?php echo $row->COD_POSTAL; ?>" />
                    <span class="style6"> <span class="style7">
                    <label></label>
                    </span></span><a href="#" class="smallRojo"  onclick="window.open ('http://www.correoargentino.com.ar/consulta_cpa/cons2.php','')";>Cod Postal </a><img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></td>
                  <td align="center"><input type="checkbox" name="chk_extranjero" id="chk_extranjero" onclick="if (this.checked){
                  																												form1.provincia.value=''; form1.cod_provincia.value=0;
                                                                                                                                form1.localidad.value=''; form1.cod_localidad.value=0;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 } else {
                                                                                                                 				form1.provincia.value=form1.provincia_memo.value; form1.cod_provincia.value=form1.cod_provincia_memo.value;                                                                                                             form1.localidad.value=form1.localidad_memo.value; form1.cod_localidad.value=form1.cod_localidad_memo.value;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 }" />				  </td>
                </tr>
              </table> </td> 
            </tr>
           
                    </table>            </td>
                  </tr>
              </table>      </td>
            </tr>
            <tr>
              <td align="left" class="texto6" scope="row">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                    <td scope="col"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                      <tr>
                        <td scope="col"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr align="left">
                              <td colspan="6" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <th width="20" align="left" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></th>
                                    <td align="center" class="td8" scope="col"><a href="#">DATOS  DEL PAGADOR</a></td>
                                  </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td width="82" align="right" class="texto5" scope="row">Valor del Premio</td>
                              <td width="2" align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" class="td_detalle"><input name="valor_premio" type="text" class="small_derecha" id="valor_premio" size="14" maxlength="14" /></td>
                            </tr>
                            <tr valign="top">
                              <td align="right" class="texto5" scope="row">Moneda</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" class="td_detalle"><?php echo armar_combo($rs_moneda,'id_moneda',''); ?><span class="texto4Totales">Moneda en la cual se paga el premio</span></td>
                            </tr>
                            <tr>
                              <td align="right" class="texto5" scope="row">Concepto</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td width="262" valign="middle" class="td_detalle"><input name="concepto" type="text" class="small" id="concepto" size="50" maxlength="100" />                              </td>
                              <td width="191" class="td_detalle"><table width="93%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                              <td width="46" align="right" class="td_detalle"><span class="texto5">Juego</span></td>
                                              <td width="143" class="td_detalle"><?php echo armar_combo($rs_juego,'juego',''); ?> </td>
                                </tr>
                                <tr>
                                  <td width="46" align="right" class="texto5">Sorteo Nro</td>
                                  <td width="143" align="left" class="texto5"><input name="sorteo_nro" id="sorteo_nro" class="small" /></td>
                                </tr>
                              </table></td>
                              <td width="142" class="td_detalle"><span class="texto4Totales">Descripcion del premio</span></td>
                              
                              
                          </tr>
                            
                            <tr>
                              <td align="right" class="texto5" scope="row">Instrumento de pago</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" class="td_detalle"><?php echo armar_combo($rs_tipo_pago,'id_tipo_pago',''); ?></td>
                          </tr>
                            <tr>
                              <td align="right" class="texto5" scope="row">Domicilio</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="2" class="td_detalle"><input name="domicilio_pago" type="text" class="small" id="domicilio_pago" size="70" maxlength="70" value="<?php echo $row->DIRECCION ?>" /></td>
                              <td  align="left" class="texto4Totales" colspan="2">Domicilio donde se adjudico el premio</td>
                            </tr>
                            <tr align="left">
                              <td align="right" valign="top" class="texto5" scope="row">Cuenta bancaria</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr valign="top">
                                    <th height="52" scope="col"><span class="td_detalle">
                                    <input name="cuenta_bancaria" type="text" class="small" id="cuenta_bancaria" size="50" maxlength="50" value="<?php echo $row1->CUENTA_BANCARIA ?>" />
                                    </span></th>
                                    <th align="left" scope="col"><span class="texto4Totales">Cuenta bancaria de la entidad que paga el premio y numero de cheque o detalle de transferencia (para el caso de MEP/SNP) con el cual se hace efectivo el mismo.</span></th>
                                  </tr>
                              </table></td>
                            </tr>
                            <tr align="left">
                              <td align="right" valign="top" class="texto5" scope="row">Cheque Nro</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td><input name="cheque_nro" type="text" class="small" id="cheque_nro" size="40" /></td>
                                  <td class="texto4Totales">Nro de Cheque (en caso de corresponder).</td>
                                </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
              </table>      
              </td>
            </tr>
            <tr>
              <td align="left" class="texto6" scope="row">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td scope="col">
                    <table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr align="left">
              <td colspan="3" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <th width="20" align="left" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></th>
                    <td align="center" class="td8" scope="col"><a href="#">DATOS PERSONALES DE OTRAS PERSONAS</a></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td width="160" align="right" class="texto5" scope="row">Tipo de documento (2)</td>
              <td width="2" align="right" class="texto5" scope="row">&nbsp;</td>
              <td width="684" class="td_detalle"><?php $rs_tipo_documento->MoveFirst(); echo armar_combo($rs_tipo_documento,'id_tipo_documento2',''); ?></td>
            </tr>
            <tr valign="top">
              <td align="right" class="texto5" scope="row">Documento (2)</td>
              <td align="right" class="texto5" scope="row">&nbsp;</td>
              <td class="td_detalle"><input name="documento2" type="text" class="small" id="documento2" size="13" maxlength="13" /></td>
            </tr>
            <tr>
              <td align="right" class="texto5" scope="row">Apellido (2)</td>
              <td align="right" class="texto5" scope="row">&nbsp;</td>
              <td class="td_detalle"><input name="apellido2" type="text" class="small" id="apellido2" size="50" maxlength="50" /></td>
            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Nombre (2)</td>
              <td align="right" class="texto5" scope="row">&nbsp;</td>
              <td scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="top">
                    <th scope="col"><span class="td_detalle">
                      <input name="nombre2" type="text" class="small" id="nombre2" size="50" maxlength="50" />
                    </span></th>
                    <th align="left" scope="col">&nbsp;</th>
                    <th align="left" scope="col"><span class="td_detalle"><span class="texto4Totales">(2) Nombre y apellido y documento de identidad (tipo y numero), de las personas a nombre de quienes se extiende el instrumento financiero, cuando el mismo no se hubiere extendido a la orden del supuesto ganador.</span></span></th>
                  </tr>
                </table>        </td>
            </tr>
                    </table></td>
                  </tr>
              </table>      </td>
            </tr>
            <tr align="left">
              <td align="center" scope="row">&nbsp;</td>
            </tr>
            <tr align="left">
              <td align="center" scope="row"><input name="button" type="submit" class="textoAzulOscuro" id="button" value="Guardar" /></td>
            </tr>
            <tr align="left">
              <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','')">Regresar</a></div></td>
            </tr>
        </table>
        </form>
