<?php //$pepe=$_GET['apellido'].', '.$_GET['nombre']
//print_r($_GET);
session_start();
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
$apodo=$_GET['apodo'];
//$tipo=$_GET['tipo'];
//print_r($_GET);
$casino='';
$cliente='';
$fecha_inicio=$_GET['fecha_inicio'];
$fhasta=$_GET['fhasta'];
//$db->debug=true;
try {
	$rs_consulta = $db->Execute("select id_cliente as cliente, id_casino as casino
									from PLA_AUDITORIA.t_cliente
									where trim(lower(decode(nombre,null,apellido,apellido || ' ' || nombre)))=trim(lower(?))
									",array($apodo));
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
$mensaje='Apelido y Nombre o Apodo repetido - DEBE CAMBIARLO!!!';
?>
	<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<table width="49%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="td2" scope="col">Apostador Registrado - Actualiza sus datos</a></td>
</tr>
  <tr valign="bottom" class="td8" >
	  <td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','cliente/modificar_apostador.php','id_cliente=<?php echo $cliente;?>&casino=<?php echo $casino;?>&fecha_inicio=<?php echo $fecha_inicio;?>&fhasta=<?php echo $fhasta;?>'); return false;"></td>      
      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('contenido','cliente/agregar_premio.php','casino=<?php echo $casino;?>&mensaje=<?php echo $mensaje;?>&apellido=<?php echo $apodo?>&fecha_inicio=<?php echo $fecha_inicio;?>&fhasta=<?php echo $fhasta;?>'); return false;"></td>
      <!--<td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('apoapo','cliente/blanco.php',''); return false;"></td>-->  
      
    </tr>
</table>
<?php }
?>
