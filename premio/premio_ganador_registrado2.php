<?php session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_POST);
	//print_r($_GET);
	//print_r($_SESSION);
	//$db->debug=true;
	//echo $_SESSION['suc_ban'];
	
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
	$rs_tipo_documento2 = $db->Execute("SELECT id_tipo_documento AS codigo,  descripcion
										FROM lavado_dinero.t_tipo_documento
										WHERE id_tipo_documento NOT IN (2,3,4)");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}

	
try {
	$rs_moneda = $db->Execute("select id_moneda as codigo, descripcion from lavado_dinero.t_moneda ");
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
//$mostrar_juego=0;
//if ($_SESSION['suc_ban']==1 || $_SESSION['suc_ban']==27|| $_SESSION['suc_ban']==23|| $_SESSION['suc_ban']==25|| $_SESSION['suc_ban']==34|| $_SESSION['suc_ban']==26|| $_SESSION['suc_ban']==30|| $_SESSION['suc_ban']==20|| $_SESSION['suc_ban']==21|| $_SESSION['suc_ban']==31|| $_SESSION['suc_ban']==24|| $_SESSION['suc_ban']==33|| $_SESSION['suc_ban']==22|| $_SESSION['suc_ban']==32){
	try {
		$rs_juego = $db->Execute("select id_juegos as codigo,juegos as descripcion from juegos.juegos where activo = 1 order by 2 ");
		}
		catch  (exception $e) 
		{ 
		die($db->ErrorMsg());
		}
    //$juegos=25;
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
				$ganador = $id_ganador;
				$condicion_ganador="and g.id_ganador = '$ganador'";
			}
		}
		
