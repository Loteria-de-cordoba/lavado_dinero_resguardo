<?php session_start(); 
$archivo = $fecha.'.zip';
exec('zip -r '.$archivo.' ./');
//$x= getcwd()."/".$archivo;
?>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<table width="50%" align="center">
	<tr><td></td></tr>
</table>

<table width="420" height="144" border="1" align="center" bordercolor="#000066" cellspacing="0">
	<tr>	
		<td>
            <table width="420" height="144" border="0" align="center" cellspacing="0" class="cuadro_mensaje">
		  		<tr>
					<td><p style=color:"#FFFFFF" align="center">El archivo se genero correctamente</p></td>	
				</tr>
				<tr>
                    <td align="center">
                        <a href="premio/xml_zipear_abrir.php?archivo=<?php echo $archivo ; ?>" target="_blank" style="text-decoration:none; color:#FFFFFF">
                            <img src="image/download.gif" border="0" /><br />Descargar Archivos
                        </a>
                    </td>
				</tr>
			</table>
	  </td>
	</tr>
</table>
