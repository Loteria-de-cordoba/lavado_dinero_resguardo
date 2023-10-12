<?php
//$titulo='DATOS PERSONALES DEL GANADOR '. '- Delegacion '.$_GET['delegacion'];
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
									g.documento, g.cuit, g.apellido, g.nombre, g.nacionalidad,g.id_localidad,g.profesion, g.calle, g.numero, g.piso, g.dpto, g.politico,g.cargo,g.autoridad, g.invocado, g.denominacion_juridica,
									g.cheque_nro, g.nro_ticket, g.cod_postal, g.sorteo_nro,g.estado_civil, g.telefono, g.email,
								    g.valor_premio,to_char(g.fecha,'dd/mm/yyyy')fecha_pago, g.id_moneda, m.descripcion moneda, g.concepto, j.juegos, tp.descripcion tipo_pago, to_char(g.fecha_alta,'DD/MM/YYYY') as fecha_alta, 
									g.domicilio_pago, g.cuenta_bancaria_salida,to_char(g.fecha_nacimiento2,'dd/mm/yyyy')fecha_nacimiento2,g.lugar_nacimiento2,g.sexo2,
									g.id_tipo_documento2, g.documento2, g.cuit2, g.apellido2, g.nombre2, g.nacionalidad2,g.id_localidad2,g.profesion2, g.calle2, g.numero2, g.piso2, g.dpto2, 
									g.politico2,g.cod_postal2, g.estado_civil2, g.telefono2, g.email2,suc.nombre as sucursal,							 
                					td.descripcion, lo.n_localidad, pro.n_provincia, pa.n_pais, pa.id_pais, pro.id_provincia, sp.descripcion as pagador
								from lavado_dinero.t_ganador g, lavado_dinero.t_tipo_documento td, lavado_dinero.t_moneda m, 
									lavado_dinero.t_tipo_pago tp, administrativo.t_paises pa, administrativo.t_provincias pro, 
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

//$y_line=40;
		
