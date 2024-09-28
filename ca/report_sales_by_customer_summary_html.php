<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.pickdtls.php");
	include_once("class/register_globals.php");

	$dx = new Datex();
	$cu = new Custs();
	$pi = new Picks();
	$pd = new PickDtls();

	if (empty($start_date)) $start_date = date("m/d/Y");
	if (empty($end_date)) $end_date = date("m/d/Y");
	$start_date = $dx->toIsoDate($start_date);
	$end_date = $dx->toIsoDate($end_date);
	$dtl_arr = $pd->getPickDtlsRangeCust($start_date, $end_date, $start_cust, $end_cust, "s",$sby);
	$dtl_num = count($dtl_arr);
?>
<HTML>
<HEAD>
<TITLE>Sales by Customer Report - Summary</TITLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setFilter() {
		var f = document.forms[0];
		f.action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.submit();
	}
//-->
</SCRIPT>
</HEAD>
<BODY>
   <table width="100%" border="0" cellspacing="1" cellpadding="0">
	  <tr> 
		<td align="center"><strong><FONT SIZE="5" COLOR="red">Sales by Customer Report(Summary)</strong></FONT></td>
	  </tr>
	  <tr>
		<td align="center">
		  <font size="3">
		    Date From : <?= (empty($start_date))?"Beginning":$start_date ?> To : <?= (empty($end_date))?"Today":$end_date ?><br>
			Customer From : <?= (empty($start_cust))?"First":$start_cust ?> To : <?= (empty($end_cust))?"Last":$end_cust ?>
		  </font> 
		</td>
	  </tr>
      <tr align="right"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th bgcolor="gray" width="5%"><font color="white">#</font></th>
              <th bgcolor="gray" width="8%"><font color="white">Cust.</font></th>
              <th bgcolor="gray" width="45%"><font color="white">Name</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Qty</font></th>
<?php
	if ($show_amount == "t") {
?>
			  <th bgcolor="gray" width="10%"><font color="white">Avg.Price</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
<?php
	}
?>
            </tr>
<?php
	$x = 0;
	// for Picking Tickiet
	for ($i=0; $i<$dtl_num; $i++) {	
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td align="center"> 
         <?= $i+1 ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["pick_cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["cust_name"] ?>
       </td>
       <td align="right"> 
         <?= $dtl_arr[$i]["qty"]+0 ?>
       </td>
<?php
	if ($show_amount == "t") {
?>
       <td align="right"> 
         <?= number_format($dtl_arr[$i]["prc"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($dtl_arr[$i]["amt"], 2, ".", ",") ?>
       </td>
<?php
	}
?>
     </tr>
<?php
	}
?>
	</table></td>
   </tr>
 </table>
</BODY>
</HTML>