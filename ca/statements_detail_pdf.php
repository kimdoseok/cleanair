<?php
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
    $pdf->SetXY(36,113);
    $pdf->Cell(0, 0, "Bill To:",0,0,"L");
    $pdf->Rect(32, 104, 342, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(32, 120,342, 112);
    
    $pdf->SetXY(452,113);
    $pdf->Cell(0, 0, "Date:",0,0,"L");
    $pdf->Rect(378, 104, 184, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(378, 120,184,50);

    $pdf->SetXY(386,183);
    $pdf->Cell(0, 0, "Amount Due",0,0,"L");
    $pdf->Rect(378, 174, 92, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(378, 190,92,42);
    $pdf->SetXY(478,183);
    $pdf->Cell(0, 0, "Amount Enc",0,0,"L");
    $pdf->Rect(470, 174, 92, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(470, 190,92,42);

	$pdf->SetDrawColor(100);
	$pdf->Line(32, 258, 562, 258);
	$pdf->SetDrawColor(0,0,0);
    
	$pdf->SetXY(57,271);
    $pdf->Cell(0, 0, "Date",0,0,"L");
    $pdf->Rect(32, 262, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(32, 278, 85, 446); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(220,271);
    $pdf->Cell(0, 0, "Transaction",0,0,"L");
    $pdf->Rect(117, 262, 275, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(117, 278, 275, 446); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(410,271);
    $pdf->Cell(0, 0, "Amount",0,0,"L");
    $pdf->Rect(392, 262, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(392, 278, 85, 446); //float x, float y, float w, float h [, string style])

    $pdf->SetXY(496,271);
    $pdf->Cell(0, 0, "Balance",0,0,"L");
    $pdf->Rect(477, 262, 85, 16); //float x, float y, float w, float h [, string style])
    $pdf->Rect(477, 278, 85, 446); //float x, float y, float w, float h [, string style])

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
$desc_len = 35;

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

//echo "9o $pick90over 9 $pick90 6 $pick60 3 $pick30 0 $pick0 r $rcpt_sum c $cmemo_sum s $credit_sum<br>";

	if ($zero_balance == "z" || ($zero_balance != "z" && $balance !=0)) {
//echo $cust_arr[$i]["cust_code"]."$balance <br>";

		$dtl_arr = array();
		for ($j=0;$j<$pick_num;$j++) {
			$tmp = array("id" => "", "da" => "", "de" => "", "am" => "","up"=>"", "fa" => "","ty" => "");
			$tmp["ty"] = "p";
			$tmp["id"] = $pick_arr[$j]["pick_id"];
			$tmp["da"] = $pick_arr[$j]["pick_date"];
			$tmp["de"] = "Invoice: ".$pick_arr[$j]["pick_id"];

   		$pdtl_arr = $pd->getPickDtlsListSales($pick_arr[$j]["pick_id"]);
   		if ($pdtl_arr) $pdtl_num = count($pdtl_arr);
   		else $pdtl_num =0;
   		if ($pdtl_num>0) $tmp["de"] .= " (";
   		for ($k=0;$k<$pdtl_num;$k++) {
   		    if ($k!=0) $tmp["de"] .= ", ";
			    $tmp["de"] .= $pdtl_arr[$k]["slsdtl_sale_id"];
			}
	    if ($pdtl_num>0) $tmp["de"] .= ")";

      if ($pick_arr[$j]["pick_freight_amt"] != 0) $tmp["fa"] = "Freight: ".number_format($pick_arr[$j]["pick_freight_amt"],2,".",",");
			$tmp["am"] = $pick_arr[$j]["pick_total"];
			array_push($dtl_arr, $tmp);
			if ($details == "d") {
				$recs = $pd->getPickDtlsList($pick_arr[$j]["pick_id"]);
				if ($recs) {
					$rec_num = count($recs);
					for ($k=0;$k<$rec_num;$k++) {
						$dea = str_pad($recs[$k]["slsdtl_item_code"],16);
						
						$dec = $recs[$k]["pickdtl_qty"]+0;
						$dec .= "@";
						$dec .= number_format($recs[$k]["slsdtl_cost"],2,".",",");
						//if ($recs[$k]["slsdtl_taxable"] != "t") $dec .= "N";
						//else $dec .= "T";
						$dec = str_pad($dec, 19, " ", STR_PAD_LEFT);
						$tmp = array("id" => "", "da" => "", "de" => "", "am" => "","up"=>"", "fa" => "","ty" => "");
						$tmp["ty"] = "pd";
						$tmp["id"] = "";
						$tmp["da"] = "";
						$tmp["de"] = $dea;
						$tmp["up"] = $dec;
						//$tmp["am"] = number_format($recs[$k]["pickdtl_cost"]*$recs[$k]["pickdtl_qty"], 2, ".", ",");
						$tmp["am"] = $recs[$k]["pickdtl_cost"]*$recs[$k]["pickdtl_qty"];
						array_push($dtl_arr, $tmp);

						$deb = wordwrap($recs[$k]["slsdtl_item_desc"],35,"\n", 1);
						$deb_arr = explode("\n", $deb);
						$deb_num = count($deb_arr);
						for ($l=0;$l<$deb_num;$l++) {
							$tmp = array("id" => "", "da" => "", "de" => "", "am" => "","up"=>"", "fa" => "","ty" => "");
							$tmp["ty"] = "pd";
							$tmp["id"] = "";
							$tmp["da"] = "";
							$tmp["de"] = $deb_arr[$l];
							if ($l==0 && $recs[$k]["slsdtl_taxable"] == "t" && $recs[$k]["pickdtl_cost"]*$recs[$k]["pickdtl_qty"]!=0) {
								$tmp["am"] = number_format($recs[$k]["pickdtl_cost"]*$recs[$k]["pickdtl_qty"]* $pick_arr[$j]["pick_taxrate"]/100, 2, ".", ",");
								$tmp["up"] = "Tax";
							} else {
								$tmp["am"] = "";
  							$tmp["up"] = "";
							}
							array_push($dtl_arr, $tmp);
						}
					}
				}
			}
		}
		for ($j=0;$j<$cmemo_num;$j++) {
			$tmp = array("id" => "", "da" => "", "de" => "", "am" => "","up"=>"", "fa" => "","ty" => "");
			$tmp["ty"] = "c";
			$tmp["id"] = $cmemo_arr[$j]["cmemo_id"];
			$tmp["da"] = $cmemo_arr[$j]["cmemo_date"];
			$tmp["de"] = "CR Memo: ".$cmemo_arr[$j]["cmemo_id"];
      if ($cmemo_arr[$j]["cmemo_freight_amt"] != 0) $tmp["fa"] = "Freight: ".number_format($cmemo_arr[$j]["cmemo_freight_amt"]*-1,2,".",",");
			$tmp["am"] = $cmemo_arr[$j]["cmemo_total"] * -1;
			array_push($dtl_arr, $tmp);
			if ($details == "d") {
				$recs = $cd->getCmemoDtlList($cmemo_arr[$j]["cmemo_id"]);
				if ($recs) {
					$rec_num = count($recs);
					for ($k=0;$k<$rec_num;$k++) {
						$dea = str_pad($recs[$k]["cmemodtl_item_code"],16);
						
						$dec = $recs[$k]["cmemodtl_qty"] * -1;
						$dec .= "@";
						$dec .= number_format($recs[$k]["cmemodtl_cost"],2,".",",");
						//if ($recs[$k]["cmemodtl_taxable"] != "t") $dec .= "N";
						//else $dec .= "T";
						$dec = str_pad($dec, 19, " ", STR_PAD_LEFT);

						$tmp = array("id" => "", "da" => "", "de" => "", "am" => "","up"=>"", "fa" => "","ty" => "");
						$tmp["ty"] = "cd";
						$tmp["id"] = "";
						$tmp["da"] = "";
						$tmp["de"] = $dea;
						$tmp["up"] = $dec;
						$tmp["am"] = number_format($recs[$k]["cmemodtl_cost"]*$recs[$k]["cmemodtl_qty"]* -1, 2, ".", ",");
						array_push($dtl_arr, $tmp);
						$deb = wordwrap($recs[$k]["cmemodtl_item_desc"],35,"\n", 1);
						$deb_arr = explode("\n", $deb);
						$deb_num = count($deb_arr);
						for ($l=0;$l<$deb_num;$l++) {
							$tmp = array("id" => "", "da" => "", "de" => "", "am" => "","up"=>"", "fa" => "","ty" => "");
							$tmp["ty"] = "cd";
							$tmp["id"] = "";
							$tmp["da"] = "";
							$tmp["de"] = $deb_arr[$l];
							if ($l==0 && $recs[$k]["cmemodtl_taxable"] == "t" && $recs[$k]["cmemodtl_cost"]*$recs[$k]["cmemodtl_qty"]!=0) {
								$tmp["am"] = number_format($recs[$k]["cmemodtl_cost"]*$recs[$k]["cmemodtl_qty"]* -1 * $cmemo_arr[$j]["cmemo_taxrate"]/100, 2, ".", ",");
								$tmp["up"] = "Tax";
							} else {
								$tmp["am"] = "";
  							$tmp["up"] = "";
							}
							array_push($dtl_arr, $tmp);
						}
					}
				}
			}
		}
		for ($j=0;$j<$rcpt_num;$j++) {
			$tmp = array("id" => "", "da" => "", "de" => "", "am" => "","up"=>"", "fa" => "","ty" => "");
			$tmp["ty"] = "r";
			$tmp["id"] = $rcpt_arr[$j]["rcpt_id"];
			$tmp["da"] = $rcpt_arr[$j]["rcpt_date"];
			if ($rcpt_arr[$j]["rcpt_type"] == "ca") $description = "Cash";
			else if ($rcpt_arr[$j]["rcpt_type"] == "ch") $description = "Check #".$rcpt_arr[$j]["rcpt_check_no"];
			else if ($rcpt_arr[$j]["rcpt_type"] == "cc") $description = "Credit Card";
			else if ($rcpt_arr[$j]["rcpt_type"] == "dc") $description = "Discount Apply";
			else if ($rcpt_arr[$j]["rcpt_type"] == "bc") $description = "Bounced Check";
			else if ($rcpt_arr[$j]["rcpt_type"] == "ot") $description = "Other Payment Type";
			else $description = "Unknown";

			$tmp["de"] = "Receipt: $description (".$rcpt_arr[$j]["rcpt_id"].")";
			$tmp["am"] = ($rcpt_arr[$j]["rcpt_amt"]+$rcpt_arr[$j]["rcpt_disc_amt"]) * -1;
			array_push($dtl_arr, $tmp);
		}

		$height = 12;
		$lines = 35;

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
			$y = 164;

			$pdf->Text(60, $y + $height * $x++, $cust_arr[$i]["cust_name"] . "(" . $cust_arr[$i]["cust_code"] . ")");
			$pdf->Text(60, $y + $height * $x++, $cust_arr[$i]["cust_addr1"]);
			if (!empty ($cust_arr[$i]["cust_addr2"]))
				$pdf->Text(60, $y + $height * $x++, $cust_arr[$i]["cust_addr2"]);
			if (!empty ($cust_arr[$i]["cust_addr3"]))
				$pdf->Text(60, $y + $height * $x++, $cust_arr[$i]["cust_addr3"]);
			$pdf->Text(60, $y + $height * $x++, $cust_arr[$i]["cust_city"] . ", " . $cust_arr[$i]["cust_state"] . " " . $cust_arr[$i]["cust_zip"]);
			$pdf->Text(60, $y + $height * $x++, $cust_arr[$i]["cust_tel"]);
			$d_value = "(" . $d->toUsaDate($start_date) . "~" . $d->toUsaDate($end_date) . ")";

			$pdf->Text(445, 140, $stmt_date);
			$pdf->Text(415, 158, $d_value);
			$bal = number_format($balance, 2, ".", ",");
			$balfrwd = number_format($bal_forwarded, 2, ".", ",");
			$pdf->Text(463 - $pdf->GetStringWidth($bal),214, $bal);

			$y = 294;
			if ($j == 0) {
				$pdf->Text(124, $y, "Balance forwarded");
				$pdf->Text(559 - $pdf->GetStringWidth($balfrwd), $y, $balfrwd);
			}
			$y += $height;

			for ($k=0;$k<$lines;$k++) {
				$m = $k+$j*$lines;
				if ($m < $dtl_num) {
					$pdf->Text(48, $y + $height * $k, $dtl_arr[$m]["da"]);
					$pdf->Text(124, $y + $height * $k, substr($dtl_arr[$m]["de"], 0, 35));
					$upw = $pdf->GetStringWidth($dtl_arr[$m]["up"]);
					$pdf->Text(383 - $upw, $y + $height * $k, $dtl_arr[$m]["up"]);
					$amt = "";
					if (is_float($dtl_arr[$m]["am"])) {
						$amt = number_format($dtl_arr[$m]["am"], 2, ".", ",");
					}
					$amtw = $pdf->GetStringWidth($amt);
					if (!empty ($dtl_arr[$m]["am"])) {
						if ($dtl_arr[$m]["ty"] == "pd" || $dtl_arr[$m]["ty"] == "cd" || $dtl_arr[$m]["ty"] == "rd")
							$amt_x = 458;
						else
							$amt_x = 472;
						$pdf->Text($amt_x - $amtw, $y + $height * $k, $amt);
					}
					if ($dtl_arr[$m]["ty"] == "p" || $dtl_arr[$m]["ty"] == "c" || $dtl_arr[$m]["ty"] == "r") {
						$tmp_bal += $dtl_arr[$m]["am"];
						$tmp_bal_f = number_format($tmp_bal, 2, ".", ",");
						$tmp_bal_fw = $pdf->GetStringWidth($tmp_bal_f);
						$pdf->Text(558 - $tmp_bal_fw, $y + $height * $k, $tmp_bal_f);
						$pdf->Text(124, $y + $height * $k, substr($dtl_arr[$m]["de"], 0, 15));
						$faw = $pdf->GetStringWidth($dtl_arr[$m]["fa"]);
						$pdf->Text(395 - $faw, $y + $height * $k, $dtl_arr[$m]["fa"]);
					}
				}
			}
			if ($j == $stmt_page_num -1) {
				$balance = number_format($balance, 2, ".", ",");
				$bal0 = number_format($bal0, 2, ".", ",");
				$bal30 = number_format($bal30, 2, ".", ",");
				$bal60 = number_format($bal60, 2, ".", ",");
				$bal90 = number_format($bal90, 2, ".", ",");
				$bal90over = number_format($bal90over, 2, ".", ",");
				$pdf->Text(112 - $pdf->GetStringWidth($bal0), 753, $bal0);
				$pdf->Text(198 - $pdf->GetStringWidth($bal30), 753, $bal30);
				$pdf->Text(288 - $pdf->GetStringWidth($bal60), 753, $bal60);
				$pdf->Text(376 - $pdf->GetStringWidth($bal90), 753, $bal90);
				$pdf->Text(466 - $pdf->GetStringWidth($bal90over), 753, $bal90over);
				$pdf->Text(556 - $pdf->GetStringWidth($balance), 753, $balance);
			}
		}
	}
}

$pdf->Output();

//===========================================================================================================
?>
