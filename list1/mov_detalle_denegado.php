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
			$docu = strtoupper(md5($_REQUEST['docu']));
			$docu1=$_REQUEST['docu'];
			$condicion_docu="and b.documento like '$docu'";
		}  else {
						$docu = '';
						$docu1='';
						$condicion_docu="";
		}

//fecha de cedula
if (isset($_REQUEST['fecha_cedula'])&& $_REQUEST['fecha_cedula']<>'' ) {
			$fecha_cedula = substr($_REQUEST['fecha_cedula'],0,10);
			if($fecha_cedula=='02/02/0002')
				{
					$condicion_fecha_cedula="and b.fecha_cedula is null";
					$cond_ced_vto="and fecha_cedula is null";
				}
			else
				{
					if($fecha_cedula<>'01/01/0001')
						{
						$condicion_fecha_cedula="and b.fecha_cedula=to_date('$fecha_cedula','dd/mm/yyyy')";
						$cond_ced_vto="and fecha_cedula=to_date('$fecha_cedula','dd/mm/yyyy')";
						}
					else
						{
							$fecha_cedula = '';
							$condicion_fecha_cedula="";
							$cond_ced_vto="";
						}
				}
		}  else {
						$fecha_cedula = '';
						$condicion_fecha_cedula="";
						$cond_ced_vto="";
				}	
//fechavto
if (isset($_REQUEST['fecha_vto'])&& $_REQUEST['fecha_vto']<>'' ) {
			$fecha_vto = substr($_REQUEST['fecha_vto'],0,10);
			if($fecha_vto=='02/02/0002')
				{
					$condicion_fechavto="and (b.fecha_cedula is null
											or vto='N')";
				}
				else
				{
					if($fecha_vto<>'01/01/0001')
					{
						//##DIA13/05/2014 CAMBIO FECHA_CEDULA - 1 SACO -1 - IGUAL LINEAS 138, 139, 488 Y EN 544 Y 554 ##
						$condicion_fechavto="and ADD_MONTHS(b.fecha_cedula,6) = to_date('$fecha_vto','dd/mm/yyyy')
											and vto='S'";
					}
					else
					{
						$fecha_vto = '';
						$condicion_fechavto="";
					}
				}
		}  else {
						$fecha_vto = '';
						$condicion_fechavto="";
				}	
