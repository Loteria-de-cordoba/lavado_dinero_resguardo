<?php session_start();
//echo "xget";
//print_r($_GET);
//echo "xpost";
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
$variables=array();
$habilitado=0;
$i=0;
 $caorigen='';
 $myarea='';
 $ccuenta=0;

//selecciono mi area con rol op_unico
try {
			$rs_myarea = $db ->Execute("SELECT us.area_id_principal as area  FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99 or area_id_principal=4 OR AREA_ID_PRINCIPAL=32)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_myarea =$rs_myarea->FetchNextObject($toupper=true);
			$myarea=$row_myarea->AREA; 
//echo $myarea;
//selecciono los casineros con rol op_unico
try {
			$rs_usuario = $db ->Execute("SELECT count(*) as cuenta FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_usuario =$rs_usuario->FetchNextObject($toupper=true);
			$ccuenta=$row_usuario->CUENTA; 

while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	//echo $_SESSION['rol'.$i];
	//Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CASINO_CARGA' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
	//if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
//echo $habilitado;
//die();
$j=0;
$casino=0;
$casino_setea=0;
$area='';
$area=$_SESSION['area'];
$totfichaje=0;
$totacierto=0;
$totinfichaje=0;
$totinacierto=0;	
if(substr($area,0,6)=='Casino')
{
	try {
			$rs_setea_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_setea_casino =$rs_setea_casino->FetchNextObject($toupper=true);
	if($rs_setea_casino->RecordCount()<>0)
		{
			$casino_setea=$row_setea_casino->CODIGO;
		}
		
}
else
		{
			$casino_setea=100;
		}
//echo $casino_setea;
//die();
if(substr($area,0,6)=='Casino' and !isset($_POST['casino']) and !isset($_GET['casino']))
{
	try {
			$rs_busca_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_busca_casino =$rs_busca_casino->FetchNextObject($toupper=true);
	$casino=$row_busca_casino->CODIGO;
	$casino=$row_busca_casino->CODIGO;
	$condicion_conforma="and a.id_casino_novedad ='$casino'";
	$soydeaca=$row_busca_casino->DESCRIPCION;

}
else
{
$soydeaca=$area;
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
if (isset($_POST['masivo']))
		{
			$masivo = strtolower($_POST['masivo']);
			//$condicion_masivo="and lower(b.apellido) like '%$masivo%'";
			$condicion_masivo="and decode(nombre,NULL,lower(b.apellido),lower(b.apellido || ', ' || b.nombre)) like '%$masivo%'";
		}
	else
		{
		if(isset($_GET['masivo']))
		 {
					$masivo = strtolower($_GET['masivo']);
					//$condicion_masivo="and lower(b.apellido) like '%$masivo%'";
						$condicion_masivo="and decode(nombre,NULL,lower(b.apellido),lower(b.apellido || ', ' || b.nombre)) like '%$masivo%'";
		 }
			else
			{
				$masivo='';
				$condicion_masivo='';
			}	
		 }
		 


//seteo casino
/*if($casino_setea==100)
{
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
}
else
{
			try {
			$rs_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino=?
										",array($casino_setea));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 
}

try {
			$rs_apostador = $db ->Execute("select codigo, descripcion
										from(
													select initcap(apellido) || decode(nombre,'','',', ' || initcap(nombre)) as descripcion, max(id_cliente) as codigo
													from PLA_AUDITORIA.t_cliente
													where fecha_baja is null
													group by initcap(apellido) || decode(nombre,'','',', ' || initcap(nombre))
													--and id_casino=$casino
													order by descripcion)");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 

while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	//if ((($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||$_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC')|| ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO'))	{$habilitado=1;} 
	//Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO') ||  ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CASINO_CARGA' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
	//if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
//print_r($_GET);
//print_r($_POST);
//$db->debug=true;
//echo $suc_ban.'sucban';*/
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
try {
			$rs_totales = $db ->Execute("SELECT 
				  sum(a.fichaje) fichaje,
				  sum(a.acierto) acierto,
				  SUM(a.mon_ing_fic) ingreso,
				  SUM(a.mon_perdido) perdido					 
				FROM PLA_AUDITORIA.t_novedades_cliente a,
				  PLA_AUDITORIA.t_cliente b,
				  casino.t_casinos c
				WHERE a.id_cliente=b.id_cliente
				AND b.id_casino      =c.id_casino(+)
				$condicion_masivo
				AND b.fecha_baja    IS NULL 
				--and (a.fichaje<>0 or a.acierto<>0)
				AND A.USUARIO_BAJA IS NULL and
				a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy') 
				");
			}	
	catch (exception $e){die ($db->ErrorMsg());} 
	$row_totales =$rs_totales->FetchNextObject($toupper=true);
	$totinfichaje=$row_totales->FICHAJE;
	$totinacierto=$row_totales->ACIERTO;
	$totingreso=$row_totales->INGRESO;
	$totperdido=$row_totales->PERDIDO;
	


?>	
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<?php 

	$_pagi_sql ="SELECT
					b.id_cliente,
					TO_CHAR(a.fecha_novedad,'dd/mm/yyyy') AS fecha,
				   a.id_casino_novedad as casinopasa,
					MAX(initcap(b.nombre)) nombre ,
					MAX(initcap(b.apellido)) apellido,
					DECODE(MAX(a.id_casino_novedad),100,'Delegacion',MAX(substr(c.n_casino,8))) as casinoorigen,
					DECODE(MAX(b.id_casino),100,'Delegacion',MAX(c.n_casino))
					|| ' (Ag. '
					|| MAX(SUBSTR(us.descripcion,1,20))
					|| ')' casino,
					MAX(b.id_casino) id_casino,
					SUM(a.fichaje) fichaje,
					SUM(a.acierto) acierto,
					SUM(a.mon_ing_fic) ingreso,
				  SUM(a.mon_perdido) perdido	
				  FROM PLA_AUDITORIA.t_novedades_cliente a,
					PLA_AUDITORIA.t_cliente b,
					casino.t_casinos c,
					SUPERUSUARIO.USUARIOS US
				  WHERE a.id_cliente=b.id_cliente
				  AND a.id_casino_novedad   =c.id_casino(+)
				  AND us.id_usuario =b.usuario
				  	$condicion_masivo
				  AND b.fecha_baja IS NULL 
				  AND A.USUARIO_BAJA IS NULL 
				  and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
				  GROUP BY b.id_cliente, a.fecha_novedad, a.id_casino_novedad
				  order by a.id_casino_novedad,a.fecha_novedad desc";

$_pagi_div = "contenido";
$_pagi_enlace = "cliente/movi_masivo.php";
$_pagi_cuantos = 12; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
$_pagi_propagar[0]='fecha';
$_pagi_propagar[1]='fhasta';
$_pagi_propagar[2]='masivo';
$_pagi_propagar[3]='apostador';

 
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
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','cliente/movi_masivo.php',this); return false;">
<table width="95%"  align="center">
<tr><td colspan="12">&nbsp;</td></tr>
<tr>
	<td colspan="12
    " align="center" valign="bottom" class="texto4" scope="col">Consulta Masiva de Movimientos  - <?php echo $soydeaca;?> </a>[Datos Resguardados]</td>
</tr>
  <tr valign="bottom" class="td8" >
    
         <td width="46" align="right" valign="middle" class="td2"  scope="col">Apellido/Apodo</td> 
<?php if($apostador<>'')
		 {?>   
         <td width="142"  valign="middle" class="td2" scope="col" align="center"><div id="casinito"><input name="masivo" id="masivo" type="text" size="50" value="<?php echo $masivo;?>" /></div></td>  
<?php }?>
	     <td width="92" valign="middle" class="td2"  scope="col">Fecha desde </td>
      <td width="168" valign="middle" class="td2" scope="col"><?php  abrir_calendario('fecha','premio', $fecha);?></td>
      <td width="84" valign="middle" class="td2" scope="col">Fecha hasta</td>
      <td width="166" valign="middle" class="td2" scope="col"><?php  abrir_calendario('fhasta','premio', $fhasta);?></td>
      <?php if ($_SESSION['rol'.$j]!='ROL_LAVADO_DINERO_ADM_CASINO') {?>
   	 <?php }?>
     <!--<td width="24" align="center" class="td2" ><a href="#" onClick="ajax_get('contenido','cliente/agregar_movimiento.php','apostador=0&casino=0&novedad=<?php// echo '1'?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_action_db_add.png" alt="Agregar Movimiento" width="20" height="20" border="0"/></a></td>-->
	                <td width="21" align="center" class="td2" ><a href="#" onClick="window.open('list1/mov_apostadores_todos.php?apostador=<?php echo $apostador ?>&masivo='+premio.masivo.value+'&casino=0&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>
       <td width="24" align="center" class="td2"><img width="24" height="24" onclick="window.open('cliente/lista_apostadores_masiva_xls.php?apostador=<?php echo $apostador ?>&masivo='+premio.masivo.value+'&casino=0&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value)" title="REPORTE EXCEL" src="image/Excel-Document.png" border="0" complete="complete" ;=""/></td>
      <!--<td align="center" class="td2" scope="col" ><a href="#" onclick="window.open('list1/datos_apostadores_todos.php?casino='+premio.casino.value+'&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>-->
      <input type="hidden" name="fhasta_consulta" id="fhasta_consulta" value="<?php echo $fhasta_consulta; ?>" />
     <td width="69" class="td2" scope="col" align="right"> <input type="submit" value="Buscar" /></td>
    </tr>
</table>
</form>
<?php if ($_pagi_result->RowCount()==0) {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','blanco.php','')\">Cerrar</a></div>");
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
    <?php if($casino==0)
			{?>
           <td width="4%" class="td4" scope="col" rowspan="2">Origen Mov.</td>
  <?php }?> 
     <td width="5%" class="td4" scope="col" rowspan="2">Fec.Movim.</td>
    <td width="9%" class="td4" scope="col" rowspan="2">Apellido y Nombre / Apodo</td>
    <td width="27%" class="td4" scope="col" rowspan="2">Inscripto en</td>
    <td class="td4" scope="col" colspan="4">Movimientos Registrados</td>
    <td width="6%" class="td4" scope="col" rowspan="2">Ver Detalles</td>
   <!-- <td width="5%" class="td4" scope="col">Confirmar</td>
    <td width="4%" class="td4" scope="col">Eliminar</td>
    <td width="6%" class="td4" scope="col">Agregar Movi.</td>-->
    <td width="5%" class="td4" scope="col" rowspan="2">Movim.del<br />
      Apostad. </td>
  </tr>
   <tr>
   
   <td width="4%" height="17" align="center" class="td4">Fichaje</td>
   <td class="td4" align="center">Aciertos</td>
   <td class="td4" align="center">Fic. Ingreso</td>
   <td class="td4" align="center">Perdido</td>
   </tr>
      
    <!--<td width="7%" class="td4" scope="col">Observaci&oacute;n</td>    -->  
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
           <?php if($casino==0)
			{
			if($caorigen<>$row->CASINOORIGEN)
			{?>
           <td width="4%" ><?php echo $row->CASINOORIGEN;
		   $caorigen=$row->CASINOORIGEN;?></td>
<?php }
		   else
		   {?>
		   		<td width="5%" >&nbsp;</td> 
     <?php }}?>
           <td align="center"><?php echo $row->FECHA;?></td>
           <td align="left"><?php 
		   if($row->NOMBRE<>'')
		   {
		  			 echo utf8_encode(trim($row->APELLIDO)).', '.utf8_encode($row->NOMBRE);
		   }
		   else
		   {
		   			 echo utf8_encode(trim($row->APELLIDO));
		   }?></td>
           <td align="left"><?php echo utf8_decode($row->CASINO);?></td>
           <td width="7%" align="right"><?php echo number_format($row->FICHAJE,2,',','.');?></td>
         <td width="11%" align="right"><?php echo number_format($row->ACIERTO,2,',','.');?></td>
         <td width="18%" align="right"><?php echo number_format($row->INGRESO,2,',','.');?></td>
         <td width="6%" align="right"><?php echo number_format($row->PERDIDO,2,',','.');?></td>
<?php 
$totfichaje=$totfichaje+$row->FICHAJE;
$totacierto=$totacierto+$row->ACIERTO;
/*
									  try {
									$rs_busca_id_novedad = $db ->Execute("select confirmado
												from PLA_AUDITORIA.t_novedades_cliente
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
									}/*
									/*if($row->ID_NOVEDAD==NULL)
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
	    <?php }*/
		?>
          <!--<td align="center" ><?php// echo $row->OBSERVACION?></td>-->
            <td width="5%" align="center"><a href="#" onClick="ajax_get('contenido','cliente/adm_novedad_explota.php','id_cliente=<?php echo $row->ID_CLIENTE ;?>&casino=<?php echo $row->CASINOPASA ;?>&fechita=<?php echo $row->FECHA;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Plus.png" alt="Detalles" width="20" height="20" border="0"/></a></td>
         <td width="2%" align="center" ><a href="#" onClick="window.open('list1/mov_apostadores.php?id_cliente=<?php echo $row->ID_CLIENTE ?>&casino=<?php echo $row->CASINOPASA;?>&fechita=<?php echo $row->FECHA;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>
  </tr>

<?php   }?>
<!--<tr>
			<td  align="center" colspan="2" valign="bottom" class="texto4" scope="col">&nbsp;</td>
          <td  align="left" valign="bottom" class="texto4" scope="col">Sub_Totales========></td>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php// echo number_format($totfichaje,2,',','.');?></td>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php// echo number_format($totacierto,2,',','.');?></td>
          <td  align="center" valign="bottom" class="texto4" scope="col">&nbsp;</td>
         
    	</tr>-->
		<tr>
        	 
             <?php if($casino<>0)
			{?>
            <td   colspan="2" align="center" valign="bottom" class="texto4" scope="col">&nbsp;</td>
          <td align="left" valign="bottom" class="texto4" scope="col" colspan="2" >Totales===========></td>
          <?php }
		  else
		  {?>
          <td   colspan="2" align="center" valign="bottom" class="texto4" scope="col">&nbsp;</td>
		  	 <td align="left" valign="bottom" class="texto4" scope="col" colspan="2">Totales===========></td>
		  <?php }?>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totinfichaje,2,',','.');?></td>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totinacierto,2,',','.');?></td>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totingreso,2,',','.');?></td>
          <td width="2%"  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totperdido,2,',','.');?></td>
          <td width="0%"  align="center" valign="bottom" class="texto4" scope="col">&nbsp;</td>
         
  </tr>
		 <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
    <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina">&nbsp;</div></td>
    </tr>
      	  	<tr align="center"><td align="center" scope="row" colspan="9"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_novedad.php','casino=<?php echo $casino;?>&apostador=<?php echo $apostador;?>')">Retornar a Adm. de Movimientos</a></div></td></tr>
</table>

<?php } $_SESSION['sqlreporte']= $_pagi_sql; 
}//fin de habilitado?>
