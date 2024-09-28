<?php
include_once("class/class.customers.php");
include_once("class/class.requests.php");
include_once("class/class.picks.php");
include_once("class/class.pickdtls.php");
include_once("class/class.cmemo.php");
include_once("class/class.cmemodtls.php");
include_once("class/class.receipt.php");
include_once("class/class.datex.php");
include_once("class/class.ezpdf.php");

$cust_num = count($cust_arr);
set_time_limit($cust_num*15);

$pdf = new CezPdf("letter", "portrait");
$pdf->ezSetMargins(27,27,27,27);

// Form Template....
$allbox = $pdf->openObject();
$pdf->saveState();
$pdf->setLineStyle(1);
$pdf->setStrokeColor(0,0,0,0);
$boldFont = 'class/fonts/Helvetica-Bold.afm';
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($boldFont);
$pdf->addText(440,730,24,"Statement");
if (file_exists('images/logo.jpg')) $pdf->addJpegFromFile('images/logo.jpg',27,690,100);
$pdf->addText(140,732,18,"Clean Air Supply, Inc.");
$pdf->selectFont($font);
$pdf->addText(140,718,12, "1301 E. Linden Avenue, Linden, NJ 07036");
$pdf->addText(140,704,12, "1-800-435-0581(NY/NJ), 908-925-7722");
$pdf->addText(140,690,12, "Fax: (908)925-5535");

$pdf->selectFont($boldFont);
$pdf->addText(38,650,10, "Bill To:");
$pdf->addText(480,650,10, "Date");
$pdf->addText(420,586,10, "Amount Due");
$pdf->addText(510,586,10, "Amount Enc.");
$pdf->addText(60,530,10, "Date");
$pdf->addText(230,530,10, "Transaction");
$pdf->addText(124,510,10, "Balance forward");
$pdf->addText(430,530,10, "Amount");
$pdf->addText(520,530,10, "Balance");
$pdf->addText(56,78,10, "Current");
$pdf->addText(142,78,10, "1~30 Days");
$pdf->addText(230,78,10, "31~60 Days");
$pdf->addText(326,78,10, "61~90 Days");
$pdf->addText(413,78,10, "Over 90 Days");
$pdf->addText(510,78,10, "Amount Due");

$x = 27;
$y = 36;
$dx = 93;
$dy = 36;
for ($i=0;$i<6;$i++) {
	$pdf->rectangle($x+$dx*$i,$y,$dx,36);
	$pdf->rectangle($x+$dx*$i,$y+$dy,$dx,20);
}

$y += 56;
$dy = 432;
$dx = 90;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);
$dx += 288;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);
$dx += 90;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);
$dx += 90;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);

$x += 378;
$dx = 90;
$y = $y+$dy+20;
$dy = 36;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);
$x += $dx;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);

$x = 27;
$y += 10;
$dx = 370;
$dy = 90;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);

$x = 405;
$dx = 180;
$y += 56;
$dy = 34;
$pdf->rectangle($x,$y,$dx,$dy);
$pdf->rectangle($x,$y+$dy,$dx,20);

$pdf->addText(470,700,10, $cust_num);

$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($allbox,'all');

// for each pages
$pagetext = $pdf->openObject();
$pdf->saveState();
$font = 'class/fonts/Helvetica.afm';
$pdf->selectFont($font);

