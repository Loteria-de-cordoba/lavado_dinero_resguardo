<?php 
session_start();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 
include("../funcion.inc.php");
include("../db_conecta_oracle_adodb.inc.php");
include("../numero_letra.php");
require('../pdf/fpdf.php');

//print_r($_GET);
//$db->debug=true;

try {$rs = $db->Execute("SELECT nvl(r.nro_resolucion,'Sin asignar') as nro_resolucion, nvl(to_char(r.fecha_autorizacion,'DD/MM/YYYY'),'Sin autorizar aun') as fechares, r.tipo_detalle, 
							r.fecha_alta, rd.tipo_referencia, rd.referencia, rd.paguea, rd.visto, rd.correspondea, rd.conformea, 
							rd.vuelvea, rd.pedir_cert_fiscal, rd.dolares, rd.transferir, rd.transferira_tipo, rd.transferira_nombre,
							rd.observacion, upper(ar.descripcion) as area, p.razon_social, p1.razon_social AS PROVEEDOR,
							rd.cert_fiscal as certf, rd.insumos, rd.multas, rd.txtmultas, rd.embargos,
							rd.txtembargos, rd.aforos, rd.txtaforos
						   	FROM administrativo.t_resolucion r, administrativo.t_resolucion_detalle rd, 
							administrativo.t_proveedores p, administrativo.t_proveedores p1, administrativo.t_facturas f, superusuario.areas ar
						   	WHERE r.id_resolucion = rd.id_resolucion
							   and rd.id_area_resuelve= ar.id_area
							   and rd.paguea = p.id_proveedor
							   and r.id_resolucion = f.id_resolucion
							   and f.id_proveedor = p1.id_proveedor
							   and r.id_resolucion =?", array($_GET['idres']));
	}
	catch(exception $e)
	{
	die(MensajeBase($db->ErrorMsg()));
	} 
$row = $rs->FetchNextObject($toupper=true);	

switch ($row->TIPO_REFERENCIA) {
	case 0:
			$ref = "Carpeta Pago";
			break;
	case 1:
			$ref = "Cotización";
			break;
	case 2:
			$ref = "Expediente";
			break;
	case 3:
			$ref = "Gestión Interna";
			break;
}
$txtdestino1="ELEVESE a Sindicatura para su correspondiente visación";
switch ($row->VUELVEA) {
		case '0':
			$destino="División Compras";
			break;
		case '1':
			$destino="División Relaciones Públicas y Publicidad";
			break;
	}
$txtdestino2="VUELVA a ".$destino;

if ($row->TIPO_DETALLE == 1 || $row->TIPO_DETALLE == 4) {
	$select_fact="SELECT f.tipo, f.nro_factura, f.neto, f.bruto, f.iva, f.insumos, f.fecha, f.descripcion
				  FROM administrativo.t_resolucion_x_factura rxf, administrativo.t_facturas f
				  WHERE rxf.id_factura = f.id_factura
				  and rxf.id_resolucion =? order by f.tipo, f.fecha, f.nro_factura";
} else {
	$select_fact="SELECT f.tipo, f.nro_factura, f.neto, f.bruto, f.iva, f.insumos, f.fecha, f.descripcion
				  FROM administrativo.t_resolucion_x_factura rxf, administrativo.t_facturas f
				  WHERE rxf.id_factura = f.id_factura
				  and rxf.id_resolucion =? order by f.tipo, f.fecha, f.nro_factura";	  
}
try {$rsf = $db->Execute($select_fact, array($_GET['idres']));
}
catch(exception $e)
{
die(MensajeBase($db->ErrorMsg()));
} 
try {$rsnumf = $db->Execute("SELECT count(f.id_factura) as cantfact
							FROM administrativo.t_resolucion_x_factura rxf, administrativo.t_facturas f
							WHERE rxf.id_factura = f.id_factura
							   and rxf.id_resolucion =? order by f.fecha, f.nro_factura", array($_GET['idres']));
}
catch(exception $e)
{
die(MensajeBase($db->ErrorMsg()));
} 
$rownumfact=$rsnumf->FetchNextObject($toupper=true);
$cant_fact=$rownumfact->CANTFACT;
while ($rowf = $rsf->FetchNextObject($toupper=true)) {
	switch ($rowf->TIPO) {
		case 'FA':
			$tipofact="FactA";
			break;
		case 'FASI':
			$tipofact="FactA";
			break;
		case 'FC':
			$tipofact="FactC";
			break;
		case 'RA':
			$tipofact="RecA";
			break;
		case 'RC':
			$tipofact="RecC";
			break;
		case 'NC':
			$tipofact="NCred";
			break;
		case 'S':
			$tipofact="Serv";
			break;
		case 'ND':
			$tipofact="NDeb";
			break;	
		case 'CO':
			$tipofact="Comp";
			break;	
	}
	$facturas .= "'" . $tipofact . "' Nº " . $rowf->NRO_FACTURA." ($".number_format($rowf->NETO,2,',','.').") ".substr($rowf->DESCRIPCION,0,35)."\n";
	$contadorf++; 
	if ($rowf->TIPO<>'NC'){
		$montoacum += $rowf->NETO; 
	} else {
		$montoacum -= $rowf->NETO; 
	}
}

$largo=strlen($facturas);
$facturas=substr($facturas,0,$largo-1);
if ($row->FECHA_ALTA<'01/08/09'){
	if ($montoacum<=2000) {
		$certf='No corresponde solicitar';
	} else {
		$certf='VIGENCIA '.$row->CERTF;
	}
} else {
	if ($row->PEDIR_CERT_FISCAL==0) {
		$certf='';
	} else {
		$certf='VIGENCIA '.$row->CERTF;
	}
}

class PDF extends FPDF {
	//Cabecera de página
	function Header() { 
		global $row;
		global $txtoficial;
		$this->SetFont('Arial','B',12);
		$this->SetFillColor(240,240,240);	
		$this->setXY(105,10);		
		//$this->Image('../image/loguito.jpg',15,10,15,15);
		$this->Cell(10,10,'LOTERIA DE LA PROVINCIA DE CORDOBA SE',0,0,'C');	
		$this->Ln(20);
		}
	//Pie de página
	function Footer() {
/*		$this->SetFont('Arial','',10);
		$this->Cell(0,20,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'L');		    //Número de página*/
		}
}

$pdf=new PDF();
$dy=25;
$pdf->AliasNbPages();
$pdf->AddPage();

if ($row->TIPO_DETALLE == 1) {
	try {$rsc = $db->Execute("SELECT rc.adj_o_pro, rc.nro, rc.fojas, rc.orden_compra_aforada, rc.fact_conformada,
								rc.certificado_fiscal, rc.otras, ar.descripcion as area_adjudica, rc.instrumento, rc.nro_oc_poliza
								FROM administrativo.t_resolucion_detalle_compras rc, superusuario.areas ar
								WHERE rc.id_area = ar.id_area
								   and rc.id_resolucion =?", array($_GET['idres']));
	}
	catch(exception $e)
	{
	die(MensajeBase($db->ErrorMsg()));
	} 

	$rowc = $rsc->FetchNextObject($toupper=true);	
	switch ($rowc->ADJ_O_PRO) {
		case 0:
			$txtadjopro="Resolución de Adjudicación de ".$rowc->AREA_ADJUDICA . "\n Nro. Res. " . utf8_decode($rowc->NRO);
			break;
		case 1:
			$txtadjopro="Resolución de Prorroga de ".$rowc->AREA_ADJUDICA . "\n Nro. Res. " . utf8_decode($rowc->NRO);
			break;
		case 2:
			$txtadjopro="Contrato de Locación de Servicio de ".$rowc->AREA_ADJUDICA ;
			break;
		case 3:
			$txtadjopro="Nota de ".$rowc->AREA_ADJUDICA."\n Nro. " . utf8_decode($rowc->NRO);
			break;
		}
	
	
	switch ($rowc->INSTRUMENTO){
		case 0:
			$txtinstrumento="Orden de Compra Aforada Nro. ".$rowc->NRO_OC_POLIZA;
			break;
		case 1:
			$txtinstrumento="Poliza de Seguro Aforada Nro. ".$rowc->NRO_OC_POLIZA;
			break;
		case 2:
			$txtinstrumento="Contrato Aforado";
			break;
	}

	$pdf->SetFont('Arial','B',12);
	$pdf->setXY(15,$dy);
	$pdf->Cell(0,5,utf8_decode($row->AREA),0,1,'C');
	$pdf->Cell(0,5,utf8_decode('Córdoba, ').$row->FECHARES,0,1,'R');		
	$pdf->SetFont('Arial','',12);
	$dy=$pdf->GetY()+7;
	$pdf->setXY(10,$dy);
	$pdf->Cell(0,10,'PROVEEDOR: '.$row->RAZON_SOCIAL ,0,2,'L');
	$dy=$dy+5;
	$pdf->setXY(140,$dy);
	$pdf->Cell(0,10,utf8_decode($ref . ' Nº: ' . $row->REFERENCIA),0,2,'R');
	$dy+=10;
	$pdf->setXY(15,$dy);
	$pdf->SetFillColor(240,240,240);
	$pdf->Cell(150,10,'CONCEPTO',1,0,'C',true);
	$pdf->Cell(35,10,'FOJAS',1,1,'C',true);
	$dy=$pdf->GetY();
	$pdf->setXY(15,$dy);
	$pdf->MultiCell(150,5, stripslashes(utf8_decode($txtadjopro)),1,'J');
	$ajustar = $pdf->GetY() - $dy;
	$pdf->SetXY(165,$dy);
	$pdf->MultiCell(35,$ajustar, $rowc->FOJAS,1,'C');
//	$dy= $pdf->GetY();
	
	if ($rowc->INSTRUMENTO!=3) {
			$dy=$pdf->GetY();
			$pdf->setXY(15,$dy);
			$pdf->MultiCell(150,10,$txtinstrumento,1);
			$pdf->SetXY(165,$dy);
			$pdf->MultiCell(35,10,$rowc->ORDEN_COMPRA_AFORADA,1,'C');	
	}
	$largo=strlen($facturas);
	
	 if ($contadorf>1) {
		//$renglones=round($largo/45);
		$alto_celda=5;
		$renglones=$contadorf;
		$alto_total=$renglones*$alto_celda;
		$largo_fojas=strlen($rowc->FACT_CONFORMADA);
		$renglones_fojas=ceil($largo_fojas/17);
		if ($renglones_fojas==0){$renglones_fojas=1;}			
		$ajustar=$alto_total/$renglones_fojas;
		//echo $alto_total." ".$renglones_fojas." ".$ajustar;
	} else {
		$alto_celda=10;
		$ajustar=$pdf->GetY()-$dy;
	} 
	$dy=$pdf->GetY();
	$pdf->SetXY(15,$dy);
	$pdf->MultiCell(150,$alto_celda,utf8_decode($facturas),1,'L');
	$pdf->SetXY(165,$dy);
	$pdf->MultiCell(35,$ajustar,$rowc->FACT_CONFORMADA,1,'C');
	$dy=$pdf->GetY();
	$pdf->setXY(15,$dy);
	if ($certf!=''){
		$pdf->Cell(150,10,'Certificado Fiscal  - '.$certf,1,0,'L');
		$pdf->SetXY(165,$dy);
		$pdf->Cell(35,10,$rowc->CERTIFICADO_FISCAL,1,1,'C');
		$dy+=10;
	}
	if ($rowc->OTRAS!=0) {
		if ($rowc->OTRAS!=""){
			$pdf->setx(15);
			$pdf->Cell(150,10,'Otras',1,0,'L');
			$pdf->setx(165);
			$pdf->Cell(35,10,$rowc->OTRAS,1,1,'C');
		}
	}
}

if ($row->TIPO_DETALLE == 2) {
	try {$rsc = $db->Execute("SELECT nota, viceg, secgral, difusion, despacho, contrato, facturas, comprobante,
								cert_fiscal, cesion, convenio, despacho_nro, despacho_fecha, contrato_orden 
								FROM administrativo.t_resolucion_detalle_pub
								WHERE id_resolucion =?", array($_GET['idres']));
	}
	catch(exception $e)
	{
	die(MensajeBase($db->ErrorMsg()));
	} 

	$rowc = $rsc->FetchNextObject($toupper=true);	

	$pdf->SetFont('Arial','B',12);
	$pdf->setXY(15,$dy);
	$pdf->Cell(0,5,utf8_decode('División Relaciones Públicas y Publicidad'),0,1,'C');
	$pdf->Cell(0,5,utf8_decode('Córdoba, ').$row->FECHARES,0,1,'R');
	$pdf->SetFont('Arial','',10);
	$dy=$pdf->GetY()+7;
	$pdf->SetXY(10,$dy);
	$pdf->Cell(0,5,'REQUISITOS CARPETA PUBLICITARIA (ESPONSOR)',0,2,'C');
	$dy+=10;
	$pdf->SetXY(10,$dy);	
	$pdf->Cell(0,5,'NOMBRE: ' . $row->RAZON_SOCIAL,0,2,'L');
	$dy+=10;
	$pdf->SetXY(10,$dy);
	$pdf->Cell(0,5,utf8_decode($ref . ' Nº: ' . $row->REFERENCIA),0,2,'L');
	$pdf->SetFont('Arial','',11);
	$dy+=15;
	$pdf->SetXY(15,$dy);
	$pdf->SetFillColor(240,240,240);
	$pdf->Cell(150,10,'CONCEPTO',1,0,'C',true);
	$pdf->Cell(35,10,'FOJAS',1,1,'C',true);
	//$dy+=10;
	$pdf->SetX(15);
	$pdf->Cell(150, 10,utf8_decode('Nota Solicitando la Esponsorización'),1,0,'L',false);
	//$pdf->SetX(125);
	$pdf->Cell(35, 10, $rowc->NOTA,1,1,'C',false);
	//$dy+=10;
	$pdf->SetX(15);
	$pdf->Cell(150,10,utf8_decode('Nota de Autorización de Vice Gobernación'),1,0,'L',false);
	//$pdf->SetX(175);
	$pdf->Cell(35,10,$rowc->VICEG,1,1,'C',false);
	//$dy+=10;
	$pdf->SetX(15);
	$pdf->Cell(150,10,utf8_decode('Nota de la Sec. Gral. de Gobernación'),1,0,'L');
	//$pdf->SetX(175);
	$pdf->Cell(35,10,$rowc->SECGRAL,1,1,'C');
//	$dy+=5;
	$pdf->SetX(15);
	$pdf->Cell(150,10,utf8_decode('Nota de Autorización de Dirección de Difusión'),1,0,'L');
	//$pdf->SetX(175);
	$pdf->Cell(35,10,$rowc->DIFUSION,1,1,'C');
	$dy=$pdf->GetY();
	$pdf->SetXY(15,$dy);
	$pdf->MultiCell(150,10,utf8_decode('Nota/s de Despacho Nº ') . $rowc->DESPACHO_NRO . ' de fecha/s ' 
												. $rowc->DESPACHO_FECHA,1,'L');
	$ajustar=$pdf->GetY()-$dy;
	$pdf->SetXY(165,$dy);
	$pdf->Cell(35,$ajustar,$rowc->DESPACHO,1,1,'C');

	$dy+=5;
	$pdf->SetX(15);
	if ($rowc->CONTRATO_ORDEN=="contrato"){
		$pdf->Cell(150,10,utf8_decode('Contrato de Sporsorización Firmado y Aforado'),1,0,'L');
	} else {
		$pdf->Cell(150,10,utf8_decode('Orden de Publicidad Firmada y Aforada'),1,0,'L');
	}
	//$pdf->SetX(175);
	$pdf->Cell(35,10,$rowc->CONTRATO,1,1,'C');
	
	$largo=strlen($facturas);
	if ($contadorf>45) {
		$alto_celda=5;
		$renglones=$contadorf;
		$alto_total=$renglones*$alto_celda;
		$largo_fojas=strlen($rowc->FACTURAS);
		$renglones_fojas=round($largo_fojas/18);
		if ($renglones_fojas==0){$renglones_fojas=1;}			
		$ajustar=$alto_total/$renglones_fojas;
	} else {
		$alto_celda=10;
		$ajustar=$pdf->GetY()-$dy;
	}
	
	$dy=$pdf->GetY();
	$pdf->SetXY(15,$dy);
	$pdf->MultiCell(150,$alto_celda,utf8_decode($facturas),1,'L');
	$ajustar=$pdf->GetY()-$dy;
	$pdf->SetXY(165,$dy);
	$pdf->MultiCell(35,$ajustar,$rowc->FACTURAS,1,'C'); 
	
	$largopub=strlen($rowc->COMPROBANTE);
	if ($largopub>19) {
		$alto_celda=5;
		/* $renglones=ceil($largopub/$alto_celda);
		$alto_total=$renglones*$alto_celda;
		$largo_fojas=strlen($rowc->COMPROBANTE);
		$renglones_fojas=round($largo_fojas/18);
		if ($renglones_fojas==0){$renglones_fojas=1;}			
		$ajustar=$alto_total/$renglones_fojas; */
	} else {
		$alto_celda=10;
		//$ajustar=$pdf->GetY()-$dy;
	}
	
	$pdf->SetX(15);
	$pdf->Cell(150,10,utf8_decode('Comprobante de Publicidad'),1,0,'L');
	//$pdf->SetX(175);
	$pdf->MultiCell(35,$alto_celda,$rowc->COMPROBANTE,1,'C');
	$dy+=5;
	if ($certf!=''){
		$pdf->SetX(15);
		$pdf->Cell(150,10,utf8_decode('Certificado Fiscal - '.$certf ),1,0,'L');
		//$pdf->SetX(175);
		$pdf->Cell(35,10,$rowc->CERT_FISCAL,1,1,'C');
	}
	if ($rowc->CESION!=null) {	
		$dy+=5;
		$pdf->SetX(15);
		$pdf->Cell(150,10,utf8_decode('Contrato de Cesión de Derechos'),1,0,'L');
		//$pdf->SetX(175);
		$pdf->Cell(35,10,$rowc->CESION,1,1,'C');
	}
	if ($rowc->CONVENIO!=null) {	
		$dy+=5;
		$pdf->SetX(15);
		$pdf->Cell(150,10,utf8_decode('Contrato de Cesión de Facturas'),1,0,'L');
		//$pdf->SetX(175);
		$pdf->Cell(35,10,$rowc->CONVENIO,1,1,'C');
	}
	if ($row->TRANSFERIR==1) {
		$pdf->setx(15);
		$pdf->MultiCell(185,10,'Transferir el pago a '.$row->TRANSFERIRA_TIPO." ".$row->TRANSFERIRA_NOMBRE,1,'L');
	}	
	/* if ($row->TRANSFERIR==1) {
		$pdf->setx(25);
		$pdf->Cell(155,10,'Transferir el pago a '.utf8_decode($row->TRANSFERIRA_TIPO)." ".utf8_decode($row->TRANSFERIRA_NOMBRE),1,0,'L');
		$pdf->setx(125);
		$pdf->Cell(55,10,,1,1,'C');
	}	 */
}

if ($row->TIPO_DETALLE == 3) {
	try {$rsc = $db->Execute("SELECT nota, viceg, secgral, difusion, despacho, contrato, facturas, comprobante,
								cert_fiscal, cesion, convenio, despacho_nro, despacho_fecha,
								detalle, orden, pauta, modelo, expediente, fojas, inversion
								FROM administrativo.t_resolucion_detalle_pub
								WHERE id_resolucion =?", array($_GET['idres']));
	}
	catch(exception $e)
	{
	die(MensajeBase($db->ErrorMsg()));
	} 

	$rowc = $rsc->FetchNextObject($toupper=true);	
	
	try {$rsgordo = $db->Execute("select productoextra, aniogordo, nombre_campania
							  from administrativo.t_resolucion_detalle_pub
							  where id_resolucion =?", array($_GET['idres']));
	}
	catch(exception $e)
	{
	die(MensajeBase($db->ErrorMsg()));
	} 
	$titulo_campania=0;
	$rowgordo=$rsgordo->FetchNextObject($toupper=true);
	if (!is_null($rowgordo->PRODUCTOEXTRA)||$rowgordo->PRODUCTOEXTRA!="") {
			$titulo_campania=1;
			$productoextra=$rowgordo->PRODUCTOEXTRA;
			$anioproducto=$rowgordo->ANIOGORDO;
			switch($productoextra) {
				case 'invierno':
					$nombrecampania="GORDO DE INVIERNO";
					break;
				case 'verano':	
					$nombrecampania="GORDO DE NAVIDAD";
					break;
				case 'otro':
					$nombrecampania=strtoupper($rowgordo->NOMBRE_CAMPANIA);
					break;
			}
	}
			
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(15,$dy);
	$pdf->Cell(0,5,utf8_decode('División Relaciones Públicas y Publicidad'),0,1,'C');
	$pdf->Cell(0,5,utf8_decode('Córdoba, ').$row->FECHARES,0,1,'R');
	$pdf->SetFont('Arial','',10);
	$dy=$pdf->GetY()+7;
	$pdf->SetXY(10,$dy);
	$pdf->Cell(0,5,'REQUISITOS CARPETA PUBLICITARIA (MEDIOS)',0,2,'C');
	$pdf->SetFont('Arial','B',10);
	$dy+=10;
	$pdf->SetXY(10,$dy);	
	$pdf->Cell(0,5,'MEDIO: ' . $row->PROVEEDOR,0,2,'L');
	if ($titulo_campania==1){
		$dy+= 5;
		$pdf->setXY(10,$dy);
		$pdf->Cell(0,5,utf8_decode("Campaña: ").$nombrecampania." ".$anioproducto,0,1,'L');	
	}
	$pdf->SetFont('Arial','',10);
	$dy+=5;
	$pdf->SetXY(10,$dy);
	$pdf->Cell(0,5,utf8_decode($ref . ' Nº: ' . $row->REFERENCIA),0,2,'L');
	$pdf->SetFont('Arial','',11);
	$dy+=15;
	$pdf->SetXY(15,$dy);
	$pdf->SetFillColor(240,240,240);
	$pdf->Cell(150,10,'CONCEPTO',1,0,'C',true);
	$pdf->Cell(35,10,'FOJAS',1,0,'C',true);
	switch ($rowc->MODELO) {
		case 'viejo':
			$dy=$pdf->GetY()+10;
			$pdf->SetXY(15,$dy);
			$pdf->Cell(150,10,utf8_decode('Nota de Pedido de Publicidad'),1,0,'L');
			$pdf->Cell(35,10,$rowc->NOTA,1,1,'C');
			$pdf->SetX(15);
			$pdf->Cell(150,10,utf8_decode('Nota de Autorización de Dirección de Difusión'),1,0,'L');
			$pdf->Cell(35,10,$rowc->DIFUSION,1,1,'C');
			if ($rowc->INVERSION==1) {
				$pdf->SetX(15);
				$pdf->Cell(150,10,utf8_decode('Detalle de Inversión de Dirección de Difusión'),1,0,'L');
				$pdf->Cell(35,10,$rowc->DETALLE,1,1,'C');
			}
			break;
		case 'nuevo':
			$dy=$pdf->GetY()+10;
			$pdf->SetXY(15,$dy);
			$pdf->MultiCell(150,5,utf8_decode('Nota de Autorización de Dirección de Difusión y Detalle de Inversión'.
							' obran en Exp. '.$rowc->EXPEDIENTE.' a Fs. '.$rowc->FOJAS),1,'L');
			$ajustar=$pdf->GetY()-$dy;
			$pdf->SetXY(165, $dy);
			$pdf->MultiCell(35,$ajustar,'',1,'L');
			break;
	}
	
		$largo1=strlen($rowc->DESPACHO);
		$largo2=strlen('Nota/s de Despacho Nº '.$rowc->DESPACHO_NRO.' de fecha/s '.$rowc->DESPACHO_FECHA);
		//echo $largo1."-".$largo2;
		if ($largo1>20) {
			$fojasdespacho=1;
		} else {
			$fojasdespacho=0;
		}
		if ($largo2>80) {
			$textodespacho=1;
		} else {
			$textodespacho=0;
		}
		if ($fojasdespacho==1&&$textodespacho==0){
			//echo ("Entra fojas");
			$alto_celda1=5;
			$renglones1=round($largo1/20);
			$alto_total1=$renglones1*$alto_celda1;
			$largo_fojas1=strlen($rowc->DESPACHO);
			$renglones_fojas1=round($largo_fojas1/15);
			if ($renglones_fojas1==0){$renglones_fojas1=1;}			
			$ajustar1=$alto_total1/$renglones_fojas1; 
		} else {
				$alto_celda1=10;
				$ajustar1=10;
		}
		//echo $largo1;
		 if ($textodespacho==1&&$fojasdespacho==0) {
				//echo ("Entra texto");
				$ajustar1=10;
				$renglones1=ceil($largo2/75);
				$alto_total1=round($ajustar1/$renglones1);
				/*$largo_fojas1=$largo2;
				$renglones_fojas1=ceil($largo_fojas1/75);
				if ($renglones_fojas1==0){$renglones_fojas1=1;}	*/		
				$alto_celda1=$alto_total1;
				//echo ($alto_celda1." ".$ajustar1);
		} else {
				$alto_celda1=10;
				$ajustar1=10;
		} 
		 
	$dy=$pdf->GetY();
	$pdf->SetXY(15,$dy);
	$pdf->MultiCell(150,$alto_celda1,utf8_decode('Nota/s de Despacho Nº ') . $rowc->DESPACHO_NRO . ' de fecha/s ' 
												. $rowc->DESPACHO_FECHA,1,'L');
	$pdf->SetXY(165,$dy);
	$pdf->MultiCell(35,$ajustar1,$rowc->DESPACHO,1,'C');
	
	
	$dy=$pdf->GetY();
	$pdf->SetXY(15,$dy);
	$pdf->MultiCell(150,5, utf8_decode('Orden de Publicidad de División Relaciones Públicas y Publicidad, ' .
				'Aforo y Firma de la misma'),1,'L');
	$pdf->SetXY(165,$dy);
	$pdf->Cell(35,10,$rowc->ORDEN,1,1,'C');
	
	$largopauta=strlen($rowc->PAUTA);
	
	if ($largopauta>19) {
		$alto_celda=5;
		$renglones=ceil($largopauta/16);
		$alto_total=$renglones*$alto_celda;
		$largo_fojas=strlen($rowc->PAUTA);
		$renglones_fojas=round($largo_fojas/16);
		if ($renglones_fojas==0){$renglones_fojas=1;}			
		$ajustar=$alto_total;
	} else {
		$alto_celda=10;
		$ajustar=$pdf->GetY()-$dy;
	}
	$dy=$pdf->GetY();
	if ($rowc->MODELO=='viejo') {
			$pdf->SetXY(15,$dy);
			$pdf->Cell(150,$ajustar,utf8_decode('Orden y Pauta de Distribución de Dirección de Difusión'),1,0,'L');
			$dy=$pdf->GetY();
			//$pdf->SetXY(175, $dy);
			$pdf->MultiCell(35,$alto_celda,$rowc->PAUTA,1,'C');
	} else {
			$pdf->SetXY(15,$dy);
			$pdf->Cell(150,$ajustar,utf8_decode('Orden y Pauta de Distribución de Dirección de Difusión'),1,0,'L');
			$dy=$pdf->GetY();
			//$pdf->SetXY(175, $dy);
			$pdf->MultiCell(35,$alto_celda,$rowc->PAUTA,1,'C');
	}
	if ($contadorf>20) {
		$pdf->AddPage();
		$largo=strlen($facturas);
	//echo $contadorf;
		if ($contadorf>1) {
			$alto_celda=5;
			$renglones=$contadorf;
			$alto_total=$renglones*$alto_celda;
			$largo_fojas=strlen($rowc->FACTURAS);
			$renglones_fojas=round($largo_fojas/16);
			if ($renglones_fojas==0){$renglones_fojas=1;}			
			$ajustar=$alto_total/$renglones_fojas;
		} else {
			$alto_celda=10;
			$ajustar=$pdf->GetY()-$dy;
		}
			
		$dy=$pdf->GetY();
		$pdf->SetXY(15,$dy);
		$this->SetFont('Arial','B',8);
		$pdf->MultiCell(150,$alto_celda,utf8_decode($facturas),1,'L');
		//$ajustar=$pdf->GetY()-$dy;
		$pdf->SetXY(165,$dy);
		$this->SetFont('Arial','B',12);
		$pdf->MultiCell(35,$ajustar,$rowc->FACTURAS,1,'C');
		}
		$largo=strlen($facturas);
	} else {
		//echo $contadorf;
		if ($contadorf>1) {
			$alto_celda=5;
			$renglones=$contadorf;
			$alto_total=$renglones*$alto_celda;
			$largo_fojas=strlen($rowc->FACTURAS);
			$renglones_fojas=round($largo_fojas/16);
			if ($renglones_fojas==0){$renglones_fojas=1;}			
			$ajustar=$alto_total/$renglones_fojas;
		} else {
			$alto_celda=10;
			$ajustar=$pdf->GetY()-$dy;
		}
			
		$dy=$pdf->GetY();
		$pdf->SetXY(15,$dy);
		$pdf->MultiCell(150,$alto_celda,utf8_decode($facturas),1,'L');
		//$ajustar=$pdf->GetY()-$dy;
		$pdf->SetXY(165,$dy);
		$pdf->MultiCell(35,$ajustar,$rowc->FACTURAS,1,'C');
	}
	 $largo=strlen($rowc->COMPROBANTE);
	//echo $largo;
	if ($largo>19) {
		$alto_celda=5;
		$renglones=ceil($largo/19);
		$alto_total=$renglones*$alto_celda;
		//echo $alto_celda."-".$renglones."-".$alto_total;
	} else {
		$alto_celda=10;
		$alto_total=10;
	} 
	
	$pdf->SetX(15);
	$pdf->Cell(150,$alto_total,utf8_decode('Comprobante de Publicidad'),1,0,'L');
	$dy=$pdf->GetY();
	//$pdf->SetXY(175, $dy);
	$pdf->MultiCell(35,$alto_celda,$rowc->COMPROBANTE,1,'C');
	if ($certf!=''){
		$pdf->SetX(15);
		$pdf->Cell(150,10,utf8_decode('Certificado Fiscal  - '.$certf ),1,0,'L');
		$dy=$pdf->GetY();
		$pdf->SetXY(165, $dy);
		$pdf->Cell(35,10,$rowc->CERT_FISCAL,1,1,'C');
	}
	if ($rowc->CESION!=null) {
		$largo=strlen($rowc->CESION);
		if ($largo>30) {
			$alto_celda=5;
			$renglones=round($largo/18);
			$alto_total=$renglones*$alto_celda;
		} else {
			$alto_celda=10;
		} 
		
		$dy=$pdf->GetY();
		$pdf->SetXY(15, $dy);
		$pdf->Cell(150,$alto_total,utf8_decode('Contrato de Cesión de Derechos'),1,0,'L');
		//$pdf->SetXY(175, $dy);
		$pdf->MultiCell(35,$alto_celda,$rowc->CESION,1,'C');
	}
	if ($rowc->CONVENIO!=null) {
		$dy=$pdf->GetY();
		$pdf->SetXY(15, $dy);
		$pdf->Cell(150,10,utf8_decode('Contrato de Cesión de Facturas'),1,0,'L');
		//$pdf->SetXY(175, $dy);
		$pdf->Cell(35,10,$rowc->CONVENIO,1,1,'C');
	}
	if ($row->TRANSFERIR==1) {
		$pdf->setx(15);
		$pdf->MultiCell(185,10,'Transferir el pago a '.$row->TRANSFERIRA_TIPO." ".$row->TRANSFERIRA_NOMBRE,1,'L');
	}	
	/* if ($row->TRANSFERIR==1) {
		$pdf->setx(25);
		$pdf->Cell(100,10,'Transferir el pago a '.utf8_decode($row->TRANSFERIRA_TIPO),1,0,'L');
		$pdf->setx(125);
		$pdf->Cell(55,10,$row->TRANSFERIRA_NOMBRE,1,1,'C');
	}	 */
}

if ($row->TIPO_DETALLE == 4) {
	try {$rsc = $db->Execute("SELECT rc.adj_o_pro, rc.nro, rc.fojas, rc.orden_compra_aforada, rc.fact_conformada,
								rc.certificado_fiscal, rc.otras,  rc.instrumento, rc.nro_oc_poliza
								FROM administrativo.t_resolucion_detalle_compras rc
								WHERE rc.id_resolucion =?", array($_GET['idres']));
	}
	catch(exception $e)
	{
	die(MensajeBase($db->ErrorMsg()));
	} 

	$rowc = $rsc->FetchNextObject($toupper=true);	
	switch ($rowc->ADJ_O_PRO) {
		case 0:
			$txtadjopro="Resolución de Adjudicación de ".$rowc->AREA_ADJUDICA . "\n Nro. Res. " . utf8_decode($rowc->NRO);
			break;
		case 1:
			$txtadjopro="Resolución de Prorroga de ".$rowc->AREA_ADJUDICA . "\n Nro. Res. " . utf8_decode($rowc->NRO);
			break;
		case 2:
			$txtadjopro="Contrato de Locación de Servicio de ".$rowc->AREA_ADJUDICA ;
			break;
		}
	
	
	switch ($rowc->INSTRUMENTO){
		case 0:
			$txtinstrumento="Orden de Compra Aforada Nro. ".$rowc->NRO_OC_POLIZA;
			break;
		case 1:
			$txtinstrumento="Poliza de Seguro Aforada Nro. ".$rowc->NRO_OC_POLIZA;
			break;
		case 2:
			$txtinstrumento="Contrato Aforado";
			break;
	}

	$pdf->SetFont('Arial','B',12);
	$pdf->setXY(15,$dy);
	$pdf->Cell(0,5,utf8_decode($row->AREA),0,1,'C');
	$pdf->Cell(0,5,utf8_decode('Córdoba, ').$row->FECHARES,0,1,'R');		
	$pdf->SetFont('Arial','',12);
	$dy=$pdf->GetY()+7;
	$pdf->setXY(10,$dy);
	$pdf->Cell(0,10,'PROVEEDOR: '. $row->RAZON_SOCIAL,0,2,'L');
	$pdf->setXY(140,$dy);
	$pdf->Cell(0,10,utf8_decode($ref . ' Nº: ' . $row->REFERENCIA),0,2,'R');
	$dy+=10;
	$pdf->setXY(25,$dy);
	$pdf->SetFillColor(240,240,240);
	$pdf->Cell(100,10,'CONCEPTO',1,0,'C',true);
	$pdf->Cell(52,10,'FOJAS',1,1,'C',true);
	$dy=$pdf->GetY();
	$largo=strlen($facturas);
	if ($contadorf>1) {
		//$renglones=round($largo/45);
		$alto_celda=5;
		$renglones=$cant_fact;
		$alto_total=$renglones*$alto_celda;
		$largo_fojas=strlen($rowc->FACT_CONFORMADA);
		$renglones_fojas=ceil($largo_fojas/25);
		//echo $renglones_fojas;
		if ($renglones_fojas==0){$renglones_fojas=1;}			
		$ajustar=$alto_total/$renglones_fojas;
		//echo "Ajustar: ".$ajustar;
	} else {
		$alto_celda=10;
		$ajustar=10;
	} 
	$dy=$pdf->GetY();
	$pdf->SetXY(25,$dy);
	$pdf->MultiCell(100,$alto_celda,utf8_decode($facturas),1,'L');
	$pdf->SetXY(125,$dy);
	$pdf->MultiCell(52,$ajustar,$rowc->FACT_CONFORMADA,1,'C');
	$dy+=10;
	/* if ($row->TRANSFERIR==1) {
		$pdf->setx(25);
		$pdf->Cell(100,10,'Transferir el pago a '.utf8_decode($row->TRANSFERIRA_TIPO),1,0,'L');
		$pdf->setx(125);
		$pdf->Cell(55,10,$row->TRANSFERIRA_NOMBRE,1,1,'C');
	}	 */
	if ($rowc->OTRAS!=0) {
		if ($rowc->OTRAS!=""){
			$pdf->setx(25);
			$pdf->Cell(100,10,'Otras',1,0,'L');
			$pdf->setx(125);
			$pdf->Cell(55,10,$rowc->OTRAS,1,1,'C');
		}
	}
}

if ($row->TIPO_DETALLE == 5) {
	try {$rsc = $db->Execute("SELECT nota, viceg, difusion, despacho, contrato, facturas, comprobante,
								cert_fiscal, cesion, convenio, despacho_nro, despacho_fecha,
								orden, conv_orden
								FROM administrativo.t_resolucion_detalle_pub
								WHERE id_resolucion =?", array($_GET['idres']));
	}
	catch(exception $e)
	{
	die(MensajeBase($db->ErrorMsg()));
	} 

	$rowc = $rsc->FetchNextObject($toupper=true);	
	
	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(15,$dy);
	$pdf->Cell(0,5,utf8_decode('División Relaciones Públicas y Publicidad'),0,1,'C');
	$pdf->Cell(0,5,utf8_decode('Córdoba, ').$row->FECHARES,0,1,'R');
	$pdf->SetFont('Arial','',10);
	$dy=$pdf->GetY()+7;
	$pdf->SetXY(10,$dy);
	$pdf->Cell(0,5,'REQUISITOS CARPETA PUBLICITARIA (EVENTOS)',0,2,'C');
	$pdf->SetFont('Arial','B',10);
	$dy+=10;
	$pdf->SetXY(10,$dy);	
	$pdf->Cell(0,5,'MEDIO: ' . $row->RAZON_SOCIAL,0,2,'L');
	$pdf->SetFont('Arial','',10);
	$dy+=5;
	$pdf->SetXY(10,$dy);
	$pdf->Cell(0,5,utf8_decode($ref . ' Nº: ' . $row->REFERENCIA),0,2,'L');
	$pdf->SetFont('Arial','',11);
	$dy+=15;
	$pdf->SetXY(15,$dy);
	$pdf->SetFillColor(240,240,240);
	$pdf->Cell(150,10,'CONCEPTO',1,0,'C',true);
	$pdf->Cell(35,10,'FOJAS',1,0,'C',true);
	$dy=$pdf->GetY()+10;
	$pdf->SetXY(15,$dy);
	$pdf->Cell(150,10,utf8_decode('Nota de de Pedido de Publicidad'),1,0,'L');
	$pdf->Cell(35,10,$rowc->NOTA,1,1,'C');
	if ($rowc->VICEG!='no') {
		$pdf->SetX(15);
		$pdf->Cell(150,10,utf8_decode('Nota de Autorización de la ViceGobernación'),1,0,'L');
		$pdf->Cell(35,10,$rowc->VICEG,1,1,'C');
	}
	$dy=$pdf->GetY();
	$pdf->SetXY(15,$dy);
	$pdf->Cell(150,10,utf8_decode('Nota de Autorización de Dirección de Difusión'),1,0,'L');
	$pdf->Cell(35,10,$rowc->DIFUSION,1,1,'C');
	$pdf->SetX(15);
	if ($rowc->CONV_ORDEN=='convenio') {
				$pdf->Cell(150,10,utf8_decode('Convenio suscripto por ambas partes y aforado'),1,0,'L');
				$pdf->Cell(35,10,$rowc->CONTRATO,1,1,'C');
	}
	$dy=$pdf->GetY();
	$pdf->SetXY(15,$dy);
	$pdf->MultiCell(150,10,utf8_decode('Nota/s de Despacho Nº ') . $rowc->DESPACHO_NRO . ' de fecha/s ' 
												. $rowc->DESPACHO_FECHA,1,'L');
	$ajustar=$pdf->GetY()-$dy;
	$pdf->SetXY(165,$dy);
	$pdf->Cell(35,$ajustar,$rowc->DESPACHO,1,1,'C');
	if ($rowc->CONV_ORDEN=='orden_pub') {
		$dy=$pdf->GetY();
		$pdf->SetX(15);
		$pdf->MultiCell(150,5, utf8_decode('Orden de Publicidad de División Relaciones Públicas y Publicidad, ' .
					'Aforo y Firma de la misma'),1,'L');
		$pdf->SetXY(165,$dy);
		$pdf->Cell(35,10,$rowc->ORDEN,1,1,'C');
	}
	$largo=strlen($facturas);
	if ($largo>45) {
		$alto_celda=5;
		$renglones=$cant_fact;
		$alto_total=$renglones*$alto_celda;
		$largo_fojas=strlen($rowc->FACTURAS);
		$renglones_fojas=round($largo_fojas/18);
		if ($renglones_fojas==0){$renglones_fojas=1;}			
		$ajustar=$alto_total/$renglones_fojas;
	} else {
		$alto_celda=10;
		$ajustar=$pdf->GetY()-$dy;
	}
		
	$dy=$pdf->GetY();
	$pdf->SetXY(15,$dy);
	$pdf->MultiCell(150,$alto_celda,utf8_decode($facturas),1,'L');
	
	$pdf->SetXY(165,$dy);
	$pdf->MultiCell(35,$ajustar,$rowc->FACTURAS,1,'C');
	
	$largo=strlen($rowc->COMPROBANTE);
	if ($largo>15) {
		$alto_celda=5;
		$renglones=round($largo/15);
		$alto_total=$renglones*$alto_celda;
	} else {
		$alto_celda=10;
		$alto_total=10;
	} 
	//echo ('Largo: '.$largo.' Celda: '.$alto_celda.' Total: '.$alto_total);
	$pdf->SetX(15);
	$pdf->Cell(150,$alto_total,utf8_decode('Comprobante de Publicidad'),1,0,'L');
	$dy=$pdf->GetY();
	//$pdf->SetXY(175, $dy);
	$pdf->MultiCell(35,$alto_celda,$rowc->COMPROBANTE,1,'C');
	if ($certf!=''){
		$pdf->SetX(15);
		$pdf->Cell(150,10,utf8_decode('Certificado Fiscal  - '.$certf ),1,0,'L');
		$dy=$pdf->GetY();
		$pdf->SetXY(165, $dy);
		$pdf->Cell(35,10,$rowc->CERT_FISCAL,1,1,'C');
	}
	if ($rowc->CESION!=null) {
		$largo=strlen($rowc->CESION);
		if ($largo>30) {
			$alto_celda=5;
			$renglones=round($largo/18);
			$alto_total=$renglones*$alto_celda;
		} else {
			$alto_celda=10;
		} 
		
		$dy=$pdf->GetY();
		$pdf->SetXY(15, $dy);
		$pdf->Cell(150,$alto_total,utf8_decode('Contrato de Cesión de Derechos'),1,0,'L');
		//$pdf->SetXY(175, $dy);
		$pdf->MultiCell(35,$alto_celda,$rowc->CESION,1,'L');
	}
	if ($rowc->CONVENIO!=null) {
		$dy=$pdf->GetY();
		$pdf->SetXY(15, $dy);
		$pdf->Cell(150,10,utf8_decode('Contrato de Cesión de Facturas'),1,0,'L');
		//$pdf->SetXY(175, $dy);
		$pdf->Cell(35,10,$rowc->CONVENIO,1,1,'C');
	}
	if ($row->TRANSFERIR==1) {
		$pdf->setx(15);
		$pdf->MultiCell(185,10,'Transferir este pago a '.$row->TRANSFERIRA_TIPO." ".$row->TRANSFERIRA_NOMBRE,1,'L');
	}	
}
if ($row->TIPO_DETALLE != 1 || $row->TIPO_DETALLE != 4) {
	$dy = $pdf->GetY() + 10;
	$pdf->SetXY(15, $dy);
	$pdf->MultiCell(0,5,"Observacion: " . $row->OBSERVACION,0);
}
/* try {$rsf = $db->Execute("SELECT f.tipo, f.nro_factura, f.neto, f.bruto, f.iva, f.insumos
							FROM administrativo.t_resolucion_x_factura rxf, administrativo.t_facturas f
							WHERE rxf.id_factura = f.id_factura
							   and rxf.id_resolucion =?", array($_GET['idres']));
}
catch(exception $e)
{
die(MensajeBase($db->ErrorMsg()));
}  */

//$pdf->Output("115.pdf", F);				//	guarda el archivo
$pdf->Output();								//	tira al navegador
?> 


