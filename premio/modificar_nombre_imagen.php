<?php session_start(); 
include("../funcion.inc.php");
include_once("../db_conecta_adodb.inc.php");
include("../jscalendar-1.0/calendario.php");
//print_r($_GET); 
//die();
// $db->debug = true;
//print_r($_GET);
//die('entre a modificar nmbre imagen'); 
$nombre= utf8_encode($_GET['nombre_imagen']);
$extension=substr($nombre,-4);

$renombre=explode(".",$nombre);
//echo $renombre[0];
//echo $renombre[1];
//die();
/*
if($extension=='.jpg'){
	$renombre=explode(".jpg",$nombre);
	}
if($extension=='.png'){
	$renombre=explode(".png",$nombre);
	}
 */
 if(isset($_GET['condicion']))
{
	$condicion=$_GET['condicion'];
	$titulin='Modificar Nombre Planilla';
}
else
{
	$condicion=0;
	$titulin='Modificar Nombre Imagen';
}
 
 ?>
 
<link href="../estilo/pedidos.css" rel="stylesheet" type="text/css">

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo2 {font-size: 12}
-->
</style>
 <form ID="FORMULARIONOTA" name="FORMULARIONOTA" method="post" action="#" onsubmit="validar_modificacion_nombre('contenido','premio/procesar_modificacion_nombre.php',this); return false;">


<table width="599" height="100" border="1" align="center">
	<tr>
		<td height="26" align="center" class="textoAzulOscuroFondo" colspan="2" ><b><?php echo $titulin;?></b></td>
	</tr>
    <tr>
		<td width="302" height="32"><input type="text" size="40" name="nombre" id="nombre" value="<?php echo $renombre[0]; ?>"/></td>
		<td width="281" height="32" class="small"> *No poner la extension del archivo</td>
	</tr>
	<tr>
    	<td height="28" colspan="2" class="td" align="center"><input name="Aceptar" align="center" type="submit" class="smallTahomaRojo" id="Aceptar" value="Modificar" />
		  <input type="hidden" name="idganador" id="idganador" value="<?php echo $_GET['id_ganador'] ?>" />
		  <input type="hidden" name="fecha" id="fecha" value="<?php echo $_GET['fdesde'] ?>" />
		  <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta'] ?>" />
		  <input type="hidden" name="conformado" id="conformado" value="<?php echo $_GET['conformado'] ?>" />
		  <input type="hidden" name="suc_ban" id="suc_ban" value="<?php echo $_GET['suc_ban'] ?>" />
		   <input type="hidden" name="id_archivo" id="id_archivo" value="<?php echo $_GET['id_archivo'] ?>" />
		    <input type="hidden" name="extension" id="extension" value="<?php echo $renombre[1];?>" />
            <input type="hidden" name="condicion" id="condicion" value="<?php echo $condicion;?>" /></td>
            
	</tr>
</table>

</form>
<div align="left">
<?php 
//if ($_SESSION['permiso'] == 'ADM_CONFORMA' || $_SESSION['permiso'] == 'ADM_CASINO' ){
while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;

 $_SESSION['bandera']=1;
 if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA'){
 if(!isset($_GET['condicion']))//viene de una imagen comun
		{
 	?>
			<a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&conformado=<?php echo $_GET['conformado'] ?>&suc_ban=<?php echo $_GET['suc_ban'] ?>');"><img src="image/regresar.png" border="0"  /></a>
			<a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&conformado=<?php echo $_GET['conformado'] ?>&suc_ban=<?php echo $_GET['suc_ban'] ?>');">Regresar</a></div>

	<?php }
	else
	{?>
    		<a href="#" onclick="ajax_get('contenido','premio/lista_planillas.php','');"><img src="image/regresar.png" border="0"  /></a>
			<a href="#" onclick="ajax_get('contenido','premio/lista_planillas.php','');">Regresar</a></div>

    <?php }}
    else { 	?>	
			<a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&conformado=<?php echo $_GET['conformado'] ?>&suc_ban=<?php echo $_GET['suc_ban'] ?>');"><img src="image/regresar.png" border="0"  /></a>
			<a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&conformado=<?php echo $_GET['conformado'] ?>&suc_ban=<?php echo $_GET['suc_ban'] ?>');">Regresar</a></div>

	<?php 
	}
}?>



