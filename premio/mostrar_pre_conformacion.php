<?php session_start();
include("../jscalendar-1.0/calendario.php");
include("../db_conecta_adodb.inc.php");
include("../funcion.inc.php");
//print_r($_REQUEST);

if(isset($_REQUEST['suc_ban']))
{
	$suc_ban=$_REQUEST['suc_ban'];
}

//print_r($_SESSION['rol1']);
//echo $suc_ban;
//$db->debug=true;

/*echo 'get ';
print_r($_GET);
echo '<br>';
echo 'post ';
print_r($_POST);
*/
//print_r($_SESSION);
//echo '<br>LISTO<br>';
//$fdesde=$_POST['fdesde'].' 00:00';
//$fhasta=$_POST['fhasta'].' 23:59';


if (isset($_GET['fdesde'])) {
	$fdesde = $_GET['fdesde'];
}
else{
	if (isset($_POST['fdesde'])) {
		$fdesde = $_POST['fdesde'];
	}
	 else {
			$fdesde = $_POST['fdesde'];
	}
}
			
if (isset($_GET['fhasta'])) {
	$fhasta = $_GET['fhasta'];
}
 else {
	if (isset($_POST['fhasta'])) {
		$fhasta = $_POST['fhasta'];
	} else {
			$fhasta =$_POST['fhasta'];
		}
}
$cuentas='665,642,611,482,483,719';
if ($_SESSION['rol1']<>'LAVADO_DINERO_CONFORMA_TODO'){//si no es este rol toma la sesion suc_ban	  
		try {$rs_conforma_gana = $db->Execute("select sum(valor_premio) as total, count(valor_premio) as registros 
												from PLA_AUDITORIA.t_ganador 
												where suc_ban = ?
												--and conformado=0
												and fecha_alta between to_date('$fdesde 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')",
												array($_SESSION['suc_ban']));
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
		}
	}
	else//si es rol conforma todo (nehme)
	{
		try {$rs_conforma_gana = $db->Execute("select sum(valor_premio) as total, count(valor_premio) as registros 
												from PLA_AUDITORIA.t_ganador 
												where suc_ban = ?
												--and conformado=0
												and fecha_alta between to_date('$fdesde 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')",
												array($suc_ban));
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
		}
		
	} 
