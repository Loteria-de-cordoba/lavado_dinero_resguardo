<?php session_start(); 
include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php");


try {
	$rs = $db->Execute("select * from administrativo.t_paises order by n_pais");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
// echo $_GET['formulario'];
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<table width="113" border="0" cellspacing="0">
  <tr   class="td">
    <td width="50"  >Pa&iacute;s</td>
  	<td width="53"><a href="#"onclick="ajax_hideTooltip()">Salir</a></td>
  </tr>
  
  <?php while ($row = $rs->FetchNextObject($toupper=true)) { ?>
   <tr  >
     <td colspan="2" class="texto3Totales"><a href="#" onClick="document.<?php echo $_GET['formulario'];?>.pais.value='<?php echo $row->N_PAIS;?>';
                                                                document.<?php echo $_GET['formulario'];?>.cod_pais.value='<?php echo $row->ID_PAIS; ?>';
                                                                document.<?php echo $_GET['formulario'];?>.pais_memo.value='<?php echo $row->N_PAIS;?>';
                                                                document.<?php echo $_GET['formulario'];?>.cod_pais_memo.value='<?php echo $row->ID_PAIS; ?>';
                                                                document.<?php echo $_GET['formulario'];?>.provincia.value='Cordoba';
                                                                document.<?php echo $_GET['formulario'];?>.cod_provincia.value='6';
                                                                document.<?php echo $_GET['formulario'];?>.provincia_memo.value='Cordoba';
                                                                document.<?php echo $_GET['formulario'];?>.cod_provincia_memo.value='6';
                                                                document.<?php echo $_GET['formulario'];?>.localidad.value='';
                                                                document.<?php echo $_GET['formulario'];?>.cod_localidad.value='';
                                                                document.<?php echo $_GET['formulario'];?>.localidad_memo.value='';
                                                                document.<?php echo $_GET['formulario'];?>.cod_localidad_memo.value='';
        														ajax_hideTooltip(); return false;" ><?php echo $row->N_PAIS; ?></a></td>          
  </tr>
              <?php } 
              ?>
</table>
 
