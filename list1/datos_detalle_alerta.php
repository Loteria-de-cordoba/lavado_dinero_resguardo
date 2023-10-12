<?php
//die('entre');
//echo $_SESSION['mayor'];
//die('entre');
require_once("header_listado.php");
//print_r($_SESSION['mayor']);
//die('entre');
//print_r($_GET);
$tipo=$_REQUEST['tipo'];
$condicion_subtipo=urldecode($_REQUEST['condicion_subtipo']);
$titulin=urldecode($_REQUEST['titulin']);
$titulinnodebe=$_REQUEST['titulinnodebe'];
$observaciones=$_REQUEST['observaciones'];
$id_base=$_REQUEST['id_base'];
$largoobserva=strlen($_REQUEST['observaciones']);
$largoobserva=$largoobserva +27;
//die($condicion_subtipo);
//$db->debug=true;
$pospremio = strpos($titulinnodebe, ')');
$premio=substr($titulinnodebe,$pospremio-6,6);
//echo $premio;
if($tipo==7)
{
	switch ($premio)
	{
		case 'CASINO':
			$premiomayor=1;//PREMIOS DE CASINO
			break;
		case 'CRIPTO':
			$premiomayor=2;//PREMIOS PRESCRIPTOS
			break;
		case 'ORANEO':
			$premiomayor=3;//PREMIOS FORANEOS
			break;
		case 'O.PRES':
			$premiomayor=4;//PREMIOS FORANEOS PRESCRIPTOS
			break;
		default:
			$premiomayor=5;//PREMIOS COMUNES EN DEL. Y CASA CENTRAL
	}
}

//$db->debug=true;

$documento = $_REQUEST['documento'];
$tmp_alternativa_condicion=$_REQUEST['tmp_alternativa_condicion'];
if(empty($condicion_subtipo) && $tmp_alternativa_condicion == 9){
	$condicion_subtipo=" and upper(o.calle || ' ' || o.numero)=upper('$documento')";
}

