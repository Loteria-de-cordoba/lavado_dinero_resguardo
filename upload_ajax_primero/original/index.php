<?php /**

Program by: Sajith.M.R
contact me: admin@sajithmr.com
*/ ?>
<form  target="hiddenframe" enctype="multipart/form-data" action="upload.php" method="POST" name="uploadform">
<p>
  
  Attach File:
  <input type="file" name="filefieldname" id="fileField"   onchange="document.uploadform.submit()"/>
  </label>
</p>
<p id="uploadedfile" >
  <label></label>
</p>
<iframe name="hiddenframe" style="display:none" >Loading...</iframe>
</form>
<p>&nbsp; </p>


