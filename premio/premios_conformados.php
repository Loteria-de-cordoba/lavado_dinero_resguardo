<?php  	session_start();
//print_r($_POST);
//$db->debug=true;

	include_once("../jscalendar-1.0/calendario.php");
	include_once("../db_conecta_adodb.inc.php");
	include_once("../funcion.inc.php");
	$paginator="../paginator_adodb_oracle.inc.php"; 
$array_fecha = FechaServer();	
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
			$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)");
			}
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 	
	 
/*if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
} else {
		if (isset($_POST['suc_ban']) && $_POST['suc_ban']!=0) {
			$suc_ban = $_POST['suc_ban'];
			$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
			} else {
					$suc_ban = 0;
					$condicion_sucursal = "";
					}
		}*/
		
$suc_ban=$_SESSION['suc_ban'];
	
if ($suc_ban==72){
	//$suc_ban=81;
	$condicion_sucursal = "and (b.suc_ban in ($suc_ban) or b.suc_ban=81)";	
}

else {
	$condicion_sucursal ="and (b.suc_ban in ($suc_ban))";
}



//$condicion_sucursal = "and (b.suc_ban in ($suc_ban) or b.suc_ban=81)";				
 
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
 
?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />
<!--<form action="" method="post" enctype="multipart/form-data" name="formulario" id="formulario">-->

<?php 
 //$db->debug=true;
 $_pagi_sql ="select a.id_ganador, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha,initcap(a.nombre) nombre , 
 			initcap(a.apellido) apellido,  initcap(b.nombre)  as casa,valor_premio, conformado
      		from PLA_AUDITORIA.t_ganador a, juegos.sucursal b
			where a.suc_ban = b.suc_ban 
			and a.fecha_alta between to_date('$fecha','DD/MM/YYYY HH24:MI') and to_date('$fhasta','DD/MM/YYYY HH24:MI')
			$condicion_sucursal
			$condicion_conforma
			order by fecha";
 
$_pagi_div = "contenido";
$_pagi_enlace = "premio/premios_conformados.php";
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
include($paginator); 

	?>
<style type="text/css">
<!--
.Estilo1 {color: #000000}
-->
</style>
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','premio/premios_conformados.php',this); return false;">
<br />
<table align="center">
<tr valign="middle" class="td8" >
	   <td align="left" class="td2"  scope="col"><table border="0" cellspacing="0">
           <tr>
             <td class="small">B&uacute;squeda</td>
           </tr>
           <tr valign="middle">
             <td><select name="conformado" class="small" id="conformado">
               <option value="1" <?php if ($conformado==1) echo 'selected';?>>conformado</option>
               <option value="0" <?php if ($conformado==0) echo 'selected';?>>no conformado</option>
             </select></td>
           </tr>
         </table></td>
      <td class="td2"  scope="col"><table border="0" cellspacing="0">
          <tr>
            <td class="small">Fecha desde</td>
          </tr>
          <tr>
            <td><?php  abrir_calendario('fecha','premio', $fecha); ?></td>
          </tr>
              </table></td>
   	  <td class="td2" scope="col"><table border="0" cellspacing="0">
          <tr>
            <td class="small">Fecha hasta</td>
          </tr>
          <tr>
            <td><?php  abrir_calendario('fhasta','premio', $fhasta); ?></td>
          </tr>
        </table></td>
      <td width="200" align="center" class="td2" scope="col"><a href="#" class="texto4" onClick="ajax_get('contenido','premio/premios_conformar.php','fecha=<?php echo date("d/m/Y"); ?>'); return false;">Conformar premios</a></td>
   	  <td align="center" class="td2" scope="col">
		
		<img src="image/xmag.gif" alt="Buscar" width="16" height="16" border="0" />
	  <input type="submit" class="small" value="Buscar" /></td>
    </tr>
</table>
</form>
<p>
  <?php if ($_pagi_result->RowCount()==0) {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">SIN MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','premio/premios_conformados.php','')\">Regresar</a></div>");
}
?>
</p>
<table width="57%" border="0" align="center">
<tr>
  <td colspan="5" align="center" valign="bottom" scope="col"><table width="100%" border="0">
              <tr>
                <td align="center" class="textoRojo" ></td>
                <td width="1%">
                <a href="#" onclick="window.open('list/premios_pagados.php','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
              </tr>
            </table></td>
  </tr>
		 
        <tr align="center" class="td2">
          <td width="15%" class="td4" scope="col">Fecha</td>
          <td width="32%" class="td4" scope="col">Apellido y Nombre</td>
          <td width="23%" class="td4" scope="col">Importe</td>
          <td width="30%" class="td4" scope="col">Im&aacute;genes</td>
    </tr>
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
           <td align="center"><?php echo $row->FECHA;?></td>

           <td align="left"><?php echo utf8_encode(trim($row->APELLIDO)).', '.utf8_encode($row->NOMBRE);?></td>
         <td align="right" ><?php echo number_format($row->VALOR_PREMIO,2,',','.');
		   						$totales=$totales+$row->VALOR_PREMIO?>           </td>
           <td align="center" ><a href="#" onclick="ajax_showTooltip('premio/mostrar_imagen.php?jsfecha='+new Date()+'&id_ganador=<?php echo $row->ID_GANADOR ?>&conformado=<?php echo $row->CONFORMADO;?>',this); return false;" >
                			  <img src="image/download.png" alt="ver archivos"  width="20" height="20" border="0"/>  </a></td>
	   </tr>
        <?php  }?>
		 <tr>
          <td colspan="5" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
  </table>
<?php $_SESSION['sqlreporte']= $_pagi_sql;?>
