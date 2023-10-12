<?php /**

Program by: Sajith.M.R
contact me: admin@sajithmr.com
*/ 
session_start();
?>
<form  target="hiddenframe" enctype="multipart/form-data" action="upload.php" method="POST" name="uploadform">
<p>
  
  Adjuntar archivos:
  <input type="file" name="filefieldname" id="fileField"   onchange="document.uploadform.submit()"/>
  </label>
</p>
<div id="uploadedfile"></div>
<iframe name="hiddenframe" style="display:none" >Cargando...</iframe>
</form>
<p>&nbsp; </p>


