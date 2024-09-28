<?php
	$vars = array("zero_balance","start_date","end_date", "stmt_date","date_sort","alt");
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
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Print Statement</TITLE>
</HEAD>
<BODY>
<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="white">
  <tr bgcolor="white">
	<td align="center" colspan="15"><font size="6" face="Arial, Helvetica, sans-serif"><strong>Statement</strong></font><br>
	<font face="Arial, Helvetica, sans-serif">Start Date : <?= $d->toUsaDate($start_date) ?> / End Date : <?= $d->toUsaDate($end_date) ?> / Stmt Date : <?= $stmt_date ?></font>
	</td>
  </tr>
  <tr bgcolor="white"><td align="center" colspan="15" height="5"><hr height="1"></td></tr>
  <tr align="center" bgcolor="silver">
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Cust#</font></strong></td>
    <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Name</font></strong></td>
    <td width="8%"><strong><font size="2" face="Arial, Helvetica, sans-serif">City/State</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Tel</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Bal. Forward</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total Sales</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total Paid</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total Credit</font></strong></td>
    <td width="8%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Balance</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">1-30</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">31-60</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">61-90</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Over 90</font></strong></td>
    <td width="8%"><strong><font size="2" face="Arial, Helvetica, sans-serif">LP Amout</font></strong></td>
    <td width="8%"><strong><font size="2" face="Arial, Helvetica, sans-serif">LP Date</font></strong></td>
  </tr>
  <tr bgcolor="white"><td align="center" colspan="15" height="5"><hr height="1"></td></tr>
<?php
	$total_forwarded = 0;
	$total_sale_amount = 0;
	$total_rcpt_amount = 0;
	$total_cmemo_amount = 0;
	$total_balance = 0;
	$total_bal90 = 0;
	$total_bal60 = 0;
	$total_bal30 = 0;
	$total_bal90over = 0;
    $cutoff_digit = 100;
	$first_pb = 1;
	$cust_num = count($cust_arr);
	$time_limit = 5 * $cust_num + 30;
	set_time_limit($time_limit);
//print_r($cust_arr);
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

//		$day0 = date("Y-m-d");
		$day0 = $d->toIsoDate($end_date);
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

		$rcpt_amt = $last_arr["rcpt_amt"] ?? 0;
		$rcpt_date = $d->toUsaDate($last_arr["rcpt_date"] ?? "")
?>
  <tr> 
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cust_arr[$i]["cust_code"] ?></font></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cust_arr[$i]["cust_name"] ?></font></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cust_arr[$i]["cust_city"].",".$cust_arr[$i]["cust_state"] ?></font></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cust_arr[$i]["cust_tel"] ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal_forwarded,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($pick_sum,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($rcpt_sum,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($cmemo_sum,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($balance,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal30,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal60,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal90,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal90over,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($rcpt_amt,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $d->toShort($rcpt_date) ?></font></td>
  </tr>
  <tr bgcolor="white"><td align="center" colspan="15" height="5"><hr height="1"></td></tr>
<?php
			$total_forwarded += $bal_forwarded;
			$total_sale_amount += $pick_sum;
			$total_rcpt_amount += $rcpt_sum;
			$total_cmemo_amount += $cmemo_sum;
			$total_balance += $balance;
			$total_bal90 += $bal90;
			$total_bal60 += $bal60;
			$total_bal30 += $bal30;
			$total_bal90over += $bal90over;
		}
	}
	$bgcolor="gray";
?>
  <tr> 
    <td align="right" bgcolor="<?= $bgcolor ?>" colspan="4"><b>TOTAL</b></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_forwarded,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_sale_amount,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_rcpt_amount,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_cmemo_amount,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_balance,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_bal30,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_bal60,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_bal90,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($total_bal90over,2,".",",") ?></font></td>
    <td align="left" bgcolor="<?= $bgcolor ?>" colspan="2">&nbsp;</td>
  </tr>
</table>
</BODY>
</HTML>
