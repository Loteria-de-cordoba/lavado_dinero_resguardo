<?php session_start();
	//echo session_id();
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	include("../jscalendar-1.0/calendario.php");
	//print_r($_POST);
	//print_r($_GET);
	//die();
	//print_r($_SESSION);
	//$db->debug=true;
	
for($i=1;$i<500;$i++)
{
	if(isset($_GET['apostador'.$i]))
	{
		$soyapostador=$_GET['apostador'.$i];
		$nombretexto='textapostador'.$i;
	}
}
	if($soyapostador==0)
	{
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="textoRojo" scope="col"><input name="<?php echo $nombretexto;?>" class="small" type="text" id="<?php echo $nombretexto;?>" size="30" maxlength="30"/></td>
                   </tr>
     </table>
     <?php }
	 else
	 {
	 if($soyapostador<>-1)
	 {
			 try {
					$rs_soyyo = $db ->Execute("select  '**Reg. en ' || b.n_casino  as descripcion
												from PLA_AUDITORIA.t_cliente a,
												casino.t_casinos b
												where  b.id_casino=a.id_casino
												and a.id_cliente=?", array($soyapostador));
					}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
					catch (exception $e)
					{
					die ($db->ErrorMsg()); 
					}
					$row_myarea =$rs_soyyo->FetchNextObject($toupper=true);
					
						$yosoy=$row_myarea->DESCRIPCION; 
						
					
			 
			 
			 ?>
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td class="textoRojo" scope="col"><input name="<?php echo $nombretexto;?>" disabled="disabled" class="small" type="text" id="<?php echo $nombretexto;?>" size="30" maxlength="30" value="<?php echo $yosoy ?>"/></td>
						   </tr>
			 </table>
			 <?php }
	 }//pasa si no es -1?>
