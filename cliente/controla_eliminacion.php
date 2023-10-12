<?php 
session_start();
include("../db_conecta_adodb.inc.php");
//print_r($_GET);
$pepe=utf8_encode($_GET['apellido'].', '.$_GET['nombre']);
$apostador=$_GET['id_cliente'];
//$db->debug=true;
$casino=$_GET['casino'];
$fecha=$_GET['fecha_inicio'];
$fhasta=$_GET['fhasta'];
$kasino='';
$kasinoant='';
//print_r($_GET);
//die();
try {
$rs_fecha_hoy = $db ->Execute("select to_char(sysdate,'dd/mm/yyyy') as fecha from dual");}
	catch (exception $e){die ($db->ErrorMsg());} 
	$row_fecha_hoy =$rs_fecha_hoy->FetchNextObject($toupper=true);
	$fechita=$row_fecha_hoy->FECHA;
	//echo $fechita;
	//die();

try {
			$rs_busca_movi = $db ->Execute("select decode(nc.id_casino_novedad,100,'Delegacion',cc.n_casino) as kasino
											 from PLA_AUDITORIA.t_novedades_cliente nc,
											 casino.t_casinos cc
												where nc.id_casino_novedad=cc.id_casino(+)
												and nc.id_cliente=?
                    							and nc.fecha_novedad=to_date(?,'dd/mm/yyyy')
												", array($apostador, $fechita));}
											catch (exception $e){die ($db->ErrorMsg());} 
					$row_busca_movi =$rs_busca_movi->FetchNextObject($toupper=true);
					if($rs_busca_movi->RecordCount()<>0)
					{
					$kasino=$row_busca_movi->KASINO;
					}

if($kasino=='')
{

//controlo que no haya movimientos anteriores
try {
$rs_busca_movi_ant = $db ->Execute("select decode(nc.id_casino_novedad,100,'Delegacion',cc.n_casino) as kasinoant
											 from PLA_AUDITORIA.t_novedades_cliente nc,
											 casino.t_casinos cc
												where nc.id_casino_novedad=cc.id_casino(+)
												and nc.id_cliente=?
                    							and nc.fecha_novedad<to_date(?,'dd/mm/yyyy')
												", array($apostador, $fechita));}
											catch (exception $e){die ($db->ErrorMsg());} 
					$row_busca_movi_ant =$rs_busca_movi_ant->FetchNextObject($toupper=true);
					if($rs_busca_movi_ant->RecordCount()<>0)
					{
					$kasinoant=$row_busca_movi_ant->KASINOANT;
					}

if($kasinoant=='')
{
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<table width="45%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="texto4" scope="col">Elimina el apostador&nbsp;<?php echo $pepe?></a></td>
</tr>
  <tr valign="bottom" class="td8" >
	  <td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','cliente/cliente_eliminar_grabar.php','id_cliente=<?php echo $_GET['id_cliente'];?>&casino=<?php echo $_GET['casino'];?>&fecha=<?php echo $_GET['fecha_inicio']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>'); return false;"></td>      
      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('elimina','cliente/blanco.php','id_cliente=<?php echo $_GET['id_cliente'];?>&casino=<?php echo $_GET['casino'] ; ?>'); return false;"></td>  
      
    </tr>
</table>
<?php
}
else
{
//aqui va si tiene mov. anteriores
$mensaje=$pepe." Puede ser eliminado/a pero posee movimientos en fechas anteriores (".$kasinoant.")";
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<table width="65%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="texto4" scope="col"><?php echo $mensaje;?></a></td>
</tr>
  
	  
      <tr valign="bottom" class="td8" >
	  <td width="50%" align="center" valign="middle" class="td2"  scope="col"><input name="acepta" type="button" value="Aceptar" onClick="ajax_get('contenido','cliente/cliente_eliminar_grabar.php','id_cliente=<?php echo $_GET['id_cliente'];?>&casino=<?php echo $_GET['casino'];?>&fecha=<?php echo $_GET['fecha_inicio']; ?>&fhasta=<?php echo $_GET['fhasta']; ?>'); return false;"></td>      
      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cancela" type="button" value="Cancelar" onClick="ajax_get('elimina','cliente/blanco.php','id_cliente=<?php echo $_GET['id_cliente'];?>&casino=<?php echo $_GET['casino'] ; ?>'); return false;"></td>  
      
    
    </tr>
</table>
<?php
	
}
}
else
{
$mensaje=$pepe." No puede ser eliminado/a porque posee movimientos en la fecha (".$kasino.")";
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<table width="65%"  align="center">
<tr>
	<td colspan="9" align="center" valign="bottom" class="texto4" scope="col"><?php echo $mensaje;?></a></td>
</tr>
  <tr valign="bottom" class="td8" >
	  
      <td width="50%"  valign="middle" class="td2" scope="col" align="center"><input name="Cierra" type="button" value="Cerrar" onClick="ajax_get('elimina','cliente/blanco.php','id_cliente=<?php echo $_GET['id_cliente'];?>&casino=<?php echo $_GET['casino'] ; ?>'); return false;"></td>  
      
    </tr>
</table>
	
	<!--//header ("location:adm_cliente.php?casino=$casino&fecha=$fecha&fhasta=$fhasta&mensaje=$mensaje&apostador=$apostador");-->
<?php }
?>