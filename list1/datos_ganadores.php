<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '-'.$_GET['delegacion'];
require("header_listado.php"); 

//print_r($_GET);
//$db->debug=true;
//print_r($_SESSION);

if (isset($_GET['id_ganador'])) {
	$ganador = $_GET['id_ganador'];
	$condicion_ganador="and g.id_ganador = '$ganador'";
	}
	else 
		{
			if (isset($_POST['id_ganador'])) {
				$ganador = $_POST['id_ganador'];
				$condicion_ganador="and g.id_ganador = '$ganador'";
				} 
			else {
				$ganador = "";
				$condicion_ganador="";
			}
		}
		

try {
	$rs_consulta = $db->Execute("select g.id_ganador,to_char(g.fecha_nacimiento,'dd/mm/yyyy')fecha_nacimiento,g.lugar_nacimiento,g.sexo, g.id_tipo_documento, 
									g.documento, g.cuit, g.apellido, g.nombre, g.nacionalidad,g.id_localidad,g.profesion, g.calle, g.numero, g.piso, g.dpto, g.politico,
									g.cheque_nro, g.nro_ticket, g.cod_postal, g.sorteo_nro,g.estado_civil, g.telefono, g.email, g.domicilio,
								    g.valor_premio,to_char(g.fecha,'dd/mm/yyyy')fecha_alta, g.id_moneda, m.descripcion moneda, g.concepto, j.juegos, tp.descripcion tipo_pago, to_char(g.fecha_alta,'DD/MM/YYYY') as fecha_pago, 
									g.domicilio_pago, g.cuenta_bancaria_salida,to_char(g.fecha_nacimiento2,'dd/mm/yyyy')fecha_nacimiento2,g.lugar_nacimiento2,g.sexo2,
									g.id_tipo_documento2, g.documento2, g.cuit2, g.apellido2, g.nombre2, g.nacionalidad2,g.id_localidad2,g.profesion2, g.calle2, g.numero2, g.piso2, g.dpto2, 
									g.politico2,g.cod_postal2, g.estado_civil2, g.telefono2, g.email2,suc.nombre as sucursal,							 
                					td.descripcion, lo.n_localidad, pro.n_provincia, pa.n_pais, pa.id_pais, pro.id_provincia, sp.descripcion as pagador, g.ddjj
								from PLA_AUDITORIA.t_ganador g, PLA_AUDITORIA.t_tipo_documento td, PLA_AUDITORIA.t_moneda m, 
									PLA_AUDITORIA.t_tipo_pago tp, administrativo.t_paises pa, administrativo.t_provincias pro, 
									administrativo.t_localidades lo, juegos.juegos j, juegos.sucursal suc, superusuario.usuarios sp                
								where g.id_tipo_documento = td.id_tipo_documento
									and g.id_moneda= m.id_moneda
									and g.id_tipo_pago= tp.id_tipo_pago
									and g.id_localidad= lo.id_localidad(+)
									and lo.id_provincia= pro.id_provincia(+)
									and pro.id_pais= pa.id_pais(+)
									and g.juego=j.id_juegos
									and j.activo=1
									and g.id_tipo_pago=tp.id_tipo_pago
									and g.suc_ban=suc.suc_ban
									and g.usuario=sp.id_usuario
									$condicion_ganador");
	}


	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}


$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();


GETx;

