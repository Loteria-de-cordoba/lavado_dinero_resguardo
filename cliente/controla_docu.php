<?php //$pepe=$_GET['apellido'].', '.$_GET['nombre']
//print_r($_GET);
session_start();
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
$documento=$_GET['documento'];
$tipo=$_GET['tipo'];
$casino='';
$cliente='';
$fecha_inicio=$_GET['fecha_inicio'];
$fhasta=$_GET['fhasta'];
$abm=$_GET['abm'];//vale 1 si es alta
//echo $abm;
$apodo=$_GET['apodo'];
if(isset($_GET['id_cliente']))
	{
	$clienteviene=$_GET['id_cliente'];
	}
//$db->debug=true;
//print_r($_GET);
try {
	$rs_consulta = $db->Execute("select id_cliente as cliente, id_casino as casino
									from PLA_AUDITORIA.t_cliente
									where documento=?
									and id_tipo_documento=?",array($documento, $tipo));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}

$row=$rs_consulta->FetchNextObject($toupper=true);
if($rs_consulta->RecordCount()<>0)
	{
	$cliente=$row->CLIENTE;
	$casino=$row->CASINO;
	}
if($cliente<>'')
{
$mensaje='Documento '.$documento.' se encuentra Repetido - DEBE CAMBIARLO!!!';
?>
	<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<table width="49%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="td2" scope="col">Documento Repetido - Recupera los datos??</a></td>
</tr>
  <tr valign="bottom" class="td8" >
	  		<td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','cliente/modificar_apostador.php','id_cliente=<?php echo $cliente;?>&casino=<?php echo $casino;?>&fecha_inicio=<?php echo $fecha_inicio;?>&fhasta=<?php echo $fhasta;?>'); return false;"></td>      
		 <?php if($abm==1)
		 {?>
         <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('contenido','cliente/agregar_premio.php','casino=<?php echo $casino;?>&mensaje=<?php echo $mensaje;?>&apellido=<?php echo $apodo?>&fecha_inicio=<?php echo $fecha_inicio;?>&fhasta=<?php echo $fhasta;?>&documento=<?php echo $documento;?>'); return false;"></td>
         <?php } 
		 else
		 {
		 ?>
          <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('contenido','cliente/modificar_apostador.php','id_cliente=<?php echo $clienteviene;?>&casino=<?php echo $casino;?>&mensaje=<?php echo $mensaje;?>&apellido=<?php echo $apodo?>&fecha_inicio=<?php echo $fecha_inicio;?>&fhasta=<?php echo $fhasta;?>&documento=<?php echo $documento;?>'); return false;"></td>      
         <?php }?>
  
<!--      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('docudocu','cliente/blanco.php',''); return false;"></td>-->  
      
    </tr>
</table>
<?php }
?>
