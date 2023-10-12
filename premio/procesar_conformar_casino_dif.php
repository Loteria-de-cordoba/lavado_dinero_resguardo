<?php session_start();
include("../db_conecta_adodb.inc.php");
//$carpeta = session_id();



//print_r($_POST);
//die();
//$db->debug=true; 
//ComenzarTransaccion($db);


if ($_SESSION['suc_ban']==72){
	//$suc_ban=81;
	$condicion_suc = "and suc_ban=81";	}
	else {  $suc_ban=$_SESSION['suc_ban'];
			$condicion_suc=" and suc_ban=$suc_ban";}

$fdesde=substr($_POST['fdesde'],0,10);
$fhasta=substr($_POST['fhasta'],0,10);

//EJERZO AUDITORIA
//obtengo datos complementarios
try {
		$rs_auditor = $db ->Execute("select to_char(sysdate,'hh24:mi:ss') as hora, to_char(sysdate,'dd/mm/yyyy') as fecha from dual");}	
		
		catch (exception $e){die ($db->ErrorMsg());} 
		
			$row_auditor =$rs_auditor->FetchNextObject($toupper=true);
	$serhora=$row_auditor->HORA;
	$serfecha=$row_auditor->FECHA;
	
	try {
			$rs_casino_auditoria = $db ->Execute("select suc_ban as codigo, nombre as descripcion 
													from juegos.sucursal 
													where suc_ban in (?)",array($_SESSION['suc_ban']));}									
												
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


//$fdesde=$_POST['fdesde'];
//$fhasta=$_POST['fhasta'];


for ($i=1;$i<=$_POST['conta2'];$i++)
{
//echo ('Entra for'.$i.'<br>');
		if (isset($_POST['conformadif'.$i]))
		{
		

//A PRIORI OBTENGO EL CONFORMADO DE ESTE PREMIO
//SI ES CERO COMO LUEGO LO TRANSFORMO, ENTONCES LO AUDITO		

try {
			$rs_conforma = $db->Execute("SELECT CONFORMADO AS CCONFORMA,
											to_char(fecha,'DD/MM/YYYY hh24:mi:ss')	as ffecha
										 FROM  PLA_AUDITORIA.t_ganador 
						  				 where  id_ganador=?  
						  ", array($_POST['conformadif'.$i]));
			}
	
		catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
$row_conforma =$rs_conforma->FetchNextObject($toupper=true);
$cconforma=$row_conforma->CCONFORMA;
$ffecha=$row_conforma->FFECHA;	
//echo ('Entra isset'.$i.'<br>');
		try {
			$db->Execute("update PLA_AUDITORIA.t_ganador 
						  set conformado='1', fecha_conforma=sysdate, usuario_conforma=?
						  where fecha_alta>=to_date(?,'dd/mm/yyyy') 
						  and fecha_alta<=to_date(?,'dd/mm/yyyy')
						  and id_ganador=?
						  and conformado='0'  
						  $condicion_suc", array('DU'.$_SESSION['usuario'],$fdesde,$fhasta,$_POST['conformadif'.$i]));
			}
	
		catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
			
			//obtengo el nombre del apostador
 try {
 $rs_uu11 = $db ->Execute("SELECT ga.apellido || ' ' || ga.nombre as apenom
 				 FROM 	PLA_AUDITORIA.t_ganador ga
					WHERE ga.id_ganador=?
					", array($_POST['conformadif'.$i]));}
											
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_uu11 =$rs_uu11->FetchNextObject($toupper=true);
			$apostador=$row_uu11->APENOM; 



  $describa='CONFORMA GANADOR: Siendo las '.$serhora.' horas,  El Agente '.$auditado.' CONFORMA movimiento del Sr. '.$apostador.' en '.$cas_audita.' - fecha/hora de la Carga: '.$ffecha;

 //inserto en tabla auditoria
 //ComenzarTransaccion($db);			
		if($cconforma==0)//si la modificacion no fue hecha auditamos
		{		
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
			}//fin de audita
	
		}
}

	
/*try {
	$db->Execute("update casino.t_reg_cp 
				  set conformado_uif=1
				  where fecha BETWEEN to_date(?,'dd/mm/yyyy') AND to_date(?,'dd/mm/yyyy') 
				  AND importe_plata>=10000",
				  array($fdesde,$fhasta));
	}
	
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}*/
	
	
	
	/*
	try {
	$row=$db->Execute("select to_char(fecha_conforma,'dd/mm/yyyy') as fecha_conforma from PLA_AUDITORIA.t_ganador 
				  		where to_char(fecha_conforma,'dd/mm/yyyy')=to_char(sysdate,'dd/mm/yyyy')
						and usuario_conforma=?
						$condicion_suc",
				  		array('DU'.$_SESSION['usuario']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		
	$consulta = $row->FetchNextObject($toupper=true);
	$fdesde=$consulta->FECHA_CONFORMA;
	$fhasta=$consulta->FECHA_CONFORMA;*/
	
	//die();
	//FinalizarTransaccion($db); 
	
header ("location:mostrar_pre_conformacion.php?fdesde=$fdesde&fhasta=$fhasta");?>




