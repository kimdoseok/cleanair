<?php
include_once("class/class.customers.php");
include_once("class/class.requests.php");
include_once("class/class.datex.php");
//include_once("class/class.ezpdf.php");
include_once("class/fpdf.php");
include_once("class/class.picks.php");
include_once("class/class.pickdtls.php");
include_once("class/class.items.php");

function splitlines($str, &$pdf, $width) {
	$out_arr = array();
	if (empty($str)) {
		$str = "";
	}
	$len = strlen($str);
	$out_text = "";
	for ($i=0;$i<$len;$i++) {
		$tmp_text = $out_text.$str[$i];
		$line_len = $pdf->GetStringWidth($tmp_text);
//echo $str[$i];
//echo " : ";
//echo ord($str[$i]);
//echo "<br>";
		if ((ord($str[$i])==10 || $str[$i]==" " || $i == $len-1) && $i != 0) {
			$next_str = "";
			for ($j=$i+1;$j<$len;$j++) {
				if ((ord($str[$j])==10 || $str[$j]==" " || $j == $len-1) && $j != 0) {
					break;
				}
				$next_str .= $str[$j];
			}
			$next_len = $pdf->GetStringWidth($next_str);
			if ($next_len+$line_len>$width || ord($str[$i])==10) {
//echo "$out_text ($next_len+$line_len>$width) <br>";
				$tmp_arr = array();
				$tmp_arr["qty"] = "";
				$tmp_arr["prc"] = "";
				$tmp_arr["ext"] = "";
				$tmp_arr["itm"] = $out_text ;
				array_push($out_arr, $tmp_arr);
				$out_text = "";
			} else {
				$out_text .= $str[$i];
			}
			if ($i == $len-1) {
				$tmp_arr = array();
				$tmp_arr["qty"] = "";
				$tmp_arr["prc"] = "";
				$tmp_arr["ext"] = "";
				$tmp_arr["itm"] = $tmp_text ;
				array_push($out_arr, $tmp_arr);
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
			$out_text = $str[$i];
			continue;
		}
		$out_text .= $str[$i];
	}
	return $out_arr;
}


function splitlines1($str, $pdf, $width) {
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
			$next_len = $pdf->GetStringWidth($next_str);
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
				$out_text .= $str[$i];
			}
			if ($i == $len-1) {
				$tmp_arr = array();
				$tmp_arr["qty"] = "";
				$tmp_arr["prc"] = "";
				$tmp_arr["ext"] = "";
				$tmp_arr["itm"] = $tmp_text ;
				array_push($out_arr, $tmp_arr);
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
			$out_text = $str[$i];
			continue;
		}
		$out_text .= $str[$i];
	}
	return $out_arr;
}

