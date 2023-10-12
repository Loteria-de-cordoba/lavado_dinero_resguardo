<?php
//include_once("../jscalendar-1.0/calendario.php");
//include_once("db_conecta_adodb.inc.php");
//include_once("../funcion.inc.php");
//$array_fecha = FechaServer();
$fecha = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];

$titulo='** LOTERIA DE CORDOBA S.E. Casinos Provinciales **';



require("header_listado.php"); 
//print_r($_GET);die();
//$consulta= $_SESSION['sqlreporte'];
//$db->debug=true;

//$registro=$_GET['registro'];
//print_r($_GET);
try{
	
	 $rs = $db->Execute("  select casa, to_char(fecha,'dd/mm/yyyy') fecha, cod_mov_caja, importe_ficha, importe_impuesto, importe_plata, nombre, cod_casa 
		                    from casino.t_reg_cp
							where cod_mov_caja=?
							and cod_casa=?", array($_GET['cod_mov_caja'],$_GET['cod_casa']));
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}

$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();


while ($row = $rs->FetchNextObject($toupper=true)) {

$pdf->SetFont('Arial','',10);
$pdf->Cell(60,8,'',0,0,'R');
$pdf->Cell(30,8,'Casino:',1,0,'R');
$pdf->Cell(60,8,$row->CASA,1,1,'L');
$pdf->Cell(60,8,'',0,0,'R');
$pdf->Cell(30,8,'Cajero:',1,0,'R');
$pdf->Cell(60,8,$row->NOMBRE,1,1,'L');
$pdf->Cell(60,8,'',0,0,'R');
$pdf->Cell(30,8,'Fecha:',1,0,'R');
$pdf->Cell(60,8,$row->FECHA,1,1,'L');
$pdf->Cell(60,8,'',0,0,'R');
$pdf->Cell(30,8,'Nro Ticket:',1,0,'R');
$pdf->Cell(60,8,$row->COD_MOV_CAJA,1,1,'L');
$pdf->Cell(60,8,'',0,0,'R');
$pdf->Cell(30,8,'Importe Ficha:',1,0,'R');
$pdf->Cell(60,8,'$'.number_format($row->IMPORTE_FICHA,2,',','.'),1,1,'L');
$pdf->Cell(60,8,'',0,0,'R');
$pdf->Cell(30,8,'Aporte Ley 9505:',1,0,'R');
$pdf->Cell(60,8,'$'.number_format($row->IMPORTE_IMPUESTO,2,',','.'),1,1,'L');
$pdf->Cell(60,8,'',0,0,'R');
$pdf->Cell(30,8,'Importe Pagado:',1,0,'R');
$pdf->Cell(60,8,'$'.number_format($row->IMPORTE_PLATA,2,',','.'),1,1,'L');

} 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(185,7,'Ref: Prevencion Lavado de Activos ',0,1,'R');

 
$pdf->SetFont('Arial','',10); 

$pdf->Cell(45,7,'Fecha: '.$fecha,0,0,'L');
//$pdf->Cell(135,7,'Ref: Solicitud de Información',0,1,'R');
$pdf->Cell(45,7,'Nota Nº: ',0,1,'L');
$pdf->Cell(45,7,'',0,1,'L');
$pdf->SetFont('Arial','U',10);
//$pdf->setx(10);

$pdf->Cell(45,7,'Sr. Jefe de Casino de '.$row->CASA,0,1,'L');
Getx;
$pdf->Cell(45,7,'',0,1,'L');
$pdf->SetFont('Arial','',10);
//$pdf->Cell(10,7,'',0,0,'L');
$pdf->multiCell(0,7,'																																																																											Con la finalidad de verificar si el sistema implementado funciona correctamente se están controlando cada una de las operaciones realizadas (cambio de fichas a los apostadores). ',0,'L');
//$pdf->Cell(10,7,'',0,0,'L');
$pdf->multiCell(0,7,'Se ha identificado, que se efectuó la siguiente operación: ',0,'L');

$pdf->Cell(0,7,'',0,1,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10,7,'',0,0,'L');
$pdf->Cell(70,7,'Fecha: '.$row->FECHA,0,1,'L');
$pdf->Cell(10,7,'',0,0,'L');
$pdf->Cell(70,7,'Cajero: '.$row->NOMBRE,0,1,'L');
$pdf->Cell(10,7,'',0,0,'L');
$pdf->Cell(70,7,'Caja: '.$row->CAJA,0,1,'L');
$pdf->Cell(10,7,'',0,0,'L');
$pdf->Cell(70,7,'Moneda: '.$row->MONEDA,0,1,'L');
$pdf->Cell(10,7,'',0,0,'L');
$pdf->Cell(70,7,'Importe: '.'$'.number_format($row->IMPORTE_FICHA,2,',','.'),0,1,'L');

$pdf->SetFont('Arial','',10);
$pdf->Cell(10,7,'',0,1,'L');
$pdf->multiCell(0,7,'Se observa que no se efectuó la carga de los datos exigidos por la U.I.F. ',0,'L');
$pdf->multiCell(0,7,'Se solicita informe al respecto.',0,'L');



//$pdf->Cell(25,6,'Fecha',1,0,'C');
//$pdf->Cell(45,6,'casa',1,0,'C');
//$pdf->Cell(60,6,'Apellido Y Nombre',1,0,'C');
//
//$pdf->Cell(30,6,'Juego',1,0,'C');
//$pdf->Cell(30,6,'Importe',1,1,'C');
 
		
//$pdf->SetFont('Arial','',9);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 
	$pdf->setx(10);
	$pdf->Cell(25,7,$row->FECHA,1,0,'C');
	$pdf->Cell(45,7,$row->CASA,1,0,'L');
	$pdf->Cell(60,7,$row->NOMBRE ,1,0,'L');
    //$pdf->Cell(30,7,$row->JUEGOS,1,0,'L');
	$pdf->Cell(30,7,number_format($row->IMPORTE_FICHA,2,',','.'),1,1,'R');
	 //$acum=$acum+$row->VALOR_PREMIO;
	} 

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(40);
$pdf->Cell(120,7,'TOTAL $ '.number_format($acum,2,',','.'),0,0,'R');
$pdf->Cell(180,7,'Total:  $'.$acum,1,0,'R');
$y_line=$pdf->GetY();
$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>