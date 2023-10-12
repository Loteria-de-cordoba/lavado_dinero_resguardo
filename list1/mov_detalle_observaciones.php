<?php
require("header_listado.php"); 
//print_r($_GET);
//die();
//$db->debug=true;
//print_r($_REQUEST);
//die();
$sumafichaje=0;
$sumaacierto=0;
$totfic_ing=0;
$totfic_ret=0;
$perdido=0;
//$documento=$_GET['docu'];
//$sexo=$_GET['sexo'];
$fecha=substr($_REQUEST['fechita'],0,10);
$casino=$_REQUEST['casino'];
if(isset($_REQUEST['cliente']))
{
	$cliente=$_REQUEST['cliente'];
	$condic_cliente='and cl.id_cliente='.$cliente;
	$titulo='Novedad Marcada Por Observacion';
}
else
{
	$condic_cliente='';
	$titulo='Listado de Novedades Marcadas Por Observaciones';
}


try {
			$rs_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino=?
										",array($casino));}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 
	$row_casino =$rs_casino->FetchNextObject($toupper=true);
	$micasino=$row_casino->DESCRIPCION;


try {
	$rs_consulta = $db->Execute("select cl.apellido || '  ' || cl.nombre as Identificacion, 
										decode(nov.valoracion,null,'Sin Valoracion Aun',nov.valoracion) as valoracion
								from lavado_dinero.t_cliente cl,
								lavado_dinero.t_novedad_casino nov
								where cl.id_cliente=nov.id_cliente
										and nov.fecha_novedad=to_date(?,'dd/mm/yyyy')
										and nov.id_casino=?
										and nov.completa=1
										$condic_cliente
								order by 1",array($fecha, $casino));} 
		catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
				
$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Ln(-22);
if($rs_consulta->RowCount()<>0)
{
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(240,240,240);
//$pdf->SetX(30);
$pdf->Cell(190,8,$titulo,0,1,'C',1);
$pdf->Cell(190,8,$micasino.' - Fecha:  '.$fecha,0,1,'C',1);

$y_line=215;
$pdf->Ln(5);
	$pdf->SetX(25);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(50,6,'Apellido y Nombre',1,0,'C');
	$pdf->Cell(115,6,'Valoracion',1,1,'C');
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
 
 //agrego 17032015
 $estoy=$pdf->GetY();
 $contar=$contar+1;
	if($contar>33 or $estoy>240)
	{
		$estoy=0;
		$pdf->AddPage();
		$pdf->SetY(28);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetFillColor(240,240,240);
		$pdf->Cell(190,8,$titulo,0,1,'C',1);
		$pdf->Cell(190,8,$micasino.' - Fecha:  '.$fecha,0,1,'C',1);
		$pdf->ln(6);
		$y_line=215;
		$pdf->SetX(25);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(50,6,'Apellido y Nombre',1,0,'C');
		$pdf->Cell(115,6,'Valoracion',1,1,'C');
		
	$contar=0;
	
	}
 	$pdf->SetX(25);
	$pdf->SetFont('Arial','',6);
	
	$pdf->Cell(50,6,$row->IDENTIFICACION,1,0,'L');
	$pdf->MultiCell(115,6,$row->VALORACION,1,'L');
	
	
	
 }
//obtengo total de cedulas
try {
		$rs_total = $db ->Execute("select count(*) as total
									from lavado_dinero.t_cliente cl,
								lavado_dinero.t_novedad_casino nov
								where cl.id_cliente=nov.id_cliente
										and nov.fecha_novedad=to_date(?,'dd/mm/yyyy')
										and nov.id_casino=?
										and nov.completa=1
								order by 1",array($fecha, $casino));
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_total =$rs_total->FetchNextObject($toupper=true);
	$toto=$row_total->TOTAL;
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,8,'Total de Novedades Marcadas  ==============>     '.$toto,0,1,'C',1);
//$pdf->Cell(190,8,$micasino.' - Fecha:  '.$fecha,0,1,'C',1);
$pdf->setx(40);
}
else
{
	$pdf->SetFont('Arial','B',12);
	$pdf->SetFillColor(240,240,240);
	$pdf->Cell(190,8,$titulo,0,1,'C',1);
	
	$pdf->Cell(190,8,'SIN MARCAS EN LA CONSULTA',0,0,'C',1);
}
$pdf->Output();
?>