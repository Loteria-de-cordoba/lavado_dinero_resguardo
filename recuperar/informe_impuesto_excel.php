<?php session_start();
include("../../db_conecta.inc.php");
//include("../db_conecta.inc.php");
 include("../../funcion.inc.php");
 include('../../jscalendar-1.0/calendario.php'); 
 $controlabusqueda=0;
// include('../funcionesfrancos.php'); 
//$DB->debug=true; 
//print_r ($_POST);
//print_r($_GET);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=excel.xls");


//$DB->debug = true;

$id_empleado = validarAsignarParametros('id_empleado','1');
$periodo = validarAsignarParametros('periodo','1');
$mes = validarAsignarParametros('mes','1');

$planta = validarAsignarParametros('planta','1');
$legajo = validarAsignarParametros('legajo','1');

$sql_legajo = '';
if($legajo){
	$sql_legajo = ' and LEGAJO = ' . $legajo;
}

$sql_planta = '';
if($planta){
	$sql_planta = ' and ID_PLANTA = ' . $planta;
}


try { 
    $excel = $DB->Execute("
SELECT 
	ID_EMPLEADO,
	FECHA_PAGO,
	PERIODO,
	MES,
	ID_PLANTA,
	LPAD(TO_NUMBER(LEGAJO),5,'0') AS LEGAJO,
	DESC_EMPLEADO,
	GAN_BRUTA_HAB_MES,
	GAN_BRUTA_TOTAL_MES,
	JUBILACION,
	OBRA_SOCIAL,
	SEGURO_VIDA,
	GASTOS_SEPELIO,
	CORREDORES,
	DONACIONES,
	AD_OBRASOCIAL,
	CUOTA_MED_ASIS,
	HON_MEDICOS,
	OTROS_DESCLEY,
	SINDICATO,
	SERV_DOMESTICO,
	PRESTAMOS_HIP,
	
	GAN_NO_REMU,
	GAN_NETA,
	
	GAN_NETA_MENSUAL,
	GAN_MESES_ANT,
	GAN_ACUM,
	GAN_NO_IMP,
	DED_ESPECIAL,
	DED_CONYUGE,
	DED_HIJO,
	OTRAS_CARGAS,
	GAN_IMP_ACUM,
	MONTO_FIJO,
	PORCENTAJE,
	RET_ACUM,
	RET_PRACTICADAS,
	RETENCION_ACTUAL,
	APLICA
FROM 
	rrhh.T_ACUM_IMPUESTO 
WHERE 
	PERIODO = ?
	and MES = ?
	
	$sql_planta
	$sql_legajo
	
ORDER BY 
	desc_empleado asc
	",array($periodo,$mes));
} catch (exception $e) {    
    die ($DB->ErrorMsg()); 
} 

		

?>
<form  name="form1" method="post" action="#" onsubmit="ajax_post('contenido','calculo/informe_impuesto_ganancias.php',this); return false;">
   <table width="71%"  border="0" align="center">
     <tr>
       <td colspan="4" align="center"><div align="Center" class="tdVerde">
  <!--<h2><Strong>Licencias Otorgadas en el Periodo <?php// echo substr($fecha,6)?></Strong></h2>-->
  <h2><Strong>Informe Impuesto a las Ganancias<Br />Periodo Fiscal   <?php echo $periodo.'/'.str_pad(trim($mes),2,'0',STR_PAD_LEFT);?></Strong></h2>
  </div>  </td>
     </tr>
     
  </table>


<table width=71% border="2" align="center" cellspacing="1">
  
  <tr align="center" class="th2">
     <td width="71" align="center">Periodo</td>
     <td width="88" align="center">Legajo</td>
	 <td width="235" align="center" >Nombre</td>      
     <td width="104">Ret. Actual</td>      
    </tr>
  <?php 
//$ll=$_pagi_result->RowCount();
  
  while ($row = $excel->FetchNextObject($toupper=true))
   { ?>
        <tr class="td">
      	<td align="center" valign="middle" class="td2"><?php echo $row->PERIODO.'/'.str_pad(trim($row->MES),2,'0',STR_PAD_LEFT);?></td>
        <td align="center" valign="middle" class="td2"><?php echo str_pad(trim($row->LEGAJO),5,'0',STR_PAD_LEFT); ?></td>
        <td align="left" valign="middle" class="td2"><?php echo $row->DESC_EMPLEADO; //$row->ID_EMPLEADO . ' - ' . ?></td>
        <td align="right" valign="middle" class="td2"><?php echo number_format($row->RETENCION_ACTUAL,'2',',',''); ?></td>
       
      </tr>
   <?php
	} ?>        
   </table>
</form>

<?php if($excel->RowCount()==0)
{
$controlabusqueda=1;
?>

<table width="100%" border="2" align="center" cellspacing="0">

  <tr>
    <td align="center" CLASS="BarraMenu"><strong>
    EL PERSONAL SOLICITADO NO EXISTE O NO TIENE DEDUCCIONES ASIGNADAS PARA EL PERIODO <?php echo $per_control;?>
   
   </strong></td>
    </tr>
    
    </table>
    <br />
    <br />

<?php }?>

