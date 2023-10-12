<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '-'.$_GET['delegacion'];
require("header_listado.php"); 

//print_r($_GET);

//$db->debug=true;
//print_r($_SESSION);
$sumafichaje=0;
$sumaacierto=0;
if (isset($_GET['id_cliente'])) {
	$apostador = $_GET['id_cliente'];
	$condicion_ganador="and g.id_cliente = '$apostador'";
	}
	else 
		{
			if (isset($_POST['id_cliente'])) {
				$apostador = $_POST['id_cliente'];
				$condicion_ganador="and g.id_cliente= '$apostador'";
				} 
			else {
				$apostador = "";
				$condicion_ganador="";
			}
		}
		
if (isset($_GET['casino'])) {
	$casino = $_GET['casino'];
	$condicion_casino="and g.id_casino_novedad(+) = '$casino'";
	}
	else 
		{
			if (isset($_POST['casino'])) {
				$casino = $_POST['casino'];
				$condicion_casino="and g.id_casino_novedad(+) = '$casino'";
				} 
			else {
				$casino = "";
				$condicion_casino="";
			}
		}

$fechita=$_GET['fechita'];

// obtengo datos del apostador
try {
	$rs_apostador = $db->Execute("SELECT  decode(cl.documento,NULL,'Sr/a. ' || decode(cl.nombre,null,cl.apellido, cl.apellido|| ', ' || cl.nombre),'Sr/a. ' || decode(cl.nombre,null,cl.apellido, cl.apellido|| ', ' || cl.nombre)|| ' Documento Nro. ' || cl.documento) as datos,
									' Registrados en ' || DECODE(G.id_casino_novedad,100,'Delegacion',ca.n_casino) || ' en fecha ' || TO_CHAR(g.fecha_novedad,'DD/MM/YYYY') as datos1
								FROM PLA_AUDITORIA.t_cliente cl,
								casino.t_casinos ca,
								PLA_AUDITORIA.t_novedades_cliente g 
								where ca.id_casino(+)=g.id_casino_novedad
								$condicion_casino
								and cl.id_cliente=?
								and g.fecha_novedad=to_date(?,'dd/mm/yyyy')", array($apostador, $fechita));
	
	
	/*select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));*/
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$datos=utf8_decode($row_apostador->DATOS);
		$datos1=utf8_decode($row_apostador->DATOS1);
		//echo $datos;
		//die();		
/*if($casino<>100)
{*/
try {
	$rs_consulta = $db->Execute("select g.id_novedad,substr(us.descripcion,1,25) as fecha_alta,g.fichaje fichaje,g.acierto acierto,
									G.MON_ING_FIC INGRESO, G.MON_PERDIDO PERDIDO,
									DECODE(g.confirmado,NULL,'SIN CONFORMAR','CONFORMADO('
									  || (SELECT uu.descripcion
									FROM superusuario.usuarios uu,
									  PLA_AUDITORIA.t_novedades_cliente nov
									WHERE uu.id_usuario=nov.usuario_conforma
									AND nov.id_novedad =g.id_novedad)  || ')') AS CONFIRMADO
							    from PLA_AUDITORIA.t_novedades_cliente g,
								superusuario.usuarios us                        
								where 1=1
								and us.id_usuario=g.usuario										
								$condicion_ganador
								$condicion_casino
								and g.usuario_baja is null
								and (G.fichaje<>0 or G.acierto<>0 or G.MON_ING_FIC<>0 or G.MON_PERDIDO<>0)
								and g.fecha_novedad=to_date(?,'dd/mm/yyyy')", array($fechita));}
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
$pdf->Cell(190,8,'[Datos Resguardados] - Movimientos Pertenecientes a',0,1,'C',1);
$pdf->Cell(190,8,utf8_decode($datos),0,1,'C',1);
$pdf->Cell(190,8,$datos1,0,1,'C',1);
//$titulo2=$datos;
//GETx;

//$pdf->ln(10);
//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line);
$pdf->Ln(5);
	$pdf->SetX(20);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(35,6,'Registrado Por',1,0,'C');
	$pdf->Cell(20,6,'Fichaje',1,0,'C');
	$pdf->Cell(20,6,'Acierto',1,0,'C');
	$pdf->Cell(20,6,'Fic.Ingreso',1,0,'C');
	$pdf->Cell(20,6,'Perdido',1,0,'C');
	$pdf->Cell(55,6,'Estado',1,1,'C');
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
//$pdf->Ln(8);
 	$pdf->SetX(20);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(35,6,$row->FECHA_ALTA,1,0,'L');
	$pdf->Cell(20,6,number_format($row->FICHAJE,2,',','.'),1,0,'R');
	$pdf->Cell(20,6,number_format($row->ACIERTO,2,',','.'),1,0,'R');
	$pdf->Cell(20,6,number_format($row->INGRESO,2,',','.'),1,0,'R');
	$pdf->Cell(20,6,number_format($row->PERDIDO,2,',','.'),1,0,'R');
	$pdf->Cell(55,6,$row->CONFIRMADO,1,1,'L');
	$sumafichaje=$sumafichaje+$row->FICHAJE;
	$sumaacierto=$sumaacierto+$row->ACIERTO;
	$sumaingreso=$sumaingreso+$row->INGRESO;
	$sumaperdido=$sumaperdido+$row->PERDIDO;
 }
$pdf->SetX(20);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(35,7,'Totales===>',1,0,'C',1);
	$pdf->Cell(20,7,number_format($sumafichaje,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaacierto,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaingreso,2,',','.'),1,0,'R',1);
	$pdf->Cell(20,7,number_format($sumaperdido,2,',','.'),1,0,'R',1);
	//$pdf->Cell(30,7,'',1,1,'L',1);

$pdf->setx(20);
}
else
{
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,7,'SIN MOVIMIENTOS O FICHAJE Y ACIERTOS NULOS',0,0,'L');
}


$pdf->Output();
?>