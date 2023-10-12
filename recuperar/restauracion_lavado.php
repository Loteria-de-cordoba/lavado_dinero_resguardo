<?php 
session_start(); 
include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php");
include('../jscalendar-1.0/calendario.php'); 
//$db->debug = true;
//print_r($_REQUEST);

$array_fecha = FechaServer();
$variables=array();
$i=0;
$j=0;
$casino=0;
$area='';
$myarea='';
$mymes=0;

//obtengo EL NOMBRE DE LOS DATOS GENERALES A RESTAURAR
//ESTA TABLA SOLO EXISTE EN LAVADO_DINERO
try {
			$rs_dato = $db ->Execute("select DESCRIPCION AS DESCRIPCION,ID_RESTAURADORA AS CODIGO										
													from LAVADO_DINERO.T_RESTAURADORA
													ORDER BY DESCRIPCION");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
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
$habilitado=0;
$i=0;
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

/*while ($i<$_SESSION['cantidadroles'])  
{
	$i=$i+1;	
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CASINO_CARGA' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0)
{
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
$array_fecha = FechaServer();	

while ($j<$_SESSION['cantidadroles']) 
 {
	$j=$j+1;*/
	
		
		if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}
		 	else {	if (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}
					 else {	//$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					 		  $fecha='01/01/2012';}
				 }
		
		if (isset($_GET['fhasta'])) {$fhasta = $_GET['fhasta'];
		
			$dia=substr($_GET['fhasta'],0,2);
			if(($dia<=29 and $mymes<>2) || ($dia<=27 and $mymes==2))
			 {
			 $dia=$dia+1;
			 }
			$fhasta_consulta = $dia.substr($_GET['fhasta'],2,8);}
			 else {	if (isset($_POST['fhasta'])) {
			 $dia=substr($_POST['fhasta'],0,2);
			  $mymes=substr($_POST['fhasta'],3,2);
			 
			 if(($dia<=29 and $mymes<>2) || ($dia<=27 and $mymes==2))
			 {
			 $dia=$dia+1;
			 }
			 $fhasta_consulta=$dia.substr($_POST['fhasta'],2,8);
			 
			 $fhasta = $_POST['fhasta'];
			 
			 }
			 		 else {	$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					 
					  $dia=str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT);
					  $mymes=str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT);
					  
					 if(($dia<=29 and $mymes<>2) || ($dia<=27 and $mymes==2))
					 {
					 $fhasta_consulta = str_pad($array_fecha["mday"]+1,2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					 }
					 else
					 {
					 $fhasta_consulta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					 }
					 
					 }
				  }
		
$area=$_SESSION['area'];		

if(substr($area,0,6)=='Casino')
{
	try {
			$rs_busca_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_busca_casino =$rs_busca_casino->FetchNextObject($toupper=true);
	$casino=$row_busca_casino->CODIGO;
	$condicion_conforma="and a.id_casino ='$casino'";
	$soydeaca=$row_busca_casino->DESCRIPCION;

}
else
{
$soydeaca=$area;
if (isset($_POST['casino']))
		{
			$casino = $_POST['casino'];
			$condicion_conforma="and a.id_casino ='$casino'";
		} 
		else
		{
		if(isset($_GET['casino']))
		 {
					$casino = $_GET['casino'];
					$condicion_conforma="and a.id_casino ='$casino'";
		 }
		} 
}

//$soydeaca=$row_busca_casino->DESCRIPCION;
if (isset($_POST['apostador']))
		{
			$apostador = $_POST['apostador'];
			if($apostador<>0)
			{
			$condicion_apostador="and id_restauradora ='$apostador'";
			}
			else
			{
			$condicion_apostador="";
			}
		} 
		else
		{
		if(isset($_GET['apostador']))
		 {
					$apostador = $_GET['apostador'];
					if($apostador<>0)
					{
					$condicion_apostador="and id_restauradora ='$apostador'";
					}
			else
			{
			$condicion_apostador="";
			}
		 }
		else
		{
					$apostador = '0';
					$condicion_apostador="";
		}
		} 





try {
			$rs = $db ->Execute("select DESCRIPCION AS DESCRIPCION,ID_RESTAURADORA AS CODIGO										
						from LAVADO_DINERO.T_RESTAURADORA
						where 1=1
						$condicion_apostador
					ORDER BY DESCRIPCION
			");
			}								
				catch (exception $e){die ($db->ErrorMsg());} 
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo2 {font-size: 5%}
-->
</style>

<table width="72%"  border="0" align="center" style="margin-top:5px;margin-bottom:10px;">
<tr>
	<td colspan="10" align="center">
		<div align="Center" class="textoAzulOscuro" style="padding:3px;">
		<h2><Strong>Seleccion de Datos a Restaurar</Strong></h2>
		</div>  
	</td>
</tr>
</table>

<form  name="form1" method="post" action="#" onsubmit="ajax_post('contenido','recuperar/procesar_asignar_items_seleccionados.php',this); return false;">

<table width="71%"  border="2" align="center" style="margin-bottom:5px;">
 <tr valign="bottom" class="td8" >
	  <td width="47" align="right" valign="middle" class="small"  scope="col">Datos: </td>
      <td width="145"  valign="middle" scope="col" align="center"><?php armar_combo_todos($rs_dato,"apostador",$apostador);?></td>        
       <td width="76" valign="middle" class="small"  scope="col">Regist.Desde: </td>
      <td width="175" valign="middle"  scope="col"><?php  abrir_calendario('fecha','premio', $fecha);?></td>
      <td width="81" valign="middle" class="small" scope="col">Regist.Hasta: </td>
      <td width="180" valign="middle"  scope="col"><?php  abrir_calendario('fhasta','premio', $fhasta);?></td>
      <td align="center" scope="col" ><a href="#" onclick="window.open('list1/datos_apostadores_todos.php?casino=&casino=<?php echo $casino;?>&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" title="Reporte de Seleccion" width="20" height="20" border="0" /></a></td>  
      <input type="hidden" name="fhasta_consulta" id="fhasta_consulta" value="<?php echo $fhasta_consulta; ?>" />       
  </tr>
</table>

<input type="hidden" name="tabla" id="tabla" value="<?php echo $apostador; ?>" />
<input type="hidden" name="fedesde" id="fedesde" value="<?php echo $fecha; ?>" />
<input type="hidden" name="fehasta" id="fehasta" value="<?php echo $fhasta; ?>" />
<input type="hidden" name="fecha_consulta" id="fecha_consulta" value="<?php echo $fhasta_consulta; ?>" />
			
<table width=60% border="0" align="center" cellspacing="1">

<tr class="th4">
	<td colspan="10" class="th" align="center"><input type="checkbox" name="op" id="op" value="sel" onclick="if(op.checked) {seleccionar_todo(form1)} else {deseleccionar_todo(form1)}" />Seleccionar Todos/Ninguno</td>
</tr>
</table>

<table width=60% border="0" align="center" cellspacing="1" style="margin-bottom:50px;">
<tr align="center" class="th2">
      <td width="80%" align="center" style="color:#0000CC; font-size:16px">Datos a Recuperar</td>
	  
      <td width="20%" style="color:#0000CC; font-size:16px">Seleccione y Ejecute
      <input name="guardar" class="smallTahoma" id="guardar" value="Ejecutar" type="submit" style="margin-left:5px;"/></td>
    </tr>
<?php

$ids_de_empleados_listados = array();
$k=0;
while ($row = $rs->FetchNextObject($toupper=true)){
$k=$k+1; 
	array_push($ids_de_empleados_listados, $k);
	//array_push($ids_de_empleados_listados, $row->CODIGO);//DE CAMBIAR HAY QUE REEMPLAZAR $K POR $row->CODIGO
?>
    <tr class="td">
      <td align="left" valign="middle" class="td2" style="color:#FF0000; font-size:14px; font-weight:bold"><?php echo $row->DESCRIPCION;?></td>
     
			<td align="center" valign="middle" class="td2"><input type="checkbox" name="aplica[]" id="aplica_<?php echo $k;?>" value="<?php echo $k;?>" /></td>
        
    </tr>
<?php 
}
?>
</table>
<input type="hidden" name="ids_de_empleados_listados" id="ids_de_empleados_listados" value="<?php echo urlencode(serialize($k)); ?>" />
</form>
