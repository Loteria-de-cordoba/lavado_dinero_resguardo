<?php session_start();
error_reporting(E_ERROR);
//ini_set("display_errors",1);
include_once("../db_conecta_adodb.inc.php");
if($_GET['conformado']==1){
$conformado="Conformados";
} else {
$conformado="No Conformado";
}
//echo "ppp";
//die();

$micontrol=0;
//print_r($_GET);
//die();
$fecha_desde=$_GET['fecha_desde'];
$fecha_hasta=$_GET['fecha_hasta'];
$condicion_fecha="and g.fecha_alta between to_date('$fecha_desde 00:00','dd/mm/yyyy HH24:MI') and to_date('$fecha_hasta 23:59','dd/mm/yyyy HH24:MI')";
//'01/11/2010 00:00','DD/MM/YYYY HH24:MI'
$agente='';
$titulo='Premios '.$conformado.' Fuera de Termino';
$titulo2='Desde el '.$fecha_desde.' Hasta el '.$fecha_hasta;
require("header_listado.php"); 

//$db->debug=true;

try {
 $consulta=  $db->Execute("SELECT APOSTADOR, MONTO_DEL_PREMIO,
							DE_FECHA, CONFORMADO_EL_DIA,
							POR_EL_AGENTE, 
							AREA, ESTADO, legajo
							FROM(
                            select Initcap(apellido)||', '||Initcap(nombre) AS APOSTADOR,
                            VALOR_PREMIO AS MONTO_DEL_PREMIO,
                            TO_CHAR(g.FECHA_alta,'DD/MM/YYYY') AS DE_FECHA,
                            TO_CHAR(FECHA_CONFORMA,'DD/MM/YYYY') AS CONFORMADO_EL_DIA,
                            Initcap(US.DESCRIPCION) AS POR_EL_AGENTE,
							US.AREA_ID AS AREA,
							e.legajo as legajo,
                            CASE 
                                WHEN TO_CHAR(g.FECHA_alta,'DD')<16 THEN 
                                    CASE
                                      WHEN (TO_CHAR(FECHA_CONFORMA,'DD')>16 OR (TO_CHAR(FECHA_CONFORMA,'DD')<17 AND TO_CHAR(FECHA_CONFORMA,'MM')>TO_CHAR(g.FECHA_Alta,'MM'))) THEN 'CONFORMADO FUERA DE FECHA'
                                      --ELSE 'CONFORMADO EN FECHA'
                                    END
                                WHEN TO_CHAR(FECHA,'DD')>15 THEN 
                                    CASE
                                      WHEN (TO_CHAR(FECHA_CONFORMA,'DD')<16 OR (TO_CHAR(FECHA_CONFORMA,'DD')>15 AND TO_CHAR(FECHA_CONFORMA,'MM')>TO_CHAR(g.FECHA_alta,'MM')) )
									  		and TO_CHAR(FECHA_CONFORMA,'DD')<>1
									   THEN 'CONFORMADO FUERA DE FECHA'
                                      --ELSE 'CONFORMADO EN FECHA'
                                    END   
                            END AS ESTADO    
                            from lavado_dinero.t_ganador G,
                                  SUPERUSUARIO.USUARIOS US,
								  rrhh.t_empleado e    
                            where US.ID_USUARIO=USUARIO_CONFORMA
                                  $condicion_fecha
                            	  AND FECHA_CONFORMA IS NOT NULL
								  and substr(us.id_usuario,3)=to_char(e.documento)
								  and e.id_planta=1
							)
				WHERE  ESTADO LIKE '%FUERA%'
				ORDER BY TO_DATE(CONFORMADO_EL_DIA,'DD/MM/YYYY'),5,1
					  ");
			}									catch (exception $e){die ($db->ErrorMsg());} 
	
//die();
$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(200,200,200); 

$pdf->SetFont('Arial','B',10);
$pdf->setx(15);
$pdf->Cell(41,6,'Apostador',1,0,'C',1);
$pdf->Cell(36,6,'Fecha de Premio',1,0,'C',1);
$pdf->Cell(25,6,'Importe',1,0,'C',1);
$pdf->Cell(42,6,'Agente Conforma',1,0,'C',1);
$pdf->Cell(36,6,'Fecha Conforma',1,1,'C',1);
//$pdf->Cell(25,6,'Importe',1,0,'C');
//$pdf->Cell(25,6,'DDJJ',1,1,'C');
$kk=240;
		
$pdf->SetFont('Arial','',8);
 
while ($row = $consulta->FetchNextObject($toupper=true)) {
 
 	$cc=$pdf->GetY();
	if($cc>250)
	{
		$pdf->AddPage();
		
			$pdf->SetFont('Arial','B',10);
			$pdf->setx(15);
			$pdf->Cell(41,6,'Apostador',1,0,'C',1);
			$pdf->Cell(36,6,'Fecha de Premio',1,0,'C',1);
			$pdf->Cell(25,6,'Importe',1,0,'C',1);
			$pdf->Cell(42,6,'Agente Conforma',1,0,'C',1);
			
			$pdf->Cell(36,6,'Fecha Conforma',1,1,'C',1);
			$pdf->SetFont('Arial','',8);
	}
	//veo si la fecha conforma
	//no fue sabado, domingo, feriado 
	//o principio de año
	$arr=(int)$row->AREA;
	$lleg=(int)$row->LEGAJO;
	try {
 				$control=  $db ->Execute("select LAVADO_DINERO.CONTROLA_MAL_CONFORMADO(
											?,
											to_date(substr(?,0,10),'dd/mm/yyyy'),
											to_date(substr(?,0,10),'dd/mm/yyyy'),
											?)	AS CONTROL FROM DUAL",
											array($arr, 
											$row->DE_FECHA,
											$row->CONFORMADO_EL_DIA,
											$lleg));}
		catch (exception $e){die ($db->ErrorMsg());}
		$row_control = $control->FetchNextObject($toupper=true);
		$micontrol=$row_control->CONTROL;
	if($micontrol==1)
	{
	$pdf->setx(15);
	$yy=utf8_encode($row->APOSTADOR);
	$pdf->Cell(41,7,substr(utf8_decode($yy),0,29),1,0,'L');
	$pdf->Cell(36,7,$row->DE_FECHA,1,0,'C');
	$pdf->Cell(25,7,'$ '.number_format($row->MONTO_DEL_PREMIO,2,',','.'),1,0,'R');
	$xx=utf8_encode($row->POR_EL_AGENTE);
			if($xx<>$agente or $cc>250)
			{
					$pdf->Cell(42,7,substr(utf8_decode($xx),0,29),1,0,'L');
			}
			else
			{
					$pdf->Cell(42,7,'',1,0,'L');
			}
	$agente=$xx;
	
	$pdf->Cell(36,7,$row->CONFORMADO_EL_DIA ,1,1,'C');
 	//$pdf->Cell(25,7,$row->JUEGOS,1,0,'L');
	//$pdf->Cell(25,7,'$ '.number_format($row->VALOR_PREMIO,2,',','.'),1,0,'R');
	//$pdf->Cell(25,7,$row->DDJJ,1,1,'R');
	 $acum=$acum+$row->MONTO_DEL_PREMIO;
	 }//fin de control
	} 

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,7,' ',0,1,'R');
$pdf->setx(121);
$pdf->Cell(39,7,'TOTAL $ '.number_format($acum,2,',','.'),0,0,'R',1);
	//$pdf->Cell(180,7,'Total:  $'.$acum,1,0,'R');
//$y_line=$pdf->GetY();
//$pdf->Line(10,$y_line,200,$y_line);
//die('en proceso');
$pdf->Output();
?>