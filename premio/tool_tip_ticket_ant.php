<?php session_start(); 
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
include_once("../jscalendar-1.0/calendario.php");
//print_r($_GET);
//die();
$cod_casa=$_GET['cod_casa'];
$cod_mov_caja=$_GET['cod_mov_caja'];
$mayora = (int)$_GET['mayora'];




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
.style2 {font-size: 12px}
.style3 {font-size: 14px}
-->
</style>
<form id="form" name="form" onsubmit="ajax_post('contenido','detalle_casino_cp.php',this); return false;">

<?php while ($rowtra= $rstranferencias->FetchNextObject($toupper=true))
   {?>
<table width="33%" align="center" border="0"> 
 <tr>
    <td colspan="2" ><div align="center">
      <p><strong>LOTERIA DE CORDOBA S.E. </strong></p>
      </div></td>      
  </tr>
 
   
 <tr>
   <td colspan="2" align="center" ><strong>Casinos Provinciales</strong></td>
 </tr>
 <tr>
   <td colspan="2" align="rigth" >-------------------------------------------------------</td>
 </tr>
 <tr>
   <td colspan="2" align="center" ><span class="style2">Aporte Ley Nro. 9505</span></td>
 </tr>
 <tr>
   <td colspan="2" align="rigth" >-------------------------------------------------------</td>
 </tr>
 <tr>
          <td colspan="2" align="left" style="" >Casino:  <?php echo $rowtra->CASA;  ?></span></span></td>
  </tr>

   
<tr>
        <td colspan="2" align="left" >Cajero:  <span class="style1"><?php echo $rowtra->NOMBRE;  ?></span></td>
  </tr>
<tr>
        <td width="50%" align="left" >Fecha:  <?php echo $rowtra->FECHA;  ?></td>
        <td width="50%" align="left" >Nro.Ticket: <?php echo $rowtra->COD_MOV_CAJA;  ?></span></td>
</tr>  
<tr>
        <td colspan="2" align="rigth" >&nbsp;</td>
  </tr>
<tr>
        <td align="left" ><div align="right">Valor recibido en fichas</div></td>
    <td align="left" ><span > $<?php echo $rowtra->IMPORTE_FICHA;  ?></span></td>
</tr>  

<tr>
        <td align="rigth" ><div align="right">Aporte Ley 9505</div></td>
        <td align="rigth" ><span >-$<?php echo $rowtra->IMPORTE_IMPUESTO;  ?></span></td>
</tr> 
<tr>
  <td align="rigth" ><div align="right">Importe  en dinero</div></td>
  <td align="rigth" ><span > $<?php echo $rowtra->IMPORTE_PLATA; ?></span></td>
</tr>
<tr>
  <td colspan="2" align="rigth" >-------------------------------------------------------</td>
</tr>
<tr>
  <td colspan="2" align="left" class="texto5" >COMPROBANTE NO VALIDO COMO FACTURA</td>
</tr>
<tr>
        <td colspan="2" align="rigth" >-------------------------------------------------------</td>
  </tr> 

<?php  

$casa=$_GET['casa'];
 
  }?>
</table>
<div align="center"><a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $casa ?>&mayora=<?php echo $mayora; ?>');"><img src="image/regresar.png" width="27" height="24" border="0"  /></a>
    <a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $casa ?>&mayora=<?php echo $mayora; ?>');">Regresar</a>
   <!-- <img src="image/24px-Crystal_Clear_app_kjobviewer.png" width="27" height="24" border="0" onclick="window.open('list/lista_ticket_casino.php?cod_mov_caja=<?php echo $cod_mov_caja; ?>&cod_casa=<?php echo $cod_casa; ?>', '_blank', 'height=480, width=640, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=yes, scrollbars=yes')"/> <span class="small">Imprimir registros </span></span>    </div>-->
    </div>
</div>
  