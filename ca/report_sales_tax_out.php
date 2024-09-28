<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.receipt.php");
	include_once("class/class.picks.php");
	include_once("class/register_globals.php");

	$dx = new Datex();
	$cu = new Custs();
	$re = new Receipt();
	$pi = new Picks();

	if (empty($tax_year)) $tax_year = date("Y");
	// for quator viewtype 
	if ($tax_qtr == 1) {
		$fds = "3/1/$tax_year";
		$tds = "5/31/$tax_year";
	} else if ($tax_qtr == 2) {
		$fds = "6/1/$tax_year";
		$tds = "8/31/$tax_year";
	} else if ($tax_qtr == 3) {
		$fds = "9/1/$tax_year";
		$tds = "11/30/$tax_year";
	} else if ($tax_qtr == 4) {
		$year = $tax_year -1;
		$tmp = "2/1/$tax_year";
		$last_day=date("t",strtotime($tmp));
		$fds = "12/1/$year";
		$tds = "2/$last_day/$tax_year";
	} else {
		$month = date("m");
		if ($month>1 && $month <= 2) {
			$tax_year = $tax_year -1;
			$fds = "9/1/$tax_year";
			$tds = "11/30/$tax_year";
		} else if ($month>2 && $month <= 5) {
			$year = $tax_year -1;
			$tmp = "2/1/$tax_year";
			$last_day=date("t",strtotime($tmp));
			$fds = "12/1/$year";
			$tds = "2/$last_day/$tax_year";
		} else if ($month>5 && $month <= 8) {
			$fds = "3/1/$tax_year";
			$tds = "5/31/$tax_year";
		} else if ($month>8 && $month <= 11) {
			$fds = "6/1/$tax_year";
			$tds = "8/31/$tax_year";
		} else if ($month == 12) {
			$fds = "9/1/$tax_year";
			$tds = "11/31/$tax_year";
		}
	}
	$ifr = date ("Y-m-d", strtotime($fds));
	$ito = date ("Y-m-d", strtotime($tds));
	$ufr = date ("m/d/Y", strtotime($fds));
	$uto = date ("m/d/Y", strtotime($tds));
	
	$adj = 0.42;
	//$adj = 1;
	if ($viewtype==0) $ratio = $pi->getPicksTaxRatio("","",$ifr, $ito, $tax_state);
	else $ratio = $pi->getPicksTaxRatioMonth("","",$tax_year, $tax_mth, $tax_state);
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setFilter() {
		var f = document.forms[0];
		f.action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.submit();
	}
//-->
</SCRIPT>
<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center">
  <tr align="right"> 
    <td colspan="1" align="center"><h2>Sales Tax Report (<?= $tax_state ?>)</h2></td>
  </tr>
  <tr align="right"> 
    <td colspan="1" align="center"><font size="3">
<?php
	if ($viewtype==0) echo "Date From : $fds To : $tds";
	else {
		echo ("Year of $tax_year : ");
		$first = 1;
		foreach($tax_mth as $k) {
			if ($first==1) $first =0;
			else echo ",";

			if ($k==0) echo "Jan. ";
			else if ($k==1) echo "Feb. ";
			else if ($k==2) echo "Mar. ";
			else if ($k==3) echo "Apr. ";
			else if ($k==4) echo "May ";
			else if ($k==5) echo "Jun. ";
			else if ($k==6) echo "Jul. ";
			else if ($k==7) echo "Aug. ";
			else if ($k==8) echo "Sep. ";
			else if ($k==9) echo "Oct. ";
			else if ($k==10) echo "Nov. ";
			else if ($k==11) echo "Dec. ";
		}
	}
?>
	
	</font></td>
  </tr>
  <tr align="right"> 
    <td colspan="1" align="left"><b>Tax Summary</b></td>
  </tr>
  <tr>
    <td align="center"></td>
      </tr>
      <tr align="center"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0" align="center">
            <tr> 
              <th width="5%" bgcolor="gray"><font color="white">#</font></th>
              <th width="10%" bgcolor="gray"><font color="white">Tax Code</font></th>
              <th width="30%" bgcolor="gray"><font color="white">Tax Name</font></th>
              <th width="10%" bgcolor="gray"><font color="white">Tax Rate</font></th>
              <th width="15%" bgcolor="gray"><font color="white">Txable Sale Amt</font></th>
              <th width="15%" bgcolor="gray"><font color="white">Tax Amt</font></th>
            </tr>
