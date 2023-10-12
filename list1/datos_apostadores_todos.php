<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '-'.$_GET['delegacion'];
require("header_listado.php"); 

//print_r($_GET);
//die();

//$db->debug=true;
//print_r($_SESSION);
$cuenta=0;
if (isset($_GET['id_cliente'])) {
	$ganador = $_GET['id_cliente'];
	$condicion_ganador="and a.id_cliente = '$ganador'";
	}
	else 
		{
			if (isset($_POST['id_cliente'])) {
				$ganador = $_POST['id_cliente'];
				$condicion_ganador="and a.id_ganador = '$ganador'";
				} 
			else {
				$ganador = "";
				$condicion_ganador="";
			}
		}
		
if (isset($_GET['casino'])) {
	$casino = $_GET['casino'];
	$condicion_casino="and a.id_casino = '$casino'";
	}
	else 
		{
			if (isset($_POST['casino'])) {
				$casino = $_POST['casino'];
				$condicion_casino="and a.id_casino = '$casino'";
				} 
			else {
				$casino = "";
				$condicion_casino="";
			}
		}
$fecha=$_GET['fecha'];
$fhasta_consulta=$_GET['fhasta'];		
/*if($casino<>100)
{*/
try {
	$rs_consulta = $db->Execute("select a.id_cliente, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha, decode(a.nombre,NULL, initcap(a.apellido), initcap(a.apellido) || ', ' || initcap(a.nombre)) apeynom ,
						a.cuit, a.estado_civil, a.telefono, a.email,
						a.domicilio || ' - (' || a.cod_postal || ') ' || a.localidad || ' - ' || a.provincia as domicilio, 
						DECODE(a.id_casino,100,'En Delegacion', b.n_casino) AS casa,
						a.id_casino,  a.observacion as observacion
						from PLA_AUDITORIA.t_cliente a,
								casino.t_casinos b
						where a.id_casino=b.id_casino(+)
						$condicion_ganador
						and a.fecha_baja is null
						and a.usuario_baja is null				
						and a.fecha_alta between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta_consulta','dd/mm/yyyy')
						order by  a.apellido asc");
	}


	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
/*}
else
{
	$rs_consulta = $db->Execute("select a.id_cliente, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha, initcap(a.apellido) || ', ' || initcap(a.nombre) apeynom ,
								a.cuit, a.estado_civil, a.telefono, a.email,
						a.domicilio || ' - (' || a.cod_postal || ') ' || a.localidad || ' - ' || a.provincia as domicilio, 
							'Delegacion' as casa,   a.id_casino,  a.observacion as observacion
							from PLA_AUDITORIA.t_cliente a
							where a.fecha_baja is null
							and a.id_casino=100
							and a.usuario_baja is null				
							and a.fecha_alta between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta_consulta','dd/mm/yyyy')
							order by  a.apellido asc");
	
}*/

$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$salto_pagina=0;

//GETx;

$pdf->ln(-20);
//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line); 

while ($row = $rs_consulta->FetchNextObject($toupper=true)) {
//$pdf->Ln(8);
$pdf->SetFillColor(240,240,240);
	$cuenta=$cuenta+1;
	$y_line=$pdf->GetY();
			$salto_pagina=number_format($y_line,0,'.',',');
			
		if($cuenta==1 or $salto_pagina>240)
			{
				if($salto_pagina>240)
				{
					$pdf->AddPage();
					$pdf->ln(-20);
				}
				$pdf->SetFont('Arial','B',15);
				//$pdf->Cell(190,8,'Datos de Apostadores',0,1,'C');
				$pdf->Cell(190,8,'Datos de Apostadores[Datos Resguardados]',0,1,'C',1);
				$pdf->Cell(190,8,'Registrados Entre '.$fecha.' y '.$fhasta_consulta,0,1,'C',1);
				//$pdf->Cell(190,8,'Con Origen en '.$row->CASA,0,1,'C',1);
				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(15,6,'Fecha_Alta',1,0,'C');
				$pdf->Cell(32,6,'Apellido y Nombre',1,0,'C');
				$pdf->Cell(50,6,'Domicilio',1,0,'C');
				$pdf->Cell(15,6,'CUIT',1,0,'C');
				$pdf->Cell(12,6,'E.Civil',1,0,'C');
				$pdf->Cell(20,6,'Telefono',1,0,'C');
				$pdf->Cell(27,6,'Email',1,0,'C');
				$pdf->Cell(23,6,'Observaciones',1,1,'C');
				$salto_pagina=0;
				
			}

	$pdf->SetFont('Arial','',5);
				$pdf->Cell(15,6,$row->FECHA,1,0,'C');
				$pdf->Cell(32,6,$row->APEYNOM,1,0,'L');
				$pdf->Cell(50,6,$row->DOMICILIO,1,0,'L');
				$pdf->Cell(15,6,$row->CUIT,1,0,'L');
				$pdf->Cell(12,6,$row->ESTADO_CIVIL,1,0,'L');
				$pdf->Cell(20,6,$row->TELEFONO,1,0,'L');
				$pdf->Cell(27,6,$row->EMAIL,1,0,'L');
				$pdf->Cell(23,6,$row->OBSERVACION,1,1,'L');
	

	/*$pdf->Rect( 40,  62, 120, 140); 

	$pdf->Ln(15);
	$pdf->SetX(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(25,8,'Tipo y Nro Doc:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	
	$pdf->SetX(85);
	$pdf->Cell(20,8,utf8_decode($row->DESCRIPCION).' '.$row->DOCUMENTO,0,1,'L');
	$pdf->SetX(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Sexo:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(40,8,utf8_decode($row->SEXO),0,1,'L');	
		
	$pdf->SetX(50);
	$pdf->SetFont('Arial','BU',9);
 	$pdf->Cell(30,8,'Cuit:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(20,8,$row->CUIT,0,1,'L');
	
	

	$pdf->SetX(50);
	$pdf->SetFont('Arial','BU',10);
	$pdf->Cell(40,8,'Apellido y Nombre:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$xx=utf8_decode($row->APELLIDO);
	$pdf->Cell(30,8,utf8_decode($xx).', '.utf8_decode($row->NOMBRE),0,1,'L');
	
	
	    	  
	    $pdf->SetX(50);
		$pdf->SetFont('Arial','BU',9);
		$pdf->Cell(40,8,'Calle:',0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(85);
		$xx=utf8_decode($row->DOMICILIO);
		$pdf->Cell(30,8,utf8_decode($xx),0,1,'L');

	  
	
	
	$pdf->SetX(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Codigo Postal:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(85);
	$pdf->Cell(30,8,$row->COD_POSTAL,0,1,'L');
	
	

	$pdf->SetX(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Localidad:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(85);
	$pdf->Cell(30,8,utf8_decode($row->LOCALIDAD),0,1,'L');
		
	$pdf->setx(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Provincia:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(85);
	$pdf->Cell(30,8,utf8_decode($row->PROVINCIA),0,1,'L');
	
	$pdf->setx(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Fecha de Alta:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(85);
	$pdf->Cell(20,8,$row->FECHA_ALTA,0,1,'L');
	
	$pdf->setx(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Estado Civil:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,utf8_decode($row->ESTADO_CIVIL),0,1,'L');
	
	$pdf->setx(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Profesion:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(85);
	$pdf->Cell(30,8,utf8_decode($row->PROFESION),0,1,'L');
  	
	
	
	$pdf->setx(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Telefono:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,$row->TELEFONO,0,1,'L');
	
	$pdf->setx(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Email:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,utf8_decode($row->EMAIL),0,1,'L');
	
	$pdf->setx(50);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Inscripto en :',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,utf8_decode($row->CASINO),0,1,'L');
	
	 $pdf->SetX(50);
		$pdf->SetFont('Arial','BU',9);
		$pdf->Cell(40,8,'Observaciones:',0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(85);
		$xx=utf8_decode($row->OBSERVACION);
		$pdf->Cell(30,8,utf8_decode($xx),0,1,'L');*/
	
	}


$pdf->setx(40);

$pdf->Output();
?>