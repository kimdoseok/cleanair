<?php
include_once("class/fpdf.php");

function pageTemplate(&$pdf) {
    $pdf->SetFont("Helvetica","b",24);
    $pdf->SetXY(400,52);
    $pdf->Cell(0, 0, "Statement",0,0,"L");
    $pdf->Image('images/logo.jpg', 24,24, 101,64); //string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]]
    $pdf->SetFont("Helvetica","b",12);
    $pdf->SetXY(130,34);
    $pdf->Cell(0, 0, "Clean Air Supply, Inc.",0,0,"L");
    $pdf->SetFont("Helvetica","",10);
    $pdf->SetXY(130,48);
    $pdf->Cell(0, 0, "170 Roosevelt Place, Palisades Park, NJ 07650",0,0,"L");
    $pdf->SetXY(130,62);
    $pdf->Cell(0, 0, "Toll Free: 1-800-435-0581, Tel: 201-461-9766",0,0,"L");
    $pdf->SetXY(130,76);
    $pdf->Cell(0, 0, "Fax: (201)461-9767",0,0,"L");
    $pdf->SetFont("Helvetica","b",11);
    $pdf->SetXY(36,103);
    $pdf->Cell(0, 0, "Bill To:",0,0,"L");
    $pdf->Rect(32, 94, 342, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(32, 110,342, 82);
    
    $pdf->SetXY(452,103);
    $pdf->Cell(0, 0, "Date:",0,0,"L");
    $pdf->Rect(378, 94, 184, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(378, 110,184,36);

    $pdf->SetXY(386,155);
    $pdf->Cell(0, 0, "Amount Due",0,0,"L");
    $pdf->Rect(378, 146, 92, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(378, 162,92,30);
    $pdf->SetXY(478,155);
    $pdf->Cell(0, 0, "Amount Enc",0,0,"L");
    $pdf->Rect(470, 146, 92, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(470, 162,92,30);

    $pdf->SetXY(57,206);
    $pdf->Cell(0, 0, "Date",0,0,"L");
    $pdf->Rect(32, 198, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(32, 214, 85, 560); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(220,206);
    $pdf->Cell(0, 0, "Transaction",0,0,"L");
    $pdf->Rect(117, 198, 275, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(117, 214, 275, 560); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(410,206);
    $pdf->Cell(0, 0, "Amount",0,0,"L");
    $pdf->Rect(392, 198, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(392, 214, 85, 560); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(496,206);
    $pdf->Cell(0, 0, "Balance",0,0,"L");
    $pdf->Rect(477, 198, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(477, 214, 85, 560); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(54,782);
    $pdf->Cell(0, 0, "Current",0,0,"L");
    $pdf->Rect(32, 774, 90, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(32, 790, 90, 20); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(134,782);
    $pdf->Cell(0, 0, "1~30 Days",0,0,"L");
    $pdf->Rect(122, 774, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(122, 790, 85, 20); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(220,782);
    $pdf->Cell(0, 0, "31~60 Days",0,0,"L");
    $pdf->Rect(207, 774, 90, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(207, 790, 90, 20); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(310,782);
    $pdf->Cell(0, 0, "61~90 Days",0,0,"L");
    $pdf->Rect(297, 774, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(297, 790, 85, 20); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(390,782);
    $pdf->Cell(0, 0, "Over 90 Days",0,0,"L");
    $pdf->Rect(382, 774, 90, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(382, 790, 90, 20); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(484,782);
    $pdf->Cell(0, 0, "Amount Due",0,0,"L");
    $pdf->Rect(472, 774, 90, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(472, 790, 90, 20); //float x, float y, float w, float h [, string style])

}

$pdf = new FPDF("P","pt");
$pdf->AddPage();
pageTemplate($pdf);




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