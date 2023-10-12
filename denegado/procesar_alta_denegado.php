<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
//print_r($_POST);
//die('entro');
//$fecha = date("d/m/Y");
//$db->debug=true;
$fecha=$_POST['fecha'];
$primer=$_POST['primedig'];
$docu=$_POST['docu'];
$ultimo=$_POST['ultdig'];
$apenom=$_POST['apenom'];
$novedad=$_POST['novedad'];
		if(!empty($_POST['primedig']) && !empty($_POST['ultdig']))
		{
			$cuit= substr($primer,0,2).substr($docu,0,8).substr($ultimo,0,1);
		}
		else
		{
			$cuit='';
		}
$sexo=$_POST['sexo'];
$fechacedula=substr($_POST['fechacedula'],0,10);
$fecha_cedula=$_REQUEST['fecha_cedula'];
$fecha_vto=$_REQUEST['fecha_vto'];

//CONTROLO SI VIENE VTO
	if(isset($_POST['vto']) and $fechacedula<>'')
		{
			$vto='S';
		}
	else
		{
			$vto='N';
		}
//echo substr($primer,0,2).substr($docu,0,8).substr($ultimo,0,1);
//die();
//echo $ultimo."....";
//die();
//print_r($_POST);

if(!empty($_POST['primedig']) && !empty($_POST['ultdig']))//viene por cuil
		{
		 if(empty($_POST['primedig']) || !is_numeric($_POST['primedig']) || empty($_POST['docu']) || !is_numeric($_POST['docu']) || (empty($_POST['ultdig']) and $ultimo<>0) || (!is_numeric($ultimo) and $ultimo<>0))
			{
			?>
			<table border="2" align="center">
				<tr>
					<td align="center" style="background-color:#00FF66">
			<?php 
				echo "Algunos de los componentes del Cuit se hallan vacios o no son numericos";
				echo"<br>";
				//$_POST['oculto']=0;
				echo '<a href="#" onClick="ajax_get(\'contenido\',\'denegado/agregar_denegado.php\',\'fecha='.$_POST['fecha'].'&primedig='.$primer.'&docu='.$docu.'&ultdig='.$ultimo.'&apenom='.$apenom.'&novedad='.$novedad.'&sexo='.$sexo.'\')"> Retornar</a><br>';
				exit();
				?>
				</td>
				</tr>
			</table>
			<?php }
		}
		else//viene por documento
		{
			if(empty($_POST['docu']) || !is_numeric($_POST['docu']))
				{ 
			?>
			<table border="2" align="center">
				<tr>
					<td align="center" style="background-color:#00FF66">
			<?php 
				echo "El Nro de Documento se halla vacio o no es  numerico";
				echo"<br>";
				//$_POST['oculto']=0;
				echo '<a href="#" onClick="ajax_get(\'contenido\',\'denegado/agregar_denegado.php\',\'fecha='.$_POST['fecha'].'&primedig='.$primer.'&docu='.$docu.'&ultdig='.$ultimo.'&apenom='.$apenom.'&novedad='.$novedad.'&sexo='.$sexo.'\')"> Retornar</a><br>';
				exit();
				?>
				</td>
				</tr>
			</table>
			<?php }
		}//fin viene por documento
		

