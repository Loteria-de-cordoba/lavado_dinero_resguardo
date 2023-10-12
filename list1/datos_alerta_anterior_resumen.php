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

$totocu=0;
$fecha_des=$_REQUEST['fecha_des'];
$fhasta=$_REQUEST['fhasta'];
$condicion_fecha=" and tt.fecha_aparicion between to_date('$fecha_des','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')";
$condicion_fecha_inclusion=" and b.fecha_aparicion between to_date('$fecha_des','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')";

/*$quefecha='';*/
//try {
	$rs_consulta = $db->Execute("SELECT tt.id_tipo_alerta, l.descripcion as descripcion, count(*) as ocurrencia,count(*) / 
                                  				(
												   select count(*)												  	 
													FROM lavado_dinero.base_alerta b
													WHERE TO_CHAR(B.FECHA_APARICION,'MMYYYY')<>TO_CHAR(SYSDATE,'MMYYYY')
														and b.id_estado_alerta=3
														$condicion_estado
														$condicion_fecha_inclusion												
												) *100  AS PORCENTAJE       
								FROM LAVADO_DINERO.base_alerta tt,
                					lavado_dinero.tipo_alerta l
								WHERE  l.id_tipo_alerta=tt.id_tipo_alerta
									and TO_CHAR(tt.FECHA_APARICION,'MMYYYY')<>TO_CHAR(SYSDATE,'MMYYYY')
									and tt.id_estado_alerta=3
									$condicion_estado_parcial
									$condicion_fecha
                				group by tt.id_tipo_alerta,l.descripcion");
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
			$pdf->Cell(190,8,'RESUMEN DE ALERTAS -   Desde el '.$fecha_des.' Hasta el '.$fhasta,0,1,'C',1);
		}
	else
		{
			$pdf->Cell(190,8,'RESUMEN DE ALERTAS -   Desde el '.$fecha_des.' Hasta el '.$fhasta,0,1,'C',1);
		}
	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'',0,0,'C');
	$pdf->Cell(60,8,'Alerta',1,0,'C',1);
	$pdf->Cell(20,8,'Ocurrencia',1,0,'C',1);
	$pdf->Cell(20,8,'Porcentaje',1,1,'C',1);
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
			$pdf->Cell(190,8,'RESUMEN DE ALERTAS -   Desde el '.$fecha_des.' Hasta el '.$fhasta,0,1,'C',1);
		}
	else
		{
			$pdf->Cell(190,8,'RESUMEN DE ALERTAS -   Desde el '.$fecha_des.' Hasta el '.$fhasta,0,1,'C',1);
		}
	//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);

	$pdf->Ln(12);
	$pdf->SetX(10);
	$pdf->Cell(40,8,'',0,0,'C');
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(60,8,'Alerta',1,0,'C',1);
	$pdf->Cell(20,8,'Ocurrencia',1,0,'C',1);
	$pdf->Cell(20,8,'Porcentaje',1,1,'C',1);
	//$pdf->Cell(30,8,'Motivo',1,0,'C');	
	//$pdf->Cell(103,8,'Alerta',1,1,'C');
	
	$pdf->SetFont('Arial','',10);		 
 } 
 $pdf->SetX(10);
 $pdf->Cell(40,8,'',0,0,'C');
  if($row->DESCRIPCION<>$repetido)
  {
		  			 $pdf->Cell(60,5,$row->DESCRIPCION,1,0,'L');
					 $repetido=$row->DESCRIPCION;
		   }
		   else
		   {
		   	$pdf->Cell(60,5,'',1,0,'L');
		   }
		   $pdf->Cell(20,5,$row->OCURRENCIA,1,0,'R');
		   $pdf->Cell(20,5,number_format($row->PORCENTAJE,2,'.',',').'%',1,1,'R');
		   $totocu=$totocu + $row->OCURRENCIA;
	/*$pdf->SetFont('Arial','B',6);
	$pdf->Cell(30,5,str_pad($row->DOCUMENTO,8,'0',STR_PAD_LEFT),1,0,'L');
	
	$pdf->SetFont('Arial','',6);
	$pdf->MultiCell(103,5,utf8_decode($row->NOMBRE),1,'L');*/
}

$pdf->Ln(2);

	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'',0,0,'C');
	$pdf->Cell(60,8,'Totales========>',1,0,'C',1);
	$pdf->Cell(20,8,$totocu,1,0,'R',1);
	$pdf->Cell(20,8,'100%',1,1,'R',1);
$pdf->Output();
?>