<?php session_start();
//include("file:///C|/Documents and Settings/emgiraudo/Datos de programa/Adobe/Dreamweaver 9/Configuration/ServerConnections/desarrollo/rrhh/db_conecta_oracle_adodb.inc.php");

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
require('../pdf/fpdf.php');

class PDF extends FPDF
{
//Cabecera de p�gina
function Header()
 {
    //global $titulo,$titulo2;
	//Logo
   // $this->Image('../image/logochico.JPG',10,6,21,15);
   // $this->Image('../image/logo_listado.jpg',185,6,15,15);

	$this->Image('loteria_recibo.jpg',10,6,21,9);
   $this->Image('loteria_recibo.jpg',180,6,21,9);
	
     //Times bold 15
    $this->SetFont('Times','B',13);
	$this->Ln(-2);
	$y_line=$this->GetY();
    //Movernos a la derecha
    $this->Cell(80);
    //T�tulo
	$y_line=$this->GetY();
	$this->Cell(30,$y_line,'LOTERIA DE CORDOBA S.E.',0,0,'C');
  
    $this->SetFont('Times','I',8);
	$y_line=$this->GetY();
	$this->Ln(5);
	$this->Cell(80);
	$this->Cell(30,$y_line,'27 de Abril 185 - C�rdoba - Rep�blica Argentina',0,1,'C');
 
 $this->Ln(2);
  
	$y_line=$this->GetY();
	$this->Line(10,$y_line,201,$y_line);

 //Salto de l�nea
$this->Ln(-3);
 //Arial bold 15
    $this->SetFont('Arial','BI',13);
	$y_line=$this->GetY();
   $this->Cell(190,$y_line,$titulo,0,1,'C');
$this->Ln(-10);
	$y_line=$this->GetY();
   $this->Cell(190,$y_line,$titulo2,0,1,'C');

 //Salto de l�nea
    $this->Ln(-5);
  }
  //Pie de p�gina
function Footer()
{
    //Posici�n: a 1,5 cm del final
    $this->SetY(-15);
	$y_line=$this->GetY();
	$this->Line(10,$y_line,200,$y_line);
//	$this->Ln(5);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //N�mero de p�gina
    $this->Cell(0,7,'Sistema KANBAN - Div. Analisis y Progr. - Usuario: '.$_SESSION['nombre_usuario'].'  '. date('d/m/Y h:i:s A'),0,0,'L');
    $this->Cell(0,7,'Pagina: '.$this->PageNo()."/{nb}",0,0,'R');

}
 }
 ?>