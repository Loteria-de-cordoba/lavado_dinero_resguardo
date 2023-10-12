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
$observafinal=$_REQUEST['observafinal'];
$novedad=$_REQUEST['novedad'];
$fecha_cedula=substr($_REQUEST['fecha_cedula'],0,10);
$vto=$_REQUEST['vto'];
##DIA 13/05/2014 QUITO -1 A FECHA VTO
//si esta vencido tomo valor fecha de vto. sino es null
	if($vto=='S')
	{
		try {
		$rs_vto = $db ->Execute(" select DECODE(B.FECHA_CEDULA,NULL,'',DECODE(VTO,'S',to_char(ADD_MONTHS(b.fecha_cedula,6),'dd/mm/yyyy'),'')) FECHAVTO
									from lavado_dinero.denegado b
									where b.id_denegado=?",array($idid));
			}							catch (exception $e){die ($db->ErrorMsg());} 
	$row_vto =$rs_vto->FetchNextObject($toupper=true);
	$fecha_vto=$row_vto->FECHAVTO;
	}
//INSERTO EN TABLA DE ELIMINACION

ComenzarTransaccion($db);	
						try {
						$db->Execute("insert into lavado_dinero.DENEGADO_ELIMINADO(
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



ComenzarTransaccion($db);			
					try {$db->Execute("delete from lavado_dinero.denegado
						where id_denegado=?
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

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' ANULA 1 Cedula de fecha vigencia '.$fecha_cedula.' en la fecha '.$serfecha;
 
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
//die('proceso en marcha');
header ("location:adm_denegado.php?fecha=$serfecha");
?>