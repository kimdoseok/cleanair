<?php
include_once("class/fpdf.php");

$pdf = new FPDF("P","pt");
$pdf->AddPage();
$pdf->SetFont("Helvetica","b",24);
$pdf->SetXY(380,32);
$pdf->Cell(0, 0, "Invoice #",0,0,"L");

$pdf->Image('images/logo.jpg', 24,24, 101,64); //string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]]
$pdf->SetFont("Helvetica","b",12);
$pdf->SetXY(130,34);
$pdf->Cell(0, 0, "Clean Air Equipment, Inc.",0,0,"L");

$pdf->SetFont("Helvetica","",10);
$pdf->SetXY(130,48);
$pdf->Cell(0, 0, "170 Roosevelt Place, Palisades Park, NJ 07650",0,0,"L");
$pdf->SetXY(130,62);
$pdf->Cell(0, 0, "Toll Free: 1-800-435-0581, Tel: 201-461-9766",0,0,"L");
$pdf->SetXY(130,76);
$pdf->Cell(0, 0, "Fax: (201)461-9767",0,0,"L");

$pdf->SetFont("Helvetica","b",11);
$pdf->SetXY(36,103);
$pdf->Cell(0, 0, "Sold To:",0,0,"L");
$pdf->Rect(32, 94, 260, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(32, 110,260,74);

$pdf->SetXY(306,103);
$pdf->Cell(0, 0, "Ship To:",0,0,"L");
$pdf->Rect(302, 94, 260, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(302, 110,260,74);

$pdf->SetXY(60,199);
$pdf->Cell(0, 0, "Phone",0,0,"L");
$pdf->Rect(32, 190, 90, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(32, 206, 90, 16); //float x, float y, float w, float h [, string style])
$pdf->SetXY(150,199);
$pdf->Cell(0, 0, "Date",0,0,"L");
$pdf->Rect(122, 190, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(122, 206, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->SetXY(215,199);
$pdf->Cell(0, 0, "Date of Plans",0,0,"L");
$pdf->Rect(207, 190, 90, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(207, 206, 90, 16); //float x, float y, float w, float h [, string style])
$pdf->SetXY(322,199);
$pdf->Cell(0, 0, "Job#",0,0,"L");
$pdf->Rect(297, 190, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(297, 206, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->SetXY(400,199);
$pdf->Cell(0, 0, "Architect",0,0,"L");
$pdf->Rect(382, 190, 90, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(382, 206, 90, 16); //float x, float y, float w, float h [, string style])
$pdf->SetXY(500,199);
$pdf->Cell(0, 0, "Total",0,0,"L");
$pdf->Rect(472, 190, 90, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(472, 206, 90, 16); //float x, float y, float w, float h [, string style])

$pdf->SetXY(147,238);
$pdf->Cell(0, 0, "Description",0,0,"L");
$pdf->Rect(32, 230, 285, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(32, 246, 285, 466); //float x, float y, float w, float h [, string style])

$pdf->SetXY(330,238);
$pdf->Cell(0, 0, "Quantity",0,0,"L");
$pdf->Rect(317, 230, 75, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(317, 246, 75, 466); //float x, float y, float w, float h [, string style])

$pdf->SetXY(416,238);
$pdf->Cell(0, 0, "Price",0,0,"L");
$pdf->Rect(392, 230, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(392, 246, 85, 466); //float x, float y, float w, float h [, string style])

$pdf->SetXY(490,238);
$pdf->Cell(0, 0, "Extension",0,0,"L");
$pdf->Rect(477, 230, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(477, 246, 85, 466); //float x, float y, float w, float h [, string style])


$pdf->SetXY(394,723);
$pdf->Cell(0, 0, "Equipment",0,0,"L");
$pdf->Rect(392, 715, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(477, 715, 85, 16); //float x, float y, float w, float h [, string style])

$pdf->SetXY(394,738);
$pdf->Cell(0, 0, "Sub Total",0,0,"L");
$pdf->Rect(392, 731, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(477, 731, 85, 16); //float x, float y, float w, float h [, string style])

$pdf->SetXY(394,754);
$pdf->Cell(0, 0, "Tax",0,0,"L");
$pdf->Rect(392, 747, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(477, 747, 85, 16); //float x, float y, float w, float h [, string style])

$pdf->SetXY(394,770);
$pdf->Cell(0, 0, "Deposit",0,0,"L");
$pdf->Rect(392, 763, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(477, 763, 85, 16); //float x, float y, float w, float h [, string style])

$pdf->SetXY(394,785);
$pdf->Cell(0, 0, "Grand Total",0,0,"L");
$pdf->Rect(392, 779, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(477, 779, 85, 16); //float x, float y, float w, float h [, string style])

$pdf->Rect(32, 715, 360, 80); //float x, float y, float w, float h [, string style])



$pdf->AddPage();

$height = 15;
$start_y = 50;
$pdf->SetFont('Arial','',10);
for ($i=0;$i<50;$i++) {
    $start_x = 10;
    $pdf->SetXY($start_x, $height*$i+$start_y);
    $pdf->Cell(0,0,$i.'Abcdefghijklmnopqrstuvwxyz');
    $start_x += 200;
    $pdf->SetXY($start_x, $height*$i+$start_y);
    $pdf->Cell(0,0,'Abcdefghijklmnopqrstuvwxyz');
    $start_x += 200;
    $pdf->SetXY($start_x, $height*$i+$start_y);
    $pdf->Cell(0,0,'Abcdefghijklmnopqrstuvwxyz');
}


$pdf->Output();

?>