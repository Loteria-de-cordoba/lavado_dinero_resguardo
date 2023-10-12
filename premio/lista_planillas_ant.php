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
	    $rsarchivos=$db->Execute(" select * from UTILIDADES.t_archivos where id_tabla=13847 ");
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
 <tr>
    <td colspan="2" class="td_nuevo" align="center">Listado de Planillas </td>
  </tr>
  
<tr> <?php while ($rowarchivos= $rsarchivos->FetchNextObject($toupper=true))
   {?>
    <td width="87%" align="left" class="td2"><?php echo $rowarchivos->NOMBRE_REAL; ?></td>
    <td width="13%" align="center" class="td2"><a href="premio/bajando.php?id_archivo=<?php echo $rowarchivos->ID_ARCHIVOS; ?>"><img src="image/jamembo-jumpto.png" alt="Descargar Planillas" width="28" height="28" border="0" /></a></td>
  </tr>
<?php   }?>
</table>
<?php if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA') {?>
<div align="center"><a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','');"><img src="image/regresar.png" width="27" height="24" border="0"  /></a>
    <a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','');">Regresar</a></div>
<?php   } else {?>  
<div align="center"><a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','');"><img src="image/regresar.png" width="27" height="24" border="0"  /></a>
    <a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','');">Regresar</a></div>
<?php   }
}?> 