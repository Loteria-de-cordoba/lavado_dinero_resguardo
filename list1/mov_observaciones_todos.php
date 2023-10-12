<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '-'.$_GET['delegacion'];
require("header_listado.php"); 

//print_r($_GET);
//die();

//$db->debug=true;
//print_r($_SESSION);

	
		if(isset($_GET['casino']))
		 {
				$casino = $_GET['casino'];
					if($casino<>0)
						{
								$condicion_conforma="and b.id_casino ='$casino'";
						}
						else
						{
							$condicion_conforma='';
						}	
					//$condicion_conforma="and a.id_casino_novedad ='$casino'";
		 }
		

		if(isset($_GET['apostador']))
		 {
			if($_GET['apostador']<>0)
				{
				$apostador = $_GET['apostador'];
				$condicion_apostador="and b.id_cliente ='$apostador'";
				}
				else
				{
				$apostador = '0';
				$condicion_apostador="";
				}
		 }
		 else
		 {
				$apostador=1;
				$condicion_apostador="";	
		 }
		
$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];


					//consulta
					try {
$rs_consulta = $db->Execute("SELECT substr(c.n_casino,8)    casino,
				TO_CHAR(a.fecha_novedad, 'dd/mm/yyyy') AS fecha,
					b.apellido
					|| ' '
					|| b.nombre AS identificacion,
					DECODE(a.valoracion, NULL, 'Sin Valoracion Aun', a.valoracion) AS valoracion,
					b.id_cliente id_cliente,    
					b.id_casino   id_casino
				FROM
					lavado_dinero.t_novedad_casino   a,
					lavado_dinero.t_cliente          b,
					casino.t_casinos                 c,
					superusuario.usuarios            us
				WHERE
					a.id_cliente = b.id_cliente
					and a.id_casino = c.id_casino
					AND us.id_usuario = b.usuario
					AND b.fecha_baja IS NULL
					AND COMPLETA=1
					$condicion_conforma
					$condicion_apostador
					AND b.fecha_baja    IS NULL
					and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
				ORDER BY
					a.id_casino,
					a.fecha_novedad DESC,
					b.apellido
					|| ' '
					|| b.nombre");}
				catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		
//die('entre');
		
$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(-20);
$salto_pagina=0;
$bandera=0;	
$cuentanovedad=0;
if($rs_consulta->RowCount()<>0)
{
				$pdf->SetFillColor(240,240,240);
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(190,8,'Listado de Novedades Marcadas Por Observaciones ',0,1,'C',1);
				$pdf->Cell(190,8,' Desde el '.$fecha.' Hasta el '.$fhasta,0,1,'C',1);
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
 $cuenta=$cuenta+1;

  if($row->CASINO<>$controlescasino and $cuenta<>1)
  		{
			$pdf->SetFillColor(240,240,240);
			$pdf->SetFont('Arial','B',12);
			$pdf->Ln(2);
			$pdf->Cell(190,8,$cuentanovedad.' Valoracion/es en el Periodo('.$controlescasino.')',0,1,'C',1);
			$todasvaloraciones=$todasvaloraciones+$cuentanovedad;
			$cuentanovedad=0;
		}
 $cuentanovedad=$cuentanovedad + 1;
//$pdf->Ln(4);
$pdf->SetFont('Arial','B',12);
//$pdf->SetX(30);
if($row->CASINO<>$controlescasino and $salto_pagina<211)
{


				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',12);
				//$pdf->Cell(190,8,'Listado de Novedades Marcadas Por Observaciones ',0,1,'C',1);
				$pdf->Cell(190,8,'Casino '.$row->CASINO,0,1,'C',1);
				$controlcasino=$row->FECHA;
				$controlescasino=$row->CASINO;
				if($cuenta<>1)
				{
				$pdf->Ln(5);
				$pdf->SetX(10);
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(25,6,'Fec. Movimiento',1,0,'C');
				$pdf->Cell(50,6,'Apellido y Nombre',1,0,'C');
				$pdf->Cell(115,6,'Valoracion',1,1,'C');
				}	
}
$y_line=$pdf->GetY();
			$salto_pagina=number_format($y_line,0,'.',',');
			if($salto_pagina>210)
			{
					$pdf->AddPage();
					$salto_pagina=0;
					$pdf->Ln(-20);
					$pdf->SetFont('Arial','B',12);
					$pdf->Cell(190,8,'Listado de Novedades Marcadas Por Observaciones ',0,1,'C',1);
					$pdf->Cell(190,8,$row->CASINO.' desde el '.$fecha.' Hasta el '.$fhasta,0,1,'C',1);
					$controlcasino=$row->FECHA;
					$controlescasino=$row->CASINO;
					$pdf->Ln(5);
					if($cuenta<>1)
					{
						$pdf->Cell(32,8,'',0,0,'L');
						$pdf->SetFont('Arial','B',11);
						$pdf->Ln(5);
						//$pdf->Cell(125,8,$row->APELLIDO,0,1,'L',1);
						$pdf->Cell(125,8,'Jornada del '.utf8_decode(utf8_decode($controlcasino)),0,1,'L',1);
						$pdf->SetX(10);
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(25,6,'Fec. Movimiento',1,0,'C');
				$pdf->Cell(50,6,'Apellido y Nombre',1,0,'C');
				$pdf->Cell(115,6,'Valoracion',1,1,'C');
						
					}
			}
$pdf->SetFont('Arial','B',11);
if($row->FECHA<>$controlcasino or $cuenta==1)
{
$pdf->Cell(32,8,'',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Ln(5);
$pdf->Cell(125,8,'Jornada del '.utf8_decode(utf8_decode($row->FECHA)),0,1,'L',1);
$controlcasino=$row->FECHA;
//$pdf->Ln(5);
	
$pdf->SetX(10);
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(25,6,'Fec. Movimiento',1,0,'C');
	$pdf->Cell(50,6,'Apellido y Nombre',1,0,'C');
	$pdf->Cell(115,6,'Valoracion',1,1,'C');
}

$y_line=215;



$pdf->SetX(10);
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(25,6,$row->FECHA,1,0,'C');
	
	$pdf->Cell(50,6,$row->IDENTIFICACION,1,0,'L');
	$pdf->MultiCell(115,6,$row->VALORACION,1,'L');
	
 }
$pdf->SetFillColor(240,240,240);
			$pdf->SetFont('Arial','B',12);
			$pdf->Ln(2);
			$pdf->Cell(190,8,$cuentanovedad.' Valoracion/es en el Periodo ('.$controlescasino.')',0,1,'C',1);
			$todasvaloraciones=$todasvaloraciones + $cuentanovedad;
			$cuentanovedad=0;
			
			if($casino==0)
				{
					$pdf->SetFillColor(155,155,155);
					$pdf->Ln(5);
					$pdf->Cell(190,8,'Un total de '.$todasvaloraciones.' Valoracion/es Efectuadas en el Periodo para todos los Casinos',0,1,'C',1);
				}
			$cuentanovedad=0;

$pdf->setx(8);
}
else
{
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,7,'SIN VALORACIONES EN LA CONSULTA',0,0,'C');
}

$pdf->Output();
?>
