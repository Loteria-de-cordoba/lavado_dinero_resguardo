<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
//$fecha = date("d/m/Y");
//$db->debug=true;
$fecha=$_POST['fecha'];
//print_r($_POST);
//die();
//$primer=$_POST['primedig'];
//$docu=$_POST['docu'];
//$ultimo=$_POST['ultdig'];
$apenom=$_POST['apenom'];
$novedad=$_POST['novedad'];
$idid=$_POST['idid'];
//$fechacedula=substr($_POST['fechacedula'],0,10);
//$fecha_cedula=$_REQUEST['fecha_cedula'];
//$fecha_vto=$_REQUEST['fecha_vto'];
//$cuit= substr($primer,0,2).substr($docu,0,8).substr($ultimo,0,1);
ComenzarTransaccion($db);
			try {
				$db->Execute("update PLA_AUDITORIA.informado_uif
								set novedad=?,
								descripcion=?
							WHERE 	id_informado=?", array($novedad,$apenom,$idid));				
																   
							  
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


	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' MODIFICA INFORMADO UIF en  fecha '.$serfecha;
 
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
header ("location:adm_informado.php?fecha=$fecha&idid=$idid");
?>