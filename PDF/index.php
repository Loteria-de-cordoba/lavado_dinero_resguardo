<script language="JavaScript">
window.moveTo(0,0);
window.resizeTo(screen.width,screen.height);
</script>
<body onLoad="window.moveTo(0,0);window.resizeTo(screen.width,screen.height);">
<?php
$temporal_nombre='';
$archivos=(glob(getcwd().'/'.'*.pdf'));
//print_r($archivos);
foreach($archivos as $key=>$archivo){
$temporal_nombre=substr(strrchr($archivo, "/"),1);
if($temporal_nombre=='02_Manual PLA  (8).pdf')
{
$temporal_nombre='Manual PLA  (8).pdf';
}
//$nombre=substr($archivo,0,strripos($archivo,
//echo '<a target="_blank" href="'.str_replace($_SERVER["DOCUMENT_ROOT"],'',getcwd()).strrchr($archivo, "/").'">'.substr(strrchr($archivo, "/"),1).'</a> <br />';
echo '<a href="'.str_replace($_SERVER["DOCUMENT_ROOT"],'',getcwd()).strrchr($archivo, "/").'">'.$temporal_nombre.'</a> <br />';
}
?>