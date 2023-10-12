<?php
/*
	@Autor: Broda Favio Noel
	@Razon: Creación. Pedido por Mario Palenzona
	@Fecha: 20/04/2012
	@Modificaciones:
		1:
		@@Autor: Broda Noel
		@@Fecha: 23/04/2012
		@@Descripcion: Añadida la Hora al ticket
*/
session_start();

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 

include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
require('fpdf.php');
require('rotation.php');

$cod_casa		= (int)$_GET['cod_casa'];
$cod_mov_caja	= (int)$_GET['cod_mov_caja'];

//$db->debug=true;

try{
	$res = $db->Execute("SELECT casa, to_char(fecha,'dd/mm/yyyy') as fecha, cod_mov_caja, importe_ficha,
								importe_impuesto, importe_plata, nombre, DECODE(anulado,'S', 1, '') AS anulado,
								NVL(hora, '') AS hora
					    FROM casino.t_reg_cp
					    WHERE cod_mov_caja = ?
					    AND cod_casa = ?",
					    array($cod_mov_caja, $cod_casa));
}catch(exception $e){ 
	die($db->ErrorMsg());
}

$pdf = new PDF_ROTATE('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$row = $res->FetchNextObject($toupper=true);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(100, 5, 'LOTERIA DE CORDOBA S.E.', 0, 1, 'C');

$pdf->Cell(100, 5, 'Casinos Provinciales', 0, 1, 'C');

$pdf->SetFont('Arial','',12);
$pdf->Cell(100, 5, '---------------------------------------------------------------------', 0, 1, 'C');
$pdf->Cell(100, 5, 'Aporte Ley Nro.: 9505', 0, 1, 'C');
$pdf->Cell(100, 5, '---------------------------------------------------------------------', 0, 1, 'C');

$pdf->Cell(100, 5, 'Casino: '.$row->CASA, 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(100, 5, 'Cajero: '.$row->NOMBRE, 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);

$fecha = $row->HORA ? $row->FECHA.' - '.$row->HORA.'hs' : $row->FECHA;
$pdf->Cell(50, 5, 'Fecha: '.$fecha, 0, 0, 'L');
$pdf->Cell(50, 5, 'Nro.Ticket: '.$row->COD_MOV_CAJA, 0, 1, 'R');

$pdf->Cell(100, 5, '---------------------------------------------------------------------', 0, 1, 'C');
$pdf->Cell(70, 5, 'Valor recibido en fichas:', 0, 0, 'L');
$pdf->Cell(30, 5, ' $'.number_format($row->IMPORTE_FICHA,2,',','.'), 0, 1, 'R');

$pdf->Cell(70, 5, 'Aporte Ley 9505:', 0, 0, 'L');
$pdf->Cell(30, 5, '-$'.number_format($row->IMPORTE_IMPUESTO,2,',','.'), 0, 1, 'R');
$pdf->SetFont('Arial', '', 5);
$pdf->Cell(100, 1, '_____________________', 0, 1, 'R');
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(70, 5, 'Importe en dinero:', 0, 0, 'L');
$pdf->Cell(30, 5, ' $'.number_format($row->IMPORTE_PLATA,2,',','.'), 0, 1, 'R');

$pdf->Cell(100, 5, '---------------------------------------------------------------------', 0, 1, 'C');
$pdf->Cell(100, 5, 'COMPROBANTE NO VALIDO COMO FACTURA', 0, 1, 'C');
$pdf->Cell(100, 5, '---------------------------------------------------------------------', 0, 1, 'C');

if($row->ANULADO){
	$pdf->SetFont('Arial', 'B', 46);
	$pdf->SetTextColor(192, 192, 192);
	$pdf->RotatedText(20, 82, 'A N U L A D O', 35);
}

$pdf->Output();