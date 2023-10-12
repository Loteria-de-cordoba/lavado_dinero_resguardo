<?php session_start();
//print_r($_GET);
include_once("../jscalendar-1.0/calendario.php");
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
$array_fecha = FechaServer();	
//print_r($_GET);
//print_r($_POST);
//print_r($_SESSION);
//$db->debug=true;

//obtengo datos para auditoria
//obtengo fecha y hora
		try {
		$rs_auditor = $db ->Execute("select to_char(sysdate,'hh24:mi:ss') as hora, to_char(sysdate,'dd/mm/yyyy') as fecha from dual");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_auditor =$rs_auditor->FetchNextObject($toupper=true);
	$serhora=$row_auditor->HORA;
	$serfecha=$row_auditor->FECHA;
	//obtengo usuario
	$serusuario='DU'.$_SESSION['usuario'];
 //obtengo el nombre del usuario
 try {
 $rs_uu = $db ->Execute("SELECT us.descripcion as uu FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_uu =$rs_uu->FetchNextObject($toupper=true);
			$auditado=$row_uu->UU; 

//si es primera vez que entro en esta sesion
if($_SESSION['ganador']==0)
{
//EJERZO AUDITORIA
	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' Ingresa a Pantalla de  Ganadores ';
 
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
									$serusuario,
									$describa));
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
				FinalizarTransaccion($db);
	//echo $serfecha.$describa;
	$_SESSION['ganador']=1;
}



if (isset($_GET['fecha'])) {
	$fecha = $_GET['fecha'];
	} else {
		if (isset($_POST['fecha'])) {
			$fecha = $_POST['fecha'];
			} else {
				$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					//'01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
				}
		}	

if (isset($_GET['fhasta'])) {
	$fhasta = $_GET['fhasta'];
	//echo 'Hasta: '.$fhasta;
	} else {
		if (isset($_POST['fhasta'])) {
			$fhasta = $_POST['fhasta'];
			} else {
				$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
				}
		}

try {$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion 
	from juegos.sucursal 
	where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,62,63,64,65,66,67,69,73,79,80,81) order by codigo");	}
	catch (exception $e) {die ($db->ErrorMsg()); } 	
	 
if (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";
	} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==2) {
		$suc_ban = $_GET['suc_ban'];
		$condicion_sucursal = "and b.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33)";
		} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==3) {
			$suc_ban = $_GET['suc_ban'];
			$condicion_sucursal = "and b.suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81)";
			} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
				} else if (isset($_POST['suc_ban']) && $_POST['suc_ban']==2) {
					$suc_ban = $_POST['suc_ban'];
					$condicion_sucursal = "and b.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33)";
					} else if (isset($_POST['suc_ban']) && $_POST['suc_ban']==3) {
						$suc_ban = $_POST['suc_ban'];
						$condicion_sucursal = "and b.suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81)";
						} else if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
							$suc_ban = $_POST['suc_ban'];
							$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
							} else {
								$suc_ban =0;
								$condicion_sucursal = "";
								}
//echo $suc_ban;		
// print_r($_SESSION);
/*if (isset($_GET['conformado'])) {
$conformado = $_GET['conformado'];
$condicion_conforma='and conformado = 1';
} else {
		} else {
				$conformado =''; 
							 
		}
}	 */
 if (isset($_POST['conformado'])&& $_POST['conformado']==0 ) {
			$conformado = $_POST['conformado'];
			$condicion_conforma="and a.conformado ='$conformado'";
}elseif (isset($_POST['conformado'])&& $_POST['conformado']==1 ) {
				$conformado = $_POST['conformado'];
				$condicion_conforma="and a.conformado ='$conformado'";
} elseif (isset($_GET['conformado'])&& $_GET['conformado']==0 ) {
			$conformado = $_GET['conformado'];
			$condicion_conforma="and a.conformado ='$conformado'";
} elseif (isset($_GET['conformado'])&& $_GET['conformado']==1 ) {
				$conformado = $_GET['conformado'];
				$condicion_conforma="and a.conformado ='$conformado'";
}else {
				$conformado = 1;
				$condicion_conforma="and a.conformado ='$conformado'";
}


if (isset($_GET['mayores'])) {
	$mayores = $_GET['mayores'];
	if($mayores ==1){
		$condicion_mayores="and a.valor_premio>50000";
	}
	
	} else {
		if (isset($_POST['mayores'])) {
			$mayores = $_POST['mayores'];
			$mayores =1;
			$condicion_mayores="and a.valor_premio>50000";
			} else {
				$mayores = 0;
				$condicion_mayores="";
				}
		}	

