<?php
//header("Content-type: application/pdf");
//header("Content-Disposition: attachment; filename=statement.pdf");

include("class/class.ezpdf.php");
$pdf = new CezPdf("letter", "portrait");
$pdf->ezSetMargins(27,27,27,27);

$allbox = $pdf->openObject();
$pdf->saveState();
$pdf->setLineStyle(1);
$pdf->setStrokeColor(0,0,0,0);
$boldFont = 'class/fonts/Helvetica-Bold.afm';
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($boldFont);
$pdf->addText(440,730,24,"Statement");
if (file_exists('images/logo.jpg')) $pdf->addJpegFromFile('images/logo.jpg',27,690,100);
$pdf->addText(140,732,18,"Clean Air Supply, Inc.");
$pdf->selectFont($font);
$pdf->addText(140,718,12, "1301 E. Linden Avenue, Linden, NJ 07036");
$pdf->addText(140,704,12, "1-800-435-0581(NY/NJ), 908-925-7722");
$pdf->addText(140,690,12, "Fax: (908)925-5535");

$pdf->selectFont($boldFont);
$pdf->addText(38,650,10, "Bill To:");
$pdf->addText(480,650,10, "Date");
$pdf->addText(420,586,10, "Amount Due");
$pdf->addText(510,586,10, "Amount Enc.");
$pdf->addText(60,530,10, "Date");
$pdf->addText(230,530,10, "Transaction");
$pdf->addText(124,510,10, "Balance forward");
$pdf->addText(430,530,10, "Amount");
$pdf->addText(520,530,10, "Balance");
$pdf->addText(56,78,10, "Current");
$pdf->addText(142,78,10, "1~30 Days");
$pdf->addText(230,78,10, "31~60 Days");
$pdf->addText(326,78,10, "61~90 Days");
$pdf->addText(413,78,10, "Over 90 Days");
$pdf->addText(510,78,10, "Amount Due");

$x = 27;
$y = 36;
$dx = 93;
$dy = 36;
for ($i=0;$i<6;$i++) {
	$pdf->rectangle($x+$dx*$i,$y,$dx,36);
	$pdf->rectangle($x+$dx*$i,$y+$dy,$dx,20);
}

$y += 56;
$dy = 432;
$dx = 90;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);
$dx += 288;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);
$dx += 90;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);
$dx += 90;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);

$x += 378;
$dx = 90;
$y = $y+$dy+20;
$dy = 36;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);
$x += $dx;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);

$x = 27;
$y += 10;
$dx = 370;
$dy = 90;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);

$x = 405;
$dx = 180;
$y += 56;
$dy = 34;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);

$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($allbox,'all');


$pagetext = $pdf->openObject();
$pdf->saveState();
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($font);

$height = 12;
$lines = 34;
$pdf->selectFont($font);

$pdf->addText(470,700,10, "1234567890");

$pdf->addText(40,625-$height*0,10, "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");
$pdf->addText(40,625-$height*1,10, "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");
$pdf->addText(40,625-$height*2,10, "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");
$pdf->addText(40,625-$height*3,10, "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");
$pdf->addText(40,625-$height*4,10, "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");
$pdf->addText(40,625-$height*5,10, "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");
$pdf->addText(470,625-$height*0,10, "12/31/2003");
$pdf->addText(482-$pdf->getTextWidth(10, "99999999.99"),560,10, "99999999.99");

for ($i=0;$i<$lines;$i++) {
	$pdf->addText(46,497-$height*$i,10, "12/31/2003");
	$pdf->addText(124,497-$height*$i,10, "WWWWWWWWWWWWWWWWWWWWWWWWWWWWW");
	$pdf->addText(482-$pdf->getTextWidth(10, "99999999.99"),497-$height*$i,10, "99999999.99");
	$pdf->addText(574-$pdf->getTextWidth(10, "99999999.99"),497-$height*$i,10, "99999999.99");
}
$pdf->addText(104-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(198-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(288-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(382-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(474-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(568-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");

$pdf->newPage();

$pdf->addText(470,700,10, "9876543210");

$pdf->addText(40,625-$height*0,10, "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");
$pdf->addText(40,625-$height*1,10, "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");
$pdf->addText(40,625-$height*2,10, "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");
$pdf->addText(40,625-$height*3,10, "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");
$pdf->addText(40,625-$height*4,10, "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");
$pdf->addText(40,625-$height*5,10, "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");
$pdf->addText(470,625-$height*0,10, "12/31/2003");
$pdf->addText(482-$pdf->getTextWidth(10, "99999999.99"),560,10, "99999999.99");

for ($i=0;$i<$lines;$i++) {
	$pdf->addText(46,497-$height*$i,10, "12/31/2003");
	$pdf->addText(124,497-$height*$i,10, "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
	$pdf->addText(482-$pdf->getTextWidth(10, "99999999.99"),497-$height*$i,10, "99999999.99");
	$pdf->addText(574-$pdf->getTextWidth(10, "99999999.99"),497-$height*$i,10, "99999999.99");
}
$pdf->addText(104-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(198-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(288-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(382-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(474-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");
$pdf->addText(568-$pdf->getTextWidth(10, "99999999.99"),50,10, "99999999.99");

$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($pagetext,'add');

$pdf->ezStream();

?>