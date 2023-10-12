<?php 
session_start();
/**
Program by: Sajith.M.R
contact me: admin@sajithmr.com
*/ 
?>
<form  target="hiddenframe" enctype="multipart/form-data" action="upload.php" method="POST" name="uploadform" id="uploadform">
  Adjuntar Documentacion al servidor:<br />
  <input name="filefieldname" type="file" id="filefieldname"   onchange="document.uploadform.submit()" size="40"/>
  <p id="uploadedfile" >
  <?php 
	 function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
    }
	/*
	$carpeta = session_id();
	if (!mkdir("upload/$carpeta",0700)) {echo "El directorio de la session ya existe!...<br>";};
  	$files=scandir("upload/$carpeta");
	for ($i=0;$i<count($files);$i++) { 
		if ($files[$i]!='.' && $files[$i]!='..'){
			$cadena.= "<a href=\"upload/".$files[$i]."\">".$files[$i]."</a><br>";
		}
 	}
	echo $cadena;
	*/
	$carpeta = session_id();
	deleteDirectory("upload/$carpeta");
 ?>
  </p>
<iframe name="hiddenframe" style="display:none" >Loading...</iframe>
</form>