<?php session_start();
//echo "xget";
//print_r($_GET);
//echo "xpost";
//print_r($_POST);
//print_r($_SESSION['permiso']);
//print $_SESSION['area'];

if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
//$db->debug=true;

//print_r($_POST);
//print_r($_GET);
//print_r($_REQUEST);
$habilitado=0;
$repetido='';
$i=0;
 $caorigen='';
 $myarea='';
 $ccuenta=0;
 $bandera=0;
 $cuenta=0;

//$serfecha=$row_auditor->FECHA;
//combo tipo alerta
try {
 $rs_tipo_alerta = $db ->Execute("SELECT id_tipo_alerta as codigo, 
 							descripcion as descripcion 
 							FROM PLA_AUDITORIA.tipo_alerta
							ORDER BY 2		
					");
			}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
//veo tipos

if (isset($_REQUEST['tipo'])&& $_REQUEST['tipo']<>0 ) 
{
			$tipo =$_REQUEST['tipo'];			
			$condicion_tipo="and b.id_tipo_alerta=$tipo";
}
	else {
			$tipo = 0;
			$condicion_tipo="";
						
		}	

//combo periodos
try {
 $rs_periodo = $db ->Execute("SELECT to_number(to_char(fecha_aparicion,'mmyyyy')) as codigo, 
 										   to_number(to_char(fecha_aparicion,'mmyyyy')) as descripcion 
 							FROM PLA_AUDITORIA.base_alerta
							where to_number(to_char(fecha_aparicion,'mmyyyy'))<>to_number(to_char(sysdate,'mmyyyy'))
							group by to_number(to_char(fecha_aparicion,'mmyyyy'))
							order by 1 desc
									
					");
			}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}

if(isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado']<>0)
{
	$id_estado=$_REQUEST['id_estado'];
	$condicion_estado="and to_number(to_char(b.fecha_aparicion,'mmyyyy'))=$id_estado";
}
else
{
	$id_estado=0;
	$condicion_estado="";
}
//obtengo datos para auditoria
//obtengo fecha y hora
		try {
		$rs_auditor = $db ->Execute("select to_char(sysdate,'hh24:mi:ss') as hora, to_char(sysdate,'dd/mm/yyyy') as fecha from dual");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_auditor =$rs_auditor->FetchNextObject($toupper=true);
	$serhora=$row_auditor->HORA;
	$serfecha=$row_auditor->FECHA;
	//obtengo usuario
	$serusuario='DU'.$_SESSION['usuario'];
 //obtengo el nombre del usuario
 try {
 $rs_uu = $db ->Execute("SELECT us.descripcion as uu FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_uu =$rs_uu->FetchNextObject($toupper=true);
			$auditado=$row_uu->UU; 


//selecciono mi area con rol op_unico
try {
			$rs_myarea = $db ->Execute("SELECT us.area_id_principal as area  FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99 or area_id_principal=4 OR AREA_ID_PRINCIPAL=32)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_myarea =$rs_myarea->FetchNextObject($toupper=true);
			$myarea=$row_myarea->AREA; 
//echo $myarea;


//selecciono los casineros con rol op_unico
try {
			$rs_usuario = $db ->Execute("SELECT count(*) as cuenta FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_usuario =$rs_usuario->FetchNextObject($toupper=true);
			$ccuenta=$row_usuario->CUENTA; 
			


while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	//echo $_SESSION['rol'.$i];
	//Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
	//if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
//si es primera vez que entro en esta sesion
//if($_SESSION['repocasino']==0)
//{
//EJERZO AUDITORIA
	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' consulta Alertas anteriores--->[Resguardo]';
 
 //inserto en tabla auditoria
 ComenzarTransaccion($db);			
			
			try {
				$db->Execute("insert into PLA_AUDITORIA.t_auditoria_externa (
																   fecha,
																   hora,
																   usuario,
																   descripcion																 
																	)
																   
							  values (to_date(?,'DD/MM/YYYY'),?,?,?)",
							  array($serfecha,
							  		$serhora,
									$serusuario,
									$describa));
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
				FinalizarTransaccion($db);
	//echo $serfecha.$describa;
	//$_SESSION['repocasino']=1;
//}
}//fin	de primera vez
		
//echo $habilitado;
//die();
$j=0;
$casino=0;
$casino_setea=0;
$area='';
$area=$_SESSION['area'];
$totfichaje=0;
$totacierto=0;
$totinfichaje=0;
$totinacierto=0;

//echo $docu;
$i=0;
while ($i<$_SESSION['cantidadroles'])  
{

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	//if ((($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||$_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC')|| ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO'))	{$habilitado=1;} 
	//Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
	//if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
//print_r($_GET);
//print_r($_POST);
//$db->debug=true;
//echo $suc_ban.'sucban';
$array_fecha = FechaServer();
//echo $array_fecha;
//die();	

while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;
	//echo $j.".....".$_SESSION['rol'.$j];
	if($j==1)
	{
	//obtengo fecha de consulta
	try {
			$rs_fechita = $db ->Execute("select substr(to_char(sysdate,'dd/mm/yyyy hh:mm:ss'),12,2) as HORA from dual");
			}
		catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}	
		
		$row_fechita =$rs_fechita->FetchNextObject($toupper=true);
		$hora=$row_fechita->HORA;
		//$hora='15';
		
		
		
		if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}
		 	else {if (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}
					 else {	
						 //SI HORA ESTA ENRTRE 0 Y 14 TOMA FECHA DEL DIA
						//DE LO CONTRARIO FECHA POSTERIOR
						//if($hora>0 and $hora<15)
							 //{
									$fecha=str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
							 //}
						 //else
							 //{
									//$fecha=str_pad($array_fecha["mday"]+1,2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
							 //}
						 }//cierro primer else
				 	}//cierro segundo else
		}//cierro j==1
}//cierro while
					
//ejecuto procedimiento	
/*saco momentaneamente				
ComenzarTransaccion($db);			
			
			try {
				$db->Execute("call PLA_AUDITORIA.ACTUALIZAR_ALERTAS_1()");
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
FinalizarTransaccion($db);

*/
//obtengo total de alertas
try {
		$rs_total = $db ->Execute("select count(*) as total
										FROM
											(
												SELECT b.id_base idid,
													b.documento documento,
													b.descripcion nombre,
													b.id_tipo_alerta alert,
													b.id_estado_alerta id_estado,
													b.observaciones as observaciones,
													kk.descripcion as estado,
													ta.descripcion nombre_alerta  	 
													FROM PLA_AUDITORIA.base_alerta b,
														PLA_AUDITORIA.tipo_alerta ta,
														PLA_AUDITORIA.estado_alerta kk
													  WHERE b.id_tipo_alerta=ta.id_tipo_alerta
															and b.id_estado_alerta=kk.id_estado_alerta
															  AND TO_CHAR(B.FECHA_APARICION,'MMYYYY')<>TO_CHAR(SYSDATE,'MMYYYY')
												  and b.id_estado_alerta=3
													  $condicion_tipo
													  $condicion_estado										
													order by 4,5
												)");
			}			
	catch (exception $e){die ($db->ErrorMsg());} 
	$row_total =$rs_total->FetchNextObject($toupper=true);
	$toto=$row_total->TOTAL;
	
			$_pagi_sql ="SELECT b.id_base idid,
							b.documento documento,
							b.descripcion nombre,
							b.id_tipo_alerta alert,
							b.id_estado_alerta id_estado,
							b.observaciones as observaciones,
							kk.descripcion as estado,
							ta.descripcion nombre_alerta,
							to_char(b.fecha_aparicion,'dd/mm/yyyy') as fecha 	 
						FROM PLA_AUDITORIA.base_alerta b,
							PLA_AUDITORIA.tipo_alerta ta,
							PLA_AUDITORIA.estado_alerta kk
						  WHERE b.id_tipo_alerta=ta.id_tipo_alerta
								and b.id_estado_alerta=kk.id_estado_alerta
								  AND TO_CHAR(B.FECHA_APARICION,'MMYYYY')<>TO_CHAR(SYSDATE,'MMYYYY')
					  and b.id_estado_alerta=3
						  $condicion_tipo
						  $condicion_estado										
						order by 4,b.fecha_aparicion";
				

//AND TO_CHAR(B.FECHA_APARICION,'DD/MM/YYYY')>=TO_CHAR(SYSDATE-11,'DD/MM/YYYY')
$_pagi_div = "contenido";
$_pagi_enlace = "alerta/adm_alerta_anterior.php";
$_pagi_cuantos = 10; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
$_pagi_propagar[0]='tipo';
$_pagi_propagar[1]='id_estado';
 
if(basename($_SERVER['PHP_SELF'])=='index.php'){ 
	include("paginator_adodb_oracle.inc.php");
} else {
		include("../paginator_adodb_oracle.inc.php");
		}	
$_SESSION['nro_pagina']=$_pagi_actual;
//}//cierro habilitado	

//lo que sigue viene de aprte.txt
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {
	color: #000000
}
-->
</style>
<form id="novedad" name="novedad" action="#" onsubmit="ajax_post('contenido','alerta/adm_alerta_anterior.php',this); return false;">
  <table width="63%"  align="center">
    <tr>
      <td colspan="13" height="25" align="center" valign="bottom" class="texto4" scope="col">MATRIZ de RIESGO - CONSULTA DE ALERTAS PROCESADAS CON ANTERIORIDAD[Datos  Resguardados]</td>
    </tr>
    <tr valign="bottom" class="td8" >
      <td align="right" colspan="7" valign="middle" class="td2"  scope="col" style="text-align:right; background-color:#FFFFFF">&nbsp;</td>
      <td align="right"  valign="middle" class="td2"  scope="col" style="text-align:right">Fecha:&nbsp;&nbsp;
        <?php  echo $fecha;?></td>
    </tr>
    <tr valign="bottom" class="td8" >
      <td     width="88" align="center" class="td2">Filtros:</td>
      <td width="89" align="right" valign="middle" class="td2"  scope="col">Tipo de Alerta:</td>
      <td width="142"  valign="middle" class="td2" scope="col" align="left"><?php armar_combo_todos($rs_tipo_alerta,'tipo',$tipo);?></td>
           <td width="123" align="right" valign="middle" class="td2"  scope="col">Periodo de Consulta:</td>
      <td width="107"  valign="middle" class="td2" scope="col" align="left"><?php armar_combo_todos($rs_periodo,'id_estado',$id_estado);?></td>
     <!--<td width="50"  align="center" class="td2" scope="col"><a href="#" onClick="ajax_get('contenido','alerta/adm_tipo_alerta.php','fecha=<?php echo $fecha;?>')"><img src="image/alerta.png" TITLE="Administracion de Tipos de Alertas" width="28" height="28" border="0" /></a></td>
       en reemplazo va el siguiente cuando se habilite el de arriba lo saco-->
      
      <td width="1"  align="center" class="td2" scope="col">&nbsp;</td>
      <td width="149" align="center" class="td2" >
      <a href="#" onClick="window.open('list1/datos_alerta_anterior.php?condicion=<?php echo $condicion_tipo;?>&fecha=<?php echo $fecha;?>&id_estado=<?php echo $id_estado;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" TITLE="Ver Reporte" width="25" height="25" border="0" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="#" onClick="ajax_get('historico','alerta/historico_anterior.php','fecha=<?php echo $fecha;?>&id_estado=<?php echo $id_estado;?>')"><img src="image/historico.png" TITLE="Resumen de Alertas Procesadas en el Periodo Consultado" width="25" height="25" border="0" /></a><!--<input name="estadistico" id="estadistico" type="button" style="width:2px;height:5px;" onclick="ajax_get('historico','alerta/estadistico.php','fecha=<?php// echo $fecha;?>')"/>--></td>
      
      <!--<input type="hidden" name="fhasta_consulta" id="fhasta_consulta" value="<?php// echo $fhasta_consulta; ?>" />-->
      <td width="84" class="td2" scope="col" align="right"><input type="submit" style="font-weight:bold" value="Consultar" /></td>
    </tr>
    <tr>
    <td colspan="9" class="td8">
      <div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /><a href="#"  onclick="ajax_get('contenido','alerta/adm_alerta.php','fecha=<?php echo $fecha ?>')">Regresar</a></div></td>
      </tr>
    <?php /* if(isset($_REQUEST['mensaje']))
	{?>
  <tr>
    <td  class="td_detalle" align="center" colspan="8" style="color:#FF0000"><?php echo strtoupper($_REQUEST['mensaje'])?></td>
  </tr>
  <?php }*/?>
  </table>
</form>
<?php  if ($_pagi_result->RowCount()==0) 
{
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">NO EXISTEN ALERTAS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','blanco.php','')\">Cerrar</a></div>");
		
}
?>
<div id="historico">&nbsp;</div>
<table width="90%" border="0" align="center">
<?php if(isset($_REQUEST['mensaje']))
{?>
<tr>
    <td colspan="10" align="center" valign="bottom" style="background-color:#FF33FF; font-size:15px; border-color:#000000; color:#000000" scope="col"><b><?php echo $_REQUEST['mensaje'];?></b></td>
  </tr>
  
  <?php }?>
  <tr>
    <td colspan="10" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
  </tr>
  <tr align="center" class="td2">
    <td width="16%"   class="td4" scope="col">Tipo de Alerta</td>
    <td width="9%"   class="td4" scope="col">Estado</td>
    <td width="14%"   class="td4" scope="col" style="font-style:italic"><b>Items<br /> 
    que Ocasion&oacute; el Alerta</b></td>
    <td width="49%"   class="td4" scope="col">Alerta</td>
    <td width="10%"   class="td4" scope="col">Fecha de Aparici&oacute;n</td>
    <td width="6%"   class="td4" scope="col">Detalles</td>
    
  </tr>
  <?php while ($row = $_pagi_result->FetchNextObject($toupper=true))
	   {?>
  <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
  <?php
   $cuenta=$cuenta+1;
  if($row->NOMBRE_ALERTA<>$repetido)
  {
  ?>
    <td  align="left" style="font-size:11px"><?php echo $row->NOMBRE_ALERTA;$repetido=$row->NOMBRE_ALERTA;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="image/observa1.png" title="" width="20" height="20" border="0"  onmouseover="ajax_showTooltip('alerta/describe_alerta.php?alerta=<?php echo $row->NOMBRE_ALERTA;?>&id_tipo=<?php echo $row->ALERT;?>&rrr=<?php echo rand(0,100);?>',this); return false;" onmouseout="ajax_hideTooltip();"/></td>
  <?php  
  }
  else
  {?>
  
  <td  align="left">&nbsp;</td>
  <?php }
  switch ($row->ID_ESTADO)
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
  <td   align="left" style=" font-weight:bold; background-color:<?php echo $color;?>"><?php echo $row->ESTADO;?><img src="image/estados1.png" style="float:right" title="" width="20" height="20" border="0" onclick="window.open('list1/describe_estados_alerta.php?id_base=<?php echo $row->IDID;?>&fecha=<?php echo $fecha;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')" onmouseover="ajax_showTooltip('alerta/describe_estado.php?id_base=<?php echo $row->IDID;?>&rrr=<?php echo rand(0,100);?>',this); return false;" onmouseout="ajax_hideTooltip();"/></td>
    <td  align="left"><b><?php echo str_pad($row->DOCUMENTO,8,'0',STR_PAD_LEFT);?></b></td>
    <td   align="left" style="font-size:11px"><?php echo $row->NOMBRE;?></td>
   <!-- &descrip=<?php// echo $row->NOMBRE;?>&alerta=<?php// echo $row->NOMBRE_ALERTA;?>-->
     
    <td width="2%"  align="center" ><?php echo $row->FECHA;?></td>
    <td width="6%"  align="center" ><a href="#" onClick="ajax_get('detalle','alerta/detalle_alerta.php','fecha=<?php echo $fecha;?>&documento=<?php echo $row->DOCUMENTO;?>&id_tipo=<?php echo $row->ALERT;?>&alerta=<?php echo $row->NOMBRE_ALERTA;?>&descrip=<?php echo urlencode($row->NOMBRE);?>&id_base=<?php echo $row->IDID;?>&id_estado=<?php echo $row->ID_ESTADO;?>&observaciones=<?php echo $row->OBSERVACIONES;?>&aplicacion=2')"><img src="image/glass.png" TITLE="Detalles y Actualizacion  del Alerta" width="20" height="20" border="0" /></a></td>
    <!--&id_base=<?php// echo $row->IDID;?>&id_estado=<?php// echo $row->ID_ESTADO;?>&observaciones=<?php// echo $row->OBSERVACIONES;?>-->
  </tr>
  <?php
        }

?>
<tr align="center" class="td2">
<td colspan="6">
<div id="detalle">&nbsp;</div>
</td>
</tr>
  <tr>
    <td colspan="2" align="center" valign="bottom" class="td8max" scope="col" style="background-color:#6666FF; font-size:16px;"><b>Total de Alertas======></b></td>
    <td colspan="2" align="center" valign="bottom" class="td8max" scope="col" style="background-color:#6666FF; font-size:16px;"><b><?php echo $toto;?></b></td>
  </tr>
  <tr>
    <td colspan="10" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
  </tr>
  <tr>
    <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina">&nbsp;</div></td>
  </tr>
  <tr>
    <td colspan="9" class="td8">
      <div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /><a href="#"  onclick="ajax_get('contenido','alerta/adm_alerta.php','fecha=<?php echo $fecha ?>')">Regresar</a></div></td>
      </tr>
  
</table>
<?php
}//fin de habilitado?>
<div id="refrescar_pantalla_alerta"></div>