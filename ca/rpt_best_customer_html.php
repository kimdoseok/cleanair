<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.receipt.php");
	include_once("class/class.cmemo.php");

	include_once("class/register_globals.php");

	$dx = new Datex();
	$cu = new Custs();
	$pk = new Picks();
	$cm = new Cmemo();
	$rc = new Receipt();

	if ($sortby=="sale") { // sales
		$arr = $pk->getPicksSumBest($dx->toIsoDate($start_date), $dx->toIsoDate($end_date), $sort, $num_disp);
	} else if ($sortby=="rcpt") { // cash receipt
		$arr = $rc->getReceiptSumBest($dx->toIsoDate($start_date), $dx->toIsoDate($end_date), $sort, $num_disp);
	} else if ($sortby=="cmemo") { // credit memo
		$arr = $cm->getCmemoSumBest($dx->toIsoDate($start_date), $dx->toIsoDate($end_date), $sort, $num_disp);
	} else if ($sortby =="balance") { // balance

	} else { // 

	}
	if ($arr) $num = count($arr);
	else $num = 0;
?>
<HTML>
<HEAD>
<TITLE>Best Customer</TITLE>
</HEAD>
<BODY>
   <table width="100%" border="0" cellspacing="1" cellpadding="0">
	  <tr> 
		<td align="center"><strong><FONT SIZE="5" COLOR="red"><?= ($sort=="t")?"Best":"Worst" ?> Customer Report</strong></FONT></td>
	  </tr>
	  <tr>
		<td align="center">
		  <font size="3">
		  <b>
<?php
	if ($sortby=="sale") echo "Invoices";
	else if ($sortby=="rcpt") echo "Cash Receipts";
	else if ($sortby=="cmemo") echo "Credit Memos";
?>
		  </b> : 
			Date from "<?= (empty($start_date))?"First":$start_date ?>" to "<?= (empty($end_date))?"Last":$end_date ?>"
		  </font> 
		</td>
	  </tr>
      <tr align="right"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th bgcolor="gray" width="3%"><font color="white">#</font></th>
              <th bgcolor="gray" width="8%"><font color="white">Code</font></th>
              <th bgcolor="gray" width="35%"><font color="white">Name</font></th>
              <th bgcolor="gray" width="16%"><font color="white">City</font></th>
              <th bgcolor="gray" width="2%"><font color="white">State</font></th>
<?php
	if ($sortby=="sale" or $sortby=="cmemo") {
?>
              <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
              <th bgcolor="gray" width="8%"><font color="white">Freight</font></th>
              <th bgcolor="gray" width="8%"><font color="white">Tax</font></th>
<?php
	} else if ($sortby=="rcpt") {
?>
              <th bgcolor="gray" width="14%"><font color="white">Amount</font></th>
              <th bgcolor="gray" width="12%"><font color="white">Discount</font></th>
<?php
	}
?>
            </tr>
<?php
	$x = 0;
	for ($i=0; $i<$num; $i++) {	
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td align="center"> 
         <?= $i+1 ?>
       </td>
       <td align="left"> 
         <?= $arr[$i]["cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $arr[$i]["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $arr[$i]["cust_city"] ?>
       </td>
       <td align="left"> 
         <?= $arr[$i]["cust_state"] ?>
       </td>
<?php
	if ($sortby=="sale" or $sortby=="cmemo") {
?>
       <td align="right"> 
         <?= $arr[$i]["total_pick"] ?>
       </td>
       <td align="right"> 
         <?= $arr[$i]["total_freight"] ?>
       </td>
       <td align="right"> 
         <?= $arr[$i]["total_tax"] ?>
       </td>
<?php
	} else if ($sortby=="rcpt") {
?>
       <td align="right"> 
         <?= $arr[$i]["total_rcpt"] ?>
       </td>
       <td align="right"> 
         <?= $arr[$i]["total_disc"] ?>
       </td>
<?php
	} else if ($sortby=="cmemo") {
?>
       <td align="right"> 
         <?= $arr[$i]["total_cmemo"] ?>
       </td>
       <td align="right"> 
         <?= $arr[$i]["total_freight"] ?>
       </td>
       <td align="right"> 
         <?= $arr[$i]["total_tax"] ?>
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