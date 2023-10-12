<?php
/*
* formulario de Grafico de Historico de alertas
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
try {
$rs_resumen = $db -> Execute("SELECT TT.ID_TIPO_ALERTA AS TIPO,
								TT.DESCRIPCION as DESCRIPCION_ALERTA, H.OCURRENCIA, H.OCURRENCIA / (
                                                   SELECT SUM(OCURRENCIA) AS PORCENTAJE
                                                   FROM PLA_AUDITORIA.HISTORICO_ALERTA
                                              WHERE TO_CHAR(fecha_alerta,'DD/MM/YYYY')=TO_CHAR(sysdate,'DD/MM/YYYY')
                                              ) *100  AS PORCENTAJE       
								FROM PLA_AUDITORIA.HISTORICO_ALERTA H,
								PLA_AUDITORIA.TIPO_ALERTA TT
								WHERE TT.ID_TIPO_ALERTA=H.ID_TIPO_ALERTA
								AND H.fecha_alerta IN
										(SELECT MAX(XX.FECHA_ALERTA)
										FROM PLA_AUDITORIA.HISTORICO_ALERTA XX)");
	}
	catch (exception $e){die($db->ErrorMsg());}
$titulo = "Ocurrencia de Alertas - ".$_REQUEST['fecha'];
?>
<html>
<head>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
			['Alertas', 'Nro. de Ocurrencias'],
		<?php
		if($rs_resumen->RowCount() > 0){
			$tmp_a = array();
			$c=0;
			while ($row_totales = $rs_resumen->FetchNextObject($toupper=true))
				 { 
				 	//$c=c$+1;
					$tmp_a[$row_totales->TIPO][$row_totales->TIPO]= $row_totales->TIPO;
					$tmp_a[$row_totales->TIPO]['DESCRIPCIONALERTA']= $row_totales->DESCRIPCION_ALERTA;
				 	//$yy=$row_totales->OCURRENCIA;
					//$tmp_a[$row_totales->OCURRENCIA]['OCURRENCIA']= $row_totales->OCURRENCIA;
					$tmp_a[$row_totales->TIPO]['OCURRENCIA']= $row_totales->OCURRENCIA;
										
				}
				
				
			}
			$c=0;
			foreach ($tmp_a as $ta=> $t) 
			{ 	
					//$c=c$+1;					
					//echo $t['3',['PORCENTAJE'],1];
				if(isset($t['1']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['2']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['3']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['4']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['5']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['6']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['7']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['8']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['9']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
					if(isset($t['10']))
					{
						echo "['".$t['DESCRIPCIONALERTA']."',".$t['OCURRENCIA']."],";
					}
			}
		?>
        ]);
        var options = {
          title: '<?php echo $titulo; ?>',
          hAxis: {title: 'Alertas', titleTextStyle: {color: '#000000'}},
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
