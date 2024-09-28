<?php
include_once("class/class.customers.php");
include_once("class/class.requests.php");
include_once("class/class.datex.php");
include_once("class/fpdf.php");
//include_once("class/class.ezpdf.php");
include_once("class/class.sales.php");
include_once("class/class.saledtls.php");
include_once("class/class.items.php");
include_once("class/register_globals.php");

function splitlines($str, &$pdf, $width) {
	$out_arr = array();
	if (is_null($str)) $str = "";
	$len = strlen($str);
	$out_text = "";

	for ($i=0;$i<$len;$i++) {
		$tmp_text = $out_text.$str[$i];
		$line_len = $pdf->GetStringWidth($tmp_text);
//echo $str{$i};
//echo " : ";
//echo ord($str{$i});
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
function splitTextlines($str, $width) {
	$out_arr = array();
	if (is_null($str)) $str = "";
	$slen = strlen($str);
	$out_text = "";

	for ($i=0;$i<$slen;$i++) {
		$line_len = strlen($out_text.$str[$i]);
		if ($line_len>=$width) {
			array_push($out_arr,$out_text);
			$out_text = "".$str[$i];
		} else {
			$out_text = $out_text.$str[$i];
		}
	}
	if (strlen($out_text)>0) {
		array_push($out_arr,$out_text);
	} else if (count($out_arr)==0) {
		array_push($out_arr,"");
	}
	return $out_arr;
}
function pageTemplate(&$pdf) {
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
		
}

$d = new Datex();
$sh = new Sales();
$sd = new SaleDtls();
$cu = new Custs();
$t = new Items();

//$pdf = new CezPdf("letter", "portrait");
//$pdf->ezSetMargins(27,27,27,27);
$pdf = new FPDF("P","pt");
$pdf->AddPage("P","Letter");

$hdr = $sh->getSales($sale_id); 
$recs = $sd->getSaleDtlsListEx($sale_id);
$cu_arr = $cu->getCusts($hdr["sale_cust_code"]);
//print_r($hdr);

// PO Lines...
if ($recs) $nums = count($recs);
else $nums = 0;

$width = 280;
$out_arr = array();
$equip_total = 0;
$other_total = 0;

for ($i=0;$i<$nums;$i++) {
	$q = 0+$recs[$i]["slsdtl_qty"];
	$qty = $q." ".strtoupper($recs[$i]["slsdtl_unit"]);
	$prc = number_format($recs[$i]["slsdtl_cost"],2,".",",");
	$ext = number_format($recs[$i]["slsdtl_qty"]*$recs[$i]["slsdtl_cost"],2,".",",");
//	$desc = $recs[$i]["slsdtl_item_code"].": ".$recs[$i]["slsdtl_item_desc"] ;
	$desc = $recs[$i]["slsdtl_item_desc"] ;
	$it_arr = $t->getItems($recs[$i]["slsdtl_item_code"]);
	if ($it_arr["item_type"]=="e") $equip_total += round($recs[$i]["slsdtl_qty"]*$recs[$i]["slsdtl_cost"]*100)/100;
	else $other_total += round($recs[$i]["slsdtl_qty"]*$recs[$i]["slsdtl_cost"]*100)/100;
	//$arr = splitlines($desc, $pdf, $width);
	$arr = splitTextlines($desc, $width);
	$arr_num = count($arr);
	for ($j=0;$j<$arr_num;$j++) {
		$anarr = array("itm"=>$arr[$j],"qty"=>"","prc"=>"","ext"=>"");
		if ($j==0) {
			$anarr["qty"] = $qty;
			$anarr["prc"] = $prc;
			$anarr["ext"] = $ext;
		}
		array_push($out_arr, $anarr);
	}
}

$tmp = array();
$tmp["itm"] = "*** Equipment Total: ".number_format($equip_total,2,".",",");
$tmp["qty"] = "";
$tmp["prc"] = "";
$tmp["ext"] = "";
array_push($out_arr, $tmp);

if ($hdr["sale_freight_amt"] != 0) {
	$tmp = array();
	$tmp["itm"] = "*** Freight : ";
	$tmp["qty"] = "";
	$tmp["prc"] = "";
	$tmp["ext"] = number_format($hdr["sale_freight_amt"],2,".",",");
	array_push($out_arr, $tmp);
}

if ($hdr["sale_tax_amt"] != 0) {
	$tmp = array();
	$tmp["itm"] = "*** Sales Tax : ";
	$tmp["qty"] = "";
	$tmp["prc"] = "";
	$tmp["ext"] = number_format($hdr["sale_tax_amt"],2,".",",");
	array_push($out_arr, $tmp);
}

if ($hdr["sale_deposit_amt"] != 0) {
	$tmp = array();
	$tmp["itm"] = "*** Deposit : ";
	$tmp["qty"] = "";
	$tmp["prc"] = "";
	$tmp["ext"] = number_format($hdr["sale_deposit_amt"]*-1,2,".",",");
	array_push($out_arr, $tmp);
}

//$arr = splitlines($hdr["sale_comnt"], $pdf, $width);
$arr = splitTextlines($hdr["sale_comnt"], $width);

$arr_num = count($arr);
for ($j=0;$j<$arr_num;$j++) {
	$tmp_arr = array("itm"=>$arr[$j],"qty"=>"","prc"=>"","ext"=>"");
	array_push($out_arr, $tmp_arr);
} 

$lineapage = 26;
if ($out_arr) $out_num = count($out_arr);
else $out_num = 0;

if ($out_num >0) $page_num = ceil($out_num / $lineapage);
else $page_num = 0;

for ($i=0;$i<$page_num;$i++) {
// PO Header...
	pageTemplate($pdf);

	if ($i>0) $pdf->AddPage();
	$pdf->SetFont("Helvetica","",12);
	$pdf->Text(530,36,$hdr["sale_id"]);
	$pdf->SetFont("Helvetica","",10);
	$pdf->Text(450,70,($i+1)." of ".$page_num." pages");

	if ($i==0) {
		$y = 126;
		$dy = 12;
		$pdf->Text(40,$y, $cu_arr["cust_name"]." (".$cu_arr["cust_code"].")");
		$y += $dy;
		if (!empty($cu_arr["cust_addr1"])) {
			$pdf->Text(40,$y, $cu_arr["cust_addr1"]);
			$y += $dy;
		}
		if (!empty($cu_arr["cust_addr2"])) {
			$pdf->Text(40,$y, $cu_arr["cust_addr2"]);
			$y += $dy;
		}
		if (!empty($cu_arr["cust_addr2"])) {
			$pdf->Text(40,$y, $cu_arr["cust_addr3"]);
			$y += $dy;
		}
		$cust_csz = $cu_arr["cust_city"].", ".strtoupper($cu_arr["cust_state"])." ".$cu_arr["cust_zip"];
		if (!empty($cust_csz)) {
			$pdf->Text(40,$y, $cust_csz);
			$y += $dy;
		}

		$y = 126;
		$dy = 12;
		$pdf->Text(320,$y, $hdr["sale_name"]." (".$hdr["sale_cust_code"].")");
		$y += $dy;
		if (!empty($hdr["sale_addr1"])) {
			$pdf->Text(320,$y, $hdr["sale_addr1"]);
			$y += $dy;
		}
		if (!empty($hdr["sale_addr2"])) {
			$pdf->Text(320,$y, $hdr["sale_addr2"]);
			$y += $dy;
		}
		if (!empty($hdr["sale_addr2"])) {
			$pdf->Text(320,$y, $hdr["sale_addr3"]);
			$y += $dy;
		}
		$vend_csz = $hdr["sale_city"].", ".strtoupper($hdr["sale_state"])." ".$hdr["sale_zip"];
		if (!empty($vend_csz)) {
			$pdf->Text(320,$y, $vend_csz);
			$y += $dy;
		}

		$pdf->Text(48,218, $hdr["sale_tel"]);
		$pdf->Text(145,218, $hdr["sale_date"]);
		$pdf->Text(240,218, $hdr["sale_prom_date"]);
		$pdf->Text(320,218, $hdr["sale_job"]);
		$pdf->Text(410,218, $hdr["sale_ref"]);
		$amt = number_format($hdr["sale_amt"]+$hdr["sale_tax_amt"]+$hdr["sale_freight_amt"]-$hdr["sale_deposit_amt"],2,".",",");
		$pdf->Text(550-$pdf->GetStringWidth($amt),218, $amt);
	}

// PO Lines...
	for ($j=0;$j<$lineapage;$j++) {
		$p = $i*$lineapage+$j;
		if ($p < $out_num) {
			$y = 260+$j*12;
			
			$pdf->Text(40,$y, $out_arr[$p]["itm"]);
			$pdf->Text(384-$pdf->GetStringWidth($out_arr[$p]["qty"]),$y, $out_arr[$p]["qty"]);
			$pdf->Text(466-$pdf->GetStringWidth($out_arr[$p]["prc"]),$y, $out_arr[$p]["prc"]);
			$pdf->Text(554-$pdf->GetStringWidth($out_arr[$p]["ext"]),$y, $out_arr[$p]["ext"]);
		}
	}
}
$pdf->Output();

/*
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($pagetext,'add');

$pdf->ezStream();
*/

//===========================================================================================================
// Form Template....
/*
$allbox = $pdf->openObject();
$pdf->saveState();
$pdf->setLineStyle(0.5);
$pdf->setStrokeColor(0,0,0,0);
$boldFont = 'class/fonts/Helvetica-Bold.afm';
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($boldFont);
$pdf->addText(380,744,18,"Contract #");
if (file_exists('images/logo.jpg')) $pdf->addJpegFromFile('images/logo.jpg',27,705,100);
$pdf->addText(140,750,12,"Clean Air Equipment, Inc.");
$pdf->selectFont($font);
$pdf->addText(140,736,10, "170 Roosevelt Place, Palisades Park, NJ 07650");
$pdf->addText(140,724,10, "Toll Free: 1-800-435-0581, Tel: 201-461-9766");
$pdf->addText(140,712,10, "Fax: (201)461-9767");
$pdf->selectFont($boldFont);

$pdf->rectangle(32,686,270,16);
$pdf->rectangle(32,612,270,74);
$pdf->addText(38,690,10, "Sold To:");

$pdf->rectangle(312,686,270,16);
$pdf->rectangle(312,612,270,74);
$pdf->addText(320,690,10, "Ship To:");

$pdf->rectangle(32,592,92,16);
$pdf->rectangle(32,572,92,20);
$pdf->addText(60,596,10, "Phone");

$pdf->rectangle(124,592,92,16);
$pdf->rectangle(124,572,92,20);
$pdf->addText(160,596,10, "Date");

$pdf->rectangle(216,592,92,16);
$pdf->rectangle(216,572,92,20);
$pdf->addText(232,596,10, "Date of Plans");

$pdf->rectangle(308,592,90,16);
$pdf->rectangle(308,572,90,20);
$pdf->addText(340,596,10, "Job#");

$pdf->rectangle(398,592,92,16);
$pdf->rectangle(398,572,92,20);
$pdf->addText(422,596,10, "Archtect");

$pdf->rectangle(490,592,92,16);
$pdf->rectangle(490,572,92,20);
$pdf->addText(522,596,10, "Total");

$pdf->rectangle(32,552,303,16);
$pdf->rectangle(32,232,303,320);
$pdf->addText(160,556,10, "Description");

$pdf->rectangle(335,552,75,16);
$pdf->rectangle(335,232,75,320);
$pdf->addText(366,556,10, "Qty");

$pdf->rectangle(410,552,84,16);
$pdf->rectangle(410,232,84,320);
$pdf->addText(440,556,10, "Price");

$pdf->rectangle(494,552,88,16);
$pdf->rectangle(494,232,88,320);
$pdf->addText(514,556,10, "Extension");


//$pdf->rectangle(32,158,550,70);
$boldFont = 'class/fonts/Times-Bold.afm';
$pdf->selectFont($boldFont);
$pdf->addText(40,218,14, "We Propose");
$pdf->selectFont($font);
$pdf->addText(120,218,10, "hereby to furnish material and labor - complete in accordance with above specifications, for the sum of:");
$pdf->addText(420,198,10, "dollars (                                           )");
$pdf->line(40,198,410,198);
$pdf->addText(40,190,7, "Payment to be made as follows:");
$pdf->line(40,178,570,178);
$pdf->rectangle(32,88,550,70);
$pdf->addText(40,120,8, "and other necessary insurance.  Our workers are fully covered by Workman's Compensation Insurance.");
$pdf->selectFont($boldFont);
$pdf->addText(40,92,9, "Authorized Signature");
$pdf->selectFont($font);
$pdf->addText(280,92,9, "This proposal may be withdrawn by us if not accepted within");
$pdf->addText(556,92,9, "days.");

$pdf->rectangle(32,18,550,70);
$pdf->selectFont($boldFont);
$pdf->addText(40,74,14, "Acceptance of Proposal");
$pdf->selectFont($font);
$pdf->addText(40,60,9, "The above prices, specifications and conditions are satisfactory and are hereby accept");
$pdf->addText(400,60,10, "Signature:");
$pdf->line(448,56,578,56);
$pdf->addText(40,48,9, "You are authorized to do the work as specified.  Payment will be made as outlined above.");

$pdf->addText(40,24,10, "Date of Acceptance:");
$pdf->addText(400,24,10, "Signature:");

$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($allbox,'all');

// for each pages
$pagetext = $pdf->openObject();
$pdf->saveState();
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($font);
*/

?>