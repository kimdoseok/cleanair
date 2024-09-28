<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.cmemo.php");
	include_once("class/class.receipt.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.cmemodtls.php");
	include_once("class/class.rcptdtls.php");
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
	$cu_picks = $pi->getPicksRangeSumState($ifr, $ito, $state, "pick_date", "pick_cust_code");
	$cu_cmemo = $cm->getCmemoRangeSumState($ifr, $ito, $state, "cmemo_date", "cmemo_cust_code");
	$cu_rcpt = $re->getReceiptRangeSumState($ifr, $ito, $state, "rcpt_date", "rcpt_cust_code");

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
    <td colspan="1" align="center"><strong>Daily Report Summary</strong></td>
  </tr>
  <tr>
    <td align="center"><font size="3">
	  Date From : <?= (empty($fr))?"Beginning":$fr ?> 
	  To : <?= (empty($to))?"Today":$to ?></font></td>
      </tr>

<?php
if ($_POST["show_pick"]=="t") {
?>
      <tr align="right"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th width="5%" bgcolor="gray"><font color="white">#</font></th>
              <th width="5%" bgcolor="gray"><font color="white">Type</font></th>
              <th width="10%" bgcolor="gray"><font color="white">Cust.</font></th>
              <th width="25%" bgcolor="gray"><font color="white">Name</font></th>
              <th width="3%" bgcolor="gray"><font color="white">State</font></th>
              <th width="9%" bgcolor="gray"><font color="white">S.Amt</font></th>
              <th width="9%" bgcolor="gray"><font color="white">F.Amt</font></th>
              <th width="9%" bgcolor="gray"><font color="white">T.Amt</font></th>
              <th width="15%" bgcolor="gray"><font color="white">Amount</font></th>
            </tr>
<?php
	$x = 0;
	// for Picking Tickiet
	$pick_amt_sum = 0;
	$pick_freight_amt_sum = 0;
	$pick_tax_amt_sum = 0;
	$p_num = count($cu_picks);
	for ($i=0; $i<$p_num; $i++) {	
		$x++;
		if ($_POST["show_line"] == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
?>
       <td align="center"> 
         <?= $x ?>
       </td>
       <td align="left"> 
         Sales
       </td>
       <td align="left"> 
         <?= $cu_picks[$i]["pick_cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $cu_picks[$i]["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $cu_picks[$i]["cust_state"] ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_picks[$i][pick_sum], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_picks[$i][pick_freight_sum], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_picks[$i][pick_tax_sum], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_picks[$i][pick_sum] + $cu_picks[$i][pick_freight_sum] + $cu_picks[$i][pick_tax_sum], 2, ".", ",") ?>
       </td>
     </tr>
<?php
			if ($_POST["show_line"]=="t") {
?>
     <tr bgcolor="#FFFFFF">
       <td align="center"> 
         &nbsp;
       </td>
       <td align="center"> 
         &nbsp;
       </td>
       <td align="left" colspan="6"> 
	     <table width="100%" border="0" cellspacing="1" cellpadding="0">
<?php
			$pd = new PickDtls();
			$sd = new SaleDtls();
		    $pdarr = $pd->getPickDtlsList($cu_picks[$i]["pick_id"]);
			for ($j=0;$j<count($pdarr);$j++) {
?>
			<tr>
             <td width="15%"> 
               <?= $pdarr[$j]["slsdtl_item_code"] ?>
             </td>
             <td width="60%"> 
               <?= $pdarr[$j]["slsdtl_item_desc"] ?>
             </td>
             <td width="15%" align="right"> 
               <?= sprintf("%0.2f",$pdarr[$j]["pickdtl_cost"]) ?> 
			   x
               <?= $pdarr[$j]["pickdtl_qty"]+0 ?>
             </td>
             <td width="8%" align="right"> 
               <?= sprintf("%0.2f",$pdarr[$j]["pickdtl_cost"]*$pdarr[$j]["pickdtl_qty"]) ?>
             </td>
             <td width="2%" align="center"> 
               <?= ($sdarr["slsdtl_taxable"]=="t")?"X":"&nbsp;" ?>
             </td>
			</tr>
<?php
			}
?>
		 </table>
       </td>
       <td align="center"> 
         &nbsp;
       </td>
     </tr>
<?php
		}
		$pick_amt_sum += $cu_picks[$i][pick_sum];
		$pick_freight_amt_sum += $cu_picks[$i][pick_freight_sum];
		$pick_tax_amt_sum += $cu_picks[$i][pick_tax_sum];
	}

	if ($p_num > 0) {
?>
	 <tr>
	   <td align="right" colspan="5" bgcolor="silver">Sub Total &nbsp;</td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($pick_amt_sum, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($pick_freight_amt_sum, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($pick_tax_amt_sum, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($pick_amt_sum+$pick_freight_amt_sum+$pick_tax_amt_sum, 2, ".", ",") ?>
       </td>
	 </tr>
	 <tr><td align="center" colspan="9"><HR></td></tr>
<?php
	}
}
if ($_POST["show_cmemo"]=="t") {

	// for Credit Memo
	$cmemo_amt_sum = 0;
	$cmemo_freight_amt_sum = 0;
	$cmemo_tax_amt_sum = 0;
	$c_num = count($cu_cmemo);
	for ($i=0; $i<$c_num; $i++) {	
		$x++;
		if ($_POST["show_line"] == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
?>
       <td align="center"> 
         <?= $x ?>
       </td>
       <td align="left"> 
         Credit
       </td>
       <td align="left"> 
         <?= $cu_cmemo[$i]["cmemo_cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $cu_cmemo[$i]["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $cu_picks[$i]["cust_state"] ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_cmemo[$i][cmemo_sum]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_cmemo[$i][cmemo_freight_sum]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_cmemo[$i][cmemo_tax_sum]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format(($cu_cmemo[$i][cmemo_sum] + $cu_cmemo[$i][cmemo_freight_sum] + $cu_cmemo[$i][cmemo_tax_sum])*-1, 2, ".", ",") ?>
       </td>
     </tr>
<?php
			if ($_POST["show_line"]=="t") {
?>
     <tr bgcolor="#FFFFFF">
       <td align="center"> 
         &nbsp;
       </td>
       <td align="center"> 
         &nbsp;
       </td>
       <td align="left" colspan="6"> 
	     <table width="100%" border="0" cellspacing="1" cellpadding="0">
<?php
				$cd = new CmemoDtl();

				$cdarr = $cd->getCmemoDtlList($cu_cmemo[$i]["cmemo_id"]);
				for ($j=0;$j<count($cdarr);$j++) {
?>
			<tr>
             <td width="15%"> 
               <?= $cdarr[$j]["cmemodtl_item_code"] ?>
             </td>
             <td width="60%"> 
               <?= $cdarr[$j]["cmemodtl_item_desc"] ?>
             </td>
             <td width="15%" align="right"> 
               <?= sprintf("%0.2f",$cdarr[$j]["cmemodtl_cost"]) ?> 
			   x
               <?= $cdarr[$j]["cmemodtl_qty"]+0 ?>
             </td>
             <td width="8%" align="right"> 
               <?= sprintf("%0.2f",$cdarr[$j]["cmemodtl_cost"]*$cdarr[$j]["cmemodtl_qty"]) ?>
             </td>
             <td width="2%" align="center"> 
               <?= ($cdarr["cmemodtl_taxable"]=="t")?"X":"&nbsp;" ?>
             </td>
			</tr>
<?php
				}
?>
		 </table>
       </td>
       <td align="center"> 
         &nbsp;
       </td>
     </tr>
<?php
		}
		$cmemo_amt_sum += $cu_cmemo[$i]["cmemo_amt"];
		$cmemo_freight_amt_sum += $cu_cmemo[$i]["cmemo_freight_amt"];
		$cmemo_tax_amt_sum += $cu_cmemo[$i]["cmemo_tax_amt"];
	}
	if ($c_num > 0) {
?>
	 <tr>
	   <td align="right" colspan="5" bgcolor="silver">Sub Total &nbsp;</td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($cmemo_amt_sum*-1, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($cmemo_freight_amt_sum*-1, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($cmemo_tax_amt_sum*-1, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format(($cmemo_amt_sum+$cmemo_freight_amt_sum+$cmemo_tax_amt_sum)*-1, 2, ".", ",") ?>
       </td>
	 </tr>
	 <tr><td align="center" colspan="9"><HR></td></tr>
<?php
	}
}
if ($_POST["show_rcpt"]=="t") {
	// for cash receipt
	$r_num = count($cu_rcpt);
	for ($i=0; $i<$r_num; $i++) {	
		$x++;
		if ($_POST["show_line"] == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
?>
       <td align="center"> 
         <?= $x ?>
       </td>
       <td align="left"> 
         Receipt
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["rcpt_cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $cu_picks[$i]["cust_state"] ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_rcpt[$i][rcpt_sum]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_rcpt[$i][rcpt_disc_sum]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         &nbsp;
       </td>
       <td align="right"> 
         <?= number_format(($cu_rcpt[$i][rcpt_sum]+$cu_rcpt[$i][rcpt_disc_sum])*-1, 2, ".", ",") ?>
       </td>
     </tr>
	 <?php
			if ($_POST["show_line"]=="t") {
?>
     <tr bgcolor="#FFFFFF">
       <td align="center"> 
         &nbsp;
       </td>
       <td align="center"> 
         &nbsp;
       </td>
       <td align="left" colspan="6"> 
	     <table width="100%" border="0" cellspacing="1" cellpadding="0">
<?php
				$rd = new RcptDtls();
				$rdarr = $rd->getRcptDtlsList($cu_rcpt[$i]["rcpt_id"]);
				for ($j=0;$j<count($rdarr);$j++) {
?>
			<tr>
             <td width="15%"> 
               <?= $rdarr[$j]["rcptdtl_pick_id"] ?>
             </td>
             <td width="60%"> 
               <?= $rdarr[$j]["rcptdtl_desc"] ?>
             </td>
             <td width="10%"> 
               <?= strtoupper($rdarr[$j]["rcptdtl_type"]) ?>
             </td>
             <td width="15%" align="right"> 
               <?= sprintf("%0.2f",$rdarr[$j]["rcptdtl_amt"]) ?> 
             </td>
			</tr>
<?php
				}
?>
		 </table>
       </td>
       <td align="center"> 
         &nbsp;
       </td>
     </tr>
<?php
	   }
		$rcpt_amt_sum += $cu_rcpt[$i][rcpt_sum];
		$rcpt_disc_amt_sum += $cu_rcpt[$i][rcpt_disc_sum];
	}
	if ($r_num>0) {
?>
	 <tr>
	   <td align="right" colspan="5" bgcolor="silver">Sub Total &nbsp;</td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($rcpt_amt_sum*-1, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format($rcpt_disc_amt_sum*-1, 2, ".", ",") ?>
       </td>
       <td align="right" bgcolor="silver"> 
         &nbsp;
       </td>
       <td align="right" bgcolor="silver"> 
         <?= number_format(($rcpt_amt_sum+$rcpt_disc_amt_sum)*-1, 2, ".", ",") ?>
       </td>
	 </tr>

<?php
	}
	if ($x == 0) {
?>
	 <tr><td align="center" colspan="5"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	} else {
?>
	 <tr>
	   <td align="right" colspan="5" bgcolor="gray"><b>Total</b> &nbsp;</td>
       <td align="right" colspan="4" bgcolor="gray"> <b>
         <?= number_format(($pick_amt_sum+$pick_freight_amt_sum+$pick_tax_amt_sum) - ($cmemo_amt_sum+$cmemo_freight_amt_sum+$cmemo_tax_amt_sum) - ($rcpt_amt_sum+$rcpt_disc_amt_sum), 2, ".", ",") ?></b>
       </td>
	 </tr>
<?php
	}
?>
	</table></td>
   </tr>
<?php
}
?>
</table>