for ($i=0;$i<$cust_num;$i++) {
	$pick_arr = $p->getPicksStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
	$rcpt_arr = $r->getReceiptStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
	$cmemo_arr = $m->getCmemoStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);

	$pick_frwd_sum = $p->getPicksSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$rcpt_frwd_sum = $r->getReceiptSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$cmemo_frwd_sum = $m->getCmemoSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
	$bal_forwarded = $cust_arr[$i]["cust_init_bal"]+$pick_frwd_sum-$rcpt_frwd_sum-$cmemo_frwd_sum;

	$pick_sum = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
	$rcpt_sum = $r->getReceiptSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
	$cmemo_sum = $m->getCmemoSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
	$balance = $bal_forwarded + $pick_sum - $rcpt_sum - $cmemo_sum;

	$pick_num = count($pick_arr);
	$rcpt_num = count($rcpt_arr);
	$cmemo_num = count($cmemo_arr);

	$day0 = date("Y-m-d");
	$day30 = $d->getIsoDate($day0,30,"b");
	$day60 = $d->getIsoDate($day0,60,"b");
	$day90 = $d->getIsoDate($day0,90,"b");

	$pick90over = $p->getPicksSumAged($cust_arr[$i]["cust_code"], "", $day90, "t", "f");
	$pick90 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day90, $day60, "t", "f");
	$pick60 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day60, $day30, "t", "f");
	$pick30 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day30, $day0, "t", "t");
	$pick0 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day0, "", "f", "t");

	if (strtotime($cust_arr[$i]["cust_created"]) <= strtotime($day0) && strtotime($cust_arr[$i]["cust_created"]) > strtotime($day30) ) $pick30 += $cust_arr[$i]["cust_init_bal"];
	else if (strtotime($cust_arr[$i]["cust_created"]) <= strtotime($day30) && strtotime($cust_arr[$i]["cust_created"]) > strtotime($day60) ) $pick60 += $cust_arr[$i]["cust_init_bal"];
	if (strtotime($cust_arr[$i]["cust_created"]) <= strtotime($day60) && strtotime($cust_arr[$i]["cust_created"]) > strtotime($day90) ) $pick90 += $cust_arr["cust_init_bal"];
	if (strtotime($cust_arr[$i]["cust_created"]) <= strtotime($day90)) $pick90over += $cust_arr[$i]["cust_init_bal"];

	$bal0 = $balance - $pick90over - $pick90 - $pick60 - $pick30 ;
	if ($bal0 < 0) $bal0 = 0;
	$bal30 = $balance - $pick90over - $pick90 - $pick60 - $pick0;
	if ($bal30 < 0) $bal30 = 0;
	$bal60 = $balance - $pick90over - $pick90 - $pick30 - $pick0 ;
	if ($bal60 < 0) $bal60 = 0;
	$bal90 = $balance - $pick90over - $pick60 - $pick30 - $pick0 ;
	if ($bal90 < 0) $bal90 = 0;
	$bal90over = $balance - $pick90 - $pick60 - $pick30 - $pick0 ;
	if ($bal90over < 0) $bal90over = 0;


	if ($zero_balance == "z" || ($zero_balance != "z" && $balance !=0)) {

		$dtl_arr = array();
		for ($j=0;$j<$pick_num;$j++) {
			$tmp = array("id"=>"", "da"=>"", "de"=>"", "am"=>"", "ty"=>"");
			$tmp["ty"] = "p";
			$tmp["id"] = $pick_arr[$j]["pick_id"];
			$tmp["da"] = $pick_arr[$j]["pick_date"];
			$tmp["de"] = "Invoice: ".$pick_arr[$j]["pick_code"];
			$tmp["am"] = $pick_arr[$j]["pick_total"];
			array_push($dtl_arr, $tmp);
		}
		for ($j=0;$j<$cmemo_num;$j++) {
			$tmp = array("id"=>"", "da"=>"", "de"=>"", "am"=>"", "ty"=>"");
			$tmp["ty"] = "c";
			$tmp["id"] = $cmemo_arr[$j]["cmemo_id"];
			$tmp["da"] = $cmemo_arr[$j]["cmemo_date"];
			$tmp["de"] = "CR Memo: ".$cmemo_arr[$j]["cmemo_id"];
			$tmp["am"] = $cmemo_arr[$j]["cmemo_total"] * -1;
			array_push($dtl_arr, $tmp);
		}
		for ($j=0;$j<$rcpt_num;$j++) {
			$tmp = array("id"=>"", "da"=>"", "de"=>"", "am"=>"", "ty"=>"");
			$tmp["ty"] = "r";
			$tmp["id"] = $rcpt_arr[$j]["rcpt_id"];
			$tmp["da"] = $rcpt_arr[$j]["rcpt_date"];
			$tmp["de"] = "Receipt: ".$rcpt_arr[$j]["rcpt_id"];
			$tmp["am"] = $rcpt_arr[$j]["rcpt_amt"] * -1;
			array_push($dtl_arr, $tmp);
		}

		$height = 12;
		$lines = 34;

		$dtl_num = count($dtl_arr);
		$stmt_page_num = ceil($dtl_num / $lines);

//		$pdf->addText(470,700,10, "1234567890");
		$tmp_bal = $bal_forwarded;
		for ($j=0;$j<$stmt_page_num;$j++) {
			$pdf->addText(40,625-$height*0,10, $cust_arr[$i]["cust_name"]."(".$cust_arr[$i]["cust_code"].")");
			$pdf->addText(40,625-$height*1,10, $cust_arr[$i]["cust_addr1"]);
			$pdf->addText(40,625-$height*2,10, $cust_arr[$i]["cust_addr2"]);
			$pdf->addText(40,625-$height*3,10, $cust_arr[$i]["cust_addr3"]);
			$pdf->addText(40,625-$height*4,10, $cust_arr[$i]["cust_city"].", ".$cust_arr[$i]["cust_state"]." ".$cust_arr[$i]["cust_zip"]);
//			$pdf->addText(40,625-$height*5,10, "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");
			$pdf->addText(470,625-$height*0,10, date("m/d/Y"));
			$bal = number_format($balance,2,".",",");
			$pdf->addText(482-$pdf->getTextWidth(10, $bal),560,10, $bal);

			for ($k=0;$k<$lines;$k++) {
				$m = $k+$j*$lines;
				$pdf->addText(46,497-$height*$k,10, $dtl_arr[$m]["da"]);
				$pdf->addText(124,497-$height*$k,10, substr($dtl_arr[$m]["de"],0,35));
				$amt = number_format($dtl_arr[$m]["am"],2,".",",");
				$tmp_bal += number_format($dtl_arr[$m]["am"],2,".",",");
				$pdf->addText(482-$pdf->getTextWidth(10, $amt),497-$height*$k,10, $dtl_arr[$m]["da"]);
				$pdf->addText(574-$pdf->getTextWidth(10, $tmp_bal),497-$height*$k,10, $tmp_bal);
			}
			if ($j == $stmt_page_num -1) {
				$bal0 = number_format($bal0,2,".",",");
				$bal30 = number_format($bal30,2,".",",");
				$bal60 = number_format($bal60,2,".",",");
				$bal90 = number_format($bal90,2,".",",");
				$bal90over = number_format($bal90over,2,".",",");
				$pdf->addText(104-$pdf->getTextWidth(10, $bal0),50,10, $bal0);
				$pdf->addText(198-$pdf->getTextWidth(10, $bal30),50,10, $bal30);
				$pdf->addText(288-$pdf->getTextWidth(10, $bal60),50,10, $bal60);
				$pdf->addText(382-$pdf->getTextWidth(10, $bal90),50,10, $bal90);
				$pdf->addText(474-$pdf->getTextWidth(10, $bal90over),50,10, $bal90over);
				$pdf->addText(568-$pdf->getTextWidth(10, $balance),50,10, $balance);
			}
			$pdf->newPage();
		}
	}
}

$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($pagetext,'add');

$pdf->ezStream();

//===========================================================================================================
?>