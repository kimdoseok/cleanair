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
	$f = new FormUtil();

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
  <tr align="right"> 
    <td colspan="8"><strong>Daily Report Detail</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100" border="0" cellspacing="1" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr>
          <td align="right">From </td>
          <td> 
			<?= $f->fillTextBox("fr",$fr,10) ?>
		  </td>
          <td align="right">To </td>
          <td> 
			<?= $f->fillTextBox("to",$to,10) ?>
		  </td>
		  <td>
			<input type="button" name="ft" value="Filter" onClick="setFilter()">
		  </td>
		  <td>
			<?= $f->fillHidden("ty", $ty) ?>
          </td>
        </tr>
		</form>
      </table></td>
      </tr>
<?php
	$fr_t = strtotime($fr);
	$to_t = strtotime($to);
	$days = ($to_t - $fr_t)/86400 + 1;
	$time_arr = getdate($fr_t);

	for ($k=0;$k<$days;$k++) {
		$ts = mktime(0,0,0,$time_arr["mon"],$time_arr["mday"]+$k,$time_arr["year"]);
		$idate = date("Y-m-d", $ts);

		$cu_picks = $pi->getPicksRange($idate, $idate, "pick_date");
		if ($cu_picks) $p_num = count($cu_picks);
		else $p_num =0;
		$cu_cmemo = $cm->getCmemoRange($idate, $idate, "cmemo_date");
		if ($cu_cmemo) $c_num = count($cu_cmemo);
		else $c_num =0;
		$cu_rcpt = $re->getReceiptRange($idate, $idate, "rcpt_date");
		if ($cu_rcpt) $r_num = count($cu_rcpt);
		else $r_num =0;

?>
 	  <tr><td align="left" bgcolor="black"><font face="Helvetica" color="white"><b>&nbsp; <?= date("m/d/Y", $ts) ?></b></font></td></tr>
      <tr align="right"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th bgcolor="gray" width="10%"><font color="white">#</font></th>
              <th bgcolor="gray" width="1%"><font color="white">Type</font></th>
              <th bgcolor="gray" colspan="2" width="10%"><font color="white">Customer</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
            </tr>
<?php
		$x = 0;
		// for Picking Tickiet
		for ($i=0; $i<$p_num; $i++) {	
			$cust_arr = $cu->getCusts($cu_picks[$i]["pick_cust_code"]);
			$amt = $cu_picks[$i]["pick_amt"]+$cu_picks[$i]["pick_tax_amt"]+$cu_picks[$i]["pick_freight_amt"];
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
         <?= $cu_picks[$i]["pick_cust_code"] ?>
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
		if ($x>0) {
?>
	 <tr><td align="center" colspan="5"><HR></td></tr>
<?php
		}
		// for Credit Memo
		for ($i=0; $i<$c_num; $i++) {	
			$cust_arr = $cu->getCusts($cu_cmemo[$i]["cmemo_cust_code"]);
			$amt = $cu_cmemo[$i]["cmemo_amt"]+$cu_cmemo[$i]["cmemo_tax_amt"]+$cu_cmemo[$i]["cmemo_freight_amt"];
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
         <?= $cu_cmemo[$i]["cmemo_cust_code"] ?>
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
		if ($x>0) {
?>
	 <tr><td align="center" colspan="5"><HR></td></tr>
<?php
		}
		// for cash receipt
		for ($i=0; $i<$r_num; $i++) {	
			$cust_arr = $cu->getCusts($cu_rcpt[$i]["rcpt_cust_code"]);
			$amt = $cu_rcpt[$i]["rcpt_amt"];
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
         <?= $cu_rcpt[$i]["rcpt_cust_code"] ?>
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
   <tr><td>&nbsp;</td></tr>
<?php
	}
?>
 </table>
