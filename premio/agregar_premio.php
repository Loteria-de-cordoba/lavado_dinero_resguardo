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


try {
	$rs_tipo_documento = $db->Execute("SELECT id_tipo_documento AS codigo,  descripcion
										FROM lavado_dinero.t_tipo_documento
										WHERE id_tipo_documento NOT IN (2,3,4)");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
	
try {
	$rs_tipo_documento2 = $db->Execute("select id_tipo_documento as codigo, descripcion from lavado_dinero.t_tipo_documento where id_tipo_documento <> 2 and id_tipo_documento <> 3 ");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}

	
try {
	$rs_moneda = $db->Execute("select id_moneda as codigo, descripcion from lavado_dinero.t_moneda");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
try {
	$rs_tipo_pago = $db->Execute("select id_tipo_pago as codigo, descripcion from lavado_dinero.t_tipo_pago ");
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
								from lavado_dinero.t_info_direcciones
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
								from lavado_dinero.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));*/
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
								from lavado_dinero.t_ganador g, lavado_dinero.t_tipo_documento td, 
								lavado_dinero.t_moneda m, lavado_dinero.t_tipo_pago tp,
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
  <form name="form1" id="form1" method="post" action="#" onSubmit="validar_ganador('contenido','premio/procesar_alta_premio.php',this); return false;">
  <table width="80%" border="0" align="center" cellspacing="0">
  	<tr align="left">   	</tr>
  	<iframe name="adjunto" frameborder="0" id="adjunto" width="100%" src="upload_ajax/index.php">&nbsp;</iframe>
    
  	<tr align="left">
  	  <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','')">Regresar</a></div></td>
    </tr>
  <tr>
        <td align="left" class="texto6" scope="row">
    	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        	    <tr>
            	    <td width="1%" valign="top" scope="col"><img src="image/My-Docs.png" width="40" height="40" /></td>
                    <td align="left" scope="col">&nbsp;</td>
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
                    <td align="center" class="textoAzulOscuroFondo" scope="col"><a href="#">DATOS  DEL GANADOR DEL PREMIO</a></td>
                  </tr>
              	</table></td>
            	</tr>
                
                <tr>
              <td align="right" class="smallVerde" scope="row">Apellido</td>
              <td class="td_detalle"><input name="apellido" type="text" class="small" id="apellido" size="50" maxlength="50" /></td>
              <td colspan="3" class="smallVerde" >Nombre
                <input name="nombre" type="text" class="small" id="nombre" size="50" maxlength="50" /></td>
              <td class="td_detalle">&nbsp;</td>
              <td class="td_detalle">&nbsp;</td>
            </tr>
                
                        <tr>
                          <td align="right" class="smallVerde" scope="row">Fecha de Nacimiento</td>
                          <td width="27%" align="left" class="smallVerde"><?php  abrir_calendario('fecha','nacimiento', $fecha);?>                          </td>
                          <td width="10%" align="right" class="smallVerde">Lugar de Nacimiento</td>
                          <td colspan="2" align="left" class="smallVerde">
                          <input name="lugar_nacimiento" type="text" class="small" id="lugar_nacimiento" size="50" maxlength="50" />                          </td>
                          <td width="3%" class="smallVerde" align="right">Sexo</td>
                          <td width="14%" class="texto5">
                          <select name="sexo" id="sexo" class="small">
                          	<option value="Masculino">Masculino</option>
                             <option value="Femenino">Femenino</option>
                          </select>                          </td>
                      </tr>
               <tr>
              <td width="8%" align="right" class="smallVerde" scope="row"><div align="right">Tipo de documento  </div></td>
              <td class="td_detalle"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',''); ?></td>
              <td colspan="5" align="left" class="td_detalle"><span class="smallVerde">Nro. Documento</span><span class="smallRojo">
                <input name="documento" type="text" id="documento" class="small" size="13" maxlength="13" value="<?php echo $_POST['documento'] ?>" onblur="var texto=$.trim(this.value);if(texto.length!=8 || (isNaN(texto)==true)) {var alerta='Solo ocho digitos - Puede necesitar 0(cero) a la izquierda!!!'; alert(alerta); this.value='<?php echo $_POST['documento']?>';return false;}"/>
                 Identificacion del cliente (Tipo de documento y numero)</span></td>
              </tr>
                      <tr>
                        <td align="right" class="smallVerde" scope="row">CUIT/CUIL/CDI</td>
                        <td colspan="6" class="smallRojo"><input name="cuit" type="text" class="small" id="cuit" size="13" maxlength="13" onblur="var texto=$.trim(this.value);if(texto.length!=11 || (isNaN(texto)==true)) {var alerta='Solo 11 digitos!!!'; alert(alerta); this.value='';return false;}" />
                          Sin punto ni guion<span class="smallRojo">
                            <label><a href="#" class="smallRojo"  onclick="window.open ('http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx','anses')";><strong>verificar cuit</strong></a></label>
                            </span>
                          <label> </label>
                          <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></td>
                      </tr>
            
            
            <tr align="left">
              <td align="right" class="smallVerde" scope="row">Nacionalidad</td>
              <td colspan="6" align="right" class="texto5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  
                  <td width="11%"><span class="td_detalle">
                    <input name="nacionalidad" type="text" class="small" id="nacionalidad" value="Argentina" size="20" maxlength="20" />
                  </span></td>
                  <td width="18%">&nbsp;</td>
                  <td width="7%" align="right" class="smallVerde">Profesion</td>
                  <td width="64%" class="smallRojo" align="left"> <input name="profesion" type="text" class="small" id="profesion" size="30" maxlength="50" />
                 Profesion / Actividad desarrollada / Oficio</td>
                  </tr>
              </table></td>
              </tr>
            <tr align="left">
              <td align="right" class="smallVerde" scope="row">Calle</td>
              <td colspan="6" class="smallVerde" scope="row">
                <input name="calle" type="text" class="small" id="calle" size="30"/>
                Nro
                <input name="numero" type="text" class="small" id="numero" size="3"/>
Piso
<input name="piso" type="text" class="small" id="piso" size="3"/>
Dpto
<input name="dpto" type="text" class="small" id="dpto" size="3"/></td>
              </tr>
             <tr>
             	<td class="smallVerde" align="right">Persona politicamente expuesta</td>
                <td class="smallVerde" colspan="6"> <div id="persona_politica"><?php include('persona_politica.php'); ?></div></td>
                
                
             </tr> 
            <tr align="left">
            <td align="right" class="smallVerde" scope="row">DDJJ Nro:</td>
              <td colspan="9" scope="row" class="smallVerde"><input name="ddjj" class="small" type="text"  id="ddjj" size="3" maxlength="4" />Estado civil
                <input name="estado_civil" class="small" type="text"  id="estado_civil" size="20" maxlength="20" />
                <span class="smallVerde">Telefono</span>                <input name="telefono" class="small" type="text" id="telefono" size="20" maxlength="20" />
                <span class="smallVerde">Email
                <input name="email" class="small" type="text" id="email" size="50" maxlength="50"/>
                </span></td>
              
              </tr>
            <tr align="left"><td colspan="7">
                 <table width="100%" border="1" cellspacing="0">
                <tr align="center">
                  <td width="39%" align="center" valign="middle" class="smallVerde"><div align="center">Provincia</div></td>
                  <td width="35%" class="smallVerde"><div align="center">Localidad</div></td>
                  <td width="14%" class="smallVerde">Codigo Postal</td>
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
                     </div></td>
                  <td>
                      <div align="center">
                        <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/localidades_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia.value,this);  return false; } "/>
                        <input name="localidad" type="text" class="tdVerde" id="localidad" readonly="yes" value="Capital"/>
                        <input type="hidden" name="cod_localidad" id="cod_localidad" value="22121"/>
                        <input name="localidad_memo" type="hidden" id="localidad_memo" value="Capital" />
                        <input name="cod_localidad_memo" type="hidden" id="cod_localidad_memo" value="22121" />
                      </div></td>
                  <td align="center"><span class="texto3">
                    <input name="cod_postal" type="text" align="center" class="small" id="cod_postal" size="15" maxlength="13" />
                    <span class="texto4Totales"><span class="smallRojo">
                    <label><a href="#" class="smallRojo"  onclick="window.open ('http://www.correoargentino.com.ar/consulta_cpa/cons2.php','')";><strong>verificar CP</strong></a></label>
                    </span>
                    <label> </label>
                    <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></span></td>
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
                              <td colspan="7" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <th width="20" align="left" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></th>
                                    <td align="center" class="textoAzulOscuroFondo" scope="col"><a href="#">DATOS  DEL PAGADOR</a></td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td align="right" class="smallVerde" scope="row">Fecha Pago </td>
                              <td align="left" class="smallVerde" scope="row">&nbsp;</td>
                              <td align="left" class="smallVerde" scope="row"><?php  abrir_calendario('fecha_pago','premio', $fecha);?></td>
                              <td align="left" class="smallVerde" scope="row">Sorteo Nro
                                <input name="sorteo_nro" id="sorteo_nro" class="small" size="15"/>
                              </td>
                              <td colspan="2" align="left" class="smallVerde" scope="row">&nbsp;</td>
                          </tr>
                            <tr>
                              <td width="87" align="right" class="smallVerde" scope="row">Valor Bruto del Premio</td>
                              <td width="2" align="right" class="smallVerde" scope="row">&nbsp;</td>
                              <td width="100" class="td_detalle"><input name="valor_premio" class="small" type="text" id="valor_premio" size="14" maxlength="14" /></td>
                              <td width="379" class="smallVerde">Numero de Ticket/Cupon/Billete
                                <input name="nro_ticket" class="small" type="text"  id="nro_ticket" size="14" maxlength="14" /> </td>
                              <td colspan="2" class="td_detalle"><p class="texto5"><span class="texto4Totales"></span></p>  </td>
                          </tr>
                            <tr valign="top">
                              <td align="right" class="smallVerde" scope="row">Moneda</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="5" class="smallRojo"><?php echo armar_combo($rs_moneda,'id_moneda',''); ?>Moneda en la cual se paga el premio</td>
                            </tr>
                            <tr>
                              <td align="right" class="smallVerde" scope="row">Concepto</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="3" valign="middle" class="smallRojo"><label>
                                <textarea name="concepto" id="concepto" cols="50" rows="3"></textarea>
                                Descripcion del premio</label>
                              <div align="left"></div></td>
                              <td width="205" class="td_detalle"><table width="93%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                              <td width="75" align="right" class="smallVerde">Juego</td>
                                              <td width="124" class="td_detalle"><?php echo armar_combo($rs_juego,'juego',$juegos); ?>                              </td>
                                </tr>
                                
                              </table></td>
                          </tr>
                            
                            <tr>
                              <td align="right" class="smallVerde" scope="row">Instrumento de pago</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <!--<td colspan="5" class="td_detalle"><?php echo armar_combo($rs_tipo_pago,'id_tipo_pago',''); ?></td>-->
                              <td align="left" ><div id="tipo_pago"><?php include('tipo_pago.php'); ?></div></td>
                          </tr>
                            <tr>
                              <td align="right" class="smallVerde" scope="row">Domicilio</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="5" class="smallRojo"><input name="domicilio_pago" readonly="readonly" type="text" class="small" id="domicilio_pago" size="70" maxlength="70" value="<?php echo $row1->DIRECCION ?>" /> Domicilio donde se adjudico el premio</td>
                          </tr>
                            <tr align="left">
                              <td align="right" valign="top" class="smallVerde" scope="row">Cuenta bancaria</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr valign="top">
                                    <td height="52" scope="col"><input name="cuenta_bancaria" type="text" readonly="readonly" class="small" id="cuenta_bancaria" size="40" maxlength="50" value="<?php echo $row1->CUENTA_BANCARIA ?>"/></td>
                                    <td align="left" scope="col" class="smallRojo">Cuenta bancaria de la entidad que paga el premio y numero de cheque o detalle de transferencia (para el caso de MEP/SNP) con el cual se hace efectivo el mismo.</td>
                                  </tr>
                              </table></td>
                            </tr>
                           
                            <tr align="left">
                              <td align="right" valign="top" class="smallVerde" scope="row"></td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td class="smallRojo"></td>
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
              <td colspan="4" scope="row">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                </table></td>
            </tr>
            			
                        <tr>
                    <td width="73" align="left" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></td>
                    <td align="center" class="textoAzulOscuroFondo" scope="col" colspan="5" ><a href="#">DATOS DEL RECEPTOR REAL DEL PREMIO (Si fuera distinto del ganador)</a></td>
                  </tr>
                        <tr>
            			  <td align="right" class="smallVerde" scope="row">Fecha de Nacimiento</td>
                          <td width="250" align="left" class="smallVerde"><?php  abrir_calendario('fecha2','nacimiento', $fecha);?>                          </td>
                          <td width="106" class="smallVerde" align="right">Lugar de Nacimiento</td>
                          <td width="257" class="texto5"><span class="smallRojo">
                          <input name="lugar_nacimiento2" type="text" class="small" id="lugar_nacimiento2" size="50" maxlength="50" />
                          </span></td>
                          <td width="46" class="smallVerde" align="right">Sexo</td>
                          <td width="214" class="texto5">
                          <select name="sexo2" id="sexo2" class="small">
                          	<option value="Masculino">Masculino</option>
                             <option value="Femenino">Femenino</option>
                          </select>                          </td>
                      </tr>
            <tr>
              <td align="right" class="smallVerde" scope="row">Tipo de documento</td>
              <td class="td_detalle"><?php echo armar_combo($rs_tipo_documento2,'id_tipo_documento2',''); ?></td>
              <td class="td_detalle" align="right"><span class="smallVerde">Nro. Documento</span></td>
              <td colspan="3" class="td_detalle"><span class="smallRojo">
                <input name="documento2" type="text" id="documento2" class="small" size="13" maxlength="13" value="<?php echo $_POST['documento2'] ?>" onblur="var texto=$.trim(this.value);if(texto.length!=8 || (isNaN(texto)==true)) {var alerta='Solo ocho digitos - Puede necesitar 0(cero) a la izquierda!!!'; alert(alerta);this.value='';return false;}"  />
                    Identificacion del cliente (Tipo de documento y numero)</span></td>
              </tr>
            <tr>
                        <td align="right" class="smallVerde" scope="row">CUIT/CUIL</td>
                        <td colspan="5" class="smallRojo"><input name="cuit2" type="text" class="small" id="cuit2" size="13" maxlength="13" onblur="var texto=$.trim(this.value);if(texto.length!=11 || (isNaN(texto)==true)) {var alerta='Solo 11 digitos!!!'; alert(alerta);this.value='';return false;}"/>
                          Sin punto ni guion<span class="smallRojo">
                            <label><a href="#" class="smallRojo"  onclick="window.open ('http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx','anses')";><strong>verificar cuit</strong></a></label>
                            </span>
                          <label> </label>
                          <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></td>
                      </tr>

         
              <tr>
              <td align="right" class="smallVerde" scope="row">Apellido</td>
              <td class="td_detalle"><input name="apellido2" type="text" class="small" id="apellido2" size="50" maxlength="50" /></td>
              <td colspan="2" class="smallVerde">Nombre
                <input name="nombre2" type="text" class="small" id="nombre2" size="50" maxlength="50" /></td>
              <td class="td_detalle">&nbsp;</td>
              <td class="td_detalle">&nbsp;</td>
              </tr>
            
            
            <tr align="left">
              <td align="right" class="smallVerde" scope="row">Nacionalidad</td>
              <td colspan="5" align="right" class="texto5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  
                  <td width="21%"><span class="td_detalle">
                    <input name="nacionalidad2" type="text" class="small" id="nacionalidad2" value="Argentina" size="20" maxlength="20" />
                  </span></td>
                  <td width="23%" align="right" class="smallVerde">Profesion</td>
                  <td width="56%" class="smallRojo"> <input name="profesion2" type="text" class="small" id="profesion2" size="30" maxlength="50" />
                    Profesion / Actividad desarrollada / Oficio</td>
                  </tr>
              </table></td>
              </tr>
			<tr align="left">
              <td align="right" class="smallVerde" scope="row">Calle</td>
              <td colspan="7" scope="row" class="smallVerde">
                <input name="calle2" type="text" class="small" id="calle2" size="30"/>
              Nro<input name="numero2" type="text" class="small" id="numero2" size="3"/>Piso
                <input name="piso2" type="text" class="small" id="piso2" size="3"/>
                Dpto
                <input name="dpto2" type="text" class="small" id="dpto2" size="3"/></td>
			</tr>
              
              <tr>
             	<td class="smallVerde" align="right">Persona politicamente expuesta</td>
                <td class="smallVerde" colspan="6"> <div id="persona_politica2"><?php include('persona_politica2.php'); ?></div></td>
             </tr>
              
			<tr align="left">
              <td align="right" class="smallVerde" scope="row">Estado civil</td>
              <td scope="row"><input name="estado_civil2" class="small" type="text"  id="estado_civil2" size="20" maxlength="20" /></td>
              <td scope="row" class="smallVerde" align="right">Telefono</td>
              <td colspan="3" align="left" scope="row" class="smallVerde"><input name="telefono2" class="small" type="text" id="telefono2" size="20" maxlength="20" />Email
                <input name="email2" class="small" type="text" id="email2" size="50" maxlength="50"/></td>
              </tr>
            <tr align="left"><td colspan="6">
                 <table width="100%" border="1" cellspacing="0">
                <tr align="center">
                  <td width="39%" align="center" valign="middle" class="smallVerde"><div align="center">Provincia</div></td>
                  <td width="35%" class="smallVerde"><div align="center">Localidad</div></td>
                  <td width="14%" class="smallVerde">Codigo Postal</td>
                  <td width="12%" class="smallVerde">Extranjero</td>
                </tr>
            <tr>
                  <td >
                    <div align="center">
                       <input name="button3" type="image" id="button3" src="image/folder_16.png" alt="Seleccionar provincia" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/provincias_tooltip2.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_pais2.value,this);  return false; }"/>
                       <input name="provincia2" type="text" class="tdVerde" id="provincia2" value="Cordoba" readonly="yes"/>
                       <input type="hidden" name="cod_provincia2" id="cod_provincia2" value="6"/>
                       <input name="provincia_memo2" type="hidden" id="provincia_memo2" value="Cordoba" />
                       <input name="cod_provincia_memo2" type="hidden" id="cod_provincia_memo2" value="6" />
                       <input type="hidden" name="cod_pais2" id="cod_pais2" value="1"/>
                     </div></td>
                  <td>
                      <div align="center">
                        <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/localidades_tooltip2.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia2.value,this);  return false; } "/>
                        <input name="localidad2" type="text" class="tdVerde" id="localidad2" readonly="yes" value="Capital"/>
                        <input type="hidden" name="cod_localidad2" id="cod_localidad2" value="22121"/>
                        <input name="localidad_memo2" type="hidden" id="localidad_memo2" value="Capital" />
                        <input name="cod_localidad_memo2" type="hidden" id="cod_localidad_memo2" value="22121" />
                      </div></td>
                  <td align="center"><span class="texto3">
                    <input name="cod_postal2" type="text" align="center" class="small" id="cod_postal2" size="15" maxlength="13" />
                    <span class="texto4Totales"><span class="smallRojo">
                    <label><a href="#" class="smallRojo"  onclick="window.open ('http://www.correoargentino.com.ar/consulta_cpa/cons2.php','')";><strong>verificar CP</strong></a></label>
                    </span>
                    <label> </label>
                    <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></span></td>
                  <td align="center"><input type="checkbox" name="chk_extranjero2" id="chk_extranjero2" onclick="if (this.checked){
                  																												form1.provincia2.value=''; form1.cod_provincia2.value=0;
                                                                                                                                form1.localidad2.value=''; form1.cod_localidad2.value=0;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 } else {
                                                                                                                 				form1.provincia2.value=form1.provincia_memo2.value; form1.cod_provincia2.value=form1.cod_provincia_memo2.value;
                                                                                                                                form1.localidad2.value=form1.localidad_memo2.value; form1.cod_localidad2.value=form1.cod_localidad_memo2.value;
                                                                                                                                if (ajax_tooltipObj) ajax_hideTooltip();
                                                                                                                 }" />				  </td>
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
