<?php /*
* formulario de HISTORICO DE ESTADOS DE UN VIATICO
* 26/08/2013
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
//$db->debug = true;
//obtengo EL RECORRIDO
try {
$rs_resumen = $db -> Execute("SELECT TT.DESCRIPCION, H.OCURRENCIA, H.OCURRENCIA / (
                                                   SELECT SUM(OCURRENCIA) 
                                                   FROM PLA_AUDITORIA.HISTORICO_ALERTA
                                              WHERE TO_CHAR(fecha_alerta,'DD/MM/YYYY')=TO_CHAR(sysdate,'DD/MM/YYYY')
                                              ) *100  AS PORCENTAJE       
								FROM PLA_AUDITORIA.HISTORICO_ALERTA H,
								PLA_AUDITORIA.TIPO_ALERTA TT
								WHERE TT.ID_TIPO_ALERTA=H.ID_TIPO_ALERTA
								AND TO_CHAR(H.fecha_alerta,'DD/MM/YYYY')=TO_CHAR(sysdate,'DD/MM/YYYY')");
	}
	catch (exception $e){die($db->ErrorMsg());}
	/*die('PROCES0');
	  while ($row_rec = $rs_recorrido->FetchNextObject($toupper=true)) 
				{ $nro=$row_rec->NRO;}*/
		$rs_resumen->MoveFirst();		
?>

<table width="800"  border="1" align="center">
  <tr>
    <td colspan="3" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif">RESUMEN DE ALERTAS -  <?php echo $_REQUEST['fecha'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/> <a href="#" class="td5" onclick="ajax_get('historico','alerta/blanco.php','')">Cerrar</a></td>
  </tr>
  <tr style="background-color:#CCFFCC">
    <td width="430" height="24"  align="center">ALERTA</td>
    <td width="163"  align="center">OCURRENCIAS</td>
    <td width="185"  align="center">PORCENTAJE</td>
  </tr>
  <?php  while ($row_rec = $rs_resumen->FetchNextObject($toupper=true)) 
				{ ?>
  <tr onmouseover="this.style.background='red'" onmouseout="this.style.background='#996600'">
    <td  align="left" style="font-size:11px;"><?php echo utf8_encode($row_rec->DESCRIPCION);?></td>
    <td  align="right" style="font-size:13px;"><b><?php echo $row_rec->OCURRENCIA;?></b></td>
    <td  align="right"  style="font-size:13px;"><b><?php echo number_format($row_rec->PORCENTAJE,2,'.',',').'%';?></b></td>
    
  </tr>
  <?php }
  ?>
</table>
<!--<br>
<br>
<table width="120" border="0" align="center" cellspacing="0">
  <tr>
    <td align="center"><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="td5" onclick="ajax_get('historico','f_parametros/blanco.php','')">Cerrar</a></td>
  </tr>
 <td    colspan="2" align="left"><div id="resultado">&nbsp; </div></td>
  <tr> </tr>
</table>-->
