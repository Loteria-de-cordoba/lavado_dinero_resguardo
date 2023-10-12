<?php session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_POST);
	//print_r($_GET);
	//die();
	//print_r($_SESSION);
	//$db->debug=true;
	//$array_fecha = FechaServer();
	//$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

	//$fecha_desde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	//$fecha_hasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];


	$fecha=$_GET['fecha'];
	

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php

/*if ($_SESSION['suc_ban']==76){
	$_SESSION['suc_ban']=80;
}
*/

//obtengo todos los apostadores
try {
			$rs_cliente = $db ->Execute("select a.id_cliente as codigo, a.apellido  || ' ' || a.nombre 
										 as descripcion from PLA_AUDITORIA.t_cliente a,
										 casino.t_casinos b
										where a.id_casino=b.id_casino
										and a.fecha_baja is null
											and a.usuario_baja is null		
										
										union
										select 0 as codigo, '*NO SE ENCUENTRA **' as descripcion 
										from dual
										union
										select -1 as codigo, '* * * Seleccione * * *' as descripcion 
										from dual
										order by descripcion");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,40,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 
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
	$rs_tipo_pago = $db->Execute("select id_tipo_pago as codigo, descripcion from PLA_AUDITORIA.t_tipo_pago
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
				$documento = "1";
				//$condicion_documento="and g.documento = '$documento'";
			}
		}
		
if (isset($_POST['cantidad'])) {
			$cantidad= $_POST['cantidad'];
			
		}elseif (isset($_GET['cantidad'])) {
			$cantidad = $_GET['cantidad'];
		}
		else
		{
			$cantidad=10;
		}		
	
if (isset($_POST['casino'])) {
			$casino= $_POST['casino'];
			
		}elseif (isset($_GET['casino'])) {
			$casino = $_GET['casino'];
		}		


//llevo a delegacion
	if($casino==0)
	{
		$casino=100;
	}		

if (isset($_POST['apostador'])) {
			$apostador= $_POST['apostador1'];
			
		}elseif (isset($_GET['apostador1'])) {
			$apostador = $_GET['apostador1'];
		}
		else
		{
		//for($i=1;$i<10;$i++)
		//{
			$apostador=-1;
		//}
		}

if (isset($_POST['novedad'])) {
			$novedad= $_POST['novedad'];
			
		}elseif (isset($_GET['novedad'])) {
			$novedad = $_GET['novedad'];
		}	
		else
		{
			$novedad=1;
		}
		//echo $novedad;	
// obtengo datos del apostador
try {
	$rs_apostador = $db->Execute("SELECT  decode(cl.nombre,NULL,initcap(cl.apellido),initcap(cl.apellido) || ', ' || initcap(cl.nombre)) || decode(cl.documento,null,'',' Documento Nro. ' || cl.documento)
									|| ' Registrado en ' || decode(cl.id_casino,100,'Delegacion',ca.n_casino) as datos
								FROM PLA_AUDITORIA.t_cliente cl,
								casino.t_casinos ca
								where ca.id_casino=cl.id_casino
								and cl.id_cliente=?", array($apostador));
	
	
	/*select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));*/
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		if($rs_apostador->RowCount()<>0)
		{
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$datos=utf8_encode($row_apostador->DATOS);
		}
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
  <form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','cliente/procesar_alta_denegado.php',this); return false;">
 	<?php if(!isset($_GET['cantidad']))
	{?>
    <table width="47%" height="53"  border="2" align="center" style="background-color:#999966">
      <tr>
                    
                    <td width="1005" align="center"  colspan="2">Fecha Contable:<?php echo $fecha;?> - ALTA DE PERSONAS SOSPECHADAS<div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /> <a href="#"  onclick="ajax_get('contenido','cliente/adm_denegado.php','fecha=<?php echo $fecha ?>')">Regresar</a></div></td>
                  </tr>
      <tr>
        <tr  class="td2">
     <td    class="td4" scope="col" >Apellido y Nombre - Descripcion</td>
     </tr>
     <tr  class="td2">
      <td width="70"  class="td4" scope="col">Cuit</td>
      </tr>
      <tr  class="td2">
      <td width="70"   class="td4" scope="col">Sexo</td>
      </tr>
      <tr  class="td2">
      <td width="70"   class="td4" scope="col">Observaci&oacute;n Novedad</td>
      </tr>
      
        <td width="150" style="font-size:14px;color:#000000;text-align:right;width:250px !important;padding-top:15px;;font:bold">Cantidad de Movimientos (Minimo 10) </td>
        <td width="50"  style="font-size:14px;color:#000000;width:130px !important;padding-left:5px;padding-top:15px;text-align:center"><input name="cantidad" id="cantidad" type="text" style="font-size:14px;COLOR:#330033;text-align:right;font:bold" size="12" maxlength="12"  value="<?php echo $cantidad;?>" onchange="if(this.value<10) {alert('Minimo 10');this.value=10;return true;}"/>
        </td>
      </tr>
      <tr>
        <td colspan="4" align="center" style="text-align:center"><div style="text-align:center;margin-top:25px;margin-bottom:15px;">
            <input name="buttonx" class="smallTahoma" id="buttonx" style="font-size:11px;color:#333333;font:bold" value="Agregar" type="button" onclick="ajax_get('contenido','cliente/agregar_novedad.php','casino=<?php echo $casino;?>&fecha=<?php echo $fecha;?>&cantidad='+form1.cantidad.value);return false;"/>
        </div></td>
      
    </table>
    <?php
	}
	else
	{
	?>
 	
          <table width="90%" border="0" align="center" cellspacing="0">
          
			<tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>

  	  	<tr align="left"><td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_novedad_casino.php','casino=<?php echo $casino?>&fecha=<?php echo $fecha ?>')">Regresar</a></div></td></tr>
  		<tr><td align="left" class="texto6" scope="row">
    	    
        
    </tr>
    <tr><td align="center" scope="row" ><input name="button" type="submit" class="textoAzulOscuro" id="button" value="Guardar"/></td></tr>
    <tr>
    	<td align="left" scope="row">&nbsp;</td>
    </tr>
<tr>
    	<td align="left" class="texto6" scope="row">
        	<table width="95%" border="0" cellspacing="0" cellpadding="0">
       			<tr>
               	  <td width="1%" scope="col"><table width="565" height="130" border="0" cellpadding="0" cellspacing="1">
       				<tr align="left">
              		<td colspan="12" scope="row">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="textoRojo" scope="col">&nbsp;</td>
                    <td width="1005" align="center" class="small_derecha" scope="col"><?php echo $soydelcasino;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha Contable:<?php echo $fecha;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="ajax_get('contenido','cliente/agregar_novedad.php','&casino=<?php echo $casino;?>&fecha=<?php echo $fecha;?>'); return false;"><img src="image/24px-Crystal_Clear_action_filenew.png" border="0" alt="Formulario Original" width="16" height="16" />  Blanquear Formulario</a></td>
                  </tr>
                  
                  
                  <tr>
                  
                    <td width="19" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></td>
                    <td align="center" class="textoAzulOscuroFondo" scope="col">Agregue Movimientos(Decimales con Punto)</td>
                     <input type="hidden" name="casino" id="casino" value="<?php echo $casino; ?>" />
                     <input type="hidden" name="apostador" id="apostador" value="<?php echo $apostador; ?>"/>
                     <input type="hidden" name="novedad" id="novedad" value="<?php echo $novedad; ?>" />
                     <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>" />
                     <input type="hidden" name="cant" id="cant" value="<?php echo $cantidad; ?>" />
                  </tr>
              	</table></td>
            	</tr>
                  
    <tr align="center" class="td2">
     <td    class="td4" scope="col" colspan="2">Apellido y Nombre - Apodo<br />(Debe Seleccionar una opcion de la lista)</td>
      <td width="70"  class="td4" scope="col">Monto Fichado</td>
      <td width="70"   class="td4" scope="col">Ingresa con Fichas<br />
      en su Poder</td>
      <td width="70"   class="td4" scope="col">Monto Cobrado<br />
      en Caja Publica</td>
      <td width="70"   class="td4" scope="col">Cantidad de Fichas<br />
      con que se retira</td>
      <td width="70"   class="td4" scope="col">Monto que perdio</td>
      <td width="237"  class="td4" colspan="2">Novedad</td>
  </tr>
             <tr>
        <?php //if(!isset($_GET['apostador1']) or $_GET['apostador1']<>0)
			 //if(!isset($_POST['apostador1']) or $_POST['apostador1']<>0)
			 //{?>
                <!--<td  align="right" class="smallVerde" scope="row"><?php //armar_combo_ejecutar_ajax_post($rs_cliente,"apostador1",$apostador,'elimina','cliente/agregar_novedad.php','form1');?>
				<?php //armar_combo_ejecutar_ninguno_ajax_get_puntero($rs_cliente,"apostador1",$apostador,'elimina','cliente/agregar_novedad.php')?></td>-->
              <?php
			  //}
			  //else
			  //{?>
              <td align="left" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador1",$apostador,'dividida1','cliente/paradividida.php','form1');?></td>
             
              <td align="left" class="td_detalle"><div id="dividida1">&nbsp;</div></td>
             <!-- <td align="left" class="td_detalle"><input name="textapostador1" class="small" type="text" id="textapostador1" size="30" maxlength="30"/></td>-->
			  <?php// }
			  ?>
				<td width="70" class="td_detalle"><input name="valor_premio1" class="small_text" type="text" id="valor_premio1" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing1" class="small_text" type="text" id="mficing1" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto1" class="small_text" type="text" id="acierto1" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret1" class="small_text" type="text" id="mficret1" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper1" class="small_text" type="text" id="monper1" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov1" class="small"  id="observamov1"  rows="2" cols="40"/></textarea></td>
                
              </tr>
              <tr>
              <?php $rs_cliente->MoveFirst()?>
                <td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador2",$apostador,'dividida2','cliente/paradividida.php','form1');?></td>
                <td align="left" class="td_detalle"><div id="dividida2">&nbsp;</div></td>
               <!-- <td align="left" class="td_detalle"><input name="textapostador2" class="small" type="text" id="textapostador2" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio2" class="small_text" type="text" id="valor_premio2" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing2" class="small_text" type="text" id="mficing2" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto2" class="small_text" type="text" id="acierto2" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret2" class="small_text" type="text" id="mficret2" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper2" class="small_text" type="text" id="monper2" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov2" class="small"  id="observamov2"  rows="2" cols="40"/></textarea></td>
              </tr>
              <tr>
              <?php $rs_cliente->MoveFirst()?>
                <td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador3",$apostador,'dividida3','cliente/paradividida.php','form1')?></td>
                 <td align="left" class="td_detalle"><div id="dividida3">&nbsp;</div></td>
                <!--<td align="left" class="td_detalle"><input name="textapostador3" class="small" type="text" id="textapostador3" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio3" class="small_text" type="text" id="valor_premio3" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing3" class="small_text" type="text" id="mficing3" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto3" class="small_text" type="text" id="acierto3" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret3" class="small_text" type="text" id="mficret3" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper3" class="small_text" type="text" id="monper3" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov3" class="small"  id="observamov3"  rows="2" cols="40"/></textarea></td>
              </tr>
              <tr>
              <?php $rs_cliente->MoveFirst()?>
               <td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador4",$apostador,'dividida4','cliente/paradividida.php','form1')?></td>
                 <td align="left" class="td_detalle"><div id="dividida4">&nbsp;</div></td>
               <!-- <td align="left" class="td_detalle"><input name="textapostador4" class="small" type="text" id="textapostador4" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio4" class="small_text" type="text" id="valor_premio4" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing4" class="small_text" type="text" id="mficing4" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto4" class="small_text" type="text" id="acierto4" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret4" class="small_text" type="text" id="mficret4" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper4" class="small_text" type="text" id="monper4" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov4" class="small"  id="observamov4"  rows="2" cols="40"/></textarea></td>
              </tr>
              <tr>
              <?php $rs_cliente->MoveFirst()?>
               <td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador5",$apostador,'dividida5','cliente/paradividida.php','form1')?></td>
                 <td align="left" class="td_detalle"><div id="dividida5">&nbsp;</div></td>
          <!--      <td align="left" class="td_detalle"><input name="textapostador5" class="small" type="text" id="textapostador5" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio5" class="small_text" type="text" id="valor_premio5" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing5" class="small_text" type="text" id="mficing5" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto5" class="small_text" type="text" id="acierto5" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret5" class="small_text" type="text" id="mficret5" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper5" class="small_text" type="text" id="monper5" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov5" class="small"  id="observamov5"  rows="2" cols="40"/></textarea></td>
             </tr>
              <tr>
              <?php $rs_cliente->MoveFirst()?>
               <td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador6",$apostador,'dividida6','cliente/paradividida.php','form1')?></td>
                 <td align="left" class="td_detalle"><div id="dividida6">&nbsp;</div></td>
               <!-- <td align="left" class="td_detalle"><input name="textapostador6" class="small" type="text" id="textapostador6" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio6" class="small_text" type="text" id="valor_premio6" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing6" class="small_text" type="text" id="mficing6" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto6" class="small_text" type="text" id="acierto6" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret6" class="small_text" type="text" id="mficre6" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper6" class="small_text" type="text" id="monper6" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov6" class="small"  id="observamov6"  rows="2" cols="40"/></textarea></td>
                
                
              </tr>
              <tr>
              <?php $rs_cliente->MoveFirst()?>
              	<td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador7",$apostador,'dividida7','cliente/paradividida.php','form1')?></td>
                 <td align="left" class="td_detalle"><div id="dividida7">&nbsp;</div></td>
                
                <!--<td align="left" class="td_detalle"><input name="textapostador7" class="small" type="text" id="textapostador7" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio7" class="small_text" type="text" id="valor_premio7" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing7" class="small_text" type="text" id="mficing7" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto7" class="small_text" type="text" id="acierto7" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret7" class="small_text" type="text" id="mficret7" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper7" class="small_text" type="text" id="monper7" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov7" class="small"  id="observamov7"  rows="2" cols="40"/></textarea></td>
                
             </tr>
             
              <tr>
              <?php $rs_cliente->MoveFirst()?>
              	<td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador8",$apostador,'dividida8','cliente/paradividida.php','form1')?></td>
                 <td align="left" class="td_detalle"><div id="dividida8">&nbsp;</div></td>
                
                <!--<td align="left" class="td_detalle"><input name="textapostador7" class="small" type="text" id="textapostador7" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio8" class="small_text" type="text" id="valor_premio8" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing8" class="small_text" type="text" id="mficing8" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto8" class="small_text" type="text" id="acierto8" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret8" class="small_text" type="text" id="mficret8" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper8" class="small_text" type="text" id="monper8" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov8" class="small"  id="observamov8"  rows="2" cols="40"/></textarea></td>
                
             </tr>
             
              <tr>
              <?php $rs_cliente->MoveFirst()?>
              	<td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador9",$apostador,'dividida9','cliente/paradividida.php','form1')?></td>
                 <td align="left" class="td_detalle"><div id="dividida9">&nbsp;</div></td>
                
                <!--<td align="left" class="td_detalle"><input name="textapostador7" class="small" type="text" id="textapostador7" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio9" class="small_text" type="text" id="valor_premio9" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing9" class="small_text" type="text" id="mficing9" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto9" class="small_text" type="text" id="acierto9" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret9" class="small_text" type="text" id="mficret9" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper9" class="small_text" type="text" id="monper9" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov9" class="small"  id="observamov9"  rows="2" cols="40"/></textarea></td>
                
             </tr>
             
              <tr>
              <?php $rs_cliente->MoveFirst()?>
              	<td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador10",$apostador,'dividida10','cliente/paradividida.php','form1')?></td>
                 <td align="left" class="td_detalle"><div id="dividida10">&nbsp;</div></td>
                
                <!--<td align="left" class="td_detalle"><input name="textapostador7" class="small" type="text" id="textapostador7" size="30" maxlength="30"/></td>-->
				<td width="70" class="td_detalle"><input name="valor_premio10" class="small_text" type="text" id="valor_premio10" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing10" class="small_text" type="text" id="mficing10" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto10" class="small_text" type="text" id="acierto10" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret10" class="small_text" type="text" id="mficret10" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper10" class="small_text" type="text" id="monper10" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov10" class="small"  id="observamov10"  rows="2" cols="40"/></textarea></td>
                
             </tr>
             
             <?php //agrego mas mov. masivos
			 
			 $cantidad_imputable=$_GET['cantidad']+1;
			 		for ($i = 11; $i < $cantidad_imputable; $i++)
						{?>
                        	<tr>
								<?php $rs_cliente->MoveFirst();
								$valor_nuevo="valor_premio".$i;
								$ingreso_nuevo="mficing".$i;
								$acierto_nuevo="acierto".$i;
								$retira_nuevo="mficret".$i;
								$pierde_nuevo="monper".$i;
								$observa_nuevo="observamov".$i;
								$div_div="dividida".$i;
								
								?>
                                <td  align="right" class="smallVerde" scope="row"><?php armar_combo_ejecutar_ajax_get($rs_cliente,"apostador".$i,$apostador,'dividida'.$i,'cliente/paradividida.php','form1')?></td>
                                     <td align="left" class="td_detalle"><div id="<?php echo $div_div;?>">&nbsp;</div></td>
                                    
                                    <!--<td align="left" class="td_detalle"><input name="textapostador7" class="small" type="text" id="textapostador7" size="30" maxlength="30"/></td>-->
                                    <td width="70" class="td_detalle"><input name="<?php echo $valor_nuevo;?>" class="small_text" type="text" id="<?php echo $valor_nuevo;?>" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                                    <td width="70" class="td_detalle"><input name="<?php echo $ingreso_nuevo;?>" class="small_text" type="text" id="<?php echo $ingreso_nuevo;?>" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                                    <td width="70" class="td_detalle"><input name="<?php echo $acierto_nuevo;?>" class="small_text" type="text" id="<?php echo $acierto_nuevo;?>" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                                    <td width="70" class="td_detalle"><input name="<?php echo $retira_nuevo;?>" class="small_text" type="text" id="<?php echo $retira_nuevo;?>" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                                    <td width="70" class="td_detalle"><input name="<?php echo $pierde_nuevo;?>" class="small_text" type="text" id="<?php $pierde_nuevo;?>" size="14" maxlength="14" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                                    <td width="237" class="td_detalle" colspan="2"><textarea name="<?php echo $observa_nuevo;?>" class="small"  id="<?php echo $observa_nuevo;?>"  rows="2" cols="40"/></textarea></td>

                         	</tr>			
						 <?php
						 }//fin del for
			 ?>
              </table>      
             
             
             
             
             
             </td></tr>
             <tr><td>&nbsp;</td></tr>
             <tr><td>&nbsp;</td></tr>
            <tr align="left">
              <td align="center" scope="row" ><input name="button" type="submit" class="textoAzulOscuro" id="button" value="Guardar"/></td>

              <td width="500" align="center" class="small_derecha" scope="col"><a href="#" onclick="ajax_get('contenido','cliente/cerrar_dia_casino.php','&casino=<?php echo $casino;?>&fecha=<?php echo $fecha;?>'); return false;"><img src="image/logo_lavado4.jpg" border="0" alt="Cierra Fichaje del Dia" width="50" height="50" />Cerrar el Dia&nbsp;&nbsp;&nbsp;<?php echo $fecha;?></a></td>
          
              </tr>
                  </table>             	  
       			</form>
  <?php //}
  }//cierro el if else que permite cargar cantidad de movimientos
  
  
  ?>