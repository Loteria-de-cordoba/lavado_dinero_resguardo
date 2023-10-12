<?php 
session_start();

include_once("../db_conecta_session.inc.php");
include_once("../funcion.inc.php");
require_once("header_listado.php"); 

$periodo = validarAsignarParametros('periodo','1');

try {
	$rs_datos = $db->Execute("
	SELECT US.DESCRIPCION || ' - ' || EMP.LEGAJO  AS USUARIO,
	AR.DESCRIPCION AS AREA, 
	GRANTED_ROLE AS ROL,
  EMP.LEGAJO AS LEGAJO
	FROM 
	SUPERUSUARIO.USUARIOS US,
	DBA_ROLE_PRIVS D,
	superusuario.areas ar,
  rrhh.t_empleado emp
	WHERE US.ID_USUARIO=D.GRANTEE
  AND SUBSTR(US.ID_USUARIO,3,8)=SUBSTR(EMP.CUIL,4,8)
	AND AR.ID_AREA=US.AREA_ID
	AND D.GRANTED_ROLE like '%ROL_LAVADO%' 
	AND AR.DESCRIPCION NOT LIKE '%Sin Area%'
	AND EMP.ACTIVO='S'
	ORDER BY GRANTED_ROLE, US.DESCRIPCION
	");
} catch  (exception $e) {
	die($db->ErrorMsg());
}

/*
try {
	$rs_datos = $db->Execute("
	SELECT US.DESCRIPCION AS USUARIO,
        AR.DESCRIPCION AS AREA,
        GRANTED_ROLE AS ROL
    FROM
        SUPERUSUARIO.USUARIOS US,
        DBA_ROLE_PRIVS D,
        rrhh.t_area_organigrama ar,
		rrhh.t_empleado e
    WHERE US.ID_USUARIO=D.GRANTEE
        AND substr(us.id_usuario,3,8)=substr(e.cuil,4,8)
	and e.id_area_organigrama=ar.id_area_organigrama
        AND D.GRANTED_ROLE like '%ROL_LAVADO%'
        AND lower(AR.DESCRIPCION) NOT LIKE '%sin area%'
    ORDER BY GRANTED_ROLE, US.DESCRIPCION
	");
} catch  (exception $e) {
	die($db->ErrorMsg());
}
*/


$titulo = 'Areas y Roles de Empleados Usuarios del Sistema de Prevencion para el Lavado de Activos';
$titulo3 = $periodo_name;

$pdf=new PDF('L');
$pdf->AliasNbPages();

$salto_pagina=185;
$pri='NO';

$pdf->SetFont('Arial','B',11);
$y_line=$pdf->GetY(); 
$pdf->SetFillColor(240,240,240);	

while ($row = $rs_datos->FetchNextObject($toupper=true)) {
	
	if ($salto_pagina > 180) {
		$salto_pagina=0;
		if ($pri=='NO') {
			
			$pdf->AddPage();
			$pdf->SetFillColor(194,194,194);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(10,6,'',0,0,'C');
			$pdf->Cell(80,6,'Empleado - Legajo',1,0,'L',1);
			$pdf->Cell(85,6,'Area',1,0,'L',1);
			$pdf->Cell(90,6,'Rol',1,1,'C',1);
			
		} else {
			$pri='NO';
		}
	}
	
	$cant=$cant+1;
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(10,6,'',0,0,'C');
	$pdf->Cell(80,6,utf8_decode($row->USUARIO),0,0,'L');
	$pdf->Cell(85,6,utf8_decode($row->AREA),0,0,'L');
	$pdf->Cell(90,6,utf8_decode($row->ROL),0,1,'L');
	
	$y_line=$pdf->GetY();
	$salto_pagina=number_format($y_line,0,'.',',');
}

//AGREGO EL 13/11/2014
$y_line = $y_line + 50;
$pdf->setY = $y_line;
$pdf->Ln(10);
$pdf->Cell(70,6,'',0,0,'C');
$pdf->SetFont('Arial','B',20);
$total='Total de Usarios===========> '.$rs_datos->RowCount();
$pdf->Cell(130,10,utf8_decode($total),1,0,'C',1);
$pdf->SetFont('Arial','',10);

if($salto_pagina > 90){
	$pdf->AddPage();
}
$y_line = $y_line + 2;
$pdf->setY = $y_line;
$pdf->Cell(10,6,'',0,1,'C');
$pdf->Cell(80,6,'Roles:',0,1,'L');
$pdf->Cell(80,6,'',0,1,'L');

$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_ADMINISTRA',0,0,'L');
$pdf->Cell(80,6,'Controla el estado de los premios mayores a $50.000 en delegaciones y casinos.',0,1,'L');
$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_ADM_CASINO',0,0,'L');
$pdf->Cell(80,6,'Puede cargar y consultar ganadores. También conformar premios. (Solo para casinos)',0,1,'L');

$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_CASINO_CARGA',0,0,'L');
$pdf->Cell(80,6,'Rol para pruebas internas del departamento de sistemas.',0,1,'L');


$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_ADM_CONFORMA',0,0,'L');
$pdf->Cell(80,6,'Puede cargar y consultar ganadores. También conformar premios de la delegación/casino al que pertenece.',0,1,'L');
$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_ADM_SIN_CC',0,0,'L');
$pdf->Cell(80,6,'Puede cargar y consultar ganadores. También conformar premios. (Solo para delegaciones)',0,1,'L');
$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_CONFORMA',0,0,'L');
$pdf->Cell(80,6,'Solo conforma premios de su delegación.',0,1,'L');
$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_CONSULTA',0,0,'L');
$pdf->Cell(80,6,'Consulta premios de cualquier delegación o casino.',0,1,'L');
$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_OP_UNICO',0,0,'L');
$pdf->Cell(80,6,'Puede cargar y consultar ganadores. También conformar premios de la delegación/casino al que pertenece.',0,1,'L');
$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_OPERADOR',0,0,'L');
$pdf->Cell(80,6,'Solo puede cargar premios.',0,1,'L');
$pdf->Cell(10,6,'',0,0,'C');
$pdf->Cell(80,6,'ROL_LAVADO_DINERO_CEDULA',0,0,'L');
$pdf->Cell(80,6,'Se utiliza  para usuarios del sistema CONTROL DE APOSTADORES que no son usuarios del Sistema de Prevencion.',0,1,'L');
//$pdf->Cell(10,6,$rs_datos->RowCount(),0,0,'C');

$pdf->Output();	

?>