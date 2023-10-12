<img src="image/icon.png" width="32" height="32" />
<?php
session_start();
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
		if (strstr($files[$i],'.jpg') || strstr($files[$i],'.png')) {
			$cadena.= "<a href=\"../upload/$carpeta/".$files[$i]."\" alt=\"Click para ver Imagen\" title=\"Click para ver Imagen\"><img src=\"../upload/$carpeta/".$files[$i]."\" width=\"58\" height=\"40\" border=\"0\"/></a> <a href=\"../upload/$carpeta/".$files[$i]."\" alt=\"Click para ver Imagen\" title=\"Click para ver Imagen\">".$files[$i]."</a> <a href=\"../eliminar.php?archivo=".$files[$i]."\" target=\"hiddenframe\" alt=\"Eliminar\" title=\"Eliminar\"><img src=\"../image/drop.gif\" width=\"32\" height=\"32\" border=\"0\"/></a><br>";
		} else {
			$cadena.= "<a href=\"../upload/$carpeta/".$files[$i]."\" alt=\"Click para ver Documento\" title=\"Click para ver Documento\"><img src=\"../image/Document.png\" width=\"32\" height=\"32\" border=\"0\"/></a> <a href=\"../upload/$carpeta/".$files[$i]."\"  alt=\"Click para ver Documento\" title=\"Click para ver Documento\">".$files[$i]."</a> <a href=\"../eliminar.php?archivo=".$files[$i]."\" target=\"hiddenframe\" alt=\"Eliminar\" title=\"Eliminar\"><img src=\"../image/drop.gif\" width=\"32\" height=\"32\" border=\"0\"/></a><br>";
		}
	}
	?>
	parent.document.getElementById('uploadedfile').innerHTML = '<?php echo $cadena; ?>';
<?php } ?>
</script>