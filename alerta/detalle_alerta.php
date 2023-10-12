<?php /*
* formulario de DETALLE DE ALERTAS
* 16/09/2013
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
//$db->debug=true;
$id_base=$_REQUEST['id_base'];
$aplica=$_REQUEST['aplicacion'];
//$estado=$_REQUEST['id_estado'];
//combo estado alerta
try {
 $rs_estado_alerta = $db ->Execute("SELECT id_estado_alerta as codigo, 
 							descripcion as descripcion 
 							FROM lavado_dinero.estado_alerta
									
					");
			}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
try {
 $rs_observaciones = $db ->Execute("SELECT DECODE(observaciones,NULL,'Sin Observaciones',observaciones) as observaciones,
 									ID_estado_ALERTA AS ID_eSTADO,
									to_char(fecha_aparicion,'dd/mm/yyyy') as fecha_aparece
 							FROM lavado_dinero.base_alerta
							WHERE ID_BASE=?	
					",array($id_base));
			}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
$row_observa =$rs_observaciones->FetchNextObject($toupper=true);
$observaciones=$row_observa->OBSERVACIONES;
$id_estado=$row_observa->ID_ESTADO;
$fecha_aparece=$row_observa->FECHA_APARECE;
//echo $fecha_aparece;
//veo tipos

if (isset($_REQUEST['estado'])&& $_REQUEST['estado']<>0) 
{
			$estado =$_REQUEST['estado'];			
			$condicion_estado="and b.id_estado_alerta=$estado";
}
	else {
			$estado = 0;
			$condicion_estado="";
						
		}	
$toti=0;
$tipo=$_REQUEST['id_tipo'];
$descrip=$_REQUEST['descrip'];
$pos = strpos($descrip, 'meses');
$documento=$_REQUEST['documento'];
//echo $documento;
$tmp_alternativa_condicion = '';
if($tipo==1)
{
$loencontre=substr($descrip,$pos-2,1);
}
else
{
	if($tipo==3)
	{
	$loencontre="9";
	}
	else
	{
	$loencontre="10";
	}
}

		switch ($loencontre)
		{
				case 2:
					$subtipo=2;//viene condicion de acuerdo al subtipo
					$condicion_subtipo=" and o.fecha>=add_months(to_date('".$fecha_aparece."','dd/mm/yyyy'),-12)";
					break;
				case 6:
					$subtipo=1;
					$condicion_subtipo=" and o.fecha>=add_months(to_date('".$fecha_aparece."','dd/mm/yyyy'),-6)";
					break;
				case 8:
					$subtipo=3;
					$condicion_subtipo=" and o.fecha>=add_months(to_date('".$fecha_aparece."','dd/mm/yyyy'),-18)";
					break;
				case 9:
					$condicion_subtipo=" and upper(o.calle || ' ' || o.numero)=UPPER('$documento')";
					//$condicion_subtipo="";
					$tmp_alternativa_condicion = 9;
					break;
				default:
					$subtipo=0;
					$condicion_subtipo='';
		}
		//echo $condicion_subtipo;

$pospremio = strpos($descrip, ')');
$premio=substr($descrip,$pospremio-6,6);
//echo $premio;
if($tipo==7)
{
	switch ($premio)
	{
		case 'CASINO':
			$premiomayor=1;//PREMIOS DE CASINO
			break;
		case 'CRIPTO':
			$premiomayor=2;//PREMIOS PRESCRIPTOS
			break;
		case 'ORANEO':
			$premiomayor=3;//PREMIOS FORANEOS
			break;
		case 'O.PRES':
			$premiomayor=4;//PREMIOS FORANEOS PRESCRIPTOS
			break;
		default:
			$premiomayor=5;//PREMIOS COMUNES EN DEL. Y CASA CENTRAL
	}
}
//echo $premiomayor;
switch ($tipo)
{
	case 0:
		break;
	case 1:
	case 2:
	case 4:
	case 6:
	case 8:
	//obtengo el documento
	if($tipo==6)
		{
			$guion=strpos($_REQUEST['documento'],'-');
			$docucontrol=substr($_REQUEST['documento'],0,$guion-1);
		}
		else
		{
			$docucontrol=substr($_REQUEST['documento'],0,8);
		}
		//echo $docucontrol;
	//OBTENGO EL SR.
			try {
			$rs_agente = $db -> Execute("SELECT DISTINCT APELLIDO || ', ' || NOMBRE || ' Doc. Nro. ' || documento as senior
											FROM LAVADO_DINERO.T_GANADOR
											WHERE substr(DOCUMENTO,0,8)=?",array($docucontrol));
				}
				catch (exception $e){die($db->ErrorMsg());}
				$row_agente = $rs_agente->FetchNextObject($toupper=true);
				$senior=$row_agente->SENIOR;
				
		//obtengo EL DETALLE
			try {
			$rs_resumen = $db -> Execute("select to_char(o.fecha,'dd/mm/yyyy') as fecha,
										jj.juegos as juego,
										o.valor_premio as monto,
										decode(o.fecha_baja,NULL,ss.nombre,ss.nombre||'(Registro anulado)') as sucursal
								  from lavado_dinero.t_ganador o,
								  juegos.sucursal ss,
								  juegos.juegos jj
								  where o.suc_ban=ss.suc_ban
								  and o.juego=jj.id_juegos
								  and o.documento=?
								  $condicion_subtipo
								  order by o.fecha desc",array($docucontrol));
				}
				catch (exception $e){die($db->ErrorMsg());}
				/*die('PROCES0');
				  while ($row_rec = $rs_recorrido->FetchNextObject($toupper=true)) 
							{ $nro=$row_rec->NRO;}*/
		$rs_resumen->MoveFirst();
		//die('entra');
		if($senior<>'')
		{
		?>

<table width="831"  border="1" align="center">
  <tr>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>DETALLE DEL ALERTA <?php echo $_REQUEST['alerta'];?> PERTENECIENTE AL SR. <?php echo $senior;?><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/> <a href="#" class="td5" onclick="ajax_get('detalle','alerta/blanco.php','')">Cerrar</a></b></td>
  </tr>
  <tr>
    <?php 
$titulin=strtoupper('DETALLE DEL ALERTA '.$_REQUEST['alerta'].' PERTENECIENTE AL SR. '.$senior);
$titulinnodebe=$_REQUEST['descrip'].' al Documento Nro. '.$_REQUEST['documento'];

?>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>PREMIOS OBTENIDOS</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.open('list1/datos_detalle_alerta.php?id_base=<?php echo $id_base;?>&condicion_subtipo=<?php 
  
	if(!empty($tmp_alternativa_condicion) && $tmp_alternativa_condicion == 9){
		$condicion_subtipo="";
	}
	echo urlencode($condicion_subtipo);
  
  ?>&tipo=<?php echo $tipo;?>&titulin=<?php echo $titulin;?>&titulinnodebe=<?php echo $titulinnodebe;?>&documento=<?php echo $_REQUEST['documento'];?>&observaciones=<?php echo $observaciones;?>&tmp_alternativa_condicion=<?php echo $tmp_alternativa_condicion;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" TITLE="Reporte del detalle" width="22" height="22" border="0" /></a></td>
  </tr>
  <tr style="background-color:#CCFFCC; font-size:12px" >
    <td width="110" height="24"  align="center">FECHA</td>
    <td width="255"  align="center">JUEGO</td>
    <td width="138"  align="center">MONTO</td>
    <td width="300"  align="center">SUCURSAL</td>
  </tr>
  <?php  while ($row_rec = $rs_resumen->FetchNextObject($toupper=true)) 
				{ ?>
  <tr onmouseover="this.style.background='red'" onmouseout="this.style.background='#996600'">
    <td  align="center" style="font-size:11px;"><?php echo $row_rec->FECHA;?></td>
    <td  align="left" style="font-size:11px;"><?php echo utf8_encode($row_rec->JUEGO);?></td>
    <td  align="right"  style="font-size:13px;"><?php echo number_format($row_rec->MONTO,2,'.',',');?></td>
    <td  align="left" style="font-size:13px;"><?php echo utf8_encode($row_rec->SUCURSAL);?></td>
  </tr>
  <?php 
				 $toti=$toti+$row_rec->MONTO;
				 }
				 ?>
  <tr style="background-color:#CCFFCC">
    <td  align="center"  style="font-size:13px;" colspan="4"><b><?php echo 'MONTO TOTAL===>        $'.number_format($toti,2,'.',',');?></b></td>
  </tr>
</table>

<?php 
}//fin de $senior<>''
else
{?>

<table width="831"  border="1" align="center">
  <tr>
    <?php $titulin=strtoupper('DETALLE DEL ALERTA '.$_REQUEST['alerta']);
				$titulinnodebe='SEÑAL DE ALERTA DESCARTADA';?>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b><?php echo $titulin;?></b><a href="#" class="td5" onclick="ajax_get('detalle','alerta/blanco.php','')"><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/>Cerrar</a></td>
  </tr>
  <tr>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:18px; color:#FF0000"><b><?php echo utf8_encode($titulinnodebe);?></b></td>
  </tr>
</table>
<?php 
 }//fin de control de senior
break;
  	case 3:
	//$a="a";
	//OBTENGO EL SR.
			try {
			$rs_agente = $db -> Execute("SELECT APELLIDO || ', ' || NOMBRE || ' Domiciliado en ' || upper(calle || ' ' || numero) as senior
											FROM LAVADO_DINERO.T_GANADOR
											WHERE upper(calle || ' ' || numero)=?",array(strtoupper($_REQUEST['documento'])));
				}
				catch (exception $e){die($db->ErrorMsg());}
				$row_agente = $rs_agente->FetchNextObject($toupper=true);
				$senior=$row_agente->SENIOR;
	//obtengo EL DETALLE
			try {
			$rs_resumen = $db -> Execute("select to_char(o.fecha,'dd/mm/yyyy') as fecha,
										jj.juegos as juego,
										o.valor_premio as monto,
										decode(o.fecha_baja,NULL,ss.nombre,ss.nombre||'(Registro anulado)') as sucursal
								  from lavado_dinero.t_ganador o,
								  juegos.sucursal ss,
								  juegos.juegos jj
								  where o.suc_ban=ss.suc_ban
								  and o.juego=jj.id_juegos
								  $condicion_subtipo
								  order by o.fecha desc");
				}
				catch (exception $e){die($db->ErrorMsg());}
	$rs_resumen->MoveFirst();
	?>
<table width="831"  border="1" align="center">
  <tr>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>DETALLE DEL ALERTA <?php echo $_REQUEST['alerta'];?> PERTENECIENTE AL SR. <?php echo $senior;?><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/> <a href="#" class="td5" onclick="ajax_get('detalle','alerta/blanco.php','')">Cerrar</a></b></td>
  </tr>
  <tr>
    <?php 
$titulin=urlencode(strtoupper('DETALLE DEL ALERTA '.$_REQUEST['alerta'].' PERTENECIENTE AL SR. '.$senior));
$titulinnodebe=urlencode($_REQUEST['descrip'].' al Documento Nro. '.$_REQUEST['documento']);
//echo $titulin.$titulinnodebe;

?>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>PREMIOS OBTENIDOS</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.open('list1/datos_detalle_alerta.php?id_base=<?php echo $id_base;?>&condicion_subtipo=<?php 
	if(!empty($tmp_alternativa_condicion) && $tmp_alternativa_condicion == 9){
		$condicion_subtipo="";
	}
	echo $condicion_subtipo; ?>&tmp_alternativa_condicion=<?php echo $tmp_alternativa_condicion;?>&tipo=<?php echo $tipo;?>&titulin=<?php echo $titulin;?>&titulinnodebe=<?php echo $titulinnodebe;?>&documento=<?php echo $_REQUEST['documento'];?>&observaciones=<?php echo $observaciones;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" TITLE="Reporte del detalle" width="22" height="22" border="0" /></a></td>
    <!--<td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>PREMIOS OBTENIDOS</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.open('list1/datos_detalle_alerta.php?id_base=<?php// echo $id_base;?>&condicion_subtipo=<?php// echo $condicion_subtipo;?>&tipo=<?php// echo $tipo;?>&titulin=<?php// echo $titulin;?>&documento=<?php// echo $_REQUEST['documento'];?>&observaciones=<?php// echo $observaciones;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" TITLE="Reporte del detalle" width="22" height="22" border="0" /></a></td>-->
  </tr>
  <tr style="background-color:#CCFFCC; font-size:12px" >
    <td width="110" height="24"  align="center">FECHA</td>
    <td width="255"  align="center">JUEGO</td>
    <td width="138"  align="center">MONTO</td>
    <td width="300"  align="center">SUCURSAL</td>
  </tr>
  <?php  while ($row_rec = $rs_resumen->FetchNextObject($toupper=true)) 
				{ ?>
  <tr onmouseover="this.style.background='red'" onmouseout="this.style.background='#996600'">
    <td  align="center" style="font-size:11px;"><?php echo $row_rec->FECHA;?></td>
    <td  align="left" style="font-size:11px;"><?php echo utf8_encode($row_rec->JUEGO);?></td>
    <td  align="right"  style="font-size:13px;"><?php echo number_format($row_rec->MONTO,2,'.',',');?></td>
    <td  align="left" style="font-size:13px;"><?php echo utf8_encode($row_rec->SUCURSAL);?></td>
  </tr>
  <?php 
				 $toti=$toti+$row_rec->MONTO;
				 }
				 ?>
  <tr style="background-color:#CCFFCC">
    <td  align="center"  style="font-size:13px;" colspan="4"><b><?php echo 'MONTO TOTAL===>        $'.number_format($toti,2,'.',',');?></b></td>
  </tr>
</table>
<?php 
	
	
	break;
	case 5:
	?>
<table width="831"  border="1" align="center">
  <tr>
    <?php $titulin=strtoupper('DETALLE DEL ALERTA '.$_REQUEST['alerta'].' PERTENECIENTE AL SR. '.$senior);
				$titulinnodebe=$_REQUEST['descrip'].' al Documento Nro. '.$_REQUEST['documento'];?>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>DETALLE DEL ALERTA <b><?php echo $_REQUEST['alerta'];?></b><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/> <a href="#" class="td5" onclick="ajax_get('detalle','alerta/blanco.php','')">Cerrar</a></b>&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.open('list1/datos_detalle_alerta.php?id_base=<?php echo $id_base;?>&condicion_subtipo=<?php 
			if(!empty($tmp_alternativa_condicion) && $tmp_alternativa_condicion == 9){
				$condicion_subtipo="";
			}
			echo $condicion_subtipo; ?>&tmp_alternativa_condicion=<?php echo $tmp_alternativa_condicion;?>&tipo=<?php echo $tipo;?>&titulin=<?php echo $titulin;?>&titulinnodebe=<?php echo $titulinnodebe;?>&documento=<?php echo $_REQUEST['documento'];?>&observaciones=<?php echo $observaciones;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" TITLE="Reporte del detalle" width="22" height="22" border="0" /></a></td>
  </tr>
  <tr>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:10px"><b><?php echo $_REQUEST['descrip'].' al Documento Nro. '.$_REQUEST['documento'];?></b></td>
  </tr>
</table>
<?php 
	break;
case 7:
	/*OBTENGO EL SR.
			try {
			$rs_agente = $db -> Execute("SELECT APELLIDO || ', ' || NOMBRE || ' Doc. Nro. ' || documento as senior
											FROM LAVADO_DINERO.T_GANADOR
											WHERE substr(DOCUMENTO,0,8)=?",array(substr($_REQUEST['documento'],0,8)));
				}
				catch (exception $e){die($db->ErrorMsg());}
				$row_agente = $rs_agente->FetchNextObject($toupper=true);
				$senior=$row_agente->SENIOR;*/
				
		//obtengo EL DETALLE
		switch ($premiomayor)
			{
				case 1://casino
					/*$_SESSION['mayor']="select to_char(o.fecha,'dd/mm/yyyy') as fecha,
													'CASINO' as juego,
													o.IMPORTE_FICHA as monto,
													O.CASA as sucursal
											  from CASINO.T_REG_CP o
											  where o.cod_mov_caja=?
											  and o.importe_ficha>50000
											  order by o.fecha desc";*/
						try {
						$rs_resumen = $db -> Execute("select to_char(o.fecha,'dd/mm/yyyy') as fecha,
													'CASINO' as juego,
													o.IMPORTE_FICHA as monto,
													O.CASA as sucursal
											  from CASINO.T_REG_CP o
											  where o.cod_mov_caja=?
											  and o.importe_ficha>50000
											  order by o.fecha desc",array(substr($_REQUEST['documento'],0,8)));
							}
							catch (exception $e){die($db->ErrorMsg());}
							
						$rs_resumen->MoveFirst();
				break;
					case 2://PREMIOS PRESCRIPTOS
					/*$_SESSION['mayor']="select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.MONTO_TOTAL as monto,
													su.descripcion as sucursal
											 from kanban.t_premios_prescriptos p,
                      kanban.t_remito_premio_detalle_pres rpd,
                      kanban.t_remito_premio_cabecera_pres ca,
                      gestion.T_sucursal su,
                      gestion.T_agencia ag,
                      KANBAN.T_JUEGO JJ
                      where p.id_juego=6
                      and p.suc_ban=su.id_sucursal
                      AND P.NRO_AGEN=AG.ID_AGENCIA
                      and rpd.id_remito_cabecera=ca.id_remito_cabecera
                      and p.id_juego=ca.id_juego
                      AND P.ID_JUEGO=JJ.ID_JUEGO
                      AND p.monto_total>50000 
                      and p.pagado='S'
					  AND P.OCR=?
  					UNION
  						select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.MONTO_TOTAL as monto,
													su1.NOMBRE as sucursal
												  from kanban.t_premios_prescriptos p,
												kanban.t_remito_premio_detalle_pres rpd,
												kanban.t_remito_premio_cabecera_pres ca,
												juegos.sucursal su1,
												juegos.agencia ag1,
												KANBAN.T_JUEGO JJ
												where p.id_juego<>6
												and p.suc_ban=su1.SUC_BAN
												AND P.NRO_aGEN=AG1.NRO_AGEN
												AND P.ID_JUEGO=JJ.ID_JUEGO
												AND p.monto_total>50000
												AND P.FECHA_PAGA>=SYSDATE-100
												and p.pagado='S'
												  AND P.OCR=?";*/
					try {
						$rs_resumen = $db -> Execute("select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.MONTO_TOTAL as monto,
													su.descripcion as sucursal
											 from kanban.t_premios_prescriptos p,
                      kanban.t_remito_premio_detalle_pres rpd,
                      kanban.t_remito_premio_cabecera_pres ca,
                      gestion.T_sucursal su,
                      gestion.T_agencia ag,
                      KANBAN.T_JUEGO JJ
                      where p.id_juego=6
                      and p.suc_ban=su.id_sucursal
                      AND P.NRO_AGEN=AG.ID_AGENCIA
                      and rpd.id_remito_cabecera=ca.id_remito_cabecera
                      and p.id_juego=ca.id_juego
                      AND P.ID_JUEGO=JJ.ID_JUEGO
                      AND p.monto_total>50000 
                      and p.pagado='S'
					  AND P.OCR=?
  					UNION
  						select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.MONTO_TOTAL as monto,
													su1.NOMBRE as sucursal
												  from kanban.t_premios_prescriptos p,
												kanban.t_remito_premio_detalle_pres rpd,
												kanban.t_remito_premio_cabecera_pres ca,
												juegos.sucursal su1,
												juegos.agencia ag1,
												KANBAN.T_JUEGO JJ
												where p.id_juego<>6
												and p.suc_ban=su1.SUC_BAN
												AND P.NRO_aGEN=AG1.NRO_AGEN
												AND P.ID_JUEGO=JJ.ID_JUEGO
												AND p.monto_total>50000
												AND P.FECHA_PAGA>=SYSDATE-100
												and p.pagado='S'
												  AND P.OCR=?",array(substr($_REQUEST['documento'],0,8), substr($_REQUEST['documento'],0,8)));
							}
							catch (exception $e){die($db->ErrorMsg());}
							
						$rs_resumen->MoveFirst();
				break;
					case 3://PREMIOS FORANEOS
					/*$_SESSION['mayor']="select distinct to_char(P.FECHA_PAGADO,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.MONTO_TOTAL as monto,
													su1.NOMBRE as sucursal
											 from kanban.t_premio_FORANEOS p,
													kanban.t_remito_premio_detalle_FOR rpd,
													kanban.t_remito_premio_cabecera_FOR ca,
													KANBAN.T_JUEGO JJ,
													juegos.sucursal su1
													where  p.suc_ban=su1.SUC_BAN
													and rpd.id_remito_cabecera=ca.id_remito_cabecera
													and p.id_juego=ca.id_juego
													AND JJ.ID_JUEGO=P.ID_JUEGO
													AND p.monto_total>50000
													And p.pagado='S'
													AND P.OCR=?";*/
					try {
						$rs_resumen = $db -> Execute("select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.importe as monto,
													su1.NOMBRE as sucursal
											 from kanban.t_premios p,
													kanban.t_remito_premio_detalle rpd,
													kanban.t_remito_premio_cabecera ca,
													KANBAN.T_JUEGO JJ,
													juegos.sucursal su1
													where  p.suc_ban=su1.SUC_BAN
													and rpd.id_remito_cabecera=ca.id_remito_cabecera
													and p.id_juego=ca.id_juego
													AND JJ.ID_JUEGO=P.ID_JUEGO
													AND p.importe>50000
													And p.pagado='S'
													AND (P.OCR=? or p.ocr=?)",array(substr($_REQUEST['documento'],0,8), substr($_REQUEST['documento'],0,9)));
							}
							catch (exception $e){die($db->ErrorMsg());}
							
				break;
				case 4://PREMIOS PRESCRIPTOS FORANEOS
					/*$_SESSION['mayor']="select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.MONTO_TOTAL as monto,
													su1.NOMBRE as sucursal
											 from kanban.t_premios_prescriptos_FOR p,
												kanban.t_remito_premio_detalle_pres rpd,
												kanban.t_remito_premio_cabecera_pres ca,
												juegos.sucursal su1,
												juegos.agencia ag1,
												KANBAN.T_JUEGO JJ
												where  p.suc_ban=su1.SUC_BAN
												AND P.NRO_aGEN=AG1.NRO_AGEN
												AND P.ID_JUEGO=JJ.ID_JUEGO
												AND p.monto_total>50000
												and p.pagado='S'
												AND P.OCR=?";*/
					try {
						$rs_resumen = $db -> Execute("select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.MONTO_TOTAL as monto,
													su1.NOMBRE as sucursal
											 from kanban.t_premios_prescriptos_FOR p,
												kanban.t_remito_premio_detalle_pres rpd,
												kanban.t_remito_premio_cabecera_pres ca,
												juegos.sucursal su1,
												juegos.agencia ag1,
												KANBAN.T_JUEGO JJ
												where  p.suc_ban=su1.SUC_BAN
												AND P.NRO_aGEN=AG1.NRO_AGEN
												AND P.ID_JUEGO=JJ.ID_JUEGO
												AND p.monto_total>50000
												and p.pagado='S'
												AND P.OCR=?",array(substr($_REQUEST['documento'],0,8)));
												}
												catch (exception $e){die($db->ErrorMsg());}
												
				break;
				default://premio comun
				/*$_SESSION['mayor']="select distinct to_char(ca.fecha,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.importe as monto,
													su.descripcion as sucursal
											 from kanban.t_premios p,
												kanban.t_remito_premio_detalle rpd,
												kanban.t_remito_premio_cabecera ca,
												gestion.T_sucursal su,
												gestion.T_agencia ag,
												kanban.t_juego jj
												where rpd.billete=p.billete
													and rpd.fraccion=p.fraccion
													and ca.suc_ban=su.id_sucursal
													and rpd.id_remito_cabecera=ca.id_remito_cabecera
													and p.id_juego=ca.id_juego
													and jj.id_juego=p.id_juego
													AND ca.id_remito_cabecera=?
													and p.importe>50000
													and p.pagado='S'";*/
					try {
						$rs_resumen = $db -> Execute("select distinct to_char(ca.fecha,'dd/mm/yyyy') as fecha,
                          						p.id_juego,
													'QUINIELA' as juego,
													p.monto_total as monto,
													su.descripcion as sucursal
											 from kanban.t_premios_sin_reparto p,
												kanban.t_remito_premio_detalle rpd,
												kanban.t_remito_premio_cabecera ca,
												gestion.T_sucursal su
												where  ca.suc_ban=su.id_sucursal
													and rpd.id_remito_cabecera=ca.id_remito_cabecera
													AND ca.id_remito_cabecera=?
                          ANd p.monto_total>50000
													and p.pagado='S'
                          and ca.confirmado='S'
                          AND ca.anulado is null
                          and ca.sorteo=p.concurso
						  and su.id_sucursal=p.suc_ban
      			UNION                          
                		  select distinct to_char(ca.fecha,'dd/mm/yyyy') as fecha,
                          p.id_juego,
													jj.descripcion as juego,
													p.importe as monto,
													su.descripcion as sucursal
											 from kanban.t_premios p,
												kanban.t_remito_premio_detalle rpd,
												kanban.t_remito_premio_cabecera ca,
												gestion.T_sucursal su,
												gestion.T_agencia ag,
												kanban.t_juego jj
												where rpd.billete=p.billete
													and rpd.fraccion=p.fraccion
													and ca.suc_ban=su.id_sucursal
													and rpd.id_remito_cabecera=ca.id_remito_cabecera
													and p.id_juego=ca.id_juego
													and jj.id_juego=p.id_juego
													AND ca.id_remito_cabecera=?
                          							ANd p.importe>50000
													and p.pagado='S'",array(substr($_REQUEST['documento'],0,8),substr($_REQUEST['documento'],0,8)));
							}
							catch (exception $e){die($db->ErrorMsg());}
							
						$rs_resumen->MoveFirst();
			}
		?>
<table width="831"  border="1" align="center">
  <tr>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>DETALLE DEL ALERTA <?php echo $_REQUEST['alerta'];?> TICKET/REMITO NRO.<?PHP echo $_REQUEST['documento']?><img src="image/undo.png" title="Cerrar" width="16" height="16" border="0" align="absbottom"/> <a href="#" class="td5" onclick="ajax_get('detalle','alerta/blanco.php','')">Cerrar</a></b></td>
  </tr>
  <tr>
    <?php 
$titulin=strtoupper('DETALLE DEL ALERTA '.$_REQUEST['alerta']);
$titulinnodebe=$_REQUEST['descrip'].' al Documento Nro. '.$_REQUEST['documento'];

?>
    <td colspan="4" align="center" style="background-color:#FFCCCC; font:Arial, Helvetica, sans-serif; font-size:12px"><b>PREMIOS OBTENIDOS</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.open('list1/datos_detalle_alerta.php?id_base=<?php echo $id_base;?>&condicion_subtipo=<?php 
  
	if(!empty($tmp_alternativa_condicion) && $tmp_alternativa_condicion == 9){
		$condicion_subtipo="";
	}
	echo $condicion_subtipo;
  
  ?>&tipo=<?php echo $tipo;?>&titulin=<?php echo $titulin;?>&titulinnodebe=<?php echo urlencode($titulinnodebe);?>&documento=<?php echo $_REQUEST['documento'];?>&observaciones=<?php echo $observaciones;?>&tmp_alternativa_condicion=<?php echo $tmp_alternativa_condicion;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" TITLE="Reporte del detalle" width="22" height="22" border="0" /></a></td>
  </tr>
  <tr style="background-color:#CCFFCC; font-size:12px" >
    <td width="110" height="24"  align="center">FECHA</td>
    <td width="255"  align="center">JUEGO</td>
    <td width="138"  align="center">MONTO</td>
    <td width="300"  align="center">SUCURSAL</td>
  </tr>
  <?php  while ($row_rec = $rs_resumen->FetchNextObject($toupper=true)) 
				{ ?>
  <tr onmouseover="this.style.background='red'" onmouseout="this.style.background='#996600'">
    <td  align="center" style="font-size:11px;"><?php echo $row_rec->FECHA;?></td>
    <td  align="left" style="font-size:11px;"><?php echo utf8_encode($row_rec->JUEGO);?></td>
    <td  align="right"  style="font-size:13px;"><?php echo number_format($row_rec->MONTO,2,'.',',');?></td>
    <td  align="left" style="font-size:13px;"><?php echo utf8_encode($row_rec->SUCURSAL);?></td>
  </tr>
  <?php 
				 $toti=$toti+$row_rec->MONTO;
				 }
				 ?>
  <tr style="background-color:#CCFFCC">
    <td  align="center"  style="font-size:13px;" colspan="4"><b><?php echo 'MONTO TOTAL===>        $'.number_format($toti,2,'.',',');?></b></td>
  </tr>
</table>
<?php 
break;
	default:
		$a='a';
}

?>
<form id="detalle_alerta" name="novedad" action="#" onsubmit="ajax_post('contenido','alerta/procesar_modificar_alerta.php',this); return false;">
  <table width="732"  border="1" align="center">
    <tr>
      <td width="219" height="24" style="background-color:#66FFCC; font-size:12px"  align="left"><b>Observacion del Alerta:</b></td>
      <td width="497" class="td_detalle"><textarea name="observaciones" class="small"  id="observaciones"  rows="2" cols="80"/><?php echo $observaciones;?></textarea></td>
    </tr>
    <tr>
      <td width="219" height="24"  align="left" style="background-color:#66FFCC; font-size:12px"><b>Estado del Alerta:</b></td>
      <td width="497" class="td_detalle"><?php armar_combo($rs_estado_alerta,'id_estado',$id_estado);?></td>
      <input name="aplicacion" type="hidden" value="<?php echo $aplica;?>" />
      <input name="id_base" type="hidden" value="<?php echo $id_base;?>" />
      <input name="descrip" type="hidden" value="<?php echo $descrip;?>" />
      <!--<input name="id_estado" type="hidden" value="<?php// echo $descrip;?>" />-->
    </tr>
    <tr>
      <td  class="td_detalle" align="center" colspan="2"><input name="Guardar" style="font-weight:bold" type="submit" value="Guardar" /></td>
    </tr>
  </table>
</form>
