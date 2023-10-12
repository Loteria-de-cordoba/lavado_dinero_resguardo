<?php
//print_r($_GET);
//die();
if($_GET['conformado']==1){
$conformado="Conformados";
} else {
$conformado="No Conformado";
}
$montito='$ 50.000';
$mes=substr($_GET['mesano'],0,2);
$ano=substr($_GET['mesano'],3,4);
//obtengo meses
switch((int)$mes)
			{ case 1:
			$nombre_mes='Enero';
			break;
			case 2:
			$nombre_mes='Febrero';
			break;
			case 3:
			$nombre_mes='Marzo';
			break;
			case 4:
			$nombre_mes='Abril';
			break;
			case 5:
			$nombre_mes='Mayo'; 
			break;
			case 6:
			$nombre_mes='Junio';
			break;
			case 7:
			$nombre_mes='Julio';
			break;
			case 8:
			$nombre_mes='Agosto';
			break;
			case 9:
			$nombre_mes='Septiembre';
			break;
			case 10:
			$nombre_mes='Octubre';
			break;
			case 11:
			$nombre_mes='Noviembre';
			break;
			case 12:
			$nombre_mes='Diciembre';
			break;
			}


$titulo='REGISTRO DE PREMIOS PAGADOS - [Datos Resguardados]';
$titulo2='RSM - '.strtoupper($nombre_mes).' / '.$ano; 
//.$conformado;
require("header_listado.php"); 

 $consulta= $_SESSION['sqlreporte'];
//echo $consulta;
//$db->debug=true;

//print_r($_GET);




try{
	
	 $rs = $db->Execute("$consulta"); 
	}
	catch(exception $e)
	{
	die($db->ErrorMsg());
	}
	

$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(240,0);
//$pdf->SetDrawColor(128,0,0);
$pdf->SetFont('Arial','B',10);
 $pdf->setx(17);
$pdf->Cell(20,6,'Fecha',1,0,'C');
$pdf->Cell(30,6,'Sucursal',1,0,'C');
$pdf->Cell(55,6,'Apellido Y Nombre',1,0,'C');
$pdf->Cell(25,6,'Juego',1,0,'C');
$pdf->Cell(25,6,'M. de Pago',1,0,'C');
$pdf->Cell(25,6,'Importe',1,1,'C');
//$pdf->Cell(25,6,'DDJJ',1,1,'C');
$salto_pagina=0;
		
$pdf->SetFont('Arial','',9);
 
while ($row = $rs->FetchNextObject($toupper=true)) {
 
 			if($salto_pagina>260)
			{
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',10);
				$pdf->setx(17);
				$pdf->Cell(20,6,'Fecha',1,0,'C');
				$pdf->Cell(30,6,'Sucursal',1,0,'C');
				$pdf->Cell(55,6,'Apellido Y Nombre',1,0,'C');
				$pdf->Cell(25,6,'Juego',1,0,'C');
				$pdf->Cell(25,6,'M. de Pago',1,0,'C');
				$pdf->Cell(25,6,'Importe',1,1,'C');
				//$pdf->Cell(25,6,'DDJJ',1,1,'C');
			}
				if($row->POLITICO=="SI")//si es politico va en rojo
				{
						$pdf->SetFont('Arial','B',9);
						$pdf->SetFillColor(240,0);
						$pdf->setx(17);
						$pdf->Cell(20,7,$row->FECHA,1,0,'C',1);
						$pdf->SetFont('Arial','B',7);
						$pdf->Cell(30,7,$row->CASA,1,0,'L',1);
						$xx=trim($row->APELLIDO);	
						$pdf->Cell(55,7,utf8_decode($xx).' '.utf8_decode(trim($row->NOMBRE)),1,0,'L',1);
						$pdf->SetFont('Arial','B',9);
						$pdf->Cell(25,7,$row->JUEGOS,1,0,'L',1);
						$pdf->Cell(25,7,$row->PPAGO,1,0,'L',1);
						$pdf->Cell(25,7,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),1,1,'R',1);
				
				}
				else if($row->VALOR_PREMIO>=1000000)//si es premio>=1000000 va en naranja
				{
						$pdf->SetFont('Arial','B',9);
						$pdf->SetFillColor(240,100);
						$pdf->setx(17);
						$pdf->Cell(20,7,$row->FECHA,1,0,'C',1);
						$pdf->SetFont('Arial','B',7);
						$pdf->Cell(30,7,$row->CASA,1,0,'L',1);
						$xx=trim($row->APELLIDO);	
						$pdf->Cell(55,7,utf8_decode($xx).' '.utf8_decode(trim($row->NOMBRE)),1,0,'L',1);
						$pdf->SetFont('Arial','B',9);
						$pdf->Cell(25,7,$row->JUEGOS,1,0,'L',1);
						$pdf->Cell(25,7,$row->PPAGO,1,0,'L',1);
						$pdf->Cell(25,7,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),1,1,'R',1);
				}
				else
				{				
						$pdf->SetFont('Arial','',9);
						$pdf->setx(17);
						$pdf->Cell(20,7,$row->FECHA,1,0,'C');
						$pdf->SetFont('Arial','',7);
						$pdf->Cell(30,7,$row->CASA,1,0,'L');
						$xx=trim($row->APELLIDO);	
						$pdf->Cell(55,7,utf8_decode($xx).' '.utf8_decode(trim($row->NOMBRE)),1,0,'L');
						$pdf->SetFont('Arial','',9);
						$pdf->Cell(25,7,$row->JUEGOS,1,0,'L');
						$pdf->Cell(25,7,$row->PPAGO,1,0,'L');
						$pdf->Cell(25,7,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),1,1,'R');
				//$pdf->Cell(25,7,$row->DDJJ,1,1,'R');
				$acum=$acum+$row->VALOR_PREMIO;
				}
	$y_line=$pdf->GetY();
	$salto_pagina=number_format($y_line,0,'.',',');
	
	} 

$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(80);
$pdf->Cell(90+strlen($acum)-strlen($rs->RowCount()),7,'CANTIDAD DE OPERACIONES                 '.$rs->RowCount(),0,1,'L');

//SAQUE TOTAL EN PESOS POR PEDIDO DE LILIANA
//$pdf->setx(80);
//$pdf->Cell(90,7,'TOTAL                                 $ '.str_pad(' ',strlen($rs->RowCount())).number_format($acum,2,',','.'),0,0,'L');
	//$pdf->Cell(180,7,'Total:  $'.$acum,1,0,'R');
//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);

$pdf->Output();
?>