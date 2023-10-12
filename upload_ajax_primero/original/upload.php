<?php /**

Program by: Sajith.M.R
contact me: admin@sajithmr.com
*/ ?>


<?php
$target_path = "upload/";

$target_path = $target_path . basename( $_FILES['filefieldname']['name']); 

if(move_uploaded_file($_FILES['filefieldname']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
    " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}?>
<script>

parent.document.getElementById('uploadedfile').innerHTML += '<br><a href="upload/<?php echo $_FILES['filefieldname']['name'] ?>"><?php echo $_FILES['filefieldname']['name'] ?></a>';
</script>