while ($row_conforma_gana=$rs_conforma_gana->FetchNextObject($toupper=true)) {
	$suma_ganador=$row_conforma_gana->TOTAL;
	//echo 'Total_Gana : '.$row_conforma_gana->TOTAL.' - Registros: '.$row_conforma_gana->REGISTROS,'<br>';
}
//die($suc_ban);
/*saco por ahora
*/
if ($_SESSION['suc_ban']==1 and $_SESSION['rol1']<>'ROL_LAVADO_DINERO_ADM_SIN_CC' and $_SESSION['rol1']<>'LAVADO_DINERO_CONFORMA_TODO') {
		try {$rs_conforma_conta = $db->Execute("select (sum(a.total)) as total
											  from conta_new.asiento_cabecera a 
											  where a.cod_area_vinculante is null
												  and a.fecha_valor between to_date('$fdesde 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
												  and (upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%') ");
			}
			
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
		} 
		$row_conforma_conta=$rs_conforma_conta->FetchNextObject($toupper=true);
		$suma_contabilidad=$row_conforma_conta->TOTAL;
		$diferencia='delegacion';
		//echo 'Total_Gana : '.$row_conforma_conta->TOTAL.' - Registros: '.$row_conforma_conta->REGISTROS.'<br>';		
} else//no es suc_ban 1
		{
			if($_SESSION['rol1']<>'LAVADO_DINERO_CONFORMA_TODO')//no es rol de nehme
				{
				 if(($_SESSION['suc_ban']==27)
				 or ($_SESSION['suc_ban']==23)
				 or ($_SESSION['suc_ban']==25) 
				 or ($_SESSION['suc_ban']==34) 
				 or ($_SESSION['suc_ban']==26) 
				 or ($_SESSION['suc_ban']==30) 
				 or ($_SESSION['suc_ban']==20)
				 or ($_SESSION['suc_ban']==21)  
				 or ($_SESSION['suc_ban']==31) 
				 or ($_SESSION['suc_ban']==24)
				 or ($_SESSION['suc_ban']==33)	
				 or ($_SESSION['suc_ban']==22)
				 or ($_SESSION['suc_ban']==32)
				 or ($_SESSION['suc_ban']==51))
				 { //si viene por session
					try {$rs_conforma_conta = $db->Execute("select  sum(a.total) as total
															from conta_new.asiento_cabecera a, adm.area c 
															where a.cod_area_vinculante=c.cod_area
															and c.suc_ban=?
															and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
															and (upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%')",
															array($_SESSION['suc_ban']));
							}
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
						} 
						$row_conforma_conta=$rs_conforma_conta->FetchNextObject($toupper=true);
						$suma_contabilidad=$row_conforma_conta->TOTAL;
						$diferencia='delegacion';
				  }//termina por session
				  else{//es casino
					try {$rs_conforma_conta = $db->Execute("SELECT SUM(importe_plata) AS total,
															COUNT(importe_plata) AS registros
															FROM casino.t_reg_cp
															WHERE casa = upper(substr(?,8))
																AND fecha BETWEEN to_date('$fdesde','DD/MM/YYYY HH24:MI') AND to_date('$fhasta','DD/MM/YYYY HH24:MI')
																AND importe_plata>=25000",
															array($_SESSION['area']));
							}
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
						} 
						$row_conforma_conta=$rs_conforma_conta->FetchNextObject($toupper=true);
						$suma_contabilidad=$row_conforma_conta->TOTAL;
						$diferencia='casino';
				}//cierra casino
			}//cierra no rol de nehme 
		else//viene rol de nehme
		{
			if(($suc_ban==27)
				 or ($suc_ban==23)
				 or ($suc_ban==25) 
				 or ($suc_ban==34) 
				 or ($suc_ban==26) 
				 or ($suc_ban==30) 
				 or ($suc_ban==20)
				 or ($suc_ban==21)  
				 or ($suc_ban==31) 
				 or ($suc_ban==24)
				 or ($suc_ban==33)	
				 or ($suc_ban==22)
				 or ($suc_ban==32)
				 or ($suc_ban==51))
				 { //veo que sucursal viene
					try {$rs_conforma_conta = $db->Execute("select  sum(a.total) as total
															from conta_new.asiento_cabecera a, adm.area c 
															where a.cod_area_vinculante=c.cod_area
															and c.suc_ban=?
															and a.fecha_valor between to_date('$fdesde','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
															and (upper(a.concepto) like '%UIF%' or upper(a.concepto) like '%U.I.F.%')",
															array($suc_ban));
							}
							catch (exception $e)
							{
							die ($db->ErrorMsg()); 
						} 
						$row_conforma_conta=$rs_conforma_conta->FetchNextObject($toupper=true);
						$suma_contabilidad=$row_conforma_conta->TOTAL;
						$diferencia='delegacion';
					
		}//termina por $suc_ban
		else{//es casino
			try {$rs_conforma_conta = $db->Execute("SELECT SUM(importe_plata) AS total,
													COUNT(importe_plata) AS registros
													FROM casino.t_reg_cp
													WHERE casa = upper(substr(?,8))
														AND fecha BETWEEN to_date('$fdesde','DD/MM/YYYY HH24:MI') AND to_date('$fhasta','DD/MM/YYYY HH24:MI')
														AND importe_plata>=25000",
													array($_SESSION['area']));
					}
					catch (exception $e)
					{
					die ($db->ErrorMsg()); 
				} 
				$row_conforma_conta=$rs_conforma_conta->FetchNextObject($toupper=true);
				$suma_contabilidad=$row_conforma_conta->TOTAL;
				$diferencia='casino';
		}//cierra else casino
		}//else rol de nehme
}//no es suc_ban1
//termino saco por ahora*/		
include("ver_detalle_registros_diferencia.php");?>
