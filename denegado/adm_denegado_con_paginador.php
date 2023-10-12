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
//print_r($_POST);
//print_r($_GET);
$habilitado=0;
$i=0;
 $caorigen='';
 $myarea='';
 $ccuenta=0;
 $bandera=0;

//combo sexo
try {
 $rs_sexo = $db ->Execute("SELECT id_sexo as codigo, descripcion as descripcion 
 							FROM lavado_dinero.sexo	
							ORDER BY ID_SEXO				
					");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}

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


//selecciono mi area con rol op_unico
try {
			$rs_myarea = $db ->Execute("SELECT us.area_id_principal as area  FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99 or area_id_principal=4 OR AREA_ID_PRINCIPAL=32)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_myarea =$rs_myarea->FetchNextObject($toupper=true);
			$myarea=$row_myarea->AREA; 
//echo $myarea;

//OJO VOY A HARDCODEAR LO QUE VIENE
//selecciono los casineros con rol op_unico
try {
			$rs_usuario = $db ->Execute("SELECT count(*) as cuenta FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}
			$row_usuario =$rs_usuario->FetchNextObject($toupper=true);
			$ccuenta=$row_usuario->CUENTA; 
			


while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	//echo $_SESSION['rol'.$i];
	//Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
	//if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
//si es primera vez que entro en esta sesion
if($_SESSION['repocasino']==0)
{
//EJERZO AUDITORIA
	 $describa='Siendo las '.$serhora.' horas,  El Agente '.$auditado.' consulta Personas Sospechadas de Lavado de Activos';
 
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
	$_SESSION['repocasino']=1;
}
	
		
//echo $habilitado;
//die();
$j=0;
$casino=0;
$casino_setea=0;
$area='';
$area=$_SESSION['area'];
$totfichaje=0;
$totacierto=0;
$totinfichaje=0;
$totinacierto=0;

	if (isset($_REQUEST['idid'])&& $_REQUEST['idid']<>'' ) {
			$idid = $_REQUEST['idid'];
			$condicion_idid="and b.id_denegado=$idid";
		}  else {
						$idid = '';
						$condicion_idid="";
				}	

	if (isset($_REQUEST['apenom'])&& $_REQUEST['apenom']<>'' ) {
			$apenom = $_REQUEST['apenom'];
			$condicion_apenom="and b.descripcion like '%$apenom%'";
		}  else {
						$apenom = '';
						$condicion_apenom="";
		}
	
	if (isset($_REQUEST['sexo'])&& $_REQUEST['sexo']<>0) {
			$sexo = $_REQUEST['sexo'];
			$condicion_sexo="and b.sexo=$sexo";
		}  else {
						$sexo = '';
						$condicion_sexo="";
		}
		
	if (isset($_REQUEST['docu'])&& $_REQUEST['docu']<>'' ) {
			$docu = strtoupper(md5($_REQUEST['docu']));
			$docu1=$_REQUEST['docu'];
			$condicion_docu="and b.documento like '$docu'";
		}  else {
						$docu = '';
						$docu1='';
						$condicion_docu="";
		}

//echo $docu;
while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	//if ((($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||$_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC')|| ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO'))	{$habilitado=1;} 
	//Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
	//if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
//print_r($_GET);
//print_r($_POST);
//$db->debug=true;
//echo $suc_ban.'sucban';
$array_fecha = FechaServer();
//echo $array_fecha;
//die();	

while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;
	//echo $j.".....".$_SESSION['rol'.$j];
	if($j==1)
	{
	//obtengo fecha de consulta
	try {
			$rs_fechita = $db ->Execute("select substr(to_char(sysdate,'dd/mm/yyyy hh:mm:ss'),12,2) as HORA from dual");
			}
		catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			}	
		
		$row_fechita =$rs_fechita->FetchNextObject($toupper=true);
		$hora=$row_fechita->HORA;
		//$hora='15';
		
		
		
		if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}
		 	else {if (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}
					 else {	
						 //SI HORA ESTA ENRTRE 0 Y 14 TOMA FECHA DEL DIA
						//DE LO CONTRARIO FECHA POSTERIOR
						//if($hora>0 and $hora<15)
							 //{
									$fecha=str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
							 //}
						 //else
							 //{
									//$fecha=str_pad($array_fecha["mday"]+1,2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
							 //}
						 }
				 	}



	
	


?>	
<script language="javascript" src="../funcion2.js"></script>
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css" />

<?php 
//if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CONFORMA' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_SIN_CC' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADM_CASINO' ||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OP_UNICO'||$_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_OPERADOR') {
//$db->debug=true;
//echo('ENTRA');

$_pagi_sql ="SELECT b.id_denegado idid,
				B.USUARIO as usu,
				us.descripcion as usuario,
					to_char(b.fecha_alta,'dd/mm/yyyy') fecha,
				  decode(b.descripcion,null,'Innominado',b.descripcion) nombre,
				  b.cuit cuit,
				  b.documento documento,
				  b.sexo id_sexo,
				  sexo.descripcion as sexo,
				  decode(b.novedad,null,'Sin Novedad',b.novedad) novedad				  	 
				FROM lavado_dinero.denegado b,
					SUPERUSUARIO.USUARIOs US,
					lavado_dinero.sexo sexo
				   WHERE b.usuario=us.id_usuario
				   			and b.sexo=sexo.id_sexo
				   			$condicion_apenom 
							$condicion_docu	
							$condicion_sexo	
							$condicion_idid
				
				order by 2";


$_pagi_div = "contenido";
$_pagi_enlace = "denegado/adm_denegado.php";
$_pagi_cuantos = 10; //OPCIONAL. Entero. Cantidad de registros que contendrá como máximo cada página. Por defecto está en 20.
$_pagi_conteo_alternativo=true;//OPCIONAL Booleano. Define si se utiliza mysql_num_rows() (true) o COUNT(*) (false). Por defecto está en false.
$_pagi_nav_num_enlaces=10;//OPCIONAL Entero. Cantidad de enlaces a los números de página que se mostrarán como máximo en la barra de navegación.
$_pagi_nav_estilo="small_navegacion";//OPCIONAL Cadena. Contiene el nombre del estilo CSS para los enlaces de paginación. Por defecto no se especifica estilo.
//$_pagi_propagar[0]='cod_juego';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
//$_pagi_propagar[0]='suc_ban';//OPCIONAL Array de cadenas. Contiene los nombres de las variables que se quiere propagar por el url. Por defecto se propagarán todas las que ya vengan por el url (GET).
$_pagi_propagar[0]='fecha';
//$_pagi_propagar[1]='fhasta';
//$_pagi_propagar[1]='casino';
//$_pagi_propagar[3]='apostador';

 
if(basename($_SERVER['PHP_SELF'])=='index.php'){ 
	include("paginator_adodb_oracle.inc.php");
} else {
		include("../paginator_adodb_oracle.inc.php");
		}	
	
	
	
$_SESSION['nro_pagina']=$_pagi_actual;

	?>
<style type="text/css">
<!--
.Estilo1 {color: #000000}
-->
</style>
<form id="novedad" name="novedad" action="#" onsubmit="ajax_post('contenido','denegado/adm_denegado.php',this); return false;">
<table width="70%"  align="center">
<tr>
	<td colspan="8" align="center" valign="bottom" class="texto4" scope="col">Administracion de Cedulas U.I.F.</td>
</tr>
<!--<tr>
	<td colspan="8" align="center" valign="bottom" class="td8max" scope="col"><?php// if(substr(strtolower($soydeaca),0,6)=='casino'){ echo $soydeaca;} else {armar_combo($rs_casino,"casino",$casino);}?></a></td>
</tr>-->
  <tr valign="bottom" class="td8" >
    <td align="right" colspan="7" valign="middle" class="td2"  scope="col" style="text-align:right; background-color:#FFFFFF">&nbsp;</td>
      <td align="right"  valign="middle" class="td2"  scope="col" style="text-align:right">Fecha:&nbsp;&nbsp;<?php  echo $fecha;?></td> 
   <tr>
    <tr valign="bottom" class="td8" >
      <?php if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA') {?>
   	 <?php }?>
     
     <td     width="53" align="center" class="td2">Filtros:</td>
     <!--<td     width="91" align="center" class="td2">Apellido y Nombre</td>
     <td width="177" align="center" class="td2"><input type="text" name="apenom" id="apenom"/></td>-->
     <td     width="91" align="center" class="td2">Sexo</td>
     <td width="177" align="center" class="td2"><?php armar_combo_todos($rs_sexo,'sexo',$sexo);?></td>
     <td     width="96" align="center" class="td2">Nro de Documento</td>
     <td width="165" align="center" class="td2"><input type="text" name="docu" id="docu" onblur="if(this.value.length!=8) {var alerta='Solo ocho digitos '; alert(alerta);this.value='';return false;}" style="text-align:center;" value="<?php echo $docu1;?>"/></td>
     
     <td width="33" align="center" class="td2" ><a href="#" onClick="ajax_get('contenido','denegado/agregar_denegado.php','fecha=<?php echo $fecha;?>','Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/24px-Crystal_Clear_action_db_add.png" alt="Agregar Cedula" width="20" height="20" border="0"/></a></td>
     <!--<td width="26" align="center" class="td2" ><a href="#" onClick="ajax_get('contenido','denegado/observagral.php','casino=<?php if(substr(strtolower($soydeaca),0,6)=='casino'){echo $casino;} else {?>'+novedad.casino.value+'<?php }?>&fecha='+novedad.fecha.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/observa.jpg" alt="Observacion General" width="20" height="20" border="0"/></a></td>
   	 <td width="26" align="center" class="td2" ><a href="#" onClick="ajax_get('abredia','denegado/controla_abrir_dia.php','casino=<?php if(substr(strtolower($soydeaca),0,6)=='casino'){echo $casino;} else {?>'+novedad.casino.value+'<?php }?>&fecha='+novedad.fecha.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/puerta.jpg" alt="Abrir Jornada" width="20" height="20" border="0"/></a></td>
     <td width="26" align="center" class="td2"><a href="#" onclick="ajax_get('contenido','denegado/cerrar_dia_casino.php','casino=<?php if(substr(strtolower($soydeaca),0,6)=='casino'){echo $casino;} else {?>'+novedad.casino.value+'<?php }?>&fecha='+novedad.fecha.value); return false;"><img src="image/cierrajornada.JPG" border="0" alt="Cerrar Jornada" width="20" height="20" /></a></td>-->
     <td width="33" align="center" class="td2" ><a href="#" onClick="window.open('list1/mov_detalle_denegado.php?docu='+novedad.docu.value+'&fechita=<?php echo $fecha;?>&sexo='+novedad.sexo.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>
     <!--<td align="center" class="td2" scope="col" ><a href="#" onclick="window.open('list1/datos_apostadores_todos.php?casino='+premio.casino.value+'&fecha='+premio.fecha.value+'&fhasta='+premio.fhasta.value,'Ventana','width=400,height=400,top=0,Left=0,menubar=no,scrollbars=yes,resizable=yes')"><img src="image/printer.png" alt="Ver Reporte" width="20" height="20" border="0" /></a></td>-->
      <input type="hidden" name="fhasta_consulta" id="fhasta_consulta" value="<?php echo $fhasta_consulta; ?>" />
     <td width="72" class="td2" scope="col" align="right"> <input type="submit" value="Consultar" /></td>
    </tr>
    <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="abredia">&nbsp;</div></td>
    </tr>
</table>
</form>
<?php if ($_pagi_result->RowCount()==0) {
	
		die("<br><div align=\"center\"><span class=\"textoRojo\">NO EXISTEN CEDULAS</span> <a href=\"#\" class=\"Estilo3\" onclick=\"ajax_get('contenido','blanco.php','')\">Cerrar</a></div>");
}
?>
<span class="td4">
<?php }?>
</span>
<table width="97%" border="0" align="center"> 
<tr>
<?php if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA') {?>
              <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    
   
    </tr>
    <tr align="center" class="td2">
    <td width="17%"   class="td4" scope="col">Usuario_Alta</td>
    <td width="8%"   class="td4" scope="col">Fecha_Alta</td>
     <!--<td width="19%"   class="td4" scope="col">Apellido y Nombre</td>-->
      <td width="11%"  class="td4" scope="col">Cuit [Encriptado]</td>
       <td width="8%"  class="td4" scope="col">Sexo</td>
      <!--<td width="11%"   class="td4" scope="col">Nro de documento</td>-->
      
      <td width="13%"   class="td4" scope="col">Novedad</td>
      <td width="17%"   class="td4" scope="col">Situaci&oacute;n en <br />
        Base de Datos</td>
      <td  class="td4" colspan="2">Acci&oacute;n</td>
      <!--<td width="4%"  class="td4" scope="col">Anular</td>-->
  </tr>
  	 <?php }?>
       <?php while ($row = $_pagi_result->FetchNextObject($toupper=true)){?>
	   <tr class="<?php if ($_pagi_result->CurrentRow()%2) { echo "td";}  else {echo "td8";}
	   
	   //CONTROL DE EXISTENCIA CUIT en base de datos

//ComenzarTransaccion($db);	
						
//FinalizarTransaccion($db);	   
	   ?>">
           <td  align="left"><?php echo $row->USUARIO;?></td>
           	 <td  align="center"><?php echo $row->FECHA;?></td>
			<!--<td align="left"><?php 
		   
		  			 //echo utf8_decode($row->NOMBRE);
		   ?></td>-->
           <td  align="left"><?php
		   
		   try {
						$rs_controlcuit=$db->Execute("select lavado_dinero.check_CUIT_esquema(?) as controcuit from dual",
							  array(substr($row->CUIT,0,32)));
							}
							catch  (exception $e) 
					{ 
					die($db->ErrorMsg());
					}
					
		$row_controlcuit =$rs_controlcuit->FetchNextObject($toupper=true);
		$controcuit=$row_controlcuit->CONTROCUIT;
		   
		    echo $row->CUIT;?></td>
           <td  align="left"><?php echo $row->SEXO;?></td>
           
           <td  align="left"><textarea name="observamov" class="small"  id="observamov"  rows="2" cols="20" readonly="readonly"/><?php echo $row->NOVEDAD;?></textarea></td>
          
          <td  align="left"><?php echo $controcuit;?></td>
          
            <td width="3%" align="center"><a href="#" onclick="ajax_get('contenido','denegado/modificar_denegado.php','id_id=<?php echo $row->IDID;?>&fecha=<?php echo $fecha;?>');return false;"><img src="image/modificar.png" alt="Modifica esta cedula?" width="20" height="20" border="0"/></a></td>
         <td width="4%" align="center"><a href="#" onclick="ajax_get('elimina','denegado/controla_eliminacion_denegado.php','idid=<?php echo $row->IDID;?>&fecha=<?php echo $fecha;?>');return false;"><img src="image/roseta_ok.png" alt="Anula esta Cedula?" width="20" height="20" border="0"/></a></td>
           
  </tr>
         
			
           
         
<?php 

//$totfichaje=$totfichaje+$row->FICHAJE;
//$totacierto=$totacierto+$row->ACIERTO;
}

?>
	<?php if ($_SESSION['rol'.$j]=='ROL_LAVADO_DINERO_ADMINISTRA') {?>	
		 <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><?php echo $_pagi_navegacion ."   ".$_pagi_info ?></td>
    </tr>
    <tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina">&nbsp;</div></td>
    </tr>
    
      	  	<tr align="center"><td align="center" scope="row" colspan="9"><div align="right"><img src="image/24px-Crystal_Clear_action_reload.png" alt="Cerrar" width="16" height="16" border="0" align="absbottom" /> <a href="#" class="small" onclick="ajax_get('contenido','cliente/blanco.php','')">Cerrar</a></div></td></tr>
            <?php }?>
</table>

<?php
}//fin de rol administra
} $_SESSION['sqlreporte']= $_pagi_sql; 

/*if(isset($_GET['casino']) and $_GET['casino']<>10000)
{?>
	<tr>
          <td colspan="9" align="center" valign="bottom" class="smallRojo" scope="col"><div id="elimina"><?php include"agregar_novedad.php"?></div></td>
    </tr>
<?php }*/



}//fin de habilitado?>
