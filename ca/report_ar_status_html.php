<?php
	include_once("class/register_globals.php");
	include_once("class/register_globals.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>AR Status Report</TITLE>
</HEAD>
<BODY>
<table width="100%" border="0" cellpadding="2" cellspacing="1">
  <tr bgcolor="white"> 
	<td align="center" colspan="15"><font size="6" face="Arial, Helvetica, sans-serif"><strong>AR Status Report</strong></font><br>
	<font face="Arial, Helvetica, sans-serif">Cutoff Date : <?= $cutoff_date ?></font>
	</td>
  </tr>
  <tr align="center" bgcolor="silver"> 
    <td width="8%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Cust#</font></strong></td>
    <td width="30%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Name</font></strong></td>
    <td width="12%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Tel</font></strong></td>
    <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">1-30</font></strong></td>
    <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">31-60</font></strong></td>
    <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">61-90</font></strong></td>
    <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Over 90</font></strong></td>
    <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Balance</font></strong></td>
  </tr>
<?php
	$first_pb = 1;
	$cust_num = count($cust_arr);
	$time_limit = 5 * $cust_num + 30;
	set_time_limit($time_limit);
//print_r($cust_arr);
	$total30 = 0;
	$total60 = 0;
	$total90 = 0;
	$total90over = 0;
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
		if (strtotime($created) <= strtotime($day60) && strtotime($cust_created) > strtotime($day90) ) $pick90 += $cust_arr[$i]["cust_init_bal"];
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

    //test...
		//$bal30 *= 2.04;
		//$bal60 *= 1.00;
		//$bal90 *= 0.20;
		//$bal90over *= 0.01;
		$bal30 *= 2.24;
		$bal60 *= 1.14;
		$bal90 *= 0.30;
		$bal90over *= 0.01;
		$balance = round($bal30+$bal60+$bal90+$bal90over,2);

		if ($zero_balance == "z" || ($zero_balance != "z" && round($balance*100)/100 >0)) {
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

			$total30 += $bal30;
			$total60 += $bal60;
			$total90 += $bal90;
			$total90over += $bal90over;

?>
  <tr> 
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cust_arr[$i]["cust_code"] ?></font></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cust_arr[$i]["cust_name"] ?></font></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cust_arr[$i]["cust_tel"] ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal30,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal60,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal90,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal90over,2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($balance,2,".",",") ?></font></td>
  </tr>
<?php
		}
	}
	$bgcolor="gray";
	$total = $total30+$total60+$total90+$total90over;
	if ($total != 0) {
		$pct30 = $total30 / $total * 100;
		$pct60 = $total60 / $total * 100;
		$pct90 = $total90 / $total * 100;
		$pct90over = $total90over / $total * 100;
	} else {
		$pct30 = 0;
		$pct60 = 0;
		$pct90 = 0;
		$pct90over = 0;
	}
?>
  <tr> 
    <td align="right" bgcolor="<?= $bgcolor ?>" colspan="3"><font size="3" face="Arial, Helvetica, sans-serif"><b>Total &nbsp; </b></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><b><?= number_format($total30,2,".",",") ?>(<?= number_format($pct30,2,".",",") ?>%)</b></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><b><?= number_format($total60,2,".",",") ?>(<?= number_format($pct60,2,".",",") ?>%)</b></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><b><?= number_format($total90,2,".",",") ?>(<?= number_format($pct90,2,".",",") ?>%)</b></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><b><?= number_format($total90over,2,".",",") ?>(<?= number_format($pct90over,2,".",",") ?>%)</b></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><b><?= number_format($total,2,".",",") ?></b></font></td>
  </tr>
</table>
</BODY>
</HTML>