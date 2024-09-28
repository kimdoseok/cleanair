<?php
	$vars = array("zero_balance");
	foreach ($vars as $var) {
		$$var = "";
	} 

	include_once("class/register_globals.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Print Statement</TITLE>
</HEAD>
<BODY>

<?php
    $cutoff_digit = 100;
	$first_pb = 1;
	$cust_num = count($cust_arr);
	$time_limit = 5 * $cust_num + 30;
	set_time_limit($time_limit);
//print_r($cust_arr);
	for ($i=0;$i<$cust_num;$i++) {
		$c->updateCustsBalance($cust_arr[$i]["cust_code"]);
		$pick_arr = $p->getPicksStmt($cust_arr[$i]["cust_code"], $start_date, $end_date);
//print_r($pick_arr);
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
//print "$balance = $bal_forwarded + $pick_sum - $rcpt_sum - $cmemo_sum <br>";
//print "$pick90over, $pick90, $pick60, $pick30, $pick0 <br>";

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
			  <?= $cust_arr[$i]["cust_city"] ?>, <?= $cust_arr[$i]["cust_state"] ?> <?= $cust_arr[$i]["cust_zip"] ?><br>
			  <?= $cust_arr[$i]["cust_tel"] ?>
			</font>
		  </td>
          <td><font face="Arial, Helvetica, sans-serif">Start Date : <?= $d->toUsaDate($start_date) ?><br>End Date : <?= $d->toUsaDate($end_date) ?><br>Stmt Date : <?= $stmt_date ?></font></td>
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
          <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Ref#</font></strong></td>
          <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Date</font></strong></td>
          <td width="40%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Description</font></strong></td>
          <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Amount</font></strong></td>
          <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Balance</font></strong></td>
        </tr>
        <tr bgcolor="white"> 
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $start_date ?></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">Balance Forwarded</font></td>
          <td align="right">&nbsp;</td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($bal_forwarded, 2, ".", ",") ?></font></td>
        </tr>
<?php
//echo "<pre>";
//print_r($out_arr);
//echo "</pre>";

	$abalance = $bal_forwarded;
	if ($out_arr) $out_num = count($out_arr);
	else $out_num = 0;
	for ($j=0;$j<$out_num;$j++) { 
		if ($out_arr[$j][0]=="I") {
			$abalance += $out_arr[$j][2]["pick_total"];
?>
        <tr bgcolor="white"> 
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $out_arr[$j][2]["pick_id"] ?></font></td>
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $out_arr[$j][2]["pick_date"] ?></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">Invoice: <?= $out_arr[$j][2]["pick_id"] ?>
<?php
	    $pdtl_arr = $pd->getPickDtlsListSales($out_arr[$j][2]["pick_id"]);
	    if ($pdtl_arr) $pdtl_num = count($pdtl_arr);
	    else $pdtl_num =0;
	    if ($pdtl_num>0) echo " (";
	    for ($k=0;$k<$pdtl_num;$k++) {
	    	if ($k!=0) echo ", ";
			echo $pdtl_arr[$k]["slsdtl_sale_id"];
	    }
	    if ($pdtl_num>0) echo ")";
?>	  
	  </font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($out_arr[$j][2]["pick_total"],2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($abalance,2,".",",") ?></font></td>
        </tr>
<?php
		} else if ($out_arr[$j][0]=="C") {
			$abalance -= $out_arr[$j][2]["cmemo_total"];
?>
        <tr bgcolor="white"> 
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $out_arr[$j][2]["cmemo_id"] ?></font></td>
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $out_arr[$j][2]["cmemo_date"] ?></font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">CR Memo: <?= $out_arr[$j][2]["cmemo_id"] ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($out_arr[$j][2]["cmemo_total"]*-1,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($abalance,2,".",",") ?></font></td>
        </tr>
<?php
		} else if ($out_arr[$j][0]=="R") {
			$abalance -= $out_arr[$j][2]["rcpt_amt"]-$out_arr[$j][2]["rcpt_disc_amt"];
			if ($out_arr[$j][2]["rcpt_type"] == "ca") $description = "Cash";
			else if ($out_arr[$j][2]["rcpt_type"] == "ch") $description = "Check #".$out_arr[$j][2]["rcpt_check_no"];
			else if ($out_arr[$j][2]["rcpt_type"] == "cc") $description = "Credit Card";
			else if ($out_arr[$j][2]["rcpt_type"] == "dc") $description = "Discount";
			else if ($out_arr[$j][2]["rcpt_type"] == "bc") $description = "Bounced Check";
			else if ($out_arr[$j][2]["rcpt_type"] == "ot") $description = "Other Payment Type";
			else $description = "Unknown";
?>
        <tr bgcolor="white"> 
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $out_arr[$j][2]["rcpt_id"] ?></font></td>
          <td align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?= $out_arr[$j][2]["rcpt_date"] ?></font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">Receipt: <?= $description ?> (<?= $out_arr[$j][2]["rcpt_id"] ?>)</font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format(($out_arr[$j][2]["rcpt_amt"]+$out_arr[$j][2]["rcpt_disc_amt"])*-1,2,".",",") ?></font></td>
          <td align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?= number_format($abalance,2,".",",") ?></font></td>
        </tr>
<?php
		}
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
