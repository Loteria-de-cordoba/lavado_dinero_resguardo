<?php  	session_start();
//print_r($_GET);

include_once("../jscalendar-1.0/calendario.php");
include_once("../db_conecta_adodb.inc.php");
include_once("../funcion.inc.php");
$array_fecha = FechaServer();	
//print_r($_GET);
//print_r($_SESSION);
//$db->debug=true;
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

 
 
		try {
			$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal  where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,62,63,64,65,66,67,69,73,79,80,81) order by nombre");
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 	
	 
if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";
} else if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
			$suc_ban = $_POST['suc_ban'];
			$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else {
	$suc_ban = $_GET['suc_ban'];
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
									
//echo($conformado);	
			 
			/* try {
				$rs_totales = $db -> Execute("select sum(valor_premio) as importe
							from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
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
//$fecha='1/10/2010';
//$fhasta='31/10/2010';

 $_pagi_sql ="select gdor.id_ganador, td.descripcion, gdor.documento, gdor.apellido, gdor.nombre, gdor.nacionalidad, gdor.cuit,
						gdor.domicilio, lo.n_localidad, pro.n_provincia, pa.n_pais, gdor.profesion, gdor.cod_postal,
						td.descripcion, gdor.documento2, gdor.apellido2, gdor.nombre2, gdor.fecha_alta, gdor.nro_premio
						from PLA_AUDITORIA.t_ganador  gdor, PLA_AUDITORIA.t_moneda mo, juegos.juegos jue, 
						PLA_AUDITORIA.t_tipo_pago pago, PLA_AUDITORIA.t_tipo_documento td, utilidades.t_localidades lo,
						utilidades.t_provincias pro, utilidades.t_paises pa
						where gdor.id_moneda=mo.id_moneda
						and gdor.juego= jue.id_juegos
						and gdor.id_tipo_pago= pago.id_tipo_pago
						and gdor.id_tipo_documento=td.id_tipo_documento
						and gdor.fecha_alta between to_date($fecha,'DD/MM/YYYY HH24:MI:SS') and to_date($fhasta,'DD/MM/YYYY HH24:MI:SS')
						and gdor.id_localidad= lo.id_localidad(+)
						and lo.id_provincia= pro.id_provincia(+)
						and pro.id_pais=pa.id_pais(+)
						order by gdor.nro_premio, gdor.fecha_alta";
 
$_pagi_div = "contenido";




//$_pagi_enlace = "premio/premios_conformados.php";
$_pagi_cuantos = 15; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=3;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='conformado'; 
$_pagi_propagar[2]='fecha'; 
$_pagi_propagar[3]='fhasta'; 


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
                
	              <td class="small">Delegacion</td>
	                 <td class="small">B&uacute;squeda</td>
           		</tr>
	            <tr valign="middle">
             		<td><?php armar_combo_todos($rs_sucursal,"suc_ban",$suc_ban);?></td>
	                <td><select name="conformado" class="small" id="conformado">
                      <option value="1" <?php if ($conformado==1) echo 'selected';?>>conformado</option>
                      <option value="0" <?php if ($conformado==0) echo 'selected';?>>no conformado</option>
                    </select></td>
               </tr>
         </table>      </td>
      <td   scope="col"><table border="0" cellspacing="0">
          <tr>
            <td class="small">Fecha desde</td>
          </tr>
          <tr>
            <td><?php  abrir_calendario('fecha','premio', $fecha); ?></td>
          </tr>
              </table></td>
   	  <td scope="col"><table border="0" cellspacing="0">
          <tr>
            <td class="small">Fecha hasta</td>
          </tr>
          <tr>
            <td><?php  abrir_calendario('fhasta','premio', $fhasta); ?></td>
          </tr>
        </table></td>
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

<table width="87%" border="0" align="center">
<tr>
  <td colspan="10" align="center" valign="bottom" scope="col"><table width="103%" border="0">
<tr>
                <td width="96%" align="center" class="texto4" >Datos Personales de Premios</td>
  <td width="4%">
                <a href="#" onclick="window.open('list/premios_pagados.php','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
        </tr>
    </table></td>
  </tr>
		 
        <tr align="center" class="td2">
          <td width="6%" class="td4" scope="col">Fecha</td>
          <td width="12%" class="td4" scope="col">Sucursal</td>
          <td width="19%" class="td4" scope="col">Ganador</td>
          <td width="8%" class="td4" scope="col">Juego</td>
          <td width="8%" class="td4" scope="col">Importe</td>
          <td width="9%" class="td4" scope="col">Im&aacute;genes</td>
          <td width="18%" class="td4" scope="col">Usuario</td>
          <td width="8%" class="td4" scope="col">Datos Ganador</td>
          <td width="12%" class="td4" scope="col">Observación</td>
          <td width="12%" class="td4" scope="col">Modificar</td>
  </tr>
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
           <td align="center"><?php echo $row->FECHA;?></td>

           <td align="left"><?php echo $row->SUCURSAL;?></td>
           <td align="left"><?php echo utf8_encode(trim($row->APELLIDO)).', '.utf8_encode($row->NOMBRE);?></td>
         <td align="left" ><?php echo $row->JUEGOS;
		   						$totales=$totales+$row->VALOR_PREMIO?></td>
           <td align="right" ><?php echo number_format($row->VALOR_PREMIO,2,',','.');
		   						$totales=$totales+$row->VALOR_PREMIO?></td>
           <td align="center" >
<!--           <a href="#" onclick="window.open('list/datos_ganadores.php?id_ganador=<?php echo $row->ID_GANADOR ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Adobe Reader 7.png" alt="Imprimir" width="24" height="23" border="0" /></a><a href="#" onclick="window.open('list/datos_ganadores.php?id_ganador=<?php echo $row->ID_GANADOR ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"></a>-->
           <a href="#" onclick="ajax_showTooltip('premio/mostrar_imagen.php?jsfecha='+new Date()+'&id_ganador=<?php echo $row->ID_GANADOR ?>',this); return false;" ><img src="image/download.png" alt="ver archivos"  width="20" height="20" border="0"/> </a></td>
						   
	       <td align="left" ><?php echo $row->USUARIO;?></td>
	       <td align="center" ><a href="#" onclick="window.open('list/datos_ganadores.php?id_ganador=<?php echo $row->ID_GANADOR ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Adobe Reader 7.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
         	<td align="center" >
         
		 <?php  if ($row->OBSERVACION==0){?>
         
         <a href="#" onclick="ajax_get('contenido','premio/procesar_datos.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=1&conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/SyncCenter.png" alt="Datos Completos" width="24" height="23" border="0" /></a>
          <?php } else {?>
		  		<a href="#" onclick="ajax_get('contenido','premio/procesar_datos.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&observacion=0&conformado=<?php echo $conformado; ?>&suc_ban=<?php echo $suc_ban ; ?>')"><img src="image/Error.png" alt="Datos Incompletos" width="24" height="23" border="0" /></a>
		  
		   <?php  }?>
           <a href="#" onclick="ajax_get('contenido','premio/nueva_nota_observacion.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>')"><img src="image/app_48.png" alt="Añadir Nota" width="24" height="23" border="0" /></a>   
           </td>
           <td align="center" ><a href="#" onclick="ajax_get('contenido','premio/modificar_premio.php','id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&casa=<?php echo $suc_ban; ?>'); return false;" ><img src="image/modificar.png" alt="modificar datos"  width="20" height="20" border="0"/></a></td>
  			</tr>
        <?php  }?>
		 <tr>
          
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
  </table>
<?php $_SESSION['sqlreporte']= $_pagi_sql;?>
<?php $_SESSION['bandera']= 0;?>
