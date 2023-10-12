<?php session_start();
include ("db_conecta_adodb.inc.php");
include ("funcion.inc.php");
$_SESSION['script'] =  basename($_SERVER['PHP_SELF']);	
?> 
<?php include("jscalendar-1.0/calendario.php"); ?>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css">
<?php function ConectarFTP(){
	//$servidor = "172.16.0.100";
	$servidor = "172.16.50.91";
	$puerto = 21;
	$timeout = 50;
	$user = "oracle10";
	$pass = "oracle10";
	//Obtiene un manejador del Servidor FTP
	$id_ftp=ftp_connect($servidor, $puerto, $timeout);
	//Se loguea al Servidor FTP
	ftp_login($id_ftp, $user, $pass); 
	//Devuelve el manejador a la funci�n
	return $id_ftp; 
}
$id_ftp=ConectarFTP(); 
if (ftp_chdir($id_ftp, "/home/cuenta_corriente")) {
   		//echo "El directorio actual es ahora: " . ftp_pwd($id_ftp) . "\n";
	} else {
   		//echo "No se pudo cambiar el directorio\n";
}
$lista = ftp_nlist($id_ftp, '.'); 

//Ordena la lista
sort($lista);
//Se leen todos los ficheros y directorios del directorio
?>
<br />
<table border="0" align="center" cellspacing="0">
  <tr>
    <td align="center" class="textoRojo" ><strong>IMPORTAR</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr valign="top">
     <td>
    <table border="1" cellspacing="0">
      <tr>
        <td align="center" class="td5">&nbsp;</td>
        <td align="center" class="td5">Archivos de Liquidaciones</td>
        <td align="center" class="td5">Fecha</td>
        </tr>
	  <?php $j=0; for ($i=0; $i<count($lista); $i++) { 
				  $item = $lista[$i];
			  if (strstr($item, '.') && strstr($item,'mv') && (!strstr($item,"mvbanco.999"))) {
				  $j++;
 				  $buff = ftp_rawlist($id_ftp, '.');
	  ?>
      <tr valign="top" class="<?php if ($i%2) { echo "td";}  else {echo "td8";}?>">
        <td align="center" ><?php for ($cant=0;$cant<=count($buff);$cant++) {
					  			if (strstr($buff[$cant],trim($item))) { echo $buff[$cant]; $permisos = $buff[$cant];}
				  				}?></td>
        <td align="center" ><?php echo $item ?></td>
        <td align="center" >
		    <?php if (substr($permisos,0,10)=="-rwxrwxrwx") { ?>
            <form action="#" method="post" enctype="multipart/form-data" name="formulario<?php echo $i; ?>" id="formulario<?php echo $i; ?>" onSubmit="ajax_post('contenido','AUDProcesarImportar.php',this); return false;">
	            <?php  abrir_calendario('fecha'.$i,'formulario'.$i, $_SESSION['fecha']); ?>
              <input type="hidden" name="archivo" id="archivo" value="<?php echo $item ?>"/>
              <input type="hidden" name="NroForm" id="NroForm" value="<?php echo $i ?>"/>
              <input type="submit" name="Submit" value="Importar" />
            </form>
            <?php } else { echo " Modifique los permisos para poder ejecutar!..."; }?>        
           </td>
        </tr>
      <?php } } ?>
    </table>
    </td>
  </tr>
</table>
<div align="center">
<?php if ($j==0) { die("<div align=\"center\"><span class=\"texto\">No existen liquidaciones para importar!...</span></div>"); }?>
<?php ftp_close($id_ftp); ?>