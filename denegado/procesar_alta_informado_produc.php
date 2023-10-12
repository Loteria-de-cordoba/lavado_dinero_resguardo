<?php session_start();
include("../db_conecta_adodb.inc.php");
$carpeta = session_id();
//print_r($_POST);
//die('entro');
//$fecha = date("d/m/Y");
//$db->debug=true;
$fecha=$_POST['fecha'];
$docu=$_POST['docu'];
$apenom=$_POST['apenom'];
$novedad=$_POST['novedad'];
$sexo=$_POST['sexo'];
//die();
//print_r($_POST);

if(empty($_POST['docu']) || !is_numeric($_POST['docu']))//docu viene vacio o no es numerico
		{
		 
			?>
			<table border="2" align="center">
				<tr>
					<td align="center" style="background-color:#00FF66">
			<?php 
				echo "El documento no existe o no es numerico";
				echo"<br>";
				//$_POST['oculto']=0;
				echo '<a href="#" onClick="ajax_get(\'contenido\',\'denegado/agregar_informado.php\',\'fecha='.$_POST['fecha'].'&docu='.$docu.'&apenom='.$apenom.'&novedad='.$novedad.'&sexo='.$sexo.'\')"> Retornar</a><br>';
				exit();
				?>
				</td>
				</tr>
			</table>
	<?php }
		
		

//CONTROL DE EXISTENCIA DOCUMENTO Y SEXO
//die();
ComenzarTransaccion($db);	
						try {
						$rs_control=$db->Execute("select PLA_AUDITORIA.check_informado(?,?) as control from dual",
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
	echo "Los Datos  Nro de Documento y Sexo ya se Encuentran en la Base de Informados";
	echo"<br>";
	//$_POST['oculto']=0;
	echo '<a href="#" onClick="ajax_get(\'contenido\',\'denegado/agregar_informado.php\',\'fecha='.$_POST['fecha'].'&docu='.$docu.'&apenom='.$apenom.'&novedad='.$novedad.'&sexo='.$sexo.'\')"> Retornar</a><br>';
	exit();
	?>
    </td>
    </tr>
    </table>
<?php }


//die($control.'ooo');


ComenzarTransaccion($db);

						try {
						$db->Execute("insert into PLA_AUDITORIA.informado_uif(
																   DESCRIPCION,
																   DOCUMENTO,
																   FECHA_ALTA,
																   NOVEDAD,
																   USUARIO,
																   sexo
																	)																   
							  values (?,?,to_date(?,'DD/MM/YYYY'),?,?,?)",
							  array($apenom,
									 substr($docu,0,8),
									 $fecha,
									 $novedad,
									 'DU'.$_SESSION['usuario'],
									 $sexo));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
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

	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' INSERTA DOCUMENTACION INFORMADA UIF  en fecha '.$serfecha;
 
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
header ("location:adm_informado.php?fecha=$fecha");
?>