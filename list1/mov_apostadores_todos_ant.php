<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '-'.$_GET['delegacion'];
require("header_listado.php"); 

//print_r($_GET);
//die();

//$db->debug=true;
//print_r($_SESSION);
$sumafichaje=0;
$sumaacierto=0;
$sumafichajeparcial=0;
$sumaaciertoparcial=0;
$controlcasino='';
$controlapellido='';
$cuenta=0;
		
if (isset($_POST['casino']))
		{
			$casino = $_POST['casino'];
			$condicion_conforma="and a.id_casino_novedad ='$casino'";
		} 
		else
		{
		if(isset($_GET['casino']))
		 {
					$casino = $_GET['casino'];
					$condicion_conforma="and a.id_casino_novedad ='$casino'";
		 }
		} 

if (isset($_POST['apostador']))
		{
			if($_POST['apostador']<>0)
			{
			$apostador = $_POST['apostador'];
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
		} 
$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];


if($casino<>100)
{
try {
$rs_consulta = $db->Execute("select b.id_cliente,   DECODE(a.fecha_novedad, NULL, 'SIN MOV.', 
				TO_CHAR(a.fecha_novedad,'DD/MM/YYYY')) AS fecha_alta,
				initcap(b.apellido) || ', ' || initcap(b.nombre) || ' - Documento Nro. ' || b.documento  as apellido,
				a.id_novedad , a.fichaje fichaje, a.acierto acierto,
				c.n_casino as casino, b.id_casino,
				decode(a.confirmado,NULL,'SIN CONFIRMAR','CONFIRMADO') AS CONFIRMADO						
      			from lavado_dinero.t_novedades_cliente a,
				lavado_dinero.t_cliente b,
				casino.t_casinos c
				where  a.id_cliente=b.id_cliente
				AND a.id_casino_novedad(+)      =c.id_casino
				$condicion_conforma
				$condicion_apostador
				and b.fecha_baja is null
				AND A.USUARIO_BAJA IS NULL
				and (a.fichaje<>0 or a.acierto<>0)
        		and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')					
        		order by b.id_casino,
				b.apellido, a.fecha_novedad desc");}
				catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
}
else
{
try {
	$rs_consulta= $db->Execute("select b.id_cliente,   DECODE(a.fecha_novedad, NULL, 'SIN MOV.', 
				TO_CHAR(a.fecha_novedad,'DD/MM/YYYY')) AS fecha_alta,
				initcap(b.apellido) || ', ' || initcap(b.nombre) || ' - Documento Nro. ' || b.documento  as apellido,
				a.id_novedad , a.fichaje, a.acierto,
				'Delegacion' as casino, '100' as id_casino,
				decode(a.confirmado,NULL,'SIN CONFIRMAR','CONFIRMADO') AS CONFIRMADO						
      			from lavado_dinero.t_novedades_cliente a,
				lavado_dinero.t_cliente b
				where a.id_cliente(+)=b.id_cliente
				and b.id_casino=100
				$condicion_conforma
				$condicion_apostador
				and b.fecha_baja is null
				and (a.fichaje<>0 or a.acierto<>0)
				AND A.USUARIO_BAJA IS NULL
        		and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
        		order by b.id_casino,
				b.apellido, a.fecha_novedad desc");}
				catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
}

/* obtengo datos del apostador
try {
	$rs_apostador = $db->Execute("SELECT  'Sr. ' || cl.apellido || ', ' || cl.nombre || ' Documento Nro. ' || cl.documento as datos,
									' Registrado en ' || ca.n_casino as datos1
								FROM lavado_dinero.t_cliente cl,
								casino.t_casinos ca
								where ca.id_casino=cl.id_casino
								and cl.id_cliente=?", array($apostador));*/
	
	
	/*select direccion, cuenta_bancaria
								from lavado_dinero.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$datos=$row_apostador->DATOS;
		$datos1=$row_apostador->DATOS1;*/
		//echo $datos;
		//die();		
/*if($casino<>100)
{
try {
	$rs_consulta = $db->Execute("select g.id_novedad,to_char(g.fecha_NOVEDAD,'dd/mm/yyyy')fecha_alta,g.fichaje fichaje,g.acierto acierto,
									decode(g.confirmado,NULL,'SIN CONFIRMAR','CONFIRMADO') AS CONFIRMADO
							    from lavado_dinero.t_novedades_cliente g        
								where 1=1								
								$condicion_ganador
								and g.usuario_baja is null");
	}


	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}*/

$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(-20);
$salto_pagina=0;
if($rs_consulta->RowCount()<>0)
{
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
 $cuenta=$cuenta+1;
 $pdf->SetFillColor(240,240,240);
//$pdf->Ln(4);
$pdf->SetFont('Arial','B',12);
//$pdf->SetX(30);
if($row->CASINO<>$controlcasino)
{
$pdf->Cell(190,8,'Origen del/os Movimiento/s: '.$row->CASINO,0,1,'C',1);
$pdf->Cell(190,8,'desde el '.$fecha.' Hasta el '.$fhasta,0,1,'C',1);
$controlcasino=$row->CASINO;
$pdf->Ln(5);
}
$pdf->SetFont('Arial','B',11);
if($row->APELLIDO<>$controlapellido)
{
if($cuenta<>1)
	{
			$pdf->SetX(40);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(35,7,'SubTotales===>',1,0,'L',1);
			$pdf->Cell(30,7,number_format($sumafichajeparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(30,7,number_format($sumaaciertoparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(30,7,'',1,1,'L');
			$y_line=$pdf->GetY();
			$salto_pagina=number_format($y_line,0,'.',',');
			if($salto_pagina>240)
			{
					$pdf->AddPage();
					$pdf->Ln(-20);
					$pdf->SetFont('Arial','B',12);
					$pdf->Cell(190,8,'Origen del/os Movimiento/s: '.$row->CASINO,0,1,'C',1);
					$pdf->Cell(190,8,'desde el '.$fecha.' Hasta el '.$fhasta,0,1,'C',1);
					$controlcasino=$row->CASINO;
					$pdf->Ln(5);
			}
	}
$sumafichajeparcial=0;
$sumaaciertoparcial=0;
$pdf->Cell(32,8,'',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Ln(5);
$pdf->Cell(125,8,$row->APELLIDO,0,1,'L',1);
$controlapellido=$row->APELLIDO;
//$pdf->Ln(5);
	$pdf->SetX(40);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(35,6,'Fecha_Movimiento',1,0,'C');
	$pdf->Cell(30,6,'Fichaje',1,0,'C');
	$pdf->Cell(30,6,'Acierto',1,0,'C');
	$pdf->Cell(30,6,'Estado',1,1,'C');
}
//$pdf->Cell(190,8,$datos,0,1,'C');
//$pdf->Cell(190,8,$datos1,0,1,'C');
//$titulo2=$datos;
//GETx;

//$pdf->ln(10);
//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line);


//$pdf->Ln(8);
 	$pdf->SetX(40);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(35,6,$row->FECHA_ALTA,1,0,'C');
	$pdf->Cell(30,6,number_format($row->FICHAJE,2,',','.'),1,0,'R');
	$pdf->Cell(30,6,number_format($row->ACIERTO,2,',','.'),1,0,'R');
	$pdf->Cell(30,6,$row->CONFIRMADO,1,1,'L');
	$sumafichaje=$sumafichaje+$row->FICHAJE;
	$sumaacierto=$sumaacierto+$row->ACIERTO;
	$sumafichajeparcial=$sumafichajeparcial+$row->FICHAJE;
	$sumaaciertoparcial=$sumaaciertoparcial+$row->ACIERTO;
	
	
 }
 
 $pdf->SetX(40);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(35,7,'SubTotales===>',1,0,'L');
	$pdf->Cell(30,7,number_format($sumafichajeparcial,2,',','.'),1,0,'R',1);
	$pdf->Cell(30,7,number_format($sumaaciertoparcial,2,',','.'),1,0,'R',1);
	$pdf->Cell(30,7,'',1,1,'L');
$pdf->SetX(40);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(35,7,'Totales===>',1,0,'L');
	$pdf->Cell(30,7,number_format($sumafichaje,2,',','.'),1,0,'R',1);
	$pdf->Cell(30,7,number_format($sumaacierto,2,',','.'),1,0,'R',1);
	$pdf->Cell(30,7,'',1,1,'L');

$pdf->setx(40);
}
else
{
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,7,'SIN MOVIMIENTOS',0,0,'L');
}

$pdf->Output();
?>