try {
	$rs_consulta = $db->Execute("select g.id_ganador,to_char(g.fecha_nacimiento,'dd/mm/yyyy')fecha_nacimiento,g.lugar_nacimiento,g.sexo, g.id_tipo_documento, 
									g.documento, g.cuit, g.apellido, g.nombre, g.nacionalidad,g.id_localidad,g.profesion, g.calle, g.numero, g.piso, g.dpto, g.politico,
									g.cheque_nro, g.nro_ticket, g.cod_postal, g.sorteo_nro,g.estado_civil, g.telefono, g.email,
								    g.cargo, g.autoridad, g.invocado, g.denominacion_juridica,g.valor_premio, g.id_moneda, m.descripcion, g.concepto, g.juego, g.id_tipo_pago, to_char(g.fecha_alta,'DD/MM/YYYY') as fecha_alta, 
									g.domicilio_pago, g.cuenta_bancaria_salida,to_char(g.fecha_nacimiento2,'dd/mm/yyyy')fecha_nacimiento2,g.lugar_nacimiento2,g.sexo2,
									g.documento2, g.cuit2, g.apellido2, g.nombre2, g.nacionalidad2,g.id_localidad2,g.profesion2, g.calle2, g.numero2, g.piso2, g.dpto2, 
									g.politico2,g.cargo2, g.autoridad2, g.invocado2, g.denominacion_juridica2,g.cod_postal2, g.estado_civil2, g.telefono2, g.email2,							 
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

}

if ($row->ID_LOCALIDAD2==0){
    $pais2=''; 
	$cod_pais2=0;
    $provincia2=''; 
	$cod_provincia2=0;
    $localidad2=''; 
	$cod_localidad2=0;
    $check2='checked=checked';
} else {
	$pais2=$row->N_PAIS; 
	$cod_pais2=$row->ID_PAIS;
	$provincia2=$row->N_PROVINCIA; 
	$cod_provincia2=$row->ID_PROVINCIA ;
    $localidad2=$row->N_LOCALIDAD ; 
	$cod_localidad2=$row->ID_LOCALIDAD;
	$check2='';
	
	
	/*echo('provincia'.$provincia);
	echo($cod_provincia);
	echo('localidad'.$localidad);
	echo($cod_localidad);*/
}




try {
	$rs_suc_ban = $db->Execute("select direccion, cuenta_bancaria
								from lavado_dinero.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}

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
              
 		
    
      <?php 
	  while ($i<$_SESSION['cantidadroles'])  {

		$i=$i+1;

	  
	  if ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA') {
     // $_SESSION['bandera']=1;?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio_administra.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&conformado=<?php echo $_GET['conformado'] ; ?>&suc_ban=<?php echo $_GET['casa'] ?>')">Regresar</a></div></td>
      <?php }
	  elseif ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA' || $_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') {?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_POST['fecha']; ?>&fhasta=<?php echo $_POST['fhasta']; ?>&suc_ban=<?php echo $_POST['suc_ban'] ?>&conformado=<?php echo $_POST['conformado'] ; ?>')">Regresar </a></div></td>
      <?php }
	  
	   else {?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_POST['fecha']; ?>&fhasta=<?php echo $_POST['fhasta']; ?>&suc_ban=<?php echo $_POST['suc_ban'] ?>&conformado=<?php echo $_POST['conformado'] ; ?>')">Regresar </a></div></td>
      <?php }?>
      <?php }?>
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
                    <td align="center" ><a href="#" onclick="ajax_showTooltip('premio/mostrar_imagen_ganador.php?jsfecha='+new Date()+'&id_ganador=<?php echo $row->ID_GANADOR ?>',this); return false;" ><img src="image/download.png" alt="ver archivos"  width="20" height="20" border="0"/><span class="texto3"> Ver imagenes cargadas</span></a></td>
                  </tr>
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
                          <td align="right" class="smallVerde" scope="row">Fecha de Nacimiento</td>
                          <td width="26%" align="left" class="smallVerde"><?php  abrir_calendario('fecha','nacimiento', $row->FECHA_NACIMIENTO);?>                          </td>
                          <td width="8%" align="right" class="smallVerde">Lugar de Nacimiento</td>
                          <td colspan="2" align="left" class="smallVerde">
                          <input name="lugar_nacimiento" type="text" class="small" id="lugar_nacimiento" size="50" maxlength="50" value="<?php echo $row->LUGAR_NACIMIENTO ?>" />                          </td>
                          <td width="3%" class="smallVerde" align="right">Sexo</td>
                          <td width="14%" class="texto5">
                          <select name="sexo" id="sexo" class="small">
                          	<option  value="Masculino" <?php if ($row->SEXO=="Masculino") echo 'selected=selected;'?>>Masculino</option>
                             <option value="Femenino"  <?php if ($row->SEXO=="Femenino") echo 'selected=selected;'?>>Femenino</option>
                          </select>                          </td>
                      </tr>
               <tr>
              <td width="8%" align="right" class="smallVerde" scope="row"><div align="right">Tipo de documento  </div></td>
              <td class="td_detalle"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',$row->ID_TIPO_DOCUMENTO); ?></td>
              <td class="td_detalle" align="right"><span class="smallVerde">Nro. Documento</span></td>
              <td colspan="4" align="left" class="td_detalle"><span class="smallRojo">
                <input name="documento" type="text" id="documento" class="small" size="13" maxlength="13" value="<?php echo $row->DOCUMENTO; ?>" onblur="var texto=$.trim(this.value);if(texto.length!=8 || (isNaN(texto)==true)) {var alerta='Solo ocho digitos - Puede necesitar 0(cero) a la izquierda!!!'; alert(alerta);this.value='<?php echo $row->DOCUMENTO;?>';return false;}"    />
                 Identificacion del cliente (Tipo de documento y numero)</span></td>
              </tr>
                      <tr>
                        <td align="right" class="smallVerde" scope="row">CUIT/CUIL/CDI</td>
                        <td colspan="6" class="smallRojo"><input name="cuit" type="text" class="small" id="cuit" size="13" maxlength="13" value="<?php echo $row->CUIT; ?>" onblur="var texto=$.trim(this.value);if(texto.length!=11 || (isNaN(texto)==true)) {var alerta='Solo 11 digitos!!!'; alert(alerta);this.value='<?php echo $row->CUIT;?>';return false;}" />
                          Sin punto ni guion<span class="smallRojo">
                            <label><a href="#" class="smallRojo"  onclick="window.open ('http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx','anses')";><strong>verificar cuit</strong></a></label>
                            </span>
                          <label> </label>
                          <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></span></td>
                      </tr>
            <tr>
              <td align="right" class="smallVerde" scope="row">Apellido</td>
              <td class="td_detalle"><input name="apellido" type="text" class="small" id="apellido" size="50" maxlength="50" value="<?php echo $row->APELLIDO; ?>" /></td>
              <td colspan="3" class="smallVerde">Nombre
                <input name="nombre" type="text" class="small" id="nombre" size="50" maxlength="50" value="<?php echo $row->NOMBRE; ?>" /></td>
              <td class="td_detalle">&nbsp;</td>
              <td class="td_detalle">&nbsp;</td>
            </tr>
            
            <tr align="left">
              <td align="right" class="smallVerde" scope="row">Nacionalidad</td>
              <td colspan="6" align="right" class="texto5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  
                  <td width="11%"><span class="td_detalle">
                    <input name="nacionalidad" type="text" class="small" id="nacionalidad" value="<?php echo utf8_encode($row->NACIONALIDAD); ?>" size="20" maxlength="20" />
                  </span></td>
                  <td width="18%">&nbsp;</td>
                  <td width="7%" align="right" class="smallVerde">Profesion</td>
                  <td width="64%" class="smallRojo" align="left"> <input name="profesion" type="text" class="small" id="profesion" size="30" maxlength="50" value="<?php echo $row->PROFESION ?>" />
                 Profesion / Actividad desarrollada / Oficio</td>
                  </tr>
              </table></td>
              </tr>
            <tr align="left">
              <td align="right" class="smallVerde" scope="row">Calle</td>
              <td colspan="6" class="smallVerde" scope="row">
              
              <?php 
			  if($row->CALLE==''){
			  $domicilio=$row->DOMICILIO;
			  }else {
			  	$domicilio=$row->CALLE;
			  }
			  ?>
              
                <input name="calle" type="text" class="small" id="calle" size="30" value="<?php echo $domicilio; ?>"/>
                Nro
                <input name="numero" type="text" class="small" id="numero" size="3" value="<?php echo $row->NUMERO ?>"/>
