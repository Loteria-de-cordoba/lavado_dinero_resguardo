<?php
require("header_listado.php"); 
//print_r($_GET);
//$db->debug=true;
//print_r($_SESSION);
$sumafichaje=0;
$sumaacierto=0;
$totfic_ing=0;
$totfic_ret=0;
$perdido=0;
$casino=$_GET['casino'];
$fechita=$_GET['fechita'];
// obtengo datos del CASINO
try {
	$rs_apostador = $db->Execute("SELECT  N_CASINO as datos
									FROM CASINO.T_CASINOS
									WHERE ID_CASINO=?", array($casino));}
								catch (exception $e){die ($db->ErrorMsg());} 	
	
	
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$soydelcasino=$row_apostador->DATOS;
	
/*con documento
DECODE(cl.documento,NULL,DECODE(cl.nombre,NULL,INITCAP(cl.apellido), INITCAP(cl.apellido)
								  || ', '
								  || INITCAP(cl.nombre)), DECODE(cl.nombre,NULL,INITCAP(cl.apellido), INITCAP(cl.apellido)
								  || ', '
								  || INITCAP(cl.nombre))
								  || ' Documento Nro. '
								  || cl.documento) AS datos	*/
		
try {
	$rs_consulta = $db->Execute("SELECT  DECODE(cl.nombre,NULL,INITCAP(cl.apellido), INITCAP(cl.apellido)
								  || ', '
								  || INITCAP(cl.nombre)) AS datos,
								  g.fichaje as fichaje,
								  g.acierto as acierto,
								  g.mon_ing_fic as fichaje_ingreso,
								  g.mon_fic_ret as fichaje_retiro,
								  g.mon_perdido as perdido,
								  g.observa_mov as observacion, 
								  decode(g.confirmado,'S','Jornada Cerrada','Jornada Abierta') as CONFIRMADO
								FROM lavado_dinero.t_cliente cl,
								  lavado_dinero.t_novedad_casino g
								WHERE cl.id_cliente=g.id_cliente
								and g.fecha_novedad=to_date(?,'dd/mm/yyyy')
								and g.id_casino=?", array($fechita,$casino));}
								catch (exception $e){die ($db->ErrorMsg());} 

$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Ln(-20);
if($rs_consulta->RowCount()<>0)
{
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(240,240,240);
//$pdf->SetX(30);
$pdf->Cell(190,8,'Reporte Diario Fichaje - Fecha '.$fechita,0,1,'C',1);
$pdf->Cell(190,8,$soydelcasino,0,1,'C',1);
//$pdf->Cell(190,8,$datos1,0,1,'C',1);
//$titulo2=$datos;
//GETx;

//$pdf->ln(10);
//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line);
$pdf->Ln(5);
	$pdf->SetX(12);
	$pdf->SetFont('Arial','B',8);
	//$pdf->Cell(10,6,'',0,1,'C');
	$pdf->Cell(60,6,'Apellido y Nombre/Apodo',1,0,'C');
	$pdf->Cell(17,6,'Fichaje',1,0,'C');
	$pdf->Cell(17,6,'Fic_Ingreso',1,0,'C');
	$pdf->Cell(17,6,'Acierto',1,0,'C');
	$pdf->Cell(15,6,'Fic_Retiro',1,0,'C');
	$pdf->Cell(15,6,'Perdido',1,0,'C');
	$pdf->Cell(20,6,'Estado',1,0,'C');
	$pdf->Cell(25,6,'Novedades',1,1,'C');
	//$pdf->Cell(20,6,'Estado',1,1,'C');
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
//$pdf->Ln(8);
 	$pdf->SetX(12);
	$pdf->SetFont('Arial','',6);
	//$pdf->Cell(10,6,'',0,1,'C');
	$pdf->Cell(60,6,$row->DATOS,1,0,'L');
	$pdf->Cell(17,6,number_format($row->FICHAJE,2,',','.'),1,0,'R');
	$pdf->Cell(17,6,number_format($row->FICHAJE_INGRESO,2,',','.'),1,0,'R');
	$pdf->Cell(17,6,number_format($row->ACIERTO,2,',','.'),1,0,'R');
	$pdf->Cell(15,6,number_format($row->FICHAJE_RETIRO,2,',','.'),1,0,'R');
	$pdf->Cell(15,6,number_format($row->PERDIDO,2,',','.'),1,0,'R');
	//$pdf->Cell(25,6,utf8_decode($row->OBSERVACION),1,0,'L');
	//$pdf->MultiCell(25,6,utf8_decode($row->OBSERVACION),1,0);
	$pdf->Cell(20,6,utf8_decode($row->CONFIRMADO),1,0,'L');
	$pdf->MultiCell(25,6,utf8_decode($row->OBSERVACION),1,1);
	
	
	$sumafichaje=$sumafichaje+$row->FICHAJE;
	$sumaacierto=$sumaacierto+$row->ACIERTO;
	$totfic_ing=$totfic_ing+$row->FICHAJE_INGRESO;
	$totfic_ret=$totfic_ret+$row->FICHAJE_RETIRO;
	$perdido=$perdido+$row->PERDIDO;
 }
$pdf->SetX(12);
	$pdf->SetFont('Arial','B',8);
	//$pdf->Cell(10,6,'',0,1,'C');
	$pdf->Cell(60,7,'Totales===>',1,0,'C',1);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(17,7,number_format($sumafichaje,2,',','.'),1,0,'R',1);
	$pdf->Cell(17,7,number_format($totfic_ing,2,',','.'),1,0,'R',1);
	$pdf->Cell(17,7,number_format($sumaacierto,2,',','.'),1,0,'R',1);
	$pdf->Cell(15,7,number_format($totfic_ret,2,',','.'),1,0,'R',1);
	$pdf->Cell(15,7,number_format($perdido,2,',','.'),1,0,'R',1);
	//$pdf->Cell(12,7,'',1,0,'R',1);
	
	
	//$pdf->Cell(30,7,'',1,1,'L',1);

$pdf->setx(40);
}
else
{
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,7,'SIN MOVIMIENTOS',0,0,'L');
}
$pdf->Output();
?>