try {
	$rs_consulta = $db->Execute("SELECT b.id_denegado idid,
							B.USUARIO as usu,
							InitCap(us.descripcion) as usuario,
								to_char(b.fecha_alta,'dd/mm/yyyy') fecha,
							  decode(b.descripcion,null,'Innominado',b.descripcion) nombre,
							   DECODE(b.cuit,'','Sin Datos',b.cuit) cuit,
							  b.documento documento,
							  b.sexo id_sexo,
							  sexo.descripcion as sexo,
							  decode(b.novedad,null,'Sin Novedad',b.novedad) novedad,
							  nvl(to_char(b.fecha_cedula,'DD/MM/YYYY'),'') fecedula,
				  			  DECODE(B.FECHA_CEDULA,NULL,'',DECODE(VTO,'S',to_char(ADD_MONTHS(b.fecha_cedula,6),'dd/mm/yyyy'),'')) FECHAVTO				  	 
							FROM lavado_dinero.denegado b,
								SUPERUSUARIO.USUARIOs US,
								lavado_dinero.sexo sexo
							   WHERE b.usuario=us.id_usuario
										and b.sexo=sexo.id_sexo
										$condicion_docu	
										$condicion_sexo	
										$condicion_fecha_cedula
										$condicion_fechavto
							
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
$pdf->Cell(190,8,'Listado de Cedulas U.I.F.[Datos Resguardados] - Fecha '.$fecha,0,1,'C',1);
//$pdf->Cell(190,8,$soydelcasino,0,1,'C',1);
//$pdf->Cell(190,8,$datos1,0,1,'C',1);
//$titulo2=$datos;
//GETx;

//$pdf->ln(10);
//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line);
$pdf->Ln(5);
	$pdf->SetX(6);
	$pdf->SetFont('Arial','B',7);
	//$pdf->Cell(10,6,'',0,1,'C');
	$pdf->Cell(25,6,'Usuario_Alta',1,0,'C');
	$pdf->Cell(15,6,'Fecha_Alta',1,0,'C');
	$pdf->Cell(17,6,'Fecha_Cedula',1,0,'C');
	$pdf->Cell(16,6,'Fecha_Vto',1,0,'C');
	//$pdf->Cell(35,6,'Apellido y Nombre',1,0,'C');
	$pdf->Cell(42,6,'C.U.I.T. [Encriptado]',1,0,'C');
	//$pdf->Cell(13,6,'Sexo',1,0,'C');
	
	$pdf->Cell(32,6,'Situacion',1,0,'C');
	$pdf->Cell(52,6,'Novedad',1,1,'C');
	//$pdf->Cell(20,6,'Estado',1,0,'C');
	//$pdf->Cell(25,6,'Novedades',1,1,'C');
	//$pdf->Cell(20,6,'Estado',1,1,'C');
while ($row = $rs_consulta->FetchNextObject($toupper=true))
 {
//$pdf->Ln(8);
$contar=$contar+1;
	if($contar==38)
	{
		$pdf->AddPage();
		$pdf->SetY(28);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetFillColor(240,240,240);
		//$pdf->SetX(30);
		$pdf->Cell(190,8,'Listado de Cedulas U.I.F.[Datos Resguardados] - Fecha '.$fecha,0,1,'C',1);
		//$pdf->Cell(190,8,$soydelcasino,0,1,'C',1);
		//$pdf->Cell(190,8,$datos1,0,1,'C',1);
		//$titulo2=$datos;
		//GETx;
		$pdf->ln(6);
		//$pdf->ln(10);
		//$y_line=40;
				
		//$pdf->Line(10,$y_line,200,$y_line); 
		$y_line=215;
		//$pdf->Line(10,$y_line,200,$y_line);
		//$pdf->Ln(-10);
		$pdf->SetX(6);
		$pdf->SetFont('Arial','B',7);
		//$pdf->Cell(10,6,'',0,1,'C');
		$pdf->Cell(25,6,'Usuario_Alta',1,0,'C');
		$pdf->Cell(15,6,'Fecha_Alta',1,0,'C');
		$pdf->Cell(17,6,'Fecha_Cedula',1,0,'C');
		$pdf->Cell(16,6,'Fecha_Vto',1,0,'C');
		//$pdf->Cell(35,6,'Apellido y Nombre',1,0,'C');
		$pdf->Cell(42,6,'C.U.I.T. [Encriptado]',1,0,'C');
		//$pdf->Cell(13,6,'Sexo',1,0,'C');
		
		$pdf->Cell(32,6,'Situacion',1,0,'C');
		$pdf->Cell(52,6,'Novedad',1,1,'C');
		
	$contar=0;
	}
 	$pdf->SetX(6);
	$pdf->SetFont('Arial','',6);
	//$pdf->Cell(10,6,'',0,1,'C');
	if($row->USUARIO<>$nombre)
	{
		$pdf->Cell(25,6,utf8_decode(utf8_decode(substr($row->USUARIO,0,20))),1,0,'L');
	}
	else
	{
		$pdf->Cell(25,6,'',1,0,'L');
	}
	$nombre=substr($row->USUARIO,0,20);
	$pdf->Cell(15,6,$row->FECHA,1,0,'C');
	$pdf->Cell(17,6,$row->FECEDULA,1,0,'C');
	$pdf->Cell(16,6,$row->FECHAVTO,1,0,'C');
	//$pdf->Cell(45,6,utf8_decode(utf8_decode(substr($row->NOMBRE,0,40))),1,0,'L');
	$pdf->Cell(42,6,$row->CUIT,1,0,'C');
	//$pdf->Cell(13,6,$row->SEXO,1,0,'L');
	
	try {
						$rs_controlcuit=$db->Execute("select LAVADO_DINERO.check_CUIT_esquema(?) as controcuit from dual",
							  array(substr($row->CUIT,0,32)));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					
		$row_controlcuit =$rs_controlcuit->FetchNextObject($toupper=true);
		$controcuit=$row_controlcuit->CONTROCUIT;
	$pdf->Cell(32,6,$controcuit,1,0,'L');
	$pdf->MultiCell(52,6,utf8_decode($row->NOVEDAD),1,'L');
	
 }
//obtengo total de cedulas
try {
		$rs_total = $db ->Execute("select count(*) as total
									from PLA_AUDITORIA.denegado b
									WHERE 1=1
									$condicion_docu	
									$condicion_sexo
									$condicion_idid
									$condicion_fecha_cedula
									$condicion_fechavto");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_total =$rs_total->FetchNextObject($toupper=true);
	$toto=$row_total->TOTAL;
$pdf->Ln(5);
$pdf->SetX(12);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,8,'Total de Cedulas U.I.F. ==============>     '.$toto,0,1,'C',1);
	//$pdf->Cell(10,6,'',0,1,'C');
	

$pdf->setx(40);
}
else
{
	$pdf->SetFont('Arial','B',12);
	$pdf->SetFillColor(240,240,240);
	$pdf->Cell(190,8,'Listado de Cedulas U.I.F.[Datos Resguardados] - Fecha '.$fecha,0,1,'C',1);
	
	$pdf->Cell(190,8,'SIN MOVIMIENTOS',0,0,'C',1);
}
$pdf->Output();
?>