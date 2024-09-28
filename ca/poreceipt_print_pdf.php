<?php
include_once("class/class.customers.php");
include_once("class/class.requests.php");
include_once("class/class.datex.php");
//include_once("class/class.ezpdf.php");
include_once("class/fpdf.php");
include_once("class/class.purchase.php");
include_once("class/class.purdtls.php");
include_once("class/class.items.php");
include_once("class/register_globals.php");


function splitlines($str, $pdf, $width) {
	$out_arr = array();
	$len = strlen($str);
	$out_text = "";
	for ($i=0;$i<$len;$i++) {
		$tmp_text = $out_text.$str[$i];
		$line_len = $pdf->getTextWidth(10, $tmp_text);
		if ((ord($str[$i])==10 || $str[$i]==" " || $i == $len-1) && $i != 0) {
			$next_str = "";
			for ($j=$i+1;$j<$len;$j++) {
				if ((ord($str[$j])==10 || $str[$j]==" " || $j == $len-1) && $j != 0) {
					break;
				}
				$next_str .= $str[$j];
			}
			$next_len = $pdf->getTextWidth(10, $next_str);
			if ($next_len+$line_len>$width || ord($str[$i])==10) {
//echo "$tmp_text<br>$out_text<br>1<br>";
				$tmp_arr = array();
				$tmp_arr["qty"] = "";
				$tmp_arr["uom"] = "";
				$tmp_arr["itm"] = $tmp_text ;
				array_push($out_arr, $tmp_arr);
				$out_text = "";
			} else {
				$out_text .= $str[$i];
			}
			if ($i == $len-1 && !empty($out_text)) {
//echo "$tmp_text<br>$out_text<br>2<br>";
				$tmp_arr = array();
				$tmp_arr["qty"] = "";
				$tmp_arr["uom"] = "";
				$tmp_arr["itm"] = $out_text ;
				array_push($out_arr, $tmp_arr);
			}
			continue;
		}
		if ($line_len > $width) {
			$tmp_arr = array();
			$tmp_arr["qty"] = "";
			$tmp_arr["uom"] = "";
			$tmp_arr["ext"] = "";
			$tmp_arr["itm"] = $out_text ;
			array_push($out_arr, $tmp_arr);
			$out_text = $str[$i];
			continue;
		}
		$out_text .= $str[$i];
	}

	return $out_arr;
}

function pageTemplate(&$pdf) {

}

$d = new Datex();
$ph = new Purchases();
$pd = new PurDtls();
$t = new Items();

//$pdf = new CezPdf("letter", "portrait");
//$pdf->ezSetMargins(27,27,27,27);
$pdf = new FPDF("P","pt");
$pdf->AddPage("P","Letter");

// Form Template....
/*
$allbox = $pdf->openObject();
$pdf->saveState();
*/
$pdf->setLineStyle(0.5);
$pdf->setStrokeColor(0,0,0,0);
$boldFont = 'class/fonts/Helvetica-Bold.afm';
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($boldFont);
$pdf->addText(320,754,14,"Purchase Order#");
if (file_exists('images/logo.jpg')) $pdf->addJpegFromFile('images/logo.jpg',27,675,100);
$pdf->addText(140,730,12,"Clean Air Supply, Inc.");
$pdf->selectFont($font);
$pdf->addText(140,716,10, "170 Roosevelt Place");
$pdf->addText(140,704,10, "Palisades Park, NJ 07650");
$pdf->addText(140,692,10, "1-800-435-0581");
$pdf->addText(140,680,10, "201-461-9763/9764,9765,9766");
$pdf->addText(140,668,10, "Fax: (201)461-9767");
$pdf->selectFont($boldFont);

$pdf->rectangle(32,626,270,16);
$pdf->rectangle(32,552,270,74);
$pdf->addText(38,630,10, "Purchase Order To:");

$pdf->rectangle(312,626,270,16);
$pdf->rectangle(312,552,270,74);
$pdf->addText(320,630,10, "Ship To:");

