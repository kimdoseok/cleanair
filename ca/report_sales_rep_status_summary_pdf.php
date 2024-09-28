<?php
include_once ("class/fpdf.php");
include_once ("class/class.datex.php");
include_once ("class/register_globals.php");

function drawForm(&$pdf, $subt, $opts)
{
	$pdf->AddPage("L","Letter");
	$pdf->SetFont("Helvetica", "b", 18);
	$title = 'SALES REP. STATUS LIST';
	$width = $pdf->GetStringWidth($title);
	$pdf->Text(($pdf->GetPageWidth() - $width) / 2, 22, $title);
	$pdf->SetFont("Helvetica", "", 11);
	$width = $pdf->GetStringWidth($subt);
	$pdf->Text(($pdf->GetPageWidth() - $width) / 2, 38, $subt);

	$pdf->SetFont("Helvetica", "", 9);
	$lineapage = $opts["lineapage"];
	$xarr = $opts["cols"];
	$y = 42;
	$h = 16;
	for ($i = 0; $i < $lineapage + 1; $i++) {
		$x = 20;
		for ($j = 0; $j < count($xarr); $j++) {
			if ($i == 0) {
				$pdf->SetFillColor(210);
				$pdf->Rect($x, $y, $xarr[$j][1], $h, "DF");
				$pdf->Text($x + ($xarr[$j][1] - $pdf->GetStringWidth($xarr[$j][0])) / 2, $y + 15, $xarr[$j][0]);
			} else if ($i % 2 == 0 && $i > 0) {
				$pdf->SetFillColor(235);
				$pdf->Rect($x, $y, $xarr[$j][1], $h, "DF");
			} else {
				$pdf->Rect($x, $y, $xarr[$j][1], $h);
			}
			$x += $xarr[$j][1];
		}
		$y += $h;
	}
	$pdf->SetFont("Times", "", 8);

}

$lineapage = 33;
$xarr = Array(
	array("SR#", 45),
	array("Cust#", 55),
	array("Name", 95),
	array("City, State", 55),
	array("Tel", 50),
	array("Forward", 45),
	array("Total Sales", 45),
	array("Total Paid", 45),
	array("Total Credit", 40),
	array("Balance", 45),
	array("1~30", 40),
	array("31~60", 40),
	array("61~90", 40),
	array("Over 90", 40),
	array("Last Paid", 40),
	array("Last Sale", 35) );

$pdf = new FPDF("L", "pt");
$opts = Array(
	"lineapage" => $lineapage,
	"cols" => $xarr
);


//$pdf->ezSetMargins(27,27,27,27);
//$pdf->selectFont('class/fonts/Helvetica-Bold.afm');
//$pdf->ezText('SALES REP. STATUS LIST',18, array("justification"=>"center"));
//$pdf->selectFont('class/fonts/Times-Roman.afm');
//$pdf->ezSetDy(-10);
//$pdf->ezText($dates,11, array("justification"=>"center"));
//$pdf->ezSetDy(-10);

$d = new DateX();
$subt = "Start Date : " . $d->toUsaDate($start_date) . "  /  End Date : " . $d->toUsaDate($end_date) . "  /  CutOff Date : " . $cutoff_date;

$data = array();
$cust_num = count($cust_arr);
$time_limit = 5 * $cust_num + 30;
set_time_limit($time_limit);

