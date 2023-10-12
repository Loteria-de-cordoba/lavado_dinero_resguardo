<?php session_start();
include("../db_conecta_adodb.inc.php");
//print_r($_POST);
//die();
$casino=$_GET['casino'];
$fecha=$_GET['fecha'];
//$fhasta=$_POST['fhasta'];
//$clienteviene=$_POST['id_cliente'];
//$cliente='';
//$casinoxx='';
//obtengo fecha

//$db->debug=true;


		ComenzarTransaccion($db);
		try {
			$db->Execute("update PLA_AUDITORIA.t_NOVEDAD_CASINO
									   set CONFIRMADO='N'
								where FECHA_NOVEDAD=TO_DATE(?,'DD/MM/YYYY')
								AND ID_CASINO=?",array($fecha, $casino));
			}
			catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
		
				
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


	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' Abre la jornada del  '.$cas_audita.' de fecha '.$fecha;
 
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
	header ("location:adm_novedad_casino.php?casino=$casino&fecha=$fecha");
?>