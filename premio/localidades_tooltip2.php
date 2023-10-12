<?php 
session_start(); 
include("../db_conecta_adodb.inc.php");
include ("../funcion.inc.php");
?>
<?php
 if (isset($_POST['codigo'])) {
	$codigo = $_POST['codigo'];
	} else if (isset($_GET['codigo']) && $_GET['codigo']!="") {
		$codigo = $_GET['codigo'];
	}

if (isset($_POST['formulario'])) {
	$formulario = $_POST['formulario'];
	} else if (isset($_GET['formulario']) && $_GET['formulario']!="") {

		$formulario = $_GET['formulario'];
	}
	
		
if (isset($_POST['descripcion'])) {
		$descripcion1 = strtoupper($_POST['descripcion']);
		$descripcion = strtr($descripcion1,utf8_encode('àèìòùáéíóúçñäëïöü'),utf8_encode('ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ'));
		$descripcion2=utf8_decode($descripcion);
		$condicion_descripcion = " and (upper(n_localidad) like ('%$descripcion2%'))";
} else if (isset($_GET['descripcion']) && $_GET['descripcion'] != "") {
		$descripcion1= strtoupper($_GET['descripcion']);
		$descripcion = strtr($descripcion1,utf8_encode('àèìòùáéíóúçñäëïöü'),utf8_encode('ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ'));
		$descripcion2=utf8_decode($descripcion);
		$condicion_descripcion = " and (upper(n_localidad) like ('%$descripcion2%'))";
} else {
		$descripcion = "";
		$condicion_descripcion = "";
}
$_pagi_sql = "select id_localidad,initcap(n_localidad) n_localidad from administrativo.t_localidades where id_provincia=$codigo $condicion_descripcion order by n_localidad"; 
$_pagi_cuantos = 10; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
$_pagi_propagar[0]='descripcion';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[1]='formulario';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[2]='codigo';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_div = "contenido_tooltip";
include("../paginator_tooltip_adodb_oracle.inc.php"); 
?>

<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<div id="contenido_tooltip">

<table border="0"   cellpadding="0" cellspacing="0">
 <tr>
   <td width="304" height="65"> 
         
  <form name="localidad" method="post" action="#" onSubmit="ajax_post_tooltip('contenido_tooltip','premio/localidades_tooltip2.php',this); return false;">

        <table width="304" border="0" cellpadding="0" cellspacing="0">
                  <tr align="center" >
                    <td   align="left" class="td" ><div align="left">B&uacute;squeda de localidades
                      <input name="formulario" type="hidden" id="formulario" value="<?php echo $formulario; ?>" />
                      <input name="codigo" type="hidden" id="codigo" value="<?php echo $codigo; ?>" /> 
                    </div></td>
                    <td width="46"  class="td" ><a href="#"onclick="ajax_hideTooltip()">Salir</a></td>
                  </tr>
              		<tr>
              		  <td>
                        <table width="100%"  cellpadding="0" cellspacing="0" border="1" >
              
                            <tr align="left" class="tdVerde">
                                <td width="20%" class="style20">
                                  <input name="descripcion" type="text" class="texto5"  id="descripcion" value="<?php echo $descripcion; ?>" size="30"/>
                              </td>
                                <td width="20%" class="style20"><div align="center">
                                  <input name="buscar" type="submit" class="small" id="buscar" value="BUSCAR" />
                                </div></td>
                            </tr>
                        </table>
				</td>
              </tr>
            </table>
        </form>
	   
	</tr>
	 
	<tr>
		<td>
		  <table  width="100%" border="0" cellspacing="0">
      		<tr>
                <td  class="smallVerde"><div align="center" class="td9"><?php echo $_pagi_navegacion.'<br> '.$_pagi_info ?></div></td>
            </tr>
           
              <tr align="left">
				<td class="td"><div align="center">Nombre Localidad</div></td></tr>
              <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)) { ?>
               <tr class="smallCalendario">
                 <td class="texto3Totales"><a href="#" onClick="document.<?php echo $formulario;?>.localidad2.value='<?php echo utf8_encode($row->N_LOCALIDAD);?>';
                                                                document.<?php echo $formulario;?>.cod_localidad2.value='<?php echo $row->ID_LOCALIDAD; ?>'; 
                                                                document.<?php echo $formulario;?>.localidad_memo2.value='<?php echo utf8_encode($row->N_LOCALIDAD);?>';
                                                                document.<?php echo $formulario;?>.cod_localidad_memo2.value='<?php echo $row->ID_LOCALIDAD; ?>';
                                                                ajax_hideTooltip(); return false;"><?php echo utf8_encode($row->N_LOCALIDAD); ?></a></td>
			</tr>
           <?php }     ?>
        </table>
    </td>
  </tr>
</table>
</div>   
 

