<?php
//$titulo='DATOS PERSONALES DE GANADORES ';
$habilitarCharSet=true;
require("header_listado.php"); 

//$db->debug=true;

$area=$_REQUEST['area'];


if (isset($_GET['conformado'])&& $_GET['conformado']==1 ) {
						$conformado = $_GET['conformado'];
						$condicion_conforma="and g.conformado ='$conformado'";
						$tit="Conformado";
}
if (isset($_GET['conformado'])&& $_GET['conformado']==0 ) {
						$conformado = $_GET['conformado'];
						$condicion_conforma="and g.conformado ='$conformado'";
						$tit=" No Conformado";
}

if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and g.suc_ban in ($suc_ban)";
		} elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
				$suc_ban = 0;
				$condicion_sucursal = "and g.suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,51)";
		} 


while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	if ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC' or $_SESSION['rol'.$i]=='LAVADO_DINERO_CONFORMA_TODO'){
		
		if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and g.suc_ban in ($suc_ban)";
		} elseif (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
				$suc_ban = 0;
				$condicion_sucursal = "and g.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
		} 
	}
	
	
}


if($area==77 || $area==78)//subgcia de casinos
		{
		if(!isset($_REQUEST['suc_ban']) || $_REQUEST['suc_ban']==0)
			{
			$condicion_sucursal = "and g.suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81)";
			}
		else
			{
			$suc_ban = $_REQUEST['suc_ban'];
			$condicion_sucursal = "and g.suc_ban in ($suc_ban)";
			}
		}
	if($area==168 || $area==52)//dpto delegaciones
		{
		if(!isset($_REQUEST['suc_ban']) || $_REQUEST['suc_ban']==0)
			{
			$condicion_sucursal = "and g.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,34,51)";
			}
		else
			{
			$suc_ban = $_REQUEST['suc_ban'];
			$condicion_sucursal = "and g.suc_ban in ($suc_ban)";
			}
		}
	
	/*
	
	if ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC'){
		$sucursal="and g.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33)";
		$suc_ban = $_GET['suc_ban'];
		$condicion_sucursal = "and g.suc_ban in ($suc_ban)";
	} else if($_GET['suc_ban']!=0){
		$sucursal="and g.suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33)";
		$suc_ban = $_GET['suc_ban'];
		$condicion_sucursal = "and g.suc_ban in ($suc_ban)";
	} else {
		$condicion_sucursal ="";
			}
}
*/

/*if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and g.suc_ban in ($suc_ban)";
			}
*/

$fecha=$_GET['fecha'];
$fhasta=$_GET['fhasta'];	

