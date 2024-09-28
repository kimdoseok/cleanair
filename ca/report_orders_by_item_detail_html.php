<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.sales.php");
	include_once("class/class.saledtls.php");
	include_once("class/register_globals.php");

	$dx = new Datex();
	$cu = new Custs();
	$sh = new Sales();
	$sl = new SaleDtls();

	if (empty($start_date)) $start_date = date("m/d/Y");
	if (empty($end_date)) $end_date = date("m/d/Y");
	$start_date = $dx->toIsoDate($start_date);
	$end_date = $dx->toIsoDate($end_date);
	$dtl_arr = $sl->getSaleDtlsRange($start_date, $end_date, $start_item, $end_item, "d");
	if ($dtl_arr) $dtl_num = count($dtl_arr);
	else $dtl_num = 0;


?>
<HTML>
<HEAD>
<TITLE>Orders by Item Report - Detail</TITLE>
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
		<td align="center"><strong><FONT SIZE="5" COLOR="red">Orders by Item Report(Detail)</strong></FONT></td>
	  </tr>
	  <tr>
		<td align="center">
		  <font size="3">
		    Date From : <?= (empty($start_date))?"Beginning":$start_date ?> To : <?= (empty($end_date))?"Today":$end_date ?><br>
			Item From : <?= (empty($start_item))?"First":$start_item ?> To : <?= (empty($end_item))?"Last":$end_item ?>
		  </font> 
		</td>
	  </tr>
      <tr align="right"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th bgcolor="gray" align="center" width="3%"><font color="white">#</font></th>
              <th bgcolor="gray" align="center" width="8%"><font color="white">Item #</font></th>
              <th bgcolor="gray" align="center" width="25%"><font color="white">Item Desc</font></th>
              <th bgcolor="gray" align="center" width="5%"><font color="white">Inv #</font></th>
              <th bgcolor="gray" align="center" width="5%"><font color="white">Date</font></th>
              <th bgcolor="gray" align="center" width="5%"><font color="white">Cust.#</font></th>
              <th bgcolor="gray" align="center" width="10%"><font color="white">Cust.Name</font></th>
              <th bgcolor="gray" align="center" width="10%"><font color="white">Address</font></th>
              <th bgcolor="gray" align="center" width="9%"><font color="white">City</font></th>
              <th bgcolor="gray" align="center" width="1%"><font color="white">State</font></th>
              <th bgcolor="gray" align="center" width="5%"><font color="white">Zip</font></th>
              <th bgcolor="gray" align="center" width="3%"><font color="white">Qty</font></th>
<?php
	if ($show_format == "wa") {
?>
			  <th bgcolor="gray" align="center" width="7%"><font color="white">Price</font></th>
              <th bgcolor="gray" align="center" width="7%"><font color="white">Amount</font></th>
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
         <?= $dtl_arr[$i]["slsdtl_item_code"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["slsdtl_item_desc"] ?>
       </td>
       <td align="center"> 
         <?= $dtl_arr[$i]["sale_id"] ?>
       </td>
       <td align="center"> 
         <?= $dx->toUsaDate($dtl_arr[$i]["sale_date"]) ?>
       </td>
       <td align="center"> 
         <?= $dtl_arr[$i]["cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["cust_name"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["cust_addr1"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["cust_city"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["cust_state"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["cust_zip"] ?>
       </td>
       <td align="right"> 
         <?= $dtl_arr[$i]["slsdtl_qty"]+0 ?>
       </td>
<?php
	if ($show_format == "wa") {
?>
       <td align="right"> 
         <?= number_format($dtl_arr[$i]["slsdtl_cost"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($dtl_arr[$i]["slsdtl_cost"]*$dtl_arr[$i]["slsdtl_qty"], 2, ".", ",") ?>
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