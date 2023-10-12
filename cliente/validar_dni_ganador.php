<?php session_start(); 
include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php"); 
print_r($_GET); 
//die();
//$fecha_desde=$_GET['fecha_desde'];
//$fecha_hasta=$_GET['fecha_hasta'];
$db->debug=true;
$tipito=1;
$array_fecha = FechaServer();	
if (isset($_GET['fecha'])) {$fecha = $_GET['fecha']; }
			else {if (isset($_POST['fecha'])) {	$fecha = $_POST['fecha'];}
					 else {	$fecha = '01/01/2012'; }
				}
			
		 if (isset($_GET['fhasta'])) {$fhasta = $_GET['fhasta'];}
			 else {if (isset($_POST['fhasta'])) {$fhasta = $_POST['fhasta'];}
			 		 else {	$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
			      }

$condicion_documento='';
$condicion_tipo_documento='';

if (isset($_POST['casino'])) {
			$casino= $_POST['casino'];
			
		}elseif (isset($_GET['casino'])) {
			$casino = $_GET['casino'];
		}		
//echo $fecha.$fhasta.$casino;
/*if (isset($_POST['suc_ban'])) {
			$suc_ban = $_POST['suc_ban'];
			
		}elseif (isset($_GET['suc_ban'])) {
			$suc_ban = $_GET['suc_ban'];
		}	*/	


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
	$rs_tipo_documento = $db->Execute("select id_tipo_documento as codigo,
				 descripcion from PLA_AUDITORIA.t_tipo_documento ");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
		

if (isset($_GET['id_tipo_documento'])) {
	$id_tipo_documento = $_GET['id_tipo_documento'];
	$condicion_tipo_documento="and id_tipo_documento = '$id_tipo_documento'";
	}
	else 
		{
			if (isset($_POST['id_tipo_documento'])) {
				$id_tipo_documento = $_POST['id_tipo_documento'];
				$condicion_tipo_documento="and id_tipo_documento = '$id_tipo_documento'";
				} 
			else {
				$id_tipo_documento = "1";
				$condicion_tipo_documento="and id_tipo_documento = '$id_tipo_documento'";
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
		
if (isset($_POST['apostador']))
		{
			$apostador = $_POST['apostador'];
			if($apostador<>0)
			{
			$condicion_apostador="and id_cliente ='$apostador'";
			}
			else
			{
			$condicion_apostador="and id_cliente='9999999999'";
			}
		} 
		else
		{
		if(isset($_GET['apostador']))
		 {
					$apostador = $_GET['apostador'];
					if($apostador<>0)
					{
					$condicion_apostador="and id_cliente ='$apostador'";
					}
			else
			{
			$condicion_apostador="and id_cliente='9999999999'";
			}
		 }
		else
		{
					$apostador = '0';
					$condicion_apostador="and id_cliente='9999999999'";
		}
		} 


try {
	$rs_consulta = $db->Execute("select id_tipo_documento, documento, ID_CLIENTE
								from PLA_AUDITORIA.t_cliente
								  where 1=1								 
								  $condicion_apostador
								 and rownum < 2
								 order by fecha_alta desc");
	}
	catch  (exception $e) 
	
	{ 
	die($db->ErrorMsg());
	}
$row = $rs_consulta->FetchNextObject($toupper=true);
 


	


if (!isset($_POST['documento'])) {

 

?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<form id="form1" name="form1" action="#" onsubmit="ajax_post('contenido','cliente/validar_dni_ganador.php',this); return false;">
<table width="173" border="1" align="center">
  <tr>
    <td colspan="2" align="center" class="textoAzulOscuroFondo">Validar Apostador</td>
  </tr>
  <tr>
    <td width="64" align="center" class="td2"><strong>Tipo DNI</strong>      </div></td>
    <?php if($apostador<>0) {$tipito=$row->ID_TIPO_DOCUMENTO;}?>
    <td width="104" align="lefth" class="td2"><?php echo armar_combo($rs_tipo_documento,'id_tipo_documento',$tipito); ?>&nbsp;</td>
  </tr> 
  <tr>
    <td align="center" class="td2"><strong>DNI</strong></td>
    <td align="left" class="td2"><input name="documento" type="text" class="small_derecha" id="documento" value="<?php if($apostador<>0) {echo $row->DOCUMENTO;}?>" size="13" maxlength="13"   /></td>
  </tr>
  <tr>
 
    <td height="34" colspan="2" align="center" class="td2"><strong>&nbsp;</strong><strong>&nbsp;<strong>
      <input name="button" type="submit" class="textoAzulOscuro" id="button" value="Validar"   />
	 
	  <input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $_GET['fecha_inicio']; ?>" />
	  <input type="hidden" name="fhasta" id="fhasta" value="<?php echo $_GET['fhasta']; ?>" />
	  <input type="hidden" name="casino" id="casino" value="<?php echo $casino; ?>" />
      <input type="hidden" name="apostador" id="apostador" value="<?php echo $apostador;?>" />
	  <!--<input type="hidden" name="suc_ban" id="suc_ban" value="<?php// echo $_GET['suc_ban']; ?>" />-->
    </strong></strong></td>
  </tr>   
</table>
</form>
<div align="center" class="td2" ><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /><a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_cliente.php','fecha=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&casino=<?php echo $casino; ?>')">Regresar</a></div>
</td>
<?php  } else {
//echo $rs_consulta->rowCount();
 if ($rs_consulta->rowCount()>0) { 
//$id_ganador= $row->ID_GANADOR;
$id_cliente= $row->ID_CLIENTE;
 
include("modificar_apostador.php");

}
else {
 include("agregar_premio.php");
} 
}

?>
