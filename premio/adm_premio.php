<?php session_start();
//print_r($_GET);
//print_r($_SESSION['permiso']);
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}

$i=0;
$_SESSION['xxx']=0;
while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC') || ($_SESSION['rol'.$i]=='LAVADO_DINERO_CONFORMA_TODO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO') || ($_SESSION['rol'.$i]=='LAVADO_DINERO_CONF_DELE'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 

//print_r($_GET);
//print_r($_POST);
//$db->debug=true;
//echo $suc_ban.'sucban';
$array_fecha = FechaServer();	

while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;
	//print_r($_SESSION['rol'.$j]);
	if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO' || $_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' || ($_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO') ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OP_UNICO'||$_SESSION['rol'.$j]=='LAVADO_DINERO_CONF_DELE') 
		{
		
		 if (isset($_GET['fecha'])) {$fecha = $_GET['fecha']; }
			else {if (isset($_POST['fecha'])) {	$fecha = $_POST['fecha'];}
					 else {	$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"]; }
				}
			
		 if (isset($_GET['fhasta'])) {$fhasta = $_GET['fhasta'];}
			 else {if (isset($_POST['fhasta'])) {$fhasta = $_POST['fhasta'];}
			 		 else {	$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
			      }
						
		 if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='LAVADO_DINERO_CONF_DELE'){	
			try {
				$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,51)");
				}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
				catch (exception $e){die ($db->ErrorMsg()); } 	
				
		 if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
						$suc_ban = $_GET['suc_ban'];
						$condicion_sucursal = "and a.suc_ban in ($suc_ban)";
						
					} elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
								$suc_ban = 0;
								$condicion_sucursal = "and a.suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,51)";
							} 
					
					elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
								$suc_ban = $_POST['suc_ban'];
								$condicion_sucursal = "and a.suc_ban in ($suc_ban)";
							} 
							 elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']==0) {
								$suc_ban = 0;
								$condicion_sucursal = "and a.suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,51)";
							} 
							
							else {
									$suc_ban = 0;
									$condicion_sucursal = "and a.suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,51)";
									
								 }
			}
				
				
				
		 if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' || $_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO'){	
		// echo('ENTRAAAAAA');
			try {
				$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51) order by suc_ban");
				}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
				catch (exception $e){die ($db->ErrorMsg()); } 	
	
			 if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
						$suc_ban = $_GET['suc_ban'];
						$condicion_sucursal = "and a.suc_ban in ($suc_ban)";
						
					} elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
								$suc_ban = 0;
								$condicion_sucursal = "and a.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
							} 
					
					elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
								$suc_ban = $_POST['suc_ban'];
								$condicion_sucursal = "and a.suc_ban in ($suc_ban)";
							} 
							 elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']==0) {
								$suc_ban = 0;
								$condicion_sucursal = "and a.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
							} 
							
							else {
									$suc_ban = 0;
									$condicion_sucursal = "and a.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
									
								 }
		} 
	
		if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO'){
			try {
				$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81) order by suc_ban");
				}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
				catch (exception $e){die ($db->ErrorMsg()); } 	
			
			
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
		
	 
				
				
	/*	elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
		 				$suc_ban = 0;
						$condicion_sucursal = "";}
		*/
		
 
 		
 
		/* try {
				$rs_totales = $db -> Execute("select sum(valor_premio) as importe
							from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
							where a.suc_ban = b.suc_ban 
							and a.suc_ban=$suc_ban
							and fecha_alta between to_date('$fecha','DD/MM/YYYY') and to_date('$fhasta','DD/MM/YYYY')");
				}
				catch (exception $e){die ($db->ErrorMsg()); } 
				$row_totales = $rs_totales->FetchNextObject($toupper=true);
		*/ 
 
									
	
} if ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR'||$_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO'||$_SESSION['rol'.$i]=='LAVADO_DINERO_CONF_DELE' ){
	
 		
		
		if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}
		 	else {	if (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}
					 else {	$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
				 }
		
		if (isset($_GET['fhasta'])) {$fhasta = $_GET['fhasta'];}
			 else {	if (isset($_POST['fhasta'])) {$fhasta = $_POST['fhasta'];}
			 		 else {	$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
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
			
		 
	 
}
//echo 'llega';
if ($suc_ban==72){
		//$suc_ban=81;
		$condicion_sucursal = "and (b.suc_ban in ($suc_ban) or b.suc_ban=81)";	
		}		
			
		try {
				$rs_totales = $db -> Execute("select sum(valor_premio) as importe
							from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
							where a.suc_ban = b.suc_ban 
							and a.suc_ban=$suc_ban
							and fecha_alta between to_date('$fecha','DD/MM/YYYY') and to_date('$fhasta','DD/MM/YYYY')");
				}
				catch (exception $e){die ($db->ErrorMsg()); } 
				$row_totales = $rs_totales->FetchNextObject($toupper=true);



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
						$conformado = 0;
						$condicion_conforma="and a.conformado ='$conformado'";
		}	






?>	
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<?php 
if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' || $_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OP_UNICO'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OPERADOR'||$_SESSION['rol'.$j]=='LAVADO_DINERO_CONF_DELE') {
//$db->debug=true;
//echo('ENTRA');
$_pagi_sql ="select a.id_ganador, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha, a.nombre nombre , a.apellido apellido, 
				initcap(b.nombre)  as casa, valor_premio, a.concepto, a.conformado, c.juegos, observacion, b.suc_ban, a.politico, a.ddjj
      			from PLA_AUDITORIA.t_ganador a, juegos.sucursal b, juegos.juegos c
				where a.suc_ban = b.suc_ban 
				and a.juego=c.id_juegos
				$condicion_sucursal
				$condicion_conforma
				and a.fecha_baja is null
				and a.usuario_baja is null
				and fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
				order by b.suc_ban, fecha desc";

$_pagi_div = "contenido";
$_pagi_enlace = "premio/adm_premio.php";
$_pagi_cuantos = 25; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='fecha';
$_pagi_propagar[2]='fhasta';
$_pagi_propagar[3]='conformado'; 
 
if(basename($_SERVER['PHP_SELF'])=='index.php'){ 
	include("paginator_adodb_oracle.inc.php");
} else {
		include("../paginator_adodb_oracle.inc.php");
		}	
	
	
	
$_SESSION['nro_pagina']=$_pagi_actual ;

	?>
<style type="text/css">
<!--
.Estilo1 {color: #000000}
-->
</style>
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','premio/adm_premio.php',this); return false;">
<table width="89%"  align="center">
  <tr valign="bottom" class="td8" >
	  <!--<td width="58" align="center" valign="middle" class="td2"  scope="col">B&uacute;squeda:</td>-->
      <?php if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' || $_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO'){	  ?> 
	  <td width="50" align="right" valign="middle" class="td2"  scope="col">Delegaci&oacute;n</td>
      <td width="22" valign="middle" class="td2" scope="col"><?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>
	<?php
       } else if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA'){	  ?> 
	  <td width="50" align="right" valign="middle" class="td2"  scope="col">Delegaci&oacute;n</td>
      <td width="24" valign="middle" class="td2" scope="col"><?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>
<?php }
			else if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO'){?>	
         <td width="34" align="right" valign="middle" class="td2"  scope="col">Casino</td>
      <td width="23" valign="middle" class="td2" scope="col"><?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>
   <?php }?>
            
      <td width="126"  valign="middle" class="td2" scope="col" align="center"><select name="conformado" class="small" id="conformado">
          <option value="1" <?php if ($conformado==1) echo 'selected';?>>conformado</option>
          <option value="0" <?php if ($conformado==0) echo 'selected';?>>no conformado</option>
          </select>
      </td>  
      <td width="28" valign="middle" class="td2"  scope="col">Fecha desde </td>
      <td width="148" valign="middle" class="td2" scope="col"><?php  abrir_calendario('fecha','premio', $fecha); ?></td>
      <td width="28" valign="middle" class="td2" scope="col">Fecha hasta</td>
      <td width="148" valign="middle" class="td2" scope="col">
        <?php  abrir_calendario('fhasta','premio', $fhasta); ?>
      </a></td>
      <?php if ($_SESSION['rol'.$j]!='ROL_LAVADO_DINERO_ADM_CASINO') {?>
   	 <td width="118" class="td2" scope="col" align="right"><div align="center"><img src="image/s_okay.png" title="Nuevo Premio" width="16" height="16" /> <a href="#" onclick="ajax_get('contenido','premio/validar_dni_ganador.php','fecha=<?php echo $fecha ?>&fhasta=<?php echo $fhasta ?>&conformado=<?php echo $conformado ?>&suc_ban=<?php echo $suc_ban; ?>');">Alta de datos personales</a></div></td><?php }?>
	  <td width="54" class="td2" scope="col" align="right"> <input type="submit" value="Buscar" /></td>
    </tr>
