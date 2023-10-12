<?php session_start();
include("../db_conecta_adodb.inc.php");
include("../../ftp.inc.php");
$carpeta = session_id();
ComenzarTransaccion($db);
$fecha = date("d/m/Y h:m:s");


//print_r($_POST);
//die();

//$db->debug=true;



if ($_SESSION['suc_ban']==72){
	$sucursal=81;
} else {
	$sucursal=$_SESSION['suc_ban'];
}

//echo('ENTRA!!');



try {
	$rs = $db->Execute("select lavado_dinero.SEQ_GANADOR.nextval as secuencia from dual");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
$row = $rs->FetchNextObject($toupper=true);
$secuencia = $row->SECUENCIA;

try {
	$db->Execute("insert into lavado_dinero.t_ganador (id_ganador, 
													   fecha_nacimiento,
													   lugar_nacimiento,
													   sexo,
													   id_tipo_documento,
													   documento,
													   cuit,
													   apellido, 
													   nombre, 
													   nacionalidad, 
													   profesion,
													   calle,
													   numero,
													   piso,
													   dpto,
													   politico, 
													   cargo,
													   autoridad,
													   invocado,
													   denominacion_juridica,
													   estado_civil,
													   telefono,
													   email,
													   suc_ban,
													   id_localidad,
													   cod_postal,
													   ddjj,
													   
													   fecha_alta,
													   valor_premio,
													   nro_ticket,
													   id_moneda,
													   concepto,
													   juego, 
													   sorteo_nro, 
													   id_tipo_pago, 
													   domicilio_pago, 
													   cuenta_bancaria_salida,
													   cheque_nro,
													   
													   fecha_nacimiento2,
													   lugar_nacimiento2,
													   sexo2,
													   id_tipo_documento2, 
													   documento2, 
													   cuit2,
													   apellido2, 
													   nombre2, 
													   nacionalidad2,
													   profesion2,
													   calle2,
													   numero2,
													   piso2,
													   dpto2, 
													   politico2,
													   cargo2,
													   autoridad2,
													   invocado2,
													   denominacion_juridica2,
													   estado_civil2,
													   telefono2,
													   email2,
													   id_localidad2,
													   cod_postal2,
														
													   fecha,
													   usuario) 
				  values (?,to_date(?,'DD/MM/YYYY'),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,to_date(?,'DD/MM/YYYY'),?,?,?,?,?,?,?,?,?,?,to_date(?,'DD/MM/YYYY'),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,to_date(?,'DD/MM/YYYY hh24:mi:ss'),?)",
				  array($secuencia,
				  		$_POST['fecha'],
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
						$_POST['invocado'],
						$_POST['denominacion_juridica'],
						$_POST['estado_civil'],
						$_POST['telefono'],
						$_POST['email'],
						$sucursal,
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
						'DU'.$_SESSION['usuario']));
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

$describa='CARGA GANADOR: Siendo las '.$serhora.' horas,  El Agente '.$auditado.' CARGA movimiento del Sr. '.$apostador.' en '.$cas_audita.' monto: '.$_POST['valor_premio'].' fecha de Pago '.$_POST['fecha_pago'].' - fecha de carga '.date('d/m/Y');

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

/*try {
	$rs_suc_ban = $db->Execute("insert into casino.t_reg_cp (cargado)
	 							values(?)", array($_POST['suc_ban']));
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
*/



	
/*function ConectarFTP()
		{
		//$servidor = "172.16.0.100";
		$servidor = "172.16.50.91";
		$puerto = 21;
		$timeout = 50;
		$user = "oracle10";
		$pass = "oracle10";
		//Obtiene un manejador del Servidor FTP
		$id_ftp=ftp_connect($servidor, $puerto, $timeout);
		//Se loguea al Servidor FTP
		ftp_login($id_ftp, $user, $pass); 
		//Devuelve el manejador a la funci?n
		return $id_ftp; 
		}*/
if (is_dir("../upload_ajax/upload/$carpeta")) {		
	$id_ftp=ConectarFtpOracle(); 

	$files=scandir("../upload_ajax/upload/$carpeta");
	for ($i=0;$i<count($files);$i++) { 
		if ($files[$i]!='.' && $files[$i]!='..'){
			ftp_chdir($id_ftp, "util_archivos_bin");
			$extensionarchivo=explode(".",$files[$i]);
			if ($extensionarchivo[1]=="txt") {
				$formato  = FTP_ASCII;
			} else {
				$formato  = FTP_BINARY;
			}
			try {$rsid=$db->Execute("select lpad((utilidades.seq_archivos.nextval),8,'0') as secuencia from dual");} 
			catch  (exception $e) 
			{ die($db->ErrorMsg());}
			$rowid=$rsid->FetchNextObject($toupper=true);
			$secuencia_utilidad=$rowid->SECUENCIA;
			$destino = $secuencia_utilidad.'.'.$extensionarchivo[1];
			$archivo = fopen("../upload_ajax/upload/".$carpeta."/".$files[$i], 'rb');
			//echo ('Archivo: '.$archivo.' - Extenxion: '.$extensionarchivo[1].' - Formato: '.$formato.' - Destino: '.$destino);
			if (ftp_fput($id_ftp, $destino, $archivo, $formato)) {} else {die('Error en el envio');}
			try {
				$db->Execute("call utilidades.set_archivo (?,?,?,?,?,?,?,?)",
						  array($secuencia,'lavado_dinero','t_ganador','/directorios/util_archivos_bin',$files[$i],$secuencia_utilidad,
								$extensionarchivo[1],'A'));
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
		}
	}
	
	function deleteDirectory($dir) {
			if (!file_exists($dir)) return true;
			if (!is_dir($dir)) return unlink($dir);
			foreach (scandir($dir) as $item) {
				if ($item == '.' || $item == '..') continue;
				if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
			}
			return rmdir($dir);
	}
		
	deleteDirectory("../upload_ajax/upload/$carpeta");
}
//die('ESTA OPERACION NO SE GRABO... INTENTELO ');
FinalizarTransaccion($db);
header ("location:adm_premio.php");
?>