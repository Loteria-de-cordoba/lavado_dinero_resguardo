<?php session_start(); 
include ("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//print_r($_GET);
//$db->debug=true;
//die();
//print_r($_POST);
//echo "<br><br><br>";
//die();
$array_fecha = FechaServer();
$idid=$_REQUEST['idid'];
$fecha=$_REQUEST['fecha'];
//$observafinal=$_REQUEST['observafinal'];
//$novedad=$_REQUEST['novedad'];

ComenzarTransaccion($db);			
					try {$db->Execute("delete from PLA_AUDITORIA.TIPO_ALERTA
						where id_TIPO_ALERTA=?
						", array($idid));}
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
	if($fecha_cedula=='')
	{
		$fecha_cedula=' nula ';
	}

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' Elimina un tipo de alerta';
 
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
//die('proceso en marcha');
header ("location:adm_tipo_alerta.php?fecha=$serfecha");
?>