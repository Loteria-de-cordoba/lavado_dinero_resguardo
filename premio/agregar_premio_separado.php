<?php
 	session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_SESSION);
	$array_fecha = FechaServer();
	$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php
try {
	$rs_tipo_documento = $db->Execute("select id_tipo_documento as codigo, descripcion from PLA_AUDITORIA.t_tipo_documento");
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
if ($_SESSION['suc_ban']==1 || $_SESSION['suc_ban']==27|| $_SESSION['suc_ban']==23|| $_SESSION['suc_ban']==25|| $_SESSION['suc_ban']==34|| $_SESSION['suc_ban']==26|| $_SESSION['suc_ban']==30|| $_SESSION['suc_ban']==20|| $_SESSION['suc_ban']==21|| $_SESSION['suc_ban']==31|| $_SESSION['suc_ban']==24|| $_SESSION['suc_ban']==33|| $_SESSION['suc_ban']==22|| $_SESSION['suc_ban']==32){
	try {
		$rs_juego = $db->Execute("select juegos as codigo,juegos as descripcion from juegos.juegos ");
		}
		catch  (exception $e) 
		{ 
		die($db->ErrorMsg());
		}
	$mostrar_juego=1;
}

try {
	$rs_suc_ban = $db->Execute("select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}

$row = $rs_suc_ban->FetchNextObject($toupper=true);
?>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />    
<?php if ($_SESSION['suc_ban']==1 || $_SESSION['suc_ban']==27|| $_SESSION['suc_ban']==23|| $_SESSION['suc_ban']==25|| $_SESSION['suc_ban']==34|| $_SESSION['suc_ban']==26|| $_SESSION['suc_ban']==30|| $_SESSION['suc_ban']==20|| $_SESSION['suc_ban']==21|| $_SESSION['suc_ban']==31|| $_SESSION['suc_ban']==24|| $_SESSION['suc_ban']==33|| $_SESSION['suc_ban']==22|| $_SESSION['suc_ban']==32){?>
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
              <td colspan="7" scope="row">
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
              <td width="11%" align="right" class="texto5" scope="row"><div align="right">Tipo de Doc (1) </div></td>
              <td width="10%" class="td_detalle"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',''); ?>&nbsp;</td>
              <td width="10%" class="texto5">Fecha Alta</td>
              <td colspan="4" class="texto5"><span class="td_detalle"><span class="td2">
                <?php  abrir_calendario('fecha','premio', $fecha); ?>
              </span></span></td>
              </tr>
            <tr valign="top">
              <td align="right" class="texto5" scope="row">Documento (1) </td>
              <td colspan="6" class="td_detalle"><input name="documento" type="text" class="small_derecha" id="documento" size="13" maxlength="13" />
                <span class="texto4Totales"> (1) Identificacion del cliente (Tipo de documento y numero sin punto ni guion)</span></td>
            </tr>
            <tr>
              <td align="right" class="texto5" scope="row">CUIT/CUIL:</td>
              <td colspan="6" class="td_detalle"><span class="texto4Totales">
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
              <td width="8%" scope="row"><div align="right"><span class="texto5">Apellido</span></div></td>
              <td width="35%" scope="row"><span class="td_detalle">
                <input name="apellido3" type="text" class="small" id="apellido3" size="50" maxlength="50" />
              </span></td>
            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Nacionalidad</td>
              <td colspan="6" scope="row"><span class="td_detalle">
                <input name="nacionalidad" type="text" class="small" id="nacionalidad" value="Argentina" size="20" maxlength="20" />
              </span></td>
            </tr>
            <tr align="left"><td colspan="7">
                 <table width="100%" border="1" cellspacing="0">
                <tr align="center">
                  <td width="35%" align="center" valign="middle" class="texto5"><div align="center">Provincia</div></td>
                  <td width="32%" class="texto5"><div align="center">Localidad</div></td>
                  <td width="24%" class="texto5">Cod Postal</td>
                  <td width="9%" class="texto5">Extranjero</td>
                </tr>
                <tr>  fdjghdkfghdkjfgh
                  <td >
                    <div align="center">
                       <input name="button3" type="image" id="button3" src="image/folder_16.png" alt="Seleccionar provincia" width="16" height="16" onclick="ajax_showTooltip('premio/provincias_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_pais.value,this);  return false; "/>
                       <input name="provincia" type="text" class="tdVerde" id="provincia" value="Cordoba" readonly="yes"/>
                       <input type="hidden" name="cod_provincia" id="cod_provincia" value="6"/>
                       <input name="provincia_memo" type="hidden" id="provincia_memo" value="Cordoba" />
                       <input name="cod_provincia_memo" type="hidden" id="cod_provincia_memo" value="6" />
                       <input type="hidden" name="cod_pais2" id="cod_pais2" value="1"/>
                     </div></td>
                  <td>
                      <div align="center">
                        <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="ajax_showTooltip('premio/localidades_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia.value,this);  return false; "/>
                        <input name="localidad" type="text" class="tdVerde" id="localidad" readonly="yes" value="Capital"/>
                        <input type="hidden" name="cod_localidad" id="cod_localidad" value="22121"/>
                        <input name="localidad_memo" type="hidden" id="localidad_memo" value="Capital" />
                        <input name="cod_localidad_memo" type="hidden" id="cod_localidad_memo" value="22121" />
                      </div></td>
                 <td><span class="texto3">
                          <input name="cod_postal" type="text" align="center" class="small" id="cod_postal" size="15" maxlength="13" value="<?php echo $row->COD_POSTAL; ?>" />
                          
                          <span class="style6">
                          <span class="style7">
                          <label></label>
                          </span></span><a href="#" class="smallRojo"  onclick="window.open ('http://www.correoargentino.com.ar/consulta_cpa/cons2.php','Cod Postal')";>Cod Postal </a><img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></td>
                  <td align="center"><input type="checkbox" name="chk_extranjero" id="chk_extranjero" onclick="if (this.checked){
                  																												form1.provincia.value=''; form1.cod_provincia.value=0;
                                                                                                                                form1.localidad.value=''; form1.cod_localidad.value=0;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 } else {
                                                                                                                 				form1.provincia.value=form1.provincia_memo.value; form1.cod_provincia.value=form1.cod_provincia_memo.value;
                                                                                                                                form1.localidad.value=form1.localidad_memo.value; form1.cod_localidad.value=form1.cod_localidad_memo.value;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 }" />				  </td>
                </tr>
              </table> </td> 
            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Domicilio Real</td>
              <td colspan="6" scope="row"><span class="td_detalle">
                <input name="domicilio" type="text" class="small" id="domicilio" size="100" maxlength="100" />
              </span></td>
            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Profesion</td>
              <td colspan="6" scope="row"><span class="texto4Totales"><span class="td_detalle">
                <input name="profesion" type="text" class="small" id="profesion" size="50" maxlength="50" />
              </span>Profesion / Actividad desarrollada</span></td>
            </tr>
                    </table>            </td>
                  </tr>
              </table>      </td>
            </tr>
            <tr>
              <td align="left" class="texto6" scope="row">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  
                  <tr>
                    <td scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td scope="col"><table width="106%" border="0" cellspacing="1" cellpadding="0">
                            <tr align="left">
                              <td colspan="5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <th width="20" align="left" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></th>
                                    <td align="center" class="td8" scope="col"><a href="#">DATOS  DEL PAGADOR</a></td>
                                  </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td width="72" align="right" class="texto5" scope="row">Valor del Premio</td>
                              <td width="3" align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="3" class="td_detalle"><input name="valor_premio2" type="text" class="small_derecha" id="valor_premio2" size="14" maxlength="14" />                                <div align="center"></div></td>
                            </tr>
                            <tr valign="top">
                              <td align="right" class="texto5" scope="row">Moneda</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="3" class="td_detalle"><?php echo armar_combo($rs_moneda,'id_moneda',''); ?><span class="texto4Totales">Moneda en la cual se paga el premio</span></td>
                            </tr>
                            <tr>
                              <td align="right" class="texto5" scope="row">Concepto</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="3" class="td_detalle"><input name="concepto2" type="text" class="small" id="concepto2" size="50" maxlength="100" />
                              <span class="texto4Totales">Descripcion del premio</span></td>
                            </tr>
                            <tr>
                              <td align="right" class="texto5" scope="row">Instrumento de pago</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td width="145" class="td_detalle"><?php echo armar_combo($rs_tipo_pago,'id_tipo_pago',''); ?></td>
                           
							<?php if ($mostrar_juego==1) {?>  
                              <td width="85" class="td_detalle"><span class="texto5">Juego</span></td>
                              <td width="56" class="td_detalle"><?php echo armar_combo($rs_juego,'juego',''); ?></td>
                      <?php }else{?>
                            	<td width="19" colspan="2" class="td_detalle">
                              <input name="juego" type="hidden" id="juego" value="CASINO" />                                </td>
                            <?php }?>
                            </tr>
                            <tr>
                              <td align="right" class="texto5" scope="row">Domicilio</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td class="td_detalle"><input name="domicilio_pago2" type="text" class="small" id="domicilio_pago2" size="70" maxlength="70" value="<?php echo $row->DIRECCION ?>" /></td>
                              <td colspan="2" class="td_detalle"><div align="left"><span class="texto4Totales">Domicilio donde se adjudico el premio</span></div></td>
                            </tr>
                            
                            <tr align="left">
                              <td align="right" valign="top" class="texto5" scope="row">Cuenta bancaria</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="3" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr valign="top">
                                    <th height="52" scope="col"><span class="td_detalle">
                                    <input name="cuenta_bancaria2" type="text" class="small" id="cuenta_bancaria2" size="50" maxlength="50" value="<?php echo $row->CUENTA_BANCARIA ?>" />
                                    </span></th>
                                    <th align="left" scope="col"><span class="texto4Totales">Cuenta bancaria de la entidad que paga el premio y numero de cheque o detalle de transferencia (para el caso de MEP/SNP) con el cual se hace efectivo el mismo.</span></th>
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
<?php } else {?>  
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
              <td align="left" scope="row">
                
              </td>
            </tr>
        
        
            <tr>
              <td align="left" class="texto6" scope="row">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="1%" scope="col"><table width="100%" border="0" cellspacing="1" cellpadding="0">
        
            <tr align="left">
              <td colspan="5" scope="row">
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
              <td width="10%" align="right" class="texto5" scope="row"><div align="right">Tipo de documento (1) </div></td>
              <td width="10%" class="td_detalle"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',''); ?>&nbsp;</td>
              <td width="13%" class="texto5">Fecha Alta</td>
              <td colspan="2" class="td_detalle"><span class="td2">
                <?php  abrir_calendario('fecha','premio', $fecha); ?>
              </span></td>
              </tr>
            <tr valign="top">
              <td align="right" class="texto5" scope="row">Documento (1) </td>
              <td colspan="4" class="td_detalle"><input name="documento" type="text" class="small_derecha" id="documento" size="13" maxlength="13" />
                <span class="texto4Totales"> (1) Identificacion del cliente (Tipo de documento y numero sin punto ni guion)</span></td>
            </tr>
            <tr>
              <td align="right" class="texto5" scope="row">CUIT/CUIL:</td>
              <td colspan="4" class="td_detalle"><span class="texto4Totales">
                <input name="cuit2" type="text" class="small" id="cuit2" size="13" maxlength="13" value="<?php echo $row->CUIT; ?>" />
(2) Sin punto ni guion</span> <span class="smallRojo">
<label><a href="#" class="smallRojo"  onclick="window.open ('http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx','anses')";><strong>verificar cuit</strong></a></label>
</span> <span class="texto4Totales">
<label> </label>
<img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span><span class="texto4Totales"></span></td>
            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Nombre</td>
              <td scope="row"><span class="td_detalle">
                <input name="nombre" type="text" class="small" id="nombre" size="50" maxlength="50" />
              </span></td>
              <td scope="row"><div align="right"><span class="texto5">Apellido</span></div></td>
              <td scope="row"><span class="td_detalle">
                <input name="apellido" type="text" class="small" id="apellido" size="50" maxlength="50" />
              </span></td>

            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Nacionalidad</td>
              <td colspan="4" scope="row"><span class="td_detalle">
                <input name="nacionalidad" type="text" class="small" id="nacionalidad" value="Argentina" size="20" maxlength="20" />
              </span></td>
            </tr>
            <tr align="left"><td colspan="5">
                  <table width="100%" border="1" cellspacing="0">
                <tr align="center">
                  <td colspan="2" rowspan="2" class="texto5"><div align="center">Provincia</div>
                     <div align="center">
                       <input name="button3" type="image" id="button3" src="image/folder_16.png" alt="Seleccionar provincia" width="16" height="16" onclick="ajax_showTooltip('premio/provincias_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_pais.value,this);  return false; "/>
                       <input name="provincia" type="text" class="tdVerde" id="provincia" value="Cordoba" readonly="yes"/>
                       <input type="hidden" name="cod_provincia" id="cod_provincia" value="6"/>
                       <input name="provincia_memo" type="hidden" id="provincia_memo" value="Cordoba" />
                       <input name="cod_provincia_memo" type="hidden" id="cod_provincia_memo" value="6" />
                          <input type="hidden" name="cod_pais" id="cod_pais" value="1"/>
                   </div></td>
                  <td width="30%" class="texto5"><div align="center">Localidad</div></td>
                  <td width="25%" class="texto5">Cod Postal</td>
                  <td width="9%" class="texto5">Extranjero</td>
                </tr>
                <tr>
                  <td>
                      <div align="center">
                        <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="ajax_showTooltip('premio/localidades_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia.value,this);  return false; "/>
                        <input name="localidad" type="text" class="tdVerde" id="localidad" readonly="yes" value="Capital"/>
                        <input type="hidden" name="cod_localidad" id="cod_localidad" value="22121"/>
                        <input name="localidad_memo" type="hidden" id="localidad_memo" value="Capital" />
                        <input name="cod_localidad_memo" type="hidden" id="cod_localidad_memo" value="22121" />
                      </div></td>
                  <td><span class="texto3">
                    <input name="cod_postal2" type="text" align="center" class="small" id="cod_postal2" size="15" maxlength="13" value="<?php echo $row->COD_POSTAL; ?>" />
                    <span class="style6"> <span class="style7">
                    <label></label>
                    </span></span><a href="#" class="smallRojo"  onclick="window.open ('http://www.correoargentino.com.ar/consulta_cpa/cons2.php','Cod Postal')";>Cod Postal </a><img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></td>
                  <td align="center"><input type="checkbox" name="chk_extranjero" id="chk_extranjero" onclick="if (this.checked){
                  																												form1.provincia.value=''; form1.cod_provincia.value=0;
                                                                                                                                form1.localidad.value=''; form1.cod_localidad.value=0;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 } else {
                                                                                                                 				form1.provincia.value=form1.provincia_memo.value; form1.cod_provincia.value=form1.cod_provincia_memo.value;
                                                                                                                                form1.localidad.value=form1.localidad_memo.value; form1.cod_localidad.value=form1.cod_localidad_memo.value;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 }" />				  </td>
                </tr>
              </table></td> 
            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Domicilio Real</td>
              <td colspan="4" scope="row"><span class="td_detalle">
                <input name="domicilio" type="text" class="small" id="domicilio" size="100" maxlength="100" />
              </span></td>
            </tr>
            <tr align="left">
              <td align="right" class="texto5" scope="row">Profesion</td>
              <td colspan="4" scope="row"><span class="texto4Totales"><span class="td_detalle">
                <input name="profesion" type="text" class="small" id="profesion" size="50" maxlength="50" />
              </span>Profesion / Actividad desarrollada</span></td>
            </tr>
            
                    </table>            </td>
                  </tr>
              </table>      </td>
            </tr>
        
            <tr>
              <td align="left" scope="row">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td scope="col"><table width="106%" border="0" cellspacing="1" cellpadding="0" class="texto6">
                                  <tr align="left">
                                    <td colspan="6" scope="row" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <th width="20" align="left" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></th>
                                          <td align="center" class="td8" scope="col"><a href="#">DATOS  DEL PAGADOR</a></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td width="72" align="right" class="texto5" scope="row">Valor del Premio</td>
                                    <td width="3" align="right" class="texto5" scope="row">&nbsp;</td>
                                    <td width="105" class="td_detalle"><input name="valor_premio3" type="text" class="small_derecha" id="valor_premio3" size="14" maxlength="14" /></td>
                                  <td width="124" class="td_detalle"><span class="texto5">
                                  <div align="center">Numero de Ticket Caja Publica</div>
                                    </span></td>
                                    <td width="76" class="td_detalle"><input name="nro_ticket" type="text" class="small_derecha" id="nro_ticket" size="14" maxlength="14" /></td>
                   
                                  </tr>
                                  <tr valign="top">
                                    <td align="right" class="texto5" scope="row">Moneda</td>
                                    <td align="right" class="texto5" scope="row">&nbsp;</td>
                                    <td colspan="4" class="td_detalle"><?php echo armar_combo($rs_moneda,'id_moneda',''); ?><span class="texto4Totales">Moneda en la cual se paga el premio</span></td>
                                  </tr>
                                  <tr>
                                    <td align="right" class="texto5" scope="row">Concepto</td>
                                    <td align="right" class="texto5" scope="row">&nbsp;</td>
                                    <td colspan="4" class="td_detalle"><input name="concepto3" type="text" class="small" id="concepto3" size="50" maxlength="100" />
                                        <span class="texto4Totales">Descripcion del premio</span></td>
                                  </tr>
                                  <tr>
                                    <td align="right" class="texto5" scope="row">Instrumento de pago</td>
                                    <td align="right" class="texto5" scope="row">&nbsp;</td>
                                    <td colspan="3" class="td_detalle"><?php echo armar_combo($rs_tipo_pago,'id_tipo_pago',''); ?></td>
                                    <td class="td_detalle">
                                      <input name="juego" type="hidden" id="juego" value="CASINO" />                                    </td>
                                </tr>
                                  <tr>
                                    <td align="right" class="texto5" scope="row">Domicilio</td>
                                    <td align="right" class="texto5" scope="row">&nbsp;</td>
                                    <td colspan="3" class="td_detalle"><input name="domicilio_pago3" type="text" class="small" id="domicilio_pago3" size="70" maxlength="70" /></td>
                                    <td class="td_detalle"><div align="left"><span class="texto4Totales">Domicilio donde se adjudico el premio</span></div></td>
                                  </tr>
                                  <tr align="left">
                                    <td align="right" valign="top" class="texto5" scope="row">Cuenta bancaria</td>
                                    <td align="right" class="texto5" scope="row">&nbsp;</td>
                                    <td colspan="4" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr valign="top">
                                          <th height="52" scope="col"><span class="td_detalle">
                                            <input name="cuenta_bancaria3" type="text" class="small" id="cuenta_bancaria3" size="50" maxlength="50" />
                                          </span></th>
                                          <th align="left" scope="col"><span class="texto4Totales">Cuenta bancaria de la entidad que paga el premio y numero de cheque o detalle de transferencia (para el caso de MEP/SNP) con el cual se hace efectivo el mismo.</span></th>
                                        </tr>
                                    </table></td>
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
<?php }?>