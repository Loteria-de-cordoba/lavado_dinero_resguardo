<?php session_start();
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=excel.xls");
//print_r($_POST);
//print_r($_GET);
//die('entre');

//$db->debug=true;
//print_r($_POST);
//print_r($_GET);
$habilitado=0;
$i=0;
 $caorigen='';
 $myarea='';
 $ccuenta=0;

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
//selecciono los casineros con rol op_unico
try {
			$rs_usuario = $db ->Execute("SELECT count(*) as cuenta FROM 
					SUPERUSUARIO.USUARIOS US
					WHERE (us.area_id_principal between 80 and 99)
					AND SUBSTR(US.ID_USUARIO,3)=?
					ORDER BY US.DESCRIPCION
					", array($_SESSION['usuario']));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
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
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CASINO_CARGA' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
	//if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
}
if ($habilitado==0){
die('<br><div align="center"><span class="textoRojo">NO TIENE ACCESO!</span></div>');
} 
else
{
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
if(substr($area,0,6)=='Casino')
{
	try {
			$rs_setea_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_setea_casino =$rs_setea_casino->FetchNextObject($toupper=true);
	if($rs_setea_casino->RecordCount()<>0)
		{
			$casino_setea=$row_setea_casino->CODIGO;
		}
		
}
else
		{
			$casino_setea=100;
		}
//echo $casino_setea;
//die();
if(substr($area,0,6)=='Casino' and !isset($_POST['casino']) and !isset($_GET['casino']))
{
	try {
			$rs_busca_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
												where substr(n_casino,7,8)=substr('$area',7,8)
                    							and id_casino not in(2,13)");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	$row_busca_casino =$rs_busca_casino->FetchNextObject($toupper=true);
	$casino=$row_busca_casino->CODIGO;
	$casino=$row_busca_casino->CODIGO;
	$condicion_conforma="and a.id_casino_novedad ='$casino'";
	$soydeaca=$row_busca_casino->DESCRIPCION;

}
else
{
$soydeaca=$area;
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
if (isset($_POST['casino']))
		{
			$casino = $_POST['casino'];
			if($casino<>0)
			{
			$condicion_conforma="and a.id_casino_novedad ='$casino'";
			}
			else
			{
				$condicion_conforma='';
			}
		} 
		else
		{
		if(isset($_GET['casino']))
		 {
					$casino = $_GET['casino'];
					if($casino<>0)
			{
					$condicion_conforma="and a.id_casino_novedad ='$casino'";
			}
			else
			{
				$condicion_conforma='';
			}	
		 }
		 else
		 {
		 			$casino = 100;
					$condicion_conforma="and a.id_casino_novedad ='$casino'";
		 }
		} 
}


//seteo casino
if($casino_setea==100)
{
try {
			$rs_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino not in(2,13)
										union 
										select 100 as codigo,
										'No pertenece a Casino' as descripcion
										from dual
										--order by codigo desc");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 
}
else
{
			try {
			$rs_casino = $db ->Execute("select id_casino as codigo, n_casino as descripcion from casino.t_casinos
										where id_casino=?
										",array($casino_setea));
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 
}

try {
			$rs_apostador = $db ->Execute("select codigo, descripcion
										from(
													select apellido || decode(nombre,'','',', ' || nombre) as descripcion, max(id_cliente) as codigo
													from PLA_AUDITORIA.t_cliente
													where fecha_baja is null
													group by apellido || decode(nombre,'','',', ' || nombre)
													--and id_casino=$casino
													order by descripcion)");
			}							//select suc_ban as codigo, nombre as descripcion from juegos.sucursal where suc_ban in (1,20,21,22,23,24,25,26,27,30,31,32,33,60,61,62,63,64,65,66,67,68)
			catch (exception $e)
			{
			die ($db->ErrorMsg()); 
			} 

while ($i<$_SESSION['cantidadroles'])  {

	$i=$i+1;
	//print_r($_SESSION['rol'.$i]);
	
	//if ((($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OPERADOR')||$_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CONFORMA') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_SIN_CC')|| ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADM_CASINO') || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO'))	{$habilitado=1;} 
	//Por pedido de Liliana se restringe provisoriamente el accso al rol_lavado_dinero_op_unico - cambiar entre dos lineas siguientes
	if (($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_OP_UNICO') ||  ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_CASINO_CARGA' and $ccuenta<>0) || ($_SESSION['rol'.$i]=='ROL_LAVADO_DINERO_ADMINISTRA'))	{$habilitado=1;} 
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

while ($j<$_SESSION['cantidadroles'])  {
	$j=$j+1;
	
		
		if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}
		 	else {	if (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}
					 else {	$fecha = '01/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
				 }
		
		if (isset($_GET['fhasta'])) {$fhasta = $_GET['fhasta'];
			$dia=substr($_GET['fhasta'],0,2);
			$fhasta_consulta = $dia.substr($_GET['fhasta'],2,8);}
			 else {	if (isset($_POST['fhasta'])) {
			 $dia=substr($_POST['fhasta'],0,2);
			 $dia=$dia+1;
			 $fhasta_consulta=$dia.substr($_POST['fhasta'],2,8);
			 
			 $fhasta = $_POST['fhasta'];
			 }
			 		 else {	$fhasta = str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];
					 $fhasta_consulta = str_pad($array_fecha["mday"]+1,2,'0',STR_PAD_LEFT).'/'.str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).'/'.$array_fecha["year"];}
				  }
		


if (isset($_POST['apostador']))
		{
			if($_POST['apostador']<>0)
			{
			$apostador = $_POST['apostador'];
			$condicion_apostador="and b.id_cliente ='$apostador'";
			}
			else
			{
			$apostador = '0';
			$condicion_apostador="";
			}
		} 
		else
		{
		if(isset($_GET['apostador']))
		 {
		 			if($_GET['apostador']<>0)
					{
					$apostador = $_GET['apostador'];
					$condicion_apostador="and b.id_cliente ='$apostador'";
					}
					else
					{
					$apostador = '0';
					$condicion_apostador="";
					}
		 }
		 else
		 {
		 			$apostador='0';
					$condicion_apostador="";	
		 }
		} 
try {
			$rs_totales = $db ->Execute("SELECT 
				  sum(a.fichaje) fichaje,
				  sum(a.acierto) acierto,
				  SUM(a.mon_ing_fic) ingreso,
				  SUM(a.mon_perdido) perdido					 
				FROM PLA_AUDITORIA.t_novedades_cliente a,
				  PLA_AUDITORIA.t_cliente b,
				  casino.t_casinos c
				WHERE a.id_cliente=b.id_cliente
				AND b.id_casino      =c.id_casino(+)
				$condicion_conforma
				$condicion_apostador
				AND b.fecha_baja    IS NULL 
				--and (a.fichaje<>0 or a.acierto<>0)
				AND A.USUARIO_BAJA IS NULL and
				a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy') 
				");
			}	
	catch (exception $e){die ($db->ErrorMsg());} 
	$row_totales =$rs_totales->FetchNextObject($toupper=true);
	$totinfichaje=$row_totales->FICHAJE;
	$totinacierto=$row_totales->ACIERTO;
	$totingreso=$row_totales->INGRESO;
	$totperdido=$row_totales->PERDIDO;
	


//$db->debug=true;
if($casino<>0)
{
try {
	$rs = $db->Execute("SELECT b.id_cliente,
				 to_char(a.fecha_novedad,'dd/mm/yyyy') AS fecha,
				  max(b.nombre) nombre ,
				  max(b.apellido) apellido,
				  decode(max(b.id_casino),100,'Delegacion',max(c.n_casino)) || ' (Ag. ' || max(substr(us.descripcion,1,20)) || ')' casino,
				  max(b.id_casino) id_casino,
				  max(a.id_casino_novedad) as casinopasa,
				  SUM(a.fichaje) fichaje,
				  SUM(a.acierto) acierto,
				  SUM(a.mon_ing_fic) ingreso,
				  SUM(a.mon_perdido) perdido				 
				FROM PLA_AUDITORIA.t_novedades_cliente a,
				  PLA_AUDITORIA.t_cliente b,
				  casino.t_casinos c,
          		SUPERUSUARIO.USUARIOS US
				WHERE a.id_cliente=b.id_cliente
				AND b.id_casino      =c.id_casino(+)
				  and us.id_usuario=b.usuario
				$condicion_conforma
				$condicion_apostador
				AND b.fecha_baja    IS NULL 
				AND A.USUARIO_BAJA IS NULL and
				a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy') 
				GROUP BY b.id_cliente, a.fecha_novedad
				order by a.fecha_novedad desc");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}
}
else
{
	try {
	$rs = $db->Execute("SELECT
					b.id_cliente,
					TO_CHAR(a.fecha_novedad,'dd/mm/yyyy') AS fecha,
				   a.id_casino_novedad as casinopasa,
					b.nombre nombre ,
					b.apellido apellido,
					DECODE(MAX(a.id_casino_novedad),100,'Delegacion',MAX(substr(c.n_casino,8))) as casinoorigen,
					DECODE(MAX(b.id_casino),100,'Delegacion',MAX(c.n_casino))
					|| ' (Ag. '
					|| MAX(SUBSTR(us.descripcion,1,20))
					|| ')' casino,
					MAX(b.id_casino) id_casino,
					SUM(a.fichaje) fichaje,
					SUM(a.acierto) acierto,
					SUM(a.mon_ing_fic) ingreso,
				  SUM(a.mon_perdido) perdido	
				  FROM PLA_AUDITORIA.t_novedades_cliente a,
					PLA_AUDITORIA.t_cliente b,
					casino.t_casinos c,
					SUPERUSUARIO.USUARIOS US
				  WHERE a.id_cliente=b.id_cliente
				  AND a.id_casino_novedad   =c.id_casino(+)
				  AND us.id_usuario =b.usuario
				  	$condicion_conforma
					$condicion_apostador
				  AND b.fecha_baja IS NULL 
				  AND A.USUARIO_BAJA IS NULL 
				  and a.fecha_novedad between to_date('$fecha','dd/mm/yyyy') and to_date('$fhasta','dd/mm/yyyy')
				  GROUP BY b.id_cliente, a.fecha_novedad, a.id_casino_novedad, b.nombre, b.apellido
				  order by a.id_casino_novedad,a.fecha_novedad desc");
	}
	catch  (exception $e) 
	{ 
	die($db->ErrorMsg());
	}

}
}?>
<table width="95%"  align="center">
<tr>
	<td colspan="10" align="center" valign="bottom" class="texto4" scope="col">Administraci&oacute;n de Movimientos  - <?php echo $soydeaca;?> </a> - [Datos Resguardados]</td>
 </tr>

 </table>
<table width="95%" border="0" align="center">    
   <!-- <tr align="center" class="td2">
    <?php //if($casino==0)
			//{?>
           <td width="4%" class="td4" scope="col">Origen Mov.</td>
  <?php //}?> 
     <td width="5%" class="td4" scope="col">Fec.Movim.</td>
    <td width="20%" class="td4" scope="col">Apellido y Nombre / Apodo</td>
    <td width="7%" class="td4" scope="col">Inscripto en</td>
    <td class="td4" scope="col" colspan="4">Movimientos Registrados<br />
      Fichaje&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aciertos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fic. Ingreso&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perdido</td>
   </tr>-->
   
  <tr align="center" class="td2">
    <?php if($casino==0)
			{?>
           <td width="4%" class="td4" scope="col" rowspan="2">Origen Mov.</td>
  <?php }?> 
     <td width="5%" class="td4" scope="col" rowspan="2">Fec.Movim.</td>
    <td width="20%" class="td4" scope="col" rowspan="2">Apellido y Nombre / Apodo</td>
    <td width="16%" class="td4" scope="col" rowspan="2">Inscripto en</td>
    <td class="td4" scope="col" colspan="4">Movimientos Registrados</td>
   </tr>
   <tr>
   
   <td height="17" align="center" class="td4">Fichaje</td>
   <td class="td4" align="center">Aciertos</td>
   <td class="td4" align="center">Fic. Ingreso</td>
   <td class="td4" align="center">Perdido</td>
   </tr>
   <?php while($row = $rs->FetchNextObject($toupper=true)){?>
	   <tr class="td2">
           <?php if($casino==0)
			{
			//if($caorigen<>$row->CASINOORIGEN)
			//{?>
           <td width="4%" ><?php echo $row->CASINOORIGEN;
		   $caorigen=$row->CASINOORIGEN;?></td>
  <?php //}
		  // else
		   //{?>
		   		<!--<td width="5%" >&nbsp;</td> -->
       <?php //}
	   }?>
           <td align="center"><?php echo $row->FECHA;?></td>
           <td align="left"><?php 
		   if($row->NOMBRE<>'')
		   {
		  			 echo utf8_decode(trim($row->APELLIDO)).', '.utf8_decode($row->NOMBRE);
		   }
		   else
		   {
		   			 echo utf8_decode(trim($row->APELLIDO));
		   }?></td>
           <td width="16%" align="left"><?php echo utf8_decode($row->CASINO);?></td>
           <td width="11%" align="right"><?php echo number_format($row->FICHAJE,2,',','.');?></td>
         <td width="13%" align="right"><?php echo number_format($row->ACIERTO,2,',','.');?></td>
         <td width="20%" align="right"><?php echo number_format($row->INGRESO,2,',','.');?></td>
         <td width="4%" align="right"><?php echo number_format($row->PERDIDO,2,',','.');?></td>
         </tr>
<?PHP    }?>
		
		<tr>
        	 
             <?php if($casino<>0)
			{?>
            <td   colspan="2" align="center" valign="bottom" class="texto4" scope="col">&nbsp;</td>
          <td align="left" valign="bottom" class="texto4" scope="col">Totales===========></td>
          <?php }
		  else
		  {?>
          <td   colspan="3" align="center" valign="bottom" class="texto4" scope="col">&nbsp;</td>
	  	  <td align="left" valign="bottom" class="texto4" scope="col">Totales===========></td>
		  <?php }?>
          <td  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totinfichaje,2,',','.');?></td>
          <td width="2%"  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totinacierto,2,',','.');?></td>
          <td width="2%"  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totingreso,2,',','.');?></td>
          <td width="2%"  align="right" valign="bottom" class="texto4" scope="col"><?php echo number_format($totperdido,2,',','.');?></td>
          <td width="1%"  align="center" valign="bottom" class="texto4" scope="col">&nbsp;</td>
         
  </tr>
           </table>

<?php // echo number_format($row->ACIERTO,2,',','.');?>
<?php } $_SESSION['sqlreporte']= $row; 
}//fin de habilitado?>