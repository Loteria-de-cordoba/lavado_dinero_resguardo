<script type="text/javascript">
	jQuery(document).ready(function($) {

			$("#ap").autocomplete({
		        source: function(request, response) {
		            $.ajax({
		                url: "cliente/ajax.php?casino="+$( "#casino option:selected" ).val(),
		                dataType: "json",
		                data: {
		                    term: request.term,
		                },
		                success: function(data) {
		                    response(data);
		                }
		            });
		        },
		        minLength: 2,
		        select: function(event, ui) {
                    $('#apostador').val(ui.item.id);
		        }
		    });

			$('#ap').blur(function()
			    {
			        if( $(this).val().length === 0 ) {
			            $('#apostador').val('');
			        }
			    });

   });


    function buscarNovedad(){
        post('cliente/valoracion_filtro.php', $("form:eq(0)").serializeAll() ,'#contenido'); return false;
    }
</script>
<?php session_start();

if (basename($_SERVER['PHP_SELF']) == 'index.php') {
    include_once "jscalendar-1.0/calendario.php";
    include_once "db_conecta_adodb.inc.php";
    include_once "funcion.inc.php";
} else {
    include "../jscalendar-1.0/calendario.php";
    include "../db_conecta_adodb.inc.php";
    include "../funcion.inc.php";
}
$habilitado = 0;
$i          = 0;
$caorigen   = '';
$myarea     = '';
$ccuenta    = 0;
$variables  = array();

//$db->debug=true;

//print_r($_REQUEST);


	

