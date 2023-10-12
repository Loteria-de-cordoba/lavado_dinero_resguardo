<?php 
	session_start();
	include("../jscalendar-1.0/calendario.php");
	include("../db_conecta_adodb.inc.php");
	include("../funcion.inc.php");
	//$db->debug=true;
	try {
    	$rs_imagen = $db -> Execute("select id_archivos, archivo_blob, nombre_real
									 from utilidades.t_archivos
									 where esquema='PLA_AUDITORIA' and tabla='t_ganador'
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
    <td colspan="2" align="center"><a href="#" onclick="ajax_get('contenido','upload/index.php','ganador=<?php echo $_GET['id_ganador']; ?>&esquema=lavado_dinero&tabla=t_ganador');"></a> | <a href="#" onClick="ajax_hideTooltip();">Cerrar</a></td>
  </tr>
  <?php while ($row_imagen=$rs_imagen->FetchNextObject($toupper=true)) {?>
  <tr>
    <td align="left" class="td5"><a href="premio/bajando.php?id_archivo=<?php echo $row_imagen->ID_ARCHIVOS; ?>"><?php echo $row_imagen->NOMBRE_REAL; ?></a></td>
    
  </tr>
  <?php }?>
</table>
