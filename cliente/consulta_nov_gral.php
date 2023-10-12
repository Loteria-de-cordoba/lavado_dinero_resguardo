<?php session_start();
//print_r($_SESSION);
//print_r($_SESSION['permiso']);
//print $_SESSION['area'];
//11 de junio retiro $_server

/*if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {*/
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
// $fecha = date("d/m/Y");
// echo $fecha;
// die();		
		//}
//$db->debug=true;

//print_r($_POST);
$variables=array();
//$casino='';
$quefecha='';
$i=0;
$j=0;
$casino=0;
$area='';
$myarea='';
$mymes=0;

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

while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//echo $_SESSION['rol'.$i];
	//print_r($_SESSION['rol'.$i]);
	//echo $_SESSION['rol'.$i];
	//Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
	//if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CASINO_CARGA' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
//print_r($_GET);
//print_r($_POST);
//$db->debug=true;
//echo $suc_ban.'sucban';
$array_fecha = FechaServer();	

while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;
	
		
		if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}
		 	else {	if (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}
					 else {	$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					 		//  $fecha='01/01/2012';
							}
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
			  //echo $dia.'  '.$mymes.' fechahasta consulta '.$fhasta_consulta;
			  //die();
			 }
			 		 else {	$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					 
					 //arreglo ultimos dias del mes
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
					 //echo $dia.' nnn   '.$mymes.' fechahasta consulta '.$fhasta_consulta;
					 // die();
					 }
				  }
		
$area=$_SESSION['area'];		
//if(substr($area,0,6)=='Casino' and !isset($_POST['casino']) and !isset($_GET['casino']))
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
} 
//obtengo datos para combo casino
if(isset($_REQUEST['casino']))
{
	$casino=$_REQUEST['casino'];
	$condicion_casino="and b.id_casino='$casino'";
}
else
{
	$casino=0;
	$condicion_casino='';
}

try {
			$rs_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino not in(2,13)
										");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 

?>	
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<?php 
if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_CASINO_CARGA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OP_UNICO'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OPERADOR') {
//$db->debug=true;
//echo('ENTRA');
/*if($casino<>100)
{*/
$_pagi_sql ="select to_char(a.fecha,'dd/mm/yyyy') as fecha,
			 a.casino as id_casino,  a.novedad as descripcion,
			 b.n_casino as casino
			 from PLA_AUDITORIA.t_observa_casino a,
			 		casino.t_casinos b
			 where a.casino=b.id_casino
			 		and a.fecha between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
					$condicion_casino
			 ORDER BY  1 desc,2";

$_pagi_div = "contenido";
$_pagi_enlace = "cliente/consulta_nov_gral.php";
$_pagi_cuantos = 10; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
$_pagi_propagar[0]='fecha';
$_pagi_propagar[1]='fhasta';
$_pagi_propagar[2]='casino';
$_pagi_propagar[3]='condicion_casino';

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
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','cliente/consulta_nov_gral.php',this); return false;">
<table width="65%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="texto4" scope="col">Consulta de Observaciones Generales[Datos Resguardados]</td>
</tr>
  <tr valign="bottom" class="td8" >
  <td width="61" valign="middle" class="td2"  scope="col">Casino: </td>
      <td width="45" valign="middle" class="td2"  scope="col"><?php armar_combo_todos($rs_casino,"casino",$casino)?></td>
      <td width="56" valign="middle" class="td2"  scope="col">Desde: </td>
      <td width="195" valign="middle" class="td2" scope="col"><?php  abrir_calendario('fecha','premio', $fecha);?></td>
      <td width="38" valign="middle" class="td2" scope="col">Hasta: </td>
      <td width="185" valign="middle" class="td2" scope="col"><?php  abrir_calendario('fhasta','premio', $fhasta);?></td>
    
   	<!-- <td width="162" class="td2" scope="col" align="right"><div align="center"><img src="image/s_okay.png" alt="Nuevo Apostador" width="16" height="16" /> <a href="#" onclick="ajax_get('contenido','cliente/validar_dni_ganador.php','fecha_inicio='+premio.fecha.value+'&fhasta='+premio.fhasta.value+'&casino=<?php//echo $casino;?>&apostador=<?php// echo //$apostador;?>');">Alta/Modif. de datos personales</a></div></td><?php //}?>-->
<!-- <td width="162" class="td2" scope="col" align="right"><div align="center"><img src="image/s_okay.png" alt="Nuevo Apostador" width="16" height="16" /> <a href="#" onclick="if(premio.apostador.value='0') {ajax_get('contenido','cliente/agregar_premio.php','fecha_inicio='+premio.fecha.value+'&fhasta='+premio.fhasta.value+'&casino=<?php //echo $casino;?>&apostador=<?php// echo $apostador;?>')} ;">Alta de datos personales</a></div></td>--><?php //}?>
	       <td width="31" align="center" class="td2" scope="col" ><a href="#" onclick="window.open('list1/datos_nov_gral.php?fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value+'&casino='+premio.casino.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" title="Reporte de Pantalla" width="20" height="20" border="0" /></a></td>
      <input type="hidden" name="fhasta_consulta" id="fhasta_consulta" value="<?php echo $fhasta_consulta; ?>" />
        
     <td width="70" class="td2" scope="col" align="right"> <input type="submit" value="Buscar" /></td>
    </tr>
</table>
</form>
<?php if ($_pagi_result->RowCount()==0)
 {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','blanco.php','')\">Cerrar</a></div>");
}
?>
<span class="td4">
<?php }?>
</span>
<table width="92%" border="0" align="center"> 
     <tr>
              <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
    <tr align="center" class="td2">
          <td width="6%" class="td4" scope="col">Fecha</td>
      <td width="18%" class="td4" scope="col">Origen</td>
    <td width="76%" class="td4" scope="col">Observacion</td>
  </tr>
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
            <td align="left"><?php 
		   if($row->FECHA<>$quefecha)
		   {
		  			 echo $row->FECHA;
		   }
		   $quefecha=$row->FECHA;
		   ?></td>
           <td align="left"><?php echo $row->CASINO;?></td>
           <td align="left"><textarea name="observamov" class="small"  id="observamov"  rows="2" cols="150" readonly="readonly"/><?php echo $row->DESCRIPCION;?></textarea></td>
          
           </tr>

<?php   }?>

		 <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
    <!--<tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina">&nbsp;</div></td>
    </tr>-->
    <?php if(isset($_GET['mensaje'])){?>
     <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="mensajito"><?php echo $_GET['mensaje'];?></div></td>
    </tr>
    <?php }?>
</table>

<?php } $_SESSION['sqlreporte']= $_pagi_sql; 
}//fin habilitado?>
