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
	
	$parr = $pi->getPicksRangeReps($start_date, $end_date, $start_rep, $end_rep, "f");
	$pnum = count($parr);

?>
<HTML>
<HEAD>
<TITLE>Sales by Sales Rep. Report - Detail</TITLE>
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
   <table width="900" border="0" cellspacing="1" cellpadding="0" align="center">
	  <tr> 
		<td align="center"><strong><FONT SIZE="5" COLOR="red">Sales by Sales Rep. Report(Detail)</strong></FONT></td>
	  </tr>
	  <tr>
		<td align="center">
		  <font size="3">
		    Date From : <?= (empty($start_date))?"Beginning":$dx->toUsaDate($start_date) ?>, To : <?= (empty($end_date))?"Today":$dx->toUsaDate($end_date) ?><br>
			Sales Rep. From : <?= (empty($start_rep))?"First":$start_rep ?>, To : <?= (empty($end_rep))?"Last":$end_rep ?>
		  </font> 
		</td>
	  </tr>
      <tr align="right"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0" align="center">
            <tr> 
              <th bgcolor="gray" align="center" width="3%"><font color="white">#</font></th>
              <th bgcolor="gray" align="center" width="8%"><font color="white">Rep.#</font></th>
              <th bgcolor="gray" align="center" width="15%"><font color="white">rep.Name</font></th>
              <th bgcolor="gray" align="center" width="7%"><font color="white">Inv #</font></th>
              <th bgcolor="gray" align="center" width="9%"><font color="white">Date</font></th>
              <th bgcolor="gray" align="center" width="8%"><font color="white">Cust. #</font></th>
              <th bgcolor="gray" align="center" width="20%"><font color="white">Cust.Name</font></th>
              <th bgcolor="gray" align="center" width="8%"><font color="white">Amount</font></th>
			  <th bgcolor="gray" align="center" width="7%"><font color="white">Tax</font></th>
              <th bgcolor="gray" align="center" width="7%"><font color="white">Freight</font></th>
              <th bgcolor="gray" align="center" width="8%"><font color="white">Inv.Amt</font></th>
            </tr>
<?php
	$amtotal = 0;
	$taxtotal = 0;
	$freightotal = 0;

	// for Picking Tickiet
	for ($i=0; $i<$pnum; $i++) {	
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td align="center"> 
         <?= $i+1 ?>
       </td>
       <td align="left"> 
         <?= $parr[$i]["slsrep_code"] ?>
       </td>
       <td align="left"> 
         <?= $parr[$i]["slsrep_name"] ?>
       </td>
       <td align="left"> 
         <?= $parr[$i]["pick_id"] ?>
       </td>
       <td align="left"> 
         <?= $parr[$i]["pick_date"] ?>
       </td>
       <td align="left"> 
         <?= $parr[$i]["cust_code"] ?>
       </td>
       <td align="left"> 
         <?= $parr[$i]["cust_name"] ?>
       </td>
       <td align="right"> 
         <?= number_format($parr[$i]["pick_amt"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($parr[$i]["pick_tax_amt"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($parr[$i]["pick_freight_amt"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($parr[$i]["pick_amt"]+$parr[$i]["pick_tax_amt"]+$parr[$i]["pick_freight_amt"], 2, ".", ",") ?>
       </td>
     </tr>
<?php
		$amtotal += $parr[$i]["pick_amt"];
		$taxtotal += $parr[$i]["pick_tax_amt"];
		$freightotal += $parr[$i]["pick_freight_amt"];
	}
?>
	 <tr bgcolor="#CCCCCC">
       <td align="right" colspan="7">
         Total : 
       </td>
       <td align="right"> 
         <?= number_format($amtotal, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($taxtotal, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($freightotal, 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($amtotal+$taxtotal+$freightotal, 2, ".", ",") ?>
       </td>
     </tr>
	</table></td>
   </tr>
 </table>
</BODY>
</HTML>