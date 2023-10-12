<?php
//die('entre');
require_once("header_listado.php"); 
//print_r($_GET);
//$db->debug=true;
//print_r($_SESSION);
//$fecha=$_GET['fecha'];
//$fhasta=$_GET['fhasta'];
if(isset($_REQUEST['id_base']) and $_REQUEST['id_base']<>'')
	{
		$id_base=strtolower($_REQUEST['id_base']);
		//$condicion_descripcion="and lower(a.descripcion) like'%$descrip%'";
	}
	else
	{
		//$descrip="";
		$id_base="";
	
	}
	try
	{
			$rs_descripcion = $db->Execute("SELECT exa.descripcion as descripcion,
													tt.descripcion as tipo
										FROM  lavado_dinero.base_alerta exa,
												lavado_dinero.tipo_alerta tt
										WHERE  exa.ID_BASE=?
										and exa.id_tipo_alerta=tt.id_tipo_alerta	
								",array($id_base));
	}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
$row_descripcion =$rs_descripcion->FetchNextObject($toupper=true);
$ddescripcion=$row_descripcion->DESCRIPCION;
$tipo=$row_descripcion->TIPO;
/*$quefecha='';*/
//try {
	$rs_consulta = $db->Execute("SELECT es.descripcion as descripcion,
 											to_char(exa.fecha_mod,'dd/mm/yyyy') as fecha,
											exa.id_Estado_alerta as ID_eSTADO
 							FROM lavado_dinero.estado_alerta es,
								 lavado_dinero.estado_x_alerta exa
							WHERE exa.id_Estado_alerta=es.id_estado_alerta
								and exa.ID_BASE=?	
					",array($id_base));
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
    $pdf->Cell(190,8,'HISTORICO DE ESTADOS ('.$_GET['fecha'].')',0,1,'C',1);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,8,'Alerta: '.$tipo,0,1,'C',1);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(190,8,$ddescripcion,0,1,'C',1);
	$pdf->Ln(12);
if($rs_consulta->rowcount()<>0)
{
	$pdf->SetX(50);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(70,8,'Estado',1,0,'C');
	$pdf->Cell(40,8,'Fecha de Modificacion',1,1,'C');
	
	
	$pdf->SetFont('Arial','',7);

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
					$pdf->Cell(190,8,'HISTORICO DE ESTADOS ('.$_GET['fecha'].')',0,1,'C',1);
					$pdf->SetFont('Arial','B',12);
					$pdf->Cell(190,8,'Alerta: '.$tipo,0,1,'C',1);
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(190,8,$ddescripcion,0,1,'C',1);
					//$pdf->Cell(190,8,'Actividades Desarrolladas entre el '.$fecha.' y el '.$fhasta,0,1,'C',1);
				
					$pdf->Ln(12);
					$pdf->SetX(50);
					$pdf->SetFont('Arial','B',7);
					$pdf->Cell(70,8,'Estado',1,0,'C');
					$pdf->Cell(40,8,'Fecha de Modificacion',1,1,'C');
					$pdf->SetFont('Arial','',7);		 
				 } 
					$pdf->SetX(50);
				   $pdf->Cell(70,5,$row->DESCRIPCION,1,0,'L');
				   $pdf->Cell(40,5,$row->FECHA,1,1,'C');		  
		}
}
else
{
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(190,8,'Sin registros de Cambio de Estado',0,1,'C',1);
}
$pdf->Output();
?>