if (isset($_GET['ganador'])) {
	$ganador = $_GET['ganador'];
	$condicion_ganador="and a.id_ganador=$ganador";
	} else {
		if (isset($_POST['ganador'])&& ($_POST['ganador']!="")) {
		
			$ganador = $_POST['ganador'];
			$condicion_ganador="and a.id_ganador=$ganador";
			} else {
				
				$condicion_ganador="";
				}
		}	


//***AGREGO

if(isset($_POST['suc_ban']))
{
//EJERZO AUDITORIA
	
	if($_POST['suc_ban']<>0 and $_POST['suc_ban']<>2 and $_POST['suc_ban']<>3)
	{
	try {
			$rs_casino_auditoria = $db ->Execute("select  nombre as descripcion from juegos.sucursal
												where  suc_ban=?",array($_POST['suc_ban']));
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_casino_auditoria =$rs_casino_auditoria->FetchNextObject($toupper=true);
	$cas_audita=$row_casino_auditoria->DESCRIPCION;
	}
	else
	{
				if($_POST['suc_ban']==0)
				{
					$cas_audita=' Todos los Casinos y Delegaciones';
				}
				else
					{
						if($_POST['suc_ban']==2)
								{
									$cas_audita='Todos las Delegaciones';
								}
						else
								{
									$cas_audita='Todos los Casinos';
								}
					}
					
	}

if($conformado==0)
{
	if($mayores==0)
	{
	 	$describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' consulta  mov. no conformados de Ganadores de '.$cas_audita.' entre fechas '.$_POST['fecha'].' y '.$_POST['fhasta'];
	 }
	 else
	 {
	 	$describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.'  consulta  mov. no conformados Mayores a 50000$ de Ganadores de '.$cas_audita.' entre fechas '.$_POST['fecha'].' y '.$_POST['fhasta'];
		 }
}
else
{
	if($mayores==0)
	{
	 	$describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' consulta  mov. Conformados de Ganadores de '.$cas_audita.' entre fechas '.$_POST['fecha'].' y '.$_POST['fhasta'];
	 }
	 else
	 {
	 	$describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.'  consulta  mov. Conformados Mayores a 50000$ de Ganadores de '.$cas_audita.' entre fechas '.$_POST['fecha'].' y '.$_POST['fhasta'];
		 }
}
 
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
									$serusuario,
									$describa));
				}
				catch  (exception $e) 
				{ 
				die($db->ErrorMsg());
				}
				FinalizarTransaccion($db);
	//echo $serfecha.$describa;
	
}		

//***FIN AGREGO


//echo 'mayores='.$mayores;
									
//echo($conformado);	
			 
			/* try {
				$rs_totales = $db -> Execute("select sum(valor_premio) as importe
							from lavado_dinero.t_ganador a, juegos.sucursal b
							where a.suc_ban = b.suc_ban 
						 ");
				}
				catch (exception $e)
				{
				die ($db->ErrorMsg()); 
				} 
			$row_totales = $rs_totales->FetchNextObject($toupper=true); */
			$totales=0;
 //$db->debug=true;
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<!--<form action="" method="post" enctype="multipart/form-data" name="formulario" id="formulario">-->

<?php 
//$db->debug=true;
// sacar la fecha!!
//$fecha='1/08/2011';
//$fhasta='31/08/2011';

 $_pagi_sql ="select rownum,a.id_ganador, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha, upper(a.nombre) nombre , observacion, a.politico, 
 			upper(a.apellido) apellido,  initcap(b.nombre)  as casa,valor_premio, c.juegos, b.nombre as sucursal, 
			upper(d.descripcion) usuario, upper(e.descripcion) usuario_conforma,a.ddjj,
			(select count(*) 
				from utilidades.t_archivos x 
				where x.esquema='lavado_dinero' and x.tabla='t_ganador' and x.id_tabla = a.id_ganador) as imagenes,
			a.xml
      		from lavado_dinero.t_ganador a, juegos.sucursal b, superusuario.usuarios d, juegos.juegos c,superusuario.usuarios e
			where a.suc_ban = b.suc_ban(+) 
			and a.juego = c.id_juegos
			and a.usuario = d.id_usuario
			and a.usuario_conforma= e.ID_usuario(+)
			and a.fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
			and a.fecha_baja is null
			$condicion_sucursal
			$condicion_conforma
			$condicion_mayores
			$condicion_ganador
			order by a.fecha_alta desc, b.nombre";
 
$_pagi_div = "contenido";




//$_pagi_enlace = "premio/premios_conformados.php";
$_pagi_cuantos = 500; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='conformado'; 
$_pagi_propagar[2]='fecha'; 
$_pagi_propagar[3]='fhasta'; 
$_pagi_propagar[4]='mayores'; 


