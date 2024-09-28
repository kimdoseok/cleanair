<?php
//include_once("class/class.ezpdf.php");
include_once("class/class.datex.php");

$vars = array("start_date","start_cust","end_cust", "zero_balance","first_pb","alt","cutoff_date");
foreach ($vars as $var) {
	$$var = "";
}

include_once("class/register_globals.php");

function getTruncated(&$pdf, $width, $str) {
	$outstr = "";
	for ($i=0;$i<strlen($str);$i++) {
		if ($pdf->GetStringWidth($outstr.$str[$i])<$width) {
			$outstr .= $str[$i];
		}
	}
	return $outstr;
}

function pageTemplate(&$pdf, $tmpl) {
	$pdf->SetFont("Helvetica", "b", 24);
	$pdf->SetXY(200, 22);
	$pdf->Cell(0, 0, "AR Status Report", 0, 0, "L");
	$pdf->SetFont("Helvetica", "", 12);
	$pdf->SetXY(200, 43);
	$tmplstr = $tmpl["cutoff"]."   ".$tmpl["pages"];
	$pdf->Cell(0, 0, $tmplstr, 0, 0, "L");

	$pdf->SetXY(37, 60);
	$pdf->Cell(0, 0, "Cust#", 0, 0, "L");
	$pdf->Rect(25, 52, 70, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(25, 68, 70, 674); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(133, 60);
	$pdf->Cell(0, 0, "Name", 0, 0, "L");
	$pdf->Rect(95, 52, 120, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(95, 68, 120, 674); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(233, 60);
	$pdf->Cell(0, 0, "Tel", 0, 0, "L");
	$pdf->Rect(215, 52, 70, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(215, 68, 70, 674); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(297, 60);
	$pdf->Cell(0, 0, "1~30", 0, 0, "L");
	$pdf->Rect(285, 52, 60, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(285, 68, 60, 674); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(355, 60);
	$pdf->Cell(0, 0, "31~60", 0, 0, "L");
	$pdf->Rect(345, 52, 60, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(345, 68, 60, 674); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(415, 60);
	$pdf->Cell(0, 0, "61~90", 0, 0, "L");
	$pdf->Rect(405, 52, 60, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(405, 68, 60, 674); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(471, 60);
	$pdf->Cell(0, 0, "Over 90", 0, 0, "L");
	$pdf->Rect(465, 52, 60, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(465, 68, 60, 674); //float x, float y, float w, float h [, string style])
	$pdf->SetXY(531, 60);
	$pdf->Cell(0, 0, "Balance", 0, 0, "L");
	$pdf->Rect(525, 52, 65, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(525, 68, 65, 674); //float x, float y, float w, float h [, string style])

	$pdf->Rect(25, 742, 260, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(285, 742, 60, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(345, 742, 60, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(405, 742, 60, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(465, 742, 60, 16); //float x, float y, float w, float h [, string style])
	$pdf->Rect(525, 742, 65, 16); //float x, float y, float w, float h [, string style])

	$pdf->SetFont("Helvetica", "b", 11);
	$pdf->Text(240, 754, "Total");
	$pdf->SetFont("Times", "", 10);

}

$cutoff_date = $_POST["cutoff_date"];
$tmplarr = array();
$tmplarr["cutoff"] = "Cutoff Date: ".$cutoff_date;
$tmplarr["pages"] = ""; 

$pdf = new FPDF("P", "pt");
//$pdf->AddPage("P", "Letter");
//pageTemplate($pdf, $tmplarr);

$d = new DateX();
$cutoff_date = $d->toIsoDate($cutoff_date);
$dates = "Cutoff Date : ".$cutoff_date;
//$start_date = $cutoff_date;
$end_date = $cutoff_date;

	$data = array();
	$cust_num = count($cust_arr);
	$time_limit = 5 * $cust_num + 30;
	set_time_limit($time_limit);

	$total_30 = 0;
	$total_60 = 0;
	$total_90 = 0;
	$total_90over = 0;

	for ($i=0;$i<$cust_num;$i++) {
		//$pick_arr = $p->getPicksStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
		//$rcpt_arr = $r->getReceiptStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
		//$cmemo_arr = $cm->getCmemoStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);

		$pick_frwd_sum = $p->getPicksSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
		$rcpt_frwd_sum = $r->getReceiptSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
		$cmemo_frwd_sum = $cm->getCmemoSumAged($cust_arr[$i]["cust_code"], "", $start_date, "t", "f");
		$bal_forwarded = $cust_arr[$i]["cust_init_bal"]+$pick_frwd_sum-$rcpt_frwd_sum-$cmemo_frwd_sum;

		$pick_sum = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
		$rcpt_sum = $r->getReceiptSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
		$cmemo_sum = $cm->getCmemoSumAged($cust_arr[$i]["cust_code"], $start_date, $end_date, "t", "t");
		$balance = $bal_forwarded + $pick_sum - $rcpt_sum - $cmemo_sum;

		//$pick_num = count($pick_arr);
		//$rcpt_num = count($rcpt_arr);
		//$cmemo_num = count($cmemo_arr);

		$created = $d->toIsoDate($cust_arr[$i]["cust_created"]);

		$pick90over = $p->getPicksSumAged($cust_arr[$i]["cust_code"], "", $day90, "t", "f");
		$pick90 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day90, $day60, "t", "f");
		$pick60 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day60, $day30, "t", "f");
		$pick30 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day30, $day0, "t", "t");
		$pick0 = $p->getPicksSumAged($cust_arr[$i]["cust_code"], $day0, "", "f", "t");

		if (strtotime($created) <= strtotime($day0) && strtotime($created) > strtotime($day30) ) $pick30 += $cust_arr[$i]["cust_init_bal"];
		else if (strtotime($created) <= strtotime($day30) && strtotime($created) > strtotime($day60) ) $pick60 += $cust_arr[$i]["cust_init_bal"];
		if (strtotime($created) <= strtotime($day60) && strtotime($created) > strtotime($day90) ) $pick90 += $cust_arr[$i]["cust_init_bal"];
		if (strtotime($created) <= strtotime($day90)) $pick90over += $cust_arr[$i]["cust_init_bal"];

		$bal0 = 0;
		$bal30 = 0;
		$bal60 = 0;
		$bal90 = 0;
		
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
    
		$balance = round($bal30+$bal60+$bal90+$bal90over,2);
//print_r($zero_balance);

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

			$tmp = array('cust'=>'', 'name'=>'', 'tel'=>'', 'thirty'=>'', 'sixty'=>'', 'ninety'=>'', 'overninety'=>'', 'balance'=>'');

			if (!isset($bal30)) $bal30 = 0;
			if (!isset($bal60)) $bal60 = 0;
			if (!isset($bal90)) $bal90 = 0;
			if (!isset($bal90over)) $bal90over = 0;
			if (!isset($balance)) $balance = 0;
			$tmp['cust'] = $cust_arr[$i]["cust_code"];
			$tmp['name'] = $cust_arr[$i]["cust_name"];
			$tmp['tel'] = $cust_arr[$i]["cust_tel"];
			$tmp['thirty'] = number_format($bal30,2,".",",");
			$tmp['sixty'] = number_format($bal60,2,".",",");
			$tmp['ninety'] = number_format($bal90,2,".",",");
			$tmp['overninety'] = number_format($bal90over,2,".",",");
			$tmp['balance'] = number_format($balance,2,".",",");
			array_push($data, $tmp);
			$total_30 += $bal30;
			$total_60 += $bal60;
			$total_90 += $bal90;
			$total_90over += $bal90over;
		}
	}

	$y = 78;
	$numline = 48;
	$dy = 14;
	$lastpage = ceil(count($data)/$numline);
	if ($lastpage<1) $lastpage = 1;
	for ($i=0;$i<count($data);$i++) {
		if ($i%$numline==0 ) { //and $i>0
			$page = ceil($i/$numline)+1;
			if ($page<1) $page = 1;
			$tmplarr["pages"] = "Page: ".$page." / ".$lastpage;
			$pdf->AddPage("P", "Letter");
			pageTemplate($pdf, $tmplarr);				
		}
		$cy = $y+$dy*($i%$numline);
		$pdf->Text(28, $cy, getTruncated($pdf, 68, $data[$i]["cust"]));
		$pdf->Text(98, $cy, getTruncated($pdf, 118, $data[$i]["name"]));
		$pdf->Text(218, $cy, getTruncated($pdf, 68, $data[$i]["tel"]));

		$cx = 282+60-$pdf->GetStringWidth($data[$i]["thirty"]);
		$pdf->Text($cx, $cy, $data[$i]["thirty"]);
		$cx = 342+60-$pdf->GetStringWidth($data[$i]["sixty"]);
		$pdf->Text($cx, $cy, $data[$i]["sixty"]);
		$cx = 402+60-$pdf->GetStringWidth($data[$i]["ninety"]);
		$pdf->Text($cx, $cy, $data[$i]["ninety"]);
		$cx = 462+60-$pdf->GetStringWidth($data[$i]["overninety"]);
		$pdf->Text($cx, $cy, $data[$i]["overninety"]);
		$cx = 522+65-$pdf->GetStringWidth($data[$i]["balance"]);
		$pdf->Text($cx, $cy, $data[$i]["balance"]);	
	}

	$thirty = number_format($total_30,2,".",",");
	$sixty = number_format($total_60,2,".",",");
	$ninety = number_format($total_90,2,".",",");
	$overninety = number_format($total_90over,2,".",",");
	$balance = number_format($total_30+$total_60+$total_90+$total_90over,2,".",",");

	$cy = 753;
	$cx = 282+60-$pdf->GetStringWidth($thirty);
	$pdf->Text($cx, $cy, $thirty);
	$cx = 342+60-$pdf->GetStringWidth($sixty);
	$pdf->Text($cx, $cy, $sixty);
	$cx = 402+60-$pdf->GetStringWidth($ninety);
	$pdf->Text($cx, $cy, $ninety);
	$cx = 462+60-$pdf->GetStringWidth($overninety);
	$pdf->Text($cx, $cy, $overninety);
	$cx = 522+65-$pdf->GetStringWidth($balance);
	$pdf->Text($cx, $cy, $balance);	

	$pdf->Output();
//$pdf->ezTable($data,$cols,"",$opt); 
//$pdf->ezStream();
?>
