<?php session_start(); 
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
include_once("../jscalendar-1.0/calendario.php");
//print_r($_GET);
//die();
$cod_casa=$_GET['cod_casa'];
$cod_mov_caja=$_GET['cod_mov_caja'];




//$db->debug=true;

 try
{
	    $rstranferencias=$db->Execute(" select casa, to_char(fecha,'dd/mm/yyyy') as fecha, cod_mov_caja, importe_ficha, importe_impuesto, importe_plata, nombre, cod_casa 
		                                from casino.t_reg_cp
										where cod_mov_caja=?
										and cod_casa=?", array($_GET['cod_mov_caja'],$_GET['cod_casa']));
}
catch  (exception $e) 
{ 
			die($db->ErrorMsg());
}
 


 ?>

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-size: 10px}
-->
</style>
<form id="form" name="form" onsubmit="ajax_post('contenido','detalle_casino_cp.php',this); return false;">

<?php while ($rowtra= $rstranferencias->FetchNextObject($toupper=true))
   {?>
<table width="28%" align="center" border="1"> 
 <tr>
    <td colspan="2" class="td_nuevo"><div align="center">
      <p><strong>LOTERIA DE CORDOBA S.E. </strong><strong>Casinos Provinciales</strong></p>
    </div></td>
  </tr>
   <td colspan="2" class="td_nuevo"><div align="center"><strong>Aporte ley Nro. 9505</strong></div></td>
<tr>
          <td width="47%" align="center" class="td"><strong>Casino</strong></td>
          <td width="53%" align="center" class="td"><span class="div_carga"><?php echo $rowtra->CASA;  ?></span></span></td>
  </tr>

   
<tr>
        <td align="center" class="td2">Cajero</td>
    <td align="center" class="td2"><span class="style1"><?php echo $rowtra->NOMBRE;  ?></span></td>
  </tr>
<tr>
        <td align="center" class="td2">Fecha</td>
    <td align="center" class="td2"><span class="style1"><?php echo $rowtra->FECHA;  ?></span></td>
  </tr>  
<tr>
        <td align="center" class="td2">Nro.Ticket:</td>
    <td align="center" class="td2"><span class="style1"><?php echo $rowtra->COD_MOV_CAJA;  ?></span></td>
  </tr>
<tr>
        <td align="center" class="td2">Importe Pagado a Ganador</td>
    <td align="center" class="td2"><span class="style1">$<?php echo $rowtra->IMPORTE_PLATA; ?></span></td>
  </tr>  

<tr>
        <td align="center" class="td2">Aporte Ley 9505</td>
    <td align="center" class="td2"><span class="style1">$<?php echo $rowtra->IMPORTE_IMPUESTO;  ?></span></td>
  </tr> 
<tr>
        <td align="center" class="td2">Importe  en Fichas</td>
    <td align="center" class="td2"><span class="style1">$<?php echo $rowtra->IMPORTE_FICHA;  ?></span></td>
</tr> 

<?php  

$casa=$_GET['casa'];
 
  }?>
</table>
<div align="center"><a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $casa ?>');"><img src="image/regresar.png" width="27" height="24" border="0"  /></a>
    <a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $casa ?>');">Regresar</a>
    <img src="image/24px-Crystal_Clear_app_kjobviewer.png" width="27" height="24" border="0" onclick="window.open('list/lista_ticket_casino.php?cod_mov_caja=<?php echo $cod_mov_caja; ?>&cod_casa=<?php echo $cod_casa; ?>', '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')"/> <span class="small">Imprimir registros </span></span>    </div>
    </div>
</div>
  