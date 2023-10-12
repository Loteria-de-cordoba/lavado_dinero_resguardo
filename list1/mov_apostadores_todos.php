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
$sumafichajeparcialdele=0;
$sumaaciertoparcialdele=0;
$sumaingreso=0;
$sumaperdido=0;
$sumaingresoparcial=0;
$sumaperdidoparcial=0;
$sumaingresoparcialdele=0;
$sumaperdidoparcialdele=0;
$sumaretiraparcial=0;
$sumaretiraparcialdele=0;
$sumaperdidoparcial=0;
$sumaperdidoparcialdele=0;


$controlcasino='';
$controlapellido='';
$cuenta=0;
	
if (isset($_POST['casino']))
		{
			$casino = $_POST['casino'];
			if($casino<>0)
			{
					$condicion_conforma="and a.id_casino_novedad ='$casino'";
			}
			else
			{
				$condicion_conforma='';
			}	
			//$condicion_conforma="and a.id_casino_novedad ='$casino'";
		} 
		else
		{
		if(isset($_GET['casino']))
		 {
					$casino = $_GET['casino'];
					if($casino<>0)
			{
					$condicion_conforma="and a.id_casino_novedad ='$casino'";
			}
			else
			{
				$condicion_conforma='';
			}	
					//$condicion_conforma="and a.id_casino_novedad ='$casino'";
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

//agrego para consulta masiva

if(isset($_GET['masivo']) and $_GET['masivo']<>'')//es masivo
		 {
					$masivo = strtolower($_GET['masivo']);
					//$condicion_masivo="and lower(b.apellido) like '%$masivo%'";
					$condicion_masivo="and decode(nombre,NULL,lower(b.apellido),lower(b.apellido || ', ' || b.nombre)) like '%$masivo%'";
					//consulta
					try {
$rs_consulta = $db->Execute("SELECT
					b.id_cliente,
					TO_CHAR(a.fecha_novedad,'dd/mm/yyyy') AS fecha_alta,
				   a.id_casino_novedad as casinopasa,
					MAX(b.nombre) nombre ,
					MAX(b.apellido) apellido,
					DECODE(MAX(a.id_casino_novedad),100,'Delegacion',MAX(substr(c.n_casino,8))) as casinoorigen,
					DECODE(MAX(b.id_casino),100,'Delegacion',MAX(c.n_casino))
					|| ' (Ag. '
					|| MAX(SUBSTR(us.descripcion,1,20))
					|| ')' casino,
					MAX(b.id_casino) id_casino,
					SUM(a.fichaje) fichaje,
					SUM(a.acierto) acierto,
					SUM(a.mon_ing_fic) ingreso,
				  	SUM(a.mon_perdido) perdido,
				  	SUM(a.mon_FIC_RET) RETIRA	
				  FROM lavado_dinero.t_novedades_cliente a,
					lavado_dinero.t_cliente b,
					casino.t_casinos c,
					SUPERUSUARIO.USUARIOS US
				  WHERE a.id_cliente=b.id_cliente
				  AND a.id_casino_novedad   =c.id_casino(+)
				  AND us.id_usuario =b.usuario
				  	$condicion_masivo
				  AND b.fecha_baja IS NULL 
				  AND A.USUARIO_BAJA IS NULL 
				  and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
				  GROUP BY b.id_cliente, a.fecha_novedad, a.id_casino_novedad
				  order by a.id_casino_novedad,a.fecha_novedad desc");}
				catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}	
		}
else//no es masivo
{

if($casino<>100)
{
if($casino<>0)
{
try {
$rs_consulta = $db->Execute("select b.id_cliente,   DECODE(a.fecha_novedad, NULL, 'SIN MOV.', 
				TO_CHAR(a.fecha_novedad,'DD/MM/YYYY')) AS fecha_alta,
				decode(b.documento,NULL,b.apellido || decode(b.nombre,null,' ',', ' || b.nombre), b.apellido || decode(b.nombre,null,' ',', ' || b.nombre) || ' - Documento Nro. ' || b.documento)  as apellido,
				a.id_novedad , a.fichaje fichaje, a.acierto acierto,
				a.MON_ING_FIC INGRESO, A.MON_FIC_RET RETIRA, a.MON_PERDIDO PERDIDO,
				c.n_casino as casino, b.id_casino,
				decode(a.confirmado,NULL,'SIN CONFORMAR','CONFORMADO('
									  || (SELECT uu.descripcion
									FROM superusuario.usuarios uu,
									  lavado_dinero.t_novedades_cliente nov
									WHERE uu.id_usuario=nov.usuario_conforma
									AND nov.id_novedad =a.id_novedad)  || ')') AS CONFIRMADO,
									A.OBSERVA_MOV AS OBB						
      			from lavado_dinero.t_novedades_cliente a,
				lavado_dinero.t_cliente b,
				casino.t_casinos c
				where  a.id_cliente=b.id_cliente
				AND a.id_casino_novedad(+)      =c.id_casino
				$condicion_conforma
				$condicion_apostador
				and b.fecha_baja is null
				AND A.USUARIO_BAJA IS NULL
				and (a.fichaje<>0 or a.acierto<>0 or a.MON_ING_FIC<>0 or a.MON_PERDIDO<>0)
        		and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')					
        		order by 
				b.id_casino, b.apellido asc, a.fecha_novedad desc");}
				catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
}
else
{
try {
$rs_consulta = $db->Execute("select b.id_cliente,   DECODE(a.fecha_novedad, NULL, 'SIN MOV.', 
				TO_CHAR(a.fecha_novedad,'DD/MM/YYYY')) AS fecha_alta,
			decode(b.documento,NULL,b.apellido || decode(b.nombre,null,' ',', ' || b.nombre), b.apellido || decode(b.nombre,null,' ',', ' || b.nombre) || ' - Documento Nro. ' || b.documento)  as apellido,
				a.id_novedad , a.fichaje fichaje, a.acierto acierto,
				a.MON_ING_FIC INGRESO, A.MON_FIC_RET RETIRA,a.MON_PERDIDO PERDIDO,
				decode(c.n_casino,null,'Delegacion',c.n_casino) as casino, a.id_casino_novedad,
				decode(a.confirmado,NULL,'SIN CONFORMAR','CONFORMADO('
									  || (SELECT uu.descripcion
									FROM superusuario.usuarios uu,
									  lavado_dinero.t_novedades_cliente nov
									WHERE uu.id_usuario=nov.usuario_conforma
									AND nov.id_novedad =a.id_novedad)  || ')') AS CONFIRMADO,
									A.OBSERVA_MOV AS OBB											
      			from lavado_dinero.t_novedades_cliente a,
				lavado_dinero.t_cliente b,
				casino.t_casinos c
				where  a.id_cliente=b.id_cliente
				AND a.id_casino_novedad =c.id_casino(+)
				$condicion_conforma
				$condicion_apostador
				and b.fecha_baja is null
				AND A.USUARIO_BAJA IS NULL
				and (a.fichaje<>0 or a.acierto<>0 or a.MON_ING_FIC<>0 or a.MON_PERDIDO<>0)
        		and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')					
        		order by 
				a.id_casino_novedad, b.apellido asc, a.fecha_novedad desc");}
				catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
}
}
else
{
try {
	$rs_consulta= $db->Execute("select b.id_cliente,   DECODE(a.fecha_novedad, NULL, 'SIN MOV.', 
				TO_CHAR(a.fecha_novedad,'DD/MM/YYYY')) AS fecha_alta,
			decode(b.documento,NULL,b.apellido || decode(b.nombre,null,' ',', ' || b.nombre), b.apellido || decode(b.nombre,null,' ',', ' || b.nombre) || ' - Documento Nro. ' || b.documento)  as apellido,
				a.id_novedad , a.fichaje, a.acierto,
				a.MON_ING_FIC INGRESO, A.MON_FIC_RET RETIRA, a.MON_PERDIDO PERDIDO,
				'Delegacion' as casino, '100' as id_casino,
				decode(a.confirmado,NULL,'SIN CONFORMAR','CONFORMADO('
									  || (SELECT uu.descripcion
									FROM superusuario.usuarios uu,
									  lavado_dinero.t_novedades_cliente nov
									WHERE uu.id_usuario=nov.usuario_conforma
									AND nov.id_novedad =a.id_novedad)  || ')') AS CONFIRMADO,
									A.OBSERVA_MOV AS OBB												
      			from lavado_dinero.t_novedades_cliente a,
				lavado_dinero.t_cliente b
				where a.id_cliente(+)=b.id_cliente
				$condicion_conforma
				$condicion_apostador
				and b.fecha_baja is null
				and (a.fichaje<>0 or a.acierto<>0 or a.MON_ING_FIC<>0 or a.MON_PERDIDO<>0)
				AND A.USUARIO_BAJA IS NULL
        		and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
        		order by 
				b.apellido asc, a.fecha_novedad desc");}
				catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
}

}//termino no masivo
if($casino<>0)
{
$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(-20);
$salto_pagina=0;
$bandera=0;	
if($rs_consulta->RowCount()<>0)
{
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
 $cuenta=$cuenta+1;
 $pdf->SetFillColor(240,240,240);
//$pdf->Ln(4);
$pdf->SetFont('Arial','B',12);
//$pdf->SetX(30);
if($row->CASINO<>$controlcasino and $salto_pagina<200)
{

if($cuenta<>1)
	{
			$pdf->SetX(8);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(35,7,'SubTotales===>',1,0,'L',1);
			$pdf->Cell(20,7,number_format($sumafichajeparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(20,7,number_format($sumaaciertoparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(20,7,number_format($sumaingresoparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(20,7,number_format($sumaretiraparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(20,7,number_format($sumaperdidoparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(65,7,'',1,1,'L');
			$bandera=1;
			
	}
				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(190,8,'Origen del/os Movimiento/s: '.$row->CASINO,0,1,'C',1);
				$pdf->Cell(190,8,'desde el '.$fecha.' Hasta el '.$fhasta,0,1,'C',1);
				$controlcasino=$row->CASINO;
				$pdf->Ln(5);
		
}
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
					if($cuenta<>1)
					{
						$pdf->Cell(32,8,'',0,0,'L');
						$pdf->SetFont('Arial','B',11);
						$pdf->Ln(5);
						//$pdf->Cell(125,8,$row->APELLIDO,0,1,'L',1);
						$pdf->Cell(125,8,utf8_decode(utf8_decode($controlapellido)),0,1,'L',1);
						
					}
			}
$pdf->SetFont('Arial','B',11);
if($row->APELLIDO<>$controlapellido)
{
if($cuenta<>1 && $bandera<>1)
	{
			$pdf->SetX(8);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(35,7,'SubTotales===>',1,0,'L',1);
			$pdf->Cell(20,7,number_format($sumafichajeparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(20,7,number_format($sumaaciertoparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(20,7,number_format($sumaingresoparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(20,7,number_format($sumaretiraparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(20,7,number_format($sumaperdidoparcial,2,',','.'),1,0,'R',1);
			$pdf->Cell(65,7,'',1,1,'L');
			
	}
$sumafichajeparcial=0;
$sumaaciertoparcial=0;
$sumaingresoparcial=0;
$sumaperdidoparcial=0;
$sumaretiraparcial=0;
$sumaperdidoparcial=0;


$pdf->Cell(32,8,'',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Ln(5);
$pdf->Cell(125,8,utf8_decode(utf8_decode($row->APELLIDO)),0,1,'L',1);
$controlapellido=$row->APELLIDO;
//$pdf->Ln(5);
	$pdf->SetX(8);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(35,6,'Fecha_Movimiento',1,0,'C');
	$pdf->Cell(20,6,'Fichaje',1,0,'C');
	$pdf->Cell(20,6,'Acierto',1,0,'C');
	$pdf->Cell(20,6,'Fic.Ingreso',1,0,'C');
	$pdf->Cell(20,6,'Fic.Retira',1,0,'C');
	$pdf->Cell(20,6,'Perdido',1,0,'C');
	$pdf->Cell(65,6,'Novedad/Observacion',1,1,'C');
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
 	$pdf->SetX(8);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(35,6,$row->FECHA_ALTA,1,0,'C');
	$pdf->Cell(20,6,number_format($row->FICHAJE,2,',','.'),1,0,'R');
	$pdf->Cell(20,6,number_format($row->ACIERTO,2,',','.'),1,0,'R');
	$pdf->Cell(20,6,number_format($row->INGRESO,2,',','.'),1,0,'R');
	$pdf->Cell(20,6,number_format($row->RETIRA,2,',','.'),1,0,'R');
	$pdf->Cell(20,6,number_format($row->PERDIDO,2,',','.'),1,0,'R');
	$pdf->SetFont('Arial','',6);
	$pdf->MultiCell(65,6,utf8_decode($row->OBB),1,'J');
	$pdf->SetFont('Arial','',7);
	$sumafichaje=$sumafichaje+$row->FICHAJE;
	$sumaacierto=$sumaacierto+$row->ACIERTO;
	$sumaingreso=$sumaingreso+$row->INGRESO;
	$sumaretira=$sumaretira+$row->RETIRA;
	$sumaperdido=$sumaperdido+$row->PERDIDO;
	$sumafichajeparcial=$sumafichajeparcial+$row->FICHAJE;
	$sumaaciertoparcial=$sumaaciertoparcial+$row->ACIERTO;
	$sumaingresoparcial=$sumaingresoparcial+$row->INGRESO;
	$sumaretiraparcial=$sumaretiraparcial+$row->RETIRA;
	$sumaperdidoparcial=$sumaperdidoparcial+$row->PERDIDO;
	
	
 }
 
 $pdf->SetX(8);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(35,7,'SubTotales===>',1,0,'L',1);
	$pdf->Cell(20,7,number_format($sumafichajeparcial,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaaciertoparcial,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaingresoparcial,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaretiraparcial,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaperdidoparcial,2,',','.'),1,0,'R',1);
	$pdf->Cell(65,7,'',1,1,'L');
$pdf->SetX(8);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(35,7,'Totales===>',1,0,'L',1);
	$pdf->Cell(20,7,number_format($sumafichaje,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaacierto,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaingreso,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaretira,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaperdido,2,',','.'),1,0,'R',1);
	$pdf->Cell(65,7,'',1,1,'L');

$pdf->setx(8);
}
else
{
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,7,'SIN MOVIMIENTOS',0,0,'L');
}

$pdf->Output();
}
else//voy por todos los casinos
 {
 	if($apostador=='0')
	{
			$pdf=new PDF('P');
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->Ln(-20);
			$salto_pagina=0;
			$bandera=1;	
			if($rs_consulta->RowCount()<>0)
			{
			while ($row = $rs_consulta->FetchNextObject($toupper=true))
			 {
			 $cuenta=$cuenta+1;
			 $pdf->SetFillColor(240,240,240);
			//$pdf->Ln(4);
			$pdf->SetFont('Arial','B',12);
			//$pdf->SetX(30);
			if($row->APELLIDO<>$controlapellido or ($row->APELLIDO==$controlapellido and $row->CASINO<>$controlcasino))
			{
			
			if($cuenta<>1)
				{
					//if($bandera<>0)
					//{
						$pdf->SetX(8);
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(35,7,'SubTotales===>',1,0,'L',1);
						$pdf->Cell(20,7,number_format($sumafichajeparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(20,7,number_format($sumaaciertoparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(20,7,number_format($sumaingresoparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(20,7,number_format($sumaretiraparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(20,7,number_format($sumaperdidoparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(65,7,'',1,1,'L');
						$sumafichajeparcial=0;
						$sumaaciertoparcial=0;
						$sumaingresoparcial=0;
						$sumaretiraparcial=0;
						$sumaperdidoparcial=0;
						$sumaretiraparcial=0;
						$sumaperdidoparcial=0;
						
						$bandera=0;
					//}
					if($row->CASINO<>$controlcasino)
					{
					//agrego subtotal casino
					//para subtotales de las delegaciones
								$pdf->Ln(5);
								$pdf->SetX(8);
									$pdf->SetFont('Arial','B',8);
									//$pdf->Cell(35,7,$row->CASINO,1,0,'L',1);
									$pdf->Cell(35,7,'SubTotal Casino  ',1,0,'L',1);
									$pdf->Cell(20,7,number_format($sumafichajeparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaaciertoparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaingresoparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaretiraparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaperdidoparcialdele,2,',','.'),1,0,'R',1);
									$sumafichajeparcialdele=0;
									$sumaaciertoparcialdele=0;
									$sumaingresoparcialdele=0;
									$sumaretiraparcialdele=0;
									$sumaperdidoparcialdele=0;
									$pdf->Cell(65,7,'',1,1,'L');
					}//fin total casino
					
						
				}
							//if(($salto_pagina<240 and $row->CASINO<>$controlcasino) or ($salto_pagina<240 and $row->CASINO<>$controlcasino and $row->APELLIDO<>$controlapellido))
							if($salto_pagina<240 and $row->CASINO<>$controlcasino)
							{
								if($salto_pagina<195)
								{
								/*if($cuenta<>1 )
									{
								//para subtotales de las delegaciones
								$pdf->Ln(5);
								$pdf->SetX(40);
									$pdf->SetFont('Arial','B',8);
									//$pdf->Cell(35,7,$row->CASINO,1,0,'L',1);
									$pdf->Cell(35,7,'SubTotal Casino  ',1,0,'L',1);
									$pdf->Cell(30,7,number_format($sumafichajeparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(30,7,number_format($sumaaciertoparcialdele,2,',','.'),1,0,'R',1);
									$sumafichajeparcialdele=0;
									$sumaaciertoparcialdele=0;
									$pdf->Cell(47,7,'',1,1,'L');
								}*/
								//$pdf->Cell(190,8,'apsooo',0,1,'C',1);
								$pdf->Ln(5);
								$pdf->SetFont('Arial','B',12);
								$pdf->Cell(190,8,'Origen del/os Movimiento/s: '.$row->CASINO,0,1,'C',1);
								$pdf->Cell(190,8,'desde el '.$fecha.' Hasta el '.$fhasta,0,1,'C',1);
								$controlcasino=$row->CASINO;
								$pdf->Ln(5);
								if($controlapellido==$row->APELLIDO)
								{
									$pdf->SetFont('Arial','B',11);
									$pdf->Cell(125,8,utf8_decode(utf8_decode($row->APELLIDO)),0,1,'L',1);
									$pdf->Ln(5);
								}
								}
								else
								{
									$pdf->Ln(15);
								}
							}
					
			}
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
								$salto_pagina=0;
								if($cuenta<>1)
								{
									$pdf->Cell(32,8,'',0,0,'L');
									$pdf->SetFont('Arial','B',11);
									$pdf->Ln(5);
									//$pdf->Cell(35,7,'pasooo',1,0,'L');
									$pdf->Cell(125,8,utf8_decode(utf8_decode($row->APELLIDO)),0,1,'L',1);
									$controlapellido=$row->APELLIDO;
								}
						}
			//if($salto_pagina<220)
			//{
			$pdf->SetFont('Arial','B',11);
			if($row->APELLIDO<>$controlapellido)
			{
			if($cuenta<>1 and $bandera==1)
				{
						$pdf->SetX(8);
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(35,7,'SubTotales===>',1,0,'L',1);
						
						$pdf->Cell(20,7,number_format($sumafichajeparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(20,7,number_format($sumaaciertoparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(20,7,number_format($sumaingresoparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(20,7,number_format($sumaretiraparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(20,7,number_format($sumaperdidoparcial,2,',','.'),1,0,'R',1);
						$pdf->Cell(65,7,'',1,1,'L');
						$bandera=0;
						
				}
			$sumafichajeparcial=0;
			$sumaaciertoparcial=0;
			$sumaingresoparcial=0;
			$sumaperdidoparcial=0;
			$sumaretiraparcial=0;
			$sumaperdidoparcial=0;


			//$pdf->Cell(190,8,$controlcasino.$row->CASINO,0,1,'C',1);
			
			
					if($salto_pagina<195)
					{
						
						$pdf->Ln(5);
					}
					else
					{
						$pdf->AddPage();
										$pdf->Ln(-20);
										$pdf->SetFont('Arial','B',12);
										$pdf->Cell(190,8,'Origen del/os Movimiento/s: '.$row->CASINO,0,1,'C',1);
										$pdf->Cell(190,8,'desde el '.$fecha.' Hasta el '.$fhasta,0,1,'C',1);
										$controlcasino=$row->CASINO;
										$pdf->Ln(5);
					}
			//}
			
			//$pdf->Cell(32,8,'',0,0,'L');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(125,8,utf8_decode(utf8_decode($row->APELLIDO)),0,1,'L',1);
			$controlapellido=$row->APELLIDO;
			//$pdf->Ln(5);
				$pdf->SetX(8);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(35,6,'Fecha_Movimiento',1,0,'C');
				$pdf->Cell(20,6,'Fichaje',1,0,'C');
				$pdf->Cell(20,6,'Acierto',1,0,'C');
				$pdf->Cell(20,6,'Fic.Ingreso',1,0,'C');
				$pdf->Cell(20,6,'Retira',1,0,'C');
				$pdf->Cell(20,6,'Perdido',1,0,'C');
				$pdf->Cell(65,6,'Novedad/Observacion',1,1,'C');
			}
			
			$y_line=215;
			
			
				$pdf->SetX(8);
				$pdf->SetFont('Arial','',7);
				//$pdf->Cell(35,6,$row->FECHA_ALTA." controlapellido ".$controlapellido." controlcasino ".$controlcasino,1,0,'C');
				$pdf->Cell(35,6,$row->FECHA_ALTA,1,0,'C');
				$pdf->Cell(20,6,number_format($row->FICHAJE,2,',','.'),1,0,'R');
				$pdf->Cell(20,6,number_format($row->ACIERTO,2,',','.'),1,0,'R');
				$pdf->Cell(20,6,number_format($row->INGRESO,2,',','.'),1,0,'R');
				$pdf->Cell(20,6,number_format($row->RETIRA,2,',','.'),1,0,'R');
				$pdf->Cell(20,6,number_format($row->PERDIDO,2,',','.'),1,0,'R');
				$pdf->SetFont('Arial','',6);
				$pdf->MultiCell(65,6,utf8_decode($row->OBB),1,'J');
				$pdf->SetFont('Arial','',7);
				$sumafichaje=$sumafichaje+$row->FICHAJE;
				$sumaacierto=$sumaacierto+$row->ACIERTO;
				$sumaingreso=$sumaingreso+$row->INGRESO;
				$sumaretira=$sumaretira+$row->RETIRA;
				$sumaperdido=$sumaperdido+$row->PERDIDO;
				$sumafichajeparcial=$sumafichajeparcial+$row->FICHAJE;
				$sumaaciertoparcial=$sumaaciertoparcial+$row->ACIERTO;
				$sumaingresoparcial=$sumaingresoparcial+$row->INGRESO;
				$sumaretiraparcial=$sumaretiraparcial+$row->RETIRA;
				$sumaperdidoparcial=$sumaperdidoparcial+$row->PERDIDO;
				$sumafichajeparcialdele=$sumafichajeparcialdele+$row->FICHAJE;
				$sumaaciertoparcialdele=$sumaaciertoparcialdele+$row->ACIERTO;
				$sumaingresoparcialdele=$sumaingresoparcialdele+$row->INGRESO;
				$sumaretiraparcialdele=$sumaretiraparcialdele+$row->RETIRA;
				$sumaperdidoparcialdele=$sumaperdidoparcialdele+$row->PERDIDO;
				
			
			 }
			 //$pdf->Ln(5);
			
			 $pdf->SetX(8);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(35,7,'SubTotales===>',1,0,'L',1);
				
				$pdf->Cell(20,7,number_format($sumafichajeparcial,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaaciertoparcial,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaingresoparcial,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaretiraparcial,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaperdidoparcial,2,',','.'),1,0,'R',1);
				//$pdf->Cell(30,7,'',1,1,'L');
				 $pdf->Ln(10);
				$pdf->SetX(8);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(35,7,'SubTotal Cas./Del.  ',1,0,'L',1);
				$pdf->Cell(20,7,number_format($sumafichajeparcialdele,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaaciertoparcialdele,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaingresoparcialdele,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaretiraparcialdele,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaperdidoparcialdele,2,',','.'),1,0,'R',1);
				//$pdf->Cell(30,7,'',1,1,'L');
				$pdf->Ln(10);
			$pdf->SetX(8);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(35,7,'Totales===>',1,0,'L',1);
				$pdf->Cell(20,7,number_format($sumafichaje,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaacierto,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaingreso,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaretira,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaperdido,2,',','.'),1,0,'R',1);
				
				//$pdf->Cell(30,7,'',1,1,'L');
			
			$pdf->setx(8);
			}
			else
			{
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(35,7,'SIN MOVIMIENTOS',0,0,'L');
			}
			
$pdf->Output();
}//todos los apostadores
else//un solo apostador
{
		
			$pdf=new PDF('P');
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->Ln(-20);
			$salto_pagina=0;
			$bandera=1;	
			if($rs_consulta->RowCount()<>0)
			{
			while ($row = $rs_consulta->FetchNextObject($toupper=true))
			 {
			 $cuenta=$cuenta+1;
			 $pdf->SetFillColor(240,240,240);
			//$pdf->Ln(4);
			$pdf->SetFont('Arial','B',12);
				if($salto_pagina<240 and $row->CASINO<>$controlcasino)
							{
									if($cuenta==1)
									{
										$pdf->Cell(32,8,'',0,0,'L');
										$pdf->SetFont('Arial','B',11);
										$pdf->Ln(5);
									
										$pdf->Cell(140,8,'Movimientos del Sr: '.utf8_decode(utf8_decode($row->APELLIDO)),0,1,'L',1);
										$pdf->Cell(140,8,'Desde el '.$fecha.' Hasta el '.$fhasta,0,1,'L',1);
										$controlapellido=$row->APELLIDO;
										
									}
								if($salto_pagina<220)
								{
								if($cuenta<>1)
									{
								//para subtotales de las delegaciones
								$pdf->Ln(5);
								$pdf->SetX(8);
									$pdf->SetFont('Arial','B',8);
									$pdf->Cell(35,7,'SubTotal  ',1,0,'L',1);
									$pdf->Cell(20,7,number_format($sumafichajeparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaaciertoparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaingresoparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaretiraparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaperdidoparcialdele,2,',','.'),1,0,'R',1);
									$sumafichajeparcialdele=0;
									$sumaaciertoparcialdele=0;
									$sumaingresoparcialdele=0;
									$sumaretiraparcialdele=0;
									$sumaperdidoparcialdele=0;
									//$pdf->Cell(30,7,'',1,1,'L');
								}
								$pdf->Ln(10);
								$pdf->SetFont('Arial','B',12);
								$pdf->Cell(190,8,$row->CASINO,0,1,'C',1);
								
								$controlcasino=$row->CASINO;
								$pdf->Ln(5);
								$pdf->SetX(8);
									$pdf->SetFont('Arial','B',9);
									$pdf->Cell(35,6,'Fecha_Movimiento',1,0,'C');
									$pdf->Cell(20,6,'Fichaje',1,0,'C');
									$pdf->Cell(20,6,'Acierto',1,0,'C');
									$pdf->Cell(20,6,'Fic.Ingreso',1,0,'C');
									$pdf->Cell(20,6,'Retira',1,0,'C');
									$pdf->Cell(20,6,'Perdido',1,0,'C');
									$pdf->Cell(65,6,'Novedad/Observacion',1,1,'C');
								}
								else
								{
									$pdf->Ln(15);
								}
							}
					
			//}
			$y_line=$pdf->GetY();
			$salto_pagina=number_format($y_line,0,'.',',');
						if($salto_pagina>240)
						{
								$pdf->AddPage();
								$pdf->Ln(-15);
								$pdf->Cell(32,8,'',0,0,'L');
										$pdf->SetFont('Arial','B',11);
										$pdf->Ln(5);
									
										$pdf->Cell(140,8,'Movimientos del Sr: '.utf8_decode(utf8_decode($row->APELLIDO)),0,1,'L',1);
										$pdf->Cell(140,8,'Desde el '.$fecha.' Hasta el '.$fhasta,0,1,'L',1);
										$controlapellido=$row->APELLIDO;
									$pdf->Ln(5);
								$pdf->SetFont('Arial','B',12);
								
								
								if($row->CASINO<>$controlcasino)
								{
								$controlcasino=$row->CASINO;
								$pdf->Ln(5);
								$pdf->SetX(8);
									$pdf->SetFont('Arial','B',8);
									$pdf->Cell(35,7,'SubTotal  ',1,0,'L',1);
									$pdf->Cell(20,7,number_format($sumafichajeparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaaciertoparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaingresoparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaretiraparcialdele,2,',','.'),1,0,'R',1);
									$pdf->Cell(20,7,number_format($sumaperdidoparcialdele,2,',','.'),1,0,'R',1);
									$sumafichajeparcialdele=0;
									$sumaaciertoparcialdele=0;
									$sumaingresoparcialdele=0;
									$sumaretiraparcialdele=0;
									$sumaperdidoparcialdele=0;
									$pdf->Ln(10);
									$pdf->Cell(190,8,$row->CASINO,0,1,'C',1);
									$pdf->Ln(5);
								}
								else
								{
								$pdf->SetFont('Arial','B',11);
									$pdf->Cell(190,8,$row->CASINO,0,1,'C',1);
									$pdf->Ln(5);
								}
								$pdf->Ln(5);
								$salto_pagina=0;
								if($cuenta<>1)
								{
									
									$pdf->SetX(8);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(35,6,'Fecha_Movimiento',1,0,'C');
				$pdf->Cell(20,6,'Fichaje',1,0,'C');
				$pdf->Cell(20,6,'Acierto',1,0,'C');
				$pdf->Cell(20,6,'Fic.Ingreso',1,0,'C');
				$pdf->Cell(20,6,'Fic.Retira',1,0,'C');
				$pdf->Cell(20,6,'Perdido',1,0,'C');
				$pdf->Cell(65,6,'Novedad/Observacion',1,1,'C');
								}
						}
			
			$pdf->SetFont('Arial','B',11);
			if($row->APELLIDO<>$controlapellido)
			{
			
			$sumafichajeparcial=0;
			$sumaaciertoparcial=0;
			
			$pdf->Cell(32,8,'',0,0,'L');
			$pdf->SetFont('Arial','B',11);
			$pdf->Ln(5);
			$pdf->Cell(125,8,'Movimientos del Sr: '.utf8_decode(utf8_decode($row->APELLIDO)),0,1,'L',1);
			$pdf->Cell(190,8,'desde el '.$fecha.' Hasta el '.$fhasta,0,1,'C',1);
			$controlapellido=$row->APELLIDO;
			
				$pdf->SetX(8);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(35,6,'Fecha_Movimiento',1,0,'C');
				$pdf->Cell(20,6,'Fichaje',1,0,'C');
				$pdf->Cell(20,6,'Acierto',1,0,'C');
				$pdf->Cell(20,6,'Fic.Ingreso',1,0,'C');
				$pdf->Cell(20,6,'Fic.Retira',1,0,'C');
				$pdf->Cell(20,6,'Perdido',1,0,'C');
				$pdf->Cell(65,6,'Novedad/Observacion',1,1,'C');
			}
			
			$y_line=215;
			
			
				$pdf->SetX(8);
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(35,6,$row->FECHA_ALTA,1,0,'C');
				$pdf->Cell(20,6,number_format($row->FICHAJE,2,',','.'),1,0,'R');
				$pdf->Cell(20,6,number_format($row->ACIERTO,2,',','.'),1,0,'R');
				$pdf->Cell(20,6,number_format($row->INGRESO,2,',','.'),1,0,'R');
				$pdf->Cell(20,6,number_format($row->RETIRA,2,',','.'),1,0,'R');
				$pdf->Cell(20,6,number_format($row->PERDIDO,2,',','.'),1,0,'R');
				$pdf->SetFont('Arial','',6);
				$pdf->MultiCell(65,6,utf8_decode($row->OBB),1,'J');
				$pdf->SetFont('Arial','',7);
				$sumafichaje=$sumafichaje+$row->FICHAJE;
				$sumaacierto=$sumaacierto+$row->ACIERTO;
				$sumaingreso=$sumaingreso+$row->INGRESO;
				$sumaretira=$sumaretira+$row->RETIRA;
				$sumaperdido=$sumaperdido+$row->PERDIDO;
				$sumafichajeparcial=$sumafichajeparcial+$row->FICHAJE;
				$sumaaciertoparcial=$sumaaciertoparcial+$row->ACIERTO;
				$sumaingresoparcial=$sumaingresoparcial+$row->INGRESO;
				$sumaretiraparcial=$sumaretiraparcial+$row->RETIRA;
				$sumaperdidoparcial=$sumaperdidoparcial+$row->PERDIDO;
				$sumafichajeparcialdele=$sumafichajeparcialdele+$row->FICHAJE;
				$sumaaciertoparcialdele=$sumaaciertoparcialdele+$row->ACIERTO;
				$sumaingresoparcialdele=$sumaingresoparcialdele+$row->INGRESO;
				$sumaretiraparcialdele=$sumaretiraoparcialdele+$row->RETIRA;
				$sumaperdidoparcialdele=$sumaperdidoparcialdele+$row->PERDIDO;
				
			
			 }
			 
				 $pdf->Ln(5);
				$pdf->SetX(8);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(35,7,'SubTotal   ',1,0,'L',1);
				$pdf->Cell(20,7,number_format($sumafichajeparcialdele,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaaciertoparcialdele,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaingresoparcialdele,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaretiraparcialdele,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaperdidoparcialdele,2,',','.'),1,0,'R',1);
				//$pdf->Cell(30,7,'',1,1,'L');
				$pdf->Ln(10);
			$pdf->SetX(8);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(35,7,'Totales===>',1,0,'L',1);
				$pdf->Cell(20,7,number_format($sumafichaje,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaacierto,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaingreso,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaretira,2,',','.'),1,0,'R',1);
				$pdf->Cell(20,7,number_format($sumaperdido,2,',','.'),1,0,'R',1);
				//$pdf->Cell(30,7,'',1,1,'L');
			
			$pdf->setx(8);
			}
			else
			{
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(35,7,'SIN MOVIMIENTOS',0,0,'L');
			}
			

		$pdf->Output();
}
 }
?>