$pdf->rectangle(312,726,270,16);
$pdf->rectangle(312,652,270,74);
$pdf->addText(320,730,10, "Purchase Order For:");

$pdf->rectangle(32,526,110,16);
$pdf->rectangle(32,506,110,20);
$pdf->addText(70,530,10, "PO Date");

$pdf->rectangle(142,526,110,16);
$pdf->rectangle(142,506,110,20);
$pdf->addText(180,530,10, "Ship Via");

$pdf->rectangle(252,526,110,16);
$pdf->rectangle(252,506,110,20);
$pdf->addText(276,530,10, "Proof Needed");

$pdf->rectangle(362,526,110,16);
$pdf->rectangle(362,506,110,20);
$pdf->addText(390,530,10, "Placed By");

$pdf->rectangle(472,526,110,16);
$pdf->rectangle(472,506,110,20);
$pdf->addText(488,530,10, "Sample Included");

$pdf->rectangle(32,480,84,16);
$pdf->rectangle(32,120,84,360);
$pdf->addText(64,484,10, "Qty");

$pdf->rectangle(116,480,64,16);
$pdf->rectangle(116,120,64,360);
$pdf->addText(136,484,10, "UOM");

$pdf->rectangle(180,480,403,16);
$pdf->rectangle(180,120,403,360);
$pdf->addText(330,484,10, "Item Description");

$pdf->rectangle(32,95,552,16);
$pdf->rectangle(32,20,552,75);
$pdf->addText(40,99,10, "Comments");

$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($allbox,'all');

// for each pages
$pagetext = $pdf->openObject();
$pdf->saveState();
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($font);

$hdr = $ph->getPurchase($purch_id); 
$recs = $pd->getPurDtlsList($purch_id);

// PO Lines...
if ($recs) $nums = count($recs);
else $nums = 0;

$width = 366;
$out_arr = array();
for ($i=0;$i<$nums;$i++) {
	$qty = $recs[$i]["purdtl_qty"]+0;
	$uom = strtoupper($recs[$i]["purdtl_unit"]);
	$itm = $recs[$i]["purdtl_item_desc"]." (".$recs[$i]["purdtl_item_code"].")" ;

	$arr = splitlines($itm, $pdf, $width);

	if ($arr) $arr_num = count($arr);
	else $arr_num = 0;
	for ($j=0;$j<$arr_num;$j++) {
		if ($j==0) {
			$arr[0]["qty"] = $qty;
			$arr[0]["uom"] = $uom;
		}
		array_push($out_arr, $arr[$j]);
	}
}

$lineapage = 29;
$out_num = count($out_arr);
if ($out_num >0) $page_num = ceil($out_num / $lineapage);

