<?php 
/*
	@Autor:
	@Razon:
	@Modificaciones:
		1:
		@@Autor: Broda Favio Noel
		@@Fecha: 19/04/2012
		@@Descripción:
			1. Se añadió el boton para ver el listado en PDF.
			2. Se añadió el texto "ANULADO" en caso que así sea.
			3. Mejorada la indentación.
*/
session_start();
include_once("db_conecta_adodb.inc.php");
include_once("funcion.inc.php");
include_once("jscalendar-1.0/calendario.php");
$array_fecha = FechaServer();
$variables=array();
//$db->debug=true;
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
	
//selecciono los casineros 
try {
			$rs_usuario = $db ->Execute("SELECT count(*) as cuenta FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_usuario =$rs_usuario->FetchNextObject($toupper=true);
			$ccuenta=$row_usuario->CUENTA; 
			
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

//si es primera vez que entro en esta sesion
if($_SESSION['ticket']==0)
{
//EJERZO AUDITORIA
	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' consulta Movimientos de Tickeadoras de Casinos';
 
 //inserto en tabla auditoria
 ComenzarTransaccion($db);			
			
			try {
				$db->Execute("insert into lavado_dinero.t_auditoria_externa (
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
	$_SESSION['ticket']=1;
}
	
//$db->debug=true;

$i = 0;
while ($i<$_SESSION['cantidadroles']){
	$i++;
	
	if(($_SESSION['rol'.$i]=='LAVADO_DINERO_CONF_DELE' and $ccuenta<>0) ||($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CONFORMA') || ($_SESSION['rol'.$i] == 'ROL_LAVADO_DINERO_ADMINISTRA') || ($_SESSION['rol'.$i] == 'ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i] == 'ROL_LAVADO_DINERO_CONSULTA')){
		$habilitado = 1;
	} 
}

if(($_SESSION['suc_ban']==20)||($_SESSION['suc_ban']==21)||($_SESSION['suc_ban'] == 22) || ($_SESSION['suc_ban'] == 23) || ($_SESSION['suc_ban'] == 24)||($_SESSION['suc_ban'] == 25) || ($_SESSION['suc_ban']==26)||($_SESSION['suc_ban']==27)||($_SESSION['suc_ban']==30)||($_SESSION['suc_ban']==31)||($_SESSION['suc_ban']==32)||($_SESSION['suc_ban']==33)){
	$habilitado = 0;
}

if ($habilitado==0){
	die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 

if(isset($_GET['fecha'])) {
	$fecha = $_GET['fecha'];
} else {
	if (isset($_POST['fecha'])) {
		$fecha = $_POST['fecha'];
	} else {
		$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	}
}

if (isset($_GET['mayora'])) {
	$mayora = (int)$_GET['mayora'];
} else {
	if (isset($_POST['mayora'])) {
		$mayora = (int)$_POST['mayora'];
	} else {
		$mayora = '0';
	}
}

if (isset($_GET['fhasta'])) {
	$fhasta = $_GET['fhasta'];
} else {
	if (isset($_POST['fhasta'])) {
		$fhasta = $_POST['fhasta'];
	} else {
		$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	}
}

if (isset($_GET['cod_casa']) && ($_GET['cod_casa']!=0)) {
	$cod_casa = $_GET['cod_casa'];
	$condicion_casa = "and to_char(cod_casa)||casa in ('$cod_casa')";
} else { 
	if (isset($_POST['cod_casa']) && $_POST['cod_casa']!=0) {
		$cod_casa = $_POST['cod_casa'];
		$condicion_casa = "and to_char(cod_casa)||casa in ('$cod_casa')";		
	}else {	
		$cod_casa = 0;
		$condicion_casa ="";
	}
}

try {
    $rs_casa = $db -> Execute("select to_char(cod_casa)||casa as codigo, casa as descripcion from casino.t_reg_cp group by cod_casa, casa");
}catch (exception $e){
	die ($db->ErrorMsg()); 
}	
?>

<?php 
try {
    $rs_totales = $db->Execute("SELECT SUM(importe_ficha) AS importe_plata
								FROM casino.t_reg_cp
								WHERE importe_ficha > $mayora
								$condicion_casa
								AND fecha BETWEEN TO_DATE('$fecha','DD/MM/YYYY HH24:MI')
								AND TO_DATE('$fhasta','DD/MM/YYYY HH24:MI')");
}catch(exception $e){
	die ($db->ErrorMsg()); 
} 

$row_totales = $rs_totales->FetchNextObject($toupper=true);

if(isset($_POST['mayora']) and $_POST['mayora']>19999)
{
//EJERZO AUDITORIA
	
	if($_POST['cod_casa']<>0)
	{
	try {
			$rs_casino_auditoria = $db ->Execute("select  casa as descripcion from casino.t_reg_cp
												where  cod_casa=?",array((int)$_POST['cod_casa']));
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_casino_auditoria =$rs_casino_auditoria->FetchNextObject($toupper=true);
	$cas_audita=$row_casino_auditoria->DESCRIPCION;
	}
	else
	{
	$cas_audita=' Todos los Casinos';
	}
	if($_POST['mayora']<50000)
	{
	 	$describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' consulta fraccionamientos en los mov. de Tickets de '.$cas_audita.' entre fechas '.$_POST['fecha'].' y '.$_POST['fhasta'];
	 }
	 else
	 {
	 	$describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' consulta Mov. Mayores a 50000$ en  los Tickets de '.$cas_audita.' entre fechas '.$_POST['fecha'].' y '.$_POST['fhasta'];
		 }
 
 //inserto en tabla auditoria
 ComenzarTransaccion($db);			
			
			try {
				$db->Execute("insert into lavado_dinero.t_auditoria_externa (
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
	
}		

// sacar la fecha!!
//$fecha='1/12/2010';
//$fhasta='31/12/2010';

$_pagi_sql = "SELECT DECODE(anulado,'S', 'ANULADO', '') as ANULADO, casa, caja, cod_casa,
			  moneda , to_char(fecha,'dd/mm/yyyy ') || decode(hora,NULL,'',' ' || hora) as fecha, nombre, importe_ficha, datos, id_cp, cod_mov_caja,
			  TO_CHAR(fecha,'dd/mm/yyyy') AS dia,
									  DECODE(hora,NULL,'',' '
									  || hora) AS hora,
									  NVL(DECLARACION_JURADA,'0') AS JURADA
			  FROM casino.t_reg_cp a
			  WHERE importe_ficha > $mayora
			  AND fecha BETWEEN to_date('$fecha 00:00:00','DD/MM/YYYY HH24:MI:SS')
			  AND to_date('$fhasta 23:59:59','DD/MM/YYYY HH24:MI:SS')
			  $condicion_casa
			  ORDER BY a.fecha DESC,
								  casa,
								  to_number(substr(caja,6,2)),
								  hora,
								  estado";

$_pagi_cuantos = 15; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=3;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='cod_casa';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='fecha'; 
$_pagi_propagar[2]='fhasta';
$_pagi_propagar[3]='mayora'; 
include("paginator_adodb_oracle.inc.php"); 
?>

<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','detalle_casino_cp.php',this); return false;">
	<br />
    <table border="0" align="center" cellspacing="0">
    <tr>
    	<td  align="center" class="textoAzulOscuro" style="font-size:36px"><b>Datos de Sistema For&aacute;neo</b></td>
 	</tr>
     <tr>
    	<td  align="center" class="textoAzulOscuro">&nbsp;</td>
 	</tr>
  </table>
    <table width="800" align="center">
        <tr valign="middle"  >
            <td style="width:20%" align="left"   scope="col">
            	<table border="0" cellspacing="0">
            		<tr> 
            			<td align="left" class="small">Casino</td>
            		</tr>
            		<tr valign="middle">
            			<td><?php armar_combo_todos($rs_casa,"cod_casa",$cod_casa);?></td>
           		  </tr>
            	</table>
		  </td>
            <td style="width:15%" align="left"   scope="col">
            	<table style="width:100%" border="0" cellspacing="0">
            		<tr> 
            			<td align="left" class="small">Mayor a</td>
            		</tr>
            		<tr valign="middle">
            			<td><input type="text" class="small" value="<?php echo $mayora; ?>" name="mayora" id="mayora" /></td>
            		</tr>
            	</table>
			</td>
            <td style="width:25%"  scope="col">
            	<table style="width:100%" border="0" cellspacing="0">
            		<tr>
            			<td width="149" class="small">Fecha desde</td>
            		</tr>
            		<tr>
            			<td><?php abrir_calendario('fecha','premio', $fecha); ?></td>
            		</tr>
            	</table>
			</td>
            <td style="width:20%"  scope="col">
            	<table width="166" border="0" cellspacing="0">
                	<tr>
                		<td width="164" class="small">Fecha hasta</td>
                	</tr>
                    <tr>
            			<td><?php abrir_calendario('fhasta','premio', $fhasta); ?></td>
        			</tr>
    			</table>
			</td>
			<td style="width:20%" align="left" valign="bottom"  scope="col">
				<a onclick="window.open('list/listado_movimientos_de_casino.php?fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&cod_casa=<?php echo $cod_casa ?>&mayora=<?php echo $mayora; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')" href="#"><img width="20" height="20" border="0" title="Imprimir" src="image/24px-Crystal_Clear_app_printer.png"></a>
				<img src="image/xmag.gif" title="Buscar" width="16" height="16" border="0" />
				<input type="submit" class="small" value="Buscar" />
			</td>
		</tr>
	</table>
</form>

<?php
if ($_pagi_result->RowCount()==0) {
	die ("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','detalle_casino_cp.php','')\">Regresar</a></div>"); 
}
?>

<table width="94%" border="0" align="center">
	<tr>
		<td colspan="10" align="center" valign="bottom" scope="col">
			<table width="100%" border="0">
				<tr>
					<td align="center" class="textoRojo" >Movimientos de Casino Caja Publica</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td colspan="10" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
	</tr>
		
	<tr align="center" class="td2">
		<td width="15%" class="td4" scope="col">Casino</td>
		<td width="8%" class="td4" scope="col">Caja</td>
		<td width="8%" class="td4" scope="col">Fecha</td>
		<td width="11%" class="td4" scope="col">Hora</td>
		<td width="18%" class="td4" scope="col">Nombre</td>
		<td width="8%" class="td4" scope="col">Importe</td>
		<!--<?php// if (($_SESSION['rol'.$i] == 'ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i] == 'ROL_LAVADO_DINERO_ADMINISTRA')){?>
		<td width="14%" class="td4" scope="col">Datos</td><?php// }?>-->
		<td width="10%" class="td4" scope="col">Estado Ticket</td>
		<td width="8%" class="td4" scope="col">Nro. Ticket</td>
        <td width="8%" class="td4" scope="col">Dec. Jurada</td>
	</tr>
	
<?php
	while ($row = $_pagi_result->FetchNextObject($toupper=true)){
		$i=0;
		$i++;?>
	<tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
		<td align="left"><?php echo $row->CASA;?></td>
		<td align="center"><?php echo $row->CAJA;?></td>
		<td align="center"><?php echo $row->DIA;?></td>
		<td align="center"><?php echo $row->HORA;?></td>
		<td align="left"><?php echo $row->NOMBRE;?></td>
		<td align="right" ><?php echo number_format($row->IMPORTE_FICHA,2,',','.');?></td>
		<!--<td align="center" >
		<?php if(($_SESSION['rol'.$i] == 'ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i] == 'ROL_LAVADO_DINERO_ADMINISTRA')){?>
		<td align="center" >
        <a href="#" onclick="ajax_get('contenido','procesar_modificar_regcp.php','registro=<?php// echo $row->ID_CP; ?>&modifica=0&fdesde=<?php// echo $fecha; ?>&fhasta=<?php// echo $fhasta; ?>&casa=<?php// echo $cod_casa; ?>&mayora=<?php// echo $mayora; ?>');"><img src="image/Export.png" title="No esta registrado"  width="30" height="28" border="0"/></a>
		<?php// if ( (!is_null($row->DATOS)) && ($row->DATOS==0) ){?>
		<a href="#" onclick="ajax_get('contenido','procesar_modificar_regcp.php','registro=<?php// echo $row->ID_CP; ?>&modifica=1&fdesde=<?php// echo $fecha; ?>&fhasta=<?php// echo $fhasta; ?>&casa=<?php// echo $cod_casa ?>&mayora=<?php// echo $mayora; ?>');"><img src="image/Import.png" title="Si esta registrado"  width="30" height="28" border="0"/></a> 
		<a href="#" onclick="window.open('list/premios_sin_ganadores.php?registro=<?php// echo $row->ID_CP; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Adobe Reader 7.png" title="Imprimir" width="24" height="23" border="0" /></a> 
		<a href="#" onclick="ajax_get('contenido','nueva_nota.php','registro=<?php// echo $row->ID_CP; ?>&fecha=<?php// echo $fecha; ?>&fhasta=<?php// echo $fhasta; ?>&cod_casa=<?php/ echo $cod_casa ; ?>&mayora=<?php// echo $mayora; ?>');return false" ><img src="image/app_48.png" title="redactar nota" width="23" height="21" border="0" /></a> 
		<a href="#" onclick="ajax_get('contenido','nueva_nota.php','registro=<?ph//p echo $row->ID_CP; ?>&fecha=<?php// echo $fecha;?>&fhasta=<?php// echo $fhasta; ?>&mayora=<?php// echo $mayora; ?>');return false" ></a><?php// }?>
		</td>
		<?php }?><!--</td>-->
		<td align="center" ><a href="#" onclick="ajax_get('contenido','premio/anulado_nota.php','anulado=<?php echo $row->ANULADO;?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&cod_casa=<?php echo $row->COD_CASA; ?>&casa=<?php echo $cod_casa ?>&cod_mov_caja=<?php echo  $row->COD_MOV_CAJA ?>&mayora=<?php echo $mayora; ?>');return false;"> <?php echo $row->ANULADO;?></a></td>
		<td align="center"><a href="#" onClick="ajax_get('contenido','premio/tool_tip_ticket.php','cod_mov_caja=<?php echo $row->COD_MOV_CAJA;?> &fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&cod_casa=<?php echo $row->COD_CASA; ?>&casa=<?php echo $cod_casa ?>&mayora=<?php echo $mayora; ?>');return false;"><?php echo $row->COD_MOV_CAJA;?></a></td>
        <td align="right"><?php echo $row->JURADA;?></td>
	</tr>
<?php
}
?>
	<tr>
		<td colspan="10" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
	</tr>
</table>
<?php $_SESSION['sqlreporte'] = $_pagi_sql; ?>