if (isset($_GET['ap'])) {
    $ap = $_GET['ap'];
	$apostador=$ap;//agrego en marzo del 2019
} else if (isset($_POST['ap'])) {
    $ap = $_POST['ap'];
	$apostador=$ap;//agrego en marzo del 2019
}
//echo $apostador;
//selecciono mi area con rol op_unico
try {
    $rs_myarea = $db->Execute("SELECT us.area_id_principal as area  FROM
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99 or area_id_principal=4 OR AREA_ID_PRINCIPAL=32)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
} //select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
 catch (exception $e) {
    die($db->ErrorMsg());
}
$row_myarea = $rs_myarea->FetchNextObject($toupper = true);
$myarea     = $row_myarea->AREA;
//echo $myarea;
//selecciono los casineros con rol op_unico
try {
    $rs_usuario = $db->Execute("SELECT count(*) as cuenta FROM
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
} //select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
 catch (exception $e) {
    die($db->ErrorMsg());
}
$row_usuario = $rs_usuario->FetchNextObject($toupper = true);
$ccuenta     = $row_usuario->CUENTA;

while ($i < $_SESSION['cantidadroles']) {

    $i = $i + 1;
    //print_r($_SESSION['rol'.$i]);
    //echo $_SESSION['rol'.$i];
    //Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
    if (($_SESSION['rol' . $i] == 'ROL_LAVADO_DINERO_OP_UNICO' and $ccuenta != 0) || ($_SESSION['rol' . $i] == 'ROL_LAVADO_DINERO_CASINO_CARGA' and $ccuenta != 0) || ($_SESSION['rol' . $i] == 'ROL_LAVADO_DINERO_ADMINISTRA')) {$habilitado = 1;}
    //if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))    {$habilitado=1;}
}
if ($habilitado == 0) {
    die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} else {
//echo $habilitado;
    //die();
    $j            = 0;
    $casino       = 0;
    $casino_setea = 0;
    $area         = '';
    $area         = $_SESSION['area'];
    $totfichaje   = 0;
    $totacierto   = 0;
    $totinfichaje = 0;
    $totinacierto = 0;
    if (substr($area, 0, 6) == 'Casino') {
        try {
            $rs_setea_casino = $db->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
        } catch (exception $e) {die($db->ErrorMsg());}
        $row_setea_casino = $rs_setea_casino->FetchNextObject($toupper = true);
        if ($rs_setea_casino->RecordCount() != 0) {
            $casino_setea = $row_setea_casino->CODIGO;
        }

    } else {
        $casino_setea = 100;
    }
//echo $casino_setea;
    //die();
    if (substr($area, 0, 6) == 'Casino' and !isset($_POST['casino']) and !isset($_GET['casino'])) {
        try {
            $rs_busca_casino = $db->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
        } catch (exception $e) {die($db->ErrorMsg());}
        $row_busca_casino   = $rs_busca_casino->FetchNextObject($toupper = true);
        $casino             = $row_busca_casino->CODIGO;
        $casino             = $row_busca_casino->CODIGO;
        $condicion_conforma = "and B.id_casino='$casino'";
        $soydeaca           = $row_busca_casino->DESCRIPCION;

    } else {
        $soydeaca = $area;
//echo '****paso***';
        /*if (isset($_POST['casino'])&& $_POST['casino']<>0 ) {
        $casino = $_POST['casino'];
        $condicion_conforma="and a.id_casino ='$casino'";
        } elseif (isset($_GET['casino'])&& $_GET['casino']<>0 ) {
        $casino = $_GET['casino'];
        $condicion_conforma="and a.id_casino ='$casino'";
        } else {
        $casino = 0;
        $condicion_conforma="and a.id_casino=0";
        }*/
        if (isset($_POST['casino'])) {
            $casino = $_POST['casino'];
            if ($casino != 0) {
                $condicion_conforma = "and B.id_casino='$casino'";
            } else {
                $condicion_conforma = '';
            }
        } else {
            if (isset($_GET['casino'])) {
                $casino = $_GET['casino'];
                if ($casino != 0) {
                    $condicion_conforma = "and B.id_casino='$casino'";
                } else {
                    $condicion_conforma = '';
                }
            } else {
                $casino             = 100;
                $condicion_conforma = "and B.id_casino='$casino'";
            }
        }
    }

//seteo casino
    if ($casino_setea == 100) {
        try {
            $rs_casino = $db->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino not in(2,13)
										");
        } //select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
         catch (exception $e) {
            die($db->ErrorMsg());
        }
    } else {
        try {
            $rs_casino = $db->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino=?
										", array($casino_setea));
        } //select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
         catch (exception $e) {
            die($db->ErrorMsg());
        }
    }

    try {
        $rs_apostador = $db->Execute("select codigo, descripcion
										from(
													select apellido || decode(nombre,'','',', ' || nombre) as descripcion, max(id_cliente) as codigo
													from lavado_dinero.t_cliente
													where fecha_baja is null
													group by apellido || decode(nombre,'','',', ' || nombre)
													--and id_casino=$casino
													order by descripcion)");
    } //select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
     catch (exception $e) {
        die($db->ErrorMsg());
    }

    while ($i < $_SESSION['cantidadroles']) {

        $i = $i + 1;
        //print_r($_SESSION['rol'.$i]);

        //if ((($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||$_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC')|| ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO'))    {$habilitado=1;}
        //Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
        if (($_SESSION['rol' . $i] == 'ROL_LAVADO_DINERO_OP_UNICO') || ($_SESSION['rol' . $i] == 'ROL_LAVADO_DINERO_CASINO_CARGA' and $ccuenta != 0) || ($_SESSION['rol' . $i] == 'ROL_LAVADO_DINERO_ADMINISTRA')) {$habilitado = 1;}
        //if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))    {$habilitado=1;}
    }
    if ($habilitado == 0) {
        die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
    } else {
//print_r($_GET);
        //print_r($_POST);
        //$db->debug=true;
        //echo $suc_ban.'sucban';
        $array_fecha = FechaServer();

        while ($j < $_SESSION['cantidadroles']) {
            $j = $j + 1;

            if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];} else {
                if (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];} else { $fecha = '01/' . str_pad($array_fecha["mon"], 2, '0', STR_PAD_LEFT) . '/' . $array_fecha["year"];}
            }

            if (isset($_GET['fhasta'])) {
                $fhasta          = $_GET['fhasta'];
                $dia             = substr($_GET['fhasta'], 0, 2);
                $fhasta_consulta = $dia . substr($_GET['fhasta'], 2, 8);} else {
                if (isset($_POST['fhasta'])) {
                    $dia             = substr($_POST['fhasta'], 0, 2);
                    $dia             = $dia + 1;
                    $fhasta_consulta = $dia . substr($_POST['fhasta'], 2, 8);

                    $fhasta = $_POST['fhasta'];
                } else {
                    $fhasta          = str_pad($array_fecha["mday"], 2, '0', STR_PAD_LEFT) . '/' . str_pad($array_fecha["mon"], 2, '0', STR_PAD_LEFT) . '/' . $array_fecha["year"];
                    $fhasta_consulta = str_pad($array_fecha["mday"] + 1, 2, '0', STR_PAD_LEFT) . '/' . str_pad($array_fecha["mon"], 2, '0', STR_PAD_LEFT) . '/' . $array_fecha["year"];}
            }

            if (isset($_POST['apostador'])) {
                if ($_POST['apostador'] != 0 and $_REQUEST['ap']!='' and isset($_REQUEST['ap'])) {
                    $apostador           = $_POST['apostador'];
                    $condicion_apostador = "and b.id_cliente ='$apostador'";
                } else {
                    $apostador           = '0';
                    $condicion_apostador = "";
                }
            } else {
                if (isset($_GET['apostador'])) {
                    if ($_GET['apostador'] != 0 and $_REQUEST['ap']!='' and isset($_REQUEST['ap'])) {
                        $apostador           = $_GET['apostador'];
                        $condicion_apostador = "and b.id_cliente ='$apostador'";
                    } else {
                        $apostador           = '0';
                        $condicion_apostador = "";
                    }
                } else {
                    $apostador           = '0';
                    $condicion_apostador = "";
                }
            }
			
			/*LO SIGUIENTE LO AGREGO EN MARZO DEL 2019*/
			if(isset($_REQUEST['ap']) and !isset($_REQUEST['apostador']))
			{
				if($_REQUEST['ap']==NULL or $_REQUEST['ap']=='')
					{
						$apostador='0';
						$condicion_apostador = "";
					}
					else
					{
						$apostador=$_REQUEST['ap'];
						$condicion_apostador = "and b.id_cliente ='$apostador'";
					}
			}
			

            ?>
<?php
//if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OP_UNICO'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OPERADOR') {
            //$db->debug=true;
            //echo('ENTRA');
            
                $_pagi_sql = "
				SELECT substr(c.n_casino,8)    casino,
				TO_CHAR(a.fecha_novedad, 'dd/mm/yyyy') AS fecha,
					b.apellido
					|| ' '
					|| b.nombre AS identificacion,
					DECODE(a.valoracion, NULL, 'Sin Valoracion Aun', a.valoracion) AS valoracion,
					b.id_cliente id_cliente,    
					b.id_casino   id_casino
				FROM
					lavado_dinero.t_novedad_casino   a,
					lavado_dinero.t_cliente          b,
					casino.t_casinos                 c,
					superusuario.usuarios            us
				WHERE
					a.id_cliente = b.id_cliente
					and a.id_casino = c.id_casino
					AND us.id_usuario = b.usuario
					AND b.fecha_baja IS NULL
					AND COMPLETA=1
					$condicion_conforma
					$condicion_apostador
					AND b.fecha_baja    IS NULL
					and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
				ORDER BY
					a.id_casino,
					a.fecha_novedad DESC,
					b.apellido
					|| ' '
					|| b.nombre";
            

            $_pagi_div                = "contenido";
            $_pagi_enlace             = "cliente/valoracion_filtro.php";
            $_pagi_cuantos            = 12;
            $_pagi_conteo_alternativo = true;
            $_pagi_nav_num_enlaces    = 10;
            navegación .
            $_pagi_nav_estilo  = "small_navegacion";
            $_pagi_propagar[0] = 'fecha';
            $_pagi_propagar[1] = 'fhasta';
            $_pagi_propagar[2] = 'casino';
            $_pagi_propagar[3] = 'apostador';
			$_pagi_propagar[4] = 'ap';

            if (basename($_SERVER['PHP_SELF']) == 'index.php') {
                include "paginator_adodb_oracle.inc.php";
            } else {
                include "../paginator_adodb_oracle.inc.php";
            }

            $_SESSION['nro_pagina'] = $_pagi_actual;

            ?>

<form id="premio" name="premio" action="#">
  <table width="95%"  align="center">
    <tr>
      <td colspan="8" align="center" valign="bottom" class="texto4" scope="col">VALORACIONES DEL REGISTRO DE APOSTADORES CASINO  - <?php echo $soydeaca; ?> </a></td>
      <!--<?php // if ($_SESSION['rol1'] == 'ROL_LAVADO_DINERO_ADMINISTRA') {?>
    	<td colspan="4" align="center" valign="bottom" class="texto4" scope="col"><img src="image/muchos.jpg" TITLE="Consulta Masiva" width="40" height="40" border="0" onclick="ajax_get('contenido','cliente/movi_masivo.php','');return false"/></td>
	<?PHP //}?>-->
    </tr>
    <tr valign="bottom" class="td8" >
      <td width="46" align="right" valign="middle" class="td2"  scope="col">Casino</td>
      <td width="142"  valign="middle" class="td2" scope="col" align="center"><?php
/*if (substr($area, 0, 6) != 'Casino') {
            armar_combo_ejecutar_ajax_get_todos($rs_casino, "casino", $casino, 'casinito', 'cliente/combo_apostador.php');
            } else {
            armar_combo_ejecutar_ajax_get($rs_casino, "casino", $casino, 'casinito', 'cliente/combo_apostador.php');
            }*/

            armar_combo_todos($rs_casino, "casino", $casino);
            ?></td>
      <td width="46" align="right" valign="middle" class="td2"  scope="col">Apostador</td>
      <?php if ($apostador != '') {?>
      <td width="142"  valign="middle" class="td2" scope="col" align="center"><div id="casinito">
          <input type="text" name="ap" id="ap" value="<?php echo $ap ?>">
          <?PHP if(isset($_REQUEST['apostador']) and $apostador!=0)
		{?>
          <input type="hidden" name="apostador" id="apostador" value="<?php echo $apostador ?>">
        </div></td>
      <?php }
	    else
        {
		?>
      <input type="hidden" name="apostador" id="apostador" value="<?php echo "";//$apostador ?>">
      </div>
      </td>
      <?php }?>
      <?php }?>
      <td width="92" valign="middle" class="td2"  scope="col">Fecha desde </td>
      <td width="168" valign="middle" class="td2" scope="col"><?php abrir_calendario('fecha', 'premio', $fecha);?></td>
      <td width="84" valign="middle" class="td2" scope="col">Fecha hasta</td>
      <td width="166" valign="middle" class="td2" scope="col"><?php abrir_calendario('fhasta', 'premio', $fhasta);?></td>
      <?php if ($_SESSION['rol' . $j] != 'ROL_LAVADO_DINERO_ADM_CASINO') {?>
      <?php }?>
      <!--<td width="24" align="center" class="td2" ><a href="#" onClick="g('cliente/agregar_movimiento.php?apostador='+premio.apostador.value+'&casino='+premio.casino.value+'&novedad=<?php// echo '1' ?>&ap='+$('#ap').val(),'#contenido')"><img src="image/24px-Crystal_Clear_action_db_add.png" alt="Agregar Movimiento" width="20" height="20" border="0"/></a></td>-->
      <td width="21" align="center" class="td2" ><a href="#" onClick="window.open('list1/mov_observaciones_todos.php?apostador=<?php echo $apostador ?>&casino='+premio.casino.value+'&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" title="Ver Reporte de todas las Valoraciones del Periodo" width="20" height="20" border="0" /></a></td>
      <!--<td align="center" class="td2" scope="col" ><a href="#" onclick="window.open('list1/datos_apostadores_todos.php?casino='+premio.casino.value+'&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>
      <td width="24" align="center" class="td2"><img width="24" height="24" onclick="window.open('cliente/lista_apostadores_xls.php?apostador=<?php// echo $apostador ?>&casino='+premio.casino.value+'&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value)" title="REPORTE EXCEL DE PANTALLA" src="image/Excel-Document.png" border="0" complete="complete" ;=""/></td>-->
      <input type="hidden" name="fhasta_consulta" id="fhasta_consulta" value="<?php echo $fhasta_consulta; ?>" />
      <td width="69" class="td2" scope="col" align="right"><input type="button" value="Buscar" onclick="buscarNovedad();" /></td>
    </tr>
    <tr align="center">
    <td align="center" scope="row" colspan="9"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="g('cliente/adm_novedad_casino.php?casino=<?php echo $casino; ?>&apostador=<?php echo $apostador; ?>&ap=<?php echo $a; ?>','#contenido')">Retornar a Reporte Diario</a></div></td>
  </tr>
  </table>
</form>
<?php if ($_pagi_result->RowCount() == 0) {

                die("<br><div align=\"center\"><span class=\"textoRojo\">NO HAY MOVIMIENTOS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','blanco.php','')\">Cerrar</a></div>");
            }
            ?>
<span class="td4">
<?php }?>
</span>
<table width="95%" border="0" align="center">




  <tr>
    <td colspan="10" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion . "   " . $_pagi_info ?></td>
  </tr>
  <tr align="center" class="td2">
  <td width="10%">Casino</td>
    <td width="5%" class="td4">Fec.Movim.</td>
   
    
    
    <td width="35%">Apellido y Nombre / Apodo</td>
    <td width="40%">Valoracion</td>
     <td width="10%">Detalle</td>
  </tr>
  <?php while ($row = $_pagi_result->FetchNextObject($toupper = true)) {
            ?>
  <tr class="<?php if ($_pagi_result->CurrentRow() % 2) {echo "td";} else {echo "td8";}?>">
    <td align="left" width="10%"><?php 
                if ($caorigen != $row->CASINO) {
                    ?>
    <?php echo $row->CASINO;
                    $caorigen = $row->CASINO; ?></td>
    <?php }?>
    <td align="center" width="5%"><?php echo $row->FECHA; ?></td>
    
    <td align="left" width="35%"><?php echo utf8_decode(trim($row->IDENTIFICACION));?></td>
    <td align="left" width="40%"><?php echo utf8_decode($row->VALORACION); ?></td>
  <td width="26" align="center" class="td2" ><a href="#" onClick="window.open('list1/mov_detalle_observaciones.php?casino=<?php echo $row->ID_CASINO;?>&fechita=<?php echo $row->FECHA;?>&cliente=<?php echo $row->ID_CLIENTE;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/ojos.jpg" title="Valoraciones Marcadas(X) - Diaria" width="20" height="20" border="0" /></a></td>
  </tr>
  <?php }?>
  <tr>
    <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion . "   " . $_pagi_info ?></td>
  </tr>
  <tr>
    <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina">&nbsp;</div></td>
  </tr>
  <tr align="center">
    <td align="center" scope="row" colspan="9"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="g('cliente/adm_novedad_casino.php?casino=<?php echo $casino; ?>&apostador=<?php echo $apostador; ?>&ap=<?php echo $a; ?>','#contenido')">Retornar a Reporte Diario</a></div></td>
  </tr>
</table>
<?php // echo number_format($row->ACIERTO,2,',','.');?>
<?php }
    $_SESSION['sqlreporte'] = $_pagi_sql;
} //fin de habilitado?>
