<?php session_start();
//print_r($_GET);
include("../jscalendar-1.0/calendario.php");
include("../db_conecta_adodb.inc.php");

include("../funcion.inc.php");
$array_fecha = FechaServer();
$variables = array();
$ganador = null;			
			
//print_r($_GET);
//print_r($_POST);
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

try {$rs_sucursal = $db ->Execute("select suc_ban as codigo, nombre as descripcion 
	from juegos.sucursal 
	where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,51,60,62,63,64,65,66,67,69,73,79,80,81) order by codigo");	}
	catch (exception $e) {die ($db->ErrorMsg()); } 	
	 
if (isset($_GET['suc_ban']) && $_GET['suc_ban']==0) {
	$suc_ban = $_GET['suc_ban'];
	$condicion_sucursal = "";
	} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==2) {
		$suc_ban = $_GET['suc_ban'];
		$condicion_sucursal = "and b.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
		} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']==3) {
			$suc_ban = $_GET['suc_ban'];
			$condicion_sucursal = "and b.suc_ban in (60,62,63,64,65,66,67,69,73,79,80,81)";
			} else if (isset($_GET['suc_ban']) && $_GET['suc_ban']!=0) {
				$suc_ban = $_GET['suc_ban'];
				$condicion_sucursal = "and b.suc_ban in ($suc_ban)";
				} else if (isset($_POST['suc_ban']) && $_POST['suc_ban']==2) {
					$suc_ban = $_POST['suc_ban'];
					$condicion_sucursal = "and b.suc_ban in (20,21,22,23,24,25,26,27,30,31,32,33,51)";
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

			$totales=0;

?>
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />


<?php 

 $_pagi_sql ="select a.id_ganador, to_char(a.fecha_alta,'DD/MM/YYYY') as fecha, a.nombre nombre , observacion, a.politico, 
 			a.apellido apellido,  initcap(b.nombre)  as casa,valor_premio, c.juegos, b.nombre as sucursal, 
			upper(substr(d.descripcion,1,17)) usuario, upper(substr(e.descripcion,1,17)) usuario_conforma,upper(substr(v.descripcion,1,17)) usuario_modifica,a.ddjj,
			(select count(*) 
				from utilidades.t_archivos x 
				where x.esquema='lavado_dinero' and x.tabla='t_ganador' and x.id_tabla = a.id_ganador) as imagenes,
			a.xml
      		from PLA_AUDITORIA.t_ganador a, juegos.sucursal b, superusuario.usuarios d, juegos.juegos c,superusuario.usuarios e,superusuario.usuarios v
			where a.suc_ban = b.suc_ban(+) 
			and a.juego = c.id_juegos
			and a.usuario = d.id_usuario
			and a.usuario_conforma= e.ID_usuario(+)
			and a.usuario_modifica= v.ID_usuario(+)
			and a.fecha_alta between to_date('$fecha 00:00','DD/MM/YYYY HH24:MI') and to_date('$fhasta 23:59','DD/MM/YYYY HH24:MI')
			and a.fecha_baja is null
			$condicion_sucursal
			$condicion_conforma
			$condicion_mayores
			$condicion_ganador
			order by a.fecha_alta desc, b.nombre";
 
$_pagi_div = "contenido";




//$_pagi_enlace = "premio/premios_conformados.php";
$_pagi_cuantos = 20; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='conformado'; 
$_pagi_propagar[2]='fecha'; 
$_pagi_propagar[3]='fhasta'; 
$_pagi_propagar[4]='mayores'; 

 include("../paginator_adodb_oracle.inc.php");
$_SESSION['nro_pagina']=$_pagi_actual ;

?>
<style type="text/css">
<!--
.Estilo1 {color: #000000}
-->
</style>
<form id="premio" name="premio" action="#" onsubmit="ajax_post('contenido','premio/adm_premio_consulta.php',this); return false;">
<br />
  
<table align="center">
<tr valign="middle"  >
<td align="left"  scope="col">
       		<table border="0" cellspacing="0">
           		<tr>
                 
	              <td class="small">Delegaciones</td>
	                 <td class="small">B&uacute;squeda</td>
           		</tr>
	            <tr valign="middle">
				
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
	    <input type="submit" class="small" name="buscar" value="Buscar" /></td>
    </tr>
</table>
</form>
<p>
  <?php if ($_pagi_result->RowCount()==0) {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">SIN MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','premio/adm_premio_consulta.php','')\">Regresar</a></div>");
}
?>
</p>

<table width="100%" border="0" align="center">
	
	<tr>
        <td width="1%" align="right"> <a href="#" onclick="window.open('list/personas_politicas.php?conformado=<?php echo $conformado; ?>&fecha=<?php echo $fecha;?>&fhasta=<?php echo $fhasta;?>&suc_ban=<?php echo $suc_ban;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
        <td colspan="8" align="center" valign="bottom" scope="col" class="texto4">Datos Personales de Premios</td>
        <td width="1%" align="right"> <a href="#" onclick="window.open('list/premios_pagados.php?conformado=<?php echo $conformado; ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_app_printer.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
    </tr>
    <tr>          
        <td colspan="10" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
        
    </tr>   
	<tr align="center" class="td2">
    	<td width="6%" class="td4" scope="col">Fecha</td>
        <td width="12%" class="td4" scope="col">Sucursal</td>
        <td width="19%" class="td4" scope="col">Ganador</td>
        <td width="8%" class="td4" scope="col">Juego</td>
        <td width="8%" class="td4" scope="col">Importe</td>
        <td width="9%" class="td4" scope="col">Im&aacute;genes</td>
        <td class="td4" scope="col" colspan="3">Usuarios<br />
      Carga&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Modifica&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Conforma</td>
    <!--<td width="18%" class="td4" scope="col">Usuario Conforma</td>-->
        <td width="8%" class="td4" scope="col">Datos Ganador</td>
       
    </tr>
	<?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
		<tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
			<td align="center"><?php echo $row->FECHA;?></td>

           	<td align="left"><?php echo $row->SUCURSAL;?></td>
           	<td align="left"><?php echo  trim($row->APELLIDO).' '.trim($row->NOMBRE);?></td>
         	<td align="left" ><?php echo $row->JUEGOS; $totales=$totales+$row->VALOR_PREMIO?></td>
           	<td align="right" ><?php echo number_format($row->VALOR_PREMIO,2,',','.'); $totales=$totales+$row->VALOR_PREMIO?></td>
           	<td align="center" >
				<a href="#" onclick="ajax_showTooltip('premio/mostrar_imagen.php?jsfecha='+new Date()+'&id_ganador=<?php echo $row->ID_GANADOR ?>&fdesde=<?php echo $fecha; ?>&fhasta=<?php echo $fhasta; ?>&conformado=<?php echo $conformado ; ?>&suc_ban=<?php echo $suc_ban; ?>',this); return false;" >
                <img src="image/download.png" alt="ver archivos"  width="20" height="20" border="0"/> 
                </a>
			</td>
	       	<td width="12%" align="left" style="font-size:10px" ><?php echo $row->USUARIO;?></td>
          <td width="12%" align="left" style="font-size:10px" ><?php echo $row->USUARIO_MODIFICA;?></td>
       	  <td width="12%" align="left" style="font-size:10px" ><?php echo $row->USUARIO_CONFORMA;?></td>
       	  <td align="center" ><a href="#" onclick="window.open('list/datos_ganadores.php?id_ganador=<?php echo $row->ID_GANADOR ?>&delegacion=<?php echo utf8_decode($row->SUCURSAL)?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/Adobe Reader 7.png" alt="Imprimir" width="24" height="23" border="0" /></a></td>
	   
		</tr>
        
		<?php  }?>
		 <tr>
          
          <td colspan="10" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
  </table>

<?php $_SESSION['sqlreporte']= $_pagi_sql;?>
<?php $_SESSION['bandera']= 0;?>
