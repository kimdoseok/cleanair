<?php
$str = "abc\ndef";
$len= strlen($str);
for ($i=0;$i<$len;$i++) {
	echo ord($str{$i});
	echo "<br>";
}

/*
	include_once("class/class.ezpdf.php");
	$pdf = new CezPdf("letter", "portrait");
	$font = 'class/fonts/Helvetica.afm';
	$pdf->selectFont($boldFont);

	$comnt = "abc defg hi jklmn opqrstuv, wxyz";
	$len = strlen($comnt);
	$out_text = "";
	$aword = "";
	$k = 0;
	$out_arr = array();
	for ($j=0;$j<$len;$j++) {
		if ($comnt{$j}==" " || $j == $len-1) {
			$out_text .= $aword;
			$line_len = $pdf->getTextWidth(10, $out_text);
			if ($line_len > 15) {
				$out_arr[$x] = $out_text;
				$x++;
				$out_text = "";
			}	
			$aword = "";
		} else {
			$aword .= $comnt{$j};
		}
	}
	print_r($out_arr);
*/
?>