</table>
</form>
<?php if ($_pagi_result->RowCount()==0) {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','blanco.php','')\">Regresar</a></div>");
}
?>
<table width="76%" border="0" align="center">
<tr>
	<td colspan="8" align="center" valign="bottom" class="texto4" scope="col"><a href="#" onclick="ajax_get('contenido','premio/lista_planillas.php','')">Descargar Planillas <img src="image/jamembo-jumpto.png" title="Descargar Planillas" width="28" height="28" border="0" /></a></td>
</tr>
  	
<tr><?php if(($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC')||($_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO')||  ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA')) {?>
    <td width="5%"><a href="#" onclick="window.open('list/delegaciones_sin_premios.php?fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" title="Delegaciones sin Premios" width="24" height="23" border="0" /></a></td>
    	<?php if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' || $_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO') {?>
		<td width="5%"><a href="#" onclick="window.open('list/movimientos_delegaciones.php?fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" title="Cantidad de Ganadores por Delegaciones" width="24" height="23" border="0" /></a></td>
		<?php }
		if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA') {?>
		<td width="5%"><a href="#" onclick="window.open('list/movimientos_delegaciones_casinos.php?fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" title="Cantidad de Ganadores por Delegaciones" width="24" height="23" border="0" /></a></td>
	 	<?php }?>
	<td width="54%"  align="center"  class="textoRojo" >Premios Pagados</td>
    <td width="4%"  align="right"><a href="#" onclick="window.open('list/listado_de_ganadores.php?fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban; ?>&rrol=<?php echo $_SESSION['rol'.$j]?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" title="Datos de Ganadores" width="24" height="23" border="0" /></a></td>
    <td width="4%"  align="right" ><a href="#" onclick="window.open('list/premios_pagados.php?conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban; ?>&fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" title="Premios Pagados" width="24" height="23" border="0" /></a></td>
    	
	<?php } else {?>
    <td width="5%"><a href="#" onclick="window.open('list/delegaciones_sin_premios.php?fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" title="Delegaciones sin Premios" width="24" height="23" border="0" /></a></td>    
    <td width="24%" align="center"  class="textoRojo" >Premios Pagados</td>
    <td width="4%" align="left"><a href="#" onclick="window.open('list/premios_pagados.php?conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban; ?>&fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" title="Premios Pagados" width="24" height="23" border="0" /></a></td>
    <?php }?>   
