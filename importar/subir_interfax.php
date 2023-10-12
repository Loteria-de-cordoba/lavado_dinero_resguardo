<?php session_start(); ?>

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<link href="../tooltip/css/ajax-tooltip.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../tooltip/js/ajax-dynamic-content.js"></script>
<script type="text/javascript" src="../tooltip/js/ajax.js"></script>
<script type="text/javascript" src="../tooltip/js/ajax-tooltip.js"></script>	
<script language="javascript" src="../funcion2.js"></script>

<?php
include("../funcion.inc.php");
include("../db_conecta_adodb.inc.php");
$array_fecha = FechaServer();
$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
$pasar = 1;

if ($_POST['Procesar']) {
	//	print_r($_POST);
	if ($HTTP_POST_FILES['archivo']['name'] == "") {
		$pasar = 0;
		$msj = "Debe seleccionar un archivo";
		echo "<script>window.open('../alerta.php?msjalerta=$msj', 'ee', 'left= 200, top= 200, height=150, width=400, toolbar=no, menubar=no, titlebar=no, resizable=no, scrollbars=no');</script>";
		} 

	if ($HTTP_POST_FILES['archivo']['name'] <> "get_data.htm") {
		$pasar = 0;
		$msj = "El archivo seleccionado no es correcto";
		echo "<script>window.open('../alerta.php?msjalerta=$msj', 'ee', 'left= 200, top= 200, height=150, width=400, toolbar=no, menubar=no, titlebar=no, resizable=no, scrollbars=no');</script>";
		} 

	if ($_POST['hs'] == 0 and $_POST['min'] == 0) {
		$pasar = 0;
		$msj = "Debe ingresar la hora del alerta";
		echo "<script>window.open('../alerta.php?msjalerta=$msj', 'ee', 'left= 200, top= 200, height=150, width=400, toolbar=no, menubar=no, titlebar=no, resizable=no, scrollbars=no');</script>";
		} 
		
	if ($pasar == 1){
		$fecha = $_POST['fecha']; $hs = str_pad($_POST['hs'],2,"0",STR_PAD_LEFT); $min = str_pad($_POST['min'],2,"0",STR_PAD_RIGHT); 
		$fecha_arch = $array_fecha["year"].str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT);
		$archivo= $fecha_arch  . $hs . $min . $HTTP_POST_FILES['archivo']['name'];
		move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], $archivo);
		include("subir_alertas_grabar.php");
		include("../blanco.php");
		die();
		}
	}
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<!--<form action="#" onSubmit="window.open('procesando.php?dealertas=1', 'ee', 'height=100, width=100, toolbar=no, menubar=no, titlebar=no, resizable=no, scrollbars=no'); 
                ajax_post('inicio','alertas/subir_alertas_grabar.php',this); return false;" method="post" name="formalertas" >-->
<form action="subir_alertas_interfax.php" method="post" name="formSubir" class="style2" enctype="multipart/form-data" >                
    <table width="30%" border="0" align="center">
        <tr align="center" class="smallAzulBarraH" >
          <td align="center"><strong> Subir Archivo</strong></td>
      </tr>
        <tr align="left" class="th2">
        	<td align="center" ><strong>Seleccione Archivo</strong></td>
        </tr>
        <tr align="left" class="th2">
        	<td align="center" ><input name="archivo" type="file" class="small" id="archivo" size="50" /></td>
        </tr>
        <tr class="th2">
          <td align="center"><input name="Procesar" type="submit" class="small" id="Procesar" value="Aceptar" /></td>
        </tr>
    </table>
</form> 
