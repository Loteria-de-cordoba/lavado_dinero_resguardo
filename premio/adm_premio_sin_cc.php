<?php  	session_start();
//print_r($_GET);
//echo $_SESSION['permiso'];
//echo (basename($_SERVER['PHP_SELF']));

if(basename($_SERVER['PHP_SELF'])=="index.php") {
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	$paginator="paginator_adodb_oracle.inc.php";}
else {
	include("../jscalendar-1.0/calendario.php");
	include("../db_conecta_adodb.inc.php");
	include("../funcion.inc.php");
	$paginator="../paginator_adodb_oracle.inc.php";
	
	//$db->debug=true;
}
$array_fecha = FechaServer();	
$variables = array();
$ganador = null;	

if ($_SESSION['permiso']=='ADMINISTRA' ||$_SESSION['permiso']=='ADM_CONFORMA' ||$_SESSION['permiso']=='ADM_CASINO') {

	 if (isset($_GET['fecha'])) {
			$fecha = $_GET['fecha'];
	   }
		else 
			{
			if (isset($_POST['fecha'])) {
				$fecha = $_POST['fecha'];
				}
				 else {
					$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
			          }
			}
			
		if (isset($_GET['fhasta'])) {
				$fhasta = $_GET['fhasta'];
		  } else {
				if (isset($_POST['fhasta'])) {
					$fhasta = $_POST['fhasta'];
					} else {
						$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
						   }
					}
		
		
		
	if($_SESSION['permiso']=='ADM_CONFORMA'){	
		try {
			$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33)");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 	
	
	
	if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and a.suc_ban in ($suc_ban)";
				
			} elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
						$suc_ban = 0;
						$condicion_sucursal = "and a.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33)";
					} 
			
			elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
						$suc_ban = $_POST['suc_ban'];
						$condicion_sucursal = "and a.suc_ban in ($suc_ban)";
					} 
					 elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']==0) {
						$suc_ban = 0;
						$condicion_sucursal = "and a.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33)";
					} 
					
					else {
							$suc_ban = 0;
							$condicion_sucursal = "and a.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33)";
							
						 }

	
	
	
			
	} 
	
	
		
	
	if($_SESSION['permiso']=='ADM_CASINO'){
		try {
			$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81)");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 	
			
			
		if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and a.suc_ban in ($suc_ban)";
				
			} elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
						$suc_ban = 0;
						$condicion_sucursal = "and a.suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81)";
					} 
			
			elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
						$suc_ban = $_POST['suc_ban'];
						$condicion_sucursal = "and a.suc_ban in ($suc_ban)";
					} 
					 elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']==0) {
						$suc_ban = 0;
						$condicion_sucursal = "and a.suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81)";
					} 
					
					else {
							$suc_ban = 0;
							$condicion_sucursal = "and a.suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81)";
							
						 }
		}
		
	 
				
	if (isset($_POST['conformado'])&& $_POST['conformado']==0 ) {
			$conformado = $_POST['conformado'];

			$condicion_conforma="and a.conformado ='$conformado'";
}elseif (isset($_POST['conformado'])&& $_POST['conformado']==1 ) {
				$conformado = $_POST['conformado'];
				$condicion_conforma="and a.conformado ='$conformado'";
} elseif (isset($_GET['conformado'])&& $_GET['conformado']==0 ) {
			$conformado = $_GET['conformado'];
			$condicion_conforma="and a.conformado ='$conformado'";
} elseif (isset($_GET['conformado'])&& $_GET['conformado']==1 ) {
				$conformado = $_GET['conformado'];
				$condicion_conforma="and a.conformado ='$conformado'";
}else {
				$conformado = 1;
				$condicion_conforma="and a.conformado ='$conformado'";
}	
		
	/*	elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
		 				$suc_ban = 0;
						$condicion_sucursal = "";}
		*/
		
 
 
 
 try {
				$rs_totales = $db -> Execute("select sum(valor_premio) as importe
							from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
							where a.suc_ban = b.suc_ban 
							and a.suc_ban=$suc_ban
							and fecha_alta between to_date('$fecha','DD/MM/YYYY') and to_date('$fhasta','DD/MM/YYYY')");
				}
				catch (exception $e)
				{
				die ($db->ErrorMsg()); 
				} 
			$row_totales = $rs_totales->FetchNextObject($toupper=true);
 
 
			/*try {
				$rs_totales = $db -> Execute("select sum(valor_premio) as importe
							from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
							where a.suc_ban = b.suc_ban 
							and to_date(fecha) = to_date('$fecha','DD/MM/YYYY')");
				}
				catch (exception $e)
				{
				die ($db->ErrorMsg()); 
				} 
			$row_totales = $rs_totales->FetchNextObject($toupper=true);*/
			//$_SESSION['script'] =  basename($_SERVER['PHP_SELF']);	
			///////////////////////////////////////////////////////////////////////	
		 //array de variables bind
			//$variables[1]= "50"; //array de variables bind
			//$variables[2]= "51"; //array de variables bind
			//$ariables[3]=$cod_juego; //array de variables bind
			
			///////////////////////////////////////////////////////////////////////////////////////////
						
	
} else if ($_SESSION['permiso']=='OPERADOR'||$_SESSION['permiso']=='OP_UNICO' ){
	//include("jscalendar-1.0/calendario.php");
 		if (isset($_GET['fecha'])) {
				$fecha = $_GET['fecha'];
		} else {
			if (isset($_POST['fecha'])) {
				$fecha = $_POST['fecha'];
			} else {
				$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
			}
		}
		
		if (isset($_GET['fhasta'])) {
				$fhasta = $_GET['fhasta'];
		} else {
			if (isset($_POST['fhasta'])) {
				$fhasta = $_POST['fhasta'];
			} else {
				$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
			}
		}
		
		try {
			$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33)");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 
			
			
			if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
			} else {
		 
					if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
						$suc_ban = $_POST['suc_ban'];
						$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
					} else {
						$suc_ban=$_SESSION['suc_ban'];
						$condicion_sucursal = "and b.suc_ban in ($suc_ban)";		
					}
			}
			
	if ($suc_ban==72){
	//$suc_ban=81;
	$condicion_sucursal = "and (b.suc_ban in ($suc_ban) or b.suc_ban=81)";	
}		
			
			
				
	 
		  	//$suc_ban=$_SESSION['suc_ban'];
			//$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
				 
		 
 				try {
				$rs_totales = $db -> Execute("select sum(valor_premio) as importe
							from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
							where a.suc_ban = b.suc_ban 
							and a.suc_ban=$suc_ban
							and fecha_alta between to_date('$fecha','DD/MM/YYYY') and to_date('$fhasta','DD/MM/YYYY')");
				}
				catch (exception $e)
				{
				die ($db->ErrorMsg()); 
				} 
			$row_totales = $rs_totales->FetchNextObject($toupper=true);
 
	 
	}

