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
	//$array_fecha = FechaServer();
	//$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

	//$fecha_desde = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	//$fecha_hasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];


	$fecha=$_REQUEST['fecha'];
	$id_id=$_REQUEST['idid'];
	/*$fecha_cedula=$_REQUEST['fecha_cedula'];
	$fecha_vto=$_REQUEST['fecha_vto'];*/
	//print_r($_REQUEST);
	//$db->debug=true;
	//die();
	
	//selecciono datos de este registro
try {
			$rs_datos = $db ->Execute("SELECT us.descripcion as apenom,
					 				us.novedad as novedad,
									TO_CHAR(us.fecha_cedula,'DD/MM/YYYY') as fedula,
									vto as vto
					 FROM PLA_AUDITORIA.denegado US
					WHERE us.id_denegado=?
					", array($id_id));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_datos =$rs_datos->FetchNextObject($toupper=true);
			$apenom=$row_datos->APENOM; 
			$novedad=$row_datos->NOVEDAD;
			$fechacedula=$row_datos->FEDULA; 
			$vto=$row_datos->VTO; 

?>

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />    
  <style type="text/css">
<!--
.style1 {
	color: #006600;
	font-family: Arial, Helvetica, sans-serif;
}
.style5 {font-size: 10px}
-->
  </style>
  <form name="form1" id="form1" method="post" action="#" onSubmit="ajax_post('contenido','denegado/denegado_eliminar_grabar.php',this); return false;">
 	<?php //  if(!isset($_GET['cantidad']))
	//{?>
    <table width="62%" height="32" align="center" style="background-color:#999966">
<tr>
<td width="84%" align="left">Fecha Contable:<?php echo $fecha;?></td>
<td width="16%">
      <div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Regresar" width="16" height="16" border="0" align="absbottom" /><a href="#"  onclick="ajax_get('contenido','denegado/adm_denegado.php','fecha=<?php echo $fecha ?>&idid=<?php echo $id_id ?>')">Regresar</a></div></td>
</tr>
</table>



<table width="62%" height="53"  border="2" align="center">

	<tr>
                    
        <td align="center"  colspan="2"  style="background-color:#999966">ELIMINACION DE CEDULAS U.I.F.</td>
    </tr>
     
    <tr>
      <td width="349"    scope="col" style="background-color:#999966">Observaci&oacute;n Final:</td>
      <td width="331"><textarea name="observafinal" class="small"  id="observafinal"  rows="2" cols="70"/><?php echo $observa_final;?></textarea></td>
    </tr>
      
           
      <tr>
        <td colspan="4" align="center" style="text-align:center; background-color:#999966"><div style="text-align:center;margin-top:25px;margin-bottom:15px;">
            <input name="fecha" id="fecha" type="hidden" value="<?php echo $fecha;?>"/>
            <input name="novedad" id="novedad" type="hidden" value="<?php echo $novedad;?>"/>
            <input name="idid" id="idid" type="hidden" value="<?php echo $id_id;?>"/>
             <input name="vto" id="vto" type="hidden" value="<?php echo $vto;?>"/>
             <input name="fecha_cedula" id="fecha_cedula" type="hidden" value="<?php echo $fechacedula;?>"/>
          <input name="buttonx" class="smallTahoma" id="buttonx" style="font-size:11px;color:#333333;font:bold" value="Eliminar" type="submit"/>
        </div></td></tr>
        </table>
  </form>
        
        