Piso
<input name="piso" type="text" class="small" id="piso" size="3" value="<?php echo $row->PISO ?>"/>
Dpto
<input name="dpto" type="text" class="small" id="dpto" size="3" value="<?php echo $row->DPTO ?>"/>
               <!--Persona politicamente expuesta 
                <select name="politico" id="politico" class="small">
                  <option value="NO" <?php if($row->POLITICO=="NO") echo 'selected=selected;'?>>NO</option> 
                  <option value="SI" <?php if($row->POLITICO=="SI") echo 'selected=selected;'?>>SI</option>
                </select>-->
                </td>
              </tr>
              
              <!--<tr>
             	<td class="smallVerde" align="right">Persona politicamente expuesta</td>
                <td class="smallVerde" colspan="6"> <div id="persona_politica"><?php //include('persona_politica.php'); ?></div></td>
             </tr>-->
            <tr>
             	<td class="smallVerde" align="right">Persona politicamente expuesta</td>
                <td class="smallVerde" colspan="6"> <div id="persona_politica"><?php include('persona_politica.php'); ?></div></td>
                
                
             </tr> 
              
            <tr align="left">
            <td align="right" class="smallVerde" scope="row">DDJJ Nro:</td>
              <td colspan="9" scope="row" class="smallVerde"><input name="ddjj" class="small" type="text"  id="ddjj" size="3" maxlength="4" />Estado civil
                <input name="estado_civil" class="small" type="text"  id="estado_civil" size="20" maxlength="20" value="<?php echo $row->ESTADO_CIVIL ?>" />
                <span class="smallVerde">Telefono</span>                <input name="telefono" class="small" type="text" id="telefono" size="20" maxlength="20"  value="<?php echo $row->TELEFONO ?>" />
                <span class="smallVerde">Email
                <input name="email" class="small" type="text" id="email" size="50" maxlength="50" value="<?php echo $row->EMAIL ?>"/>
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
                       <input name="provincia" type="text" class="tdVerde" id="provincia" value="<?php echo $provincia; ?>" readonly="yes"/>
                       <input type="hidden" name="cod_provincia" id="cod_provincia" value="6"/>
                       <input name="provincia_memo" type="hidden" id="provincia_memo" value="Cordoba" />
                       <input name="cod_provincia_memo" type="hidden" id="cod_provincia_memo" value="6" />
                       <input type="hidden" name="cod_pais" id="cod_pais" value="1"/>
                     </div></td>
                  <td>
                      <div align="center">
                        <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/localidades_tooltip.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia.value,this);  return false; } "/>
                        <input name="localidad" type="text" class="tdVerde" id="localidad" readonly="yes" value="<?php echo $localidad; ?>"/>
                        <input type="hidden" name="cod_localidad" id="cod_localidad" value="22121"/>
                        <input name="localidad_memo" type="hidden" id="localidad_memo" value="Capital" />
                        <input name="cod_localidad_memo" type="hidden" id="cod_localidad_memo" value="22121" />
                      </div></td>
                  <td align="center"><span class="texto3">
                    <input name="cod_postal" type="text" align="center" class="small" id="cod_postal" size="15" maxlength="13" value="<?php echo $row->COD_POSTAL; ?>" />
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
                              <td align="left" class="smallVerde" scope="row"><?php  abrir_calendario('fecha_pago','premio',$fecha);?></td>
                              <td align="left" class="smallVerde" scope="row">Sorteo Nro<span class="texto5">
                                <input name="sorteo_nro2" id="sorteo_nro2" class="small" size="15"/>
                              </span></td>
                              <td colspan="2" align="left" class="smallVerde" scope="row">&nbsp;</td>
                          </tr>
                            <tr>
                              <td width="87" align="right" class="smallVerde" scope="row">Valor del Premio</td>
                              <td width="2" align="right" class="smallVerde" scope="row">&nbsp;</td>
                              <td width="100" class="td_detalle"><input name="valor_premio" class="small" type="text" id="valor_premio" size="14" maxlength="14"/></td>
                              <td width="379" class="smallVerde">Numero de Ticket/Cupon/Billete
                                <input name="nro_ticket" class="small" type="text"  id="nro_ticket" size="14" maxlength="14"/>  </td>
                              <td colspan="2" class="td_detalle"><p class="texto5"><span class="texto4Totales">.</span></p>  </td>
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
                                <textarea name="concepto" id="concepto" cols="50" rows="3" class="small" ></textarea>
                                Descripcion del premio</label>
                              <div align="left"></div></td>
                              <td width="205" class="td_detalle"><table width="93%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                              <td width="75" align="right" class="smallVerde">Juego</td>
                                              <td width="124" class="td_detalle"><?php echo armar_combo($rs_juego,'juego',''); ?>                              </td>
                                </tr>
                              </table></td>
                          </tr>
                            
                            <tr>
                              <td align="right" class="smallVerde" scope="row">Instrumento de pago</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="5" class="td_detalle"><div id="tipo_pago"><?php include('tipo_pago.php'); ?></div></td>
                          </tr>
                            <tr>
                              <td align="right" class="smallVerde" scope="row">Domicilio</td>
                              <td align="right" class="texto5" scope="row">&nbsp;</td>
                              <td colspan="5" class="smallRojo"><input name="domicilio_pago" readonly="readonly" type="text" class="small" id="domicilio_pago" size="70" maxlength="70"  value="<?php echo $row1->DIRECCION ?>" /> Domicilio donde se adjudico el premio</td>
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
                          <td width="250" align="left" class="smallVerde"><?php  abrir_calendario('fecha2','nacimiento', $row->FECHA_NACIMIENTO2);?>                          </td>
                          <td width="106" class="smallVerde" align="right">Lugar de Nacimiento</td>
                          <td width="257" class="texto5"><span class="smallRojo">
                          <input name="lugar_nacimiento2" type="text" class="small" id="lugar_nacimiento2" size="50" maxlength="50" value="<?php echo $row->LUGAR_NACIMIENTO2 ?>" />
                          </span></td>
                          <td width="46" class="smallVerde" align="right">Sexo</td>
                          <td width="214" class="texto5">
                          <select name="sexo2" id="sexo2" class="small">
                          	<option value="Masculino" <?php if ($row->SEXO2=="Masculino") echo 'selected=selected;'?>>Masculino</option>
                             <option value="Femenino" <?php if ($row->SEXO2=="Femenino") echo 'selected=selected;'?>>Femenino</option>
                          </select>                          </td>
                      </tr>
            <tr>
              <td width="73" align="right" class="smallVerde" scope="row"><div align="right">Tipo de documento  </div></td>
              <td class="td_detalle"><?php echo armar_combo($rs_tipo_documento2,'id_tipo_documento2',$row->ID_TIPO_DOCUMENTO2); ?></td>
              <td class="td_detalle" align="right"><span class="smallVerde">Documento</span></td>
              <td colspan="3" class="td_detalle"><span class="smallRojo">
                <input name="documento2" type="text" id="documento2" class="small" size="13" maxlength="13" value="<?php echo $row->DOCUMENTO2 ?>" onblur="var texto=$.trim(this.value);if(texto.length!=8 || (isNaN(texto)==true)) {var alerta='Solo ocho digitos - Puede necesitar 0(cero) a la izquierda!!!'; alert(alerta);this.value='<?php echo $row->DOCUMENTO2;?>';return false;}"   />
                    Identificacion del cliente (Tipo de documento y numero)</span></td>
              </tr>
            <tr>
                        <td align="right" class="smallVerde" scope="row">CUIT/CUIL</td>
                        <td colspan="5" class="smallRojo"><input name="cuit2" type="text" class="small" id="cuit2" size="13" maxlength="13" value="<?php echo $row->CUIT2 ?>" onblur="var texto=$.trim(this.value);if(texto.length!=11 || (isNaN(texto)==true)) {var alerta='Solo 11 digitos!!!'; alert(alerta);this.value='<?php echo $row->CUIT2;?>';return false;}"  />
                          Sin punto ni guion<span class="smallRojo">
                            <label><a href="#" class="smallRojo"  onclick="window.open ('http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx','anses')";><strong>verificar cuit</strong></a></label>
                            </span>
                          <label> </label>
                          <img src="image/xmag.gif" alt="Buscar" width="22" height="18" border="0" /></td>
                      </tr>

         
              <tr>
              <td align="right" class="smallVerde" scope="row">Apellido</td>
              <td class="td_detalle"><input name="apellido2" type="text" class="small" id="apellido2" size="50" maxlength="50" value="<?php echo $row->APELLIDO2 ?>"/></td>
              <td colspan="2" class="smallVerde">Nombre
                <input name="nombre2" type="text" class="small" id="nombre2" size="50" maxlength="50" value="<?php echo $row->NOMBRE2 ?>" /></td>
              <td class="td_detalle">&nbsp;</td>
              <td class="td_detalle">&nbsp;</td>
              </tr>
            
            
            <tr align="left">
              <td align="right" class="smallVerde" scope="row">Nacionalidad</td>
              <td colspan="5" align="right" class="texto5" scope="row"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  
                  <td width="21%"><span class="td_detalle">
                    <input name="nacionalidad2" type="text" class="small" id="nacionalidad2" value="<?php echo $row->NACIONALIDAD2 ?>" size="20" maxlength="20" />
                  </span></td>
                  <td width="23%" align="right" class="smallVerde">Profesion</td>
                  <td width="56%" class="smallRojo"> <input name="profesion2" type="text" class="small" id="profesion2" size="30" maxlength="50" value="<?php echo $row->PROFESION2 ?>" />
                    Profesion / Actividad desarrollada / Oficio</td>
                  </tr>
              </table></td>
              </tr>
			<tr align="left">
              <td align="right" class="smallVerde" scope="row">Calle</td>
              <td colspan="7" scope="row" class="smallVerde">
                <input name="calle2" type="text" class="small" id="calle2" size="30" value="<?php echo $row->CALLE2 ?>"/>
              Nro<input name="numero2" type="text" class="small" id="numero2" size="3" value="<?php echo $row->NUMERO2 ?>"/>Piso
                <input name="piso2" type="text" class="small" id="piso2" size="3" value="<?php echo $row->PISO2 ?>"/>
                Dpto
                <input name="dpto2" type="text" class="small" id="dpto2" size="3"value="<?php echo $row->DPTO2 ?>"/>
                </td>
              </tr>
              <!--<tr>
             	<td class="smallVerde" align="right">Persona politicamente expuesta</td>
                <td class="smallVerde" colspan="6"> <div id="persona_politica2"><?php //include('persona_politica2.php'); ?></div></td>
             </tr>-->
	<tr align="left">
              <td align="right" class="smallVerde" scope="row">Estado civil</td>
              <td scope="row"><input name="estado_civil2" class="small" type="text"  id="estado_civil2" size="20" maxlength="20" value="<?php echo $row->ESTADO_CIVIL2 ?>" /></td>
              <td scope="row" class="smallVerde" align="right">Telefono</td>
              <td colspan="3" align="left" scope="row" class="smallVerde"><input name="telefono2" class="small" type="text" id="telefono2" size="20" maxlength="20" value="<?php echo $row->TELEFONO2 ?>" />Email
                <input name="email2" class="small" type="text" id="email2" size="50" maxlength="50" value="<?php echo $row->EMAIL2 ?>"/></td>
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
                       <input name="provincia2" type="text" class="tdVerde" id="provincia2" value="<?php echo $provincia2?>" readonly="yes"/>
                       <input type="hidden" name="cod_provincia2" id="cod_provincia2" value="6"/>
                       <input name="provincia_memo2" type="hidden" id="provincia_memo2" value="Cordoba" />
                       <input name="cod_provincia_memo2" type="hidden" id="cod_provincia_memo2" value="6" />
                       <input type="hidden" name="cod_pais2" id="cod_pais2" value="1"/>
                     </div></td>
                  <td>
                      <div align="center">
                        <input name="button4" type="image" id="button4" src="image/folder_16.png" alt="Seleccionar localidad" width="16" height="16" onclick="if (chk_extranjero.checked) {return false;} else {ajax_showTooltip('premio/localidades_tooltip2.php?jsfecha='+new Date()+'&amp;formulario='+this.form.name+'&amp;codigo='+cod_provincia2.value,this);  return false; } "/>
                        <input name="localidad2" type="text" class="tdVerde" id="localidad2" readonly="yes" value="<?php echo $localidad2?>"/>
                        <input type="hidden" name="cod_localidad2" id="cod_localidad2" value="22121"/>
                        <input name="localidad_memo2" type="hidden" id="localidad_memo2" value="Capital" />
                        <input name="cod_localidad_memo2" type="hidden" id="cod_localidad_memo2" value="22121" />
                      </div></td>
                  <td align="center"><span class="texto3">
                    <input name="cod_postal2" type="text" align="center" class="small" id="cod_postal2" size="15" maxlength="13" value="<?php echo $row->COD_POSTAL2?>" />
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
              <td align="center" scope="row">
              
              <input name="button" type="submit" class="textoAzulOscuro" id="button" value="Guardar" />
              <input type="hidden" name="id_ganador" id="id_ganador" value="<?php echo $row->ID_GANADOR; ?>" />
              <input type="hidden" name="fdesde" id="fdesde" value="<?php echo $_GET['fdesde']; ?>" />
              <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta']; ?>" />
              <input type="hidden" name="conformado" id="conformado" value="<?php echo $_GET['conformado']; ?>" />
              <input type="hidden" name="casa" id="casa" value="<?php echo $_GET['casa']; ?>" />
              </td>
            </tr>
            <tr align="left">
              
 		
    
      <?php 
	  while ($i<$_SESSION['cantidadroles'])  {

		$i=$i+1;

	  
	  if ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA') {
     // $_SESSION['bandera']=1;?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio_administra.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&conformado=<?php echo $_GET['conformado'] ; ?>&suc_ban=<?php echo $_GET['casa'] ?>')">Regresar</a></div></td>
      <?php }
	  elseif ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA' || $_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') {?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&suc_ban=<?php echo $_GET['casa'] ?>&conformado=<?php echo $_GET['conformado'] ; ?>')">Regresar </a></div></td>
      <?php }
	  
	   else {?>
      <td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png"  alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&suc_ban=<?php echo $_GET['casa'] ?>&conformado=<?php echo $_GET['conformado'] ; ?>')">Regresar</a></div></td>
      <?php }?>
      <?php }?>
    </tr>

        </table>
        </form>
