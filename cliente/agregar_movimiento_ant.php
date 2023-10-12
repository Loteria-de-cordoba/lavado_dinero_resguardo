<?php session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_POST);
	print_r($_GET);
	//die();
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
	$rs_tipo_pago = $db->Execute("select id_tipo_pago as codigo, descripcion from lavado_dinero.t_tipo_pago
										where id_tipo_pago not in(3,4) ");
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
				$documento = "1";
				//$condicion_documento="and g.documento = '$documento'";
			}
		}
		
if (isset($_POST['casino'])) {
			$casino= $_POST['casino'];
			
		}elseif (isset($_GET['casino'])) {
			$casino = $_GET['casino'];
		}		

if (isset($_POST['apostador'])) {
			$apostador= $_POST['apostador'];
			
		}elseif (isset($_GET['apostador'])) {
			$apostador = $_GET['apostador'];
		}

if (isset($_POST['novedad'])) {
			$novedad= $_POST['novedad'];
			
		}elseif (isset($_GET['novedad'])) {
			$novedad = $_GET['novedad'];
		}			
// obtengo datos del apostador
try {
	$rs_apostador = $db->Execute("SELECT  cl.apellido || ', ' || cl.nombre || ' Documento Nro. ' || cl.documento
									|| ' Registrado en ' || ca.n_casino as datos
								FROM lavado_dinero.t_cliente cl,
								casino.t_casinos ca
								where ca.id_casino=cl.id_casino
								and cl.id_cliente=?", array($apostador));
	
	
	/*select direccion, cuenta_bancaria
								from lavado_dinero.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));*/
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$datos=$row_apostador->DATOS;
		//echo $datos;die();


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
  <form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','cliente/procesar_alta_movimiento.php',this); return false;">
 <table width="90%" border="0" align="center" cellspacing="0">
 
  	  	<tr align="left"><td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_novedad.php','casino=<?php echo $casino;?>&apostador=<?php echo $apostador;?>')">Regresar</a></div></td></tr>
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
               	  <td width="1%" scope="col"><table width="99%" height="130" border="0" cellpadding="0" cellspacing="1">
       				<tr align="left">
              		<td colspan="12" scope="row">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="textoRojo" scope="col">&nbsp;</td>
                    <td width="1005" align="center" class="small_derecha" scope="col">
                    <a href="#" onclick="ajax_get('contenido','cliente/agregar_movimiento.php','&apostador=<?php echo $apostador;?>&casino=<?php echo $casino;?>'); return false;"><img src="image/24px-Crystal_Clear_action_filenew.png" border="0" alt="Formulario Original" width="16" height="16" />  Blanquear Formulario</a></td>
                  </tr>
                  
                  
                  <tr>
                  
                    <td width="19" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></td>
                    <td align="center" class="textoAzulOscuroFondo" scope="col">Movimiento asignado a<br /><?php echo $datos?></td>
                     <input type="hidden" name="casino" id="casino" value="<?php echo $casino; ?>" />
                     <input type="hidden" name="apostador" id="apostador" value="<?php echo $apostador; ?>" />
                     <input type="hidden" name="novedad" id="novedad" value="<?php echo $novedad; ?>" />
                  </tr>
              	</table></td>
            	</tr>
                <tr><td>&nbsp;</td></tr>
             <tr><td>&nbsp;</td></tr>
                <tr>
                <td width="69" align="right" class="smallVerde" scope="row">Fecha Mov.: </td>
<!--<td align="left" class="smallVerde" scope="row">&nbsp;</td>-->
                                                <td width="137" align="left" class="smallVerde" scope="row"><?php  abrir_calendario('fecha_pago','premio', $fecha);?></td>            
                  <td width="105" class="smallVerde" align="right">Tipo de Movimiento</td>
    <td width="76" class="smallVerde">
<select name="sexo" id="sexo" class="small" onChange="if(sexo.value=='Acierto'){
                        	ajax_get('pagos','cliente/tipo_pago_ambos.php','pago='+form1.sexo.value); return false; 
                            } else {
                            	ajax_get('pagos','cliente/tipo_pago_efectivo.php','pago='+form1.sexo.value); return false;
                                }">
                          	<option value="Fichaje" selected="selected">Fichaje</option>
                            <option value="Acierto">Acierto</option>
                          </select></td>
               <input type="hidden" name="tipomov" id="tipomov" value="form1.sexo.value" />            
              <td width="103" align="right" class="smallVerde" scope="row">Instrumento de pago</td>
              <td align="right" colspan="5" ><div id="pagos"><?php  include('tipo_pago_efectivo.php'); ?></div></td>
                  <!--<td width="14%" align="right" class="texto5" scope="row">&nbsp;</td>-->
<!--<td colspan="5" class="td_detalle"><?php// echo armar_combo($rs_tipo_pago,'id_tipo_pago',''); ?></td>
                              <td width="134" align="left" ><div id="tipo_pago"><?php // include('tipo_pago.php'); ?></div></td>-->                                               
                    
                  <td width="41" align="right" class="smallVerde" scope="row">Monto</td>
<!--  <td width="2" align="right" class="smallVerde" scope="row">&nbsp;</td>-->
                              <td width="73" class="td_detalle"><input name="valor_premio" class="small_text" type="text" id="valor_premio" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                  </tr>
                  <tr>
                  <td colspan="11">&nbsp;</td>
                  <td width="94" align="right" class="smallRojo" scope="row">Decimales con Punto</td>
               </tr>
               <tr>
              
                </tr>
                
              </table> </td> 
            </tr>
            <tr><td>&nbsp;</td></tr>
             <tr><td>&nbsp;</td></tr>
            <tr align="left">
              <td align="center" scope="row" colspan="8"><input name="button" type="submit" class="textoAzulOscuro" id="button" value="Guardar"/></td>
            </tr>
              	  	<tr align="left"><td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_novedad.php','casino=<?php echo $casino;?>&apostador=<?php echo $apostador;?>')">Regresar</a></div></td></tr>
             </table>             	  
       			</form>