<?php
/*
	@Autor:
	@Razon:
	@Modificaciones:
		1:
		@@Autor: Broda Favio Noel
		@@Fecha: 20/04/2012
		@@Descripción:
			1. Se añadió el boton para ver en PDF.
			2. Se añadió el texto "ANULADO" en caso que así sea.
			3. Mejorada la indentación
*/
session_start(); 
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
include_once("../jscalendar-1.0/calendario.php");

$casa			= $_GET['casa'];
$cod_casa		= $_GET['cod_casa'];
$cod_mov_caja	= $_GET['cod_mov_caja'];
$mayora			= (int)$_GET['mayora'];

//$db->debug=true;

//ACTUALIZACION DE CONSULTAS POR CENTRALIZACION DE EQUIPOS LEY 9505 - VHP

try{
	$rstranferencias_ant=$db->Execute("SELECT casa, to_char(fecha,'dd/mm/yyyy') AS fecha,
										TO_CHAR(fecha_alta, 'dd/mm/yyyy') AS fecha_cont, cod_mov_caja,
										  importe_ficha, importe_impuesto, importe_plata, nombre, cod_casa,
										  DECODE(anulado,'S', 1, '') AS anulado, NVL(hora, '') AS hora
								   FROM casino.t_reg_cp
								   WHERE cod_mov_caja = ?
								   AND cod_casa = ?", array($_GET['cod_mov_caja'], $_GET['cod_casa']));
}catch(exception $e){ 
	die($db->ErrorMsg());
}

//DESPUES
try{
				$rstranferencias=$db->Execute("SELECT distinct 
				ca.descripcion   AS casa,
				TO_CHAR(a.fecha_hora, 'dd/mm/yyyy') AS fecha,
				TO_CHAR(a.fecha, 'dd/mm/yyyy') AS fecha_cont,
				a.cod_mov_caja,
				a.importe_ficha,
				a.importe_plata,
				USU.nombre                                 AS NOMBRE,
				a.importe_impuesto,
				ca.cod_casa      AS cod_casa,
				DECODE(a.anulado, 'S', 1, '') AS anulado,
				TO_CHAR(a.fecha_hora, 'HH24:MI') AS hora
			FROM
				casino_pc.mov_caja_cp   a,
				casino_pc.usuario       usu,
				casino_pc.casa          ca,
				casino_pc.caja          caja,
				casino_pc.moneda        moneda
			WHERE
				a.cod_usuario = usu.cod_usuario
				AND usu.cod_casa = ca.cod_casa
					AND a.cod_caja = caja.cod_caja
											   AND cod_mov_caja = ?
											   AND CA.cod_casa = ?", array($_GET['cod_mov_caja'], $_GET['cod_casa']));
			}catch(exception $e){ 
				die($db->ErrorMsg());
			}

							$tengo_elementos=$rstranferencias->RowCount();
							$tengo_elementos_ant=$rstranferencias_ant->RowCount();
							//die($tengo_elementos.'   y antes     '.$tengo_elementos_ant);
							
							if($tengo_elementos==0)//funciona con la consulta nueva
								{
									$rstranferencias=$rstranferencias_ant;	
								}
 
$rowtra = $rstranferencias->FetchNextObject($toupper=true);
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
<br>
<center>
	<a onclick="window.open('list/impresion_ticket.php?cod_casa=<?php echo $cod_casa; ?>&amp;cod_mov_caja=<?php echo $cod_mov_caja; ?>','Ventana','width=500,height=500,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')" href="#">
		<img width="20" height="20" border="0" alt="Imprimir" src="image/24px-Crystal_Clear_app_printer.png">
	</a>
</center> 

<table width="350" align="center" border="0"> 
	<tr>
		<td colspan="2">
			<div align="center">
				<p><strong>LOTERIA DE CORDOBA S.E.</strong></p>
			</div>
		</td>      
	</tr>

	<tr>
		<td colspan="2" align="center"><strong>Casinos Provinciales</strong></td>
	</tr>

	<tr>
		<td colspan="2" align="center">---------------------------------------------------------</td>
	</tr>

	<tr>
		<td colspan="2" align="center"><span class="style2">Aporte Ley Nro.: 9505</span></td>
	</tr>

	<tr>
		<td colspan="2" align="center">---------------------------------------------------------</td>
	</tr>

	<tr>
		<td colspan="2" align="left">Casino: <?php echo $rowtra->CASA; ?></span></span></td>
	</tr>
	   
	<tr>
		<td colspan="2" align="left">Cajero: <span class="style1"><?php echo $rowtra->NOMBRE; ?></span></td>
	</tr>

	<tr>
		<td width="60%" align="left">Fecha: <?php echo $rowtra->HORA ? $rowtra->FECHA.' - '.$rowtra->HORA.'hs' : $rowtra->FECHA ?></td>
        
		<td width="40%" align="right">Nro. Ticket: <?php echo $rowtra->COD_MOV_CAJA; ?></span></td>
	</tr>
    <tr>
		<td width="60%" align="left"><?php echo '(F. Cont. '.$rowtra->FECHA_CONT.')' ?></td>
    </tr>
	<tr>
		<td colspan="2" align="right">&nbsp;</td>
	</tr>

	<tr>
		<td align="left">Valor recibido en fichas:</td>
		<td align="right">$<?php echo number_format($rowtra->IMPORTE_FICHA,2,',','.'); ?></td>
	</tr>

	<tr>
		<td align="left">Aporte Ley 9505:</td>
		<td align="right">-$<?php echo number_format($rowtra->IMPORTE_IMPUESTO,2,',','.'); ?></td>
	</tr>
	
	<tr>
		<td colspan="2" align="right" style="font-size:5px;">______________________________________</td>
	</tr>

	<tr>
		<td align="left">Importe  en dinero:</td>
		<td align="right">$<?php echo number_format($rowtra->IMPORTE_PLATA,2,',','.'); ?></td>
	</tr>

	<tr>
		<td colspan="2" align="center">---------------------------------------------------------</td>
	</tr>

	<!--<tr>
		<!--<td colspan="2" align="left" class="texto5">COMPROBANTE NO VALIDO COMO FACTURA</td>
        <td colspan="2" align="justify" class="texto5">Se    deja constancia  que  el presente  ticket NO ACREDITA   GANANCIA,  tampoco   constituye  una declaraci&oacute;n   de origen y licitud de fondos para toda  materia  prevista en  la Ley  N&deg; 25.246   y  normativa complementaria  de los organismos de  control pertinentes,  constituyendo un simple comprobante de la retenci&oacute;n  del art. 16 inc. 2 Ley N&deg; 9505.</td>
	</tr>-->
	
    <tr>
		<td colspan="2" align="center" class="texto5">COMPROBANTE NO VALIDO COMO FACTURA</td>
        <td colspan="2" align="left" class="texto5"> </td>
	</tr>
    
	<tr>
		<td colspan="2" align="center">---------------------------------------------------------</td>
	</tr>

	<?php
	if($rowtra->ANULADO){
	?>
		<tr>
			<td colspan="2"><center><span style="color:#F00">A N U L A D O</span></center></td>
		</tr>
	<?php  
	}
	?>
</table>

<div align="center">
	<a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $casa ?>&mayora=<?php echo $mayora; ?>');"><img src="image/regresar.png" width="27" height="24" border="0"/></a>
    <a href="#" onclick="ajax_get('contenido','detalle_casino_cp.php','fecha=<?php echo $_GET['fdesde'] ?>&fhasta=<?php echo $_GET['fhasta'] ?>&cod_casa=<?php echo $casa ?>&mayora=<?php echo $mayora; ?>');">Regresar</a>
</div>
  