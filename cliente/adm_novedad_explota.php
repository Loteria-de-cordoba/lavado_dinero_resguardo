<?php session_start();
//print_r($_GET);
//print_r($_POST);
//print_r($_SESSION['permiso']);
//print $_SESSION['area'];
//11 de junio retiro $_server

/*if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {*/
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		
		//}
//$db->debug=true;
//print_r($_POST);
//print_r($_GET);
$totfichaje=0;
$totacierto=0;
$totfic_ing=0;
$totfic_ret=0;
$perdido=0;

$i=0;
$j=0;
$casino=0;
$area='';
$area=$_SESSION['area'];		
if(substr($area,0,6)=='Casino' and !isset($_POST['casino']) and !isset($_GET['casino']))
{
	try {
			$rs_busca_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_busca_casino =$rs_busca_casino->FetchNextObject($toupper=true);
	$casino=$row_busca_casino->CODIGO;
	$condicion_conforma="and b.id_casino ='$casino'";

}
else
{

if (isset($_GET['id_cliente'])) {
	$apostador = $_GET['id_cliente'];
	$condicion_ganador="and g.id_cliente = '$apostador'";
	}
	else 
		{
			if (isset($_POST['id_cliente'])) {
				$apostador = $_POST['id_cliente'];
				$condicion_ganador="and g.id_cliente= '$apostador'";
				} 
			else {
				$apostador = "";
				$condicion_ganador="";
			}
		}
		
if (isset($_GET['casino'])) {
	$casino = $_GET['casino'];
	$condicion_casino="and g.id_casino_novedad(+) = '$casino'";
	}
	else 
		{
			if (isset($_POST['casino'])) {
				$casino = $_POST['casino'];
				$condicion_casino="and g.id_casino_novedad(+) = '$casino'";
				} 
			else {
				$casino = "";
				$condicion_casino="";
			}
		}

$fechita=$_GET['fechita'];

// obtengo datos del apostador
try {
	$rs_apostador = $db->Execute("SELECT  'Sr/a. ' || cl.apellido || ' ' || cl.nombre as datos,
									' Registrados en ' || DECODE(G.id_casino_novedad,100,'Delegacion',ca.n_casino) || ' en fecha ' || TO_CHAR(g.fecha_novedad,'DD/MM/YYYY') as datos1
								FROM PLA_AUDITORIA.t_cliente cl,
								casino.t_casinos ca,
								PLA_AUDITORIA.t_novedades_cliente g 
								where ca.id_casino(+)=g.id_casino_novedad
								$condicion_casino
								and cl.id_cliente=?
								and g.fecha_novedad=to_date(?,'dd/mm/yyyy')", array($apostador, $fechita));
	
	
	/*select direccion, cuenta_bancaria
								from PLA_AUDITORIA.t_info_direcciones
								where suc_ban =?", array($_SESSION['suc_ban']));*/
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
		$row_apostador =$rs_apostador->FetchNextObject($toupper=true);
		$datos=utf8_decode($row_apostador->DATOS);
		$datos1=utf8_decode($row_apostador->DATOS1);



?>	
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<?php 
//$db->debug=true;
//echo('ENTRA');
try {
$rs_detalle = $db ->Execute("select g.id_novedad,to_char(g.fecha_NOVEDAD,'dd/mm/yyyy')fecha_alta,g.fichaje fichaje,g.acierto acierto,
									decode(g.cheque_nro,NULL,'Efectivo','Ch.Nro.' || g.cheque_nro) as cheque,
									decode(g.confirmado,NULL,'SIN CONFIRMAR','CONFIRMADO') AS CONFIRMADO,
									us.descripcion as descripcion,
								  g.mon_ing_fic as fichaje_ingreso,
								  g.mon_fic_ret as fichaje_retiro,
								  g.mon_perdido as perdido,
								  g.observa_mov as observacion,
										(select uu.descripcion
										  from superusuario.usuarios uu,
										  PLA_AUDITORIA.t_novedades_cliente nov
										  where uu.id_usuario=nov.usuario_conforma
										  and nov.id_novedad=g.id_novedad) as usuario_conforma
							    from PLA_AUDITORIA.t_novedades_cliente g,
								superusuario.usuarios us        
								where 1=1
								and us.id_usuario=g.usuario								
								$condicion_ganador
								$condicion_casino
								and g.usuario_baja is null
								--and (G.fichaje<>0 or G.acierto<>0)
								and g.fecha_novedad=to_date(?,'dd/mm/yyyy')", array($fechita));}
								catch (exception $e){die ($db->ErrorMsg());} 
	
	
	
	
//$_SESSION['nro_pagina']=$_pagi_actual ;

	?>
<style type="text/css">
<!--
.Estilo1 {color: #000000}
-->
</style>

<span class="td4">
<?php }?>
</span>
<table width="99%" border="0" align="center"> 
  <tr>
	<td colspan="10" align="center" valign="bottom" class="texto4" scope="col">Movimientos Pertenecientes a <?php echo $datos.'<br>'.$datos1?> - [Datos Resguardados]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.open('list1/mov_apostadores_detalle.php?id_cliente=<?php echo $apostador ?>&casino=<?php echo $casino;?>&fechita=<?php echo $fechita ?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>
    <?php if($_SESSION['rol'.'1']<>'ROL_LAVADO_DINERO_CASINO_CARGA')
	{
	?>
    <!--<td width="14%" align="center" valign="bottom" class="texto4" scope="col"><img src="image/s_okay.png" alt="Nuevo Apostador" width="16" height="16" /> <a href="#" onclick="ajax_get('contenido','cliente/movimiento_confirmar.php','fechita=<?php// echo $fechita ?>&casino=<?php// echo $casino;?>&apostador=<?php// echo $apostador ?>');">Conformacion Masiva</a></td>-->
<?php
	}
	else
	{
	?>
    	<td width="2%" align="center" valign="bottom" class="texto4" scope="col">&nbsp;</td>
	<?php }?>
</tr>
     
    
<tr align="center">
<!--<td  width="28%" class="div_carga" scope="col">&nbsp;</td>-->
<td  width="18%" class="td4" scope="col">Registrado Por</td>
    <td width="4%" class="td4" scope="col">Fichaje</td>
    <td width="5%" class="td4" scope="col">Aciertos</td>
    <td width="5%" class="td4" scope="col">F. Pago</td>  
    <td width="7%" class="td4" scope="col">Fic_Ingreso</td>
    <td width="6%" class="td4" scope="col">Fic_Retiro</td>
    <td width="4%" class="td4" scope="col">Perdido</td>
    <td width="10%" class="td4" scope="col">Observaciones</td>
    <td width="13%" class="td4" scope="col">Conformado Por</td>
    <?php if($_SESSION['rol'.'1']<>'ROL_LAVADO_DINERO_CASINO_CARGA')
	{
	?> 
    <!--<td width="6%" class="td4" scope="col">Conformar</td>-->
    <?php
	}
	?>
    <!--<td width="14%" class="td4" scope="col">Eliminar</td>-->
    
  </tr>
       <?php while ($row = $rs_detalle->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($rs_detalle->CurrentRow()%2) { echo "td";}  else {echo "td8";}?>">
         <!--<td  width="28%" style="background-color:#FFFFFF">&nbsp;</td> -->
          <td  width="20%" align="left"><?php echo $row->DESCRIPCION;?></td> 
         <td width="4%" align="right"><?php echo number_format($row->FICHAJE,2,',','.');?></td>
         <td width="5%" align="right"><?php echo number_format($row->ACIERTO,2,',','.');?></td>
         <td width="5%" align="left"><?php echo $row->CHEQUE;?></td>
<?php
	
	
	$totfichaje=$totfichaje+$row->FICHAJE;
	$totacierto=$totacierto+$row->ACIERTO;
	$totfic_ing=$totfic_ing+$row->FICHAJE_INGRESO;
	$totfic_ret=$totfic_ret+$row->FICHAJE_RETIRO;
	$perdido=$perdido+$row->PERDIDO;
	?>
    <td width="7%" align="right"><?php echo number_format($row->FICHAJE_INGRESO,2,',','.');?></td>
		 <td width="6%" align="right"><?php echo number_format($row->FICHAJE_RETIRO,2,',','.');?></td>
         <td width="4%" align="right"><?php echo number_format($row->PERDIDO,2,',','.');?></td>
         <td width="10%" align="left"><?php echo $row->OBSERVACION;?></td>
         <td width="13%" align="LEFT"><?php echo $row->USUARIO_CONFORMA;?></td>
<?php


									  try {
									$rs_busca_id_novedad = $db ->Execute("select confirmado
												from PLA_AUDITORIA.t_novedades_cliente
												where id_novedad=?",array($row->ID_NOVEDAD));
											}									catch (exception $e){die ($db->ErrorMsg());} 
									$row_busca_id_novedad =$rs_busca_id_novedad->FetchNextObject($toupper=true);
									if($rs_busca_id_novedad->RowCount()<>0)
									{
									$novedad=$row_busca_id_novedad->CONFIRMADO;
									}
									/*else
									{
									$novedad='N';
									}*/
									if($row->ID_NOVEDAD==NULL)
									{
									?>
										<!--<td align="center" >S/M</td>
										<td align="center" >S/M</td>-->
									<?php 
									}
									else
									{
					
											if($novedad==NULL)
											{
											 if($_SESSION['rol'.'1']<>'ROL_LAVADO_DINERO_CASINO_CARGA')
												{	
											 ?>
											<!--<td width="2%" align="center" ><a href="#" onclick="ajax_get('contenido','cliente/movimiento_confirmar_unico.php','id_novedad=<?php// echo $row->ID_NOVEDAD;?>&fechita=<?php// echo $fechita;?>&casino=<?php// echo $casino;?>&apostador=<?php// echo $apostador;?>');return false;"><img src="image/C_Checkmark_md.png" alt="Confirma este movimiento"  width="20" height="20" border="0"/></a></td>-->
<?php }?>
                                            
                                                                           
                                            
                                            
		 									<!--<td width="2%" align="center" ><a href="#" onClick="ajax_get('elimina','cliente/controla_eliminacion_novedad.php','id_novedad=<?php// echo $row->ID_NOVEDAD;?>&fechita=<?php// echo $fechita ?>&casino=<?php// echo $casino;?>&apostador=<?php// echo $apostador ?>');return false;"><img src="image/roseta_ok.png" alt="Elimina" width="20" height="20" border="0"/></a></td>-->
<?php
											 }
											 else
											 {
											 ?>
											 <!--<td width="2%" align="center" ><img src="image/candado.png" alt="Movimiento Conformado" width="20" height="20" border="0"/></td>
                                            
		 <td width="2%" align="center" ><img src="image/candado.png" alt="Movimiento Conformado" width="20" height="20" border="0"/></td>-->
		 <?php
											 }
									}
											 ?>
       <!--<td align="center" ><a href="#" onClick="ajax_get('contenido','cliente/cliente_eliminar_grabar.php','id_cliente=<?php// echo $row->ID_CLIENTE;?>&casino=<?php// echo $casino ; ?>'); return false;">
								<img src="image/roseta_ok.png" alt="Activa" width="20" height="20" border="0" /></a></td>-->
                                     
                                     
            <?php if($row->ID_NOVEDAD==NULL)
									{
									?>
										<!--<td width="3%" align="center" >S/M</td>-->
										
	 <?php 
									}
									else
									{?>
             
  <?php }?>
          
                                             
          
          
  <!--<td align="center" ><?php// echo $row->OBSERVACION?></td>--></tr>

<?php   }?>
<tr>
          <td  align="center" valign="bottom" class="texto4" scope="col">Totales========></td>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totfichaje,2,',','.');?></td>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totacierto,2,',','.');?></td>
         <td  align="center" valign="bottom" class="texto4" >&nbsp;</td>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totfic_ing,2,',','.');?></td>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totfic_ret,2,',','.');?></td>
           <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($perdido,2,',','.');?></td>
         
    		</tr><tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina">&nbsp;</div></td>
    </tr>
      	  	<tr align="center"><td align="center" scope="row" colspan="9"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/adm_novedad.php','casino=<?php echo $casino;?>&apostador=<?php echo $apostador;?>')">Retornar</a></div></td></tr>
</table>