$pdf->ln(-10);
//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line); 
while ($row = $rs_consulta->FetchNextObject($toupper=true)) {

	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(200,8,'Datos del Ganador',0,1,'C');

	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Fecha Nacimiento:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(40);
	$pdf->Cell(30,8,$row->FECHA_NACIMIENTO,0,0,'L');

	$pdf->SetX(70);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(25,8,'Tipo y Nro Doc:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	//$xx=utf8_decode($row->DESCRIPCION);
	$pdf->Cell(20,8,utf8_decode($row->DESCRIPCION).' '.$row->DOCUMENTO,0,0,'L');
		
	$pdf->SetX(160);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Sexo:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(170);
	$pdf->Cell(40,8,$row->SEXO,0,1,'L');	
		
	$pdf->SetFont('Arial','BU',9);
 	$pdf->Cell(30,8,'Cuit:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(20);
	$pdf->Cell(20,8,$row->CUIT,0,0,'L');
	
	$pdf->SetX(70);
	$pdf->SetFont('Arial','BU',9);
 	$pdf->Cell(40,8,'Lugar de Nacimiento:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(40,8,$row->LUGAR_NACIMIENTO,0,1,'L');

	
	$pdf->SetFont('Arial','BU',10);
	$pdf->Cell(40,8,'Apellido y Nombre:',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->SetX(45);
	$xx=utf8_decode($row->APELLIDO);
	$pdf->Cell(30,8,utf8_decode($xx).', '.utf8_decode($row->NOMBRE),0,1,'L');
	
	if($row->CALLE==''){
	    	  
	    $pdf->SetFont('Arial','BU',9);
		$pdf->Cell(40,8,'Calle:',0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(20);
		$xx=utf8_decode($row->DOMICILIO);
		$pdf->Cell(30,8,utf8_decode($xx),0,1,'L');

	  
	  }else {
			
		$pdf->SetFont('Arial','BU',9);
		$pdf->Cell(40,8,'Calle:',0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(20);
		$xx=utf8_decode($row->CALLE);
		$pdf->Cell(30,8,utf8_decode($xx),0,0,'L');
	}
	
	$pdf->setx(70);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Numero:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,$row->NUMERO,0,0,'L');

	$pdf->setx(120);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Piso:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->Cell(30,8,$row->PISO,0,0,'L');

	$pdf->setx(155);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Dpto:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->Cell(30,8,$row->DPTO,0,1,'L');

	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Codigo Postal:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(35);
	$pdf->Cell(30,8,$row->COD_POSTAL,0,0,'L');
	
	//$pdf->setx(70);

	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Localidad:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(87);
	$pdf->Cell(30,8,$row->N_LOCALIDAD,0,1,'L');
		
	//$pdf->setx(5);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Provincia:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(30);
	$pdf->Cell(30,8,$row->N_PROVINCIA,0,0,'L');
	
	$pdf->setx(70);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'País:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(80);
	$pdf->Cell(20,8,$row->N_PAIS,0,1,'L');
	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Estado Civil:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(30);
	$pdf->Cell(30,8,$row->ESTADO_CIVIL,0,0,'L');
	
	$pdf->setx(70);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Profesion:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(90);
	$pdf->Cell(30,8,$row->PROFESION,0,1,'L');
  	
	//$pdf->setx(120);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Persona Politicamente Expuesta:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(62);
	$pdf->Cell(30,8,$row->POLITICO,0,0,'L');
	
	$pdf->SetX(70);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Nro. DDJJ:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(90);
	$pdf->Cell(30,8,$row->DDJJ,0,1,'L');
	
	//$pdf->setx(120);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Telefono:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(30);
	$pdf->Cell(30,8,$row->TELEFONO,0,0,'L');
	
	$pdf->setx(70);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Email:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(80);
	$pdf->Cell(30,8,$row->EMAIL,0,1,'L');
	
	$pdf->Cell(30,8,"",0,1,'L');
	
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(200,8,'Datos del Premio',0,1,'C');
	
	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Fecha Pago:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(30);
	$pdf->Cell(30,8,$row->FECHA_PAGO,0,0,'L');
	
	$pdf->setx(70);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Valor del Premio:',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->setx(100);
	$pdf->Cell(30,8,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),0,0,'L');
	//'$ '.number_format($rowconta->TOTAL,2,',','.')
	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Moneda:',0,0,'R');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(30,8,$row->MONEDA,0,1,'L');
		
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Juego:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(21);
	$pdf->Cell(30,8,$row->JUEGOS,0,0,'L');
	
	$pdf->setx(43);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Sorteo:',0,0,'R');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(30,8,$row->SORTEO_NRO,0,0,'L');
	
	$pdf->setx(120);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Numero de Ticket/Cupon/Billete:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(170);
	$pdf->Cell(30,8,$row->NRO_TICKET,0,1,'L');

	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Concepto:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(30);
	$pdf->cell(30,8,$row->CONCEPTO,0,1,'L');
	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Instrumento de Pago:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(45);
	
	
	if ($row->CHEQUE_NRO!=""){
	//$pdf->setx(45);
	$pdf->Cell(30,8,$row->TIPO_PAGO,0,0,'L');
	$pdf->SetFont('Arial','BU',9);
	$pdf->setx(70);
	$pdf->Cell(40,8,'Cheque Nro:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(90);
	$pdf->Cell(30,8,$row->CHEQUE_NRO,0,1,'L');
	} else {
		$pdf->Cell(30,8,$row->TIPO_PAGO,0,1,'L');
	}
	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Domicilio de Pago:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(40);
	$pdf->cell(30,8,$row->DOMICILIO_PAGO,0,0,'L');
	
	$pdf->setx(145);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Cuenta Bancaria:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(172);
	$pdf->cell(30,8,$row->CUENTA_BANCARIA_SALIDA,0,1,'L');
	
	$pdf->Cell(30,8,"",0,1,'L');	
	
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(200,8,'Datos del Receptor Real del Premio (Si fuera distinto del ganador)',0,1,'C');
	$pdf->SetFont('Arial','B',10);
	if($row->DOCUMENTO2==""){
	$pdf->Cell(40,8,'No posee datos',0,1,'L');
		
	}else {

			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Fecha Nacimiento:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(40);
			$pdf->Cell(30,8,$row->FECHA_NACIMIENTO2,0,0,'L');
		
			$pdf->SetX(70);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(25,8,'Tipo y Nro Doc:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(20,8,$row->DESCRIPCION.' '.$row->DOCUMENTO2,0,0,'L');
				
			$pdf->SetX(120);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Sexo:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(130);
			$pdf->Cell(40,8,$row->SEXO2,0,1,'L');
			
			
			
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(30,8,'Cuit:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(20);
			$pdf->Cell(20,8,$row->CUIT2,0,0,'L');
			
			$pdf->SetX(70);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Lugar de Nacimiento:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(40,8,$row->LUGAR_NACIMIENTO2,0,1,'L');
		
			
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Apellido y Nombre:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(40);
			$pdf->Cell(30,8,$row->APELLIDO2.', '.$row->NOMBRE2 ,0,1,'L');
			
			//$pdf->setx(40);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Calle:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(20);
			$pdf->Cell(30,8,$row->CALLE2,0,0,'L');
		
			$pdf->setx(70);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Numero:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(90);
			$pdf->Cell(30,8,$row->NUMERO2,0,0,'L');
		
			$pdf->setx(120);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Piso:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(30,8,$row->PISO2,0,0,'L');
		
			$pdf->setx(155);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Dpto:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(30,8,$row->DPTO2,0,1,'L');
		
			
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Codigo Postal:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->setx(35);
			$pdf->Cell(30,8,$row->COD_POSTAL2,0,0,'L');
			
			$pdf->setx(70);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'País:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->setx(80);
			$pdf->Cell(20,8,$row->N_PAIS,0,0,'L');
			
			$pdf->setx(90);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Provincia:',0,0,'R');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(30,8,$row->N_PROVINCIA,0,0,'L');
			
			$pdf->setx(155);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Localidad:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->setx(174);
			$pdf->Cell(30,8,$row->N_LOCALIDAD,0,1,'L');
			
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Estado Civil:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(30);
			$pdf->Cell(30,8,$row->ESTADO_CIVIL2,0,0,'L');
			
			$pdf->setx(70);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Profesion:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->setx(90);
			$pdf->Cell(30,8,$row->PROFESION2,0,1,'L');
			
			/*$pdf->setx(120);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Persona Politicamente Expuesta:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(30,8,$row->POLITICO2,0,1,'L');*/
			
			//$pdf->setx(120);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Telefono:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(30);
			$pdf->Cell(30,8,$row->TELEFONO2,0,0,'L');
			
			$pdf->setx(70);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Email:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(30,8,$row->EMAIL2,0,1,'L');
		
			
	}
	//$pdf->SetX(150);

	//$pdf->Cell(30,8,"",0,1,'L');
	
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(200,8,'Datos del Pagador',0,1,'C');


	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Fecha de Carga:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(37);
	$pdf->Cell(30,8,$row->FECHA_ALTA,0,1,'L');
	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Sucursal de Carga:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(40);
	$pdf->Cell(30,8,$row->SUCURSAL,0,0,'L');
	$pdf->SetFont('Arial','BU',9);
	$pdf->setx(90);
	$pdf->Cell(40,8,'Usuario que Cargo:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(120);
	$pdf->Cell(30,8,$row->PAGADOR,0,1,'L');

	} 

//$pdf->Cell(180,7,'',0,1,'R');

//$pdf->ln(-7);

$pdf->Cell(30,8,"",0,1,'L');

$pdf->SetFont('Arial','BU',9);
$pdf->Cell(40,8,'Tipo de doc. presentado:',0,1,'L');
$pdf->Cell(40,8,'Listado de terroristas:',0,1,'l');
$pdf->Cell(40,8,'Imagen del doc:',0,1,'l');
$pdf->Cell(40,8,'Imagen del ticket/juego:',0,1,'L');
//$pdf->Cell(40,8,'Mov. registro caja pública:',0,1,'L');
$pdf->Cell(40,8,'Observaciones:',0,1,'L');


$pdf->setx(40);

$pdf->Output();
?>