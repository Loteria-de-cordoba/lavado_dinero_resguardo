<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
ComenzarTransaccion($db);
$fecha = date("d/m/Y");
//print_r($_POST);
//die(); 
//$db->debug=true;
/*if ($_SESSION['suc_ban']==72){
	$sucursal=81;
} else {
	$sucursal=$_SESSION['suc_ban'];
}
*/
//CONTROLO POLITICO - campo invocado
if(isset($_POST['politico']) and substr($_POST['politico'],0,2)=='NO') 
{
	$invocatus='';	
}
else
{
	$invocatus=$_POST['invocado'];	
}

try {
	$db->Execute("update  lavado_dinero.t_ganador 
					set fecha_nacimiento=to_date(?,'dd/mm/yyyy'),
					   lugar_nacimiento=?,
					   sexo=?,
					   id_tipo_documento=?,
					   documento=?,
					   cuit=?,
					   apellido=?, 
					   nombre=?, 
					   nacionalidad=?, 
					   profesion=?,
					   calle=?,
					   numero=?,
					   piso=?,
					   dpto=?, 
					   politico=?,
					   cargo=?,
					   autoridad=?,
					   invocado=?,
					   denominacion_juridica=?,
					   estado_civil=?,
					   telefono=?,
					   email=?,
					   id_localidad=?,
					   cod_postal=?,
					   ddjj=?,					   
					   fecha_alta=to_date(?,'dd/mm/yyyy'),
					   valor_premio=?,
					   nro_ticket=?,
					   id_moneda=?,
					   concepto=?,
					   juego=?, 
					   sorteo_nro=?, 
					   id_tipo_pago=?, 
					   domicilio_pago=?, 
					   cuenta_bancaria_salida=?,
					   cheque_nro=?,					   
					   fecha_nacimiento2=to_date(?,'dd/mm/yyyy'),
					   lugar_nacimiento2=?,
					   sexo2=?,
					   id_tipo_documento2=?, 
					   documento2=?, 
					   cuit2=?,
					   apellido2=?, 
					   nombre2=?, 
					   nacionalidad2=?,
					   profesion2=?,
					   calle2=?,
					   numero2=?,
					   piso2=?,
					   dpto2=?, 
					   politico2=?,
					   cargo2=?,
					   autoridad2=?,
					   invocado2=?,
					   denominacion_juridica2=?,
					   estado_civil2=?,
					   telefono2=?,
					   email2=?,
					   id_localidad2=?,
					   cod_postal2=?,						
					   fecha_modifica=to_date(?,'dd/mm/yyyy'),
					   usuario_modifica=?
				  where id_ganador =?",
				 array(	$_POST['fecha'],
						$_POST['lugar_nacimiento'],
						$_POST['sexo'],
						$_POST['id_tipo_documento'],
				  		$_POST['documento'],
						$_POST['cuit'],
						$_POST['apellido'],
						$_POST['nombre'],
						$_POST['nacionalidad'],
						$_POST['profesion'],
						$_POST['calle'],
						$_POST['numero'],
						$_POST['piso'],
						$_POST['dpto'],
						$_POST['politico'],
						$_POST['cargo'],
						$_POST['autoridad'],
						$invocatus,
						$_POST['denominacion_juridica'],
						$_POST['estado_civil'],
						$_POST['telefono'],
						$_POST['email'],						
						$_POST['cod_localidad'],
						$_POST['cod_postal'],
						$_POST['ddjj'],
												
						$_POST['fecha_pago'],
						$_POST['valor_premio'],
						$_POST['nro_ticket'],
						$_POST['id_moneda'],
						$_POST['concepto'],
						$_POST['juego'],
						$_POST['sorteo_nro'],
						$_POST['id_tipo_pago'],
						$_POST['domicilio_pago'],
						$_POST['cuenta_bancaria'],
						$_POST['cheque_nro'],
												
						$_POST['fecha2'],
						$_POST['lugar_nacimiento2'],
						$_POST['sexo2'],
						$_POST['id_tipo_documento2'],
						$_POST['documento2'],
						$_POST['cuit2'],
						$_POST['apellido2'],
						$_POST['nombre2'],
						$_POST['nacionalidad2'],
						$_POST['profesion2'],
						$_POST['calle2'],
						$_POST['numero2'],
						$_POST['piso2'],
						$_POST['dpto2'],
						$_POST['politico2'],
						$_POST['cargo2'],
						$_POST['autoridad2'],
						$_POST['invocado2'],
						$_POST['denominacion_juridica2'],
						$_POST['estado_civil2'],
						$_POST['telefono2'],
						$_POST['email2'],
						$_POST['cod_localidad2'],
						$_POST['cod_postal2'],
						$fecha,
						'DU'.$_SESSION['usuario'],
						$_POST['id_ganador']));
	
}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
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

//obtengo el nombre del apostador
 /*try {
 $rs_uu11 = $db ->Execute("SELECT ga.apellido || ' ' || ga.nombre as apenom
 				 FROM 	lavado_dinero.t_ganador ga
					WHERE ga.id_ganador=?
					", array($_POST['ddjj']));}
											
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_uu11 =$rs_uu11->FetchNextObject($toupper=true);
			$apostador=$row_uu11->APENOM; */

$apostador=$_POST['apellido'].' '.$_POST['nombre'];

$describa='MODIFICA GANADOR: Siendo las '.$serhora.' horas,  El Agente '.$auditado.' MODIFICA movimiento del Sr. '.$apostador.' en '.$cas_audita.' monto: '.$_POST['valor_premio'].' fecha de Pago '.$_POST['fecha_pago'].' - fecha de modific. '.date('d/m/Y');

 //inserto en tabla auditoria
 //ComenzarTransaccion($db);			
			
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

	
	/*
$descripcion="Libreta Cívica";
$db->debug=true;	
try {
	$rs_suc_ban = $db->Execute("update lavado_dinero.t_tipo_documento
								set descripcion=?
								where id_tipo_documento=?", array(utf8_encode($descripcion),5));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
	*/
$fdesde=$_POST['fdesde'];
$fhasta=$_POST['fhasta'];
$conformado=$_POST['conformado'];
$casa=$_POST['casa'];
$mayores=$_POST['mayores'];
FinalizarTransaccion($db);
$i=0;
while ($i<$_SESSION['cantidadroles'])  {
	$i=$i+1;
if ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA') {
$_SESSION['bandera']=1;
header ("location:adm_premio_administra.php?fecha=$fdesde&fhasta=$fhasta&suc_ban=$casa&conformado=$conformado&mayores=$mayores");
} else {
	header ("location:adm_premio.php?fecha=$fdesde&fhasta=$fhasta&casa=$casa&conformado=$conformado");
	}
}
?>