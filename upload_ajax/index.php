<?php session_start();
/**
Program by: Sajith.M.R
contact me: admin@sajithmr.com
*/ 
?>
<form  target="hiddenframe" enctype="multipart/form-data" action="upload.php" method="POST" name="uploadform">
  <table width="80%" border="0">
    <tr>
      <td width="50%"> Adjuntar Im&aacute;genes al Servidor:<br />
          <input name="filefieldname" type="file" id="filefieldname"   onchange="document.uploadform.submit();" size="20"/>
          <input name="cant_archivos" type="hidden" id="cant_archivos" value="0" />
      </td>
      <td width="50%"> <p id="uploadedfile" >&nbsp;</p></td>
    </tr>
  </table>
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
	$carpeta = session_id();
	deleteDirectory("upload/$carpeta");
 ?>
<iframe name="hiddenframe" style="display:none" >Loading...</iframe>
</form>