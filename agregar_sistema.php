<?php session_start();?>
<?php include("../funcion.inc.php");?>
<?php include($_SESSION['conexionBase']);?>
<?php
try {
	$rs_sucursal = $db->Execute("select id_casa as codigo, descripcion from superusuario.casas ");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
try {
	$rs_depende = $db->Execute("select id_area as codigo, descripcion from superusuario.areas ");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<link href="../estilo.css" rel="stylesheet" type="text/css" />
<br>
<br>
<br><form name="form1" method="post" action="#" onSubmit="validar_sistema('contenido','../sistemas/procesar_alta_sistema.php',this,this.descripcion,this.esquema,this.url); return false;">
  <table width="100%" border="1">
    <tr>
      <td colspan="2" align="left" class="texboxtitulo" scope="row">Alta de sistemas</td>
    </tr>
    <tr>
      <td width="39%" align="right" class="texboxchico" scope="row">Nuevo Sistema*</td>
      <td width="61%" class="td_detalle"><input name="descripcion" type="text" class="small" id="descripcion" size="45" maxlength="50" /></td>
    </tr>
    <tr>
      <td align="right" class="texboxchico" scope="row">Esquema*</td>
      <td class="td_detalle"><input name="esquema" type="text" class="small" id="esquema" size="45" maxlength="100" /></td>
    </tr>
    <tr>
      <td align="right" class="texboxchico" scope="row">URL*</td>
      <td class="td_detalle"><input name="url" type="text" class="small" id="url" size="45" maxlength="50" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" scope="row"><input name="alta" type="submit" value="Agregar" class="small" /></td>
    </tr>
  </table>
</form>
<a href="#" class="td5" onclick="ajax_get('sistema','../blanco.php','')"><img src="../imagenes/b_drop.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> Cerrar</a><a href="#" class="td5" onClick="ajax_get('sistema','blanco.php','')"></a>
<br>
<br>