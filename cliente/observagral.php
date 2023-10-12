<?php session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_POST);
	//print_r($_GET);
	//die();
	//print_r($_SESSION);
	//$db->debug=true;
	//$array_fecha = FechaServer();
	//$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

	//$fecha_desde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	//$fecha_hasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];




					
					

$casino=$_GET['casino'];

// obtengo datos del CASINO
try {
	$rs_apostador = $db->Execute("SELECT  N_CASINO as datos
									FROM CASINO.T_CASINOS
									WHERE ID_CASINO=?", array($casino));}
								catch (exception $e){die ($db->ErrorMsg());} 	
	
	
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$soydelcasino=$row_apostador->DATOS;
	
	
	$fecha=$_GET['fecha'];

//obtengo novedad si existe

try {
					$rs_existe = $db->Execute("select novedad  as nuevo
								from PLA_AUDITORIA.t_observa_casino
								where fecha=to_date(?,'dd/mm/yyyy')
								and casino=?",
								array($fecha,$casino));
					}
					catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					$row_existe = $rs_existe->FetchNextObject($toupper=true);
					$nuevo = $row_existe->NUEVO;

		if($rs_existe->RowCount()<>0)
		{
			$observanuevo=$nuevo;
		}
		else
		{
			$observanuevo='';
		}
		
		//echo $observanuevo;
//controlo que este dia no este cerrado
try {
			$rs_cerrado = $db ->Execute("SELECT count(*) as cuenta
				 FROM PLA_AUDITORIA.t_novedad_casino a
				WHERE (a.confirmado='S' or a.confirmado='s')
				and a.fecha_novedad = to_date('$fecha','dd/mm/yyyy')
				and a.id_casino=$casino");
			}	
	catch (exception $e){die ($db->ErrorMsg());} 
	$row_cerrado =$rs_cerrado->FetchNextObject($toupper=true);
	$contar=$row_cerrado->CUENTA;


if($contar==0)//el dia no esta cerrado
{
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php

/*if ($_SESSION['suc_ban']==76){
	$_SESSION['suc_ban']=80;
}
*/

//obtengo todos los apostadores
try {
			$rs_cliente = $db ->Execute("select a.id_cliente as codigo, a.apellido  || ' ' || a.nombre 
										 as descripcion from PLA_AUDITORIA.t_cliente a,
										 casino.t_casinos b
										where a.id_casino=b.id_casino
										and a.fecha_baja is null
											and a.usuario_baja is null		
										
										union
										select 0 as codigo, '*NO SE ENCUENTRA **' as descripcion 
										from dual
										union
										select -1 as codigo, '* * * Seleccione * * *' as descripcion 
										from dual
										order by descripcion");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,40,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 
try {
	$rs_tipo_documento = $db->Execute("SELECT id_tipo_documento AS codigo,  descripcion
										FROM PLA_AUDITORIA.t_tipo_documento
										WHERE id_tipo_documento NOT IN (2,3,4)");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
	
try {
	$rs_tipo_documento2 = $db->Execute("select id_tipo_documento as codigo, descripcion from PLA_AUDITORIA.t_tipo_documento where id_tipo_documento <> 2 and id_tipo_documento <> 3 ");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}

	
try {
	$rs_moneda = $db->Execute("select id_moneda as codigo, descripcion from PLA_AUDITORIA.t_moneda");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
try {
	$rs_tipo_pago = $db->Execute("select id_tipo_pago as codigo, descripcion from PLA_AUDITORIA.t_tipo_pago
										where id_tipo_pago not in(3,4) ");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
$mostrar_juego=0;
//if ($_SESSION['suc_ban']==1 || $_SESSION['suc_ban']==27|| $_SESSION['suc_ban']==23|| $_SESSION['suc_ban']==25|| $_SESSION['suc_ban']==34|| $_SESSION['suc_ban']==26|| $_SESSION['suc_ban']==30|| $_SESSION['suc_ban']==20|| $_SESSION['suc_ban']==21|| $_SESSION['suc_ban']==31|| $_SESSION['suc_ban']==24|| $_SESSION['suc_ban']==33|| $_SESSION['suc_ban']==22|| $_SESSION['suc_ban']==32){
	try {
		$rs_juego = $db->Execute("select id_juegos as codigo,juegos as descripcion from juegos.juegos where activo = 1 order by 2 ");
		}
		catch  (exception $e) 
		{ 
		die($db->ErrorMsg());
		}
    $juegos=25;
	$mostrar_juego=2;
//}

try {
	$rs_suc_ban = $db->Execute("select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
	
try {
	$rs_cod_ticket = $db->Execute("SELECT casa as codigo, cod_mov_caja as descripcion
								FROM casino.t_reg_cp a
								WHERE importe_ficha >= 10000
								and anulado like 'N'
								and fecha_alta between to_date('$fecha_desde','DD/MM/YYYY HH24:MI') and to_date('$fecha_hasta','DD/MM/YYYY HH24:MI')
								and cargado=0
								and cod_casa=?
								ORDER BY cod_mov_caja", array($_SESSION['suc_ban']));
	
	
	/*select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));*/
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
	

if (isset($_GET['documento'])) {
	$documento = $_GET['documento'];
	//$condicion_documento="and g.documento = '$documento'";
	}
	else 
		{
			if (isset($_POST['documento'])) {
				$documento = $_POST['documento'];
				//$condicion_documento="and g.documento = '$documento'";
				} 
			else {
				$documento = "1";
				//$condicion_documento="and g.documento = '$documento'";
			}
		}
		
if (isset($_POST['cantidad'])) {
			$cantidad= $_POST['cantidad'];
			
		}elseif (isset($_GET['cantidad'])) {
			$cantidad = $_GET['cantidad'];
		}
		else
		{
			$cantidad=10;
		}		
	
if (isset($_POST['casino'])) {
			$casino= $_POST['casino'];
			
		}elseif (isset($_GET['casino'])) {
			$casino = $_GET['casino'];
		}		


//llevo a delegacion
	if($casino==0)
	{
		$casino=100;
	}		

if (isset($_POST['apostador'])) {
			$apostador= $_POST['apostador1'];
			
		}elseif (isset($_GET['apostador1'])) {
			$apostador = $_GET['apostador1'];
		}
		else
		{
		//for($i=1;$i<10;$i++)
		//{
			$apostador=-1;
		//}
		}

if (isset($_POST['novedad'])) {
			$novedad= $_POST['novedad'];
			
		}elseif (isset($_GET['novedad'])) {
			$novedad = $_GET['novedad'];
		}	
		else
		{
			$novedad=1;
		}
		//echo $novedad;	
// obtengo datos del apostador
try {
	$rs_apostador = $db->Execute("SELECT  decode(cl.nombre,NULL,initcap(cl.apellido),initcap(cl.apellido) || ', ' || initcap(cl.nombre)) || decode(cl.documento,null,'',' Documento Nro. ' || cl.documento)
									|| ' Registrado en ' || decode(cl.id_casino,100,'Delegacion',ca.n_casino) as datos
								FROM PLA_AUDITORIA.t_cliente cl,
								casino.t_casinos ca
								where ca.id_casino=cl.id_casino
								and cl.id_cliente=?", array($apostador));
	
	
	/*select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));*/
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		if($rs_apostador->RowCount()<>0)
		{
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$datos=utf8_encode($row_apostador->DATOS);
		}
		//echo $datos;die();


$row1 = $rs_suc_ban->FetchNextObject($toupper=true);
?>

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />    
  <style type="text/css">
<!--
.style1 {
	color: #006600;
	font-family: Arial, Helvetica, sans-serif;
}
.style5 {font-size: 10px}
-->
  </style>
  <form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','cliente/procesar_observacion.php',this); return false;">
 	
    <table width="47%" height="53"  border="2" align="center" style="background-color:#999966">
      
                    <tr>
                    <td width="1005" align="center"  colspan="3"><marquee><?php echo $soydelcasino;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha Contable:<?php echo $fecha;?></marquee><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /> <a href="#"  onclick="ajax_get('contenido','cliente/adm_novedad_casino.php','casino=<?php echo $casino?>&fecha=<?php echo $fecha ?>')">Cancelar</a></div></td>
                  </tr>
                  <tr>
                    <td style="font-size:14px;color:#000000;text-align:center;width:850px !important;padding-top:15px;;font:bold" colspan="3" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OBSERVACION GENERAL DE LA JORNADA</td></tr>
      <tr>
        <!--<td width="150" style="font-size:14px;color:#000000;text-align:right;width:250px !important;padding-top:15px;;font:bold">Cantidad de Movimientos (Minimo 10) </td>-->
        <td width="50"  style="font-size:14px;color:#000000;width:250px !important;padding-left:5px;padding-top:15px;text-align:center" colspan="3">
        	<textarea name="novedad" style="font-size:12px;color:#0033FF"  id="novedad"  rows="5" cols="120"/><?php echo $observanuevo?></textarea></td>
        
      </tr>
      
      <input name="casino" id="casino" type="hidden" value="<?php echo $casino?>"/>
      <input name="fecha" id="fecha" type="hidden" value="<?php echo $fecha?>"/>
      <tr>
        <td colspan="4" align="center" style="text-align:center"><div style="text-align:center;margin-top:25px;margin-bottom:15px;">
            <input name="buttonx" class="smallTahoma" id="buttonx" style="font-size:11px;color:#333333;font:bold" value="Registrar" type="submit"/>
        </div></td>
      
    </table>
  
  <?php //}
  }//cierro el if else que permite cargar cantidad de movimientos
  else//el dia esta cerrado
  {?>
  <table width="90%" border="0" align="center" cellspacing="0">
 	<tr><td>&nbsp;</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>&nbsp;</td></tr>
  	  	<tr align="left"><td align="center" scope="row" class="td9Grande"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /><a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_novedad_casino.php','')"><?php echo $fecha;?>***Jornada Cerrada <?php echo $soydelcasino?>***</a></td></tr></table>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
  		
    	    
        
    </tr>
  
  <?php }
  
  ?>