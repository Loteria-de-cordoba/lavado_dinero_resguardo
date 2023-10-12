<?php 
if(basename($_SERVER['PHP_SELF'])=="AUDSaldo.php") {
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
?><script language="javascript" src="funcion2.js"></script>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" /><form action="" method="post" enctype="multipart/form-data" name="formulario" id="formulario">
<?php	
/*
if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
	} else {
	$suc_ban = 0;
	$condicion_sucursal = "";
}
*/
try {
    $rs_sucursal = $db -> Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33)");
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 
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
try {
    $rs_totales = $db -> Execute("select SUM(importe) as importe 
				from cuenta_corriente.t_banco_cabecera a, cuenta_corriente.t_banco_detalle b, cuenta_corriente.concepto d 
				where a.id_banco_cabecera = b.id_banco_cabecera 
				and b.cod_concepto = d.cod_concepto 
				and a.fecha_valor = to_date(?,'DD/MM/YYYY')
				$condicion_sucursal	
				order by suc_ban, nro_agen",array($_SESSION['fecha']));
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 
$row_totales = $rs_totales->FetchNextObject($toupper=true);
//$_SESSION['script'] =  basename($_SERVER['PHP_SELF']);	
///////////////////////////////////////////////////////////////////////	
$variables[0]=$_SESSION['fecha']; //array de variables bind
$variables[1]= "50"; //array de variables bind
$variables[2]= "51"; //array de variables bind
//$ariables[3]=$cod_juego; //array de variables bind
$_pagi_sql ="select suc_ban, nro_agen, d.descripcion as concepto, sum(decode(b.cod_concepto,51,importe,importe*-1)) as importe
				from cuenta_corriente.t_banco_cabecera a, cuenta_corriente.t_banco_detalle b, cuenta_corriente.concepto d 
				where a.id_banco_cabecera = b.id_banco_cabecera 
				and b.cod_concepto = d.cod_concepto 
				and a.fecha_valor = to_date(?,'DD/MM/YYYY')
				and b.cod_concepto in (?,?)
				$condicion_sucursal
				group by suc_ban, nro_agen, d.descripcion";
//ho $_pagi_sql.$_SESSION['fecha'];	 
$_pagi_div = "saldos_totales";
$_pagi_enlace = "AUDSaldo.php";
$_pagi_cuantos = 7; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=3;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
include("paginator_adodb_oracle.inc.php"); 
///////////////////////////////////////////////////////////////////////////////////////////
?>
  <?php if ($_pagi_result->RowCount()==0) {
			die ("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','AUDbalance.php','')\">Regresar</a></div>"); 
		}
?>
<!--
<table border="0" align="center">
  <tr>
    <th scope="col">Delegacion</th>
    <th scope="col"><?php armar_combo_ejecutar_ajax_get_todos($rs_sucursal,"suc_ban",$suc_ban,'saldos_totales','AUDSaldo.php');?></th>
  </tr>
</table>
-->
<table width="80%" border="0" align="center">
<tr>
  <td colspan="6" align="center" valign="bottom" scope="col"><table width="100%" border="0">
              <tr>
                <td align="center" class="textoRojo" >Saldos</td>
                <td width="1%"><a href="#" class="Estilo3" onclick="window.open('list/prueba2.php?cod_juego=<?php echo $cod_juego ?>&suc_ban=<?php echo $suc_ban ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
              </tr>
            </table></td>
       </tr>
		 <tr>
          <td colspan="6" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
        <tr align="center" class="td2">
          <td class="td4" scope="col">Del</td>
          <td class="td4" scope="col">Age</td>
          <td class="td4" scope="col">Importe</td>
    </tr>
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
           <td align="center"><?php echo $row->SUC_BAN;?></td>
           <td align="center" ><?php echo str_pad($row->NRO_AGEN,4,'0',STR_PAD_LEFT);?></td>
           <td align="right" ><?php echo number_format($row->IMPORTE,2,',','.');?></td>
       </tr>
        <?php  }?>
		 <tr>
          <td colspan="6" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
  </table>
</form>
<table width="80%" border="0" align="center">
  <tr class="texto3FondoRosa">
    <td align="left">TOTALES</td>
    <td width="20%" align="right" ><?php echo number_format($row_totales->IMPORTE,2,',','.');?></td>
  </tr>
</table>
<br />
<br />
