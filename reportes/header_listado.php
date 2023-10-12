<?php
@session_start();

include_once("../db_conecta_adodb.inc.php");


//include_once("../db_conecta_adodb.inc.php");

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 

//$db->debug = true;
	
require_once('fpdf.php');



class PDF extends FPDF {
//Cabecera de p�gina
function Header() {

    global $titulo,$titulo2,$titulo3;
	//Logo
    $this->Image('../image/loteria_recibo.jpg',10,6,21,9);
    $this->Image('../image/loteria_recibo.jpg',260,6,21,9);
	
    //Times bold 15
    $this->SetFont('Times','B',13);
	
	$this->Ln(-2);
	$y_line=$this->GetY();
    //Movernos a la derecha
    $this->Cell(120);
    //T�tulo
	$y_line=$this->GetY();
	$this->Cell(30,$y_line,'LOTERIA DE CORDOBA S.E.',0,0,'C');
  
    //$this->SetFont('Times','I',8);
	$y_line=$this->GetY();
	$this->Ln(4);
	$this->Cell(120);
	$this->Cell(30,$y_line,'27 de Abril 185 - C�rdoba - Rep�blica Argentina',0,1,'C');
 
	$this->Ln(-2);
  
	$y_line=$this->GetY();
	$this->Line(10,$y_line,280,$y_line);

	//Salto de l�nea
	$this->Ln(-3);
	//Arial bold 15
    $this->SetFont('Arial','B',11);
	$y_line=$this->GetY();
	
	$this->Cell(40);
	$this->Cell(190,$y_line,$titulo,0,1,'C');
	
	$this->Ln(-13);
	
	$y_line=$this->GetY();
	$this->Cell(190,$y_line,$titulo2,0,1,'C');
	
	if(!empty($titulo3)){
		$this->Ln(-10);
		//$y_line=$this->GetY();
		$this->Cell(120);
		$this->Cell(190,$y_line,$titulo3,0,1,'C');
	}

	//Salto de l�nea
    $this->Ln(-4);
  }
  //Pie de p�gina
function Footer()
{
    
	//Posici�n: a 1,5 cm del final
    $this->SetY(-15);
	$y_line=$this->GetY();
	$this->Line(10,$y_line,280,$y_line);
//	$this->Ln(5);
    //Arial italic 8
    //$this->SetFont('Arial','I',8);
    //N�mero de p�gina
    $this->Cell(0,7,'Usuario: '.$_SESSION['nombre_usuario'].'  '. date('d/m/Y h:i:s A'),0,0,'L');
    $this->Cell(0,7,'P�gina: '.$this->PageNo()."/{nb}",0,0,'R');

}
 }
 ?>