<?php
session_start();
$carpeta = session_id();
unlink("upload/$carpeta/".$_GET['archivo']);
?>
<script>
<?php $files=scandir("upload/$carpeta");
for ($i=0;$i<count($files);$i++) { 
	if ($files[$i]!='.' && $files[$i]!='..'){
		$cadena.= "<a href=\"../upload/$carpeta/".$files[$i]."\"><img src=\"../upload/$carpeta/".$files[$i]."\" width=\"58\" height=\"40\" border=\"0\"/></a> <a href=\"../upload/$carpeta/".$files[$i]."\">".$files[$i]."</a> <a href=\"../eliminar.php?archivo=".$files[$i]."\" target=\"hiddenframe\"><img src=\"../image/drop.gif\" width=\"32\" height=\"32\" border=\"0\"/></a><br>";
	}
	?>
	parent.document.getElementById('uploadedfile').innerHTML = '<?php echo $cadena; ?>';
<?php } ?>
</script>