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
//$db->debug=true;
$casino=$_POST['casino'];
$cantidad=$_POST['cant']+1;
if(isset($_POST['cheque_nro']) && $_POST['cheque_nro']<>'')
{
	$cheque_nro=$_POST['cheque_nro'];
}
else
{
	$cheque_nro=NULL;
}

for ($i = 1; $i < $cantidad; $i++)
{
	//echo "pppp".substr(strtolower($_POST['textapostador'.$i]),0,3);
//die();
			$apostador=$_POST['apostador'.$i];
			
			//echo $apostador;
			//die();
			if(isset($_POST['textapostador'.$i]) and substr(strtolower($_POST['textapostador'.$i]),0,3)<>'reg')
			{
				$textapostador=utf8_encode($_POST['textapostador'.$i]);
			}
			else
			{
				$textapostador='';
			}
			$monto=$_POST['valor_premio'.$i];
			//nuevos campos
			$fic_ing=$_POST['mficing'.$i];
			$fic_ret=$_POST['mficret'.$i];
			$observacion=$_POST['observamov'.$i];
			$monper=$_POST['monper'.$i];
			$acierto=$_POST['acierto'.$i];
	//echo 'apost'.$apostador.'texto del apostador'.$textapostador.'monto'.$monto.'fic_ing'.$fic_ing.'fic_ret'.$fic_ret.'observ'.$observacion.'montoper'.$monper.'acierto'.$acierto;
//}	
	
//die();

//if($monto<>0)//SACO RESTRICCION DE MONTO
//{


			
			//if($textapostador<>'' or $apostador==0)
				if($textapostador<>'' and $apostador==0)
					{//inserto el apostador
					//echo "paso ".$monto."apost".$textapostador;
						ComenzarTransaccion($db);
						try {
					$rs_cl = $db->Execute("select lavado_dinero.SEQ_ID_NOVEDAD_CASINO.nextval as clisec from dual");
					}
					catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					$row_cl = $rs_cl->FetchNextObject($toupper=true);
					$secuencia_cli = $row_cl->CLISEC;
						try {
						$db->Execute("insert into lavado_dinero.t_CLIENTE(
																   APELLIDO,
																   ID_CASINO,
																   FECHA_ALTA,
																   USUARIO,
																   id_cliente
																	)																   
							  values (?,?,to_date(?,'DD/MM/YYYY'),?,?)",
							  array($textapostador,
									 $casino,
									 $fecha,
									 'DU'.$_SESSION['usuario'],
									 $secuencia_cli));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					$apostador= $secuencia_cli;
			FinalizarTransaccion($db);
			//obtengo el id_cliente
			/*ComenzarTransaccion($db);	
				try {	
					$clcl=$db->Execute("select id_cliente as clie
									from lavado_dinero.t_CLIENTE
									where id_cliente=?", array($textapostador,
									 $casino,
									 $fecha,
									 'DU'.$_SESSION['usuario']));
			
					}
					catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					$row_clcl = $clcl->FetchNextObject($toupper=true);
					$apostador = $row_clcl->CLIE;
					FinalizarTransaccion($db);*/
			
				}
				//echo "el apostador ".$textapostador." el cliente ".$apostador;
	
	if($apostador<>0 and $apostador<>-1)
	{
				ComenzarTransaccion($db);			
			try {
				$rs = $db->Execute("select lavado_dinero.SEQ_ID_NOVEDAD_CASINO.nextval as secuencia from dual");
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
			$row = $rs->FetchNextObject($toupper=true);
			$secuencia = $row->SECUENCIA;
			
			//echo $secuencia;
			//die();
			try {
				$db->Execute("insert into lavado_dinero.t_novedad_CASINO (
																   id_novedad_CASINO, id_cliente, fecha_novedad,
																   fichaje, acierto, usuario, 
																   id_casino,
																   mon_ing_fic,
																   mon_fic_ret,
																   mon_perdido,
																   observa_mov,
																   CONFIRMADO,
																   ENVIADO
																	)							
																   
							  values (?,?,to_date(?,'DD/MM/YYYY'),?,?,?,?,?,?,?,?,?,?)",
							  array($secuencia,
									 $apostador,
									 $fecha,
									 $monto,
									 $acierto,
									 'DU'.$_SESSION['usuario'],
									  $casino,
									 $fic_ing,
									$fic_ret,
									$monper,
									$observacion,
									'N',
									'N'));
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
				FinalizarTransaccion($db);
				
				

			
				
				
		}
//}SACO RESTRICCION DE MONTO
}

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
					lavado_dinero.t_cliente US
					WHERE id_cliente=?
					", array($apostador));}
											
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_ap =$rs_ap->FetchNextObject($toupper=true);
			$soycliente=$row_ap->UU; 
			echo $soycliente;*/

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' INSERTA movimientos en '.$cas_audita.' en fecha '.$serfecha;
 
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
//die('Proceso hecho');
//$casino=$_POST['casino'];
//$apostador=$_POST['apostador'];
//$fhasta=$_POST['fhasta'];
header ("location:adm_novedad_casino.php?casino=$casino&fecha=$fecha");
?>