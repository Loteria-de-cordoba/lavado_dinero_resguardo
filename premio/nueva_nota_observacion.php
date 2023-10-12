<?php session_start(); 
include("../funcion.inc.php");
include_once("../db_conecta_adodb.inc.php");
include("../jscalendar-1.0/calendario.php");
//print_r($_GET); 
// $db->debug = true;?>
 
 <?php 
 
 try {$rsnota = $db->Execute("select nota_observacion
                              from PLA_AUDITORIA.t_ganador 
                              where id_ganador=?",array($_GET['id_ganador']));
                }
                catch (exception $e)
                {
                die ($db->ErrorMsg()); 
            }
 
 $row=$rsnota->FetchNextObject($toupper=true);
 
 ?>
 
<link href="../estilo/pedidos.css" rel="stylesheet" type="text/css">

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<form ID="FORMULARIONOTA" name="FORMULARIONOTA" method="post" action="#" onsubmit="ajax_post('contenido','premio/procesar_grabar_nota_observacion.php',this); return false;">


<table width="37%" height="155" border="1" align="center">

<tr>
	<td height="26" align="center" class="textoAzulOscuroFondo" style="">Nueva Nota</td>
</tr>
<tr> 
	  <tr>
	    <td width="257" height="87" colspan="2"><textarea name="nota" rows="5"  cols="60" class="textbox" id="nota"><?php echo $row->NOTA_OBSERVACION ?>  </textarea>
    </tr>
<tr>
    <td height="28" colspan="3" class="td" align="center"><input name="Aceptar" align="center" type="submit" class="smallTahomaRojo" id="Aceptar" value="Aceptar" />
      <input type="hidden" name="idganador" id="idganador" value="<?php echo $_GET['id_ganador'] ?>" />
      <input type="hidden" name="fecha" id="fecha" value="<?php echo $_GET['fdesde'] ?>" />
      <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta'] ?>" />
      <input type="hidden" name="conformado" id="conformado" value="<?php echo $_GET['conformado'] ?>" />
      <input type="hidden" name="suc_ban" id="suc_ban" value="<?php echo $_GET['suc_ban'] ?>" /></td>
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
 	?>
			<a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&conformado=<?php echo $_GET['conformado'] ?>&suc_ban=<?php echo $_GET['suc_ban'] ?>');"><img src="image/regresar.png" border="0"  /></a>
			<a href="#" onclick="ajax_get('contenido','premio/adm_premio_administra.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&conformado=<?php echo $_GET['conformado'] ?>&suc_ban=<?php echo $_GET['suc_ban'] ?>');">Regresar</a></div>

	<?php }
    else { 	?>	
			<a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&conformado=<?php echo $_GET['conformado'] ?>&suc_ban=<?php echo $_GET['suc_ban'] ?>');"><img src="image/regresar.png" border="0"  /></a>
			<a href="#" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&conformado=<?php echo $_GET['conformado'] ?>&suc_ban=<?php echo $_GET['suc_ban'] ?>');">Regresar</a></div>

	<?php 
	}
}?>



