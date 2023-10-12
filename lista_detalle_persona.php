<?php session_start();
include ("db_conecta_adodb.inc.php");
include ("funcion.inc.php");
?>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
<?php 

try {
	$rs = $db->Execute("select sum(debe-haber) as saldo
						from cuenta_corriente.movimiento_detalle b 
						where b.suc_ban = ?
						and b.nro_agen = ?",array($_GET['suc_ban'],$_GET['nro_agen']));
	} catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
$row = $rs->FetchNextObject($toupper=true);
if($rs->RowCount()==0) {die ("<br>NO HAY MOVIMIENTOS "); }
?>
<table border="0" cellspacing="0">
  <tr> </tr>
  <tr>
    <td><table border="1" align="center" cellspacing="0">
      <tr class="td2">
        <td width="50%" class="td4">Agencia <? echo $_GET['nro_agen'];?></td>
        <td width="10%" align="center" bgcolor="#CCCCCC" class="td4"><strong>Saldo</strong></td>
        <td align="right" class="td4"><?php echo  number_format($row->SALDO,2,',','.');?></td>
      </tr>
    </table></td>
  </tr>
</table>
