<?php session_start(); 
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
include_once("../jscalendar-1.0/calendario.php");
//print_r($_GET);
//die();
$j=0;
while ($j<$_SESSION['cantidadroles'])  {

	$j=$j+1;




//$db->debug=true;

 try
{
	    $rsarchivos=$db->Execute("select id_archivos, archivo_blob, nombre_real
									 from PLA_AUDITORIA.T_ARCHIVOS
									 where esquema='lavado_dinero' and tabla='t_ganador'
									 and id_tabla=13847");
}
catch  (exception $e) 
{ 
			die($db->ErrorMsg());
}
 


 ?>

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-size: 10px}
-->
</style>
<form id="form" name="form" onsubmit="ajax_post('contenido','detalle_casino_cp.php',this); return false;">


<table width="36%" align="center" border="1"> 
 <?php if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA') {?>
 <tr> 
 <td  class="td_nuevo"  colspan="2" align="center"><b>Listado de Planillas[Datos Resguardados]</b></td>
 <!--<td  class="td_nuevo" colspan="3" align="center"><a href="#" onclick="ajax_get('contenido','upload/index_resguardo.php','ganador=13847&esquema=lavado_dinero&tabla=t_ganador&conformado=1&suc_ban=0&condicion=1');"/><img src="image/My-Docs.png" width="27" height="24" border="0" title="Nueva Planilla"/></a></td>-->
  </tr>
 <?php }
 else
 {?>
 <tr>
    <td colspan="2" class="td_nuevo" align="center"><b>Listado de Planillas[Datos Resguardados]</b></td>
    <?php }?> 
 </tr> 
<tr> <?php while ($rowarchivos= $rsarchivos->FetchNextObject($toupper=true))
   {?>
    <td width="82%" align="left" class="td2"><?php echo $rowarchivos->NOMBRE_REAL; ?></td>
    <td width="18%" align="center" class="td2"><a href="premio/bajando.php?id_archivo=<?php echo $rowarchivos->ID_ARCHIVOS; ?>"><img src="image/jamembo-jumpto.png" title="Descargar Planilla" width="28" height="28" border="0" /></a></td>
    <?php 	 
	 if (($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA')) {?>
    <!-- <td class="td5" align="center">
    <a href="#" onclick="confirmar_eliminar_planilla('contenido','premio/procesar_eliminar_imagen.php','<?php// echo $rowarchivos->NOMBRE_REAL;?>&id_archivo=<?php// echo $rowarchivos->ID_ARCHIVOS; ?>&id_ganador=13847&suc_ban=0&condicion=1','')"><img src="image/Trash-Empty.png" title="Eliminar Planilla"  width="20" height="20" border="0"/></a>	</td>
	<td class="td5" align="center">
	<a href="#" onclick="ajax_get('contenido','premio/modificar_nombre_imagen.php','nombre_imagen=<?php// echo $rowarchivos->NOMBRE_REAL;?>&id_archivo=<?php// echo $rowarchivos->ID_ARCHIVOS; ?>&id_ganador=13847&suc_ban=0&condicion=1','')"><img src="image/b_edit.png" title="Modificar Nombre Planilla"  width="20" height="20" border="0"/></a>	</td>-->
	 <?php }?></tr><?php 
    }?>
</table>
<?php if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA') {?>
<div align="center"><a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','');"><img src="image/regresar.png" width="27" height="24" border="0"  /></a>
    <a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','');">Regresar</a></div>
<?php   } else {?>  
<div align="center"><a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','');"><img src="image/regresar.png" width="27" height="24" border="0"  /></a>
    <a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','');">Regresar</a></div>
<?php   }
}?> 