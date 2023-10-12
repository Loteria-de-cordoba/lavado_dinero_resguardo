<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
//print_r($_POST);
//die('entro');
//$fecha = date("d/m/Y");
//$db->debug=true;
$fecha=$_POST['fecha'];
$apenom=$_POST['apenom'];
$novedad=$_POST['novedad'];
//OBTENGO MAXIMO Y SUMO 1
try {
						$rs_proximo=$db->Execute("SELECT MAX(ID_tipo_alerta)+1 AS MAYOR
										FROM PLA_AUDITORIA.TIPO_ALERTA");
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					$row_proximo=$rs_proximo->FetchNextObject($toupper=true);
					$mayor=$row_proximo->MAYOR;
					
//INSERTO DATO
						try {
						$db->Execute("insert into PLA_AUDITORIA.tipo_alerta(ID_TIPO_ALERTA,
																   DESCRIPCION,
																   funcion
																	)																   
							  values (?,?,?)",
							  array($mayor,
							  		strtoupper($apenom),
									 $novedad));
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

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' INSERTA 1 nuevo tipo de alerta  en fecha '.$serfecha;
 
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
//die();
header("location:adm_tipo_alerta.php?fecha=$fecha");
?>