<?php
 	session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
//print_r($_GET);
//print_r($_GET);	
//$db->debug=true;

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php

try {
	$rs_tipo_documento = $db->Execute("select id_tipo_documento as codigo, descripcion from PLA_AUDITORIA.t_tipo_documento
										where id_tipo_documento <> 2 and id_tipo_documento <> 3");
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
//$mostrar_juego=1;	
//if ($_SESSION['suc_ban']==1 || $_SESSION['suc_ban']==27|| $_SESSION['suc_ban']==23|| $_SESSION['suc_ban']==25|| $_SESSION['suc_ban']==34|| $_SESSION['suc_ban']==26|| $_SESSION['suc_ban']==30|| $_SESSION['suc_ban']==20|| $_SESSION['suc_ban']==21|| $_SESSION['suc_ban']==31|| $_SESSION['suc_ban']==24|| $_SESSION['suc_ban']==33|| $_SESSION['suc_ban']==22|| $_SESSION['suc_ban']==32){
	try {
		$rs_juego = $db->Execute("select id_juegos as codigo,juegos as descripcion from juegos.juegos where activo = 1");
		}
		catch  (exception $e) 
		{ 
		die($db->ErrorMsg());
		}
	$mostrar_juego=1;
//}

if (isset($_GET['id_ganador'])) {
	$ganador = $_GET['id_ganador'];
	$condicion_ganador="and g.id_ganador = '$ganador'";
	}
	else 
		{
			if (isset($_POST['id_ganador'])) {
				$ganador = $_POST['id_ganador'];
				$condicion_ganador="and g.id_ganador = '$ganador'";
				} 
			else {
				$ganador = "";
				$condicion_ganador="";
			}
		}
		
try {
	$rs_consulta = $db->Execute("select g.id_ganador,g.id_tipo_documento, g.documento, g.apellido, g.nombre, g.nacionalidad, g.domicilio,g.cheque_nro, g.nro_ticket, g.cod_postal, g.sorteo_nro,
								g.profesion, g.valor_premio, g.id_moneda, m.descripcion, g.concepto, g.juego, g.id_tipo_pago, to_char(g.fecha_alta,'DD/MM/YYYY') as fecha_alta, 
								g.domicilio_pago, g.cuenta_bancaria_salida, g.documento2, g.apellido2, g.nombre2, g.id_localidad, g.cuit,
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
								$condicion_ganador");
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
	
	
	/*echo('provincia'.$provincia);
	echo($cod_provincia);
	echo('localidad'.$localidad);
	echo($cod_localidad);*/
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

$row1 = $rs_suc_ban->FetchNextObject($toupper=true);

?>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />    
<style type="text/css">
<!--
.style6 {font-size: 12px}
.style7 {font-weight: bold; color: #FF0000; font-family: tahoma;}
.style10 {
	font-weight: bold;
	color: #FF0000;
	font-family: tahoma;
	font-size: 10px;
}
-->
</style>
<form name="form1" id="form1" method="post" action="#" onSubmit="validar_modificar_ganador('contenido','premio/procesar_modificar_premio.php',this); return false;">
  <table width="82%" border="0" align="center" cellspacing="0">
    <tr align="left">
 <?php if($_GET['bandera']==2){?>           
 		<td width="86%" align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/premios_conformar.php','')">Regresar</a></div></td>
    <?php } else {?>  <?php if ($_SESSION['permiso']=='ADMINISTRA') {
	    $_SESSION['bandera']=1;?>
      <td width="86%" align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio_administra.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&conformado=<?php echo $_GET['conformado'] ; ?>&suc_ban=<?php echo $_GET['casa'] ?>')">Regresar</a></div></td>
      <?php }
	   elseif ($_SESSION['permiso']=='ADM_CONFORMA' || $_SESSION['permiso']=='ADM_CASINO') {?>
       
      <td width="7%" align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&suc_ban=<?php echo $_GET['casa'] ?>&conformado=<?php echo $_GET['conformado'] ; ?>')">Regresar </a></div></td>
      <?php }
	  
	   else {?>
      <td width="7%" align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&suc_ban=<?php echo $_GET['casa'] ?>&conformado=<?php echo $_GET['conformado'] ; ?>')">Regresar</a></div></td>
      <?php }?>
 <?php }?>
    </tr>
    <tr>
      <td align="left" class="texto6" scope="row"></td>
    </tr>
    <tr>
      <td align="left" scope="row"></td>
    </tr>
    <tr>
      <td align="left" class="texto6" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="1%" scope="col"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr align="left">
                  <td colspan="6" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="textoRojo" scope="col">&nbsp;</td>
                        <td align="center" class="small_derecha" scope="col">&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="20" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></td>
                        <td align="center" class="td4" scope="col"><a href="#">DATOS PERSONALES DEL GANADOR</a></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td width="111" align="right" class="texto5" scope="row"><div align="right"> Tipo de Doc (1) </div></td>
                  <td width="2" align="right" class="texto5" scope="row">&nbsp;</td>
                  <td width="373" class="td_detalle"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',$row->ID_TIPO_DOCUMENTO); ?>&nbsp;</td>
                  <td width="72" class="texto5">Fecha Alta</td>
                  <td width="145" colspan="2" class="td_detalle"><span class="td2">
                    <?php  abrir_calendario('fecha','premio', $row->FECHA_ALTA); ?>
                  </span></td>
                </tr>
                <tr valign="top">
                  <td align="right" class="texto5" scope="row">Documento (1) </td>
                  <td align="right" class="texto5" scope="row">&nbsp;</td>
                  <td colspan="4" class="td_detalle"><span class="texto4Totales">
                    <input name="documento" type="text" class="small" id="documento" size="13" maxlength="13" value="<?php echo $row->DOCUMENTO; ?>" />
                    (1) Identificacion del cliente (Tipo de documento y numero sin punto ni guion)</span></td>
                </tr>
                <tr>
                  <td align="right" class="texto5" scope="row">CUIT/CUIL:</td>
                  <td align="right" class="texto5" scope="row">&nbsp;</td>
                  <td colspan="4" class="td_detalle"><span class="texto4Totales">
                    <input name="cuit" type="text" class="small" id="cuit" size="13" maxlength="13" value="<?php echo $row->CUIT; ?>" />
                    (2) Sin punto ni guion</span>
                    <span class="smallRojo">
                    <label><a href="#" class="smallRojo"  onclick="window.open ('http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx','anses')";><strong>verificar cuit</strong></a></label>
                    </span>
                                        <span class="texto4Totales">
                    <label> </label>
                    <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span><span class="texto4Totales"> </span></td>
                </tr>
                <tr align="left">
                  <td align="right" class="texto5" scope="row">Nombre</td>
                  <td align="right" class="texto5" scope="row">&nbsp;</td>
                  <td colspan="4" scope="row"><span class="td_detalle">
                    <input name="nombre" type="text" class="small" id="nombre" size="40" maxlength="50" value="<?php echo utf8_encode($row->NOMBRE); ?>" />
                  </span><span class="texto5"> Apellido </span><span class="td_detalle">
                    <input name="apellido" type="text" class="small" id="apellido" size="40" maxlength="50" value="<?php echo utf8_encode($row->APELLIDO); ?>" />
                  </span></td>
                </tr>
                <tr align="left">
                  <td align="right" class="texto5" scope="row">Nacionalidad</td>
                  <td align="right" class="texto5" scope="row">&nbsp;</td>
                  <td colspan="4" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="18%"><span class="td_detalle">
                          <input name="nacionalidad" type="text" class="small" id="nacionalidad"  size="20" maxlength="20" value="<?php echo utf8_encode($row->NACIONALIDAD); ?>" />
                        </span></td>
                        <td width="8%" align="left"><span class="texto5">Profesion</span></td>
                        <td width="74%"><span class="texto4Totales"><span class="td_detalle">
                          <input name="profesion" type="text" class="small" id="profesion" size="50" maxlength="50" value="<?php echo utf8_encode($row->PROFESION); ?>" />
                          Profesion / Actividad desarrollada</span></span></td>
                      </tr>
                  </table></td>
                </tr>
                <tr align="left">
                  <td height="36" align="right" class="texto5" scope="row">Domicilio Real</td>
                  <td align="right" class="texto5" scope="row">&nbsp;</td>
                  <td colspan="4" scope="row"><span class="td_detalle">
                    <input name="domicilio" type="text" class="small" id="domicilio" size="100" maxlength="100" value="<?php echo utf8_encode($row->DOMICILIO); ?>" />
                  </span></td>
                </tr>
                <tr align="left">
                  <td colspan="6" scope="row"><table width="100%" border="1" cellspacing="0">
                      <tr align="center">
                        <td colspan="2" rowspan="2" class="texto5"><div align="center">Provincia</div>
                          <div align="center">
                            <input name="button3" type="image" id="button3" src="image/folder_16.png" alt="Seleccionar provincia" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/provincias_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_pais.value,this);  return false; }"/>
                            <input name="provincia" type="text" class="tdVerde" id="provincia" value="<?php echo $provincia; ?>" readonly="yes"/>
                            <input type="hidden" name="cod_provincia" id="cod_provincia" value="6"/>
                            <input name="provincia_memo" type="hidden" id="provincia_memo" value="Cordoba" />
                            <input name="cod_provincia_memo" type="hidden" id="cod_provincia_memo" value="6" />
                            <input type="hidden" name="cod_pais" id="cod_pais" value="1"/>
                        </div>                        </td>
                        <td width="31%" class="texto5"><div align="center">Localidad</div></td>
                        <td width="28%" class="texto5">Codigo Postal</td>
                        <td width="9%" class="texto5">Extranjero</td>
                      </tr>
                      <tr>
                        <td><div align="center">
                            <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/localidades_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia.value,this);  return false; } "/>
                            <input name="localidad" type="text" class="tdVerde" id="localidad" readonly="yes" value="<?php echo $localidad; ?>"/>
                            <input type="hidden" name="cod_localidad" id="cod_localidad" value="<?php echo $cod_localidad ?>"/>
                            <input name="localidad_memo" type="hidden" id="localidad_memo" value="Capital" />
                            <input name="cod_localidad_memo" type="hidden" id="cod_localidad_memo" value="22121" />
                        </div></td>
                        <td><span class="texto3">
                          <input name="cod_postal" type="text" align="center" class="small" id="cod_postal" size="15" maxlength="13" value="<?php echo $row->COD_POSTAL; ?>" />
                          
                          <span class="style6">
                          <span class="style7">
                          <label></label>
                          </span></span><a href="#" class="smallRojo"  onclick="window.open ('http://www.correoargentino.com.ar/consulta_cpa/cons2.php','')";>Cod Postal </a><img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></td>
                        <td align="center"><input type="checkbox" name="chk_extranjero" id="chk_extranjero" onclick="if (this.checked){
                  																												form1.provincia.value=''; form1.cod_provincia.value=0;
                                                                                                                                form1.localidad.value=''; form1.cod_localidad.value=0;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 } else {
                                                                                                                 				form1.provincia.value=form1.provincia_memo.value; form1.cod_provincia.value=form1.cod_provincia_memo.value;
                                                                                                                                form1.localidad.value=form1.localidad_memo.value; form1.cod_localidad.value=form1.cod_localidad_memo.value;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 }" />                        </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td align="left" scope="row" class="texto6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
                              <td width="80" align="right" class="texto5" scope="row">Valor del Premio</td>
                              <td width="2" align="right" class="texto5" scope="row">&nbsp;</td>
                              <td width="206"  class="td_detalle"><input name="valor_premio" type="text" class="small_derecha" id="valor_premio" size="14" maxlength="14" value="<?php echo $row->VALOR_PREMIO ?>" /></td>
                              <td width="106"  class="texto5"><p>Numero de ticket<br />
                                Caja Publica</p></td>
                              <td colspan="2"  class="texto5"><span class="td_detalle">
                                <input name="nro_ticket" type="text" class="small_derecha" id="nro_ticket" size="14" maxlength="14" value="<?php echo $row->NRO_TICKET ?>" />
                              </span><span class="texto4Totales">Obligatorio para CASINO.</span></td>
                            </tr>
                            <tr valign="top">
                              <td align="right" class="texto5" scope="row">Moneda</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" class="td_detalle"><?php echo armar_combo($rs_moneda,'id_moneda',$row->ID_MONEDA); ?><span class="texto4Totales">Moneda en la cual se paga el premio</span></td>
                            </tr>
                            <tr>
                              <td align="right" class="texto5" scope="row">Concepto</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" class="td_detalle"><span class="texto4Totales">
                                <label>
                                <textarea name="concepto" id="concepto" cols="60" rows="2"><?php echo $row->CONCEPTO ?></textarea>
                                </label>
                                Descripcion del premio</span></td>
                            </tr>
                            <tr>
                              <td align="right" class="texto5" scope="row">Instrumento de pago</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" class="td_detalle"><?php echo armar_combo($rs_tipo_pago,'id_tipo_pago',$row->ID_TIPO_PAGO); ?></td>
                            </tr>
                            <tr>
                              <td height="19" align="right" class="texto5" scope="row"><span class="td_detalle">Juego</span></td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td class="td_detalle"><?php if ($mostrar_juego==1) {?>
                                <span class="texto5">
                                <?php armar_combo($rs_juego,'juego',$row->JUEGO); ?>
                                <?php }else{?>
                                <input name="juego" id="juego" type="hidden" value="0" />
                                <?php }?>
                              </span></td>
                              <td class="texto5"><div align="right">N&ordm; de Sorteo</div></td>
                              <td class="td_detalle">
                            <input name="sorteo_nro" type="text" class="small_derecha" id="sorteo_nro" size="5" maxlength="5" value="<?php echo $row->SORTEO_NRO ?>" />
                            <span class="texto4Totales">N&uacute;mero de sorteo del premio.</span></td>
                            
                            </tr>
                            <tr>
                              <td align="right" class="texto5" scope="row">Domicilio</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="3" class="td_detalle"><input name="domicilio_pago" type="text" class="small" id="domicilio_pago" size="70" maxlength="70" value="<?php echo utf8_encode($row->DOMICILIO_PAGO); ?>" />
                              <span class="texto4Totales">Domicilio donde se adjudico el premio.</span></td>
                            </tr>
                            <tr align="left">
                              <td align="right" valign="top" class="texto5" scope="row">Cuenta bancaria</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="4" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr valign="top">
                                    <th height="39" scope="col"><span class="td_detalle">
                                      <input name="cuenta_bancaria" type="text" class="small" id="cuenta_bancaria" size="50" maxlength="50" value="<?php echo utf8_encode($row->CUENTA_BANCARIA_SALIDA) ?>" />
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
                                    <td><input name="cheque_nro" type="text" class="small" id="cheque_nro" value="<?php echo utf8_encode($row->CHEQUE_NRO); ?>" />                                      <span class="texto4Totales">Nro de Cheque (en caso de corresponder).</span></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td align="left" class="texto6" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td scope="col"><table width="100%" border="0" cellspacing="1" cellpadding="0">
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
                  <td colspan="2" class="td_detalle"><?php $rs_tipo_documento->MoveFirst(); echo armar_combo($rs_tipo_documento,'id_tipo_documento2',$row->ID_TIPO_DOCUMENTO2); ?></td>
                  <td colspan="3" class="td_detalle">&nbsp;</td>
                </tr>
                <tr valign="top">
                  <td align="right" class="texto5" scope="row">Documento (2)</td>
                  <td align="right" class="texto5" scope="row">&nbsp;</td>
                  <td width="684" class="td_detalle"><input name="documento2" type="text" class="small" id="documento2" size="13" maxlength="13" value="<?php echo $row->DOCUMENTO2; ?>" /></td>
                </tr>
                <tr>
                  <td align="right" class="texto5" scope="row">Apellido (2)</td>
                  <td align="right" class="texto5" scope="row">&nbsp;</td>
                  <td class="td_detalle"><input name="apellido2" type="text" class="small" id="apellido2" size="50" maxlength="50" value="<?php echo utf8_encode($row->APELLIDO2); ?>"/></td>
                </tr>
                <tr align="left">
                  <td align="right" class="texto5" scope="row">Nombre (2)</td>
                  <td align="right" class="texto5" scope="row">&nbsp;</td>
                  <td scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <th scope="col"><span class="td_detalle">
                          <input name="nombre2" type="text" class="small" id="nombre2" size="50" maxlength="50" value="<?php echo utf8_encode($row->NOMBRE2); ?>" />
                        </span></th>
                        <th align="left" scope="col">&nbsp;</th>
                        <th align="left" scope="col"><span class="td_detalle"><span class="texto4Totales">(2) Nombre y apellido y documento de identidad (tipo y numero), de las personas a nombre de quienes se extiende el instrumento financiero, cuando el mismo no se hubiere extendido a la orden del supuesto ganador.</span></span></th>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
    <tr align="left">
      <td align="center" scope="row">&nbsp;</td>
    </tr>
    <tr align="left">
      <td align="center" scope="row">
       <?php if ($_SESSION['permiso']!='ADM_CONFORMA' and $_SESSION['permiso']!='ADM_CASINO') {?>
      <input name="button" type="submit" class="textoAzulOscuro" id="button" value="Guardar" />
      <input type="hidden" name="id_ganador" id="id_ganador" value="<?php echo $row->ID_GANADOR; ?>" />
      <input type="hidden" name="fdesde" id="fdesde" value="<?php echo $_GET['fdesde']; ?>" />
      <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta']; ?>" />
      <input type="hidden" name="conformado" id="conformado" value="<?php echo $_GET['conformado']; ?>" />
      <input type="hidden" name="casa" id="casa" value="<?php echo $_GET['casa']; ?>" />
      <?php } ?>
      </td>
    </tr>
    <tr align="left">
    <?php if($_GET['bandera']==2){?>           
 		<td width="86%" align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/premios_conformar.php','')">Regresar</a></div></td>
    <?php } else {?>
      <?php if ($_SESSION['permiso']=='ADMINISTRA') {
      $_SESSION['bandera']=1;?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio_administra.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&conformado=<?php echo $_GET['conformado'] ; ?>&suc_ban=<?php echo $_GET['casa'] ?>')">Regresar</a></div></td>
      <?php }
	  elseif ($_SESSION['permiso']=='ADM_CONFORMA' || $_SESSION['permiso']=='ADM_CASINO') {?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&suc_ban=<?php echo $_GET['casa'] ?>&conformado=<?php echo $_GET['conformado'] ; ?>')">Regresar </a></div></td>
      <?php }
	  
	   else {?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&suc_ban=<?php echo $_GET['casa'] ?>&conformado=<?php echo $_GET['conformado'] ; ?>')">Regresar</a></div></td>
      <?php }?>
      <?php }?>
    </tr>
  </table>
</form>