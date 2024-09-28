<?php
//include_once("class/class.ezpdf.php");
include_once ("class/fpdf.php");
include_once("class/class.datex.php");

$vars = array("zero_balance","start_date","end_date", "stmt_date","date_sort","alt","first_pb");
foreach ($vars as $var) {
	$$var = "";
}

include_once("class/register_globals.php");
$stmt_date = $_POST["stmt_date"];
$start_date = $_POST["start_date"];
$end_date = $_POST["end_date"];
$start_cust = $_POST["start_cust"];
$end_cust = $_POST["end_cust"];
$details = $_POST["details"];
$date_sort = $_POST["date_sort"];

function drawForm(&$pdf, $opts)
{
	$pdf->AddPage("L","Letter");
	$pdf->SetFont("Helvetica", "b", 18);
	$title = $opts["title"];
	$width = $pdf->GetStringWidth($title);
	$pdf->Text(($pdf->GetPageWidth() - $width) / 2, 22, $title);
	$pdf->SetFont("Helvetica", "", 11);
	$subtitle = $opts["subtitle"];
	$width = $pdf->GetStringWidth($subtitle);
	$pdf->Text(($pdf->GetPageWidth() - $width) / 2, 38, $subtitle);

	$pdf->SetFont("Helvetica", "", 9);
	$lineapage = $opts["lineapage"];
	$xarr = $opts["cols"];
	$y = 40;
	$h = 16;
	for ($i = 0; $i < $lineapage + 1; $i++) {
		$x = 20;
		for ($j = 0; $j < count($xarr); $j++) {
			if ($i == 0) {
				$pdf->SetFillColor(210);
				$pdf->Rect($x, $y, $xarr[$j][1], $h, "DF");
				$pdf->Text($x + ($xarr[$j][1] - $pdf->GetStringWidth($xarr[$j][0])) / 2, $y + 13, $xarr[$j][0]);
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
	array("Cust#", 45),
	array("Name", 110),
	array("City, State", 75),
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

$d = new DateX();

$pdf = new FPDF("L", "pt");
$opts = Array(
	"lineapage" => $lineapage,
	"cols" => $xarr,
	"title" => 'STATEMENT LIST',
	//"subtitle" => "Start Date : ".$d->toUsaDate($start_date)."  /  End Date : ".$d->toUsaDate($end_date)."  /  Stmt Date : ".$stmt_date
	"subtitle" => "Start Date : ".$start_date."  /  End Date : ".$end_date."  /  Stmt Date : ".$stmt_date
);

$cutoff_digit = 100;
$data = array();
$cust_num = count($cust_arr);
$time_limit = 5 * $cust_num + 30;
	set_time_limit($time_limit);
//print_r($cust_arr);
	$total_forwarded = 0;
	$total_pick_amount = 0;
	$total_rcpt_amount = 0;
	$total_cmemo_amount = 0;
	$total_balance = 0;
	$total_bal30 = 0;
	$total_bal60 = 0;
	$total_bal90 = 0;
	$total_bal90over = 0;

	$start_date = $d->toIsoDate($start_date);
	$end_date = $d->toIsoDate($end_date);

	for ($i=0;$i<$cust_num;$i++) {
		$pick_arr = $p->getPicksStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
//print_r($pick_arr);
		$rcpt_arr = $r->getReceiptStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
		$last_arr = $r->getReceiptLast($cust_arr[$i]["cust_code"]);
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

		if ($zero_balance == "z" || ($zero_balance != "z" && $balance !=0)) {
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

			$tmp = array('cust'=>'', 'name'=>'', 'city'=>'', 'tel'=>'', 'forward'=>'', 'sales'=>'', 'paid'=>'', 'credit'=>'', 'balance'=>'', 'thirty'=>'', 'sixty'=>'', 'ninety'=>'', 'overninety'=>'', 'lpamt'=>'', 'lpdate'=>'');

			$rcpt_amt = $last_arr["rcpt_amt"] ?? 0;
			$rcpt_date = $last_arr["rcpt_date"] ?? "";

			$tmp['cust'] = $cust_arr[$i]["cust_code"];
			$tmp['name'] = $cust_arr[$i]["cust_name"];
			$tmp['city'] = $cust_arr[$i]["cust_city"].", ".$cust_arr[$i]["cust_state"];
			$tmp['tel'] = $cust_arr[$i]["cust_tel"];
			$tmp['forward'] = number_format($bal_forwarded,2,".",",");
			$tmp['sales'] = number_format($pick_sum,2,".",",");
			$tmp['paid'] = number_format($rcpt_sum,2,".",",");
			$tmp['credit'] = number_format($cmemo_sum,2,".",",");
			$tmp['balance'] = number_format($balance,2,".",",");
			$tmp['thirty'] = number_format($bal30,2,".",",");
			$tmp['sixty'] = number_format($bal60,2,".",",");
			$tmp['ninety'] = number_format($bal90,2,".",",");
			$tmp['overninety'] = number_format($bal90over,2,".",",");
			$tmp['lpamt'] = number_format($rcpt_amt,2,".",",");
			$tmp['lpdate'] = $d->toUsaDate($rcpt_date);
			array_push($data, $tmp);
	
			$total_forwarded += $bal_forwarded;
			$total_pick_amount += $pick_sum;
			$total_rcpt_amount += $rcpt_sum;
			$total_cmemo_amount += $cmemo_sum;
			$total_balance += $balance;
			$total_bal30 += $bal30;
			$total_bal60 += $bal60;
			$total_bal90 += $bal90;
			$total_bal90over += $bal90over;
		}
	}

	$tmp = array('cust'=>'', 'name'=>'', 'city'=>'', 'tel'=>'TOTAL', 'forward'=>'', 'sales'=>'', 'paid'=>'', 'credit'=>'', 'balance'=>'', 'thirty'=>'', 'sixty'=>'', 'ninety'=>'', 'overninety'=>'', 'lpamt'=>'', 'lpdate'=>'');

	$tmp['forward'] = number_format($total_forwarded ?? 0,2,".",",");
	$tmp['sales'] = number_format($total_pick_amount ?? 0,2,".",",");
	$tmp['paid'] = number_format($total_rcpt_amount ?? 0,2,".",",");
	$tmp['credit'] = number_format($total_cmemo_amount ?? 0,2,".",",");
	$tmp['balance'] = number_format($total_balance ?? 0,2,".",",");
	$tmp['thirty'] = number_format($total_bal30 ?? 0,2,".",",");
	$tmp['sixty'] = number_format($total_bal60 ?? 0,2,".",",");
	$tmp['ninety'] = number_format($total_bal90 ?? 0,2,".",",");
	$tmp['overninety'] = number_format($total_bal90over ?? 0,2,".",",");
	array_push($data, $tmp);


$page_num = ceil(count($data) / $lineapage);
$idx = 0;
for ($i=0;$i<$page_num;$i++) {
	drawForm($pdf,$opts);
	$y = 60;
	$h = 16;
	$dy = 9;
	$dx = 2;

	for ($j=0;$j<$lineapage;$j++) {
		$x = 20;
		for ($k=0;$k<count($xarr);$k++) {
			if ($k==0) { 
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["cust"]);
			} else if ($k==1) { //Cust#
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["name"]);				
			} else if ($k==2) { //Name
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["city"]);								
			} else if ($k==3) { //City, State
				$pdf->Text($x+$dx ,$y+$dy,$data[$idx]["tel"]);								
			} else if ($k==4) { //Tel
				$atext = strval($data[$idx]["forward"]);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==5) { //Bal. Forward
				$atext = strval($data[$idx]["sales"]);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==6) { //Total Sales
				$atext = strval($data[$idx]["paid"]);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==7) { //Total Paid
				$atext = strval($data[$idx]["credit"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==8) { //Total Credit
				$atext = strval($data[$idx]["balance"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==9) { //Balance
				$atext = strval($data[$idx]["thirty"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==10) { //1~30
				$atext = strval($data[$idx]["sixty"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==11) { //31~60
				$atext = strval($data[$idx]["ninety"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==12) { //61~90
				$atext = strval($data[$idx]["overninety"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==13) { //Over 90
				$atext = strval($data[$idx]["lpamt"] ?? 0);
				$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
			} else if ($k==14) { //Last Paid
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
