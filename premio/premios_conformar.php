<?php session_start();
include("../jscalendar-1.0/calendario.php");
include("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
$array_fecha = FechaServer();
//$fdesde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
//$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
//print_r($_SESSION['rol1']);
//quito acceso a casino_carga
$i=0;
$habilitado=0;
while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	if (($_SESSION['rol'.$i]<>'ROL_LAVADO_DINERO_CASINO_CARGA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
}	
else
{
if (isset($_GET['fdesde'])) {
	$fdesde = $_GET['fdesde'];
}
else{
	if (isset($_POST['fdesde'])) {
		$fdesde = $_POST['fdesde'];
	}
	 else {
			$fdesde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
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

//obtengo sucursales para el rol LAVADO_DINERO_CONFORMA_TODO
try {
				$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51) order by suc_ban");
				}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
				catch (exception $e){die ($db->ErrorMsg()); } 
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<form action="#" method="post" name="FrmBuscaFechaConformar" onSubmit="ajax_post('conformar','premio/mostrar_pre_conformacion.php',this); return false;">
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="6" align="center" class="textoAzulOscuro">CONFORMAR</td>
  </tr>
  <tr>
  <td><table border="0" cellspacing="0">
    
        <tr>
        	<?php if ($_SESSION['rol1']=='LAVADO_DINERO_CONFORMA_TODO'){	  ?>
          <td class="small">Delegaci&oacute;n</td>
        </tr>
        <tr>
          <td><?php armar_combo($rs_sucursal,"suc_ban",$suc_ban);?></td>
          <?php }?>
        </tr>
      </table></td>
    <td><table border="0" cellspacing="0">
    
        <tr>
          <td class="small">Desde</td>
        </tr>
        <tr>
          <td><?php abrir_calendario('fdesde','FrmBuscaFechaConformar',$fdesde); ?></td>
        </tr>
      </table></td>
    <td><table border="0" cellspacing="0">
        <tr>
          <td class="small">Hasta</td>
        </tr>
        <tr>
          <td><?php abrir_calendario('fhasta','FrmBuscaFechaConformar',$fhasta); ?></td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
    <td valign="bottom"><input name="Procesar" type="submit" class="small" id="Procesar" value="Buscar" /></td>
  </tr>
</table>
</form>
<div id="conformar"></div>
<?php }//cierro acceso?>

