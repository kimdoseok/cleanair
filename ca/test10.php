<?php
include_once("class/class.ezpdf.php");
include_once("class/class.sales.php");
include_once("class/class.saledtls.php");
include_once("class/class.items.php");
function splitlines($str, $pdf, $width) {
	$out_arr = array();
	$len = strlen($str);
	$out_text = "";
	for ($i=0;$i<$len;$i++) {
		$tmp_text = $out_text.$str[$i];
		$line_len = $pdf->getTextWidth(10, $tmp_text);
		if ((ord($str{$i})==10 || $str{$i}==" " || $i == $len-1) && $i != 0) {
			$next_str = "";
			for ($j=$i+1;$j<$len;$j++) {
				if ((ord($str{$j})==10 || $str{$j}==" " || $j == $len-1) && $j != 0) {
					break;
				}
				$next_str .= $str{$j};
			}
			$next_len = $pdf->getTextWidth(10, $next_str);
			if ($next_len+$line_len>$width) {
//echo "$out_text ($next_len+$line_len>$width) <br>";
				$tmp_arr = array();
				$tmp_arr["qty"] = "";
				$tmp_arr["prc"] = "";
				$tmp_arr["ext"] = "";
				$tmp_arr["itm"] = $out_text ;
				array_push($out_arr, $tmp_arr);
				$out_text = "";
			} else {
				$out_text .= $str{$i};
			}
			continue;
		}
		if ($line_len > $width) {
//echo "$tmp_text ($line_len > $width)<br>";
			$tmp_arr = array();
			$tmp_arr["qty"] = "";
			$tmp_arr["prc"] = "";
			$tmp_arr["ext"] = "";
			$tmp_arr["itm"] = $out_text ;
			array_push($out_arr, $tmp_arr);
			$out_text = $str{$i};
			continue;
		}
		$out_text .= $str{$i};
	}
	return $out_arr;
}

$pdf = new CezPdf("letter", "portrait");
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($font);

$str = "abcdefg hijklmn opq rstu vwxyz 123 45678 90 ABC DEFG HIJKLM NO PQRSTUV WXYZ";
echo "<pre>";
echo $str;
$arr = splitlines($str, $pdf, 30);
echo "<br>";
for ($i=0;$i<count($arr);$i++) echo $arr[$i]["itm"]."<br>";
echo "</pre>";

?>