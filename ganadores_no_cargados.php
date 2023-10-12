<?php 
session_start();
include_once("db_conecta_adodb.inc.php");
include_once("funcion.inc.php");
include_once("jscalendar-1.0/calendario.php");
$array_fecha = FechaServer();	
//$db->debug=true;
/*if(basename($_SERVER['PHP_SELF'])=="AUDSaldo.php") {
	session_start();
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
			$suc_ban = $_GET['suc_ban'];
			$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
		} else {
			$suc_ban = 0;
			$condicion_sucursal = "";
	}
}
*/
?>
<?php	

/*<!--if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";
} else if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
			$suc_ban = $_POST['suc_ban'];
			$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";
}-->*/
$variables=array();
$cod_casa='';
$suc_ban='';
$i='';
if (isset($_GET['fecha'])) {
	$fecha = $_GET['fecha'];
} else {
		if (isset($_POST['fecha'])) {
			$fecha = $_POST['fecha'];
		} else {
				$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
							 //'01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
		}
}	

if (isset($_GET['fhasta'])) {
		$fhasta = $_GET['fhasta'];
		//echo 'Hasta: '.$fhasta;
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
		$cod_casa = 0;
		$condicion_casa ="";
}
 if (isset($_POST['cod_casa']) && $_POST['cod_casa']!=0) {
			$cod_casa = $_POST['cod_casa'];
			$condicion_casa = "and to_char(cod_casa)||casa in ('$cod_casa')";
} else {
			if(isset($_GET['cod_casa'])){$cod_casa = $_GET['cod_casa'];
			$condicion_casa = ""; }
}


