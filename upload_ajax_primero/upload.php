<?php /**
Program by: Sajith.M.R
contact me: admin@sajithmr.com
*/ 
session_start();
$sesion_id=session_id();
$target_path = "upload/";
$archivo=$sesion_id.basename($_FILES['filefieldname']['name']);
$target_path = $target_path . $archivo; 
//echo $_FILES['filefieldname']['name'];

//print_r($archivo); die();
?>
<script>

//alert ('File: <?php //print_r($archivo) ?>');

</script>

<?php
if(move_uploaded_file($_FILES['filefieldname']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
    " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}?>
<script>
function eliminar(clave){
	var puntero = document.getElementById(clave);
	puntero.removeChild();
}
var nuevoelemento = document.createElement("p");
var texto = document.createTextNode("<?php echo $_FILES['uploadedfile']['name'] ?>");
nuevoelemento.appendChild(texto);
var puntero = parent.document.getElementById("uploadedfile");
puntero.appendChild(nuevoelemento);
//parent.document.getElementById('uploadedfile').innerHTML += '<p id="<?php //echo $archivo ?>"><?php //echo $_FILES['filefieldname']['name']; ?><a href="#" onclick="eliminar(<?php //echo $archivo; ?>); return false;">Eliminar</a>';
//parent.document.getElementById('uploadedfile').innerHTML += '<br><div id="<?php //echo $archivo ?>"><?php //echo $_FILES['filefieldname']['name'] ?><a href="#" onclick="alert(\'<?php //echo $archivo ?>\')">Eliminar</a></div>';
</script>
