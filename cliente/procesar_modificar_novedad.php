<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
//$fecha = date("d/m/Y");
$fecha=$_POST['fecha'];
//echo $fecha;
//die();
//print_r($_POST);
//die();
$i=0;
$db->debug=true;
$casino=$_POST['casino'];
$id=$_POST['id'];
$fichaje=$_POST['valor_premio1'];
$ingresa=$_POST['mficing1'];
$acierto=$_POST['acierto1'];
$retira=$_POST['mficret1'];
$observa=$_POST['observamov1'];
$perdido=$_POST['monper1'];

ComenzarTransaccion($db);
			try {
				$db->Execute("update PLA_AUDITORIA.t_novedad_CASINO 
								set fichaje=?,
								acierto=?,
								mon_ing_fic=?,
								mon_fic_ret=?,
								mon_perdido=?,
								observa_mov=?
							WHERE 	id_novedad_casino=?", array($fichaje,$acierto,$ingresa,$retira,$perdido,$observa,$id));				
																   
							  
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
				


//obtengo id_cliente
try {
		$rs_cliente = $db ->Execute("select id_cliente as idc
										FROM PLA_AUDITORIA.t_novedad_CASINO 
										WHERE 	id_novedad_casino=?",array($id));
	}	
		
		catch (exception $e)
		{
		die ($db->ErrorMsg());
		} 
		
			$row_cliente =$rs_cliente->FetchNextObject($toupper=true);
			$cliente=$row_cliente->IDC;
	

//realizo update en tabla 
//de division PLA
//t_novedades_cliente
try {
				$db->Execute("update PLA_AUDITORIA.t_novedades_cliente
								set fichaje=?,
								acierto=?,
								mon_ing_fic=?,
								mon_fic_ret=?,
								mon_perdido=?,
								observa_mov=?
							WHERE 	id_casino_novedad=?
									and id_cliente=?
									and fecha_novedad=to_date(?,'dd/mm/yyyy')", array($fichaje,$acierto,$ingresa,$retira,$perdido,$observa,$casino,$cliente,substr($fecha,0,10)));				
																   
							  
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
FinalizarTransaccion($db);

	try {
			$rs_casino_auditoria = $db ->Execute("select  n_casino as descripcion from casino.t_casinos
												where  id_casino=?",array($casino));}									
												
												catch (exception $e){die ($db->ErrorMsg());} 
	$row_casino_auditoria =$rs_casino_auditoria->FetchNextObject($toupper=true);
	$cas_audita=$row_casino_auditoria->DESCRIPCION;

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
/*obtengo el nombre del apostador
try {
 $rs_ap = $db ->Execute("SELECT us.apellido as uu FROM 
					PLA_AUDITORIA.t_cliente US
					WHERE id_cliente=?
					", array($apostador));}
											
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_ap =$rs_ap->FetchNextObject($toupper=true);
			$soycliente=$row_ap->UU; 
			echo $soycliente;*/

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' MODIFICA movimientos en '.$cas_audita.' en fecha '.$serfecha;
 
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
header ("location:adm_novedad_casino.php?casino=$casino&fecha=$fecha");
?>