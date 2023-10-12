<?php session_start(); 
include ("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//print_r($_GET);
$db->debug=true;
//die();
//print_r($_POST);
//echo "<br><br><br>";
//die();
$array_fecha = FechaServer();
$casino=$_GET['casino'];
$fecha=$_GET['fecha'];
//$usuario='DU'.$_SESSION['usuario'];	
//$fechita = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					//Elimino registros del detalle
//obtengo id_cliente
try {
		$rs_cliente = $db ->Execute("select id_cliente as idc
										FROM PLA_AUDITORIA.t_novedad_CASINO 
										WHERE 	id_novedad_casino=?",array($_GET['id_id']));
	}	
		
		catch (exception $e)
		{
		die ($db->ErrorMsg());
		} 
		
			$row_cliente =$rs_cliente->FetchNextObject($toupper=true);
			$cliente=$row_cliente->IDC;

ComenzarTransaccion($db);			
					try {$db->Execute("delete from PLA_AUDITORIA.t_novedad_casino
						where id_novedad_casino=?
						", array($_GET['id_id']));}
						catch(exception $e){die($db->ErrorMsg());} 



//ELIMINO DE TABLA
//DE PLA
//T_NOVEDADES_CLIENTE
try {$db->Execute("delete from PLA_AUDITORIA.t_novedades_cliente
							WHERE id_casino_novedad=?
									and id_cliente=?
									and fecha_novedad=to_date(?,'dd/mm/yyyy')", 
									array($casino,$cliente,substr($fecha,0,10)));	
	}		
	catch(exception $e){die($db->ErrorMsg());} 
	
FinalizarTransaccion($db);
//die('Finalizado');
//$apostador=$_GET['id_apostador'];

//$fhasta=$_GET['fhasta'];

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

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' ANULA 1 movimiento de fecha '.$fecha.' en '.$cas_audita.' y en la fecha '.$serfecha;
 
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