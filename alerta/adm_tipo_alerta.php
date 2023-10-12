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
$i=0;
 $caorigen='';
 $myarea='';
 $ccuenta=0;
 $bandera=0;


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

//OJO VOY A HARDCODEAR LO QUE VIENE
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
				if($_SESSION['repocasino']==1)
				{
				//EJERZO AUDITORIA
					 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' consulta Tipos de Alertas--->[Resguardo]';
				 
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
					$_SESSION['repocasino']=0;
				}	//fin de repocasino=1	
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

//print_r($_GET);
//print_r($_POST);
//$db->debug=true;
//echo $suc_ban.'sucban';
$array_fecha = FechaServer();
//echo $array_fecha;
//die();	


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
						 }
				 }
				 //}//de prueba sacarlo
				
				 //die('entre');
				 
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php 
//if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OP_UNICO'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OPERADOR') {
//$db->debug=true;
//echo('ENTRA');
//obtengo total de cedulas
try {
		$rs_total = $db ->Execute("select count(*) as total
									from PLA_AUDITORIA.tipo_alerta b
									");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_total =$rs_total->FetchNextObject($toupper=true);
	$toto=$row_total->TOTAL;
	
try {
			$rs_principal = $db ->Execute("SELECT b.id_tipo_alerta idid,
				B.descripcion as descripcion,
				b.funcion as funcion					
				FROM PLA_AUDITORIA.tipo_alerta b							
				order by 2");}
				catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}	


/*$_pagi_div = "contenido";
$_pagi_enlace = "denegado/adm_denegado.php";
$_pagi_cuantos = 10; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
//$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='fecha';
//$_pagi_propagar[1]='fhasta';
//$_pagi_propagar[1]='casino';
//$_pagi_propagar[3]='apostador';

 
if(basename($_SERVER['PHP_SELF'])=='index.php'){ 
	include("paginator_adodb_oracle.inc.php");
} else {
		include("../paginator_adodb_oracle.inc.php");
	
$_SESSION['nro_pagina']=$_pagi_actual;*/	?>
<style type="text/css">
<!--
.Estilo1 {
	color: #000000
}
-->
</style>
<form id="novedad" name="novedad" action="#" onsubmit="ajax_post('contenido','alerta/adm_tipo_alerta.php',this); return false;">
  <table width="69%" height="130"  align="center"  background="image/alerta.png"  style="background-repeat:no-repeat; background-position:left">
    <tr>
      <td colspan="13" height="100" align="center" valign="bottom" class="texto4" scope="col">MATRIZ DE RIESGO - ADMINISTRACION DE TIPO DE ALERTAS[Datos  Resguardados]</td>
    </tr>
    <!--<tr>
	<td colspan="8" align="center" valign="bottom" class="td8max" scope="col"><?php// if(substr(strtolower($soydeaca),0,6)=='casino'){ echo $soydeaca;} else {armar_combo($rs_casino,"casino",$casino);}?></a></td>
</tr>-->
    <tr valign="bottom" class="td8" >
      <td width="144" align="right"  valign="middle" class="td2" style="text-align:right"  scope="col">Fecha:&nbsp;&nbsp;<?php  echo $fecha;?></td>
      <td width="436" align="center" class="td2" ><!--<a href="#" onClick="ajax_get('contenido','alerta/agregar_alerta.php','fecha=<?php// echo $fecha;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_action_db_add.png" title="Agregar Tipo de Alerta" width="20" height="20" border="0"/></a>-->
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.open('list1/datos_tipo_alerta.php?fecha=<?php echo $fecha;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" TITLE="Ver Reporte" width="22" height="22" border="0" /></a></td>
    </tr>
  </table>
</form>
<table width="97%" border="0" align="center">
  <!--<tr>
              <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php//echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    
   
    </tr>-->
    <tr align="center">
    <td align="center" scope="row" colspan="4"><div align="right"><a href="#" class="small" onclick="ajax_get('contenido','alerta/adm_alerta.php','')"><img src="image/24px-Crystal_Clear_action_reload.png" title="Retorno a Alertas diarias" width="16" height="16" border="0" align="absbottom" />Regresar</a></div></td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina">&nbsp;</div></td>
  </tr>
  <tr align="center" class="td2">
    <td width="17%"   class="td4" scope="col">Descripcion</td>
    <td width="75%"   class="td4" scope="col">Funcion</td>
    <!--<td width="5%"   class="td4" scope="col">Modificar</td>
    <td width="3%"   class="td4" scope="col">Eliminar</td>-->
  </tr>
  <!--</table>-->
  <?php 
 // }//de prueba sacarlo				
//die('entre');
  while ($row = $rs_principal->FetchNextObject($toupper=true))
	   {?>
  <tr class="<?php	if($rs_principal->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
    <td  align="left"><?php echo $row->DESCRIPCION;?></td>
    <td  align="left"><textarea name="observamov" class="small"  id="observamov"  style="height:40px;" cols="180" readonly="readonly"/><?php echo $row->FUNCION;?></textarea></td>
    <!--<td width="5%" align="center"><a href="#" onclick="ajax_get('contenido','alerta/modificar_alerta.php','idid=<?php echo $row->IDID;?>&fecha=<?php echo $fecha;?>');return false;"><img src="image/modificar.png" title="Modifica este Tipo de Alerta?" width="20" height="20" border="0"/></a></td>
    <td width="3%" align="center"><a href="#" onclick="ajax_get('elimina','alerta/controla_eliminacion_alerta.php','idid=<?php echo $row->IDID;?>&fecha=<?php echo $fecha;?>');return false;"><img src="image/roseta_ok.png" title="Elimina este Tipo de Alerta?" width="20" height="20" border="0"/></a></td>-->
  </tr>
  <?php 
    }//FIN DE WHILE
    ?>
  <tr>
    <td colspan="4" align="center" valign="bottom" class="td8max" scope="col" style="color:#FF0000; font-size:24px">Total de Tipos de Alertas======><?php echo $toto;?></td>
    
  </tr>
  
  <tr align="center">
    <td align="center" scope="row" colspan="4"><div align="right"><a href="#" class="small" onclick="ajax_get('contenido','alerta/adm_alerta.php','')"><img src="image/24px-Crystal_Clear_action_reload.png" title="Retorno a Alertas diarias" width="16" height="16" border="0" align="absbottom" />Regresar</a></div></td>
  </tr>
</table>
<?php

}//fin de habilitado?>
