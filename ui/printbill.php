<?php

require('fpdf/fpdf.php');

include_once 'connectdb.php';

$id=$_GET["id"];

$select=$conn->prepare("SELECT * FROM tbl_invoice WHERE invoice_id = $id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$pdf = new FPDF('p','mm', array(120,200)); // Create new PDF writer object

$pdf->AddPage(); //add new page


$pdf->SetFont('Arial','B',16); //set font to bold
$pdf->Cell(100,8,'POS BARCODE SYSTEM',1,1,'C');

$pdf->SetFont('Arial','B',8); //set font to bold
$pdf->Cell(100,5,'Phone No. : +91 7572922581',0,1,'C');
$pdf->Cell(100,5,'Website   : www.posbarcode.com',0,1,'C');

$pdf->Line(10,28,110,28);
$pdf->Ln(1);

$pdf->SetFont('Arial','BI',8); //set font to bold
$pdf->Cell(20,4,'Bill  No. : ',0,0,'');

$pdf->SetFont('Courier','BI',8); //set font to bold
$pdf->Cell(40,4,$row->invoice_id,0,1,'');

$pdf->SetFont('Arial','BI',8); //set font to bold
$pdf->Cell(20,4,'Date      : ',0,0,'');

$pdf->SetFont('Courier','BI',8); //set font to bold
$pdf->Cell(40,4,$row->order_date,0,1,'');


$pdf->SetX(10);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(51,5,'Product',1,0,'C');
$pdf->Cell(12,5,'Qty',1,0,'C');
$pdf->Cell(18,5,'Price',1,0,'C'); // all the co-ordinates modified by dip kathiriya
$pdf->Cell(18,5,'Total',1,1,'C');


$select=$conn->prepare("select * from tbl_invoice_details where invoice_id =$id");
$select->execute();


while($product=$select->fetch(PDO::FETCH_OBJ)){

$pdf->SetX(10);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(51,5,$product->product_name,1,0,'L');
$pdf->Cell(12,5,$product->qty,1,0,'C');
$pdf->Cell(18,5,$product->rate,1,0,'C');
$pdf->Cell(18,5,$product->rate*$product->qty,1,1,'C');

}

//======  further code is about total and co-ordinates again modified by dip =============

$pdf->SetX(10);
$pdf->SetFont('courier','B',8);
$pdf->Cell(51,5,'Total(Rs)',1,0, 'C');
$pdf->Cell(30,5,'',1,0,'L');//to set a blank space yehh with framed = 1 co-ordinates
$pdf->Cell(18,5,$row->total,1,1,'C');

$pdf->SetX(10);
$pdf->SetFont('courier','B',8);
$pdf->Cell(51,5,'Remaining(Rs)',1,0, 'C');
$pdf->Cell(30,5,'',1,0,'L');//to set a blank space
$pdf->Cell(18,5,$row->ramount,1,1,'C');

$pdf->SetX(10);
$pdf->SetFont('courier','B',8);
$pdf->Cell(51,5,'Paid(Rs)',1,0, 'C');
$pdf->Cell(30,5,'',1,0,'L');//to set a blank space
$pdf->Cell(18,5,$row->paid,1,1,'C');



$pdf->SetX(10);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(25,5,'Important Notice : ',0,1,'');

$pdf->SetX(10);
$pdf->SetFont('Arial','',5);
$pdf->Cell(75,5,'No Product Will Be Replaced Or Refunded If You Dont Have Bill With You',0,2,'');

$pdf->SetX(10);
$pdf->SetFont('Arial','',5);
$pdf->Cell(75,5,'You Can Refund Within 2 Days Of Purchase',0,2,'');


$pdf->Output();

?>