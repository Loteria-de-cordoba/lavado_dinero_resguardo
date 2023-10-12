<?php 
session_start();
//print_r($_POST);
include("../db_conecta_adodb.inc.php");
?>	 
<link href="../estilo/pedidos.css" rel="stylesheet" type="text/css" />
<form name="iform" id="iform" action="subir_archivo_resguardo.php" method="post" enctype="multipart/form-data">   
<table style="border:groove"  width="86%">
	<tr>
       <td class="h1"  width="64%"> Elija archivo</td>
    </tr>
    <tr>
        <td><input size="80" class="textbox" name="adjunto" type="file"   id="adjunto"/></td>
    </tr>
    <tr>
      <td>
                        <input name="enviar" type="submit" id="enviar" value="Adjuntar" />
                        <a href="../blanco.php"> &nbsp;Salir</a>


                        <input type="hidden" id="esquema"  name="esquema"  value="<?php echo $_GET['esquema']; ?>" >
                        <input type="hidden" id="tabla"    name="tabla"    value="<?php echo $_GET['tabla']; ?>" >
                        <input type="hidden" id="id_tabla" name="id_tabla" value="<?php echo $_GET['id_tabla']; ?>" >
      </td>
            </tr>
</table>
</form>