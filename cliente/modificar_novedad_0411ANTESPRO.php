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

$id=$_GET['id_id'];
$casino=$_GET['casino'];

// obtengo datos del CASINO
try {
	$rs_apostador = $db->Execute("SELECT  N_CASINO as datos
									FROM CASINO.T_CASINOS
									WHERE ID_CASINO=?", array($casino));}
								catch (exception $e){die ($db->ErrorMsg());} 	
	
	
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$soydelcasino=$row_apostador->DATOS;
	
	
	$fecha=$_GET['fecha'];
	
//controlo que este dia no este cerrado
try {
			$rs_cerrado = $db ->Execute("SELECT count(*) as cuenta
				 FROM lavado_dinero.t_novedad_casino a
				WHERE (a.confirmado='S' or a.confirmado='s')
				and a.fecha_novedad = to_date('$fecha','dd/mm/yyyy')
				and a.id_casino=$casino");
			}	
	catch (exception $e){die ($db->ErrorMsg());} 
	$row_cerrado =$rs_cerrado->FetchNextObject($toupper=true);
	$contar=$row_cerrado->CUENTA;


if($contar==0)//el dia no esta cerrado
{
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php

/*if ($_SESSION['suc_ban']==76){
	$_SESSION['suc_ban']=80;
}
*/

//obtengo el apostador
try {
			$rs_cliente = $db ->Execute("select a.id_cliente as codigo, initcap(a.apellido)  || ' ' || initcap(a.nombre) 
										 as descripcion,
										 c.fichaje as fichaje,
										 c.mon_ing_fic as ingresa,
										 c.acierto as acierto,
										 c.mon_fic_ret as retira,
										 c.mon_perdido as perdido,
										 c.observa_mov as observa 
										 from lavado_dinero.t_cliente a,
										 	   lavado_dinero.t_novedad_casino	c
										where a.id_cliente=c.id_cliente
												and c.id_novedad_casino=?
												
										",array($id));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,40,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 

	if($rs_cliente->RowCount()<>0)
		{
		$row_apostador =$rs_cliente->FetchNextObject($toupper=true);
		$datos=utf8_decode($row_apostador->DESCRIPCION);
		$fichaje=$row_apostador->FICHAJE;
		$ingresa=$row_apostador->INGRESA;
		$acierto=$row_apostador->ACIERTO;
		$retira=$row_apostador->RETIRA;
		$perdido=$row_apostador->PERDIDO;
		$observa=$row_apostador->OBSERVA;
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
  <form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','cliente/procesar_modificar_novedad.php',this); return false;">
 <table width="90%" border="0" align="center" cellspacing="0">
 	<tr><td>&nbsp;</td></tr>

  	  	<tr align="left"><td align="center" scope="row"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_novedad_casino.php','casino=<?php echo $casino?>&fecha=<?php echo $fecha ?>')">Regresar</a></div></td></tr>
  		<tr><td align="left" class="texto6" scope="row">
    	    
        
    </tr>
    <tr>
    	<td align="left" scope="row">&nbsp;</td>
    </tr>
    <tr>
    	<td align="left" class="texto6" scope="row">
        	<table width="95%" border="0" cellspacing="0" cellpadding="0">
       			<tr>
               	  <td width="1%" scope="col"><table width="1048" height="130" border="0" cellpadding="0" cellspacing="1">
       				<tr align="left">
              		<td colspan="12" scope="row">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="textoRojo" scope="col">&nbsp;</td>
                    <td width="1005" align="center" class="small_derecha" scope="col"><?php echo $soydelcasino;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha Contable:<?php echo $fecha;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="ajax_get('contenido','cliente/modificar_novedad.php','&casino=<?php echo $casino;?>&fecha=<?php echo $fecha;?>&id_id=<?php echo $id;?>'); return false;"><img src="image/24px-Crystal_Clear_action_filenew.png" border="0" alt="Formulario Original" width="16" height="16" />  Blanquear Formulario</a></td>
                  </tr>
                  
                  
                  <tr>
                  
                    <td width="19" scope="col"><img src="image/s_okay.png" alt="Alta de datos personales" width="16" height="16" /></td>
                    <td align="center" class="textoAzulOscuroFondo" scope="col">Modifica Movimiento(Decimales con Punto)</td>
                     <input type="hidden" name="casino" id="casino" value="<?php echo $casino; ?>" />
                     <input type="hidden" name="apostador" id="apostador" value="<?php echo $apostador; ?>"/>
                     <input type="hidden" name="novedad" id="novedad" value="<?php echo $novedad; ?>" />
                     <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>" />
                     <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                  </tr>
              	</table></td>
            	</tr>
                  
    <tr align="center" class="td2">
     <td    class="td4" scope="col" colspan="2">Apellido y Nombre - Apodo</td>
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
              <td align="left" class="smallVerde" colspan="2"><?php echo $datos;?></td>
             
               <!--<td align="left" class="td_detalle"><div id="dividida1">&nbsp;</div></td>
             <td align="left" class="td_detalle"><input name="textapostador1" class="small" type="text" id="textapostador1" size="30" maxlength="30"/></td>-->
			  <?php// }
			  ?>
				<td width="70" class="td_detalle"><input name="valor_premio1" class="small_text" type="text" id="valor_premio1" size="14" maxlength="14" value="<?php echo $fichaje?>" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficing1" class="small_text" type="text" id="mficing1" size="14" maxlength="14" value="<?php echo $ingresa;?>"  onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>       
                <td width="70" class="td_detalle"><input name="acierto1" class="small_text" type="text" id="acierto1" size="14" maxlength="14" value="<?php echo $acierto;?>" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="mficret1" class="small_text" type="text" id="mficret1" size="14" maxlength="14" value="<?php echo $retira;?>" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="70" class="td_detalle"><input name="monper1" class="small_text" type="text" id="monper1" size="14" maxlength="14" value="<?php echo $perdido;?>" onchange="if(this.value<0) {alert('El Monto debe ser superior a 0');return true;}"/></td>
                <td width="237" class="td_detalle" colspan="2"><textarea name="observamov1" class="small"  id="observamov1"  rows="2" cols="40"/><?php echo $observa;?></textarea></td>
                
              </tr>
              
              </table>      
             
             
             
             
             
             </td></tr>
             <tr><td>&nbsp;</td></tr>
             <tr><td>&nbsp;</td></tr>
         <tr align="left">
              <td align="center" scope="row" ><input name="button" type="submit" class="textoAzulOscuro" id="button" value="Modificar"/></td>

              <!--<td width="500" align="center" class="small_derecha" scope="col"><a href="#" onclick="ajax_get('contenido','cliente/cerrar_dia_casino.php','&casino=<?php echo $casino;?>&fecha=<?php echo $fecha;?>'); return false;"><img src="image/logo_lavado4.jpg" border="0" alt="Cierra Fichaje del Dia" width="50" height="50" />Cerrar el Dia&nbsp;&nbsp;&nbsp;<?php echo $fecha;?></a></td>
          -->
              </tr>
                  </table>             	  
       			</form>
  <?php }
  else//el dia esta cerrado
  {?>
  <table width="90%" border="0" align="center" cellspacing="0">
 	<tr><td>&nbsp;</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>&nbsp;</td></tr>
  	  	<tr align="left"><td align="center" scope="row" class="td9Grande"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /><a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_novedad_casino.php','')"><?php echo $fecha;?>***Jornada Cerrada <?php echo $soydelcasino?>***</a></td></tr></table>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
  		
    	    
        
    </tr>
  
  <?php }?>