?>	
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<!--<form action="" method="post" enctype="multipart/form-data" name="formulario" id="formulario">-->

<?php 
if ($_SESSION['permiso']=='ADMINISTRA' ||$_SESSION['permiso']=='ADM_CONFORMA' ||$_SESSION['permiso']=='ADM_CASINO') {
//$db->debug=true;
$_pagi_sql ="select a.id_ganador, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha, initcap(a.nombre) nombre , initcap(a.apellido) apellido, 
				initcap(b.nombre)  as casa, valor_premio, a.concepto, a.conformado, c.juegos, observacion
      			from PLA_AUDITORIA.t_ganador a, juegos.sucursal b, juegos.juegos c
				where a.suc_ban = b.suc_ban 
				and a.juego=c.id_juegos
				$condicion_sucursal
				$condicion_conforma
				and fecha_alta between to_date('$fecha','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
				order by fecha desc";




	
/* $_pagi_sql ="select a.id_ganador, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha,initcap(a.nombre) nombre , initcap(a.apellido) apellido,  initcap(b.nombre)  as casa,valor_premio
      			from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
				where a.suc_ban = b.suc_ban  
				$condicion_sucursal
				and to_date(fecha) = to_date('$fecha','DD/MM/YYYY')
				order by fecha";
*/ 
$_pagi_div = "contenido";
$_pagi_enlace = "premio/adm_premio.php";
$_pagi_cuantos = 15; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=3;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='fecha';
$_pagi_propagar[2]='fhasta';
$_pagi_propagar[3]='conformado'; 
include($paginator); 
	?>
<style type="text/css">
<!--
.Estilo1 {color: #000000}
-->
</style>
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','premio/adm_premio_sin_cc.php',this); return false;">
<table width="89%"  align="center">
  <tr valign="bottom" class="td8" >
	  <td width="65" align="center" valign="middle" class="td2"  scope="col">B&uacute;squeda:</td>
      <?php if ($_SESSION['permiso']=='ADM_CONFORMA_SIN_CC'){	  ?> 
	  <td width="69" align="right" valign="middle" class="td2"  scope="col">Delegaci&oacute;n</td>
      <td width="17" valign="middle" class="td2" scope="col"><?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>
	<?php
       } else if ($_SESSION['permiso']=='ADM_CONFORMA'){	  ?> 
	  <td width="69" align="right" valign="middle" class="td2"  scope="col">Delegaci&oacute;n</td>
      <td width="17" valign="middle" class="td2" scope="col"><?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>
<?php }
			else if ($_SESSION['permiso']=='ADM_CASINO'){?>	
         <td width="43" align="right" valign="middle" class="td2"  scope="col">Casino</td>
      <td width="17" valign="middle" class="td2" scope="col"><?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>
   <?php }?>
            
      <td width="128"  valign="middle" class="td2" scope="col"><select name="conformado" class="small" id="conformado">
          <option value="1" <?php if ($conformado==1) echo 'selected';?>>conformado</option>
          <option value="0" <?php if ($conformado==0) echo 'selected';?>>no conformado</option>
          </select>
      </td>  
      <td width="44" valign="middle" class="td2"  scope="col">Fecha desde </td>
      <td width="91" valign="middle" class="td2" scope="col"><?php  abrir_calendario('fecha','premio', $fecha); ?></td>
      <td width="41" valign="middle" class="td2" scope="col">Fecha hasta</td>
      <td width="83" valign="middle" class="td2" scope="col">
        <?php  abrir_calendario('fhasta','premio', $fhasta); ?>
      </a></td>
      <?php if ($_SESSION['permiso']!='ADM_CASINO') {?>
   	 <td width="66" class="td2" scope="col"><div align="center"><img src="image/s_okay.png" alt="Nuevo Premio" width="16" height="16" /> <a href="#" onclick="ajax_get('contenido','premio/validar_dni_ganador.php','');">Alta de datos personales</a></div></td><?php }?>
	  <td width="126" class="td2" scope="col">
		
		<img src="image/xmag.gif" alt="Buscar" width="16" height="16" border="0" />
	  <input type="submit" value="Buscar" /></td>
    </tr>
</table>
</form>
<?php if ($_pagi_result->RowCount()==0) {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','blanco.php','')\">Regresar</a></div>");
}
?>
<table width="76%" border="0" align="center">
<tr>
  <td colspan="8" align="center" valign="bottom" scope="col"><table width="100%" border="0">
              <tr>
                <td align="center" class="textoRojo" >Premios Pagados</td>
                <td width="1%">
                <a href="#" onclick="window.open('list/premios_pagados.php','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
              </tr>
            </table></td>
  </tr>
		 <tr>
          <td colspan="7" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
        <tr align="center" class="td2">
          <td width="12%" class="td4" scope="col">Fecha</td>
          <td width="30%" class="td4" scope="col">Apellido y Nombre</td>
          <?php if ($_SESSION['permiso']=='ADM_CONFORMA') {?>
          <td width="11%" class="td4" scope="col">Delegaci&oacute;n</td>
          <?php } else if ($_SESSION['permiso']=='ADM_CASINO') {?>
          <td width="11%" class="td4" scope="col">Casino</td>
          <?php }?>
          <td width="15%" class="td4" scope="col">Importe</td>
          <td width="12%" class="td4" scope="col">Im&aacute;genes</td>
           <td class="td4" scope="col">Datos Ganador</td>
           <td width="12%" class="td4" scope="col">Observaci&oacute;n</td>
  </tr>
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
           <td align="center"><?php echo $row->FECHA;?></td>
           <td align="left"><?php echo trim($row->APELLIDO).' '.trim($row->NOMBRE);?></td>
           <td align="left"><?php echo utf8_decode($row->CASA);?></td>
           <td align="right" ><?php echo number_format($row->VALOR_PREMIO,2,',','.');?><?php  $total=$total+$row->VALOR_PREMIO;?></td>
                   
          
        <td align="center" ><a href="#" onclick="ajax_showTooltip('premio/mostrar_imagen.php?jsfecha='+new Date()+'&id_ganador=<?php echo $row->ID_GANADOR ?>',this); return false;" ><img src="image/download.png" alt="ver archivos"  width="20" height="20" border="0"/></a></td>
        <td align="center" ><a href="#" onclick="ajax_get('contenido','premio/modificar_premio.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&casa=<?php echo $suc_ban ; ?>'); return false;" ><img src="image/modificar.png" alt="modificar datos"  width="20" height="20" border="0"/></a>
        <a href="#" onclick="window.open('list/datos_ganadores.php?id_ganador=<?php echo $row->ID_GANADOR ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Adobe Reader 7.png" alt="Imprimir" width="20" height="20" border="0" /></a></td>
		
        <!--<?php//if ($row -> CONFORMADO == 0) {?> <td width="12%" align="center" ><a href="#" onclick="ajax_get('contenido','premio/modificar_premio.php','id_ganador=<?php // echo $row->ID_GANADOR ?>'); return false;" ><img src="image/modificar.png" alt="modificar datos"  width="20" height="20" border="0"/></a></td>
      <?php // }  
	//	else {?>
		  <td width="8%" align="center" ></a><img src="image/candado.png" alt="No se puede modificar, ganador fue conformado" width="20" height="20" border="0" />	<?php //}?>       </td>
-->  
	<td align="center" >
    
	  <?php  if ($row->OBSERVACION==0){?>
         
      <a href="#" onclick="ajax_get('contenido','premio/procesar_datos.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=1&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/SyncCenter.png" alt="Datos Completos" width="24" height="23" border="0" /></a>
      <?php } else {?>
  		   <a href="#" onclick="ajax_get('contenido','premio/procesar_datos.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=0&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/Error.png" alt="Datos Incompletos" width="24" height="23" border="0" /></a>
		  
      <?php  }?>
      <a href="#" onclick="ajax_get('contenido','premio/nueva_nota_observacion.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/app_48.png" alt="Añadir Nota" width="24" height="23" border="0" /></a>      </td>
</tr>    
<?php  }?>

		 <tr>
          <td colspan="7" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
</table>
<table width="75%" border="0" align="center">
<tr class="texto3FondoRosa">
   	  <td width="65%" align="right">TOTAL $</td>
   	  <td width="7%" align="right" ><?php echo number_format($total,2,',','.');?></td>
    <td width="28%" align="left" >&nbsp;</td>
  </tr>
</table>
<?php } else if ($_SESSION['permiso']=='OPERADOR'||$_SESSION['permiso']=='OP_UNICO' ) {
//$db->debug=true;
$_pagi_sql ="select a.id_ganador, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha, initcap(a.nombre) nombre , initcap(a.apellido) apellido, 
				initcap(b.nombre)  as casa, valor_premio, a.concepto, a.conformado, c.juegos
      			from PLA_AUDITORIA.t_ganador a, juegos.sucursal b, juegos.juegos c
				where a.suc_ban = b.suc_ban 
				and a.juego=c.id_juegos
				$condicion_sucursal
				and fecha_alta between to_date('$fecha','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
				order by fecha desc";
//ho $_pagi_sql.$_SESSION['fecha'];	 
$_pagi_div = "contenido";
$_pagi_enlace = "premio/adm_premio.php";
$_pagi_cuantos = 10; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=3;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='fecha';
$_pagi_propagar[2]='fhasta';
//$_pagi_propagar[3]='total';
 include_once($paginator); 
 ?>
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','premio/adm_premio.php',this); return false;">
 <table width="789"   align="center">
  <tr valign="bottom" class="td4">
      <!--<td valign="bottom" class="td2"  scope="col">Delegaci&oacute;n</td>
      <td width="39" valign="middle" class="td2" scope="col"><?php //armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>-->
      <td width="59" valign="bottom" class="td2"  scope="col">Fecha desde </td>
      <td width="180" valign="bottom" class="td2"  scope="col"><?php  abrir_calendario('fecha','premio', $fecha); ?></td>
      <td width="54" valign="bottom" class="td2" scope="col">Fecha hasta</td>
      <td width="167" valign="bottom" class="td2" scope="col"><?php  abrir_calendario('fhasta','premio', $fhasta); ?></td>
	  <td width="122" class="td2" scope="col"><div align="center"><img src="image/s_okay.png" alt="Nuevo Premio" width="16" height="16" /> <a href="#" onclick="ajax_get('contenido','premio/validar_dni_ganador.php','');">Alta de datos personales</a></div></td>
	  <td width="83" class="td2" scope="col">
		
		<img src="image/xmag.gif" alt="Buscar" width="16" height="16" border="0" />
      <input type="submit" value="Buscar" /></td>
    </tr>
</table>
</form>
<?php if ($_pagi_result->RowCount()==0) {
		die ("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span></div>");
	}
?>
<table width="80%" border="0" align="center">
	<tr>
  		<td colspan="9" align="center" valign="bottom" scope="col">
        <table width="100%" border="0">
        	<tr>
            	<td align="center" class="textoRojo" >Premios Pagados</td>
                <td width="1%"><a href="#" class="Estilo3" onclick="window.open('list/premios_pagados.php?cod_juego=<?php echo $cod_juego ?>&suc_ban=<?php echo $suc_ban ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
          </tr>
        </table></td>
 	</tr>
	<tr>
    	<td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
    <tr align="center" class="td2">
    	<td width="9%" class="td4" scope="col">Fecha</td>
      <td width="29%" class="td4" scope="col">Apellido y Nombre</td>
        
        <td width="28%" class="td4" scope="col">Concepto</td>
        <td width="10%" class="td4" scope="col">Importe</td>
        <td width="12%" class="td4" scope="col">Im&aacute;genes</td>
        <td width="12%" colspan="2" class="td4" scope="col">Modificar</td>
  </tr>
    <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	<tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
    	<td align="center"><?php echo $row->FECHA;?></td>
        <td align="left"><?php echo utf8_encode(trim($row->APELLIDO)).', '.utf8_encode($row->NOMBRE);?></td>
        <td align="left"><?php echo utf8_encode($row->CONCEPTO);?></td>
        <td align="right" ><?php echo number_format($row->VALOR_PREMIO,2,',','.');
		 
		$total=$total+$row->VALOR_PREMIO;
		?></td>
        <td align="center" ><a href="#" onclick="ajax_showTooltip('premio/mostrar_imagen.php?jsfecha='+new Date()+'&id_ganador=<?php echo $row->ID_GANADOR ?>',this); return false;" ><img src="image/download.png" alt="ver archivos"  width="20" height="20" border="0"/></a></td>
        
		<?php if ($row -> CONFORMADO == 0) {?> <td align="center" ><a href="#" onclick="ajax_get('contenido','premio/modificar_premio.php','id_ganador=<?php echo $row->ID_GANADOR ?>'); return false;" ><img src="image/modificar.png" alt="modificar datos"  width="20" height="20" border="0"/></a></td>
		  <?php }  
		else {?>
		  <td align="center" ></a><img src="image/candado.png" alt="No se puede modificar, ganador fue conformado" width="20" height="20" border="0" />	<?php }?>
      
       </td>
  </tr>    
<?php }?>

  
  
</table>
<table width="80%" border="0" align="center">
	<tr class="texto3FondoRosa">
    	<td width="66%" align="right">TOTAL $</td>
   	  <td width="10%" align="right" ><?php echo number_format($total,2,',','.');?></td>
      <td width="24%" align="left" >&nbsp;</td>
  </tr>
</table>

<?php   } else { echo ("ERROR DE INGRESO"); }?>
<?php $_SESSION['sqlreporte']= $_pagi_sql; ?>