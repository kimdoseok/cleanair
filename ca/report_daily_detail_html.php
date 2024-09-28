<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.cmemodtls.php");
	include_once("class/class.rcptdtls.php");

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
  <tr align="center"> 
    <td colspan="1" align="center"><font size="4"><strong>Daily Report Detail</strong></font></td>
  </tr>
  <tr>
    <td align="center"><font size="3">
	  Date From : <?= (empty($fr))?"Beginning":$fr ?> 
	  To : <?= (empty($to))?"Today":$to ?></font></td>
      </tr>
<?php
	$pick_amt_total = 0;
	$pick_freight_amt_total = 0;
	$pick_tax_amt_total = 0;
	$cmemo_amt_total = 0;
	$cmemo_freight_amt_total = 0;
	$cmemo_tax_amt_total = 0;
	$rcpt_amt_total = 0;
	$rcpt_disc_amt_total = 0;

	$fr_t = strtotime($fr);
	$to_t = strtotime($to);
	$days = ceil(($to_t+86399 - $fr_t)/86400 + 0);
	$time_arr = getdate($fr_t);

	for ($k=0;$k<$days;$k++) {
		$ts = mktime(0,0,0,$time_arr["mon"],$time_arr["mday"]+$k,$time_arr["year"]);
		$idate = date("Y-m-d", $ts);

		$cu_picks = $pi->getPicksRangeState($idate, $idate, $state, "pick_date", "t", "pick_cust_code");
		if ($cu_picks) $p_num = count($cu_picks);
		else $p_num =0;
		$cu_cmemo = $cm->getCmemoRangeState($idate, $idate, $state, "cmemo_date", "t", "cmemo_cust_code");
		if ($cu_cmemo) $c_num = count($cu_cmemo);
		else $c_num =0;
		$cu_rcpt = $re->getReceiptRangeState($idate, $idate, $state, "rcpt_date", "t", "rcpt_cust_code");
		if ($cu_rcpt) $r_num = count($cu_rcpt);
		else $r_num =0;

?>
 	  <tr>
	    <td align="left" bgcolor="black">
		  <font face="Helvetica" color="white"><b>&nbsp; <?= date("m/d/Y", $ts) ?></b></font>
	    </td>
	  </tr>
      <tr> 
        <td>
		  <table width="100%" border="0" cellspacing="1" cellpadding="0">
<?php
  $showline = "";
  if (array_key_exists("show_line", $_POST)) {
    $showline = $_POST["show_line"];
  }
  $showpick = "";
  if (array_key_exists("show_pick", $_POST)) {
    $showpick = $_POST["show_pick"];
  }
  $showcmemo = "";
  if (array_key_exists("show_cmemo", $_POST)) {
    $showcmemo = $_POST["show_cmemo"];
  }
  $showrcpt = "";
  if (array_key_exists("show_rcpt", $_POST)) {
    $showrcpt = $_POST["show_rcpt"];
  }
  

if ($showpick == "t") {
?>
            <tr> 
              <th bgcolor="gray" width="5%"><font color="white">#</font></th>
              <th bgcolor="gray" width="7%"><font color="white">Ref#</font></th>
              <th bgcolor="gray" width="8%"><font color="white">Type</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Cust.</font></th>
              <th bgcolor="gray" width="30%"><font color="white">Name</font></th>
              <th bgcolor="gray" width="3%"><font color="white">State</font></th>
              <th bgcolor="gray" width="10%"><font color="white">S.Amt</font></th>
              <th bgcolor="gray" width="10%"><font color="white">F.Amt</font></th>
              <th bgcolor="gray" width="10%"><font color="white">T.Amt</font></th>
              <th bgcolor="gray" width="17%"><font color="white">Amount</font></th>
            </tr>
<?php
	$x = 0;
	// for Picking Tickiet
	$pick_amt_sum = 0;
	$pick_freight_amt_sum = 0;
	$pick_tax_amt_sum = 0;


	for ($i=0; $i<$p_num; $i++) {	
		$cust_arr = $cu->getCusts($cu_picks[$i]["pick_cust_code"]);
		$amt = $cu_picks[$i]["pick_amt"]+$cu_picks[$i]["pick_tax_amt"]+$cu_picks[$i]["pick_freight_amt"];
		$x++;
		if ($showline == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
?>
       <td align="center"> 
         <?= $x ?>
       </td>
       <td align="center"> 
         <?= $cu_picks[$i]["pick_id"] ?>
       </td>
       <td align="left"> 
         Sales
       </td>
       <td align="left"> 
         <?= $cu_picks[$i]["pick_cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $cust_arr["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $cust_arr["cust_state"] ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_picks[$i]["pick_amt"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_picks[$i]["pick_freight_amt"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_picks[$i]["pick_tax_amt"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($amt, 2, ".", ",") ?>
       </td>
     </tr>
<?php
			if ($showline == "t") {
?>
     <tr bgcolor="#FFFFFF">
       <td align="center"> 
         &nbsp;
       </td>
       <td align="center"> 
         &nbsp;
       </td>
       <td align="left" colspan="7"> 
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
		} // show lines endif
		$pick_amt_sum += $cu_picks[$i]["pick_amt"];
		$pick_freight_amt_sum += $cu_picks[$i]["pick_freight_amt"];
		$pick_tax_amt_sum += $cu_picks[$i]["pick_tax_amt"];
	}
	if ($p_num>0) {
		$pick_amt_total += $pick_amt_sum;
		$pick_freight_amt_total += $pick_freight_amt_sum;
		$pick_tax_amt_total += $pick_tax_amt_sum;
?>
	 <tr>
	   <td align="right" colspan="6" bgcolor="silver">Sub Total &nbsp;</td>
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
	 <tr><td align="center" colspan="10"><HR></td></tr>
<?php
		}
	}
	if ($showcmemo =="t") {
		// for Credit Memo
		$cmemo_amt_sum = 0;
		$cmemo_freight_amt_sum = 0;
		$cmemo_tax_amt_sum = 0;
		for ($i=0; $i<$c_num; $i++) {	
			$cust_arr = $cu->getCusts($cu_cmemo[$i]["cmemo_cust_code"]);
			$amt = $cu_cmemo[$i]["cmemo_amt"]+$cu_cmemo[$i]["cmemo_tax_amt"]+$cu_cmemo[$i]["cmemo_freight_amt"];
			$x++;
			if ($showline == "t") {
				echo "<tr bgcolor=\"#EEEEEE\">";
			} else {
				if ($i%2 == 1) echo "<tr>"; 
				else echo "<tr bgcolor=\"#EEEEEE\">";
			}
?>
       <td align="center"> 
         <?= $x ?>
       </td>
       <td align="center"> 
         <?= $cu_cmemo[$i]["cmemo_id"] ?>
       </td>
       <td align="left"> 
         Credit
       </td>
       <td align="left"> 
         <?= $cu_cmemo[$i]["cmemo_cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $cust_arr["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $cust_arr["cust_state"] ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_cmemo[$i]["cmemo_amt"]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_cmemo[$i]["cmemo_freight_amt"]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_cmemo[$i]["cmemo_tax_amt"]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($amt*-1, 2, ".", ",") ?>
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
       <td align="left" colspan="7"> 
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
		if ($c_num>0) {
			$cmemo_amt_total += $cmemo_amt_sum;
			$cmemo_freight_amt_total += $cmemo_freight_amt_sum;
			$cmemo_tax_amt_total += $cmemo_tax_amt_sum;
?>
	 <tr>
	   <td align="right" colspan="6" bgcolor="silver">Sub Total &nbsp;</td>
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
	 <tr><td align="center" colspan="10"><HR></td></tr>
<?php
		}
	}
	if ($showrcpt =="t") {
		// for cash receipt
		$rcpt_amt_sum = 0;
		$rcpt_disc_amt_sum = 0;
		for ($i=0; $i<$r_num; $i++) {	
			$cust_arr = $cu->getCusts($cu_rcpt[$i]["rcpt_cust_code"]);
			$amt = $cu_rcpt[$i]["rcpt_amt"];
			$x++;
			if ($showline == "t") {
				echo "<tr bgcolor=\"#EEEEEE\">";
			} else {
				if ($i%2 == 1) echo "<tr>"; 
				else echo "<tr bgcolor=\"#EEEEEE\">";
			}
?>
       <td align="center"> 
         <?= $x ?>
       </td>
       <td align="center"> 
         <?= $cu_rcpt[$i]["rcpt_id"] ?>
       </td>
       <td align="left"> 
         Receipt
       </td>
       <td align="left"> 
         <?= $cu_rcpt[$i]["rcpt_cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $cust_arr["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $cust_arr["cust_state"] ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_rcpt[$i]["rcpt_amt"]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($cu_rcpt[$i]["rcpt_disc_amt"]*-1, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= $cu_rcpt[$i]["rcpt_check_no"] ?>(<?= $cu_rcpt[$i]["rcpt_type"] ?>)
       </td>
       <td align="right"> 
         <?= number_format($amt*-1, 2, ".", ",") ?>
       </td>
     </tr>
<?php
			if ($showline=="t") {
?>
     <tr bgcolor="#FFFFFF">
       <td align="center"> 
         &nbsp;
       </td>
       <td align="center"> 
         &nbsp;
       </td>
       <td align="left" colspan="8"> 
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
			$rcpt_amt_sum += $cu_rcpt[$i]["rcpt_amt"];
			$rcpt_disc_amt_sum += $cu_rcpt[$i]["rcpt_disc_amt"];
		}
		if ($r_num > 0) {
			$rcpt_amt_total += $rcpt_amt_sum;
			$rcpt_disc_amt_total += $rcpt_disc_amt_sum;
?>
	 <tr>
	   <td align="right" colspan="6" bgcolor="silver">Sub Total &nbsp;</td>
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
	 <tr><td align="center" colspan="10"><font color="red">Empty!</font></td></tr>
<?php
		}
	}
?>
	</table></td>
   </tr>
   <tr><td>&nbsp;</td></tr>
<?php
}
?>
	<tr><td colspan="1">&nbsp;</td><tr>
	<tr><td colspan="1">
		<TABLE border="0" cellspacing="1" bgcolor="gray">
		<TR>
			<TD bgcolor="silver" width="200">&nbsp;</TD>
			<TD bgcolor="white" width="120" align="center">Sub Total</TD>
			<TD bgcolor="white" width="120" align="center">Freight/Disc. Total</TD>
			<TD bgcolor="white" width="120" align="center">Tax Total</TD>
		</TR>
<?php
if ($showpick=="t") {
?>
		<TR>
			<TD bgcolor="silver"><B>Sales</B></TD>
			<TD bgcolor="white" align="right"><?= number_format($pick_amt_total, 2, ".", ",") ?></TD>
			<TD bgcolor="white" align="right"><?= number_format($pick_freight_amt_total, 2, ".", ",") ?></TD>
			<TD bgcolor="white" align="right"><?= number_format($pick_tax_amt_total, 2, ".", ",") ?></TD>
		</TR>
<?php
}
if ($_POST["show_cmemo"]=="t") {
?>
		<TR>
			<TD bgcolor="silver"><B>Credit Memo</B></TD>
			<TD bgcolor="white" align="right"><?= number_format($cmemo_amt_total*-1, 2, ".", ",") ?></TD>
			<TD bgcolor="white" align="right"><?= number_format($cmemo_freight_amt_total*-1, 2, ".", ",") ?></TD>
			<TD bgcolor="white" align="right"><?= number_format($cmemo_tax_amt_total*-1, 2, ".", ",") ?></TD>
		</TR>
<?php
}
if ($_POST["show_rcpt"]=="t") {
?>
		<TR>
			<TD bgcolor="silver"><B>Receipt</B></TD>
			<TD bgcolor="white" align="right"><?= number_format($rcpt_amt_total*-1, 2, ".", ",") ?></TD>
			<TD bgcolor="white" align="right"><?= number_format($rcpt_disc_amt_total*-1, 2, ".", ",") ?></TD>
			<TD bgcolor="white" align="right">&nbsp;</TD>
		</TR>
<?php
}
?>
	</TABLE>
	</td></tr>
 </table>
