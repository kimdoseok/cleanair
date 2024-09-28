<?php

require('korean.php');

class KPDF extends PDF_Korean {

}


$pdf = new KPDF("L","pt","Letter");
$pdf->AddUHCFont('����');


$cols = 4;
$rows = 2;
$page = 1;

$startx = 10;
$starty = 30;
$diffx = 190;
$diffy = 220;
$gapx = 5;
$gapy = 50;
$maxw = $diffx-10;
$maxh = $diffy-10;

$numrows = count($_SESSION["selections"]);
$lastpage = ceil($numrows/$cols/$rows);

$pdf->AddUHCFont('�ü�','Gungsuh');
$pdf->AddUHCFont('����', 'HYGoThic-Medium-Acro');
$pdf->AddPage();
$pdf->SetFont('�ü�','',36);
$pdf->Text(300, 260, "��ǰ īŸ�α�");
$pdf->Text(300, 300, "Item Catalog");
$pdf->SetFont('����','',18);
$pdf->Text(350, 330, "(".date("m/d/Y").")");
$pdf->SetFont('����','',14);
$pdf->Text(390, 350, $numrows." items ");

$pdf->SetFont('����','',10);

for ($i=0;$i<$lastpage;$i++) {
    $pdf->AddPage();
    for ($j=0;$j<$rows;$j++) {
        for ($k=0;$k<$cols;$k++) {
            $curpos = $i*$cols*$rows+ $j*$cols + $k;
            //$curpos = $i*$cols*$rows+ $j*($cols+$gapx) + $k*(1+$gapy);
            if ($numrows > $curpos) {
                $line = $_SESSION["selections"][$curpos];

                $imgfile = $_SERVER["DOCUMENT_ROOT"]."item_images/".strtolower(trim($line["item_code"])).".jpg";

                list($width, $height, $type, $attr) = getimagesize($imgfile);
                $w = $width;
                $h = $height;
                if ($w>$maxw) {
                    $h = round($h*$maxw/$w);
                    $w = $maxw;
                }
                if ($h>$maxh) {
                    $w = round($w*$maxh/$h);
                    $h = $maxh;
                }
                $x = $k*($diffx+$gapx)+$startx;
                $y = $j*($diffy+$gapy)+$starty;
                $pdf->Image($imgfile,$x,$y,$w,$h);
                $y += $maxh+30;
                $pdf->Text($x+20, $y, mb_convert_encoding(stripslashes($line["item_desc"]), "UHC", "UTF-8"));
                $pdf->Text($x+20, $y+13, stripslashes($line[item_name]));
                //echo mb_convert_encoding($line[desc1], "UTF-8", "UHC")."<br>";
                //echo $line[desc1]."<br>";
            }
        }
    }
}
$pdf->Output();
exit;
/*
$pdf->AddUHCFont('����');
$pdf->AddUHCFont('����', 'HYGoThic-Medium-Acro');
$pdf->AddUHCFont('����', 'Dotum');
$pdf->AddUHCFont('����', 'Batang');
$pdf->AddUHCFont('�ü�', 'Gungsuh');
$pdf->AddUHCFont('����', 'Gulim');
$pdf->AddUHCFont('�Ѱܷ���ü', '�Ѱܷ���ü');
$pdf->AddUHCFont('���±۲�', '���±۲�');
$pdf->Open();
$pdf->AddPage();
$pdf->SetFont('����','',16);
$pdf->Write(8,'PHP 3.0�� 1998�� 6���� ���������� ������Ǿ���.');
$pdf->Ln();
$pdf->SetFont('����','',16);
$pdf->Write(8,"(����)����۲õ� ��Ÿ�� �� �־���.");
$pdf->Ln();
$pdf->SetFont('����','',16);
$pdf->Write(8,"(����)�ϴ� ������ ���ο� ��Ʈ�� �߰������� �ʾƵ�...");
$pdf->Ln();
$pdf->SetFont('�ü�','',16);
$pdf->Write(8,'(�ü�)������� �ִ� �⺻���� �۲��� �����ϴ�.');
$pdf->Ln();
$pdf->SetFont('����','',16);
$pdf->Write(8,'(����)�۲õ��� ���� �޶��̽ó���?');
$pdf->Ln();
$pdf->SetFont('����','',16);
$pdf->Write(8,'(����)�̰� ����ü���ϴ�.');
$pdf->Ln();
$pdf->SetFont('�Ѱܷ���ü','',16);
$pdf->Write(8,'(�Ѱܷ���ü)�̰� �Ѱܷ���ü���ϴ�.');
$pdf->Ln();
$pdf->SetFont('���±۲�','',16);
$pdf->Write(8,'(���±۲�)�۲��� ������ �⺻���� ����ü�� ��Ÿ���ϴ�.');
$pdf->Output();
$pdf->Ln(); $pdf->Ln();
*/
?>
