<?php session_start();
//echo "xget";
//print_r($_GET);
//echo "xpost";
//print_r($_POST);
//print_r($_SESSION['permiso']);
//print $_SESSION['area'];
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
//$db->debug=true;
$fecha_cedula = substr($_REQUEST['fecha_cedula'],0,10);
			if($fecha_cedula=='02/02/0002' or $fecha_cedula==0)
				{
					$condicion_fecha_cedula="and b.fecha_cedula is null";
					$cond_ced_vto="and fecha_cedula is null";
				}
			else
				{
					if($fecha_cedula<>'01/01/0001')
						{
							$condicion_fecha_cedula="and b.fecha_cedula=to_date('$fecha_cedula','dd/mm/yyyy')";
							$cond_ced_vto="and fecha_cedula=to_date('$fecha_cedula','dd/mm/yyyy')";
						}
						else
						{
							$fecha_cedula = '';
							$condicion_fecha_cedula="";
							$cond_ced_vto="";
						}
				}
//combo fecha_vto
if($fecha_cedula==0)
{
	$cond_ced_vto='';
}
 if($fecha_cedula<>'01/01/0001')
 {
		try {
		 $rs_fevto = $db ->Execute("
		 							SELECT to_char(to_date('01/01/01','dd/mm/yyyy'),'dd/mm/yyyy') AS codigo,
							  'Todas'       AS descripcion
							FROM lavado_dinero.denegado_eliminado
							--GROUP BY fecha_cedula
							UNION
 							SELECT to_char(ADD_MONTHS(fecha_cedula -1,6),'dd/mm/yyyy') AS codigo,
								  TO_CHAR(ADD_MONTHS(fecha_cedula -1,6),'dd/mm/yyyy') AS descripcion
							FROM lavado_dinero.denegado_eliminado
							WHERE fecha_cedula IS NOT NULL
							$cond_ced_vto
							GROUP BY fecha_cedula
							UNION
							SELECT to_char(to_date('02/02/02','dd/mm/yyyy'),'dd/mm/yyyy') AS codigo,
							  'Sin Fecha'       AS descripcion
							FROM lavado_dinero.denegado_eliminado
							WHERE fecha_vto is null
							--GROUP BY fecha_cedula
														
							--ORDER BY codigo			
							");
					}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
					catch (exception $e)
					{
					die ($db->ErrorMsg()); 
					}
	}
	else
	{
				try {
		 	$rs_fevto = $db ->Execute("SELECT to_char(to_date('01/01/01','dd/mm/yyyy'),'dd/mm/yyyy') AS codigo,
									  'Todas'       AS descripcion
									FROM lavado_dinero.denegado
									WHERE ROWNUM<2
							");
					}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
					catch (exception $e)
					{
					die ($db->ErrorMsg()); 
					}
			
	}
$fecha_vto= $rs_fevto->DESCRIPCION;
armar_combo($rs_fevto,'fecha_vto',$fecha_vto);
?>
