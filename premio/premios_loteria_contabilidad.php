<?php 
session_start();
include_once("../jscalendar-1.0/calendario.php");
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
$array_fecha = FechaServer();	
$j='';
$variables=array();
$_SESSION['rol']=array();
$fecha='';
$conformado='';
//$db->debug=true;
//print_r($_SESSION);

$i=0;
while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC') || ($_SESSION['rol'.$i]=='LAVADO_DINERO_CONFORMA_TODO') || ($_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO')|| ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CONFORMA')|| ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA')|| ($_SESSION['rol'.$i]=='LAVADO_DINERO_CONF_DELE') )	{
	
	$habilitado=1;} 
}

if(($_SESSION['suc_ban']==60)||($_SESSION['suc_ban']==62)||($_SESSION['suc_ban']==63)||($_SESSION['suc_ban']==64)||($_SESSION['suc_ban']==65)||($_SESSION['suc_ban']==66)||($_SESSION['suc_ban']==67)||($_SESSION['suc_ban']==73)||($_SESSION['suc_ban']==79)||($_SESSION['suc_ban']==81)){
	
	$habilitado=0;
}

if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 


if (isset($_GET['fdesde'])) {
				$fdesde = $_GET['fdesde'];
		} else {
			if (isset($_POST['fdesde'])) {
				$fdesde = $_POST['fdesde'];
			} else {
				$fdesde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
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



while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;

	if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' || $_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO'){	
		// echo('ENTRAAAAAA');
			try {
				$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51) order by suc_ban");
				}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
				catch (exception $e){die ($db->ErrorMsg()); } 	
	
			 if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
						$suc_ban = $_GET['suc_ban'];
						$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
						
					} elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
								$suc_ban = 0;
								$condicion_sucursal = "and b.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
							} 
					
					elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
								$suc_ban = $_POST['suc_ban'];
								$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
							} 
							 elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']==0) {
								$suc_ban = 0;
								$condicion_sucursal = "and b.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
							} 
							
							else {
									$suc_ban = 0;
									$condicion_sucursal = "and b.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
									
								 }

try {
	$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)");
	}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
	} 


		}
	


	if(($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA')||($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_CONFORMA')||($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OP_UNICO')||  ($_SESSION['rol'.$j]=='LAVADO_DINERO_CONF_DELE')|| ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA')){

			if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
			} 
			
			elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
						$suc_ban = $_POST['suc_ban'];
						$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
					} 
					 elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']==0) {
						$suc_ban = 0;
						$condicion_sucursal = "";
					} 
					
					else {
							$suc_ban = $_SESSION['suc_ban'];
							$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
						 }

try {
	$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,51)");
	}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
	} 


	}
	
	
	/*if($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_CONFORMA'){

			if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
			} 
			
			elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
						$suc_ban = $_POST['suc_ban'];
						$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
					} 
					 elseif (isset($_POST['suc_ban']) && $_POST['suc_ban']==0) {
						$suc_ban = 0;
						$condicion_sucursal = "";
					} 
					
					else {
							$suc_ban = $_SESSION['suc_ban'];
							$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
						 }

	}
*/
	
	
	



