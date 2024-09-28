<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.cmemo.php");
	include_once("class/class.cmemodtls.php");
	include_once("class/class.receipt.php");
	include_once("class/class.rcptdtls.php");
	include_once("class/class.requests.php");
	include_once("class/class.userauths.php");
	include_once("class/register_globals.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Sales Rep. Status Report</TITLE>
</HEAD>
<BODY>
<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="white">
  <tr bgcolor="white"> 
	<td align="center" colspan="16"><font size="6" face="Arial, Helvetica, sans-serif"><strong>Sales Rep. Status Report</strong></font><br>
	<font face="Arial, Helvetica, sans-serif">Start Date : <?= $d->toUsaDate($start_date) ?> / End Date : <?= $d->toUsaDate($end_date) ?> / CutOff Date : <?= $cutoff_date ?></font>
	</td>
  </tr>
  <tr bgcolor="white"><td align="center" colspan="16" height="5"><hr height="1"></td></tr>
  <tr align="center" bgcolor="silver"> 
    <td width="5%"><strong><font size="2" face="Arial, Helvetica, sans-serif">SR#</font></strong></td>
    <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Cust#</font></strong></td>
    <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Name</font></strong></td>
    <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">City/State</font></strong></td>
    <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Tel</font></strong></td>
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
  <tr bgcolor="white"><td align="center" colspan="16" height="5"><hr height="1"></td></tr>
<?php
	$first_pb = 1;
	$cust_num = count($cust_arr);
	$time_limit = 5 * $cust_num + 30;
	set_time_limit($time_limit);
//print_r($cust_arr);
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
		$balance = $bal_forwarded + $pick_sum - $rcpt_sum - $cmemo_sum;

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
		$b90over = $t_bal;

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
?>
  <tr> 
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cust_arr[$i]["cust_slsrep"] ?></font></td>
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
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($last_arr["rcpt_amt"],2,".",",") ?></font></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif"><?= $d->toShort($last_arr["rcpt_date"]) ?></font></td>
  </tr>
  <tr bgcolor="white"><td align="center" colspan="16" height="5"><hr height="1"></td></tr>
<?php
		}
	}
?>
</table>
</BODY>
</HTML>