for ($i=0;$i<$page_num;$i++) {
// PO Header...
	$pdf->addText(440,755,10, $hdr["purch_id"]);
	if ($i>0) $pdf->newPage();
	if ($i==0) {
		$y = 710;
		$dy = 12;
		$pdf->addText(320,$y,10, $hdr["purch_cust_name"]." (".$hdr["purch_cust_code"].")");
		$y -= $dy;
		if (!empty($hdr["purch_cust_addr1"])) {
			$pdf->addText(320,$y,10, $hdr["purch_cust_addr1"]);
			$y -= $dy;
		}
		if (!empty($hdr["purch_cust_addr2"])) {
			$pdf->addText(320,$y,10, $hdr["purch_cust_addr2"]);
			$y -= $dy;
		}
		if (!empty($hdr["purch_cust_addr2"])) {
			$pdf->addText(320,$y,10, $hdr["purch_cust_addr3"]);
			$y -= $dy;
		}
		$cust_csz = $hdr["purch_cust_city"].", ".strtoupper($hdr["purch_cust_state"])." ".$hdr["purch_cust_zip"];
		if (!empty($cust_csz)) {
			$pdf->addText(320,$y,10, $cust_csz);
			$y -= $dy;
		}
		if (!empty($hdr["purch_cust_contact"])) $pdf->addText(320,$y,10, $hdr["purch_cust_contact"]);

		$y = 610;
		$dy = 12;
		$pdf->addText(320,$y,10, $hdr["purch_ship_name"]." (".$hdr["purch_ship_code"].")");
		$y -= $dy;
		if (!empty($hdr["purch_ship_addr1"])) {
			$pdf->addText(320,$y,10, $hdr["purch_ship_addr1"]);
			$y -= $dy;
		}
		if (!empty($hdr["purch_ship_addr2"])) {
			$pdf->addText(320,$y,10, $hdr["purch_ship_addr2"]);
			$y -= $dy;
		}
		if (!empty($hdr["purch_ship_addr2"])) {
			$pdf->addText(320,$y,10, $hdr["purch_ship_addr3"]);
			$y -= $dy;
		}
		$ship_csz = $hdr["purch_ship_city"].", ".strtoupper($hdr["purch_ship_state"])." ".$hdr["purch_ship_zip"];
		if (!empty($ship_csz)) {
			$pdf->addText(320,$y,10, $ship_csz);
			$y -= $dy;
		}
		if (!empty($hdr["purch_ship_contact"])) $pdf->addText(320,$y,10, $hdr["purch_ship_contact"]);

		$y = 610;
		$dy = 12;
		$pdf->addText(40,$y,10, $hdr["purch_vend_name"]." (".$hdr["purch_vend_code"].")");
		$y -= $dy;
		if (!empty($hdr["purch_vend_addr1"])) {
			$pdf->addText(40,$y,10, $hdr["purch_vend_addr1"]);
			$y -= $dy;
		}
		if (!empty($hdr["purch_vend_addr2"])) {
			$pdf->addText(40,$y,10, $hdr["purch_vend_addr2"]);
			$y -= $dy;
		}
		if (!empty($hdr["purch_vend_addr2"])) {
			$pdf->addText(40,$y,10, $hdr["purch_vend_addr3"]);
			$y -= $dy;
		}
		$vend_csz = $hdr["purch_vend_city"].", ".strtoupper($hdr["purch_vend_state"])." ".$hdr["purch_vend_zip"];
		if (!empty($vend_csz)) {
			$pdf->addText(40,$y,10, $vend_csz);
			$y -= $dy;
		}
		if (!empty($hdr["purch_vend_contact"])) $pdf->addText(40,$y,10, $hdr["purch_vend_contact"]);


		$pdf->addText(60,512,10, $hdr["purch_date"]);
		$pdf->addText(150,512,10, $hdr["purch_shipvia"]);
		if ($hdr["purch_need_confirm"] == "t") $need_confirm = "Yes";
		else $need_confirm = "No";
		$pdf->addText(300,512,10, $need_confirm);
		$pdf->addText(400,512,10, $hdr["purch_user_code"]);
		if ($hdr["purch_sample_included"] == "t") $sample_included = "Yes";
		else $sample_included = "No";
		$pdf->addText(520,512,10, $sample_included);

		$comnt = $hdr["purch_comnt"];
		$width = 528;
		$arr = splitlines($comnt, $pdf, $width);
		$arr_num = count($arr);
		$k=0;
		$y = 84;
		for ($j=0;$j<$arr_num;$j++) {
			$pdf->addText(42,$y-$k*12,10, $arr[$j]["itm"]);
			if ($k>5) break;
		}
	}

// PO Lines...
	for ($j=0;$j<$lineapage;$j++) {
		$p = $i*$lineapage+$j;
		if ($p < $out_num) {
			$y = 466-$j*12;
			$pdf->addText(105-$pdf->getTextWidth(10, $out_arr[$p]["qty"]),$y,10, $out_arr[$p]["qty"]);
			$pdf->addText(134,$y,10, $out_arr[$p]["uom"]);
			$pdf->addText(190,$y,10, $out_arr[$p]["itm"]);
		}
	}
}

$pdf->Output();

//===========================================================================================================
?>