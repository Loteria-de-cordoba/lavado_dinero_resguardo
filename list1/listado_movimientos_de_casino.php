<?php
/*
	@Autor: Broda Favio Noel
	@Razon: Creación del listado. Pedido por Mario Palenzona.
	@Modificaciones:
		@@Autor:
		@@Razon:
		@@Descripción:
*/
session_start();

include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
require("header_listado_horizontal.php");

$titulo = 'Movimientos de Casino Caja Publica';


if(isset($_GET['fecha'])) {
	$fecha = $_GET['fecha'];
}else{
	if(isset($_POST['fecha'])) {
		$fecha = $_POST['fecha'];
	}else{
		$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	}
}

if(isset($_GET['mayora'])) {
	$mayora = (int)$_GET['mayora'];
}else{
	if(isset($_POST['mayora'])) {
		$mayora = (int)$_POST['mayora'];
	}else{
		$mayora = '0';
	}
}

if(isset($_GET['fhasta'])) {
	$fhasta = $_GET['fhasta'];
}else{
	if(isset($_POST['fhasta'])) {
		$fhasta = $_POST['fhasta'];
	}else{
		$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
	}
}

if(isset($_GET['cod_casa']) && ($_GET['cod_casa'] !=0 )) {
	$cod_casa = $_GET['cod_casa'];
	$condicion_casa = "AND TO_CHAR(cod_casa) || casa IN ('$cod_casa')";
} else { 
	if (isset($_POST['cod_casa']) && $_POST['cod_casa'] != 0) {
		$cod_casa = $_POST['cod_casa'];
		$condicion_casa = "AND TO_CHAR(cod_casa) || casa IN ('$cod_casa')";	
	}else{
		$cod_casa = 0;
		$condicion_casa = '';
	 }
}

//$db->debug=true;

try{
	$res = $db->Execute("SELECT DECODE(anulado,'S', 'ANULADO', '') AS ANULADO, casa, caja, moneda,
								TO_CHAR(fecha,'dd/mm/yyyy ') AS fecha, nombre, importe_ficha, cod_mov_caja
						 FROM casino.t_reg_cp a
						 WHERE importe_ficha > $mayora
						 AND fecha BETWEEN TO_DATE('$fecha 00:00:00','DD/MM/YYYY HH24:MI:SS')
						 AND TO_DATE('$fhasta 23:59:59','DD/MM/YYYY HH24:MI:SS')
						 $condicion_casa
						 ORDER BY casa, a.fecha DESC, estado");
}catch(exception $e){ 
	die($db->ErrorMsg());
}

$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','',12);
//Acumulador del dinero total
$total = 0;
//Acumulador del dinero por "casino", se reinicia a 0 cada vez que cambia de casino
$subTotal = 0;
//Nombre del utlimo Casino analizado en el FetchNextObjet,
//Sirve para ver cuando cambia el nombre, y por ende hay que,
//hacer todo el calculo de subtotales y eso.
$ultimoCasino = '';

while($row = $res->FetchNextObject($toupper=true)){
	//[IMPRIMIR CABECERA]:
		//Si cambió de casino -> [Y ANTES SALTAR PAGINA], o
		//Si es la 1º vuelta, o
		//Si estamos llegando al final.
	if($row->CASA != $ultimoCasino || $ultimoCasino == '' || $pdf->GetY() >= 184){
		//[IMPRIMIR SUBTOTAL]:
			//Si cambió de casino y NO es el primer registro recorrido.
		if($row->CASA != $ultimoCasino && $ultimoCasino != ''){
			$pdf->Line(10, $pdf->GetY(), 280, $pdf->GetY());
			imprimirSubTotal();
			$pdf->Line(10, $pdf->GetY(), 280, $pdf->GetY());
			$pdf->Ln(4);
		}
		
		//[SALTAMOS DE PAGINA]:
			//Si estamos llegando al final.
		if($pdf->GetY() >= 184){
			$pdf->SetY(184);
		}
		
		//[SALTO DE LINEA]:
			//Si no estamos en la primer hoja
		if($ultimoCasino != ''){
			$pdf->Ln(2);
		}
		
		//Imprimimos el nombre del casino
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(100, 5, 'Casino: '.$row->CASA, 0, 1, 'L');
		$pdf->SetFont('Arial', '', 12);
		
		//Imprimimos la cabecera de la tabla
		$pdf->Cell(25, 5, 'Caja', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Moneda', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Fecha', 1, 0, 'C');
		$pdf->Cell(100, 5, 'Nombre', 1, 0, 'C');
		$pdf->Cell(35, 5, 'Importe', 1, 0, 'C');
		$pdf->Cell(30, 5, 'Estado Ticket', 1, 0, 'C');
		$pdf->Cell(30, 5, 'Nro. Ticket', 1, 1, 'C');
	}
	
	//Imprimimos los datos de la fila que recorrimos
	$pdf->Cell(25, 5, $row->CAJA, 0, 0, 'C');
	$pdf->Cell(25, 5, $row->MONEDA, 0, 0, 'C');
	$pdf->Cell(25, 5, $row->FECHA, 0, 0, 'C');
	$pdf->Cell(100, 5, $row->NOMBRE, 0, 0, 'L');
	$pdf->Cell(35, 5, '$'.number_format($row->IMPORTE_FICHA,2,',','.'), 0, 0, 'R');
	$pdf->Cell(30, 5, $row->ANULADO, 0, 0, 'C');
	$pdf->Cell(30, 5, $row->COD_MOV_CAJA, 0, 1, 'C');
	
	//[ACUMULAMOS TOTAL Y SUBTOTAL]:
		//Si el ticket no esta anulado
	if($row->ANULADO != 'ANULADO'){
		$subTotal	+= $row->IMPORTE_FICHA;
		$total		+= $row->IMPORTE_FICHA;
	}
	
	//Seteamos el nombre del casino que acabamos de recorrer
	$ultimoCasino = $row->CASA;
}

//[IMPRIMIR SUBTOTAL Y TOTAL]:
	//Si hubo registros en la consulta SQL.
if($res->RecordCount() > 0){
	$pdf->Line(10, $pdf->GetY(), 280, $pdf->GetY());
	imprimirSubTotal();
	
	//Imprimos el Total
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(175, 10, 'Total General:', 0, 0, 'R');
	$pdf->Cell(35, 10, '$'.number_format($total,2,',','.'), 0, 1, 'R');
	$pdf->SetFont('Arial', '', 12);
	$pdf->Line(10, $pdf->GetY(), 280, $pdf->GetY());
}

$pdf->Output();

/* FUNCIONES PROPIAS DE LA CLASE */

function imprimirSubTotal(){
	global $pdf, $subTotal;
	//Imprimimos el sub total
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(175, 10, 'SubTotal:', 0, 0, 'R');
	$pdf->Cell(35, 10, '$'.number_format($subTotal,2,',','.'), 0, 1, 'R');
	$pdf->SetFont('Arial', '', 12);
	
	//Volvemos a "0" el SubTotal
	$subTotal = 0;
}