</tr>
          
</table> 
<table width="76%" border="0" align="center"> 
 <tr>
          <td colspan="8" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
</tr>
    <tr align="center" class="td2">
          <td width="5%" class="td4" scope="col">Fecha</td>
          <td width="30%" class="td4" scope="col">Apellido y Nombre</td>
          <?php if (($_SESSION['rol'.$j]<>'ROL_LAVADO_DINERO_OPERADOR')&&($_SESSION['rol'.$j]<>'ROL_LAVADO_DINERO_OP_UNICO')&&($_SESSION['rol'.$j]<>'LAVADO_DINERO_CONF_DELE')) {?> 
          <td width="19%" class="td4" scope="col">
		  <?php if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' || $_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO') {?>
          Delegaci&oacute;n
          <?php } else if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO') {?>
          Casino
          
          <?php }?></td>
          <?php }?>
          <td width="13%" class="td4" scope="col">Importe</td>
          <td width="12%" class="td4" scope="col">Im&aacute;genes</td>
          <td class="td4" scope="col">Datos Ganador</td>
           <!--<td class="td4" scope="col">PEP'S</td>-->
           <?php if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' || $_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO'){?>
           <td width="9%" class="td4" scope="col">Observaci&oacute;n</td> 
          <?php }?>
  </tr>
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
           <td align="center"><?php echo $row->FECHA;?></td>
           <td align="left"><?php echo trim($row->APELLIDO).' '.trim($row->NOMBRE);?></td>
           <?php if (($_SESSION['rol'.$j]<>'ROL_LAVADO_DINERO_OPERADOR')&&($_SESSION['rol'.$j]<>'ROL_LAVADO_DINERO_OP_UNICO')&&($_SESSION['rol'.$j]<>'LAVADO_DINERO_CONF_DELE')) {?> 
           <td align="left"><?php echo utf8_decode($row->CASA);?></td>
            <?php }?>
           <td align="right" ><?php echo number_format($row->VALOR_PREMIO,2,',','.');?><?php  $total=$total+$row->VALOR_PREMIO;?></td>
                   
          
        <td align="center" ><a href="#" onclick="ajax_showTooltip('premio/mostrar_imagen.php?jsfecha='+new Date()+'&id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=1&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban ; ?>',this); return false;" ><img src="image/download.png" title="ver archivos"  width="20" height="20" border="0"/></a></td>
        <td align="center" > <?php if(($row->CONFORMADO==0)||($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC')||($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO')){?> 
        <a href="#" onclick="ajax_get('contenido','premio/modificar_premio2.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&casa=<?php echo $suc_ban ; ?>'); return false;" ><img src="image/modificar.png" title="modificar datos"  width="20" height="20" border="0"/></a>
        <a href="#" onclick="window.open('list/datos_ganadores.php?id_ganador=<?php echo $row->ID_GANADOR ?>&delegacion=<?php echo utf8_decode($row->CASA);?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Adobe Reader 7.png" title="Imprimir" width="20" height="20" border="0" /></a> 
        <?php } else {?> 
        <img src="image/candado.png" title="No se puede modificar, ganador fue conformado" width="20" height="20" border="0" />	  
        <a href="#" onclick="window.open('list/datos_ganadores.php?id_ganador=<?php echo $row->ID_GANADOR ?>&delegacion=<?php echo utf8_decode($row->CASA);?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Adobe Reader 7.png" title="Imprimir" width="20" height="20" border="0" /></a> <?php }?> </td>
		
        <!--<td align="center" >
        <?php if($row->POLITICO=="SI"){?>
        <a href="#" onclick="window.open('list/peps.php?id_ganador=<?php echo $row->ID_GANADOR ?>&delegacion=<?php echo utf8_decode($row->CASA);?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')" ><img src="image/Adobe Reader 7.png" title="modificar datos"  width="20" height="20" border="0"/></a>
        <?php } else {?> NO <?php  }?>  </td>-->
		  
  
	<?php if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO' || $_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO'){?>
	<td align="center" >
    
	  <?php  if ($row->OBSERVACION==0){?>
         
      <a href="#" onclick="ajax_get('contenido','premio/procesar_datos.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=1&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/SyncCenter.png" title="Datos Completos" width="24" height="23" border="0" /></a>
      <?php } else {?>
  		   <a href="#" onclick="ajax_get('contenido','premio/procesar_datos.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=0&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/Error.png" title="Datos Incompletos" width="24" height="23" border="0" /></a>
		  
      <?php  }?>
      <a href="#" onclick="ajax_get('contenido','premio/nueva_nota_observacion.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/app_48.png" title="Cargar Nota" width="24" height="23" border="0" /></a>      </td><?php }?>
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





<?php } 

 } $_SESSION['sqlreporte']= $_pagi_sql; ?>