//OBTENGO ESTADO Y FECHA_APARICION
try
{
$rs_estado = $db -> Execute("select ee.descripcion, ee.id_estado_alerta idid,
									to_char(fecha_aparicion,'DD/MM/YYYY') as aparece
								from lavado_dinero.estado_alerta ee,
									lavado_dinero.base_alerta b
									where ee.id_estado_alerta=b.id_estado_alerta
									and B.ID_BASE=?",array($id_base));
									}
					catch (exception $e){die($db->ErrorMsg());}
					$row_estado = $rs_estado ->FetchNextObject($toupper=true);
					$estadin=$row_estado->DESCRIPCION;
					$aparece=$row_estado->APARECE;
					$idid=$row_estado->IDID;



$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
switch($tipo)
{
							case 0:
								break;
							case 1:
							case 2:
							case 4:
							case 6:
							case 8:
							//obtengo el documento
								if($tipo==6)
									{
										$guion=strpos($_REQUEST['documento'],'-');
										$docucontrol=substr($_REQUEST['documento'],0,$guion-1);
									}
									else
									{
										$docucontrol=substr($_REQUEST['documento'],0,8);
									}		
								//obtengo EL DETALLE
									
									$rs_consulta = $db -> Execute("select to_char(o.fecha,'dd/mm/yyyy') as fecha,
																jj.juegos as juego,
																o.valor_premio as monto,
																decode(o.fecha_baja,NULL,ss.nombre,ss.nombre||'(Registro anulado)') as sucursal
														  from lavado_dinero.t_ganador o,
														  juegos.sucursal ss,
														  juegos.juegos jj
														  where o.suc_ban=ss.suc_ban
														  and o.juego=jj.id_juegos
														  and o.documento=?
														  $condicion_subtipo
														  order by o.fecha desc",array($docucontrol));
									//die('PROCES0');
										  //while ($row_rec = $rs_recorrido->FetchNextObject($toupper=true)) 
													//{ $nro=$row_rec->NRO;}
								//$rs_resumen->MoveFirst();
								//die('entra');
								
						
						
						
						//GETx;
						
						$pdf->ln(-10);
						//$y_line=40;
								
						//$pdf->Line(10,$y_line,200,$y_line); 
						$y_line=215;
						//$pdf->Line(10,$y_line,200,$y_line); 
						
						
						$pdf->Ln(-10);
						$pdf->SetFillColor(240,240,240);
							$pdf->SetFont('Arial','B',7);
							$pdf->Cell(190,8,$titulin,0,1,'C',1);
							$pdf->Ln(1);
							$pdf->SetFont('Arial','B',7);
							$pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($observaciones),1,'L',1);
							//$pdf->Cell($largoobserva,8,'Observaciones: '.utf8_decode($observaciones),0,1,'L',1);
							$pdf->SetFont('Arial','B',8);
							if($idid==1)
							{
								$pdf->SetTextColor(144,30,30); 
							}
							if($idid==3)
							{
								$pdf->SetTextColor(100,100); 
							}
							if($idid==2)
							{
								$pdf->SetTextColor(230,230); 
							}
							$pdf->Cell(50,8,'Estado: '.$estadin,0,1,'L',1);
							$pdf->SetTextColor(0,0,0); 
							$pdf->Cell(50,8,'Aparicion: '.utf8_decode($aparece),0,1,'L',1);						
							$pdf->Ln(12);
							$pdf->SetX(10);
							$pdf->SetFont('Arial','B',7);
							$pdf->Cell(35,8,'Fecha',1,0,'C');
							$pdf->Cell(22,8,'Juego',1,0,'C');
							$pdf->Cell(35,8,'Monto',1,0,'C');
							$pdf->Cell(98,8,'Sucursal',1,1,'C');
							
							
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
											$pdf->SetFont('Arial','B',7);
											$pdf->Cell(190,8,$titulin,0,1,'C',1);
											$pdf->Ln(1);
											$pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($observaciones),1,'L',1);
											$pdf->SetFont('Arial','B',8);
											if($idid==1)
											{
												$pdf->SetTextColor(144,30,30); 
											}
											if($idid==3)
											{
												$pdf->SetTextColor(100,100); 
											}
											if($idid==2)
											{
												$pdf->SetTextColor(230,230); 
											}
										$pdf->Cell(50,8,'Estado: '.$estadin,0,1,'L',1);
										$pdf->SetTextColor(0,0,0);
										$pdf->Cell(50,8,'Aparicion: '.utf8_decode($aparece),0,1,'L',1);	 
											$pdf->Ln(12);
											$pdf->SetX(10);
											$pdf->SetFont('Arial','B',7);
											$pdf->Cell(35,8,'Fecha',1,0,'C');
											$pdf->Cell(22,8,'Juego',1,0,'C');
											$pdf->Cell(35,8,'Monto',1,0,'C');
											$pdf->Cell(98,8,'Sucursal',1,1,'C');	
											$pdf->SetFont('Arial','',6);
												 
										 } 
						 $pdf->SetX(10);
						 
							$pdf->Cell(35,5,$row->FECHA,1,0,'L');
							//$repetido=$row->NOMBRE_ALERTA;		   
							$pdf->Cell(22,5,$row->JUEGO,1,0,'L');
							$pdf->SetFont('Arial','B',6);
							$pdf->Cell(35,5,number_format($row->MONTO,2,',','.'),1,0,'R');
							$pdf->SetFont('Arial','',6);
							$pdf->MultiCell(98,5,utf8_decode($row->SUCURSAL),1,'L');
							$toti=$toti+$row->MONTO;
						}
						$pdf->SetFont('Arial','',8);
                        $pdf->Cell(92,6,'MONTO TOTAL===>        $'.number_format($toti,2,'.',','),1,0,'R',1);
						break;
							case 3:
							
							//obtengo EL DETALLE
								
									$rs_consulta = $db -> Execute("select to_char(o.fecha,'dd/mm/yyyy') as fecha,
																jj.juegos as juego,
																o.valor_premio as monto,
																decode(o.fecha_baja,NULL,ss.nombre,ss.nombre||'(Registro anulado)') as sucursal
														  from lavado_dinero.t_ganador o,
														  juegos.sucursal ss,
														  juegos.juegos jj
														  where o.suc_ban=ss.suc_ban
														  and o.juego=jj.id_juegos
														  $condicion_subtipo
														  order by o.fecha desc");
										
										
							//$rs_resumen->MoveFirst();
							
						
						
						
						
						//GETx;
						
						$pdf->ln(-10);
						//$y_line=40;
								
						//$pdf->Line(10,$y_line,200,$y_line); 
						$y_line=215;
						//$pdf->Line(10,$y_line,200,$y_line); 
						
						
						$pdf->Ln(-10);
						$pdf->SetFillColor(240,240,240);
							$pdf->SetFont('Arial','B',7);
							$pdf->Cell(190,8,$titulin,0,1,'C',1);
							$pdf->Ln(1);
							$pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($observaciones),1,'L',1);
							$pdf->SetFont('Arial','B',8);
							if($idid==1)
							{
								$pdf->SetTextColor(144,30,30); 
							}
							if($idid==3)
							{
								$pdf->SetTextColor(100,100); 
							}
							if($idid==2)
							{
								$pdf->SetTextColor(230,230); 
							}
							$pdf->Cell(50,8,'Estado: '.$estadin,0,1,'L',1);
							$pdf->SetTextColor(0,0,0); 
							$pdf->Cell(50,8,'Aparicion: '.utf8_decode($aparece),0,1,'L',1);	
							$pdf->Ln(12);
							$pdf->SetX(10);
							$pdf->SetFont('Arial','B',7);
							$pdf->Cell(35,8,'Fecha',1,0,'C');
							$pdf->Cell(22,8,'Juego',1,0,'C');
							$pdf->Cell(35,8,'Monto',1,0,'C');
							$pdf->Cell(98,8,'Sucursal',1,1,'C');
							
							
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
										$pdf->SetFont('Arial','B',7);
										$pdf->Cell(190,8,$titulin,0,1,'C',1);
										$pdf->Ln(1);
										$pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($observaciones),1,'L',1);
										$pdf->SetFont('Arial','B',8);
											if($idid==1)
											{
												$pdf->SetTextColor(144,30,30); 
											}
											if($idid==3)
											{
												$pdf->SetTextColor(100,100); 
											}
											if($idid==2)
											{
												$pdf->SetTextColor(230,230); 
											}
											$pdf->Cell(50,8,'Estado: '.$estadin,0,1,'L',1);
											$pdf->SetTextColor(0,0,0); 
											$pdf->Cell(50,8,'Aparicion: '.utf8_decode($aparece),0,1,'L',1);	
										$pdf->Ln(12);
										$pdf->SetX(10);
										$pdf->SetFont('Arial','B',7);
										$pdf->Cell(35,8,'Fecha',1,0,'C');
										$pdf->Cell(22,8,'Juego',1,0,'C');
										$pdf->Cell(35,8,'Monto',1,0,'C');
										$pdf->Cell(98,8,'Sucursal',1,1,'C');	
										$pdf->SetFont('Arial','',6);		 
									 } 
						 $pdf->SetX(10);
						 
							$pdf->Cell(35,5,$row->FECHA,1,0,'L');
							//$repetido=$row->NOMBRE_ALERTA;		   
							$pdf->Cell(22,5,$row->JUEGO,1,0,'L');
							$pdf->SetFont('Arial','B',6);
							$pdf->Cell(35,5,number_format($row->MONTO,2,',','.'),1,0,'R');
							$pdf->SetFont('Arial','',6);
							$pdf->MultiCell(98,5,utf8_decode($row->SUCURSAL),1,'L');
							$toti=$toti+$row->MONTO;
						}
						$pdf->SetFont('Arial','',8);
                        $pdf->Cell(92,6,'MONTO TOTAL===>        $'.number_format($toti,2,'.',','),1,0,'R',1);
						break;
						
							case 7:
							
								//obtengo EL DETALLE
								switch ($premiomayor)
									{
											case 1://casino
							
													try {	
															$rs_consulta = $db -> Execute("select to_char(o.fecha,'dd/mm/yyyy') as fecha,
																			'CASINO' as juego,
																			o.IMPORTE_FICHA as monto,
																			O.CASA as sucursal
																	  from CASINO.T_REG_CP o
																	  where o.cod_mov_caja=?
																	  and o.importe_ficha>50000
																	  order by o.fecha desc",array(substr($_REQUEST['documento'],0,8)));
														}
														catch (exception $e){die($db->ErrorMsg());}
										
										
											break;
											case 2://PREMIOS PRESCRIPTOS
							
													try {
												$rs_consulta = $db -> Execute("select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
																			jj.descripcion as juego,
																			p.MONTO_TOTAL as monto,
																			su.descripcion as sucursal
																	 from kanban.t_premios_prescriptos p,
											  kanban.t_remito_premio_detalle_pres rpd,
											  kanban.t_remito_premio_cabecera_pres ca,
											  gestion.T_sucursal su,
											  gestion.T_agencia ag,
											  KANBAN.T_JUEGO JJ
											  where p.id_juego=6
											  and p.suc_ban=su.id_sucursal
											  AND P.NRO_AGEN=AG.ID_AGENCIA
											  and rpd.id_remito_cabecera=ca.id_remito_cabecera
											  and p.id_juego=ca.id_juego
											  AND P.ID_JUEGO=JJ.ID_JUEGO
											  AND p.monto_total>50000 
											  and p.pagado='S'
											  AND P.OCR=?
											UNION
												select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
																			jj.descripcion as juego,
																			p.MONTO_TOTAL as monto,
																			su1.NOMBRE as sucursal
																		  from kanban.t_premios_prescriptos p,
																		kanban.t_remito_premio_detalle_pres rpd,
																		kanban.t_remito_premio_cabecera_pres ca,
																		juegos.sucursal su1,
																		juegos.agencia ag1,
																		KANBAN.T_JUEGO JJ
																		where p.id_juego<>6
																		and p.suc_ban=su1.SUC_BAN
																		AND P.NRO_aGEN=AG1.NRO_AGEN
																		AND P.ID_JUEGO=JJ.ID_JUEGO
																		AND p.monto_total>50000
																		AND P.FECHA_PAGA>=SYSDATE-100
																		and p.pagado='S'
																		  AND P.OCR=?",array(substr($_REQUEST['documento'],0,8), substr($_REQUEST['documento'],0,8)));
													}
													catch (exception $e){die($db->ErrorMsg());}
											break;
											case 3://PREMIOS FORANEOS
												try {
											$rs_consulta = $db -> Execute("select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.importe as monto,
													su1.NOMBRE as sucursal
											 from kanban.t_premios p,
													kanban.t_remito_premio_detalle rpd,
													kanban.t_remito_premio_cabecera ca,
													KANBAN.T_JUEGO JJ,
													juegos.sucursal su1
													where  p.suc_ban=su1.SUC_BAN
													and rpd.id_remito_cabecera=ca.id_remito_cabecera
													and p.id_juego=ca.id_juego
													AND JJ.ID_JUEGO=P.ID_JUEGO
													AND p.importe>50000
													And p.pagado='S'
													AND (P.OCR=? or p.ocr=?)",array(substr($_REQUEST['documento'],0,8), substr($_REQUEST['documento'],0,9)));
							}
							catch (exception $e){die($db->ErrorMsg());}
											break;
											case 4://PREMIOS PRESCRIPTOS FORANEOS
											
											try {
						$rs_consulta = $db -> Execute("select distinct to_char(P.FECHA_PAGA,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.MONTO_TOTAL as monto,
													su1.NOMBRE as sucursal
											 from kanban.t_premios_prescriptos_FOR p,
												kanban.t_remito_premio_detalle_pres rpd,
												kanban.t_remito_premio_cabecera_pres ca,
												juegos.sucursal su1,
												juegos.agencia ag1,
												KANBAN.T_JUEGO JJ
												where  p.suc_ban=su1.SUC_BAN
												AND P.NRO_aGEN=AG1.NRO_AGEN
												AND P.ID_JUEGO=JJ.ID_JUEGO
												AND p.monto_total>50000
												and p.pagado='S'
												AND P.OCR=?",array(substr($_REQUEST['documento'],0,8)));
												}
												catch (exception $e){die($db->ErrorMsg());}
												break;
												default://premio comun
												
												try {
						$rs_consulta= $db -> Execute("select distinct to_char(ca.fecha,'dd/mm/yyyy') as fecha,
                          						p.id_juego,
													'QUINIELA' as juego,
													p.monto_total as monto,
													su.descripcion as sucursal
											 from kanban.t_premios_sin_reparto p,
												kanban.t_remito_premio_detalle rpd,
												kanban.t_remito_premio_cabecera ca,
												gestion.T_sucursal su
												where  ca.suc_ban=su.id_sucursal
													and rpd.id_remito_cabecera=ca.id_remito_cabecera
													AND ca.id_remito_cabecera=?
                          ANd p.monto_total>50000
													and p.pagado='S'
                          and ca.confirmado='S'
                          AND ca.anulado is null
                          and ca.sorteo=p.concurso
      			UNION                          
				select distinct to_char(ca.fecha,'dd/mm/yyyy') as fecha,p.id_juego,
													jj.descripcion as juego,
													p.importe as monto,
													su.descripcion as sucursal
											 from kanban.t_premios p,
												kanban.t_remito_premio_detalle rpd,
												kanban.t_remito_premio_cabecera ca,
												gestion.T_sucursal su,
												gestion.T_agencia ag,
												kanban.t_juego jj
												where rpd.billete=p.billete
													and rpd.fraccion=p.fraccion
													and ca.suc_ban=su.id_sucursal
													and rpd.id_remito_cabecera=ca.id_remito_cabecera
													and p.id_juego=ca.id_juego
													and jj.id_juego=p.id_juego
													AND ca.id_remito_cabecera=?
													and p.importe>50000
													and p.pagado='S'",array(substr($_REQUEST['documento'],0,8),substr($_REQUEST['documento'],0,8)));
							}
							catch (exception $e){die($db->ErrorMsg());}
						//GETx;
				}
						$pdf->ln(-10);
						//$y_line=40;
								
						//$pdf->Line(10,$y_line,200,$y_line); 
						$y_line=215;
						//$pdf->Line(10,$y_line,200,$y_line); 
						
						
						$pdf->Ln(-10);
						$pdf->SetFillColor(240,240,240);
							$pdf->SetFont('Arial','B',7);
							$pdf->Cell(190,8,$titulin.' TICKET/REMITO NRO. '.$_REQUEST['documento'],0,1,'C',1);
							$pdf->Ln(1);
							$pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($observaciones),1,'L',1);
							$pdf->SetFont('Arial','B',8);
							if($idid==1)
							{
								$pdf->SetTextColor(144,30,30); 
							}
							if($idid==3)
							{
								$pdf->SetTextColor(100,100); 
							}
							if($idid==2)
							{
								$pdf->SetTextColor(230,230); 
							}
							$pdf->Cell(50,8,'Estado: '.$estadin,0,1,'L',1);
							$pdf->SetTextColor(0,0,0);
							$pdf->Cell(50,8,'Aparicion: '.utf8_decode($aparece),0,1,'L',1);	 
							$pdf->Ln(12);
							$pdf->SetX(10);
							$pdf->SetFont('Arial','B',7);
							$pdf->Cell(35,8,'Fecha',1,0,'C');
							$pdf->Cell(22,8,'Juego',1,0,'C');
							$pdf->Cell(35,8,'Monto',1,0,'C');
							$pdf->Cell(98,8,'Sucursal',1,1,'C');
							
							
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
										$pdf->SetFont('Arial','B',7);
										$pdf->Cell(190,8,$titulin,0,1,'C',1);
										$pdf->Ln(1);
										$pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($observaciones),1,'L',1);
										$pdf->SetFont('Arial','B',8);
											if($idid==1)
											{
												$pdf->SetTextColor(144,30,30); 
											}
											if($idid==3)
											{
												$pdf->SetTextColor(100,100); 
											}
											if($idid==2)
											{
												$pdf->SetTextColor(230,230); 
											}
											$pdf->Cell(50,8,'Estado: '.$estadin,0,1,'L',1);
											$pdf->SetTextColor(0,0,0); 
											$pdf->Cell(50,8,'Aparicion: '.utf8_decode($aparece),0,1,'L',1);	
										$pdf->Ln(12);
										$pdf->SetX(10);
										$pdf->SetFont('Arial','B',7);
										$pdf->Cell(35,8,'Fecha',1,0,'C');
										$pdf->Cell(22,8,'Juego',1,0,'C');
										$pdf->Cell(35,8,'Monto',1,0,'C');
										$pdf->Cell(98,8,'Sucursal',1,1,'C');	
										$pdf->SetFont('Arial','',6);		 
									 } 
						 $pdf->SetX(10);
						 
							$pdf->Cell(35,5,$row->FECHA,1,0,'L');
							//$repetido=$row->NOMBRE_ALERTA;		   
							$pdf->Cell(22,5,$row->JUEGO,1,0,'L');
							$pdf->SetFont('Arial','B',6);
							$pdf->Cell(35,5,number_format($row->MONTO,2,',','.'),1,0,'R');
							$pdf->SetFont('Arial','',6);
							$pdf->MultiCell(98,5,utf8_decode($row->SUCURSAL),1,'L');
							$toti=$toti+$row->MONTO;
						}
						$pdf->SetFont('Arial','',8);
                        $pdf->Cell(92,6,'MONTO TOTAL===>        $'.number_format($toti,2,'.',','),1,0,'R',1);
						break;
						
						
						case 5:
							
						
						
						//GETx;
						
						$pdf->ln(-10);
						//$y_line=40;
								
						//$pdf->Line(10,$y_line,200,$y_line); 
						$y_line=215;
						//$pdf->Line(10,$y_line,200,$y_line); 
						
						
						$pdf->Ln(-10);
						$pdf->SetFillColor(240,240,240);
							$pdf->SetFont('Arial','B',7);
							$pdf->MultiCell(190,8,utf8_decode($titulinnodebe),1,'L',1);
							
							$pdf->Ln(1);
							$pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($observaciones),1,'L',1);
							$pdf->SetFont('Arial','B',8);
							if($idid==1)
							{
								$pdf->SetTextColor(144,30,30); 
							}
							if($idid==3)
							{
								$pdf->SetTextColor(100,100); 
							}
							if($idid==2)
							{
								$pdf->SetTextColor(230,230); 
							}
							$pdf->Ln(3);
							$pdf->Cell(50,8,'Estado: '.$estadin,0,1,'L',1);
							$pdf->SetTextColor(0,0,0); 
							$pdf->Cell(50,8,'Aparicion: '.utf8_decode($aparece),0,1,'L',1);	
							break;
							default:
								$a='a';
}
$pdf->Output();
//die('entre');
?>