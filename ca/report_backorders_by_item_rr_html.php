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

	$dtl_arr = $pd->getPickDtlRangeVendor($start_date, $end_date, $start_item, $end_item, $start_vend, $end_vend, "s");
	if ($dtl_arr) $dtl_num = count($dtl_arr);
	else $dtl_num = 0;

?>
<HTML>
<HEAD>
<TITLE>Sales by Item Report - RR</TITLE>
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
		<td align="center"><strong><FONT SIZE="5" COLOR="red">Sales by Item Report(RR)</strong></FONT></td>
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
              <th bgcolor="gray" width="3%"><font color="white">#</font></th>
              <th bgcolor="gray" width="12%"><font color="white">Phone</font></th>
              <th bgcolor="gray" width="18%"><font color="white">Customer</font></th>
              <th bgcolor="gray" width="20%"><font color="white">Street</font></th>
			  <th bgcolor="gray" width="10%"><font color="white">City</font></th>
              <th bgcolor="gray" width="3%"><font color="white">State</font></th>
              <th bgcolor="gray" width="5%"><font color="white">Zip</font></th>
              <th bgcolor="gray" width="8%"><font color="white">ShipDate</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Prod.Code</font></th>
              <th bgcolor="gray" width="7%"><font color="white">ShipQty</font></th>
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
         <?= $dtl_arr[$i]["pick_tel"] ?>
       </td>

       <td align="left"> 
         <?= $dtl_arr[$i]["pick_name"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["pick_addr1"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["pick_city"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["pick_state"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["pick_zip"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["pick_date"] ?>
       </td>
       <td align="left"> 
         <?= $dtl_arr[$i]["item_vend_prod_code"] ?>
       </td>
       <td align="right"> 
         <?= $dtl_arr[$i]["pickdtl_qty"]+0 ?>
       </td>
	 </tr>
<?php
	}
?>
	</table></td>
   </tr>
 </table>
</BODY>
</HTML>