function drawForm(&$pdf) {
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
	$pdf->Rect(32, 246, 285, 416); //float x, float y, float w, float h [, string style])
	
	$pdf->SetXY(330,238);
	$pdf->Cell(0, 0, "Quantity",0,0,"L");
	$pdf->Rect(317, 230, 75, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(317, 246, 75, 416); //float x, float y, float w, float h [, string style])
	
	$pdf->SetXY(416,238);
	$pdf->Cell(0, 0, "Price",0,0,"L");
	$pdf->Rect(392, 230, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(392, 246, 85, 416); //float x, float y, float w, float h [, string style])
	
	$pdf->SetXY(490,238);
	$pdf->Cell(0, 0, "Extension",0,0,"L");
	$pdf->Rect(477, 230, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(477, 246, 85, 416); //float x, float y, float w, float h [, string style])
	
	
	$pdf->SetXY(394,673);
	$pdf->Cell(0, 0, "Equipment",0,0,"L");
	$pdf->Rect(392, 665, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(477, 665, 85, 16); //float x, float y, float w, float h [, string style])
	
	$pdf->SetXY(394,688);
	$pdf->Cell(0, 0, "Sub Total",0,0,"L");
	$pdf->Rect(392, 681, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(477, 681, 85, 16); //float x, float y, float w, float h [, string style])
	
	$pdf->SetXY(394,704);
	$pdf->Cell(0, 0, "Tax",0,0,"L");
	$pdf->Rect(392, 697, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(477, 697, 85, 16); //float x, float y, float w, float h [, string style])
	
	$pdf->SetXY(394,720);
	$pdf->Cell(0, 0, "Deposit",0,0,"L");
	$pdf->Rect(392, 713, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(477, 713, 85, 16); //float x, float y, float w, float h [, string style])
	
	$pdf->SetXY(394,735);
	$pdf->Cell(0, 0, "Grand Total",0,0,"L");
	$pdf->Rect(392, 729, 85, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(477, 729, 85, 16); //float x, float y, float w, float h [, string style])
	
	$pdf->Rect(32, 665, 360, 80); //float x, float y, float w, float h [, string style])
	
}

$d = new Datex();
$ph = new Picks();
$pd = new PickDtls();
$cu = new Custs();
$t = new Items();

//$pdf = new CezPdf("letter", "portrait");
//$pdf->ezSetMargins(27,27,27,27);
$pdf = new FPDF("P","pt");
$pdf->AddPage("P","Letter");
drawForm($pdf);

// Form Template....
/*
$allbox = $pdf->openObject();
$pdf->saveState();
$pdf->setLineStyle(0.5);
$pdf->setStrokeColor(0,0,0,0);
$boldFont = 'class/fonts/Helvetica-Bold.afm';
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($boldFont);
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($allbox,'all');
*/

/*

// for each pages
$pagetext = $pdf->openObject();
$pdf->saveState();
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($font);
*/

$hdr = $ph->getPicks($_GET["pick_id"]); 
$recs = $pd->getPickDtlsList($_GET["pick_id"]);
$cu_arr = $cu->getCusts($hdr["pick_cust_code"]);

// PO Lines...
if ($recs) $nums = count($recs);
else $nums = 0;

$width = 280;
$out_arr = array();
$equip_total = 0;
for ($i=0;$i<$nums;$i++) {
	$it_arr = $t->getItems($recs[$i]["slsdtl_item_code"]);
	$q = $recs[$i]["slsdtl_qty"]+0;
	$qty = $q." ".strtoupper($recs[$i]["slsdtl_unit"]);
	$prc = number_format($recs[$i]["pickdtl_cost"],2,".",",");
	$ext = number_format($recs[$i]["pickdtl_qty"]*$recs[$i]["pickdtl_cost"],2,".",",");
//	$desc = $recs[$i]["slsdtl_item_code"].": ".$recs[$i]["slsdtl_item_desc"] ;
	$desc = $recs[$i]["slsdtl_item_desc"] ;
	if (strtolower($it_arr["item_type"])=="e") $equip_total += $recs[$i]["pickdtl_qty"]*$recs[$i]["pickdtl_cost"];

	$arr = splitlines($desc, $pdf, $width);
	$arr_num = count($arr);
	for ($j=0;$j<$arr_num;$j++) {
		if ($j==0) {
			$arr[0]["qty"] = $qty;
			$arr[0]["prc"] = $prc;
			$arr[0]["ext"] = $ext;
		}
		array_push($out_arr, $arr[$j]);
	}
}
$arr = splitlines($hdr["pick_comnt"], $pdf, $width);
$arr_num = count($arr);
for ($j=0;$j<$arr_num;$j++) array_push($out_arr, $arr[$j]);

$lineapage = 34;
if ($out_arr) $out_num = count($out_arr);
else $out_num = 0;

if ($out_num >0) $page_num = ceil($out_num / $lineapage);
else $page_num = 0;

for ($i=0;$i<$page_num;$i++) {
	if ($i>0) {
		$pdf->AddPage("P","Letter");
		drawForm($pdf);
	
	}
// PO Header...
	if ($i>0) $pdf->AddPage();
	$pdf->Text(510,35,$hdr["pick_id"]);
	$pagestr = ($i+1)." of ".$page_num." pages";
	$pdf->Text(420,85,$pagestr);

	if ($i==0) {
		$y = 126;
		$dy = 12;
		$pdf->Text(40,$y,$cu_arr["cust_name"]." (".$cu_arr["cust_code"].")");
		//$pdf->addText(40,$y,10, $cu_arr["cust_name"]." (".$cu_arr["cust_code"].")");
		$y += $dy;
		if (!empty($cu_arr["cust_addr1"])) {
			$pdf->Text(40,$y,$cu_arr["cust_addr1"]);
			//$pdf->addText(40,$y,10, $cu_arr["cust_addr1"]);
			$y += $dy;
		}
		if (!empty($cu_arr["cust_addr2"])) {
			$pdf->Text(40,$y,$cu_arr["cust_addr2"]);
			//$pdf->addText(40,$y,10, $cu_arr["cust_addr2"]);
			$y += $dy;
		}
		if (!empty($cu_arr["cust_addr2"])) {
			$pdf->Text(40,$y,$cu_arr["cust_addr3"]);
			//$pdf->addText(40,$y,10, $cu_arr["cust_addr3"]);
			$y += $dy;
		}
		$cust_csz = $cu_arr["cust_city"].", ".strtoupper($cu_arr["cust_state"])." ".$cu_arr["cust_zip"];
		if (!empty($cust_csz)) {
			$pdf->Text(40,$y,$cust_csz);
			//$pdf->addText(40,$y,10, $cust_csz);
			$y += $dy;
		}

		$y = 126;
		$dy = 12;
		$pdf->Text(320,$y,$hdr["pick_name"]." (".$hdr["pick_cust_code"].")");
		//$pdf->addText(320,$y,10, $hdr["pick_name"]." (".$hdr["pick_cust_code"].")");
		$y += $dy;
		if (!empty($hdr["pick_addr1"])) {
			$pdf->Text(40,$y,$cu_arr["cust_addr1"]);
			//$pdf->addText(320,$y,10, $hdr["pick_addr1"]);
			$y += $dy;
		}
		if (!empty($hdr["pick_addr2"])) {
			$pdf->Text(40,$y,$cu_arr["pick_addr2"]);
			//$pdf->addText(320,$y,10, $hdr["pick_addr2"]);
			$y += $dy;
		}
		if (!empty($hdr["pick_addr2"])) {
			$pdf->Text(40,$y,$cu_arr["pick_addr3"]);
			//$pdf->addText(320,$y,10, $hdr["pick_addr3"]);
			$y += $dy;
		}
		$vend_csz = $hdr["pick_city"].", ".strtoupper($hdr["pick_state"])." ".$hdr["pick_zip"];
		if (!empty($vend_csz)) {
			$pdf->Text(40,$y,$vend_csz);
			//$pdf->addText(320,$y,10, $vend_csz);
			$y += $dy;
		}
		$pdf->Text(38,218,$hdr["pick_tel"]);
		$pdf->Text(135,218,$hdr["pick_date"]);
		$pdf->Text(220,218,$hdr["pick_prom_date"]);

		$amt = $hdr["pick_amt"]+$hdr["pick_tax_amt"]+$hdr["pick_freight_amt"]-$hdr["pick_deposit_amt"];
		$pdf->Text(554-$pdf->GetStringWidth(number_format($amt,2,".",",")),218,number_format($amt,2,".",","));
		//$pdf->addText(574-$pdf->getTextWidth(10,number_format($amt,2,".",",")),578,10, number_format($amt,2,".",","));
	}

// PO Lines...
	for ($j=0;$j<$lineapage;$j++) {
		$p = $i*$lineapage+$j;
		if ($p < $out_num) {
			$y = 260+$j*12;
			$pdf->Text(40,$y,$out_arr[$p]["itm"]);
			settype($out_arr[$p]["qty"],"float");
			settype($out_arr[$p]["prc"],"float");
			settype($out_arr[$p]["ext"],"float");
			if ($out_arr[$p]["qty"]*$out_arr[$p]["prc"]==0) {
				continue;
			}
			$qty = number_format($out_arr[$p]["qty"],0,".",",");
			$prc = number_format($out_arr[$p]["prc"],2,".",",");
			$ext = number_format($out_arr[$p]["ext"],2,".",",");
			$pdf->Text(382-$pdf->GetStringWidth($qty),$y,$qty);
			$pdf->Text(468-$pdf->GetStringWidth($prc),$y,$prc);
			$pdf->Text(554-$pdf->GetStringWidth($ext),$y,$ext);
		}
	}

	if ($i+1==$page_num) {
		$eamt = number_format($equip_total, 2, ",",".");
		$pamt = number_format($hdr["pick_amt"], 2, ",",".");
		$tamt = number_format($hdr["pick_tax_amt"], 2, ",",".");
		$damt = number_format($hdr["pick_deposit_amt"], 2, ",",".");
		$gamt = number_format($amt, 2, ",",".");
		$y = 677;
		$dy = 16;
		$pdf->Text(554-$pdf->GetStringWidth($eamt),$y,$eamt);
		$y += $dy;
		$pdf->Text(554-$pdf->GetStringWidth($pamt),$y,$pamt);
		$y += $dy;
		$pdf->Text(554-$pdf->GetStringWidth($tamt),$y,$tamt);
		$y += $dy;
		$pdf->Text(554-$pdf->GetStringWidth($damt),$y,$damt);
		$y += $dy;
		$pdf->Text(554-$pdf->GetStringWidth($gamt),$y,$gamt);
	}

}
$pdf->Output();

/*
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($pagetext,'add');

$pdf->ezStream();
$pdf->
*/
//===========================================================================================================
?>