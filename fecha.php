<?php session_start(); ?>
<?php 
if(basename($_SERVER['PHP_SELF'])=="fecha.php") {
	include("jscalendar-1.0/calendario.php");
}
if (isset($_GET['fecha'])) {
	$_SESSION['fecha'] = $_GET['fecha'];
} elseif (isset($_POST['fecha'])) {
	$_SESSION['fecha'] = $_POST['fecha'];
} else {
	$_SESSION['fecha'] = date("d/m/Y");
}
?>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
<table border="0" cellspacing="2">
  <tr class="small">
    <td scope="col">Fecha Valor: <?php  abrir_calendario_ajax_get('fecha','form_fecha', $_SESSION['fecha'],'contenido','adm_premio.php'); ?></td>
    <td scope="col"><a href="index.php">Regresar a hoy</a></td>
  </tr>
</table>