<?php
	// for cash receipt
	if ($viewtype==0) $cu_rcpt = $re->getReceiptRangeSumTax($ifr, $ito, "rcpt_date", "taxrate_code", "f", $tax_state, "taxrate_code");
	else $cu_rcpt = $re->getReceiptRangeSumTaxMonth($tax_year, $tax_mth, "rcpt_date", "taxrate_code", "f", $tax_state, "taxrate_code");
	if ($cu_rcpt) $r_num = count($cu_rcpt);
	else $r_num =0;
	$rcpt_amt_sum = 0;
	$tax_amt_sum = 0;
	for ($i=0; $i<$r_num; $i++) {	
		$amount = ($cu_rcpt[$i][rcpt_sum]-$cu_rcpt[$i][rcpt_disc_sum])*$adj*$ratio;
		if ($amount==0) continue;
		$tax_amt = $amount * $cu_rcpt[$i]["taxrate_pct"] / 100;
		if ($x%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td align="center"> 
         <?= $i+1 ?>
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["taxrate_code"] ?>
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["taxrate_desc"] ?>
       </td>
       <td align="right"> 
         <?= sprintf("%0.3f", $cu_rcpt[$i]["taxrate_pct"]) ?>
       </td>
       <td align="right"> 
         <?= number_format($amount, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($tax_amt, 2, ".", ",") ?>
       </td>
     </tr>
<?php
		$rcpt_amt_sum += $amount;
		$tax_amt_sum += $tax_amt;
	}
	if ($r_num>0) {
?>
	 <tr>
	   <td align="right" colspan="4" bgcolor="silver">Sub Total &nbsp;</td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($rcpt_amt_sum, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($tax_amt_sum, 2, ".", ",") ?>
       </td>
	 </tr>

<?php
	}
?>
	</table></td>
   </tr>
  <tr align="right"> 
    <td colspan="1" align="center"><hr height="1"></td>
  </tr>
  <tr align="right"> 
    <td colspan="1" align="left"><b>Tax Detail</b></td>
  </tr>
  <tr>
    <td align="center"></td>
      </tr>
      <tr align="center"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0" align="center">
            <tr> 
              <th width="5%" bgcolor="gray"><font color="white">#</font></th>
              <th width="10%" bgcolor="gray"><font color="white">Cust.</font></th>
              <th width="25%" bgcolor="gray"><font color="white">Name</font></th>
              <th width="20%" bgcolor="gray"><font color="white">City</font></th>
              <th width="5%" bgcolor="gray"><font color="white">State</font></th>
              <th width="5%" bgcolor="gray"><font color="white">Tax Code</font></th>
              <th width="10%" bgcolor="gray"><font color="white">Tax Rate</font></th>
              <th width="15%" bgcolor="gray"><font color="white">Txable Sale Amt</font></th>
              <th width="15%" bgcolor="gray"><font color="white">Tax Amt</font></th>
            </tr>
<?php
	// for cash receipt
	if ($viewtype==0) $cu_rcpt = $re->getReceiptRangeSumTax($ifr, $ito, "rcpt_date", "rcpt_cust_code", "f", $tax_state, "rcpt_cust_code");
	else $cu_rcpt = $re->getReceiptRangeSumTaxMonth($tax_year, $tax_mth, "rcpt_date", "taxrate_code", "f", $tax_state, "rcpt_cust_code");
	$r_num = count($cu_rcpt);
	$rcpt_amt_sum = 0;
	$tax_amt_sum = 0;
	for ($i=0; $i<$r_num; $i++) {	
		$amount = ($cu_rcpt[$i][rcpt_sum]-$cu_rcpt[$i][rcpt_disc_sum])*$adj*$ratio;
		if ($amount==0) continue;
		$tax_amt = $amount * $cu_rcpt[$i]["taxrate_pct"] / 100;
		if ($x%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td align="center"> 
         <?= $i+1 ?>
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["rcpt_cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["cust_city"] ?>
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["cust_state"] ?>
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["taxrate_code"] ?>
       </td>
       <td align="right"> 
         <?= sprintf("%0.3f", $cu_rcpt[$i]["taxrate_pct"]) ?>
       </td>
       <td align="right"> 
         <?= number_format($amount, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($tax_amt, 2, ".", ",") ?>
       </td>
     </tr>
<?php
		$rcpt_amt_sum += $amount;
		$tax_amt_sum += $tax_amt;
	}
	if ($r_num>0) {
?>
	 <tr>
	   <td align="right" colspan="7" bgcolor="silver">Sub Total &nbsp;</td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($rcpt_amt_sum, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($tax_amt_sum, 2, ".", ",") ?>
       </td>
	 </tr>

<?php
	}
?>
	</table></td>
   </tr>
 </table>
<FONT SIZE="2" COLOR="silver"><?= $ratio ?></FONT>