try {
	$rs_consulta = $db->Execute("select g.id_ganador,to_char(g.fecha_nacimiento,'dd/mm/yyyy')fecha_nacimiento,g.lugar_nacimiento,g.sexo, g.id_tipo_documento, 
									g.documento, g.cuit, g.apellido, g.nombre, g.nacionalidad,g.id_localidad,g.profesion, g.calle, g.numero, g.piso, g.dpto, g.politico,
									g.cheque_nro, g.nro_ticket, g.cod_postal, g.sorteo_nro,g.estado_civil, g.telefono, g.email,
								    g.valor_premio,to_char(g.fecha_alta,'dd/mm/yyyy')fecha_PAGO, g.id_moneda, m.descripcion moneda, g.concepto, j.juegos, tp.descripcion tipo_pago, to_char(g.fecha,'DD/MM/YYYY') as fecha_alta, 
									g.domicilio_pago, g.cuenta_bancaria_salida,to_char(g.fecha_nacimiento2,'dd/mm/yyyy')fecha_nacimiento2,g.lugar_nacimiento2,g.sexo2,
									g.id_tipo_documento2, g.documento2, g.cuit2, g.apellido2, g.nombre2, g.nacionalidad2,g.id_localidad2,g.profesion2, g.calle2, g.numero2, g.piso2, g.dpto2,g.ddjj,
									g.politico2,g.cod_postal2, g.estado_civil2, g.telefono2, g.email2,suc.nombre as sucursal,							 
                					td.descripcion, lo.n_localidad, pro.n_provincia, pa.n_pais, pa.id_pais, pro.id_provincia, sp.descripcion as pagador,
									'Tipo de doc. presentado:'tipo_doc, 'Listado de terroristas:'terroristas, 'Imagen del doc:'imagen_doc, 'Imagen del ticket/juego:'imagen_ticket, 'Mov. registro caja pública:'mov, 'Observaciones:'obs,
									(SELECT WW.DESCRIPCION 
									FROM LAVADO_DINERO.T_GANADOR TT,
										SUPERUSUARIO.USUARIOS WW
										WHERE TT.USUARIO_CONFORMA=WW.ID_USUARIO
										AND TT.ID_GANADOR=G.ID_GANADOR) AS CONFORMADOR, 
										DECODE(g.ddjj,NULL,'-----',G.DDJJ) AS DDJJ, 
										DECODE(g.NRO_aGEN,0,'----',g.NRO_aGEN) AS NRO_aGEN, 
										G.SUC_VTA AS SUC_VTA,
										G.SUC_BAN AS SUQUI
								from lavado_dinero.t_ganador g, lavado_dinero.t_tipo_documento td, lavado_dinero.t_moneda m, 
									lavado_dinero.t_tipo_pago tp, administrativo.t_paises pa, administrativo.t_provincias pro, 
									administrativo.t_localidades lo, juegos.juegos j, juegos.sucursal suc, superusuario.usuarios sp                
								where g.id_tipo_documento = td.id_tipo_documento
									and g.id_moneda= m.id_moneda
									and g.id_tipo_pago= tp.id_tipo_pago
									and g.id_localidad= lo.id_localidad(+)
									and lo.id_provincia= pro.id_provincia(+)
									and pro.id_pais= pa.id_pais(+)
									and g.fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
									
									and g.juego=j.id_juegos
									and j.activo=1
									and g.id_tipo_pago=tp.id_tipo_pago
									and g.suc_ban=suc.suc_ban
									and g.usuario=sp.id_usuario
									and g.fecha_baja is null
									and g.usuario_baja is null
									$condicion_conforma
									$condicion_sucursal
									order by g.suc_ban, g.fecha_alta desc");
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
	//$pdf->Cell(200,8,'Datos del Ganador '.$tit,0,1,'C');
	if($row->POLITICO=="SI")//si es politico va en rojo
				{
					$pdf->SetFillColor(240,0);
					$pdf->Cell(190,8,'Datos del Ganador PEPS '.$tit,0,1,'C',1);
				}
				else if($row->VALOR_PREMIO>=1000000)
				{
					$pdf->SetFillColor(240,100);
					$pdf->Cell(190,8,'Datos del Ganador - Premio>=1.000.000 '.$tit,0,1,'C',1);
				}
				else
				
				{
					$pdf->Cell(200,8,'Datos del Ganador '.$tit,0,1,'C');
				}
				

	
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
	$xx=trim($row->APELLIDO);
	$pdf->Cell(80,8,utf8_decode($xx).' '.utf8_decode(trim($row->NOMBRE)),0,0,'L');
	
	$pdf->SetFont('Arial','BU',10);
	$pdf->Cell(40,8,'Nacionalidad:',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->SetX(150);
	$pdf->Cell(30,8,utf8_decode(trim($row->NACIONALIDAD)),0,1,'L');
	
	if($row->CALLE==''){
	    	  
	    $pdf->SetFont('Arial','BU',9);
		$pdf->Cell(40,8,'Calle:',0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(20);
		$xx=$row->DOMICILIO;
		$pdf->Cell(30,8,utf8_decode($xx),0,1,'L');


	  
	  }else {
			
		$pdf->SetFont('Arial','BU',9);
		$pdf->Cell(40,8,'Calle:',0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(20);
		$xx=$row->CALLE;
		$pdf->Cell(30,8,utf8_decode($xx),0,0,'L');
	}
	
	$pdf->setx(70);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Numero:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->SetX(85);
	$pdf->Cell(30,8,$row->NUMERO,0,0,'L');

	$pdf->setx(125);
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
	$pdf->Cell(35,8,$row->COD_POSTAL,0,0,'L');
	
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
	$pdf->Cell(30,8,utf8_decode($row->ESTADO_CIVIL),0,0,'L');
	
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
	
	/* EN JULIO DE 2018 REEMPLAZO DESDE ESTA LINEA HASTA
	
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
	
	EN JULIO DE 2018 REEMPLAZO HASTA ESTA LINEA INCLUSIVE POR LO SIGUIENTE
	TRAIDO DE DATOS_GANADORES*/
	
		$pdf->Cell(30,5,"",0,1,'L');
	
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
	$pdf->Cell(20,8,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),0,0,'L');
	//'$ '.number_format($rowconta->TOTAL,2,',','.')
	
	$pdf->setx(120);
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
	
	//MAYO 2018
	//AGREGO NRO DE AGENCIA
	if($row->SUQUI!=$row->SUC_VTA)//DEBO OBTENER NOMBRE DE SUC DE ORIGEN
		{
			//OBTENGO SUC_BAN_SESION PARA MODIFICAR
					try {
							$rs_suqui = $db->Execute("SELECT DESCRIPCION AS SUCUCU
														FROM GESTION.T_SUCURSAL
															WHERE ID_SUCURSAL=?",array($row->SUC_VTA));
							}
							catch  (exception $e) 
							{ 
							die($db->ErrorMsg());
							}
							
					$row_suqui = $rs_suqui->FetchNextObject($toupper=true);	
					$suc_vta=$row_suqui->SUCUCU;
					$agen=$row->NRO_AGEN.'('.$suc_vta.')';
					if(strlen($agen)>17)
					{
						$agen=substr($agen,0,17).'.)';
					}
		}
		else
		{
					$agen=$row->NRO_AGEN;
		}
		
	$pdf->setx(145);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Numero de Agencia:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(177);
	$pdf->Cell(30,8,$agen,0,1,'L');
	
	

	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Concepto:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(30);
	$pdf->cell(30,8,utf8_decode($row->CONCEPTO),0,1,'L');
	
	//MAYO 2018
	//MUEVO TICKET
	//$pdf->setx(120);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Numero de Ticket/Cupon/Billete:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(62);
	$pdf->Cell(30,8,$row->NRO_TICKET,0,1,'L');
	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Instrumento de Pago:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(45);
	
	if(substr(strtoupper($row->TIPO_PAGO),0,2)=='TR')
		{
			$row->TIPO_PAGO='Transf. Bancaria';
		}
	
	if ($row->CHEQUE_NRO!=""){
	//$pdf->setx(45);
	$pdf->Cell(30,8,utf8_decode($row->TIPO_PAGO),0,0,'L');
	$pdf->SetFont('Arial','BU',9);
	$pdf->setx(70);
	$pdf->Cell(40,8,'Nro:',0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setx(85);
	$pdf->Cell(30,8,$row->CHEQUE_NRO,0,1,'L');
	} else {
		
		$pdf->Cell(30,8,$row->TIPO_PAGO,0,1,'L');
	}
	
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Domicilio de Pago:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(40);
	$pdf->cell(30,8,utf8_decode($row->DOMICILIO_PAGO),0,0,'L');
	
	$pdf->setx(145);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Cuenta Bancaria:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(172);
	$pdf->cell(30,8,$row->CUENTA_BANCARIA_SALIDA,0,1,'L');
	
	$pdf->Cell(30,5,"",0,1,'L');
	
	/*EN JULIO DE 2018 Y HACIENDO JUEGO
	CON QUE APAREZCA LA AGENCIA Y SUCURSAL DE PAGO
	SE SACA TB. ESTO QUE YA NO HACE FALTA
	
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
				
			$pdf->SetX(155);
			$pdf->SetFont('Arial','BU',9);
			$pdf->Cell(40,8,'Sexo:',0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->SetX(165);
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
			
			//$pdf->setx(120);
			//$pdf->SetFont('Arial','BU',9);
			//$pdf->Cell(40,8,'Persona Politicamente Expuesta:',0,0,'L');
			//$pdf->SetFont('Arial','',8);
			//$pdf->Cell(30,8,$row->POLITICO2,0,1,'L');
			
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
	JULIO 2018 SE SACA HASTA AQUI HACIENDO JUEGO CON LO INDIVIDUAL PLANILLA DATOS_GANADORES*/
	//$pdf->SetX(150);

	//$pdf->Cell(30,8,"",0,1,'L');
	
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(200,8,'Datos del Pagador',0,1,'C');


	$pdf->SetFont('Arial','BU',9);
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
	$pdf->setx(70);
	$pdf->Cell(40,8,'Usuario    Cargo:',0,0,'L');
    $pdf->SetFont('Arial','',8);
	$pdf->setx(100);
	$pdf->Cell(30,8,$row->PAGADOR,0,0,'L');
	$pdf->SetFont('Arial','BU',9);
	$pdf->setx(140);
	if($row->CONFORMADOR<>'')
		{
			$pdf->Cell(30,8,'Conformo: ',0,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->setx(158);	
			$pdf->Cell(30,8,$row->CONFORMADOR,0,1,'L');
		}
	else
		{
			$pdf->Cell(30,8,'',0,1,'L');
		}
	
	//AGREGO EN AGOSTO DE 2019
	$pdf->ln(5);
	$pdf->SetFont('Arial','BU',9);
	$pdf->Cell(40,8,'Reporte Control Filtro Terroristas:',0,1,'L');
	$pdf->Cell(40,8,'Reporte Control de Apostadores:',0,1,'l');
	$pdf->Cell(40,8,'OBSERVACIONES:',0,1,'L');
	
	
	/*$pdf->Cell(40,8,$row->TIPO_DOC,0,1,'L');
	$pdf->Cell(40,8,$row->TERRORISTAS,0,1,'l');
	$pdf->Cell(40,8,$row->IMAGEN_DOC,0,1,'l');
	$pdf->Cell(40,8,$row->IMAGEN_TICKET,0,1,'L');
	//$pdf->Cell(40,8,'Mov. registro caja pública:',0,1,'L');
	$pdf->Cell(40,8,$row->OBS,0,1,'L');*/

$pdf->ln(53);
	} 

//$pdf->Cell(180,7,'',0,1,'R');

//$pdf->ln(-7);

/*$pdf->Cell(30,8,"",0,1,'L');

$pdf->SetFont('Arial','BU',9);
$pdf->Cell(40,8,'Tipo de doc. presentado:',0,1,'L');
$pdf->Cell(40,8,'Listado de terroristas:',0,1,'l');
$pdf->Cell(40,8,'Imagen del doc:',0,1,'l');
$pdf->Cell(40,8,'Imagen del ticket/juego:',0,1,'L');
//$pdf->Cell(40,8,'Mov. registro caja pública:',0,1,'L');
$pdf->Cell(40,8,'Observaciones:',0,1,'L');
*/

$pdf->ln(30);

$pdf->Output();
?>