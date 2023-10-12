<?php 
/* formulario de modificacion DE detalle de riesgos
* 08/01/2014
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
//die();
//$db->debug=true;
//die('entre');
$riesgo=$_REQUEST['riesgo'];
$incidencia=$_REQUEST['incidencia'];
$voy=$_REQUEST['voy'];
$abro=$_REQUEST['abro'];
$pesitos=$_REQUEST['pesitos'];
$cuit=$_REQUEST['cuit'];
$otrocuit=$_REQUEST['otrocuit'];
$fecha=$_REQUEST['fecha'];
$id=$_REQUEST['id'];
$descri=trim($_REQUEST['descripcion']);
		if(isset($_REQUEST['minimo']))
			{
				$minimo=$_REQUEST['minimo'];
				
			}
			else
			{
				$minimo='';
			}
		if(isset($_REQUEST['maximo']))
			{
				$maximo=$_REQUEST['maximo'];
			}
			else
			{
				$maximo='';
			}
//die('entre');
ComenzarTransaccion($db);
if($riesgo==1)//tabla_t_risgo_sujeto
{

			try {
				$db->Execute("update PLA_AUDITORIA.t_riesgo_sujeto
								set descripcion=?,
								incidencia=?
							WHERE 	id_riesgo_sujeto=?", array(strtoupper($descri),$incidencia,$id));				
																   
							  
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
}//seguir con otros dos casos
else if($riesgo==2)
{
try {
				$db->Execute("update PLA_AUDITORIA.t_riesgo_valor
								set descripcion=?,
								incidencia=?,
								minimo=?,
								maximo=?
							WHERE 	id_riesgo_valor=?", array(strtoupper($descri),$incidencia,$minimo,$maximo,$id));				
																   
							  
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
}
else
{
try {
				$db->Execute("update PLA_AUDITORIA.t_riesgo_caract
								set descripcion=?,
								incidencia=?,
								minimo=?,
								maximo=?
							WHERE 	id_riesgo_caract=?", array(strtoupper($descri),$incidencia,$minimo,$maximo,$id));				
																   
							  
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
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


	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' MODIFICA BASE DE RIESGOS SUJETOS en  fecha '.$serfecha;
 
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
$mensaje=urlencode('Componente '.$descri.' Modificada con Exito');
header ("location:estadistico.php?vengomod=1&mensaje=$mensaje&riesgo=$riesgo&voy=$voy&abro=0&pesitos=$pesitos&cuit=$cuit&otrocuit=$otrocuit&fecha=$fecha&componente=$incidencia&minimo=$minimo&maximo=$maximo");
?>
