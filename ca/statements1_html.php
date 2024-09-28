<?php
	include_once("class/register_globals.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Print Statement</TITLE>
</HEAD>
<BODY>

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
				echo "<DIV style=\"page-break-after:always\"></DIV>";
			}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr bgcolor="white"> 
          <td width="64%"><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td width="36%"><font size="6" face="Arial, Helvetica, sans-serif"><strong>Statement</strong></font></td>
        </tr>
        <tr bgcolor="white"> 
          <td>
		    <font face="Arial, Helvetica, sans-serif">
			  <b><?= $cust_arr[$i]["cust_name"] ?>(<?= $cust_arr[$i]["cust_code"] ?>)</b><br>
			  <?= (!empty($cust_arr[$i]["cust_addr1"]))? $cust_arr[$i]["cust_addr1"]."<br>":"" ?>
			  <?= (!empty($cust_arr[$i]["cust_addr2"]))? $cust_arr[$i]["cust_addr2"]."<br>":"" ?>
			  <?= (!empty($cust_arr[$i]["cust_addr3"]))? $cust_arr[$i]["cust_addr3"]."<br>":"" ?>
			  <?= $cust_arr[$i]["cust_city"] ?>, <?= $cust_arr[$i]["cust_state"] ?> <?= $cust_arr[$i]["cust_zip"] ?>
			</font>
		  </td>
          <td><font face="Arial, Helvetica, sans-serif">Start Date : <?= $d->toUsaDate($start_date) ?><br>End Date : <?= $d->toUsaDate($end_date) ?><br>Stmt Date : <?= date("m/d/Y") ?></font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="19" bgcolor="white"><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="black">
        <tr align="center" bgcolor="silver"> 
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Bal. 
            Forward</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
            Sales</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
            Paid</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
            Credit</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Balance</font></strong></td>
        </tr>
        <tr bgcolor="white"> 
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal_forwarded,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($pick_sum,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($rcpt_sum,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($cmemo_sum,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($balance,2,".",",") ?></font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td bgcolor="white"><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="black">
        <tr align="center" bgcolor="silver"> 
          <td width="9%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Ref#</font></strong></td>
          <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Date</font></strong></td>
          <td width="40%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Description</font></strong></td>
          <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Amount</font></strong></td>
        </tr>
        <tr bgcolor="white"> 
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $start_date ?></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">Balance Forwarded</font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal_forwarded, 2, ".", ",") ?></font></td>
        </tr>
<?php
	for ($j=0;$j<$pick_num;$j++) {
?>
        <tr bgcolor="white"> 
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $pick_arr[$j]["pick_id"] ?></font></td>
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $pick_arr[$j]["pick_date"] ?></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">Invoice: <?= $pick_arr[$j]["pick_code"] ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= $pick_arr[$j]["pick_total"] ?></font></td>
        </tr>
<?php
	}
	for ($j=0;$j<$cmemo_num;$j++) {
?>
        <tr bgcolor="white"> 
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cmemo_arr[$j]["cmemo_id"] ?></font></td>
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $cmemo_arr[$j]["cmemo_date"] ?></font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">CR Memo: <?= $cmemo_arr[$j]["cmemo_id"] ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($cmemo_arr[$j]["cmemo_total"],2,".",",") ?></font></td>
        </tr>
<?php
	}
	for ($j=0;$j<$rcpt_num;$j++) {
		if ($rcpt_arr[$j]["rcpt_type"] == "ca") $description = "Cash";
		else if ($rcpt_arr[$j]["rcpt_type"] == "ch") $description = "Check #".$rcpt_arr[$j]["rcpt_check_no"];
		else if ($rcpt_arr[$j]["rcpt_type"] == "cc") $description = "Credit Card";
		else if ($rcpt_arr[$j]["rcpt_type"] == "ot") $description = "Other Payment Type";
		else $description = "Unknown";
?>
        <tr bgcolor="white"> 
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $rcpt_arr[$j]["rcpt_id"] ?></font></td>
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $rcpt_arr[$j]["rcpt_date"] ?></font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">Receipt: <?= $description ?> (<?= $rcpt_arr[$j]["rcpt_id"] ?>)</font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($rcpt_arr[$j]["rcpt_amt"],2,".",",") ?></font></td>
        </tr>
<?php
	}
?>
      </table></td>
  </tr>
  <tr> 
    <td bgcolor="white"><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="black">
        <tr align="center" bgcolor="silver"> 
<!--
		  <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Current</font></strong></td>
-->
          <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">1-30 
            Due</font></strong></td>
          <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">31-60 
            Due</font></strong></td>
          <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">61-90 
            Due</font></strong></td>
          <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Over 
            90 Due</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Amount 
            Due</font></strong></td>
        </tr>
		<tr bgcolor="white"> 
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal30,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal60,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal90,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal90over,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($balance,2,".",",") ?></font></td>
        </tr>
<!--
		<tr bgcolor="white"> 
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal30,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal60,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal90,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal90over,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($balance,2,".",",") ?></font></td>
        </tr>
-->
      </table>
    </td>
  </tr>
  <tr> 
    <td bgcolor="white">&nbsp;</td>
  </tr>
</table>
<?php
		}
	}
?>
</BODY>
</HTML>