<?php session_start();
//print_r($_GET);
//print_r($_POST);
//print_r($_SESSION['permiso']);
//print $_SESSION['area'];
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
//$db->debug=true;
//print_r($_POST);
//print_r($_GET);
$i=0;
$j=0;
$casino=0;
$area='';
$area=$_SESSION['area'];		
if(substr($area,0,6)=='Casino' and !isset($_POST['casino']) and !isset($_GET['casino']))
{
	try {
			$rs_busca_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_busca_casino =$rs_busca_casino->FetchNextObject($toupper=true);
	$casino=$row_busca_casino->CODIGO;
	$condicion_conforma="and b.id_casino ='$casino'";

}
else
{
//echo '****paso***';
/*if (isset($_POST['casino'])&& $_POST['casino']<>0 ) {
			$casino = $_POST['casino'];
			$condicion_conforma="and a.id_casino ='$casino'";
		} elseif (isset($_GET['casino'])&& $_GET['casino']<>0 ) {
					$casino = $_GET['casino'];
					$condicion_conforma="and a.id_casino ='$casino'";
		} else {
						$casino = 0;
						$condicion_conforma="and a.id_casino=0";
		}*/
if (isset($_POST['casino']))
		{
			$casino = $_POST['casino'];
			$condicion_conforma="and b.id_casino ='$casino'";
		} 
		else
		{
		if(isset($_GET['casino']))
		 {
					$casino = $_GET['casino'];
					$condicion_conforma="and b.id_casino ='$casino'";
		 }
		} 
}
try {
			$rs_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino not in(2,13)
										union 
										select 100 as codigo,
										'No pertenece a Casino' as descripcion
										from dual
										--order by codigo desc");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 

try {
			$rs_apostador = $db ->Execute("select id_cliente as codigo, initcap(apellido) || ', ' || initcap(nombre) as descripcion
										from lavado_dinero.t_cliente
										where fecha_baja is null
										and id_casino=$casino
										order by apellido");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 

while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	if ((($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||$_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC')|| ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO'))	{$habilitado=1;} 
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
	
		
		if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}
		 	else {	if (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}
					 else {	$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
				 }
		
		if (isset($_GET['fhasta'])) {$fhasta = $_GET['fhasta'];
			$dia=substr($_GET['fhasta'],0,2);
			$fhasta_consulta = $dia.substr($_GET['fhasta'],2,8);}
			 else {	if (isset($_POST['fhasta'])) {
			 $dia=substr($_POST['fhasta'],0,2);
			 $dia=$dia+1;
			 $fhasta_consulta=$dia.substr($_POST['fhasta'],2,8);
			 
			 $fhasta = $_POST['fhasta'];
			 }
			 		 else {	$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					 $fhasta_consulta = str_pad($array_fecha["mday"]+1,2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
				  }
		


if (isset($_POST['apostador']))
		{
			if($_POST['apostador']<>0)
			{
			$apostador = $_POST['apostador'];
			$condicion_apostador="and b.id_cliente ='$apostador'";
			}
			else
			{
			$apostador = '0';
			$condicion_apostador="";
			}
		} 
		else
		{
		if(isset($_GET['apostador']))
		 {
		 			if($_GET['apostador']<>0)
					{
					$apostador = $_GET['apostador'];
					$condicion_apostador="and b.id_cliente ='$apostador'";
					}
					else
					{
					$apostador = '0';
					$condicion_apostador="";
					}
		 }
		 else
		 {
		 			$apostador='0';
					$condicion_apostador="";	
		 }
		} 
//}

/*if (isset($_POST['fhasta_consulta'])&& $_POST['fhasta_consulta']<>0 )
 {
			$fhasta_consulta = $_POST['fhasta_consulta']; }
else {
	if(isset($_GET['fhasta_consulta'])&& $_GET['fhasta_consulta']<>0 ) {
					$fhasta_consulta = $_GET['fhasta_consulta'];
					//$condicion_conforma="and a.id_casino ='$casino'";
		}}*/



?>	
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<?php 
if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OP_UNICO'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OPERADOR') {
//$db->debug=true;
//echo('ENTRA');
if($casino<>100)
{
$_pagi_sql ="select b.id_cliente,   DECODE(a.fecha_novedad, NULL, 'SIN MOV.', TO_CHAR(a.fecha_novedad,'DD/MM/YYYY')) AS fecha,
				initcap(b.nombre) nombre , initcap(b.apellido) apellido,
				a.id_novedad , fichaje, acierto,
				c.n_casino as casino, b.id_casino						
      			from lavado_dinero.t_novedades_cliente a,
				lavado_dinero.t_cliente b,
				casino.t_casinos c
				where a.id_cliente(+)=b.id_cliente
				and b.id_casino=c.id_casino
				$condicion_conforma
				$condicion_apostador
				and b.fecha_baja is null
				--and (a.fichaje<>0 or a.acierto<>0)
				AND A.USUARIO_BAJA IS NULL
        		and (a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
					or a.fecha_novedad is null)
        		order by a.fecha_novedad desc";
}
else
{
	$_pagi_sql ="select b.id_cliente,   DECODE(a.fecha_novedad, NULL, 'SIN MOV.', TO_CHAR(a.fecha_novedad,'DD/MM/YYYY')) AS fecha,
				initcap(b.nombre) nombre , initcap(b.apellido) apellido,
				a.id_novedad , a.fichaje, a.acierto,
				'Delegacion' as casino, '100' as id_casino						
      			from lavado_dinero.t_novedades_cliente a,
				lavado_dinero.t_cliente b
				where a.id_cliente(+)=b.id_cliente
				and b.id_casino=100
				$condicion_conforma
				$condicion_apostador
				and b.fecha_baja is null
				--and (a.fichaje<>0 or a.acierto<>0)
				AND A.USUARIO_BAJA IS NULL
        		and (a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
					or a.fecha_novedad is null)
        		order by a.fecha_novedad desc, b.apellido asc";
}
$_pagi_div = "contenido";
$_pagi_enlace = "cliente/adm_novedad.php";
$_pagi_cuantos = 12; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
//$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='fecha';
$_pagi_propagar[1]='fhasta';
$_pagi_propagar[2]='casino';
//if($apostador==1){
				//$apostador=0;
				//}
$_pagi_propagar[3]='apostador';

//$_pagi_propagar[3]='fhasta_consulta'; 
 
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
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','cliente/adm_novedad.php',this); return false;">
<table width="95%"  align="center">
<tr>
	<td colspan="11
    " align="center" valign="bottom" class="texto4" scope="col">Administraci&oacute;n de Movimientos </a></td>
</tr>
  <tr valign="bottom" class="td8" >
    
      <td width="47" align="right" valign="middle" class="td2"  scope="col">Casino</td>      
      <td width="145"  valign="middle" class="td2" scope="col" align="center"><?php armar_combo_ejecutar_ajax_get($rs_casino,"casino",$casino,'casinito','cliente/combo_apostador.php');?></td>  
         <td width="47" align="right" valign="middle" class="td2"  scope="col">Apostador</td> 
         <?php if($apostador<>'')
		 {?>   
         <td width="145"  valign="middle" class="td2" scope="col" align="center"><div id="casinito"><?php armar_combo_todos($rs_apostador,"apostador",$apostador);?></div></td>  
         <?php }?>
	     <td width="76" valign="middle" class="td2"  scope="col">Fecha desde </td>
      <td width="175" valign="middle" class="td2" scope="col"><?php  abrir_calendario('fecha','premio', $fecha);?></td>
      <td width="81" valign="middle" class="td2" scope="col">Fecha hasta</td>
      <td width="180" valign="middle" class="td2" scope="col"><?php  abrir_calendario('fhasta','premio', $fhasta);?></td>
      <?php if ($_SESSION['rol'.$j]!='ROL_LAVADO_DINERO_ADM_CASINO') {?>
   	 <td width="162" class="td2" scope="col" align="right"><div align="center"><img src="image/s_okay.png" alt="Nuevo Apostador" width="16" height="16" /> <a href="#" onclick="ajax_get('contenido','cliente/movimiento_confirmar.php','fecha=<?php echo $fecha ?>&fhasta=<?php echo $fhasta ?>&casino='+premio.casino.value+'&apostador='+premio.apostador.value);">Confirmacion Masiva</a></div></td><?php }?>
	                <td align="center" class="td2" ><a href="#" onClick="window.open('list1/mov_apostadores_todos.php?apostador=<?php echo $apostador ?>&casino='+premio.casino.value+'&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>
       <!--<td align="center" class="td2" scope="col" ><a href="#" onclick="window.open('list1/datos_apostadores_todos.php?casino='+premio.casino.value+'&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>-->
      <input type="hidden" name="fhasta_consulta" id="fhasta_consulta" value="<?php echo $fhasta_consulta; ?>" />
     <td width="80" class="td2" scope="col" align="right"> <input type="submit" value="Buscar" /></td>
    </tr>
</table>
</form>
<?php if ($_pagi_result->RowCount()==0) {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','blanco.php','')\">Regresar</a></div>");
}
?>
<span class="td4">
<?php }?>
</span>
<table width="95%" border="0" align="center"> 
     <tr>
              <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
    <tr align="center" class="td2">
          <td width="9%" class="td4" scope="col">Fec.Movim.</td>
    <td width="21%" class="td4" scope="col">Apellido y Nombre</td>
    <td width="20%" class="td4" scope="col">Inscripto en</td>
    <td class="td4" scope="col" colspan="2">Movimientos Registrados<br />
      Fichaje  / Aciertos</td>
    <td width="5%" class="td4" scope="col">Confirmar</td>
    <td width="4%" class="td4" scope="col">Eliminar</td>
    <td width="6%" class="td4" scope="col">Agregar Movi.</td>
    <td width="6%" class="td4" scope="col">Movim.del<br />Apostad. </td>
    <!--<td width="7%" class="td4" scope="col">Observaci&oacute;n</td>    -->    
  </tr>
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
           <td align="center"><?php echo $row->FECHA;?></td>
           <td align="left"><?php echo utf8_encode(trim($row->APELLIDO)).', '.utf8_encode($row->NOMBRE);?></td>
           <td align="left"><?php echo utf8_decode($row->CASINO);?></td>
           <td width="9%" align="right"><?php echo number_format($row->FICHAJE,2,',','.');?></td>
         <td width="9%" align="right"><?php echo number_format($row->ACIERTO,2,',','.');?></td>
<?php
									  try {
									$rs_busca_id_novedad = $db ->Execute("select confirmado
												from lavado_dinero.t_novedades_cliente
												where id_novedad=?",array($row->ID_NOVEDAD));
											}									catch (exception $e){die ($db->ErrorMsg());} 
									$row_busca_id_novedad =$rs_busca_id_novedad->FetchNextObject($toupper=true);
									if($rs_busca_id_novedad->RowCount()<>0)
									{
									$novedad=$row_busca_id_novedad->CONFIRMADO;
									}
									/*else
									{
									$novedad='N';
									}*/
									if($row->ID_NOVEDAD==NULL)
									{
									?>
										<td align="center" >S/M</td>
										<td align="center" >S/M</td>
									<?php 
									}
									else
									{
					
											if($novedad==NULL)
											{
											 ?>
											<td align="center" ><a href="#" onclick="ajax_get('contenido','cliente/movimiento_confirmar_unico.php','id_novedad=<?php echo $row->ID_NOVEDAD;?>&casino=<?php echo $casino;?>&fecha_inicio=<?php echo $fecha ?>&fhasta=<?php echo $fhasta ?>&apostador=<?php echo $apostador?>'); return false;" ><img src="image/C_Checkmark_md.png" alt="Confirma este movimiento"  width="20" height="20" border="0"/></a></td>       
											<td align="center" ><a href="#" onClick="ajax_get('elimina','cliente/controla_eliminacion_novedad.php','id_novedad=<?php echo $row->ID_NOVEDAD;?>&id_apostador=<?php echo $row->ID_CLIENTE;?>&casino=<?php echo $casino;?>&fecha_inicio=<?php echo $fecha ?>&fhasta=<?php echo $fhasta ?>');return false;"><img src="image/roseta_ok.png" alt="Elimina" width="20" height="20" border="0"/></a></td>
											 <?php
											 }
											 else
											 {
											 ?>
											 <td width="2%" align="center" ><img src="image/candado.png" alt="Movimiento Confirmado" width="20" height="20" border="0"/></td>
		 <td width="2%" align="center" ><img src="image/candado.png" alt="Movimiento Confirmado" width="20" height="20" border="0"/></td>
		 <?php
											 }
									}
											 ?>
       <!--<td align="center" ><a href="#" onClick="ajax_get('contenido','cliente/cliente_eliminar_grabar.php','id_cliente=<?php// echo $row->ID_CLIENTE;?>&casino=<?php// echo $casino ; ?>'); return false;">
								<img src="image/roseta_ok.png" alt="Activa" width="20" height="20" border="0" /></a></td>-->
                                     
                                     
            <td width="2%" align="center" ><a href="#" onClick="ajax_get('contenido','cliente/agregar_movimiento.php','apostador=<?php echo $row->ID_CLIENTE ?>&casino=<?php echo $row->ID_CASINO ?>&novedad=<?php echo $row->ID_NOVEDAD?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_action_db_add.png" alt="Fichaje/Acierto" width="20" height="20" border="0"/></a></td>
<?php if($row->ID_NOVEDAD==NULL)
									{
									?>
										<td width="3%" align="center" >S/M</td>
										
	 <?php 
									}
									else
									{?>
             <td width="2%" align="center" ><a href="#" onClick="window.open('list1/mov_apostadores.php?id_cliente=<?php echo $row->ID_CLIENTE ?>&casino=<?php echo $row->ID_CASINO;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>
	    <?php }?>
          <!--<td align="center" ><?php// echo $row->OBSERVACION?></td>--></tr>

<?php   }?>

		 <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
    <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina">&nbsp;</div></td>
    </tr>
      	  	<tr align="center"><td align="center" scope="row" colspan="9"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_cliente.php','casino=<?php echo $casino;?>&apostador=<?php echo $apostador;?>')">Retornar a Apostadores</a></div></td></tr>
</table>

<?php } $_SESSION['sqlreporte']= $_pagi_sql; ?>
