<?php
//die('entre');
require_once("header_listado.php"); 
//print_r($_REQUEST);
//die('entre');
//$db->debug=true;
//print_r($_SESSION);
//$fecha=$_GET['fecha'];
//$fhasta=$_GET['fhasta'];
if(isset($_REQUEST['condicion']) and $_REQUEST['condicion']<>'')
	{
		$condicion=strtolower($_REQUEST['condicion']);
		//$condicion_descripcion="and lower(a.descripcion) like'%$descrip%'";
	}
	else
	{
		//$descrip="";
		$condicion="";
	
	}
if(isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado']<>0)
{
	$id_estado=$_REQUEST['id_estado'];
	$condicion_estado="and to_number(to_char(b.fecha_aparicion,'mmyyyy'))=$id_estado";
}
else
{
	$id_estado=0;
	$condicion_estado="";
}

$fecha_des=$_REQUEST['fecha_des'];
$fhasta=$_REQUEST['fhasta'];
$condicion_fecha=" and b.fecha_aparicion between to_date('$fecha_des','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')";


/*$quefecha='';*/
//try {
	$rs_consulta = $db->Execute("SELECT b.id_base idid,
							b.documento documento,
							b.descripcion nombre,
							b.id_tipo_alerta alert,
							b.id_estado_alerta id_estado,
							b.observaciones as observaciones,
							kk.descripcion as estado,
							ta.descripcion nombre_alerta,
							to_char(b.fecha_aparicion,'dd/mm/yyyy') as fecha 	 
						FROM lavado_dinero.base_alerta b,
							lavado_dinero.tipo_alerta ta,
							lavado_dinero.estado_alerta kk
						  WHERE b.id_tipo_alerta=ta.id_tipo_alerta
								and b.id_estado_alerta=kk.id_estado_alerta
								  AND TO_CHAR(B.FECHA_APARICION,'MMYYYY')<>TO_CHAR(SYSDATE,'MMYYYY')
					   and b.id_estado_alerta=3
				  $condicion
				  $condicion_estado
				  $condicion_fecha
				  order by 4, b.fecha_aparicion");
/*}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}*/	


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();


//GETx;

$pdf->ln(-10);
//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line); 


$pdf->Ln(-10);
$pdf->SetFillColor(240,240,240);
	$pdf->SetFont('Arial','B',12);
	//$pdf->Cell(190,8,'Administracion de Alertas Sistema PLA - '.$_GET['fecha'],0,1,'C',1);
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);
	if($_REQUEST['id_estado']==0)
		{
			$pdf->Cell(190,8,'MATRIZ de RIESGO -  ALERTAS PROCESADAS DESDE EL '.$fecha_des.' HASTA EL '.$fhasta,0,1,'C',1);
		}
	else
		{
			$pdf->Cell(190,8,'MATRIZ de RIESGO -  ALERTAS PROCESADAS DESDE EL '.$fecha_des.' HASTA EL '.$fhasta,0,1,'C',1);
		}
	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(35,8,'Tipo de Alerta',1,0,'C');
	$pdf->Cell(15,8,'Aparicion',1,0,'C');
	$pdf->Cell(12,8,'Estado',1,0,'C');
	$pdf->Cell(30,8,'Motivo',1,0,'C');	
	$pdf->Cell(103,8,'Alerta',1,1,'C');
	$pdf->SetFont('Arial','',6);
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
 $estoy=$pdf->GetY();
 if($estoy>265)
 {
 	$estoy=0;
		 $pdf->Addpage();
		 $pdf->Ln(-10);	
		//$pdf->Ln(-10);
$pdf->SetFillColor(240,240,240);
	$pdf->SetFont('Arial','B',12);
	 if($_REQUEST['id_estado']==0)
		{
			$pdf->Cell(190,8,'MATRIZ de RIESGO -  ALERTAS PROCESADAS DESDE EL '.$fecha_des.' HASTA EL '.$fhasta,0,1,'C',1);
		}
	else
		{
			$pdf->Cell(190,8,'MATRIZ de RIESGO -  ALERTAS PROCESADAS DESDE EL '.$fecha_des.' HASTA EL '.$fhasta,0,1,'C',1);
		}
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(35,8,'Tipo de Alerta',1,0,'C');
	$pdf->Cell(15,8,'Aparicion',1,0,'C');
	$pdf->Cell(12,8,'Estado',1,0,'C');
	$pdf->Cell(30,8,'Motivo',1,0,'C');	
	$pdf->Cell(103,8,'Alerta',1,1,'C');
	
	$pdf->SetFont('Arial','',6);		 
 } 
 $pdf->SetX(10);
  if($row->NOMBRE_ALERTA<>$repetido)
  {
		  			 $pdf->Cell(35,5,$row->NOMBRE_ALERTA,1,0,'L');
					 $repetido=$row->NOMBRE_ALERTA;
		   }
		   else
		   {
		   	$pdf->Cell(35,5,'',1,0,'L');
		   }
		   $pdf->Cell(15,5,$row->FECHA,1,0,'C');
		   $pdf->Cell(12,5,$row->ESTADO,1,0,'C');
	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(30,5,str_pad($row->DOCUMENTO,8,'0',STR_PAD_LEFT),1,0,'L');
	
	$pdf->SetFont('Arial','',6);
	$pdf->MultiCell(103,5,utf8_decode($row->NOMBRE),1,'L');
}
$pdf->Output();
?>