//$pdf->Line(10,$y_line,200,$y_line); 
$y_line=215;
//$pdf->Line(10,$y_line,200,$y_line); 
while ($row = $rs_consulta->FetchNextObject($toupper=true)) {
	
	$pdf->ln(-15);
	$pdf->SetFont('Arial','BU',11);
	$pdf->Cell(200,8,'DATOS DEL GANADOR DEL PREMIO',0,1,'C');

	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'FECHA DE COBRO DEL PREMIO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(65);
	$pdf->Cell(30,8,$row->FECHA_PAGO,0,1,'L');

//	$pdf->SetX(70);
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'APELLIDO Y NOMBRE:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(50);
	$pdf->Cell(30,8,$row->APELLIDO.', '.$row->NOMBRE ,0,1,'L');

	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'SEXO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(20);
	$pdf->Cell(40,8,$row->SEXO,0,0,'L');
	
	$pdf->SetX(50);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(25,8,'FECHA DE NACIMIENTO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(90);
	$pdf->Cell(20,8,$row->FECHA_NACIMIENTO,0,0,'L');

	$pdf->SetX(110);
	$pdf->SetFont('Arial','B',9);
 	$pdf->Cell(40,8,'LUGAR DE NACIMIENTO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(40,8,$row->LUGAR_NACIMIENTO,0,1,'L');

	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(25,8,'TIPO DE DOC:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,8,$row->DESCRIPCION,0,0,'L');
	
	$pdf->SetX(50);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(25,8,'NRO DE DOC:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,8,$row->DOCUMENTO,0,1,'L');	
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'AUTORIDAD QUE EMITIO EL DOC:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(65);
	$pdf->Cell(30,8,$row->PAGADOR,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
 	$pdf->Cell(30,8,'CUIT/CUIL/CDI:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(35);
	$pdf->Cell(20,8,$row->CUIT,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(30,8,'NACIONALIDAD:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(30,8,$row->NACIONALIDAD,0,0,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'ESTADO CIVIL:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(95);
	$pdf->Cell(30,8,$row->ESTADO_CIVIL,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'PROFESION/ACTIVIDAD/OFICIO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(65);
	$pdf->Cell(30,8,$row->PROFESION,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'CALLE:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->SetX(25);
	$pdf->Cell(30,8,$row->CALLE,0,0,'L');

	$pdf->setx(70);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'NUMERO:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,$row->NUMERO,0,0,'L');

	$pdf->setx(120);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'PISO:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->Cell(30,8,$row->PISO,0,0,'L');

	$pdf->setx(155);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'DPTO:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->Cell(30,8,$row->DPTO,0,1,'L');

	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'LOCALIDAD:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(35);
	$pdf->Cell(30,8,$row->N_LOCALIDAD,0,0,'L');
	
	$pdf->setx(50);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'PROVINCIA:',0,0,'R');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(90);
	$pdf->Cell(30,8,$row->N_PROVINCIA,0,0,'L');
	
	$pdf->setx(130);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'CODIGO POSTAL:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(160);
	$pdf->Cell(30,8,$row->COD_POSTAL,0,1,'L');
		
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'TELEFONO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(30);
	$pdf->Cell(30,8,$row->TELEFONO,0,0,'L');
	
	$pdf->setx(70);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'EMAIL:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,$row->EMAIL,0,1,'L');
  	
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'PERSONA POLITICAMENTE EXPUESTA (PEP):',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,$row->POLITICO,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'CARGO/FUNCION/JERARQUIA/RELACION CON LA PEP:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(100);
	$pdf->Cell(30,8,$row->CARGO,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'CARACTER INVOCADO (TITULAR, REPRESENTANTE LEGAL, APODERADO):',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(130);
	$pdf->Cell(30,8,$row->INVOCADO,0,1,'L');

	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'DENOMINACION DE LA PERSONA JURIDICA (Cuando el firmante lo haga en caracter de apoderado o representante legal',0,1,'L');
	$pdf->Cell(40,8,'de una persona juridica):',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(50);
	$pdf->Cell(30,8,$row->DENOMINACION_JURIDICA,0,1,'L');

	 $y_line=$pdf->GetY();
	 $pdf->Line(5,$y_line,205,$y_line);
	//******************************************************************************
	
	$pdf->SetFont('Arial','BU',11);
	$pdf->Cell(200,8,'DATOS DEL PREMIO',0,1,'C');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'NOMBRE DEL JUEGO DEL PREMIO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(67);
	$pdf->Cell(30,8,$row->JUEGOS,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'SORTEO NRO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(35);
	$pdf->Cell(30,8,$row->SORTEO_NRO,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'NUMERO DEL TICKET/CUPON/BILLETE:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(75);
	$pdf->Cell(30,8,$row->NRO_TICKET,0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'VALOR DEL PREMIO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(45);
	$pdf->Cell(30,8,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),0,1,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'INSTRUMENTO DE PAGO:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(50);
	
	
	if ($row->CHEQUE_NRO!=""){
	//$pdf->setx(45);
	$pdf->Cell(30,8,$row->TIPO_PAGO,0,1,'L');
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'NRO DE CHEQUE:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(40);
	$pdf->Cell(30,8,$row->CHEQUE_NRO,0,1,'L');
	} else {
		$pdf->Cell(30,8,$row->TIPO_PAGO,0,1,'L');
	}

	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(40,8,'SUCURSAL Y DOMICILIO DE PAGO:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(70);
	$pdf->cell(30,8,$row->SUCURSAL.' - '.$row->DOMICILIO_PAGO,0,1,'L');
	
}	

$pdf->ln(30);	
$pdf->setx(30);	
$pdf->Cell(40,8,'..................................................',0,0,'L');	
$pdf->setx(130);
$pdf->Cell(40,8,'...........................................................',0,1,'L');	
$pdf->setx(30);	
$pdf->Cell(40,8,'Firma del Ganador del Premio',0,0,'L');		
$pdf->setx(130);	
$pdf->Cell(40,8,'Firma y sello del Funcionario de LPCSE',0,1,'L');		
	
	
$pdf->Output();	

?>