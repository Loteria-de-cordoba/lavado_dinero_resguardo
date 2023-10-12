<?php session_start();
include("../db_conecta_adodb.inc.php");
//print_r($_SESSION);
//die();
$usuariocierra='DU'.$_SESSION['usuario'];
$casino=$_GET['casino'];
$fecha=$_GET['fecha'];
//$fhasta=$_POST['fhasta'];
//$clienteviene=$_POST['id_cliente'];
//$cliente='';
//$casinoxx='';
//obtengo fecha

$db->debug=true;


		ComenzarTransaccion($db);
		
		
		//paso datos
		try {
			$db->Execute("insert into PLA_AUDITORIA.t_novedades_cliente
				(id_cliente,fecha_novedad,fichaje,acierto,confirmado,usuario,id_casino_novedad,mon_ing_fic,
				mon_fic_ret,mon_perdido,observa_mov,usuario_conforma) (select id_cliente,fecha_novedad,fichaje,acierto,confirmado,usuario,id_casino,mon_ing_fic,
							mon_fic_ret,mon_perdido,observa_mov, '$usuariocierra' as xx
							from PLA_AUDITORIA.t_novedad_casino
							where fecha_novedad=to_date(?,'dd/mm/yyyy')
							and id_casino=?
							and lower(enviado)='n')",array($fecha, $casino));
			}
			catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
		//actualizo origen
		try {
			$db->Execute("update PLA_AUDITORIA.t_NOVEDAD_CASINO
									   set CONFIRMADO='S',
									    ENVIADO='S'
								where FECHA_NOVEDAD=TO_DATE(?,'DD/MM/YYYY')
								AND ID_CASINO=?",array($fecha, $casino));
			}
			catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
	
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


	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' Cierra la jornada del  '.$cas_audita.' de fecha '.$fecha;
 
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
		
		FinalizarTransaccion($db);
		//die('proceso hecho');
	header ("location:adm_novedad_casino.php?casino=$casino&fecha=$fecha");
?>