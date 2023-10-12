<?php 
session_start(); 
include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php");
 
//print_r($_GET); 
//$fecha_desde=$_GET['fecha_desde'];
//$fecha_hasta=$_GET['fecha_hasta'];
//$db->debug=true;
$array_fecha = FechaServer();


//quito acceso a casino_carga
$i=0;
$habilitado=0;
while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	if (($_SESSION['rol'.$i]<>'ROL_LAVADO_DINERO_CASINO_CARGA' and $_SESSION['rol'.$i]<>'LAVADO_DINERO_CONF_DELE'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
}	
else
{
if (isset($_GET['fecha'])) {$fecha = $_GET['fecha']; }
			else {if (isset($_POST['fecha'])) {	$fecha = $_POST['fecha'];}
					 else {	$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"]; }
				}
			
		 if (isset($_GET['fhasta'])) {$fhasta = $_GET['fhasta'];}
			 else {if (isset($_POST['fhasta'])) {$fhasta = $_POST['fhasta'];}
			 		 else {	$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
			      }



if (isset($_POST['conformado'])) {
			$conformado = $_POST['conformado'];
			
		}elseif (isset($_GET['conformado'])) {
			$conformado = $_GET['conformado'];
		}		

if (isset($_POST['suc_ban'])) {
			$suc_ban = $_POST['suc_ban'];
			
		}elseif (isset($_GET['suc_ban'])) {
			$suc_ban = $_GET['suc_ban'];
		}		


//$db->debug=true; 
/*$asiento=$_GET['asiento'];
$fecha=$_GET['fecha'];


$condicion_asiento="and a.nro_asiento =$asiento";
//$cuentas='665,642,611,482,483,719';

try {
	$tuhermana = $db->Execute("select a.total, a.concepto, to_char(a.fecha_valor,'DD/MM/YYYY') as fecha_valor 
                         from conta_new.asiento_cabecera a--, conta_new.asiento_detalle b 
                         -- a.nro_asiento=b.nro_asiento
                         --and b.cod_cuenta in ($cuentas)
                         where a.cod_area_vinculante is null
                         and a.fecha_valor = to_date('$fecha', 'dd/mm/yyyy')
                         and upper(a.concepto) like '%UIF%'
						 $condicion_asiento
                         order by fecha_valor, a.total desc");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}*/
	
try {
	$rs_tipo_documento = $db->Execute("select id_tipo_documento as codigo, descripcion from PLA_AUDITORIA.t_tipo_documento ");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
		

if (isset($_GET['id_tipo_documento'])) {
	$id_tipo_documento = $_GET['id_tipo_documento'];
	$condicion_tipo_documento="where id_tipo_documento = '$id_tipo_documento'";
	}
	else 
		{
			if (isset($_POST['id_tipo_documento'])) {
				$id_tipo_documento = $_POST['id_tipo_documento'];
				$condicion_tipo_documento="where id_tipo_documento = '$id_tipo_documento'";
				} 
			else {
				$id_tipo_documento = "1";
				$condicion_tipo_documento="where id_tipo_documento = '$id_tipo_documento'";
			}
		}



if (isset($_GET['documento'])) {
	$documento = $_GET['documento'];
	$condicion_documento="and documento = '$documento'";
	}
	else 
		{
			if (isset($_POST['documento'])) {
				$documento = $_POST['documento'];
				$condicion_documento="and documento = '$documento'";
				} 
			/*else {
				$documento = "1";
				$condicion_documento="and documento = '$documento'";
			}*/
		}
		



try {
	$rs_consulta = $db->Execute("select id_ganador  from PLA_AUDITORIA.t_ganador								 
								 $condicion_tipo_documento
								 $condicion_documento
								 and rownum = 1
								 order by fecha desc");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
$row = $rs_consulta->FetchNextObject($toupper=true);
 


	


if (!isset($_POST['documento'])) {

 

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<form id="form1" name="form1" action="#" onsubmit="ajax_post('contenido','premio/validar_dni_ganador.php',this); return false;">
<table width="173" border="1" align="center">
  <tr>
    <td colspan="2" align="center" class="textoAzulOscuroFondo">Validar Ganador</td>
  </tr>
  <tr>
    <td width="64" align="center" class="td2"><strong>Tipo DNI</strong>      </div></td>
    <td width="104" align="lefth" class="td2"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',''); ?>&nbsp;</td>
  </tr> 
  <tr>
    <td align="center" class="td2"><strong>DNI</strong></td>
    <td align="left" class="td2"><input name="documento" type="text" class="small_derecha" id="documento" size="13" maxlength="13"   /></td>
  </tr>
  <tr>
 
    <td height="34" colspan="2" align="center" class="td2"><strong>&nbsp;</strong><strong>&nbsp;<strong>
      <input name="button" type="submit" class="textoAzulOscuro" id="button" value="Validar"   />
	 
	  <input type="hidden" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" />
	  <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta']; ?>" />
	  <input type="hidden" name="conformado" id="conformado" value="<?php echo $_GET['conformado']; ?>" />
	  <input type="hidden" name="suc_ban" id="suc_ban" value="<?php echo $_GET['suc_ban']; ?>" />
    </strong></strong></td>
  </tr>   
</table>
</form>
<div align="center" class="td2" ><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /><a href="#" class="small" onclick="ajax_get('contenido','premio/adm_premio.php','fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban; ?>')">Regresar</a></div>
</td>
<?php  } else {
//echo $rs_consulta->rowCount();
 if ($rs_consulta->rowCount()>0) { 
$id_ganador= $row->ID_GANADOR;
 
include("premio_ganador_registrado2.php");

}
else {
 include("agregar_premio.php");
} 
}
}//fin del else por no habilitado
?>
