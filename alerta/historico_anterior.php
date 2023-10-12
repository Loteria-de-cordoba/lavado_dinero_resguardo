<?php /*
* formulario de RESUMEN DE ALERTAS
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
//condiciono al periodo
if(isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado']<>0)
{
	$id_estado=$_REQUEST['id_estado'];
	$condicion_estado="and to_number(to_char(b.fecha_aparicion,'mmyyyy'))=$id_estado";
	$condicion_estado_parcial="and to_number(to_char(tt.fecha_aparicion,'mmyyyy'))=$id_estado";
}
else
{
	$id_estado=0;
	$condicion_estado="";
	$condicion_estado_parcial="";
}

$_SESSION['abrografica']=1;
if(isset($_REQUEST['abrografica']))
	{
		$abrografica=1;
	}
	else
	{
		$abrografica=0;
	}
//$db->debug = true;
//obtengo el resumen
try {
$rs_resumen = $db -> Execute("SELECT tt.id_tipo_alerta, l.descripcion as descripcion, count(*) as ocurrencia,count(*) / 
                                  				(
												   select count(*)												  	 
													FROM PLA_AUDITORIA.base_alerta b
													WHERE TO_CHAR(B.FECHA_APARICION,'MMYYYY')<>TO_CHAR(SYSDATE,'MMYYYY')
														and b.id_estado_alerta=3
														$condicion_estado												
												) *100  AS PORCENTAJE       
								FROM PLA_AUDITORIA.base_alerta tt,
                					PLA_AUDITORIA.tipo_alerta l
								WHERE  l.id_tipo_alerta=tt.id_tipo_alerta
									and TO_CHAR(tt.FECHA_APARICION,'MMYYYY')<>TO_CHAR(SYSDATE,'MMYYYY')
									and tt.id_estado_alerta=3
									$condicion_estado_parcial
                				group by tt.id_tipo_alerta,l.descripcion");
	}
	catch (exception $e){die($db->ErrorMsg());}
	/*die('PROCES0');
	  while ($row_rec = $rs_recorrido->FetchNextObject($toupper=true)) 
				{ $nro=$row_rec->NRO;}*/
		$rs_resumen->MoveFirst();		
?>

<table width="800"  border="1" align="center">
  <tr>
    <td colspan="3" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif">RESUMEN DE ALERTAS -  <?php if($_REQUEST['id_estado']<>0) {echo 'Periodo '.$_REQUEST['id_estado'];}else
	{ echo "Periodos Anteriores";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="#" class="smallTahomaRojoNegrita" style="margin-left:5px;" onclick="vercharts('ifchart','alerta/graf_alerta_anterior.php?fecha=<?php echo $_REQUEST['fecha'];?>&id_estado=<?php echo $id_estado;?>');"><img src="image/rrhh_statistics.png" title="Ver Grafico" width="16" height="16" border="0" /></a>
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="window.print();"><img src="image/24px-Crystal_Clear_app_printer.png" title="Imprimir Pantalla" width="16" height="16" border="0" align="absbottom" />Imprimir Pantalla</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="ajax_get('historico','alerta/blanco.php','')"><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/>Cerrar Res&uacute;men</a>
              
        </td>
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
   <?php if($_SESSION['abrografica']==1)
		{?>
  		<tr>
            <td align="center" colspan="3">
           
            <a href="#" class="td5" onclick="verchartscierre('ifchart','alerta/blanco.php')"><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/>Cerrar Grafico</a>
            </td>        
        </tr>
        <?php }?>
        <tr>
		<td align="center" colspan="3">
		<iframe id="ifchart" name="ifchart"  src="" height="0" width="0" frameborder="0" ></iframe>
		</td>
	</tr>
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
