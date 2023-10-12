<?php session_start();
//print_r($_GET).'get';
//print_r($_POST).'post';
$conformado=$_REQUEST['conformado'];
$suc_ban=$_REQUEST['suc_ban'];
$i=0;
if(isset($_GET['condicion']))
{
	$condicion=$_GET['condicion'];
}
else
{
	$condicion=0;
}
while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
		
?>
<div  id="adjuntar"> 
  <iframe align="center" width="80%"   src="upload/upload.php?id_tabla=<?php echo $_GET['ganador']; ?>&esquema=<?php echo $_GET['esquema']; ?>&tabla=<?php echo $_GET['tabla']; ?>&condicion=<?php echo $condicion;?>" frameborder="0"></iframe>
</div>
<div align="center">
  
 <?php if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA')||($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO') || ($_SESSION['rol'.$i]=='LAVADO_DINERO_CONFORMA_TODO')){ ?>
 <a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>');return false;">Regresar</a>
<a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>');"><img src="image/regresar.png" alt="" width="20" height="20" border="0"  /></a></div>
 <?php } elseif (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CONFORMA')){?>
 
 <a href="#" onclick="ajax_get('contenido','premio/premios_conformados.php','conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>');return false;">Regresar</a>
<a href="#" onclick="ajax_get('contenido','premio/premios_conformados','conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>');"><img src="image/regresar.png" alt="" width="20" height="20" border="0"  /></a></div>
 
 
<?php } else {
				if($condicion==0)
				{
				?>				
				<a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>');return false;">Regresar</a>
				<a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>');"><img src="image/regresar.png" alt="" width="20" height="20" border="0"  /></a></div>
				<?php 
				}
				else
				{?>
				<a href="#" onclick="ajax_get('contenido','premio/lista_planillas.php','conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>');return false;">Regresar</a>
				<a href="#" onclick="ajax_get('contenido','premio/lista_planillas.php','conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>');"><img src="image/regresar.png" alt="" width="20" height="20" border="0"  /></a></div>
				<?php 
				}
}}
?>