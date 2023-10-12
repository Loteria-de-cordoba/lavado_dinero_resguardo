<img src="image/icon.png" width="32" height="32" />
<?php
session_start();

$archivo = explode('.',$_FILES['filefieldname']['name']);
if ( strtolower($archivo[1])!='jpg' && strtolower($archivo[1])!='jpeg' && strtolower($archivo[1])!='png' && strtolower($archivo[1])!='gif') {
	?>
	<script>	
		alert('El archivo no tiene formato conocido: jpg, jpeg, gif o png');
		parent.document.getElementById('filefieldname').value = '';
	</script>
	<?
	die();
}
$carpeta = session_id();
$target_path = "upload/$carpeta/";
mkdir("upload/$carpeta",0777);

$target_path = $target_path . basename( $_FILES['filefieldname']['name']); 

if(move_uploaded_file($_FILES['filefieldname']['tmp_name'], $target_path)) {
   // echo "The file ".  basename( $_FILES['uploadedfile']['name'])." has been uploaded";
} else{
    //echo "There was an error uploading the file, please try again!";
}?>
<script>
<?php $files=scandir("upload/$carpeta");
for ($i=0;$i<count($files);$i++) { 
	if ($files[$i]!='.' && $files[$i]!='..'){
		if (strstr(strtolower($files[$i]),'.jpeg') || strstr(strtolower($files[$i]),'.png') || strstr(strtolower($files[$i]),'.jpg') || strstr(strtolower($files[$i]),'.gif')) {
			$cadena.= "<a href=\"upload/$carpeta/".$files[$i]."\" alt=\"Click para ver Imagen\" title=\"Click para ver Imagen\"><img src=\"upload/$carpeta/".$files[$i]."\" width=\"58\" height=\"40\" border=\"0\"/></a> <a href=\"upload/$carpeta/".$files[$i]."\" alt=\"Click para ver Imagen\" title=\"Click para ver Imagen\">".$files[$i]."</a> <a href=\"eliminar.php?archivo=".$files[$i]."\" target=\"hiddenframe\" alt=\"Eliminar\" title=\"Eliminar\"><img src=\"image/drop.gif\" width=\"32\" height=\"32\" border=\"0\"/></a><br>";
		} else {
			$cadena.= "<a href=\"upload/$carpeta/".$files[$i]."\" alt=\"Click para ver Documento\" title=\"Click para ver Documento\"><img src=\"image/Document.png\" width=\"32\" height=\"32\" border=\"0\"/></a> <a href=\"upload/$carpeta/".$files[$i]."\"  alt=\"Click para ver Documento\" title=\"Click para ver Documento\">".$files[$i]."</a> <a href=\"eliminar.php?archivo=".$files[$i]."\" target=\"hiddenframe\" alt=\"Eliminar\" title=\"Eliminar\"><img src=\"image/drop.gif\" width=\"32\" height=\"32\" border=\"0\"/></a><br>";
		}
	}
	?>
	//alert (parent.document.getElementById('filefieldname'));  
	parent.document.getElementById('filefieldname').value = '';
	parent.document.getElementById('uploadedfile').innerHTML = '<?php echo $cadena; ?>';
<?php } ?>
parent.document.getElementById('cant_archivos').value = parseInt(parent.document.getElementById('cant_archivos').value) + 1;
</script>