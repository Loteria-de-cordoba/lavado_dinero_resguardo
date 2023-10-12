<?php session_start(); 
include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php");
?>
<?php



if (isset($_POST['codigo'])) {
	$codigo = $_POST['codigo'];
	} else if (isset($_GET['codigo']) && $_GET['codigo']!="") {
		$codigo = $_GET['codigo'];
	}
//echo($codigo);	
try {
	$rs=$db->Execute("select * from administrativo.t_provincias where id_pais=?", array($codigo));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	} 
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<table width="328" border="0" cellspacing="0">
  <tr>
    <td colspan="5" align="center" class="td">Provincia</td>
    <td    align="right" class="td"><a href="#" onclick="ajax_hideTooltip();">Salir</a></td>
  </tr>
  <?php $contador=0; ?>
  <tr  class="texto3Totales">
    <?php while ($row = $rs->FetchNextObject($toupper=true)) { ?>
    <td   colspan="2"><a href="#" onclick="document.<?php echo $_GET['formulario'];?>.provincia.value='<?php echo $row->N_PROVINCIA; ?>';
                                    	   document.<?php echo $_GET['formulario'];?>.cod_provincia.value='<?php echo $row->ID_PROVINCIA; ?>';
                                           document.<?php echo $_GET['formulario'];?>.provincia_memo.value='<?php echo $row->N_PROVINCIA; ?>';
                                    	   document.<?php echo $_GET['formulario'];?>.cod_provincia_memo.value='<?php echo $row->ID_PROVINCIA; ?>';
                                           document.<?php echo $_GET['formulario'];?>.localidad.value='';
                                           document.<?php echo $_GET['formulario'];?>.cod_localidad.value='0';
                                           document.<?php echo $_GET['formulario'];?>.localidad_memo.value='';
                                           document.<?php echo $_GET['formulario'];?>.cod_localidad_memo.value='0';
                                           ajax_hideTooltip(); return false;" ><?php echo utf8_encode($row->N_PROVINCIA); ?></a></td>
    <?php $contador++; 
		 if ($contador==3) { echo "</tr><tr class='texto3Totales'>"; $contador=0;}?>
    <?php }  ?>
  </tr>
</table>
