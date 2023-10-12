<?php session_start(); 
include("jscalendar-1.0/calendario.php");
include("db_conecta_adodb.inc.php");
include("funcion.inc.php");
//print_r($_SESSION);
//print_r($_SESSION['permiso']);
//$db->debug=true;

//la siguiente variable de sesion es para controlar auditoria
$_SESSION['repocasino']=0;//para reporte diario
$_SESSION['ticket']=0;//para ticket
$_SESSION['ganador']=0;//para consulta de ganadores

try {$rsroles=$db->Execute("select rownum, granted_role
	from user_role_privs                                                                                                                                                          
 	where username = ?
   		and (granted_role like 'ROL_LAVADO_DINERO%'
		or granted_role like 'LAVADO_DINERO%')",array("DU".$_SESSION['usuario']));}
	catch  (exception $e){ die($db->ErrorMsg());}

$_SESSION['cantidadroles']=$rsroles->RecordCount();
	//echo $_SESSION['cantidadroles'];
if ($rsroles->RecordCount()>0) { 
	while($rowroles= $rsroles->FetchNextObject($toupper=true)){
		$_SESSION['rol'. $rowroles->ROWNUM]= $rowroles->GRANTED_ROLE;
		//echo $_SESSION['rol1'];
		}
	} else {
		die("<span class=\"textoRojo\">No tiene acceso al sistema!...</span>");
		}
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Usuario: <?php echo $_SESSION['nombre_usuario']; ?></title>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../librerias/jquery/jquery.js"></script>
<script type="text/javascript" src="upload_ajax/float/js/ajax_upload.js"></script>
<script type="text/javascript" src="jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="tooltip/js/ajax-dynamic-content.js"></script>
<script type="text/javascript" src="tooltip/js/ajax.js"></script>
<script type="text/javascript" src="tooltip/js/ajax-tooltip.js"></script>	


<link rel="stylesheet" type="text/css" media="all" href="jscalendar-1.0/calendar-brown.css" title="summer" />

<link rel="stylesheet" href="upload_ajax/float/css/floating-window.css" media="screen" type="text/css">
<script type="text/javascript" src="upload_ajax/float/js/floating-window.js"></script>

<script language="javascript" src="funcion2.js"></script>
</head>
<body>

<?php 
$i = 0;
while ($i<$_SESSION['cantidadroles'])  {
	$i=$i+1;
	switch($_SESSION['rol'.$i]){
 
//     switch($_SESSION['permiso']) {
		case 'ROL_LAVADO_DINERO_ADMINISTRA': // print_r($_SESSION); ?>
            <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="45" align="left" scope="col"><a href="http://www.loteriadecordoba.com.ar"><img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" /></a> </td>
                    <td width="833" align="left" scope="col"><script type='text/javascript' src='OpenCube/OpenCube4.js'></script></td>
                </tr>
                <tr>
                    <td align="center" >&nbsp;</td>
                    <td align="center" >
                    	<table align="left">
                            <tr class="small">
                                <td align="center"  scope="col"><a href="#">Usuario</a></td>
                                <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                                <td align="left" scope="col"> |</td>
                                <th align="left" scope="col">&nbsp;</th>
                                <td align="center"  scope="col"><a href="#">Delegacion/Casino</a></td>
                                <td align="center"  scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                                <td align="center" >&nbsp;</td>
                        	</tr>
                        </table>
                	</td>
                </tr>
            </table>
            
            <?php   // aca iria el logo?>
			<?php break;  
            
            case 'ROL_LAVADO_DINERO_CEDULA': // print_r($_SESSION); ?>
            <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="45" align="left" scope="col">&nbsp;</td>
                </tr>
            </table>
        
            
			<?php   // aca iria el logo?>
			<?php  break;    
			
		case 'ROL_LAVADO_DINERO_CONSULTA': // print_r($_SESSION); ?>
            <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="45" align="left" scope="col"><a href="http://www.loteriadecordoba.com.ar"><img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" /></a> </td>
                    <td width="833" align="left" scope="col"><script type='text/javascript' src='OpenCube/OpenCube5.js'></script></td>
                </tr>
                <tr>
                    <td align="center" >&nbsp;</td>
                    <td align="center" >
                    	<table align="left">
                            <tr class="small">
                                <td align="center"  scope="col"><a href="#">Usuario</a></td>
                                <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                                <td align="left" scope="col"> |</td>
                                <th align="left" scope="col">&nbsp;</th>
                                <td align="center"  scope="col"><a href="#">Delegacion/Casino</a></td>
                                <td align="center"  scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                                <td align="center" >&nbsp;</td>
                        	</tr>
                        </table>
                	</td>
                </tr>
            </table>
			<?php   // aca iria el logo?>
			<?php break;     
	 case 'ROL_LAVADO_DINERO_OPERADOR': // print_r($_SESSION); ?>
            <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="45" align="left" scope="col"><a href="http://www.loteriadecordoba.com.ar"><img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" /></a> </td>
                    <td width="833" align="left" scope="col"><script type='text/javascript' src='OpenCube/OpenCube6.js'></script></td>
                </tr>
                <tr>
                    <td align="center" >&nbsp;</td>
                    <td align="center" >
                    	<table align="left">
                            <tr class="small">
                                <td align="center"  scope="col"><a href="#">Usuario</a></td>
                                <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                                <td align="left" scope="col"> |</td>
                                <th align="left" scope="col">&nbsp;</th>
                                <td align="center"  scope="col"><a href="#">Delegacion/Casino</a></td>
                                <td align="center"  scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                                <td align="center" >&nbsp;</td>
                        	</tr>
                        </table>
                	</td>
                </tr>
            </table>
			<?php   // aca iria el logo?>
			<?php break;     

        
		case 'ROL_LAVADO_DINERO_CONFORMA': 
			try{$rsrol1=$db->Execute ("SELECT
					CASE
						WHEN TO_CHAR(SYSDATE, 'DD') <= 15 THEN
							TO_DATE('15/' || TO_CHAR(SYSDATE, 'MM/YYYY'), 'DD/MM/YYYY') -
								TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY')
								
							ELSE
								LAST_DAY(SYSDATE) - SYSDATE
							END AS CUANTOSFALTAN
					FROM DUAL");}
				catch (exception $e) { die(MensajeBase($db->ErrorMsg()));}
	
			$row = $rsrol1->FetchNextObject($toupper=true); ?>
     
     			<table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="43" align="left"><a href="http://www.loteriadecordoba.com.ar"><img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" /></a> </td>
                    <td width="835" align="left"><script type='text/javascript' src='OpenCube/OpenCube1.js'></script></td>
                </tr>
                <tr>
                    <td align="center" >&nbsp;</td>
                    <td align="center" ><table align="left">
                    <tr class="small">
                    <td align="center"  scope="col"><a href="#">Usuario</a></td>
                    <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                    <td align="left" scope="col"> |</td>
                    <th align="left" scope="col">&nbsp;</th>
                    <td align="center"  scope="col"><a href="#">Delegacion/Casino</a></td>
                    <td align="center"  scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                    <td align="center" >&nbsp;</td>
                </tr>
            </table>  
        </td>
        </tr>
        </table>
        	<table align="center">
                <tr>
                	<?php if ($row->CUANTOSFALTAN==0){ ?>
                		<td align="center"  scope="col">
                        	<a href="#" onClick="ajax_get('contenido','premio/premios_conformar.php','')" class="texto3">
                            	ATENCION!! HOY DEBE CONFORMAR! 
							</a>
						</td>
                		<?php } else { ?>
							<td align="center" scope="col">
                            	<a href="#" onClick="ajax_get('contenido','premio/premios_conformar.php','')" class="texto3">
                                ATENCION!! Faltan <?php echo $row->CUANTOSFALTAN; ?> dias para CONFORMAR!
                                </a>
                            </td>
                			<?php }?>
                </tr>
            </table> 
		<?php 	break;
		
		case 'ROL_LAVADO_DINERO_OPERADOR': ?>
            <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                	<th width="43" align="left" scope="col">
                    	<a href="http://www.loteriadecordoba.com.ar">
                        <img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" />
                        </a>
                    </th>
	                <td width="835" scope="col"><img src="image/0barra1.jpg" width="1000" height="30" ></td>
                </tr>
                <tr>
                    <td align="center" >&nbsp;</td>
                    <td align="center" >
                    	<table align="left">
                            <tr class="small">
                                <td align="center"  scope="col"><a href="#">Usuario</a></td>
                                <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                                <td align="left" scope="col"> |</td>
                                <th align="left" scope="col">&nbsp;</th>
                                <td align="center"  scope="col"><a href="#">Delegacion/Casino</a></td>
                                <td align="center"  scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                                <td align="center" >&nbsp;</td>
                			</tr>
                		</table>
					</td>
                </tr>
            </table>
			<?php   break;
	
		case 'ROL_LAVADO_DINERO_OP_UNICO' or 'ROL_LAVADO_DINERO_CASINO_CARGA': 
     		try{$rsrol1=$db->Execute ("SELECT
					CASE
						WHEN TO_CHAR(SYSDATE, 'DD') <= 15 THEN
							TO_DATE('15/' || TO_CHAR(SYSDATE, 'MM/YYYY'), 'DD/MM/YYYY') -
								TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY')
								
							ELSE
								LAST_DAY(SYSDATE) - SYSDATE
								END AS CUANTOSFALTAN
								
				FROM DUAL");}
				catch  (exception $e) { die(MensajeBase($db->ErrorMsg()));}
			$row = $rsrol1->FetchNextObject($toupper=true);?>
            <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="43" align="left">
                        <a href="http://www.loteriadecordoba.com.ar">
                        <img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" />
                        </a>
                    </td>
                    <td width="835" align="left"><script type='text/javascript' src='OpenCube/OpenCube2.js'></script></td>
                </tr>
                <tr>
                    <td align="center" >&nbsp;</td>
                    <td align="center" >
                    	<table align="left">
                            <tr class="small">
                                <td align="center"  scope="col"><a href="#">Usuario</a></td>
                                <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                                <td align="left" scope="col"> |</td>
                                <th align="left" scope="col">&nbsp;</th>
                                <td align="center"  scope="col">Delegacion/Casino</td>
                                <td align="center"  scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                                <td align="center" ></td>
                			</tr>
                		</table>
					</td>
                </tr>
            </table>
        	<table align="center">
        		<tr>
        			<?php if ($row->CUANTOSFALTAN==0){ ?>
        				<td align="center"  scope="col"><a href="#" onClick="ajax_get('contenido','premio/premios_conformar.php','')" class="texto3">ATENCION!! HOY DEBE CONFORMAR! </a></td>
						<?php } else { ?>
        					<td align="center" scope="col">
                            	<a href="#" onClick="ajax_get('contenido','premio/premios_conformar.php','')" class="texto3">
                                ATENCION!! Faltan <?php echo $row->CUANTOSFALTAN; ?> dias para CONFORMAR! </a>
							</td>
        					<?php }?>
        		</tr>
        	</table>
			<?php break;      

		case ('ROL_LAVADO_DINERO_ADM_CONFORMA'): // || 'ROL_LAVADO_DINERO_ADM_SIN_CC'): 
     		try{$rsrol1=$db->Execute ("SELECT
					CASE
						WHEN TO_CHAR(SYSDATE, 'DD') <= 15 THEN
							TO_DATE('15/' || TO_CHAR(SYSDATE, 'MM/YYYY'), 'DD/MM/YYYY') -
								TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY')
							ELSE
								LAST_DAY(SYSDATE) - SYSDATE
								END AS CUANTOSFALTAN
								
				FROM DUAL");}
				catch (exception $e) { die(MensajeBase($db->ErrorMsg()));}
			$row = $rsrol1->FetchNextObject($toupper=true);	?>
            <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="43" align="left">
                    	<a href="http://www.loteriadecordoba.com.ar">
                        <img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" />
                        </a> 
					</td>
                    <td width="835" align="left"><script type='text/javascript' src='OpenCube/OpenCube2.js'></script></td>
                </tr>
				<tr>
                    <td align="center" >&nbsp;</td>
                    <td align="center" >
                    	<table align="left">
                            <tr class="small">
                                <td align="center" scope="col">Usuario</td>
                                <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                                <td align="left" scope="col"> |</td>
                                <th align="left" scope="col">&nbsp;</th>
                                <td align="center" scope="col">Delegacion/Casino</td>
                                <td align="center" scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                                <td align="center"></td>
                            </tr>
                    	</table>
					</td>
                </tr>
            </table>
        	<table align="center">
        		<tr>
        			<?php if ($row->CUANTOSFALTAN==0){ ?>
        				<td align="center"  scope="col">
                        	<a href="#" onClick="ajax_get('contenido','premio/premios_conformar.php','')" class="texto3">
                            ATENCION!! HOY DEBE CONFORMAR!
                            </a>
						</td>
						<?php } else { ?>
        					<td align="center" scope="col">
                            	<a href="#" onClick="ajax_get('contenido','premio/premios_conformar.php','')" class="texto3">
                                ATENCION!! Faltan <?php echo $row->CUANTOSFALTAN; ?> dias para CONFORMAR! 
                                </a>
                            </td>
        					<?php } ?>
        		</tr>
        	</table>
			<?php break;            
       
		
		case 'ROL_LAVADO_DINERO_ADM_SIN_CC' || 'LAVADO_DINERO_CONFORMA_TODO': 
	    	try{$rsrol1=$db->Execute ("SELECT
					CASE
						WHEN TO_CHAR(SYSDATE, 'DD') <= 15 THEN
							TO_DATE('15/' || TO_CHAR(SYSDATE, 'MM/YYYY'), 'DD/MM/YYYY') -
								TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY')
								
							ELSE
								LAST_DAY(SYSDATE) - SYSDATE
								END AS CUANTOSFALTAN
								
				FROM DUAL"); }
				catch  (exception $e) { die(MensajeBase($db->ErrorMsg()));}
			$row = $rsrol1->FetchNextObject($toupper=true);	?>
        
        
        
        
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="43" align="left"><a href="http://www.loteriadecordoba.com.ar"><img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" /></a> </td>
		    <td width="835" align="left"><script type='text/javascript' src='OpenCube/OpenCube2.js'></script></td>
          </tr>
          
  		  <tr>
    		<td align="center" >&nbsp;</td>
    	    <td align="center" ><table align="left">
              <tr class="small">
                <td align="center"  scope="col"><a href="#">Usuario</a></td>
                <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                <td align="left" scope="col"> |</td>
                <th align="left" scope="col">&nbsp;</th>
                <td align="center"  scope="col">Delegacion/Casino</td>
                <td align="center"  scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                <td align="center" ></td>
                
              </tr>
              
              
            </table></td>
                                    
  		  </tr>
		</table>
        
        <table align="center">
        <tr>
        <?php if ($row->CUANTOSFALTAN==0){ ?>
        
        <td align="center"  scope="col"><a href="#" onClick="ajax_get('contenido','premio/premios_conformar.php','')" class="texto3">ATENCION!! HOY DEBE CONFORMAR! </a></td>
		
		<?php } else 
		{?>
        <td align="center"  scope="col"><a href="#" onClick="ajax_get('contenido','premio/premios_conformar.php','')" class="texto3">ATENCION!! Faltan <?php echo $row->CUANTOSFALTAN; ?> dias para CONFORMAR! </a></td>
        <?php }?>
        </tr>
        </table>
        
<?php   break;            



	   
	    case 'ROL_LAVADO_DINERO_ADM_CASINO': ?>
        
        
        <?php 
     try{$rsrol1=$db->Execute ("SELECT
								CASE
								WHEN TO_CHAR(SYSDATE, 'DD') <= 15 THEN
								TO_DATE('15/' || TO_CHAR(SYSDATE, 'MM/YYYY'), 'DD/MM/YYYY') -
								TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY')
								
								ELSE
								LAST_DAY(SYSDATE) - SYSDATE
								END AS CUANTOSFALTAN
								
								FROM DUAL");
	}
	catch  (exception $e) 
	{ 
	die(MensajeBase($db->ErrorMsg()));
	}
	
	$row = $rsrol1->FetchNextObject($toupper=true);
	?>
        
        
        
        
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="43" align="left"><a href="http://www.loteriadecordoba.com.ar"><img src="image/loguito.gif" alt="logo_lote" width="39" height="38" border="0" /></a> </td>
		    <td width="835" align="left"><script type='text/javascript' src='OpenCube/OpenCubeAdmCasino.js'></script></td>
          </tr>
          
  		  <tr>
    		<td align="center" >&nbsp;</td>
    	    <td align="center" ><table align="left">
              <tr class="small">
                <td align="center"  scope="col"><a href="#">Usuario</a></td>
                <td align="left" scope="col"><?php echo $_SESSION['nombre_usuario']; ?></td>
                <td align="left" scope="col"> |</td>
                <th align="left" scope="col">&nbsp;</th>
                <td align="center"  scope="col">Delegacion/Casino</td>
                <td align="center"  scope="col"><?php echo $_SESSION['sucursal']; ?></td>
                <td align="center" ></td>
                
              </tr>
              
              
            </table></td>
                                    
  		  </tr>
		</table>
        
        <table align="center">
        <tr>
        <?php if ($row->CUANTOSFALTAN==0){ ?>
        
        <td align="center"  scope="col" class="texto3">ATENCION!! HOY DEBE CONFORMAR!</td>
		
		<?php } else 
		{?>
        <td align="center"  scope="col" class="texto3">ATENCION!! Faltan <?php echo $row->CUANTOSFALTAN; ?> dias para CONFORMAR! </a></td>
        <?php }?>
        </tr>
        </table>
        

        
        
        
           
<?php   break;            
        
        
      }?>  
        
        
        
        
        
        
<div id="carga">
    <table border="0" cellspacing="0">
      <tr>
        <td align="center" class="div_carga" scope="col">Listo </td>
      </tr>
      <tr>
        <td><img src="image/Good.png" width="50" height="50"></td>
      </tr>
</table>
</div>

 
<?php   
}?> <td colspan="3" align="center"  valign="top"><div id="contenido"><img src="image/logo_lavado_resguardo.JPG" border="0" width="70%" height="400"/>

</div>
<script type="text/javascript">
	var id_refrescar_pantalla_alerta = null;

	id_refrescar_pantalla_alerta = setInterval(refrescar_pantalla_alerta, 1800000);
	
	function refrescar_pantalla_alerta(){
		if(document.getElementById('refrescar_pantalla_alerta') != null){
			document.getElementsByName('novedad')[0].onsubmit();
		}
	}
</script>
</body>
</html>

