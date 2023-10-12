<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
//$fecha = date("d/m/Y");
//$db->debug=true;
$fecha=$_REQUEST['fecha'];
$fhasta=$_REQUEST['fhasta'];
//print_r($_REQUEST);
//die('entre');
$apenom=$_REQUEST['apenom'];
$novedad=$_REQUEST['novedad'];
$id_id=$_REQUEST['id_id'];
ComenzarTransaccion($db);
			try {
				$db->Execute("update lavado_dinero.informado_uif
								set novedad_final=?,
								estado=1
							WHERE 	id_informado=?", array($novedad,$id_id));				
																   
							  
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


	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' RECUPERA INFORMADO UIF  en  fecha '.$serfecha;
 
 //inserto en tabla auditoria
 ComenzarTransaccion($db);			
			
			try {
				$db->Execute("insert into lavado_dinero.t_auditoria_externa (
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
	$ppp=$_SESSION['nro_pagina'];
//die('Proceso hecho');
//$casino=$_REQUEST['casino'];
//$aREQUESTador=$_REQUEST['aREQUESTador'];
//$fhasta=$_REQUEST['fhasta'];
//die();
header ("location:adm_informado_eliminado.php?fecha=$fecha&idid=$idid&pagiactual=$ppp&fhasta=$fhasta");
?>