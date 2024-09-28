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

function splitlines($str, &$pdf, $width) {
	$out_arr = array();
	if (is_null($str)) {
		$len = 0;
	} else {
		$len = strlen($str);
	}
	$out_text = "";
	for ($i=0;$i<$len;$i++) {
		$tmp_text = $out_text.$str[$i];
		$line_len = $pdf->GetStringWidth($tmp_text);
		if ((ord($str[$i])==10 || $str[$i]==" " || $i == $len-1) && $i != 0) {
			$next_str = "";
			for ($j=$i+1;$j<$len;$j++) {
				if ((ord($str[$j])==10 || $str[$j]==" " || $j == $len-1) && $j != 0) {
					break;
				}
				$next_str .= $str[$i];
			}
			$next_len = $pdf->GetStringWidth($next_str);
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

function drawForm(&$pdf) {
	$pdf->SetFont("Helvetica","b",18);
	$pdf->SetXY(310,24);
	$pdf->Cell(0, 0, "Purchase Order #",0,0,"L");

	$pdf->Image('images/logo.jpg', 24,24, 101,64); //string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]]
	$pdf->SetFont("Helvetica","b",12);
	$pdf->SetXY(130,34);
	$pdf->Cell(0, 0, "Clean Air Equipment, Inc.",0,0,"L");

	$compos = 48;
	$compheight =14; 
	$pdf->SetFont("Helvetica","",10);
	$pdf->SetXY(130,$compos);
	$pdf->Cell(0, 0, "170 Roosevelt Place",0,0,"L");
	$pdf->SetXY(130,$compos+$compheight);
	$pdf->Cell(0, 0, "Palisades Park, NJ 07650",0,0,"L");
	$pdf->SetXY(130,$compos+$compheight*2);
	$pdf->Cell(0, 0, "Toll Free: 1-800-435-0581",0,0,"L");
	$pdf->SetXY(130,$compos+$compheight*3);
	$pdf->Cell(0, 0, "Tel: 201-461-9766",0,0,"L");
	$pdf->SetXY(130,$compos+$compheight*4);
	$pdf->Cell(0, 0, "Fax: (201)461-9767",0,0,"L");

	$pdf->SetFont("Helvetica","b",11);
	$pdf->SetXY(306,43);
	$pdf->Cell(0, 0, "Purchase Order for:",0,0,"L");
	$pdf->Rect(302, 34, 260, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(302, 50,260,74);

	$ypos = 130;
	$pdf->SetXY(36,$ypos+8);
	$pdf->Cell(0, 0, "Purchase Order To:",0,0,"L");
	$pdf->Rect(32, $ypos, 260, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(32, $ypos+16,260,74);
	
	$pdf->SetXY(306,$ypos+8);
	$pdf->Cell(0, 0, "Ship To:",0,0,"L");
	$pdf->Rect(302, $ypos, 260, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(302, $ypos+16,260,74);

	$ypos = 224;
	$pdf->SetXY(54,$ypos+8);
	$pdf->Cell(0, 0, "PO Date",0,0,"L");
	$pdf->Rect(32, $ypos, 90, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(32, $ypos+16, 90, 16); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(140,$ypos+8);
	$pdf->Cell(0, 0, "Ship Via",0,0,"L");
	$pdf->Rect(122, $ypos, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(122, $ypos+16, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(215,$ypos+8);
	$pdf->Cell(0, 0, "Date of Plans",0,0,"L");
	$pdf->Rect(207, $ypos, 90, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(207, $ypos+16, 90, 16); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(301,$ypos+8);
	$pdf->Cell(0, 0, "Proof Needed",0,0,"L");
	$pdf->Rect(297, $ypos, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(297, $ypos+16, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(395,$ypos+8);
	$pdf->Cell(0, 0, "Placed By",0,0,"L");
	$pdf->Rect(382, $ypos, 90, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(382, $ypos+16, 90, 16); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(470,$ypos+8);
	$pdf->Cell(0, 0, "Sample Included",0,0,"L");
	$pdf->Rect(472, $ypos, 90, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(472, $ypos+16, 90, 16); //float x, float y, float w, float h [, string style])

	$ypos = 260;
	$pdf->SetXY(52,$ypos+8);
	$pdf->Cell(0, 0, "Qty",0,0,"L");
	$pdf->Rect(32, $ypos, 75, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(32, $ypos+16, 75, 416); //float x, float y, float w, float h [, string style])	
	$pdf->SetXY(129,$ypos+8);
	$pdf->Cell(0, 0, "UOM",0,0,"L");
	$pdf->Rect(107, $ypos, 75, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(107, $ypos+16, 75, 416); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(310,$ypos+8);
	$pdf->Cell(0, 0, "Item Description",0,0,"L");
	$pdf->Rect(182, $ypos, 380, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(182, $ypos+16, 380, 416); //float x, float y, float w, float h [, string style])
	
	$ypos = 696;	
	$pdf->SetXY(52,$ypos+8);
	$pdf->Cell(0, 0, "Comments",0,0,"L");
	$pdf->Rect(32, $ypos, 530, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(32, $ypos+16, 530, 50); //float x, float y, float w, float h [, string style])	
	
}


$d = new Datex();
$ph = new Purchases();
$pd = new PurDtls();
$t = new Items();

//$pdf = new CezPdf("letter", "portrait");
//$pdf->ezSetMargins(27,27,27,27);
$pdf = new FPDF("P","pt");
$pdf->AddPage("P","Letter");
drawForm($pdf);

// Form Template....


$hdr = $ph->getPurchase($purch_id); 
$recs = $pd->getPurDtlsList($purch_id);

// PO Lines...
if ($recs) $nums = count($recs);
else $nums = 0;

$width = 370;
$out_arr = array();
for ($i=0;$i<$nums;$i++) {
	$qty = $recs[$i]["purdtl_qty"] ?? 0;
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

$lineapage = 34;
$out_num = count($out_arr);
if ($out_num >0) $page_num = ceil($out_num / $lineapage);

for ($i=0;$i<$page_num;$i++) {
	$pdf->SetFont("Helvetica","b",16);
	$pdf->Text(470,28,$hdr["purch_id"]);
	$pdf->SetFont("Helvetica","",10);
	if ($i>0) {
		$pdf->AddPage("P","Letter");
		drawForm($pdf);
	}
	if ($i==0) { //302,34
		$x = 310;
		$y = 60;
		$dy = 12;
		$pdf->Text($x,$y, $hdr["purch_cust_name"]." (".$hdr["purch_cust_code"].")");
		$y += $dy;
		if (!empty($hdr["purch_cust_addr1"])) {
			$pdf->Text($x,$y, $hdr["purch_cust_addr1"]);
			$y += $dy;
		}
		if (!empty($hdr["purch_cust_addr2"])) {
			$pdf->Text($x,$y, $hdr["purch_cust_addr2"]);
			$y += $dy;
		}
		if (!empty($hdr["purch_cust_addr2"])) {
			$pdf->Text($x,$y, $hdr["purch_cust_addr3"]);
			$y += $dy;
		}
		$cust_csz = $hdr["purch_cust_city"].", ".strtoupper($hdr["purch_cust_state"])." ".$hdr["purch_cust_zip"];
		if (!empty($cust_csz)) {
			$pdf->Text($x,$y, $cust_csz);
			$y += $dy;
		}
		if (!empty($hdr["purch_cust_contact"])) {
      		$pdf->Text($x,$y, $hdr["purch_cust_contact"]);
    	  	$y += $dy;
	    }
		if (!empty($hdr["purch_cust_tel"])) $pdf->Text($x,$y, $hdr["purch_cust_tel"]);

		$x = 40;
		$y = 156;
		$dy = 12;
		$pdf->Text(40,$y, $hdr["purch_vend_name"]." (".$hdr["purch_vend_code"].")");
		$y += $dy;
		if (!empty($hdr["purch_vend_addr1"])) {
			$pdf->Text($x,$y, $hdr["purch_vend_addr1"]);
			$y += $dy;
		}
		if (!empty($hdr["purch_vend_addr2"])) {
			$pdf->Text($x,$y, $hdr["purch_vend_addr2"]);
			$y += $dy;
		}
		if (!empty($hdr["purch_vend_addr2"])) {
			$pdf->Text($x,$y, $hdr["purch_vend_addr3"]);
			$y += $dy;
		}
		$vend_csz = $hdr["purch_vend_city"].", ".strtoupper($hdr["purch_vend_state"])." ".$hdr["purch_vend_zip"];
		if (!empty($vend_csz)) {
			$pdf->Text($x,$y, $vend_csz);
			$y += $dy;
		}
		if (!empty($hdr["purch_vend_contact"])) {
			$pdf->Text($x,$y, $hdr["purch_vend_contact"]);
			$y += $dy;
		}
		if (!empty($hdr["purch_vend_tel"])) $pdf->Text($x,$y, $hdr["purch_vend_tel"]);
		
		$x = 310;
		$y = 156;
		$dy = 12;
		$pdf->Text($x,$y, $hdr["purch_ship_name"]." (".$hdr["purch_ship_code"].")");
		$y += $dy;
		if (!empty($hdr["purch_ship_addr1"])) {
			$pdf->Text($x,$y, $hdr["purch_ship_addr1"]);
			$y += $dy;
		}
		if (!empty($hdr["purch_ship_addr2"])) {
			$pdf->Text($x,$y, $hdr["purch_ship_addr2"]);
			$y += $dy;
		}
		if (!empty($hdr["purch_ship_addr2"])) {
			$pdf->Text($x,$y, $hdr["purch_ship_addr3"]);
			$y += $dy;
		}
		$ship_csz = $hdr["purch_ship_city"].", ".strtoupper($hdr["purch_ship_state"])." ".$hdr["purch_ship_zip"];
		if (!empty($ship_csz)) {
			$pdf->Text($x,$y, $ship_csz);
			$y += $dy;
		}
		if (!empty($hdr["purch_ship_contact"])) {
      		$pdf->Text($x,$y, $hdr["purch_ship_contact"]);
      		$y += $dy;
		}
		if (!empty($hdr["purch_ship_tel"])) $pdf->Text($x,$y, $hdr["purch_ship_tel"]);


		$x = 50;
		$y = 252;

		$pdf->Text($x,$y, $hdr["purch_date"] ?? "");
		$pdf->Text($x+80,$y, $hdr["purch_shipvia"] ?? "");
		$pdf->Text($x+164,$y, $hdr["purch_prom_date"] ?? "");
		if ($hdr["purch_need_confirm"] == "t") $need_confirm = "Yes";
		else $need_confirm = "No";
		$pdf->Text($x+280,$y, $need_confirm);
		$pdf->Text($x+340,$y, $hdr["purch_user_code"]);
		if ($hdr["purch_sample_included"] == "t") $sample_included = "Yes";
		else $sample_included = "No";
		$pdf->Text($x+460,$y, $sample_included);

		$x = 42;
		$y = 722;
		$dy = 12;
		$width = 510;
		$comnt = $hdr["purch_comnt"] ?? "";
		$arr = splitlines($comnt, $pdf, $width);
		$arr_num = count($arr);
		for ($j=0;$j<$arr_num;$j++) {
			$pdf->Text($x,$y+$j*$dy, $arr[$j]["itm"]);
			if ($j>=3) break;
		}
	}

// PO Lines...
	$y = 280;
	$dy = 12;
	for ($j=0;$j<$lineapage;$j++) {
		$p = $i*$lineapage+$j;
		if ($p < $out_num) {
			$y += $dy;
			$pdf->Text(105-$pdf->GetStringWidth($out_arr[$p]["qty"]),$y, $out_arr[$p]["qty"]);
			$pdf->Text(134,$y, $out_arr[$p]["uom"]);
			$pdf->Text(190,$y, $out_arr[$p]["itm"]);
		}
	}
}
$pdf->Output();


//===========================================================================================================
?>
