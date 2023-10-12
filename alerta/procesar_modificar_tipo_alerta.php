<?php 
/* formulario de modificacion DE ALERTAS
* 21/10/13
* PARODI VICTOR 
*/
session_start();
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
//print_r($_REQUEST);
//$db->debug=true;
$idid=$_REQUEST['idid'];
$novedad=$_REQUEST['novedad'];
$descrip=$_REQUEST['apenom'];
//die('entre');

ComenzarTransaccion($db);
			try {
				$db->Execute("update PLA_AUDITORIA.tipo_alerta
								set descripcion=?,
								funcion=?
							WHERE 	id_tipo_alerta=?",array(strtoupper($descrip),$novedad,$idid));				
																   
							  
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
				

//FinalizarTransaccion($db);


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


	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' MODIFICA TIPO DE ALERTAS en  fecha '.$serfecha;
 
 //inserto en tabla auditoria
 //ComenzarTransaccion($db);			
			
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
	//die('ok');
//$mensaje=urlencode('Alerta '.$descrip.' Modificada con Exito');
header ("location:adm_tipo_alerta.php");
?>