//print_r($cust_arr);
for ($i = 0; $i < $cust_num; $i++) {
	$pick_arr = $p->getPicksStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
	$rcpt_arr = $r->getReceiptStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
	$last_arr = $r->getReceiptLast($cust_arr[$i]["cust_code"]);
	$cmemo_arr = $cm->getCmemoStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);

	$pick_frwd_sum = $p->getPicksSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$rcpt_frwd_sum = $r->getReceiptSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$cmemo_frwd_sum = $cm->getCmemoSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$bal_forwarded = $cust_arr[$i]["cust_init_bal"] + $pick_frwd_sum - $rcpt_frwd_sum - $cmemo_frwd_sum;

	$pick_sum = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
	$rcpt_sum = $r->getReceiptSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
	$cmemo_sum = $cm->getCmemoSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
	$balance = $bal_forwarded + $pick_sum - $rcpt_sum - $cmemo_sum;

	$pick_num = count($pick_arr);
	$rcpt_num = count($rcpt_arr);
	$cmemo_num = count($cmemo_arr);

	$day0 = date("Y-m-d");
	$day30 = $d->getIsoDate($day0, 30, "b");
	$day60 = $d->getIsoDate($day0, 60, "b");
	$day90 = $d->getIsoDate($day0, 90, "b");
	$created = $d->toIsoDate($cust_arr[$i]["cust_created"]);

	$pick90over = $p->getPicksSumAged($cust_arr[$i]["cust_code"], "", $day90, "t", "f");
	$pick90 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day90, $day60, "t", "f");
	$pick60 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day60, $day30, "t", "f");
	$pick30 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day30, $day0, "t", "t");
	$pick0 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day0, "", "f", "t");

	if (strtotime($created) <= strtotime($day0) && strtotime($created) > strtotime($day30))
		$pick30 += $cust_arr[$i]["cust_init_bal"];
	else if (strtotime($created) <= strtotime($day30) && strtotime($created) > strtotime($day60))
		$pick60 += $cust_arr[$i]["cust_init_bal"];
	if (strtotime($created) <= strtotime($day60) && strtotime($created) > strtotime($day90))
		$pick90 += $cust_arr[$i]["cust_init_bal"];
	if (strtotime($created) <= strtotime($day90))
		$pick90over += $cust_arr[$i]["cust_init_bal"];

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

	$alt = false;
	if ($zero_balance == "z" || ($zero_balance != "z" && $balance != 0)) {
		if ($first_pb == 1) {
			$first_pb = 0;
		} else {
			//echo "<DIV style=\"page-break-after:always\"></DIV>";
		}
		if ($alt == true) {
			$bgcolor = "#EEEEEE";
			$alt = false;
		} else {
			$bgcolor = "white";
			$alt = true;
		}

		$tmp = array('rep' => '', 'cust' => '', 'name' => '', 'city' => '', 'tel' => '', 'forward' => '', 'sales' => '', 'paid' => '', 'credit' => '', 'balance' => '', 'thirty' => '', 'sixty' => '', 'ninety' => '', 'overninety' => '', 'lpamt' => '', 'lpdate' => '');

		$tmp['rep'] = $cust_arr[$i]["cust_slsrep"] ?? "";
		$tmp['cust'] = $cust_arr[$i]["cust_code"] ?? "";
		$tmp['name'] = $cust_arr[$i]["cust_name"] ?? "";
		$tmp['city'] = $cust_arr[$i]["cust_city"] . ", " . $cust_arr[$i]["cust_state"];
		$tmp['tel'] = $cust_arr[$i]["cust_tel"] ?? "";
		$tmp['forward'] = number_format($bal_forwarded ?? 0, 2, ".", ",");
		$tmp['sales'] = number_format($pick_sum ?? 0, 2, ".", ",");
		$tmp['paid'] = number_format($rcpt_sum ?? 0, 2, ".", ",");
		$tmp['credit'] = number_format($cmemo_sum ?? 0, 2, ".", ",");
		$tmp['balance'] = number_format($balance ?? 0, 2, ".", ",");
		$tmp['thirty'] = number_format($bal30 ?? 0, 2, ".", ",");
		$tmp['sixty'] = number_format($bal60 ?? 0, 2, ".", ",");
		$tmp['ninety'] = number_format($bal90 ?? 0, 2, ".", ",");
		$tmp['overninety'] = number_format($bal90over ?? 0, 2, ".", ",");
		if (array_key_exists("rcpt_amt",$last_arr)) {
			$tmp['lpamt'] = number_format($last_arr["rcpt_amt"] ?? 0, 2, ".", ",");
		} else {
			$tmp['lpamt'] = 0;
		}
		if (array_key_exists("rcpt_date",$last_arr)) {
			$tmp['lpdate'] = $d->toShort($last_arr["rcpt_date"] ?? "");
		} else {
			$tmp['lpdate'] = "";
		}
		if ($zero_balance == "z" || ($zero_balance != "z" && $balance != 0)) {
			array_push($data, $tmp);
		}
	}
}

$page_num = ceil(count($data) / $lineapage);
$idx = 0;
for ($i=0;$i<$page_num;$i++) {
	drawForm($pdf,$subt,$opts);
	$y = 64;
	$h = 16;
	$dy = 9;
	$dx = 2;

	for ($j=0;$j<$lineapage;$j++) {
		$x = 20;
		for ($k=0;$k<count($xarr);$k++) {
			if ($k==0) { //SR#
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["rep"]);
			} else if ($k==1) { //Cust#
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["cust"]);
			} else if ($k==2) { //Name
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["name"]);				
			} else if ($k==3) { //City, State
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["city"]);								
			} else if ($k==4) { //Tel
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["tel"]);								
			} else if ($k==5) { //Bal. Forward
				$atext = strval($data[$idx]["forward"]);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==6) { //Total Sales
				$atext = strval($data[$idx]["sales"]);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==7) { //Total Paid
				$atext = strval($data[$idx]["paid"]);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==8) { //Total Credit
				$atext = strval($data[$idx]["credit"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==9) { //Balance
				$atext = strval($data[$idx]["balance"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==10) { //1~30
				$atext = strval($data[$idx]["thirty"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==11) { //31~60
				$atext = strval($data[$idx]["sixty"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==12) { //61~90
				$atext = strval($data[$idx]["ninety"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==13) { //Over 90
				$atext = strval($data[$idx]["overninety"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==14) { //Last Paid
				$atext = strval($data[$idx]["lpamt"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==15) { //Last S.Date
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["lpdate"] ?? "");				
			}
			$x += $xarr[$k][1];
		}

		$idx++;
		if ($idx==count($data)) {
			break;
		}
		$y += $h;		

	}
}

$pdf->Output();
?>