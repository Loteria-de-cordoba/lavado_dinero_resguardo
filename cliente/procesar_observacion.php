<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
//$fecha = date("d/m/Y");
$fecha=substr($_POST['fecha'],0,10);
//echo $fecha;
//die();
//print_r($_POST);
//die();
$i=0;
//$db->debug=true;
$casino=$_POST['casino'];
$novedad=substr($_POST['novedad'],0,2000);


ComenzarTransaccion($db);

if($novedad<>'')
{
			try {
								$rs_existe = $db->Execute("select COUNT(*)  as cuenta 
											from PLA_AUDITORIA.t_observa_casino
											where fecha=to_date(?,'dd/mm/yyyy')
											and casino=?",
											array($fecha,$casino));
								}
								catch  (exception $e) 
								{ 
								die($db->ErrorMsg());
								}
								$row_existe = $rs_existe->FetchNextObject($toupper=true);
								$cuenta = $row_existe->CUENTA;
								
								if($cuenta==0)
								{
								$rrr='Registra';
											try {
											$db->Execute("insert into PLA_AUDITORIA.t_observa_casino(
																					   fecha,
																					   casino,
																					   novedad
																						)																   
												  values (to_date(?,'DD/MM/YYYY'),?,?)",
												  array($fecha,
														 $casino,
														 $novedad));
												}
												catch  (exception $e) 
										{ 
										die($db->ErrorMsg());
										}
								}//termina cuenta=0
								else
								{
								$rrr='Modifica';
										try 
										{
												$db->Execute("update PLA_AUDITORIA.t_observa_casino
																	set novedad=?
																	where fecha=to_date(?,'dd/mm/yyyy')
																	and casino=?",
													  array(
															  $novedad,
															  $fecha,
															  $casino,
															 ));
													}
													catch  (exception $e) 
													{ 
													die($db->ErrorMsg());
													}
										
								}//fin del else
	}//fin de novedad <> a blanco
	else//paso por aca cuando está en blanco la novedad
	{
	try 
										{
										$rrr='Elimina';
												$db->Execute("delete from PLA_AUDITORIA.t_observa_casino
																	where fecha=to_date(?,'dd/mm/yyyy')
																	and casino=?",
													  array(
															  $fecha,
															  $casino,
															 ));
													}
													catch  (exception $e) 
													{ 
													die($db->ErrorMsg());
													}
										
	}//fin del else novedad en blanco
						
FinalizarTransaccion($db);


//EJERZO AUDITORIA
//obtengo datos complementarios
try {
		$rs_auditor = $db ->Execute("select to_char(sysdate,'hh24:mi:ss') as hora, to_char(sysdate,'dd/mm/yyyy') as fecha from dual");}	
		
		catch (exception $e){die ($db->ErrorMsg());} 
		
			$row_auditor =$rs_auditor->FetchNextObject($toupper=true);
	$serhora=$row_auditor->HORA;
	$serfecha=$row_auditor->FECHA;
	
	try {
			$rs_casino_auditoria = $db ->Execute("select  n_casino as descripcion from casino.t_casinos
												where  id_casino=?",array($casino));}									
												
												catch (exception $e){die ($db->ErrorMsg());} 
	$row_casino_auditoria =$rs_casino_auditoria->FetchNextObject($toupper=true);
	$cas_audita=$row_casino_auditoria->DESCRIPCION;

 //obtengo el nombre del usuario
 try {
 $rs_uu = $db ->Execute("SELECT us.descripcion as uu FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));}
											
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_uu =$rs_uu->FetchNextObject($toupper=true);
			$auditado=$row_uu->UU; 


	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' '.$rrr.' Observacion General '.$cas_audita.' de fecha '.$fecha;
 
 //inserto en tabla auditoria
 ComenzarTransaccion($db);			
			
			try {
				$db->Execute("insert into PLA_AUDITORIA.t_auditoria_externa (
																   fecha,
																   hora,
																   usuario,
																   descripcion																 
																	)
																   
							  values (to_date(?,'DD/MM/YYYY'),?,?,?)",
							  array($serfecha,
							  		$serhora,
									'DU'.$_SESSION['usuario'],
									$describa));
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
	FinalizarTransaccion($db);
//die('Proceso hecho');
//$casino=$_POST['casino'];
//$apostador=$_POST['apostador'];
//$fhasta=$_POST['fhasta'];
header ("location:adm_novedad_casino.php?casino=$casino&fecha=$fecha");
?>