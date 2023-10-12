<?php 
	session_start();
	include("../jscalendar-1.0/calendario.php");
	include("../db_conecta_adodb.inc.php");
	include("../funcion.inc.php");
	//$db->debug=true;
	//print_r($_GET);
	try {
    	$rs_imagen = $db -> Execute("select id_archivos, archivo_blob, nombre_real
									 from utilidades.t_archivos
									 where esquema='lavado_dinero' and tabla='t_ganador'
									 and id_tabla=?",
									 array($_GET['id_ganador']));
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 
	
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<table width="55%" border="0">
  <tr class="smallRojo2">
    <td  align="center"><a href="#" onclick="ajax_get('contenido','upload/index.php','ganador=<?php echo $_GET['id_ganador']; ?>&esquema=lavado_dinero&tabla=t_ganador');"> Adjuntar otra imagen</a>  </td>
  	<td align="right" colspan="2"><a href="#" onClick="ajax_hideTooltip();">Cerrar</a> </td>
  </tr>
  <?php while ($row_imagen=$rs_imagen->FetchNextObject($toupper=true)) {?>
  <tr>
    <td align="left"  class="td5"><a href="premio/bajando.php?id_archivo=<?php echo $row_imagen->ID_ARCHIVOS; ?>"><?php echo $row_imagen->NOMBRE_REAL; ?></a></td>
    <td class="td5" align="center">
    <?php if(($_GET['conformado']==0)){ ?>
    <a href="#" onclick="confirmar_eliminar_imagen('contenido','premio/procesar_eliminar_imagen.php','<?php echo $row_imagen->NOMBRE_REAL;?>&id_archivo=<?php echo $row_imagen->ID_ARCHIVOS; ?>&id_ganador=<?php echo $_GET['id_ganador']; ?>','')"><img src="image/Trash-Empty.png" alt="Eliminar Imagen"  width="20" height="20" border="0"/></a> 
    <?php } else {?>
    <img src="image/candado.png" alt="Ganador Conformado, Imposible Eliminar Imagen"  width="24" height="24" border="0"/>
    <?php }?></td>
	<td class="td5" align="center">
	 <?php if(($_GET['conformado']==0)){ ?>
    <a href="#" onclick="ajax_get('contenido','premio/modificar_nombre_imagen.php','nombre_imagen=<?php echo $row_imagen->NOMBRE_REAL;?>&id_archivo=<?php echo $row_imagen->ID_ARCHIVOS; ?>&id_ganador=<?php echo $_GET['id_ganador']; ?>&fdesde=<?php echo $_GET['fdesde']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>&conformado=<?php echo $_GET['conformado']; ?>&suc_ban=<?php echo $_GET['suc_ban']; ?>','')"><img src="image/b_edit.png" alt="Modificar Nombre Imagen"  width="20" height="20" border="0"/></a> 
    <?php } else {?>
    <img src="image/candado.png" alt="Ganador Conformado, Imposible Modificar Imagen"  width="24" height="24" border="0"/>
    <?php }?>
	</td>
  </tr>
  <?php }?>
</table>