//CONTROL DE EXISTENCIA DOCUMENTO Y SEXO
//die();
ComenzarTransaccion($db);	
						try {
						$rs_control=$db->Execute("select PLA_AUDITORIA.check_denegado(PLA_AUDITORIA.md5(?),?) as control from dual",
							  array( substr($docu,0,8),
									 $sexo));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					
		$row_control =$rs_control->FetchNextObject($toupper=true);
		$control=$row_control->CONTROL;
					
FinalizarTransaccion($db);

if($control<>0)
{
?>
<table border="2" align="center">
	<tr>
    	<td align="center" style="background-color:#00FF66">
<?php 
	echo "Los Datos  Nro de Documento y Sexo ya se Encuentran en la Base de Cedulas";
	echo"<br>";
	//$_POST['oculto']=0;
	echo '<a href="#" onClick="ajax_get(\'contenido\',\'denegado/agregar_denegado.php\',\'fecha='.$_POST['fecha'].'&primedig='.$primer.'&docu='.$docu.'&ultdig='.$ultimo.'&apenom='.$apenom.'&novedad='.$novedad.'&sexo='.$sexo.'\')"> Retornar</a><br>';
	exit();
	?>
    </td>
    </tr>
    </table>
<?php }

if($cuit<>'')
{
//CONTROL DE EXISTENCIA CUIT

ComenzarTransaccion($db);	
						try {
						$rs_controlcuit=$db->Execute("select PLA_AUDITORIA.check_CUIT(PLA_AUDITORIA.md5(?)) as controcuit from dual",
							  array( substr($cuit,0,11)));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					
		$row_controlcuit =$rs_controlcuit->FetchNextObject($toupper=true);
		$controcuit=$row_controlcuit->CONTROCUIT;
					
FinalizarTransaccion($db);

if($controcuit<>0)
{
?>
<table border="2" align="center">
	<tr>
    	<td align="center" style="background-color:#00FF66">
<?php 
	echo "Este C.U.I.T ya se Encuentra en la Base de Cedulas";
	echo"<br>";
	//$_POST['oculto']=0;
	echo '<a href="#" onClick="ajax_get(\'contenido\',\'denegado/agregar_denegado.php\',\'fecha='.$_POST['fecha'].'&primedig='.$primer.'&docu='.$docu.'&ultdig='.$ultimo.'&apenom='.$apenom.'&novedad='.$novedad.'&sexo='.$sexo.'\')"> Retornar</a><br>';
	exit();
	?>
    </td>
    </tr>
    </table>
<?php }
}//fin control de cuit
//die($cuit.'ooo');


ComenzarTransaccion($db);
if($cuit<>'')
{	
						try {
						$db->Execute("insert into PLA_AUDITORIA.DENEGADO(
																   DESCRIPCION,
																   CUIT,
																   DOCUMENTO,
																   FECHA_ALTA,
																   NOVEDAD,
																   USUARIO,
																   sexo,
																   fecha_cedula,
																   vto
																	)																   
							  values (?,PLA_AUDITORIA.MD5(?),PLA_AUDITORIA.MD5(?),to_date(?,'DD/MM/YYYY'),?,?,?,to_date(?,'DD/MM/YYYY'),?)",
							  array($apenom,
									$cuit,
									 substr($docu,0,8),
									 $fecha,
									 $novedad,
									 'DU'.$_SESSION['usuario'],
									 $sexo,
									 $fechacedula,
									 $vto));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
}
else
{//cuit solo documento
				try {
						$db->Execute("insert into PLA_AUDITORIA.DENEGADO(
																   DESCRIPCION,
																   CUIT,
																   DOCUMENTO,
																   FECHA_ALTA,
																   NOVEDAD,
																   USUARIO,
																   sexo,
																   fecha_cedula,
																   vto
																	)																   
							  values (?,?,PLA_AUDITORIA.MD5(?),to_date(?,'DD/MM/YYYY'),?,?,?,to_date(?,'DD/MM/YYYY'),?)",
							  array($apenom,
									$cuit,
									 substr($docu,0,8),
									 $fecha,
									 $novedad,
									 'DU'.$_SESSION['usuario'],
									 $sexo,
									 $fechacedula,
									 $vto));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
}
					
FinalizarTransaccion($db);
			

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

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' INSERTA movimientos Cedula UIF  en fecha '.$serfecha;
 
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
//die();
header ("location:adm_denegado.php?fecha=$fecha&fecha_cedula=$fecha_cedula&fecha_vto=$fecha_vto");
?>