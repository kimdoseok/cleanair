<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/register_globals.php");


	$dx = new Datex();
	$cu = new Custs();

	$cu_arr = $cu->getCustsReports($start_cust,$end_cust,$start_zip,$end_zip,$start_tel,$end_tel,$start_points,$end_points,$sby);
	$cu_num = count($cu_arr);
	
?>
<HTML>
<HEAD>
<TITLE>Customer Report</TITLE>
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
   <table width="100%" border="0" cellspacing="1" cellpadding="0" align="center">
	  <tr> 
		<td align="center"><strong><FONT SIZE="5" COLOR="red">Customer Report</strong></FONT></td>
	  </tr>
	  <tr>
		<td align="center">
		  <font size="3">
			Customer From : <?= (empty($start_cust))?"First":$start_cust ?> To : <?= (empty($end_cust))?"Last":$end_cust ?><br>
		    Zip From : <?= (empty($start_zip))?"First":$start_zip ?> To : <?= (empty($end_zip))?"Last":$end_zip ?><br>
			Tel From : <?= (empty($start_tel))?"First":$start_tel ?> To : <?= (empty($end_tel))?"Last":$end_zip ?><br>
			Point From : <?= (empty($start_points))?"First":$start_points ?> To : <?= (empty($end_points))?"Last":$end_points ?>
		  </font> 
		</td>
	  </tr>
      <tr align="center"> 
        <td><table width="1024" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th bgcolor="gray" width="10%"><font color="white">Cust.</font></th>
              <th bgcolor="gray" width="20%"><font color="white">Name</font></th>
              <th bgcolor="gray" width="15%"><font color="white">Addr1</font></th>
              <th bgcolor="gray" width="10%"><font color="white">City</font></th>
              <th bgcolor="gray" width="2%"><font color="white">State</font></th>
              <th bgcolor="gray" width="5%"><font color="white">Zip</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Phone</font></th>  
              <th bgcolor="gray" width="10%"><font color="white">Email</font></th>  
              <th bgcolor="gray" width="3%"><font color="white">LastAmt</font></th>
              <th bgcolor="gray" width="2%"><font color="white">Aging</font></th>
            </tr>
<?php
	$x = 0;
	// for Picking Tickiet
	for ($i=0; $i<$cu_num; $i++) {	
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td align="left"> 
         <?= $cu_arr[$i]["cust_code"] ?>
       </td>
       <td align="left"> 
         <?= stripslashes($cu_arr[$i]["cust_name"]) ?>
       </td>
       <td align="left"> 
         <?= $cu_arr[$i]["cust_addr1"] ?>
       </td>
       <td align="left"> 
         <?= $cu_arr[$i]["cust_city"] ?>
       </td>
       <td align="left"> 
         <?= $cu_arr[$i]["cust_state"] ?>
       </td>
       <td align="left"> 
         <?= $cu_arr[$i]["cust_zip"] ?>
       </td>
       <td align="left"> 
         <?= $cu_arr[$i]["cust_tel"] ?>
       </td>
       <td align="left"> 
         <?= $cu_arr[$i]["cust_email"] ?>
       </td>
       <td align="right"> 
         <?= $cu_arr[$i]["pick_amt"] ?>
       </td>
       <td align="right"> 
         <?= $cu_arr[$i][aging] ?>
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