if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";
} else if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
			$suc_ban = $_POST['suc_ban'];
			$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else {
	if(isset($_GET['suc_ban'])) {$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";}
}

try {
    $rs_casa = $db -> Execute("select to_char(cod_casa)||casa as codigo, casa as descripcion from casino.t_reg_cp group by cod_casa, casa");
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 
/*	
	
	try {
			$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68,79)");
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 	
	*/ 
if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";
} else if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
			$suc_ban = $_POST['suc_ban'];
			$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else {
	if(isset($_GET['suc_ban'])) {$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";}
}
	
/*
try {
    $rs_totales_suc = $db ->Execute("select b.suc_ban, SUM(importe) as importe 
				from cuenta_corriente.t_banco_cabecera a, cuenta_corriente.t_banco_detalle b, cuenta_corriente.concepto d 
				where a.id_banco_cabecera = b.id_banco_cabecera 
				and b.cod_concepto = d.cod_concepto 
				and a.fecha_valor = to_date(?,'DD/MM/YYYY')
				$condicion_sucursal	
				group by b.suc_ban",array($_SESSION['fecha']));
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 
*/
try {
    $rs_totales = $db -> Execute("select SUM(importe_ficha) as importe_plata from casino.t_reg_cp 
						where importe_ficha >= 10000 $condicion_casa
						and fecha between to_date('$fecha','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')");
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 
$row_totales = $rs_totales->FetchNextObject($toupper=true);
//$_SESSION['script'] =  basename($_SERVER['PHP_SELF']);	
///////////////////////////////////////////////////////////////////////	
//$variables[0]=$_SESSION['fecha']; //array de variables bind
//$variables[0]=$_SESSION['fecha']; //array de variables bind
//$variables[1]= "50"; //array de variables bind
//$variables[2]= "51"; //array de variables bind
//$ariables[3]=$cod_juego; //array de variables bind
$_pagi_sql ="select casa, caja, moneda, to_char(fecha,'dd/mm/yyyy') as fecha, nombre, importe_ficha, datos, id_cp 
				from casino.t_reg_cp a
				where importe_ficha >= 10000
				$condicion_casa
				and datos=0 
				and fecha between to_date('$fecha','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
				order by a.fecha desc";
//ho $_pagi_sql.$_SESSION['fecha'];	 
//$_pagi_div = "saldos_totales";
//$_pagi_enlace = "AUDSaldo.php";
$_pagi_cuantos = 20; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=3;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='fecha'; 
$_pagi_propagar[2]='fhasta'; 
include("paginator_adodb_oracle.inc.php"); 
///////////////////////////////////////////////////////////////////////////////////////////
?>
  

<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','ganadores_no_cargados.php',this); return false;">
  <br />
  <table border="0" align="center" cellspacing="0">
    <tr>
    	<td  align="center" class="textoAzulOscuro" style="font-size:36px"><b>Datos de Sistema For&aacute;neo</b></td>
 	</tr>
     <tr>
    	<td  align="center" class="textoAzulOscuro">&nbsp;</td>
 	</tr>
  </table>
  <table align="center">
    <tr valign="middle"  >
      <td align="left"   scope="col"><table border="0" cellspacing="0">
        <tr>
          <td align="left" class="small">Casino</td>
          </tr>
        <tr valign="middle">
          <td><?php armar_combo_todos($rs_casa,"cod_casa",$cod_casa);?></td>
        
          </tr>
      </table></td>
      <td  scope="col"><table border="0" cellspacing="0">
        <tr>
          <td class="small">Fecha desde</td>
        </tr>
        <tr>
          <td><?php  abrir_calendario('fecha','premio', $fecha); ?></td>
        </tr>
      </table></td>
      <td  scope="col"><table border="0" cellspacing="0">
        <tr>
          <td class="small">Fecha hasta</td>
        </tr>
        <tr>
          <td><?php  abrir_calendario('fhasta','premio', $fhasta); ?></td>
        </tr>
      </table></td>
      <td align="center" valign="bottom"  scope="col"><img src="image/xmag.gif" alt="Buscar" width="16" height="16" border="0" />
      <input type="submit" class="small" value="Buscar" /></td>
    </tr>
  </table>
</form>
<table width="78%" border="0" align="center">
    <tr>
      <td colspan="6" align="center" valign="bottom" scope="col"><table width="100%" border="0">
                  <tr>
                    <td align="center" class="textoRojo" >Premios Pagados sin Registros de Ganadores</td>
                    <td align="center" class="textoRojo" ><a href="#" onclick="window.open('list/lista_premios_sin_ganadores.php','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
                    <!--<td width="1%"><a href="#" class="Estilo3" onclick="window.open('list/prueba2.php?cod_juego=<?php echo $cod_juego ?>&suc_ban=<?php echo $suc_ban ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>-->
                  </tr>
                </table></td>
           </tr>
             <tr>
              <td colspan="6" align="center" valign="bottom" class="smallRojo" scope="col"><?php // echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
        </tr>
            <tr align="center" class="td2">
              <td width="14%" class="td4" scope="col">Fecha</td>
              <td width="20%" class="td4" scope="col">Casa</td>
              <td width="14%" class="td4" scope="col">Moneda</td>
              <td width="15%" class="td4" scope="col">Caja</td>
              <td width="23%" class="td4" scope="col">Nombre</td>
              <td width="14%" class="td4" scope="col">Importe</td>
  </tr>
           <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
           <?php $i=$i+1;?>
           <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
               <td align="center"><?php echo $row->FECHA;?></td>
             <td align="left"><?php echo $row->CASA;?></td>
               <td align="center"><?php echo $row->MONEDA;?></td>
               <td align="rigth"><?php echo $row->CAJA;?></td>
               <td align="left"><?php echo $row->NOMBRE;?></td>
               <td align="right" ><?php echo number_format($row->IMPORTE_FICHA,2,',','.');?></td>
           </tr>
        <?php  }?>
		 <tr>
          <td colspan="6" align="center" valign="bottom" class="smallRojo" scope="col"><?php  echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
  </table>
  <?php $_SESSION['sqlreporte']= $_pagi_sql;?>
<br />
<br />
