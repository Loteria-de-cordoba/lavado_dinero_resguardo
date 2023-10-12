<?php
require("header_listado.php"); 
//print_r($_GET);
//die();
//$db->debug=true;
//print_r($_SESSION);
$sumafichaje=0;
$sumaacierto=0;
$totfic_ing=0;
$totfic_ret=0;
$perdido=0;
//$documento=$_GET['docu'];
//$sexo=$_GET['sexo'];
$fecha=$_GET['fechita'];
$nombre='';
$contar=0;

if (isset($_REQUEST['sexo'])&& $_REQUEST['sexo']<>0) {
			$sexo = $_REQUEST['sexo'];
			$condicion_sexo="and b.sexo=$sexo";
		}  else {
						$sexo = '';
						$condicion_sexo="";
		}
		
	if (isset($_REQUEST['docu'])&& $_REQUEST['docu']<>'' ) {
			$docu = strtoupper($_REQUEST['docu']);
			$docu1=$_REQUEST['docu'];
			$condicion_docu="and b.documento like '$docu'";
		}  else {
						$docu = '';
						$docu1='';
						$condicion_docu="";
		}

if (isset($_REQUEST['fechadesde'])&& $_REQUEST['fechadesde']<>'') 
{$fechadesde=$_REQUEST['fechadesde'];}
else
{$fechadesde='01/01/2013';}
if (isset($_REQUEST['fhasta'])&& $_REQUEST['fhasta']<>'') 
{$fhasta=$_REQUEST['fhasta'];}
else
{$fhasta=$fecha;}

$condicion_fecha="and b.fecha_alta between to_date('$fechadesde','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')";
	if($fechadesde<>$fhasta)
	{
		$dias="Registrados desde ".$fechadesde." hasta  ".$fhasta;
	}
	else
	{
		$dias="Registrados en la Fecha ".$fechadesde;
	}

try {
	$rs_consulta = $db->Execute("SELECT b.id_informado idid,
				B.USUARIO as usu,
				us.descripcion as usuario,
					to_char(b.fecha_alta,'dd/mm/yyyy') fecha,
				  decode(b.descripcion,null,'Innominado',b.descripcion) nombre,
				  b.documento documento,
				  b.sexo id_sexo,
				  sexo.descripcion as sexo,
				  decode(b.novedad,null,'Sin Novedad',b.novedad) novedad				  	 
				FROM lavado_dinero.informado_uif b,
					SUPERUSUARIO.USUARIOs US,
					lavado_dinero.sexo sexo
				   WHERE b.usuario=us.id_usuario
				   			and b.sexo=sexo.id_sexo
							AND B.ESTADO=1
				   			$condicion_apenom 
							$condicion_docu	
							$condicion_sexo	
							$condicion_idid
							$condicion_fecha
				
				order by 2");} 
		catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
				
$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Ln(-20);
if($rs_consulta->RowCount()<>0)
{
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(240,240,240);
//$pdf->SetX(30);
$pdf->Cell(190,8,'Listado de Clientes a Informar - '.$dias,0,1,'C',1);

$y_line=215;
$pdf->Ln(5);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(25,6,'Usuario_Alta',1,0,'C');
	$pdf->Cell(62,6,'Apellido y Nombre',1,0,'C');
	$pdf->Cell(16,6,'Fecha_Alta',1,0,'C');
	$pdf->Cell(21,6,'Nro. Documento',1,0,'C');
	$pdf->Cell(15,6,'Sexo',1,0,'C');
	$pdf->Cell(51,6,'Novedad',1,1,'C');
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
		$pdf->Cell(190,8,'Listado de Clientes a Informar - '.$dias,0,1,'C',1);
		$pdf->ln(6);
		$y_line=215;
		$pdf->SetX(10);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(25,6,'Usuario_Alta',1,0,'C');
		$pdf->Cell(62,6,'Apellido y Nombre',1,0,'C');
		$pdf->Cell(16,6,'Fecha_Alta',1,0,'C');
		$pdf->Cell(21,6,'Nro. Documento',1,0,'C');
		$pdf->Cell(15,6,'Sexo',1,0,'C');
		$pdf->Cell(51,6,'Novedad',1,1,'C');
		
	$contar=0;
	
	}
 	$pdf->SetX(10);
	$pdf->SetFont('Arial','',6);
	
	if(substr($row->USUARIO,0,17)<>$nombre or $contar==0)
	{
		$pdf->Cell(25,6,utf8_decode(utf8_decode(substr($row->USUARIO,0,17))),1,0,'L');
	}
	else
	{
		$pdf->Cell(25,6,'',1,0,'L');
	}
	$nombre=substr($row->USUARIO,0,17);
	$pdf->Cell(62,6,$row->NOMBRE,1,0,'L');
	$pdf->Cell(16,6,$row->FECHA,1,0,'C');
	$pdf->Cell(21,6,$row->DOCUMENTO,1,0,'C');
	
	$pdf->Cell(15,6,$row->SEXO,1,0,'L');
	
	/*try {
						$rs_controlcuit=$db->Execute("select lavado_dinero.check_CUIT_esquema(?) as controcuit from dual",
							  array(substr($row->CUIT,0,32)));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					
		$row_controlcuit =$rs_controlcuit->FetchNextObject($toupper=true);
		$controcuit=$row_controlcuit->CONTROCUIT;
	$pdf->Cell(32,6,$controcuit,1,0,'L');*/
	$pdf->MultiCell(51,6,utf8_decode($row->NOVEDAD),1,'L');
	
 }
//obtengo total de cedulas
try {
		$rs_total = $db ->Execute("select count(*) as total
									from lavado_dinero.INFORMADO_UIF b
									WHERE 1=1
									AND ESTADO=1
									$condicion_docu	
									$condicion_sexo
									$condicion_idid
									$condicion_fecha_cedula
									$condicion_fechavto
									$condicion_fecha");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_total =$rs_total->FetchNextObject($toupper=true);
	$toto=$row_total->TOTAL;
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,8,'Total de Clientes a Informar  ==============>     '.$toto,0,1,'C',1);
$pdf->setx(40);
}
else
{
	$pdf->SetFont('Arial','B',12);
	$pdf->SetFillColor(240,240,240);
	$pdf->Cell(190,8,'Listado de Clientes a Informar - '.$dias,0,1,'C',1);
	
	$pdf->Cell(190,8,'SIN MOVIMIENTOS',0,0,'C',1);
}
$pdf->Output();
?>