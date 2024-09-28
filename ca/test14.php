<?php
include_once("class/fpdf.php");

$pdf = new FPDF("P","pt");
$pdf->AddPage();
$pdf->SetFont("Helvetica","b",24);
$pdf->SetXY(380,32);
$pdf->Cell(0, 0, "Contract #",0,0,"L");

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

$pdf->SetXY(147,236);
$pdf->Cell(0, 0, "Description",0,0,"L");
$pdf->Rect(32, 228, 285, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(32, 244, 285, 346); //float x, float y, float w, float h [, string style])

$pdf->SetXY(330,236);
$pdf->Cell(0, 0, "Quantity",0,0,"L");
$pdf->Rect(317, 228, 75, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(317, 244, 75, 346); //float x, float y, float w, float h [, string style])

$pdf->SetXY(416,236);
$pdf->Cell(0, 0, "Price",0,0,"L");
$pdf->Rect(392, 228, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(392, 244, 85, 346); //float x, float y, float w, float h [, string style])

$pdf->SetXY(490,236);
$pdf->Cell(0, 0, "Extension",0,0,"L");
$pdf->Rect(477, 228, 85, 16); //float x, float y, float w, float h [, string style])
$pdf->Rect(477, 244, 85, 346); //float x, float y, float w, float h [, string style])

$pdf->SetFont("Times","b",18);
$pdf->Text(36, 610, "We Propose");
$pdf->SetFont("Times","",10);
$pdf->Text(136, 610, "hereby to furnish material and labor - complete in accordance with above specifications, for the sum of:");
$pdf->Line(36, 632, 392, 632);
$pdf->Text(410, 632, "dollars (                                       )");
$pdf->SetFont("Helvetica","",8);
$pdf->Text(36, 640, "Payment to be made as follows:");
$pdf->Line(36, 652, 560, 652);
$pdf->Rect(36, 676, 524, 70); //float x, float y, float w, float h [, string style])
$pdf->SetFont("Helvetica","",9);
$pdf->Text(40, 710, "and other necessary insurance.  Our workers are fully covered by Workman's Compensation Insurance.");
$pdf->SetFont("Times","b",9);
$pdf->Text(40, 742, "Authorized Signature");
$pdf->SetFont("Helvetica","",9);
$pdf->Text(274, 742, "This proposal may be withdrawn by us if not accepted within         days.");

$pdf->Rect(36, 746, 524, 70); //float x, float y, float w, float h [, string style])
$pdf->SetFont("Times","b",18);
$pdf->Text(40, 762, "Acceptance of Proposal");
$pdf->SetFont("Times","",9);
$pdf->Text(40, 776, "The above prices, specifications and conditions are satisfactory and are hereby accept");
$pdf->Text(40, 784, "You are authorized to do the work as specified.  Payment will be made as outlined above.");
$pdf->SetFont("Helvetica","",9);

$pdf->Text(370,780,"Signature:");
$pdf->Line(416, 780, 560, 780);
$pdf->Text(40,812, "Date of Acceptance:");
$pdf->Text(370,812, "Signature:");



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