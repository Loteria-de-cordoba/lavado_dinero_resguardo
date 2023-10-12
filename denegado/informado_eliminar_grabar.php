<?php session_start(); 
include ("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//print_r($_REQUEST);
//$db->debug=true;
//die('entre');
//print_r($_POST);
//echo "<br><br><br>";
//die('ENTRE');
$array_fecha = FechaServer();
$idid=$_REQUEST['id_id'];
//$fecha=$_REQUEST['fecha'];
$observafinal=$_REQUEST['observafinal'];
//$novedad=$_REQUEST['novedad'];


//si esta vencido tomo valor fecha de vto. sino es null
	/*if($vto=='S')
	{
		try {
		$rs_vto = $db ->Execute(" select DECODE(B.FECHA_CEDULA,NULL,'',DECODE(VTO,'S',to_char(ADD_MONTHS(b.fecha_cedula -1,6),'dd/mm/yyyy'),'')) FECHAVTO
									from PLA_AUDITORIA.informado_uif b
									where b.id_informado=?",array($idid));
			}							catch (exception $e){die ($db->ErrorMsg());} 
	$row_vto =$rs_vto->FetchNextObject($toupper=true);
	$fecha_vto=$row_vto->FECHAVTO;
	}*/
//INSERTO EN TABLA DE ELIMINACION
//no para este caso
/*
ComenzarTransaccion($db);	
						try {
						$db->Execute("insert into PLA_AUDITORIA.DENEGADO_ELIMINADO(
																   OBSERVAFINAL,
																   NOVEDAD,
																   FECHA_CEDULA,
																   FECHA_VTO,
																   USUARIO_ELIMINA
																	)																   
							  values (?,?,to_date(?,'DD/MM/YYYY'),to_date(?,'DD/MM/YYYY'),?)",
							  array($observafinal,
									$novedad,
									$fecha_cedula,
								    $fecha_vto,
									'DU'.$_SESSION['usuario']));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					
FinalizarTransaccion($db);
*/
//ELIMINARLO ES CAMBIARLE EL ESTADO DE 1  A 0

ComenzarTransaccion($db);			
					try {$db->Execute("UPDATE  PLA_AUDITORIA.informado_uif
					SET ESTADO=0,
					novedad_final=?
						where id_informado=?
						", array($observafinal,$idid));}
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

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' ELIMINA DEFINITIVAMENTE INFORMADO UIF en la fecha '.$serfecha;
 
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
	$ppp=$_SESSION['nro_pagina'];
//die('proceso en marcha');
header ("location:adm_informado.php?fecha=$serfecha&pagiactual=$ppp");
?>