/*$_pagi_propagar[4]='apellido'; 
$_pagi_propagar[5]='nombre'; 
$_pagi_propagar[6]='valor_premio';
$_pagi_propagar[7]='id_ganador'; 
*/ 
 include("../paginator_adodb_oracle.inc.php");
$_SESSION['nro_pagina']=$_pagi_actual ;

?>
<style type="text/css">
<!--
.Estilo1 {color: #000000}
-->
</style>
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','premio/adm_premio_administra.php',this); return false;">
<br />
  
<table align="center">
<tr valign="middle"  >
<td align="left"  scope="col">
       		<table border="0" cellspacing="0">
           		<tr>
                  <td class="small">Nro. Ganador</td>
	              <td class="small">Delegacion</td>
	                 <td class="small">B&uacute;squeda</td>
           		</tr>
	            <tr valign="middle">
				<td><input name="ganador" id="ganador" type="text" size="6" value="<?php echo $ganador ?>"></td>
             		<td><?php armar_combo_todos_lavado($rs_sucursal,"suc_ban",$suc_ban);?></td>
	                <td><select name="conformado" class="small" id="conformado">
                      <option value="1" <?php if ($conformado==1) echo 'selected';?>>conformado</option>
                      <option value="0" <?php if ($conformado==0) echo 'selected';?>>no conformado</option>
                    </select></td>
               </tr>
         </table>      </td>
      <td   scope="col">
	  <table border="0" cellspacing="0">
          <tr>
            <td class="small">Fecha desde</td>
          </tr>
          <tr>
            <td><?php  abrir_calendario('fecha','premio', $fecha); ?></td>
          </tr>
              </table></td>
   	  <td scope="col">
	  <table border="0" cellspacing="0">
          <tr>
            <td class="small">Fecha hasta</td>
          </tr>
          <tr>
            <td><?php  abrir_calendario('fhasta','premio', $fhasta); ?></td>
          </tr>
        </table>
		
		</td>
		<td align="center" valign="bottom" scope="col" class="small">Mayores a $50000<input name="mayores" id="mayores" type="checkbox" <?php if($mayores==1){ $checked='checked=checked';} else { $checked='';}?> <?php echo $checked ?>/></td>
      <td align="center" valign="bottom" scope="col">
		
		<img src="image/xmag.gif" alt="Buscar" width="16" height="16" border="0" />
	    <input type="submit" class="small" value="Buscar" /></td>
    </tr>
</table>
</form>
<p>
  <?php if ($_pagi_result->RowCount()==0) {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">SIN MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','premio/adm_premio_administra.php','')\">Regresar</a></div>");
}
?>
</p>
<form id="detalle_premio" name="detalle_premio" action="#" onsubmit="ajax_post('contenido','premio/xml_generar_cambio_de_fichas.php',this); return false;">
<table width="100%" border="0" align="center">
	<tr>
    	<td colspan="14" align="center" valign="bottom" class="texto4" scope="col">
        	<a href="#" onclick="ajax_get('contenido','premio/lista_planillas.php','')">
            Descargar Planillas <img src="image/jamembo-jumpto.png" alt="Descargar Planillas" width="28" height="28" border="0" /></a>
		</td>
	</tr>
	<tr>
        <td width="1%" align="right"> <a href="#" onclick="window.open('list/personas_politicas.php?conformado=<?php echo $conformado; ?>&fecha=<?php echo $fecha;?>&fhasta=<?php echo $fhasta;?>&suc_ban=<?php echo $suc_ban;?>&mayores=<?php echo $mayores;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
        <td colspan="12" align="center" valign="bottom" scope="col" class="texto4">Datos Personales de Premios</td>
        <td width="1%" align="right"> <a href="#" onclick="window.open('list/premios_pagados.php?conformado=<?php echo $conformado; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
    </tr>
    <tr>          
        <td colspan="8" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
        <td colspan="5" align="center" valign="bottom" class="smallRojo" scope="col">
        	<a href="#" onclick="xml_marcar('detalle_premio',1);">
        	xml Marcar Todos 
			</a>
            &nbsp;/&nbsp;
            <a href="#" onclick="xml_marcar('detalle_premio',0);">
            xml Desmarcar Todos
            </a>
        </td>
        <td align="center" valign="bottom" class="smallRojo" scope="col">
            <input type="submit" class="small" value="Generar xml" />
        </td>
    </tr>   
	<tr align="center" class="td2">
    	
		<td width="6%" class="td4" scope="col">Fecha</td>
        <td width="12%" class="td4" scope="col">Sucursal</td>
        <td width="19%" class="td4" scope="col">Ganador</td>
        <td width="8%" class="td4" scope="col">Juego</td>
        <td width="8%" class="td4" scope="col">Importe</td>
        <td width="9%" class="td4" scope="col">Im&aacute;genes</td>
        <td width="18%" class="td4" scope="col">Usuario Carga</td>
        <td width="18%" class="td4" scope="col">Usuario Conforma</td>
        <td width="8%" class="td4" scope="col">Datos Ganador</td>
        <td width="12%" class="td4" scope="col">Observación</td>
        <td width="12%" class="td4" scope="col">Modificar</td>
        <!--<td width="12%" class="td4" scope="col">PEP'S</td>-->
        <td width="12%" class="td4" scope="col">Eliminar</td>
        <td scope="col">Estado<br />xml</td>
        <td scope="col">Generar<br />xml</td>
    </tr>
	<?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
		<tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
			
			<td align="center"><?php echo $row->FECHA;?></td>
           	<td align="left"><?php echo $row->SUCURSAL;?></td>
           	<td align="left"><?php echo utf8_decode($row->APELLIDO).' '.utf8_decode($row->NOMBRE);?></td>
         	<td align="left" ><?php echo $row->JUEGOS; $totales=$totales+$row->VALOR_PREMIO?></td>
           	<td align="right" ><?php echo number_format($row->VALOR_PREMIO,2,',','.'); $totales=$totales+$row->VALOR_PREMIO?></td>
           	<td align="center" >
				<a href="#" onclick="ajax_showTooltip('premio/mostrar_imagen.php?jsfecha='+new Date()+'&id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>',this); return false;" >
                <img src="image/download.png" alt="ver archivos"  width="20" height="20" border="0"/> 
                </a>
			</td>
	       	<td align="left" ><?php echo $row->USUARIO;?></td>
           	<td align="left" ><?php echo $row->USUARIO_CONFORMA;?></td>
	       	<td align="center" ><a href="#" onclick="window.open('list/datos_ganadores.php?id_ganador=<?php echo $row->ID_GANADOR ?>&delegacion=<?php echo utf8_decode($row->SUCURSAL)?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Adobe Reader 7.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
			<td align="center" >
		 	<?php  if ($row->OBSERVACION==0){?>
         		<a href="#" onclick="ajax_get('contenido','premio/procesar_datos.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=1&conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/SyncCenter.png" alt="Datos Completos" width="24" height="23" border="0" />
                </a>
          		<?php } else {?>
		  		<a href="#" onclick="ajax_get('contenido','premio/procesar_datos.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=0&conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/Error.png" alt="Datos Incompletos" width="24" height="23" border="0" /></a>
		  
		   <?php  }?>
           <a href="#" onclick="ajax_get('contenido','premio/nueva_nota_observacion.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>')"><img src="image/app_48.png" alt="Añadir Nota" width="24" height="23" border="0" /></a>   
           </td>
           <td align="center" ><a href="#" onclick="ajax_get('contenido','premio/modificar_premio2.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&casa=<?php echo $suc_ban; ?>&mayores=<?php echo $mayores; ?>'); return false;" ><img src="image/modificar.png" alt="modificar datos"  width="20" height="20" border="0"/></a></td>
  			<!--<td align="center" ><?php if($row->POLITICO=="SI"){?>
        <a href="#" onclick="window.open('list/peps.php?id_ganador=<?php echo $row->ID_GANADOR ?>&delegacion=<?php echo utf8_decode($row->CASA);?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')" ><img src="image/Adobe Reader 7.png" alt="modificar datos"  width="20" height="20" border="0"/></a>
        <?php } else {?> NO <?php  }?>  </td>-->
            <td align="center" ><a href="#" onclick="confirmar_eliminar_ganador('contenido','premio/procesar_eliminar_ganador.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&casa=<?php echo $suc_ban; ?>','')" ><img src="image/Trash-Empty.png" alt="Eliminar Ganador"  width="20" height="20" border="0"/></a></td>
            <td align="center">
            	<?php if ($row->XML == 1) {
                	echo "<img src='image/si.png' alt='Generado'  width='20' height='20' border='0'/>";
                    } else {
                    	echo "<img src='image/no.png' alt='Sin Generar'  width='20' height='20' border='0'/>";
                    	} ?>
            </td>
        	<td align="center">
            	<input name="xml_<?php echo $row->ID_GANADOR ?>" id="xml_<?php echo $row->ID_GANADOR ?>" type="checkbox" value="<?php echo $row->ID_GANADOR ?>"/>
            </td>    
		</tr>
        
		<?php  }?>
		 <tr>
          
          <td colspan="13" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
  </table>
</form>
<?php $_SESSION['sqlreporte']= $_pagi_sql;?>
<?php $_SESSION['bandera']= 0;?>