try {
    $rs_totales = $db -> Execute(" SELECT SUM(total) AS importe_plata
 								   FROM conta_new.asiento_cabecera a
 								   WHERE a.concepto LIKE '%UIF%' 
								   and a.fecha_valor BETWEEN  to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')");
	}
	catch (exception $e)
	{
	die ($db->ErrorMsg()); 
    } 

$row_totales = $rs_totales->FetchNextObject($toupper=true);

if($_SESSION['rol1']=='LAVADO_DINERO_CONFORMA_TODO' and $suc_ban==0)
{
$_pagi_sql=("select a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor,
					a.nro_asiento as asiento, a.concepto as concepto, b.nombre as sucursal, d.descripcion as operador, b.suc_ban AS suc_ban
						from conta_new.asiento_cabecera a, adm.area c, juegos.sucursal b, adm.personal d
						where a.cod_area = c.cod_area
							  and c.suc_ban = b.suc_ban
							  and a.cod_area_vinculante is null
							  and a.operador = lpad(d.legajo,5,'0')
							  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
							  and ( upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%' )
							  $condicion_sucursal
							  
			UNION 
							select DISTINCT  a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor,
							a.nro_asiento as asiento, a.concepto as concepto, 'CAPITAL FEDERAL' as sucursal, d.descripcion as operador, 51 AS suc_ban
							from conta_new.asiento_cabecera a, adm.area c, juegos.sucursal b, adm.personal d
							where a.cod_area = 33
							and c.suc_ban = b.suc_ban
							and a.cod_area_vinculante is null
							and a.operador = lpad(d.legajo,5,'0')
							and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
							and ( upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%' )
							and b.suc_ban=1
							order by suc_ban, fecha_valor");
}
else
{
$_pagi_sql=("select a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor,
					a.nro_asiento as asiento, a.concepto as concepto, b.nombre as sucursal, d.descripcion as operador, b.suc_ban AS suc_ban
						from conta_new.asiento_cabecera a, adm.area c, juegos.sucursal b, adm.personal d
						where a.cod_area = c.cod_area
							  and c.suc_ban = b.suc_ban
							  and a.cod_area_vinculante is null
							  and a.operador = lpad(d.legajo,5,'0')
							  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
							  and ( upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%' )
							  $condicion_sucursal
							  order by b.suc_ban, fecha_valor");
}

if    ($suc_ban==51 && $_SESSION['rol1']=='LAVADO_DINERO_CONFORMA_TODO')
{
	$_pagi_sql=("select DISTINCT  a.total, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor,
							a.nro_asiento as asiento, a.concepto as concepto, 'CAPITAL FEDERAL' as sucursal, d.descripcion as operador, 51 AS suc_ban
						from conta_new.asiento_cabecera a, adm.area c, juegos.sucursal b, adm.personal d
						where a.cod_area = 33
							  and c.suc_ban = b.suc_ban
							  and a.cod_area_vinculante is null
							  and a.operador = lpad(d.legajo,5,'0')
							  and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
							  and ( upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%' )
							  and b.suc_ban=1
							  order by suc_ban, fecha_valor");
}                
 
$_pagi_div = "contenido";
//$_pagi_enlace = "AUDSaldo.php";
$_pagi_cuantos = 15; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=3;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='fdesde'; 
$_pagi_propagar[1]='fhasta'; 
$_pagi_propagar[2]='suc_ban';
//$_pagi_propagar[3]='sucursal';
//$_pagi_propagar[4]='fecha'; 
include("../paginator_adodb_oracle.inc.php");
$_SESSION['nro_pagina']=$_pagi_actual ;

//$paginator="../paginator_adodb_oracle.inc.php"; 
///////////////////////////////////////////////////////////////////////////////////////////
?>

<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','premio/premios_loteria_contabilidad.php',this); return false;">
<br />
    <table border="0" align="center" cellspacing="0">
    <tr>
    	<td  align="center" class="textoAzulOscuro" style="font-size:36px"><b>Datos de Sistema For&aacute;neo</b></td>
 	</tr>
     <tr>
    	<td  align="center" class="textoAzulOscuro">&nbsp;</td>
 	</tr>
  </table>
<table border="0" align="center" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="0">
      <tr><?php if(($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA')||($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC') || ($_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO') || ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA')){?>
        <td><span class="small">Delegacion</span></td><?php }?>
        <td><span class="small">Fecha desde</span></td>
        <td><span class="small">Fecha hasta</span></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> <?php if(($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA')||($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC') || ($_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO') || ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA')){?>
        <td><span class="td2">
          <?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?>
        </span></td><?php }?>
        <td><span class="small">
          <?php abrir_calendario('fdesde','premio',$fdesde); ?>
        </span></td>
        <td><span class="small">
          <?php abrir_calendario('fhasta','premio',$fhasta); ?>
        </span></td>
        <td>&nbsp;</td>
        <td><input name="Procesar" type="submit" class="small" id="Procesar" value="Buscar" />
          <img src="image/xmag.gif" alt="Buscar" width="16" height="16" border="0" /></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
<?php 
if ($_pagi_result->RowCount()==0) {
			die ("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','premio/premios_loteria_contabilidad.php','')\">Regresar</a></div>"); 
}
?>

<table width="82%" border="0" align="center">
<tr>
      <td colspan="7" align="center" valign="bottom" scope="col">
        <table width="100%" border="0">
            <tr><?php if(($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA')||($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC') || ($_SESSION['rol'.$j]=='LAVADO_DINERO_CONFORMA_TODO')){?>
				<td width="1%"><a href="#" onclick="window.open('list/cantidad_movimientos_delegacion.php?fecha=<?php echo $fdesde; ?>&fhasta=<?php echo $fhasta; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Totales por Delegacion" width="24" height="23" border="0" /></a></td>
              	<?php }?>
				<td align="center" class="textoRojo" >Registro de Contabilidad</td>
            	<td width="1%"><a href="#" onclick="window.open('list/loteria_contabilidad.php?conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban; ?>&fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Premios Pagados" width="24" height="23" border="0" /></a></td>
            </tr>
        </table>	  </td>
    </tr>
    <tr>
      <td colspan="7" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
    <tr align="center" class="td2">
      <td width="12%" class="td4" scope="col">Casa</td>
      <td width="11%" class="td4" scope="col">Fecha</td>
      <td width="21%" class="td4" scope="col">Operador</td>
      <td width="8%" class="td4" scope="col">Detalle</td>
      <td width="38%" class="td4" scope="col">Concepto</td>
      <td width="10%" class="td4" scope="col">Monto</td>
    </tr>
    <?php  $fecha='';	while ($rowconta=$_pagi_result->FetchNextObject($toupper=true)) {?>  
    <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
      <td height="30" align="left"  ><?php echo $rowconta->SUCURSAL; ?></td>
        <td height="30" align="center"  ><?php echo $rowconta->FECHA_VALOR; ?></td>
        <td height="30" align="left"  ><?php echo $rowconta->OPERADOR; ?></td>
        <td height="30" align="center"  ><a href="#" onmouseover="ajax_showTooltip('premio/detalle_asiento_contable_tooltip.php?jsfecha='+new Date()+'&amp;asiento=<?php echo $rowconta->ASIENTO; ?>&amp;fecha=<?php echo $rowconta->FECHA_VALOR; ?>&amp;fdesde=<?php echo $fdesde; ?>&amp;fhasta=<?php echo $fhasta; ?>',this);return false" onmouseout="ajax_hideTooltip()"><img src="image/xmag.gif" alt="ver" width="23" height="21" border="0" /></a></td>
        <td height="30" align="left" ></a><?php echo utf8_encode($rowconta->CONCEPTO); ?></td>
        <td height="30" align="right"  ><input type="hidden" name="MONTO"  value="<?php echo $rowconta->TOTAL; ?>" />
      <?php echo '$ '.number_format($rowconta->TOTAL,2,',','.') ?></td>
  </tr>
     <?php  } ?>
	 <tr>
          <td colspan="7" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
  </table>
<br />
<br />
<?php }
$_SESSION['sqlreporte']= $_pagi_sql;
?>