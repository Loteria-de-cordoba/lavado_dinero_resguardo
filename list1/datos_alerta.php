<?php
//die('entre');
require_once("header_listado.php"); 
//print_r($_GET);
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
if(isset($_REQUEST['condicion_estado']) and $_REQUEST['condicion_estado']<>'')
	{
		$condicion_estado=strtolower($_REQUEST['condicion_estado']);
		//$condicion_descripcion="and lower(a.descripcion) like'%$descrip%'";
	}
	else
	{
		//$descrip="";
		$condicion_estado="";
	
	}
/*$quefecha='';*/
//try {
	$rs_consulta = $db->Execute("SELECT b.id_base idid,
				b.documento documento,
				b.descripcion nombre,
				b.id_tipo_alerta alert,
				b.id_estado_alerta id_estado,
				b.observaciones as observaciones,
				TO_CHAR(b.fecha_aparicion,'DD/MM/YYYY') as fefe,
				kk.descripcion as estado,
				ta.descripcion nombre_alerta  	 
				FROM PLA_AUDITORIA.base_alerta b,
					PLA_AUDITORIA.tipo_alerta ta,
					PLA_AUDITORIA.estado_alerta kk
				  WHERE b.id_tipo_alerta=ta.id_tipo_alerta
				  		and b.id_estado_alerta=kk.id_estado_alerta						
				  $condicion
				  $condicion_estado										
			MINUS
				SELECT b.id_base idid,
				b.documento documento,
				b.descripcion nombre,
				b.id_tipo_alerta alert,
				b.id_estado_alerta id_estado,
				b.observaciones as observaciones,
				TO_CHAR(b.fecha_aparicion,'DD/MM/YYYY') as fefe,
				kk.descripcion as estado,
				ta.descripcion nombre_alerta  	 
				FROM PLA_AUDITORIA.base_alerta b,
					PLA_AUDITORIA.tipo_alerta ta,
					PLA_AUDITORIA.estado_alerta kk
				  WHERE b.id_tipo_alerta=ta.id_tipo_alerta
				  		and b.id_estado_alerta=kk.id_estado_alerta
						  AND TO_CHAR(B.FECHA_APARICION,'MMYYYY')<>TO_CHAR(SYSDATE,'MMYYYY')
              and b.id_estado_alerta=3
				  $condicion
				  $condicion_estado					
				order by 4,5");
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
	$pdf->SetFont('Arial','B',15);
	//$pdf->Cell(190,8,'Administracion de Alertas Sistema PLA - '.$_GET['fecha'],0,1,'C',1);
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);
    $pdf->Cell(190,8,'MATRIZ de RIESGO - ADMINISTRACION de ALERTAS ('.$_GET['fecha'].')',0,1,'C',1);
	 $pdf->Cell(190,8,'[Datos  Resguardados]',0,1,'C',1);
	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(35,8,'Tipo de Alerta',1,0,'C');
	$pdf->Cell(15,8,'Aparicion',1,0,'C');
	$pdf->Cell(17,8,'Estado',1,0,'C');
	$pdf->Cell(27,8,'Motivo',1,0,'C');
	$pdf->Cell(93,8,'Alerta',1,1,'C');
	
	
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
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(190,8,'MATRIZ de RIESGO - ADMINISTRACION de ALERTAS ('.$_GET['fecha'].')',0,1,'C',1);
	$pdf->Cell(190,8,'[Datos  Resguardados]',0,1,'C',1);
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(35,8,'Tipo de Alerta',1,0,'C');	
	$pdf->Cell(15,8,'Aparicion',1,0,'C');
	$pdf->Cell(17,8,'Estado',1,0,'C');
	$pdf->Cell(27,8,'Motivo',1,0,'C');
	$pdf->Cell(93,8,'Alerta',1,1,'C');
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
		  $pdf->Cell(15,5,$row->FEFE,1,0,'C');
		   $pdf->Cell(17,5,$row->ESTADO,1,0,'L');		  
	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(27,5,str_pad($row->DOCUMENTO,8,'0',STR_PAD_LEFT),1,0,'L');
	$pdf->SetFont('Arial','',6);
	$pdf->MultiCell(93,5,utf8_decode($row->NOMBRE),1,'L');
	

	

}

$pdf->Output();
?>