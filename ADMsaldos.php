<?php session_start();
include ("db_conecta_adodb.inc.php");
include ("funcion.inc.php");
?>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
<br />
<table width="100%" border="0">
    <tr>
      <td>
      <form action="" method="post" enctype="multipart/form-data" name="formulario" id="formulario" onSubmit="ajax_post('contenido','index.php',this); return false;">
	  <table width="25%" border="0" align="center">
<tr>
              <td colspan="4" align="center" bordercolor="#FFFFFF" class="textoRojo"><?php $_SESSION['script'] =  basename($_SERVER['PHP_SELF']); ?> <strong>SALDOS</strong></td>
          </tr>
            <tr align="center" valign="top" class="texto">
              <td width="25%" bordercolor="#FFFFFF"><a href="#" onClick="ajax_get('contenido1','ADMagencias.php','');">Agencias</a></td>
              <!--<td width="25%" bordercolor="#FFFFFF"><a href="#" onClick="ajax_get('contenido1','ADMtitular_detalle.php','');">Titular</a></td>-->
              <!--<td width="25%" bordercolor="#FFFFFF"><a href="#" onClick="ajax_get('contenido1','ADMapoderado.php','');">Apoderado</a></td>-->
              <td width="25%" bordercolor="#FFFFFF"><a href="#" onClick="ajax_get('contenido1','ADMembargos.php','');">Consulta para Embargos </a></td>
          </tr>
  	  </table>
	  </form>
      </td>
    </tr>
  </table>
<div id="contenido1" ></div>