<?php /*
* formulario de ESTADISTICO ANUAL ROS
* CANTIDAD DE REPORTES DE OPERACIONES SOSPECHOSAS
* 03/01/2014
* PARODI VICTOR
* 
*/
session_start();
//COMBO CUIT
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
$_SESSION['abrografica']=1;
$cuenta=0;
if(isset($_REQUEST['abrografica']))
	{
		$abrografica=1;
	}
	else
	{
		$abrografica=0;
	}
//$db->debug = true;
try {
 $rs_cuit = $db ->Execute("select DISTINCT cuit as codigo,
						apellido || decode(nombre,'','',', ' || nombre)||'['||cuit||']' as descripcion
						from PLA_AUDITORIA.t_ganador 
						where cuit is not null
						and length(cuit)=11
						order by 2		
					");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
	if(isset($_REQUEST['cuit'])&& $_REQUEST['cuit']<>0)
					 {
						$cuit= $_REQUEST['cuit'];
					 }
				else
					{
						$cuit=0;
					}	
	
	if((isset($_REQUEST['control']) and !isset($_REQUEST['conesta'])) or isset($_REQUEST['mensaje']))
					 {
						$control=1;
					 }
				else
					{
						$control=0;
					}	
				
	if(isset($_REQUEST['fecha']))
					 {
						$fecha= $_REQUEST['fecha'];
					 }
				/*else
					{
						$fecha=0;
					}	*/
	
	if(isset($_REQUEST['otrocuit'])&& $_REQUEST['otrocuit']<>'')
					 {
						$otrocuit= $_REQUEST['otrocuit'];
					 }
				else
					{
						$otrocuit='';
					}	
	
	if(isset($_REQUEST['abro'])&& $_REQUEST['abro']<>0)
					 {
						$abro= $_REQUEST['abro'];
					 }
				else
					{
						$abro='';
					}
	
	if(isset($_REQUEST['voy'])&& $_REQUEST['voy']<>0)
					 {
						$voy= $_REQUEST['voy'];
					 }
				else
					{
						$voy='';
					}		
					
	if(isset($_REQUEST['pesitos'])&& $_REQUEST['pesitos']<>0)
					 {
						$pesitos= $_REQUEST['pesitos'];
					 }
				else
					{
						$pesitos="0.00";
					}	
//echo 'cuit'.$cuit;

//obtengo EL RECORRIDO
try {
$rs_resumen = $db -> Execute("SELECT TO_CHAR(FECHA,'YYYY') AS PERIODO,
									COUNT(*) AS ROS, 
									COUNT(*)/(SELECT COUNT(*) FROM PLA_AUDITORIA.T_GANADOR) * 100 AS PORCENTAJE
									FROM PLA_AUDITORIA.T_GANADOR GROUP BY TO_CHAR(FECHA,'YYYY') 
									ORDER BY TO_CHAR(FECHA,'YYYY')");
	}
	catch (exception $e){die($db->ErrorMsg());}
	/*die('PROCES0');
	  while ($row_rec = $rs_recorrido->FetchNextObject($toupper=true)) 
				{ $nro=$row_rec->NRO;}*/
		$rs_resumen->MoveFirst();		
?>
<script type="text/javascript" language="javascript">
if(this.document.onload()){ alert('aaaaaa');} else {alert ('bbbb');}
</script>
<form id="estad" name="estad" action="#" onSubmit="ajax_post('contenido','alerta/estadistico.php',this); return false;">
<table width="88%"  align="center" border="2">
<tr>
<!--<td background="image/sospechosa.png"  style="background-repeat:no-repeat; background-position:left;" id="book" height="130">-->
<td><div id="clickme" style="color:#FF0000; text-align:center"><a onClick="ampliarImagen();" href="#">
<img id="book" src="image/sosp.png" title="Haga Clic P/animar" width="20" height="20" border="2" style="position: relative; left: 10px;" >
</a>
<b>&nbsp;&nbsp;&nbsp;OPERACIONES SOSPECHOSAS</b>
</div>
</td></tr>
  <tr><td>
  <?php if($abro<>1)
	{?>
  <table width="70%"  align="center">
    <tr>
      <!--&pesitos='+estad.pesitos.value+'&cuit='+estad.cuit.value+'&otrocuit='+estad.otrocuit.value-->
      <td colspan="3" height="2" align="center" valign="bottom" style="background-color:#FFCCCC; font:bold" scope="col"><b>CONTROL DE RIESGO EN OPERACION SOSPECHOSA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="ajax_get('contenido','alerta/adm_alerta.php','')"><img src="image/undo.png" title="Retorna a Administracion de Alertas" width="16" height="16" border="0" align="absbottom"/>Regresar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($control<>1) {?><a href="#" onClick="ajax_get('xxx','alerta/estadistico.php','abro=1&fecha=<?php echo $_REQUEST['fecha'];?>&pesitos=<?php echo $pesitos;?>&cuit=<?php echo $cuit;?>&otrocuit=<?php echo $otrocuit;?>&voy=<?php echo $voy;?>');ampliarImagen()"><img src="image/puerta.jpg"  title="Estadistico" width="16" height="16" border="0" align="absbottom"/>Estadistico</a><?php }?></b></td>
    </tr>
    <tr>
      <td width="241" style="background-color:#CCFFCC" align="left" valign="middle" class="td2"  scope="col"  rowspan="2"><b>Sujeto Existente en Base[CUIT/CUIL]:</b>&nbsp;&nbsp;</td>
      <td align="left"   valign="middle"  scope="col" colspan="2"><?php  armar_combo_ejecutar_ninguno_ajax_get_puntero($rs_cuit,'cuit',$cuit,'ccuit','alerta/ccuit.php');?></td>
    </tr>
    <tr>
      <td  valign="middle" cstyle="background-color:#CCFFCC" scope="col" align="left" colspan="2">
      <div id="ccuit">
          <?php if(!isset($_REQUEST['voy']) or $cuit==0){?>
          <input name="otrocuit" type="text" style="text-align:right; font:bold;" id="otrocuit" value="<?php echo $otrocuit;?>" onBlur="if(this.value.length!=11 && this.value!=''){alert('NRO DE CUIT/CUIL INVALIDO');this.value='';return false;}"/>
          &nbsp;&nbsp;<b>[Nuevo]</b>
          <?php }?>
       </div>      </td>
    </tr>
    <tr  rowspan="2">
      <!--<td width="4%" align="left"   valign="middle" class="td2" scope="col"></td>-->
      <td width="241"  align="left" valign="middle" style="background-color:#CCFFCC"  scope="col"><b>Monto de la Operaci&oacute;n:</b>&nbsp;&nbsp;</td>
      <td width="425" align="left"   valign="middle"  scope="col" colspan="2"><input name="pesitos" type="text" style="text-align:right; font:bold;" id="pesitos" value="<?php echo $pesitos;?>" onBlur="if(this.value.length>11 || this.value<0){alert('NRO DEMASIADO GRANDE o NEGATIVO');$('#pesitos').val('0.00');$('#pesitos').focus();} else {validapeso('controlpesos','alerta/ctrlpesos.php','estad','ff='+parseFloat(this.value));}"/><div id="controlpesos" style="display:inline-block">&nbsp;</div></td>
      
<!--      onBlur="if(this.value.length>11 || this.value<0){alert('NRO DEMASIADO GRANDE o NEGATIVO');$('#pesitos').val('0.00');$('#pesitos').focus();} else {if(document.getElementById('control').style.visibility=='visible'  && document.getElementById('oculto')!=null && $('#oculto').val()=='0') {alert('entro');esconder('0');};var xx=document.getElementById('controlpesos'); xx.style.display='block'; ajax_get('controlpesos','alerta/ctrlpesos.php','ff='+parseFloat(this.value));if($('#oculto').val()=='0' || $('#oculto').val()=='undefined'){mostrar('0');}}"-->
      
      <input name="voy" type="hidden"  id="voy" value="1"/>
      <input name="abro" type="hidden"  id="abro" value="<?php echo $abro?>"/>
      <input name="fecha" type="hidden"  id="fecha" value="<?php echo $fecha;?>"/>
    </tr>
    <tr>
      <td colspan="3" align="center"   valign="middle" class="td2" scope="col" style="background-color:#FFCCCC; font:bold"><b><span class="td2" style="background-color:#FFCCCC; font:bold"><b><span class="td2" style="background-color:#FFCCCC; font:bold"><b><span class="td2" style="background-color:#FFCCCC; font:bold"><b>
        <input style="color:#FF0000;font:bold; font-size:15px; visibility:visible;" name="control" type="submit" id="control" value="Control"   onClick="ampliarImagen();" />
      </b></span></b></span></b></span></b></td>
    </tr>
    
    <tr>
      <?php if(isset($_REQUEST['voy']) and $_REQUEST['voy']<>0)
  {
	  if($cuit<>0)
	  {
	  	$micuit=$cuit;
	  }
	  else
	  {
	  	$micuit=$otrocuit;
	  }
if($micuit<>0 && $micuit<>'')//control
{
 try {
 	$rs_check=$db -> Execute("select PLA_AUDITORIA.check_riesgo(?,?) as ppp from dual",array($micuit,$pesitos));}				
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_check =$rs_check->FetchNextObject($toupper=true);
	$riesgo=$row_check->PPP;
 	$riesgoexplode=explode("-",$riesgo); 
  
  ?>
      <td colspan="3">
      <table width="100%"  align="center" border="2">
          <?php if(isset($_REQUEST['mensaje']))
	{?>
          <tr>
            <td  class="td_detalle" align="center" colspan="3" style="color:#FF0000; font-size:12px"><b><?php echo strtoupper($_REQUEST['mensaje'])?></b></td>
          </tr>
          <?php }?>
          <tr>
            <td colspan="2" height="2" align="center" valign="bottom" style="background-color:#999966; font:bold" scope="col"><b>MATRIZ DE RIESGO</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="ajax_get('contenido','alerta/estadistico.php','voy=0&abro=0&fecha=<?php echo $_REQUEST['fecha'];?>');ampliarImagen();"><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/>Cerrar Matriz</a></td>
          </tr>
          <tr>
            <td width="69%" align="center" style="background-color:#FFFFCC; font:bold"><b>Condici&oacute;n</b></td>
            <td width="31%" align="center" style="background-color:#FFFFCC; font:bold"><b>Incidencia<br />
              (Impacto + Probabilidad)</b></td>
          </tr>
          <tr>
            <td align="left"><?php echo $riesgoexplode[0];if($riesgoexplode[1]<>0){?>&nbsp;&nbsp;<a href="#" onClick="ajax_get('detriesgo','alerta/detalle_riesgo.php','fecha=<?php echo $fecha;?>&componente=<?php echo $riesgoexplode[1];?>&riesgo=<?php echo '1';?>&voy=<?php echo $voy;?>&abro=0&cuit=<?php echo $cuit;?>&otrocuit=<?php echo $otrocuit;?>&pesitos=<?php echo $pesitos;?>')"><img src="image/C_EditState_md - Copy.png" TITLE="Actualizacion del Componente del Riesgo" width="20" height="20" border="0" /></a>
              <?php }?></td>
            <td align="right"><?php echo $riesgoexplode[1];?></td>
          </tr>
          <tr>
            <td align="left"><?php echo $riesgoexplode[2];if($riesgoexplode[3]<>0){?>&nbsp;&nbsp;<a href="#" onClick="ajax_get('detriesgo','alerta/detalle_riesgo.php','fecha=<?php echo $fecha;?>&componente=<?php echo $riesgoexplode[3];?>&riesgo=<?php echo '2';?>&voy=<?php echo $voy;?>&abro=0&cuit=<?php echo $cuit;?>&otrocuit=<?php echo $otrocuit;?>&pesitos=<?php echo $pesitos;?>')"><img src="image/C_EditState_md - Copy.png" TITLE="Actualizacion del Componente del Riesgo" width="20" height="20" border="0" /></a>
              <?php }?></td>
            <td align="right"><?php echo $riesgoexplode[3];?></td>
          </tr>
          <tr>
            <td align="left"><?php echo $riesgoexplode[4];if($riesgoexplode[5]<>0){?>&nbsp;&nbsp;<a href="#" onClick="ajax_get('detriesgo','alerta/detalle_riesgo.php','fecha=<?php echo $fecha;?>&componente=<?php echo $riesgoexplode[5];?>&riesgo=<?php echo '3';?>&voy=<?php echo $voy;?>&abro=0&cuit=<?php echo $cuit;?>&otrocuit=<?php echo $otrocuit;?>&pesitos=<?php echo $pesitos;?>')"><img src="image/C_EditState_md - Copy.png" TITLE="Actualizacion del Componente del Riesgo" width="20" height="20" border="0" /></a>
              <?php }?></td>
            <td align="right"><?php echo $riesgoexplode[5];?></td>
          </tr>
          <tr>
            <td align="center" colspan="2" style="background-color:#FFFFCC; font:bold"><b><?php echo $riesgoexplode[6];?></b></td>
          </tr>
        </table>
        <div id="detriesgo">&nbsp;</div></td>
      <!--<td colspan="3" align="center" style="font:bold; color:#0000FF; font-size:16px; background-color:#FFCCFF"   valign="middle" class="td2" scope="col"><b><?php// echo $riesgo;?></b></td>-->
      <?php
  }//fin de control
  else
  {?>
      <td colspan="3" align="center" style="font:bold; color:#0000FF; font-size:16px; background-color:#FFCCFF"   valign="middle" class="td2" scope="col"><b>CUIT NULO - IMPOSIBLE CONTROLAR RIESGO</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="ampliarImagen();ajax_get('contenido','alerta/estadistico.php','voy=0&abro=0&conesta=0&fecha=<?php echo $_REQUEST['fecha'];?>');"><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/>Ocultar</a></td>
      
	  <?php }
  }?>
    </tr>
  </table>
  <?php
 }//fin abro distinto de 1
 ?>
  <div id="xxx">
    <?php if(isset($_REQUEST['abro']) && $_REQUEST['abro']==1)
{
//print_r($_REQUEST);
//echo 'otro'.$otrocuit;
//echo 'cuit'.$cuit;
?>
    <table width="100%"  border="1" align="center">
      <tr>
        <td colspan="4" align="center" style="background-color:#FFCCCC;"><b>ESTADISTICO DE REPORTES DE OPERACIONES SOSPECHOSAS - <?php echo $_REQUEST['fecha'];?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="#" class="smallTahomaRojoNegrita" style="margin-left:5px;" onClick="vercharts('ifchart1','alerta/graf_estadistico.php?fecha=<?php echo $_REQUEST['fecha'];?>');ampliarImagen();"><img src="image/rrhh_statistics.png" title="Ver Grafico Estadistico" width="16" height="16" border="0" /></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.print();"><img src="image/24px-Crystal_Clear_app_printer.png" title="Imprimir Pantalla" width="16" height="16" border="0" align="absbottom" />Imprimir Pantalla</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="ajax_get('xxx','alerta/blanco.php','abro=0')"><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/>Cerrar Estad&iacute;stico</a> </td>
      </tr>
      <tr style="background-color:#CCFFCC">
        <td width="49"    align="center" style="font:bold;"><b>A&Ntilde;O</b></td>
        <td width="138"   align="center" style="font:bold;"><b>OCURRENCIAS</b></td>
        <td width="290"   align="center" style="font:bold;"><b>INCREMENTO EVOLUTIVO</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="smallTahomaRojoNegrita" style="margin-left:5px;"onclick="vercharts('ifchart1','alerta/graf_evolutivo.php?fecha=<?php echo $_REQUEST['fecha'];?>');ampliarImagen();"><img src="image/rrhh_statistics.png" title="Ver Grafico Evolutivo" width="16" height="16" border="0" /></a></td>
        <td width="251"   align="center" style="font:bold;"><b>PORCENTAJE SOBRE TOTAL</b></td>
      </tr>
      <?php  while ($row_rec = $rs_resumen->FetchNextObject($toupper=true)) 
				{ ?>
      <tr onMouseOver="this.style.background='red'" onMouseOut="this.style.background='#996600'">
        <td  align="center" style="font-size:14px;"><?php 
   $cuenta=$cuenta+1;
   //echo $registro;
   		if($cuenta==1)
				{
					$incremento=0;
					$registro=0;
				}
				else
				{
					$incremento=$row_rec->ROS-$registro;
				}
		?>
          <b><?php echo utf8_encode($row_rec->PERIODO);?></b></td>
        <td  align="right" style="font-size:13px;"><b><?php echo $row_rec->ROS;?></b></td>
        <?php if($incremento>=0)
	{?>
        <td  align="right" style="font-size:15px;"><b><?php echo $incremento;?></b></td>
        <?php }
	else
	{?>
        <td  align="right" style="font-size:15px; color:#FF0000"><b><?php echo $incremento;?></b></td>
        <?php }?>
        <td width="220"  align="right"  style="font-size:13px;"><b><?php echo number_format($row_rec->PORCENTAJE,2,'.',',').'%';?></b>
          <?php $registro=$row_rec->ROS;?></td>
      </tr>
      <?php }
  ?>
      <?php if($_SESSION['abrografica']==1)
		{?>
      <tr>
        <td align="center" colspan="4"><a href="#" class="td5" onClick="verchartscierre('ifchart1','alerta/blanco.php')"><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/>Cerrar Grafico</a> </td>
      </tr>
      <?php }?>
      <tr>
        <td align="center" colspan="4"><iframe id="ifchart1" name="ifchart1"  src="" height="0" width="0" frameborder="0" ></iframe></td>
      </tr>
    </table>
    
  </div>
  <?php }//cierro la condicion de exhibicion de div
?>
</td>
</tr>
</table>
</form>

