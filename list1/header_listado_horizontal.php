<?php

//include("file:///C|/Documents and Settings/emgiraudo/Datos de programa/Adobe/Dreamweaver 9/Configuration/ServerConnections/desarrollo/rrhh/db_conecta_oracle_adodb.inc.php");

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 
require('fpdf.php');

class PDF extends FPDF
{
//Cabecera de página
function Header()
 {
    global $titulo,$titulo2;
	//Logo
	
	$this->Image('../image/logo_loteria_web.png',10,10,30,15);
	//$this->Image('../image/bandera_cordoba.png',260,10,25,15); 
	
     //Times bold 15
    $this->SetFont('Times','B',13);
	$this->Ln(2);
	$y_line=$this->GetY();
    //Movernos a la derecha
    $this->Cell(80);
    //Título
	$y_line=$this->GetY();
	$this->Cell(90,$y_line,'LOTERIA DE CORDOBA S.E.',0,0,'C');
  
    $this->SetFont('Times','I',8);
	$y_line=$this->GetY();
	$this->Ln(5);
	$this->Cell(80);
	$this->Cell(90,$y_line,'27 de Abril 185 - Córdoba - República Argentina',0,1,'C');
 
 $this->Ln(2);
  
	$y_line=$this->GetY();
	$this->Line(10,$y_line,280,$y_line);

 //Salto de línea
$this->Ln(-3);
 //Arial bold 15
    $this->SetFont('Arial','BUI',13);
	$y_line=$this->GetY();
   $this->Cell(250,$y_line,$titulo,0,1,'C');
$this->Ln(-25);
	$y_line=$this->GetY();
   $this->Cell(250,$y_line,$titulo2,0,1,'C');

 //Salto de línea
    $this->Ln(-3);
  }
  //Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
	$y_line=$this->GetY();
	$this->Line(10, $y_line, 280, $y_line);
//	$this->Ln(5);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(0,7,'Usuario: '.$_SESSION['nombre_usuario'].'  '. date('d/m/Y h:i:s A'),0,0,'L');
    $this->Cell(0,7,'Página: '.$this->PageNo()."/{nb}",0,0,'R');

}
 }
 ?>