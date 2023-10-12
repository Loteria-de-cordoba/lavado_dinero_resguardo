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
//ACTUALIZACION DE CONSULTAS POR CENTRALIZACION DE EQUIPOS LEY 9505 - VHP

try{
	$res_ant = $db->Execute("SELECT casa, to_char(fecha,'dd/mm/yyyy') as fecha,
										TO_CHAR(fecha_alta, 'dd/mm/yyyy') AS fecha_cont, cod_mov_caja, importe_ficha,
								importe_impuesto, importe_plata, nombre, DECODE(anulado,'S', 1, '') AS anulado,
								NVL(hora, '') AS hora
					    FROM casino.t_reg_cp
					    WHERE cod_mov_caja = ?
					    AND cod_casa = ?",
					    array($cod_mov_caja, $cod_casa));
}catch(exception $e){ 
	die($db->ErrorMsg());
}

try{
	$res = $db->Execute("SELECT distinct 
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
											   AND CA.cod_casa = ?",
					    array($cod_mov_caja, $cod_casa));
}catch(exception $e){ 
	die($db->ErrorMsg());
}

$pdf = new PDF_ROTATE('P');
$pdf->AliasNbPages();
$pdf->AddPage();

							$tengo_elementos=$res->RowCount();
							$tengo_elementos_ant=$res_ant->RowCount();
							//die($tengo_elementos.'   y antes     '.$tengo_elementos_ant);
							
							if($tengo_elementos==0)//funciona con la consulta nueva
								{
									$res=$res_ant;	
								}


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

$pdf->Cell(50, 5, '(F. Cont. '.$row->FECHA_CONT.')', 0, 1, 'L');


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
$pdf->Cell(100, 5, 'COMPROBANTE NO VALIDO COMO FACTURA', 0, 1, 'C');//VIEJO VUELTA A NUEVO
/*//PENDIENTE
$pdf->Cell(100, 5,'Se    deja    constancia  que  el presente  ticket   NO ', 0, 1, 'L');
$pdf->Cell(100, 5,'ACREDITA  GANANCIA,  tampoco   constituye  una  ', 0, 1, 'L');
//$pdf->Cell(100, 5,'', 0, 1, 'L');
$pdf->Cell(100, 5,'declaración   de origen y licitud de fondos para toda  ', 0, 1, 'L');
$pdf->Cell(100, 5,'materia  prevista en  la Ley  Nº 25.246   y  normativa    ', 0, 1, 'L');
$pdf->Cell(100, 5,'complementaria  de los organismos de  control perti-', 0, 1, 'L');
$pdf->Cell(100, 5,'nentes,  constituyendo un simple comprobante de la', 0, 1, 'L');
$pdf->Cell(100, 5,'retención del art. 16 inc. 2 Ley Nº 9505.    ', 0, 1, 'L');
//$pdf->Cell(100, 5,'  de la retención', 0, 1, 'L');
//$pdf->Cell(100, 5,'', 0, 1, 'L');*/
$pdf->Cell(100, 5, '---------------------------------------------------------------------', 0, 1, 'C');

if($row->ANULADO){
	$pdf->SetFont('Arial', 'B', 46);
	$pdf->SetTextColor(192, 192, 192);
	$pdf->RotatedText(20, 82, 'A N U L A D O', 35);
}

$pdf->Output();