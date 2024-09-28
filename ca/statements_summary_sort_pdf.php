<?php
include_once("class/class.customers.php");
include_once("class/class.requests.php");
include_once("class/class.picks.php");
include_once("class/class.pickdtls.php");
include_once("class/class.cmemo.php");
include_once("class/class.cmemodtls.php");
include_once("class/class.receipt.php");
include_once("class/class.datex.php");
include_once("class/fpdf.php");
//include_once("class/class.ezpdf.php");
 
$vars = array("zero_balance");
foreach ($vars as $var) {
	$$var = "";
} 

include_once("class/register_globals.php");

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
    $pdf->Rect(32, 214, 85, 510); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(220,206);
    $pdf->Cell(0, 0, "Transaction",0,0,"L");
    $pdf->Rect(117, 198, 275, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(117, 214, 275, 510); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(410,206);
    $pdf->Cell(0, 0, "Amount",0,0,"L");
    $pdf->Rect(392, 198, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(392, 214, 85, 510); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(496,206);
    $pdf->Cell(0, 0, "Balance",0,0,"L");
    $pdf->Rect(477, 198, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(477, 214, 85, 510); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(54,732);
    $pdf->Cell(0, 0, "Current",0,0,"L");
    $pdf->Rect(32, 724, 90, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(32, 740, 90, 22); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(134,732);
    $pdf->Cell(0, 0, "1~30 Days",0,0,"L");
    $pdf->Rect(122, 724, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(122, 740, 85, 22); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(220,732);
    $pdf->Cell(0, 0, "31~60 Days",0,0,"L");
    $pdf->Rect(207, 724, 90, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(207, 740, 90, 22); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(310,732);
    $pdf->Cell(0, 0, "61~90 Days",0,0,"L");
    $pdf->Rect(297, 724, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(297, 740, 85, 22); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(390,732);
    $pdf->Cell(0, 0, "Over 90 Days",0,0,"L");
    $pdf->Rect(382, 724, 90, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(382, 740, 90, 22); //float x, float y, float w, float h [, string style])
    $pdf->SetXY(484,732);
    $pdf->Cell(0, 0, "Amount Due",0,0,"L");
    $pdf->Rect(472, 724, 90, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(472, 740, 90, 22); //float x, float y, float w, float h [, string style])
}

$cust_num = count($cust_arr);
$time_limit = 10 * $cust_num + 30;
set_time_limit($time_limit);
$first_pb = 1;

//$pdf = new CezPdf("letter", "portrait");
//$pdf->ezSetMargins(27,27,27,27);
$pdf = new FPDF("P","pt");
$pdf->AddPage("P","Letter");
pageTemplate($pdf);

$cutoff_digit = 100;

for ($i=0;$i<$cust_num;$i++) {
	$pick_arr = $p->getPicksStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
	$rcpt_arr = $r->getReceiptStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
	$cmemo_arr = $cm->getCmemoStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);

	$out_arr = array();
	if ($pick_arr) $pick_num = count($pick_arr);
	else $pick_num = 0;
	for ($j=0;$j<$pick_num;$j++) {
		$tmp_arr = array();
		array_push($tmp_arr, "I");
		$isodate = $d->toIsoDate($pick_arr[$j]["pick_date"]);
		array_push($tmp_arr, $isodate);
		array_push($tmp_arr, $pick_arr[$j]);
		array_push($out_arr, $tmp_arr);
	}
	if ($cmemo_arr) $cmemo_num = count($cmemo_arr);
	else $cmemo_num = 0;
	for ($j=0;$j<$cmemo_num;$j++) {
		$isodate = $d->toIsoDate($cmemo_arr[$j]["cmemo_date"]);
		$tmpout_arr = array();
		if ($out_arr) $out_num = count($out_arr);
		else $out_num = 0;
		$action = False;
		for ($k=0;$k<$out_num;$k++) {
			if ($out_arr[$k][1]>$isodate and $action==False) {
				$tmp_arr = array();
				array_push($tmp_arr, "C");
				array_push($tmp_arr, $isodate);
				array_push($tmp_arr, $cmemo_arr[$j]);
				array_push($tmpout_arr, $tmp_arr);
				$action = True;
			}
			array_push($tmpout_arr, $out_arr[$k]);
		}
		if ($action==False) {
			$tmp_arr = array();
			array_push($tmp_arr, "C");
			array_push($tmp_arr, $isodate);
			array_push($tmp_arr, $cmemo_arr[$j]);
			array_push($tmpout_arr, $tmp_arr);
		}
		$out_arr = $tmpout_arr;
		unset($tmpout_arr);
	}
	if ($rcpt_arr) $rcpt_num = count($rcpt_arr);
	else $rcpt_num = 0;
	for ($j=0;$j<$rcpt_num;$j++) {
		$isodate = $d->toIsoDate($rcpt_arr[$j]["rcpt_date"]);
		$tmpout_arr = array();
		if ($out_arr) $out_num = count($out_arr);
		else $out_num = 0;
		$action = False;
		for ($k=0;$k<$out_num;$k++) {
			if ($out_arr[$k][1]>$isodate and $action==False) {
				$tmp_arr = array();
				array_push($tmp_arr, "R");
				array_push($tmp_arr, $isodate);
				array_push($tmp_arr, $rcpt_arr[$j]);
				array_push($tmpout_arr, $tmp_arr);
				$action = True;
			}
			array_push($tmpout_arr, $out_arr[$k]);
		}
		if ($action==False) {
			$tmp_arr = array();
			array_push($tmp_arr, "R");
			array_push($tmp_arr, $isodate);
			array_push($tmp_arr, $rcpt_arr[$j]);
			array_push($tmpout_arr, $tmp_arr);
		}
		$out_arr = $tmpout_arr;
		unset($tmpout_arr);
	}

	$pick_frwd_sum = $p->getPicksSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$rcpt_frwd_sum = $r->getReceiptSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$cmemo_frwd_sum = $cm->getCmemoSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$bal_forwarded = $cust_arr[$i]["cust_init_bal"]+$pick_frwd_sum-$rcpt_frwd_sum-$cmemo_frwd_sum;

	$pick_sum = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
	$rcpt_sum = $r->getReceiptSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
	$cmemo_sum = $cm->getCmemoSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");

	$pick_frwd_sum = round($pick_frwd_sum*$cutoff_digit)/$cutoff_digit;
	$rcpt_frwd_sum = round($rcpt_frwd_sum*$cutoff_digit)/$cutoff_digit;
	$cmemo_frwd_sum = round($cmemo_frwd_sum*$cutoff_digit)/$cutoff_digit;
	$bal_forwarded = round($bal_forwarded*$cutoff_digit)/$cutoff_digit;
	$pick_sum = round($pick_sum*$cutoff_digit)/$cutoff_digit;
	$rcpt_sum = round($rcpt_sum*$cutoff_digit)/$cutoff_digit;
	$cmemo_sum = round($cmemo_sum*$cutoff_digit)/$cutoff_digit;

	$balance = $bal_forwarded + $pick_sum - $rcpt_sum - $cmemo_sum;
	$balance = round($balance*$cutoff_digit)/$cutoff_digit;
	
	$pick_num = count($pick_arr);
	$rcpt_num = count($rcpt_arr);
	$cmemo_num = count($cmemo_arr);

	$day0 = date("Y-m-d");
	$day30 = $d->getIsoDate($day0,30,"b");
	$day60 = $d->getIsoDate($day0,60,"b");
	$day90 = $d->getIsoDate($day0,90,"b");
	$created = $d->toIsoDate($cust_arr[$i]["cust_created"]);

	$pick90over = $p->getPicksSumAged($cust_arr[$i]["cust_code"], "", $day90, "t", "f");
	$pick90 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day90, $day60, "t", "f");
	$pick60 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day60, $day30, "t", "f");
	$pick30 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day30, $day0, "t", "t");
	$pick0 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day0, "", "f", "t");

	$pick90over = round($pick90over*$cutoff_digit)/$cutoff_digit;
	$pick90 = round($pick90*$cutoff_digit)/$cutoff_digit;
	$pick60 = round($pick60*$cutoff_digit)/$cutoff_digit;
	$pick30 = round($pick30*$cutoff_digit)/$cutoff_digit;
	$pick0 = round($pick0*$cutoff_digit)/$cutoff_digit;

	if (strtotime($created) <= strtotime($day0) && strtotime($created) > strtotime($day30) ) $pick30 += $cust_arr[$i]["cust_init_bal"];
	else if (strtotime($created) <= strtotime($day30) && strtotime($created) > strtotime($day60) ) $pick60 += $cust_arr[$i]["cust_init_bal"];
	if (strtotime($created) <= strtotime($day60) && strtotime($created) > strtotime($day90) ) $pick90 += $cust_arr[$i]["cust_init_bal"];
	if (strtotime($created) <= strtotime($day90)) $pick90over += $cust_arr[$i]["cust_init_bal"];

	$t_bal = $balance;
	if ($t_bal > $pick0) {
		$bal0 = $pick0;
		$t_bal -= $bal0;
	} else {
		$bal0 = $t_bal;
		$t_bal = 0;
	}
	if ($t_bal > $pick30) {
		$bal30 = $pick30;
		$t_bal -= $bal30;
	} else {
		$bal30 = $t_bal;
		$t_bal = 0;
	}
	if ($t_bal > $pick60) {
		$bal60 = $pick60;
		$t_bal -= $bal60;
	} else {
		$bal60 = $t_bal;
		$t_bal = 0;
	}
	if ($t_bal > $pick90) {
		$bal90 = $pick90;
		$t_bal -= $bal90;
	} else {
		$bal90 = $t_bal;
		$t_bal = 0;
	}
	$bal90over = $t_bal;

	if ($zero_balance == "z" || ($zero_balance != "z" && $balance !=0)) {
//echo $cust_arr[$i]["cust_code"]."$balance <br>";

		$dtl_arr = array();
		$abalance = $bal_forwarded;
		if ($out_arr) $out_num = count($out_arr);
		else $out_num = 0;
		for ($j=0;$j<$out_num;$j++) { 
			if ($out_arr[$j][0]=="I") {
				$abalance += $out_arr[$j][2]["pick_total"];
				$tmp = array("id"=>"", "da"=>"", "de"=>"", "am"=>"", "ty"=>"");
				$tmp["ty"] = "p";
				$tmp["id"] = $out_arr[$j][2]["pick_id"];
				$tmp["da"] = $out_arr[$j][2]["pick_date"];
				$tmp["de"] = "Invoice: ".$out_arr[$j][2]["pick_id"];

	    		$pdtl_arr = $pd->getPickDtlsListSales($out_arr[$j][2]["pick_id"]);
//print_r($pdtl_arr);
	    		if ($pdtl_arr) $pdtl_num = count($pdtl_arr);
	    		else $pdtl_num =0;
	    		if ($pdtl_num>0) $tmp["de"] .= " (";
	    		for ($k=0;$k<$pdtl_num;$k++) {
	    		    if ($k!=0) $tmp["de"] .= ", ";
				    $tmp["de"] .= $pdtl_arr[$k]["slsdtl_sale_id"];
				}
	    		if ($pdtl_num>0) $tmp["de"] .= ")";

				$tmp["am"] = $out_arr[$j][2]["pick_total"];
				array_push($dtl_arr, $tmp);
			} else if ($out_arr[$j][0]=="C") {
				$tmp = array("id"=>"", "da"=>"", "de"=>"", "am"=>"", "ty"=>"");
				$tmp["ty"] = "c";
				$tmp["id"] = $out_arr[$j][2]["cmemo_id"];
				$tmp["da"] = $out_arr[$j][2]["cmemo_date"];
				$tmp["de"] = "CR Memo: ".$out_arr[$j][2]["cmemo_id"];
				$tmp["am"] = $out_arr[$j][2]["cmemo_total"] * -1;
				array_push($dtl_arr, $tmp);
			} else if ($out_arr[$j][0]=="R") {
				$tmp = array("id"=>"", "da"=>"", "de"=>"", "am"=>"", "ty"=>"");
				$tmp["ty"] = "r";
				$tmp["id"] = $out_arr[$j][2]["rcpt_id"];
				$tmp["da"] = $out_arr[$j][2]["rcpt_date"];
				if ($out_arr[$j][2]["rcpt_type"] == "ca") $description = "Cash";
				else if ($out_arr[$j][2]["rcpt_type"] == "ch") $description = "Check #".$out_arr[$j][2]["rcpt_check_no"];
				else if ($out_arr[$j][2]["rcpt_type"] == "cc") $description = "Credit Card";
				else if ($out_arr[$j][2]["rcpt_type"] == "dc") $description = "Discount";
				else if ($out_arr[$j][2]["rcpt_type"] == "bc") $description = "Bounced Check";
				else if ($out_arr[$j][2]["rcpt_type"] == "ot") $description = "Other Payment Type";
				else $description = "Unknown";

				$tmp["de"] = "Receipt: $description (".$out_arr[$j][2]["rcpt_id"].")";
				$tmp["am"] = ($out_arr[$j][2]["rcpt_amt"]+$out_arr[$j][2]["rcpt_disc_amt"]) * -1;
				array_push($dtl_arr, $tmp);
			}
		}

		$height = 12;
		$lines = 40;

		$dtl_num = count($dtl_arr);
		$stmt_page_num = ceil($dtl_num / $lines);
		if ($balance != 0 && $stmt_page_num == 0) $stmt_page_num = 1;
//print_r($dtl_arr);
//echo "$stmt_page_num = ceil($dtl_num / $lines)<br>";
//$pdf->addText(490,720,10, $stmt_page_num);
		$tmp_bal = $bal_forwarded;
		for ($j=0;$j<$stmt_page_num;$j++) {
			if ($first_pb == 1) {
				$first_pb = 0;
			} else {
				$pdf->AddPage("P","Letter");
				pageTemplate($pdf);
				
			}
			$x = 0;
			$y = 130;
			$pdf->SetFontSize(10);
			//$page_indicator = "$i of $stmt_page_num pages";
			//$pdf->addText(470,690,10, $page_indicator);
			$pdf->Text(60,$y+$height*$x++, $cust_arr[$i]["cust_name"]."(".$cust_arr[$i]["cust_code"].")");
			$pdf->Text(60,$y+$height*$x++, $cust_arr[$i]["cust_addr1"]);
			if (!empty($cust_arr[$i]["cust_addr2"])) $pdf->Text(60,$y+$height*$x++,$cust_arr[$i]["cust_addr2"]);
			if (!empty($cust_arr[$i]["cust_addr3"])) $pdf->Text(60,$y+$height*$x++,$cust_arr[$i]["cust_addr3"]);
			$pdf->Text(60,$y+$height*$x++, $cust_arr[$i]["cust_city"].", ".$cust_arr[$i]["cust_state"]." ".$cust_arr[$i]["cust_zip"]);
			$pdf->Text(60,$y+$height*$x++, $cust_arr[$i]["cust_tel"]);
			$d_value = "(".$d->toUsaDate($start_date)."~".$d->toUsaDate($end_date).")";
			$pdf->Text(445,125, $stmt_date);
			$pdf->Text(415,138, $d_value);
			$bal = number_format($balance,2,".",",");
			$balfrwd = number_format($bal_forwarded,2,".",",");
			$pdf->Text(463-$pdf->GetStringWidth( $bal),180, $bal);

			$y = 228;
			if ($j==0) {
				$pdf->Text(124,$y, "Balance forwarded");
				$pdf->Text(559-$pdf->GetStringWidth( $balfrwd),$y, $balfrwd);
			}
			$y += $height;
			for ($k=0;$k<$lines;$k++) {
				$m = $k+$j*$lines;
				if ($m < $dtl_num) {
					$pdf->Text(48,$y+$height*$k, $dtl_arr[$m]["da"]);
					$pdf->Text(124,$y+$height*$k, substr($dtl_arr[$m]["de"],0,35));
					$amt = number_format($dtl_arr[$m]["am"],2,".",",");
					$tmp_bal += $dtl_arr[$m]["am"];
					$tmp_bal_f = number_format($tmp_bal,2,".",",");
					$pdf->Text(472-$pdf->GetStringWidth( $amt),$y+$height*$k, $amt);
					$pdf->Text(559-$pdf->GetStringWidth( $tmp_bal_f),$y+$height*$k, $tmp_bal_f);
				}
			}
			if ($j == $stmt_page_num -1) {
				$balance = number_format($balance,2,".",",");
				$bal0 = number_format($bal0,2,".",",");
				$bal30 = number_format($bal30,2,".",",");
				$bal60 = number_format($bal60,2,".",",");
				$bal90 = number_format($bal90,2,".",",");
				$bal90over = number_format($bal90over,2,".",",");
				$pdf->Text(112-$pdf->GetStringWidth( $bal0),753, $bal0);
				$pdf->Text(198-$pdf->GetStringWidth( $bal30),753, $bal30);
				$pdf->Text(288-$pdf->GetStringWidth( $bal60),753, $bal60);
				$pdf->Text(376-$pdf->GetStringWidth( $bal90),753, $bal90);
				$pdf->Text(466-$pdf->GetStringWidth( $bal90over),753, $bal90over);
				$pdf->Text(556-$pdf->GetStringWidth( $balance),753, $balance);
			}
		}
	}
}

$pdf->Output();

//===========================================================================================================
?>
