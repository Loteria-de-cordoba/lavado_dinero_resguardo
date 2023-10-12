<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '-'.$_GET['delegacion'];
require("header_listado.php"); 

//print_r($_GET);

//$db->debug=true;
//print_r($_SESSION);
$sumafichaje=0;
$sumaacierto=0;
$totfic_ing=0;
$totfic_ret=0;
$perdido=0;
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
	$rs_apostador = $db->Execute("SELECT  decode(cl.documento,NULL,'Sr/a. ' || decode(cl.nombre,null,cl.apellido, cl.apellido || ', ' || cl.nombre),'Sr/a. ' || decode(cl.nombre,null,cl.apellido, cl.apellido || ', ' || cl.nombre)|| ' Documento Nro. ' || cl.documento) as datos,
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
	$rs_consulta = $db->Execute("select g.id_novedad,substr(us.descripcion,1,17) as fecha_alta,g.fichaje fichaje,
									DECODE(g.cheque_nro, NULL, 'Efectivo' , 'Ch.' || g.cheque_nro) as cheque, g.acierto as acierto,
									DECODE(g.confirmado,NULL,'SIN CONFORMAR','CONFORMADO('
									  || (SELECT uu.descripcion
									FROM superusuario.usuarios uu,
									  PLA_AUDITORIA.t_novedades_cliente nov
									WHERE uu.id_usuario=nov.usuario_conforma
									AND nov.id_novedad =g.id_novedad)  || ')') AS CONFIRMADO,
								  g.mon_ing_fic as fichaje_ingreso,
								  g.mon_fic_ret as fichaje_retiro,
								  g.mon_perdido as perdido,
								  g.observa_mov as observacion
							    from PLA_AUDITORIA.t_novedades_cliente g,
								superusuario.usuarios us                
								where 1=1	
								and us.id_usuario=g.usuario									
								$condicion_ganador
								$condicion_casino
								and g.usuario_baja is null
								and (G.fichaje<>0 or G.acierto<>0)
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
$pdf->Cell(190,8,'Movimientos Pertenecientes a',0,1,'C',1);
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
	$pdf->SetX(3);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(20,6,'Regist. por',1,0,'C');
	$pdf->Cell(17,6,'Fichaje',1,0,'C');
	$pdf->Cell(17,6,'Acierto',1,0,'C');
	$pdf->Cell(12,6,'F.Pago',1,0,'C');
	$pdf->Cell(17,6,'Fic_Ingreso',1,0,'C');
	$pdf->Cell(15,6,'Fic_Retiro',1,0,'C');
	$pdf->Cell(15,6,'Perdido',1,0,'C');
	$pdf->Cell(45,6,'Novedades',1,0,'C');
	$pdf->Cell(45,6,'Estado',1,1,'C');
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
//$pdf->Ln(8);
 	$pdf->SetX(3);
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(20,6,$row->FECHA_ALTA,1,0,'L');
	$pdf->Cell(17,6,number_format($row->FICHAJE,2,',','.'),1,0,'R');
	$pdf->Cell(17,6,number_format($row->ACIERTO,2,',','.'),1,0,'R');
	$pdf->SetFont('Arial','',5);
	$pdf->Cell(12,6,$row->CHEQUE,1,0,'L');
	$pdf->Cell(17,6,number_format($row->FICHAJE_INGRESO,2,',','.'),1,0,'R');
	$pdf->Cell(15,6,number_format($row->FICHAJE_RETIRO,2,',','.'),1,0,'R');
	$pdf->Cell(15,6,number_format($row->PERDIDO,2,',','.'),1,0,'R');
	$pdf->Cell(45,6,utf8_decode($row->OBSERVACION),1,0,'L');
	$pdf->Cell(45,6,utf8_decode($row->CONFIRMADO),1,1,'L');
	$sumafichaje=$sumafichaje+$row->FICHAJE;
	$sumaacierto=$sumaacierto+$row->ACIERTO;
	$totfic_ing=$totfic_ing+$row->FICHAJE_INGRESO;
	$totfic_ret=$totfic_ret+$row->FICHAJE_RETIRO;
	$perdido=$perdido+$row->PERDIDO;
 }
$pdf->SetX(3);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(20,7,'Totales===>',1,0,'C',1);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(17,7,number_format($sumafichaje,2,',','.'),1,0,'R',1);
	$pdf->Cell(17,7,number_format($sumaacierto,2,',','.'),1,0,'R',1);
	$pdf->Cell(12,7,'',1,0,'R',1);
	$pdf->Cell(17,7,number_format($totfic_ing,2,',','.'),1,0,'R',1);
	$pdf->Cell(15,7,number_format($totfic_ret,2,',','.'),1,0,'R',1);
	$pdf->Cell(15,7,number_format($perdido,2,',','.'),1,0,'R',1);
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