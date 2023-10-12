<?php /*
* formulario de DETALLE DE ESTADOS DE UN ALERTA
* 19/05/2014
* PARODI VICTOR
* 
*/
session_start();
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
//print_r($_REQUEST);
//die('entre');
//$db->debug=true;
$id=$_REQUEST['id_base'];

$fecha=str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

try {
 $rs_estados_alerta = $db ->Execute("SELECT es.descripcion as descripcion,
 											to_char(exa.fecha_mod,'dd/mm/yyyy') as fecha,
											exa.id_Estado_alerta as ID_eSTADO
 							FROM lavado_dinero.estado_alerta es,
								 lavado_dinero.estado_x_alerta exa
							WHERE exa.id_Estado_alerta=es.id_estado_alerta
							and exa.ID_BASE=?
							order by exa.fecha_mod desc	
					",array($id));
			}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}

//$observaciones=$row_observa->OBSERVACIONES;
?>
<table width="435"  border="1" align="center">
<tr>
  <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>Hist&oacute;rico de Estados[Resguardo]</b></td>
</tr>
<?php
if($rs_estados_alerta->RowCount()<>0)
{
?>
<tr>
  <td colspan="2"   align="center" style="background-color:#FFCCFF; font:Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold"><p>ESTADO</p></td> 
  <td width="259" colspan="2"     align="center" style="background-color:#FFCCFF; font:Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold"><p>FECHA DE MODIFICACION</p></td> 
</tr>
<?php
while($row_estados_alerta =$rs_estados_alerta->FetchNextObject($toupper=true))
{
 switch ($row_estados_alerta->ID_ESTADO)
  	{
		case 1:
			$color='#FF0000';
			break;
		case 2:
			$color='#FFFF33';
			break;
		case 3:
			$color='#00FF33';
			break;
		default:
			$color='#00FF33';
	}
?>
<tr>
  <td colspan="2"   align="left" style="background-color:<?php echo $color;?>; font:Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold"><p><?php echo $row_estados_alerta->DESCRIPCION;?></p></td> 
  <td colspan="2" align="center" style="background-color:#FFFFFF; font:Arial, Helvetica, sans-serif; font-size:15px; font-weight:bold"><p><?php echo $row_estados_alerta->FECHA;?></p></td> 
</tr>
<?php
}
}
else//no tiene registros
{
?>
<tr>
  <td colspan="4" align="center" style="background-color:#FFCCFF; font:Arial, Helvetica, sans-serif; font-size:15px"><b>Sin registros de Cambio de Estado</b></td>
</tr>
<?php 
}
?>
</table>
