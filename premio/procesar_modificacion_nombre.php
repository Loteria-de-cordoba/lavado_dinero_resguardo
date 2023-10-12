<?php session_start();
include("../db_conecta_adodb.inc.php");
//$carpeta = session_id();

//$db->debug=true;
/*echo "<br>";
echo "<br>";
echo "<br>";
print_r($_POST);*/
//die('entre a procesar_modificacion_nombre imagen');
ComenzarTransaccion($db);
//print_r($_POST);
//die();




//$fdesde=$_POST['fdesde'];
//$fhasta=$_POST['fhasta'];
$nombre=$_POST['nombre'].'.'.$_POST['extension'];
//echo 'nombre '.$nombre;
 //die();
		try {
			$db->Execute("update PLA_AUDITORIA.ARCHIVOS_PLA
							set nombre_real = ?
							where esquema='lavado_dinero' 
						  		and tabla='t_ganador'
						  		and id_tabla=?
								and id_archivos=?", array($nombre,$_POST['idganador'],$_POST['id_archivo']));
			}
	
		catch  (exception $e) 
			{ 
			die($db->ErrorMsg());
			}
	
		

	
	$fdesde=$_POST['fecha'];
	$fhasta=$_POST['fhasta'];
	$conformado=$_POST['conformado'];
	$suc_ban=$_POST['suc_ban'];
	//die();
	FinalizarTransaccion($db); 

while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;

 $_SESSION['bandera']=1;
 if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA')
 	{
 	if($_POST['condicion']==0)//viene de una imagen comun
		{
			//die('paso');
 			header("location:adm_premio_administra.php?fdesde=$fdesde&fhasta=$fhasta&conformado=$conformado&suc_ban=$suc_ban"); 
		}
		else
		{
			header("location:lista_planillas.php"); 
		}
	}
    else { 	
			header ("location:adm_premio.php?fdesde=$fdesde&fhasta=$fhasta&conformado=$conformado&suc_ban=$suc_ban");
	}
}?>



