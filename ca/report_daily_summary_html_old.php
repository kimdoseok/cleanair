<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.cmemo.php");
	include_once("class/class.receipt.php");
	include_once("class/register_globals.php");

	$dx = new Datex();
	$cu = new Custs();
	$pi = new Picks();
	$cm = new Cmemo();
	$re = new Receipt();

	if (empty($fr)) $fr = date("m/d/Y");
	if (empty($to)) $to = date("m/d/Y");
	$ifr = $dx->toIsoDate($fr);
	$ito = $dx->toIsoDate($to);
	$cu_picks = $pi->getPicksRange($ifr, $ito, "pick_date");
	$cu_cmemo = $cm->getCmemoRange($ifr, $ito, "cmemo_date");
	$cu_rcpt = $re->getReceiptRange($ifr, $ito, "rcpt_date");

	$cu_arr = array();
	if ($cu_picks) {
		$p_num = count($cu_picks);
		for ($i=0;$i<$p_num;$i++) {
			if ($cu_arr) $cu_num = count($cu_arr);
			$found = 0;
			for ($j=0;$j<$cu_num;$j++) {
				if ($cu_arr[$j]==$cu_picks[$i]["pick_cust_code"]) {
					$found = 1;
					break;
				}
			}
			if ($found == 0) {
				array_push($cu_arr, $cu_picks[$i]["pick_cust_code"]);
			}
		}
	} else {
		$p_num =0;
	}

	if ($cu_cmemo) {
		$c_num = count($cu_cmemo);
		for ($i=0;$i<$c_num;$i++) {
			if ($cu_arr) $cu_num = count($cu_arr);
			$found = 0;
			for ($j=0;$j<$cu_num;$j++) {
				if ($cu_arr[$j]==$cu_cmemo[$i]["cmemo_cust_code"]) {
					$found = 1;
					break;
				}
			}
			if ($found == 0) {
				array_push($cu_arr, $cu_cmemo[$i]["cmemo_cust_code"]);
			}
		}
	} else {
		$c_num =0;
	}

	if ($cu_rcpt) {
		$r_num = count($cu_rcpt);
		for ($i=0;$i<$r_num;$i++) {
			if ($cu_arr) $cu_num = count($cu_arr);
			$found = 0;
			for ($j=0;$j<$cu_num;$j++) {
				if ($cu_arr[$j]==$cu_rcpt[$i]["rcpt_cust_code"]) {
					$found = 1;
					break;
				}
			}
			if ($found == 0) {
				array_push($cu_arr, $cu_rcpt[$i][crcptcust_code]);
			}
		}
	} else {
		$r_num =0;
	}

	sort($cu_arr);
	$cu_num = count($cu_arr);

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
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td colspan="1"><strong>Daily Report Summary</strong></td>
  </tr>
  <tr>
    <td align="center"><font size="3">
	  Date From : <?= (empty($fr))?"Beginning":$fr ?> 
	  To : <?= (empty($to))?"Today":$to ?></font></td>
      </tr>
      <tr align="right"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th bgcolor="gray"><font color="white">#</font></th>
              <th bgcolor="gray"><font color="white">Type</font></th>
              <th bgcolor="gray" colspan="2"><font color="white">Customer</font></th>
              <th bgcolor="gray"><font color="white">Amount</font></th>
            </tr>
<?php
	$x = 0;
	// for Picking Tickiet
	for ($i=0; $i<$cu_num; $i++) {	
		$cust_arr = $cu->getCusts($cu_arr[$i]);
		$amt = 0;
		for ($j=0;$j<$p_num;$j++) {
			if ($cu_picks[$j]["pick_cust_code"]==$cu_arr[$i]) {
				$amt += $cu_picks[$j]["pick_amt"];
				$amt += $cu_picks[$j]["pick_tax_amt"];
				$amt += $cu_picks[$j]["pick_freight_amt"];
			}
		}
		if ($amt > 0) {
			$x++;
			if ($x%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td width="10%" align="center"> 
         <?= $x ?>
       </td>
       <td width="10%" align="left"> 
         Sales
       </td>
       <td width="10%" align="center"> 
         <?= $cu_arr[$i] ?>
       </td>
       <td width="50%" align="left"> 
         <?= $cust_arr["cust_name"] ?>
       </td>
       <td width="20%" align="right"> 
         <?= number_format($amt, 2, ".", ",") ?>
       </td>
     </tr>
<?php
		}
	}

	if ($x > 0) {
?>
	 <tr><td align="center" colspan="5"><HR></td></tr>
<?php
	}

	// for Credit Memo
	for ($i=0; $i<$cu_num; $i++) {	
		$cust_arr = $cu->getCusts($cu_arr[$i]);
		$amt = 0;
		for ($j=0;$j<$c_num;$j++) {
			if ($cu_cmemo[$j]["cmemo_cust_code"]==$cu_arr[$i]) {
				$amt += $cu_cmemo[$j]["cmemo_amt"];
				$amt += $cu_cmemo[$j]["cmemo_tax_amt"];
				$amt += $cu_cmemo[$j]["cmemo_freight_amt"];
			}
		}
		if ($amt > 0) {
			$x++;
			if ($x%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td width="10%" align="center"> 
         <?= $x ?>
       </td>
       <td width="10%" align="left"> 
         Credit
       </td>
       <td width="10%" align="center"> 
         <?= $cu_arr[$i] ?>
       </td>
       <td width="50%" align="left"> 
         <?= $cust_arr["cust_name"] ?>
       </td>
       <td width="20%" align="right"> 
         <?= number_format($amt, 2, ".", ",") ?>
       </td>
     </tr>
<?php
		}
	}
	if ($x > 0) {
?>
	 <tr><td align="center" colspan="5"><HR></td></tr>
<?php
	}

	// for cash receipt
	for ($i=0; $i<$cu_num; $i++) {	
		$cust_arr = $cu->getCusts($cu_arr[$i]);
		$amt = 0;
		for ($j=0;$j<$r_num;$j++) {
			if ($cu_rcpt[$j]["rcpt_cust_code"]==$cu_arr[$i]) {
				$amt += $cu_rcpt[$j]["rcpt_amt"];
			}
		}
		if ($amt > 0) {
			$x++;
			if ($x%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td width="10%" align="center"> 
         <?= $x ?>
       </td>
       <td width="10%" align="left"> 
         Receipt
       </td>
       <td width="10%" align="center"> 
         <?= $cu_arr[$i] ?>
       </td>
       <td width="50%" align="left"> 
         <?= $cust_arr["cust_name"] ?>
       </td>
       <td width="20%" align="right"> 
         <?= number_format($amt, 2, ".", ",") ?>
       </td>
     </tr>
<?php
		}
	}

	if ($x == 0) {
?>
	 <tr><td align="center" colspan="5"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
	</table></td>
   </tr>
 </table>
