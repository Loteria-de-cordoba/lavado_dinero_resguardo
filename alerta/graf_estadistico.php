<?php
/*
* formulario de Grafico de
* ROS (Reporte de Operaciones Sospechosas)
* 29/10/2013
* PARODI VICTOR
* 
*/
session_start();
if(basename($_SERVER['PHP_SELF'])=='index.php'){
	include_once("jscalendar-1.0/calendario.php");
	include_once("db_conecta_adodb.inc.php");
	include_once("funcion.inc.php");
	} else {
		include("../jscalendar-1.0/calendario.php");
		include("../db_conecta_adodb.inc.php");
		include("../funcion.inc.php");
		}
		$_SESSION['abrografica']=0;
//print_r($_REQUEST);
//$db->debug = true;
//obtengo EL RECORRIDO
/*try {
$rs_resumen = $db -> Execute("SELECT TT.ID_TIPO_ALERTA AS TIPO,
								TT.DESCRIPCION as DESCRIPCION_ALERTA, H.OCURRENCIA, H.OCURRENCIA / (
                                                   SELECT SUM(OCURRENCIA) AS PORCENTAJE
                                                   FROM PLA_AUDITORIA.HISTORICO_ALERTA
                                              WHERE TO_CHAR(fecha_alerta,'DD/MM/YYYY')=TO_CHAR(sysdate,'DD/MM/YYYY')
                                              ) *100  AS PORCENTAJE       
								FROM PLA_AUDITORIA.HISTORICO_ALERTA H,
								PLA_AUDITORIA.TIPO_ALERTA TT
								WHERE TT.ID_TIPO_ALERTA=H.ID_TIPO_ALERTA
								AND TO_CHAR(H.fecha_alerta,'DD/MM/YYYY')=TO_CHAR(sysdate,'DD/MM/YYYY')");
	}
	catch (exception $e){die($db->ErrorMsg());}*/

try {
$rs_resumen = $db -> Execute("SELECT TO_NUMBER(TO_CHAR(FECHA,'YYYY')) AS ANO,
									TO_CHAR(FECHA,'YYYY') AS PERIODO,
									COUNT(*) AS ROS, 
									COUNT(*)/(SELECT COUNT(*) FROM PLA_AUDITORIA.T_GANADOR) * 100 AS PORCENTAJE
									FROM PLA_AUDITORIA.T_GANADOR GROUP BY TO_CHAR(FECHA,'YYYY') 
									ORDER BY TO_CHAR(FECHA,'YYYY')");
	}
	catch (exception $e){die($db->ErrorMsg());}
	/*die('PROCES0');
	  while ($row_rec = $rs_recorrido->FetchNextObject($toupper=true)) 
				{ $nro=$row_rec->NRO;}*/
		$rs_resumen->MoveFirst();
$titulo = "MATRIZ DE RIESGOS - Ocurrencia Historica de Operaciones Sospechosas al ".$_REQUEST['fecha'];
?>
<html>
<head>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
			['Periodos', 'Nro. de Ocurrencias'],
		<?php
		if($rs_resumen->RowCount() > 0){
			$tmp_a = array();
			$c=0;
			while ($row_totales = $rs_resumen->FetchNextObject($toupper=true))
				 { 
				 	//$c=c$+1;
					$tmp_a[$row_totales->ANO][$row_totales->ANO]= $row_totales->ANO;
					$tmp_a[$row_totales->ANO]['PERIODO']= $row_totales->PERIODO;
				 	//$yy=$row_totales->OCURRENCIA;
					//$tmp_a[$row_totales->OCURRENCIA]['OCURRENCIA']= $row_totales->OCURRENCIA;
					$tmp_a[$row_totales->ANO]['ROS']= $row_totales->ROS;
										
				}
				
				
			}
			$c=substr($_REQUEST['fecha'],6,4);
			foreach ($tmp_a as $ta=> $t) 
			{ 	
					//$c=c$+1;					
					//echo $t['3',['PORCENTAJE'],1];
				if(isset($t[$c-9]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
					if(isset($t[$c-8]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
					if(isset($t[$c-7]))
					{
						echo "['".$t[$c-3]."',".$t['ROS']."],";
					}
					if(isset($t[$c-6]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
					if(isset($t[$c-5]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
					if(isset($t[$c-4]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
					if(isset($t[$c-3]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
					if(isset($t[$c-2]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
					if(isset($t[$c-1]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
					if(isset($t[$c]))
					{
						echo "['".$t['PERIODO']."',".$t['ROS']."],";
					}
			}
		?>
        ]);
        var options = {
          title: '<?php echo $titulo; ?>',
          hAxis: {title: 'PERIODOS', titleTextStyle: {color: '#000000'}},
		  colors:['#024670','#007000']
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
</head>
<body>
<?php 
/*print_r($tmp_a); 
	echo "<br>";
	echo "<br>";
	foreach($tmp_a as $ta=> $t) { 
		echo "['".$ta."'],";
		print_r($t);
	}
	*/
	?>
<div id="chart_div" style="width: 900px; height: 250px;"></div>
</body>
</html>
