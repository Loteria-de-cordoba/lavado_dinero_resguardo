<?php include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php");
//$DB->debug = true;
//print_r($_REQUEST);
//echo urldecode(unserialize($_REQUEST['ids_de_empleados_listados']));
//echo $_REQUEST['xxx'][0];
$lista=$_REQUEST['aplica'];
$todos=$_REQUEST['apostador'];

for ($h=0;$h<count($lista); $h++)
{
	//echo $lista[$h]."<br/>";

				if($lista[$h]==1)
				{
					echo "alerta"."<br/>";//por cada entrada se debe procesar
				}
				if($lista[$h]==2)
				{
					echo "auditoria"."<br/>";
				}
				if($lista[$h]==3)
				{
					echo "cedula"."<br/>";
				}
				if($lista[$h]==4)
				{
					echo "fichaje"."<br/>";
				}
				if($lista[$h]==5)
				{
					echo "ganador"."<br/>";
				}
				if($lista[$h]==6)
				{
					echo "imagenes"."<br/>";
				}
				if($lista[$h]==7)
				{
					echo "sujeto informado"."<br/>";
				}
}
//control 2
/*if($todos<>0)//no son todos los seleccionados
{
	switch ($lista)
	 {
		case 1:
			echo "alerta";
			break;
		case 2:
			echo "auditoria";
			break;
		case 3:
			echo "cedula";
			break;
	}
}*/
die('ENTRE');
$_pagi_pg  = $_REQUEST['_pagi_pg'];

	//die();

if (isset($_POST['descripcion']) && $_POST['descripcion']!="")
 	{
			$descripcion = $_POST['descripcion'];
			
			//obtengo idempleado
			try {
			$rs_id = $DB->Execute("select id_empleado as emple
									from rrhh.t_empleado
									where legajo = $descripcion");
			}
			catch  (exception $e)
			{
			die($DB->ErrorMsg());
			}
		
			$ro_per_hoy=$rs_id->FetchNextObject($toupper=true);	
			$idid = $ro_per_hoy->EMPLE;
			
			
					$condicion_descripcion = "and a.id_empleado = $idid";
	}
	else
	{
		$idid=$_REQUEST['id_empleado'];
		$condicion_descripcion = "and a.id_empleado = $idid";
	} 

if (isset($_POST['periodo']) && $_POST['periodo']!="" && isset($_POST['mes']) && $_POST['mes']!="")
 	{
			$periodo = $_POST['periodo'];
			$mes= $_POST['mes'];
					$condicion_periodo = "and a.mes = $mes and a.periodo=$periodo";
	} 



if (isset($_POST['id_planta']) && $_POST['id_planta']!='') 
		{
			$id_planta = $_POST['id_planta'];
				$condicion_planta = "and a.id_planta = $id_planta";
			$condicion_planta0 = "where a.id_planta = $id_planta";
		} 
		else
		{
			
			$condicion_planta='';
		}
		

//echo 'idid'.$idid.'periodo'.$periodo.'id_planta'.$id_planta;
//die();		

//recorro lista y asigno
for ($h=0;$h<count($lista);$h++)
			{
			//eliminar si ya tiene items
			//cambiar aplica de N a S
			try {
				$rs = $DB->Execute("update  rrhh.t_acum_impuesto a
												set aplica='S'
										 where  a.id_empleado=?
										 		$condicion_planta
												$condicion_periodo
												", array($lista[$h]));
				}
				catch  (exception $e)
				{
				die($DB->ErrorMsg());
				}
	
	
//die();
			//insertar el item solo para los seleccionados
			//primero para los negativos a.retencion<0
						try {
							$DB->Execute("insert into rrhh.t_item_detalle 
											(id_item_cabecera, id_empleado, id_planta,  importe, periodo, periodo_sac)
												  select (case 
												  when (a.id_planta = 1)
												  then
												  (1083)
												  when (a.id_planta = 2)
												  then
												  (3907)
												  when (a.id_planta = 3)
												  then(3908)
												  end) as id_item_cabecera, a.id_empleado, 
												  a.id_planta, a.retencion_actual, lpad(a.mes,2,'0')||a.periodo, lpad(a.mes,2,'0')||a.periodo
												  from rrhh.t_acum_impuesto a , rrhh.t_empleado c
												  where a.id_empleado = c.id_empleado 
												  and c.activo = 'S'
												  and c.genera_haber = 'S'    
												  and  a.retencion_actual < 0
												$condicion_planta
												$condicion_periodo
												 and a.id_empleado=?", array($lista[$h]));
							}
							catch  (exception $e)
							{ 
							die($DB->ErrorMsg());
							}
							
		//insertar el item solo para los seleccionados
		//primero para los negativos a.retencion>0
		
		try {
				$DB->Execute("insert into rrhh.t_item_detalle 
								(id_item_cabecera, id_empleado, id_planta,  importe, periodo, periodo_sac)
									  select (case 
									  when (a.id_planta = 1)
									  then
									  (14)
									  when (a.id_planta = 2)
									  then
									  (1455)
									  when (a.id_planta = 3)
									  then(804)
									  end) as id_item_cabecera, a.id_empleado, 
									  a.id_planta, a.retencion_actual, trim(lpad(a.mes,2,'0')||a.periodo), trim(lpad(a.mes,2,'0')||a.periodo)
									  from rrhh.t_acum_impuesto a , rrhh.t_empleado c
									  where a.id_empleado = c.id_empleado 
									  and c.activo = 'S'
									  and c.genera_haber = 'S'    
									  and  a.retencion_actual > 0
									 $condicion_planta
									 $condicion_periodo
									 and a.id_empleado=?", array($lista[$h]));
				}
				catch  (exception $e)
				{ 
				die($DB->ErrorMsg());
				}
			
}//FIN DEL FOR
//die();	
?>
<table border="2" align="center" cellspacing="1">
					  <tbody>
						
						 <tr>
							
							<td align="left">
							   <table border="0" cellpadding="0" cellspacing="0">
								  <tbody>
									 <tr>
										<td align="left"><span class="Estilo2">
										  
										  <?php 
										  
										  if(count($lista)<>0)
										  {
										  	echo count($lista).' Asignaciones Efectivas';
										  }
										  else
										  {
										  	echo 'No Selecciono Items - No existen Asignaciones';
										  }?> 
                                           </span></td>
									</tr>
								  </tbody>
							</table></td>
						 </tr>
                         <tr>
     <?php if($descripcion<>'' or (count($lista)<>0 and $descripcion==''))
	 {?> 
    		<td align="center" class="tdRojo">Regresar<img src="image/return_v.png" title="Ver Informe" width="24" height="24" border="0" onClick="ajax_get('contenido','calculo/informe_impuesto_ganancias.php','planta=<?php echo $id_planta;?>&periodo=<?php echo $periodo;?>&mes=<?php echo $mes;?>&legajo=<?php echo $descripcion;?>&_pagi_pg=<?php echo $_pagi_pg;?>')"/></td>
    <?php 
	}
    else
	{
	?>
    		<td align="center" class="tdRojo">Regresar<img src="image/return_v.png" title="Ver Informe" width="24" height="24" border="0" onClick="ajax_get('contenido','calculo/informe_impuesto_ganancias_detalle.php','planta=<?php echo $id_planta;?>&periodo=<?php echo $periodo;?>&id_empleado=<?php echo $idid;?>&mes=<?php echo $mes;?>&_pagi_pg=<?php echo $_pagi_pg;?>')"/></td>
    <?php }?>
    </tr>
					  </tbody>
				  </table>
<?php
//header("location:consulta_calculo_menos.php");
?>