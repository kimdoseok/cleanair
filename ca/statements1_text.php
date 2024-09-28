<?php
	include_once("class/register_globals.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Print Statement</TITLE>
</HEAD>
<BODY>
<pre>
<?php
	$s = new Picks();
	$recs = $s->getPicks($pick_id);
	$s->increasePicks($pick_id, "pick_print", 1);
	$c = new Custs();
	$cust_arr = $c->getCusts($recs["pick_cust_code"]);
	$sd = new PickDtls();
	$sd_arr = $sd->getPickDtlsList($pick_id);

	$cols = 80;
	$page = array();
	$page_len = 42;
	$line_len = 12;
	
	$sd_num = count($sd_arr);
	$page_num = ceil($sd_num/$page_len);

	for ($j=0;$j<$page_num;$j++) {
		$page[$j] = "";
		$page[$j] .= "CLEANAIR SUPPLY, INC.";
		$page[$j] .= "\n";
		$page[$j] .= "1301 E. Linden Ave., Linden, NJ 07036\n";
		$page[$j] .= "Tel:1-800-435-0581(NY/NJ) 908-925-7722\n";
		$page[$j] .= "Fax: 908-925-5535\n";
		$page[$j] .= str_repeat(" ", 54);
		$page[$j] .= "Picking Ticket\n";
		$page[$j] .= str_repeat(" ", 54);
		$page[$j] .= str_pad(date("m/d/y"), 10, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($pick_id, 8, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($j+1, 2, " ", STR_PAD_LEFT);
		$page[$j] .= "\n";
		for ($i=0;$i<4;$i++) $page[$j] .= "\n";
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad(substr($cust_arr["cust_name"],0,30), 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad(substr($recs["pick_name"],0,30), 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n";
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad(substr($cust_arr["cust_addr1"],0,30), 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad(substr($recs["pick_addr1"],0,30), 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n";

		if (empty($cust_arr["cust_addr2"]) && empty($recs["pick_addr2"])) {
			$blank_addr2 = 1;
		} else {
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad(substr($cust_arr["cust_addr2"],0,30), 30, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad(substr($recs["pick_addr2"],0,30), 30, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n";
			$blank_addr2 = 0;
		}
		$blank_addr3 = 0;
		if (empty($cust_arr["cust_addr3"]) && empty($recs["pick_addr3"])) {
			$blank_addr3 = 1;
		} else {
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad(substr($cust_arr["cust_addr3"],0,30), 30, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad(substr($recs["pick_addr3"],0,30), 30, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n";
			$blank_addr3 = 0;
		}
		$page[$j] .= str_repeat(" ", 6);
		$csz = substr($cust_arr["cust_city"],0,17).", ".$cust_arr["cust_state"]." ".$cust_arr["cust_zip"];
		$page[$j] .= str_pad($csz, 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 6);
		$csz = substr($recs["pick_city"],0,17).", ".$recs["pick_state"]." ".$recs["pick_zip"];
		$page[$j] .= str_pad($csz, 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n";
		for ($i=0;$i<3;$i++) $page[$j] .= "\n";
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad(date("m/d/y", strtotime($recs["pick_date"])), 10, " ", STR_PAD_CENTER);
		$page[$j] .= str_pad($recs["pick_cust_code"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= str_pad($recs["pick_user_code"], 10, " ", STR_PAD_CENTER);
		$page[$j] .= str_pad($recs["pick_shipvia"], 16, " ", STR_PAD_CENTER);
		$page[$j] .= "\n";
		if ($blank_addr2==1) $page[$j] .= "\n";
		if ($blank_addr3==1) $page[$j] .= "\n";

		for ($i=0;$i<2;$i++) $page[$j] .= "\n";

		$start = $j*$line_len;
		if ($j+1 == $page_num) $end = count($sd_arr);
		else $end = ($j+1)*$line_len;
		for ($k=$start;$k<$end;$k++) {
			$page[$j] .= str_pad($sd_arr[$k]["slsdtl_item_code"], 15, " ", STR_PAD_RIGHT);
			$page[$j] .= str_pad(substr($sd_arr[$k]["slsdtl_item_desc"],0,34), 34, " ", STR_PAD_RIGHT);
			$page[$j] .= str_pad($sd_arr[$k]["slsdtl_qty"]+0, 8, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($sd_arr[$k]["slsdtl_cost"]), 10, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($sd_arr[$k]["slsdtl_qty"]*$sd_arr[$k]["slsdtl_cost"]), 12, " ", STR_PAD_LEFT);
 			if ($sd_arr[$k]["slsdtl_taxable"]=="t") $page[$j] .= "T";
			$page[$j] .= "\n";
		}	
		if ($j+1 == $page_num) {
			$res = $line_len - ($sd_num % $line_len);
			for ($k=0; $k<$res; $k++) $page[$j] .= "\n";
			for ($i=0;$i<3;$i++) $page[$j] .= "\n";
			$dx = new Datex();
			$day0 = date("Y-m-d");
			$day30 = $dx->getIsoDate($day0,30,"b");
			$day60 = $dx->getIsoDate($day0,60,"b");
			$day90 = $dx->getIsoDate($day0,90,"b");
//echo "$day0 $day30 $day60 $day90 <br>";
			$cr = new Receipt();
			$pt = new Picks();
			$cm = new Cmemo();

			$crmemo_total = $cm->getCmemoSumAged($recs["sale_cust_code"], "", "", "t", "f");
			$rcpt_total = $cr->getReceiptSumAged($recs["pick_cust_code"], "", "", "t", "f");
			$pick90over = $pt->getPicksSumAged($recs["pick_cust_code"], "", $day90, "t", "f");
			$pick90 = $pt->getPicksSumAged($recs["pick_cust_code"], $day90, $day60, "t", "f");
			$pick60 = $pt->getPicksSumAged($recs["pick_cust_code"], $day60, $day30, "t", "f");
			$pick30 = $pt->getPicksSumAged($recs["pick_cust_code"], $day30, $day0, "t", "t");

			if (strtotime($cust_arr["cust_created"]) <= strtotime($day0) && strtotime($cust_arr["cust_created"]) > strtotime($day30) ) $pick30 += $cust_arr["cust_init_bal"];
			else if (strtotime($cust_arr["cust_created"]) <= strtotime($day30) && strtotime($cust_arr["cust_created"]) > strtotime($day60) ) $pick60 += $cust_arr["cust_init_bal"];
			if (strtotime($cust_arr["cust_created"]) <= strtotime($day60) && strtotime($cust_arr["cust_created"]) > strtotime($day90) ) $pick90 += $cust_arr["cust_init_bal"];
			if (strtotime($cust_arr["cust_created"]) <= strtotime($day90)) $pick90over += $cust_arr["cust_init_bal"];

			$balance = $pick90over + $pick90 + $pick60 + $pick30 - $rcpt_total - $crmemo_total;

			$bal30 = $balance - $pick90over - $pick90 - $pick60 ;
			if ($bal30 < 0) $bal30 = 0;
			$bal60 = $balance - $pick90over - $pick90 - $pick30 ;
			if ($bal60 < 0) $bal60 = 0;
			$bal90 = $balance - $pick90over - $pick60 - $pick30 ;
			if ($bal90 < 0) $bal90 = 0;
			$bal90over = $balance - $pick90 - $pick60 - $pick30 ;
			if ($bal90over < 0) $bal90over = 0;

			$page[$j] .= str_pad(number_format($bal30,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($bal60,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($bal90,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($bal90over,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($balance,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($recs["pick_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);

			$page[$j] .= "\n";
			$page[$j] .= "\n";
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad("Freight : ", 10, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($recs["pick_freight_amt"], 2, ".", ","), 13, " ", STR_PAD_RIGHT);
			$page[$j] .= str_pad("Tax : ", 10, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($recs["pick_tax_amt"], 2, ".", ","), 13, " ", STR_PAD_RIGHT);
			$page[$j] .= str_pad("Total : ", 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($recs["pick_amt"] + $recs["pick_freight_amt"] + $recs["pick_tax_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n";

		} else {
			for ($i=0;$i<9;$i++) $page[$j] .= "\n";
		}
		$page[$j] .= "\n";
		$page[$j] .= "\n";
		$page[$j] .= "\n";
	}
	$out = "";
	for ($i=0;$i<$page_num;$i++) {
		$out .= $page[$i];
	}
	echo $out;
?>
</pre>
</BODY>
</HTML>
<!--
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr bgcolor="white"> 
          <td width="64%"><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td width="36%"><font size="6" face="Arial, Helvetica, sans-serif"><strong>Statement</strong></font></td>
        </tr>
        <tr bgcolor="white"> 
          <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font face="Arial, Helvetica, sans-serif">Date : </font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="19" bgcolor="white"><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="black">
        <tr align="center" bgcolor="silver"> 
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Bal. 
            Forward</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
            Sales</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
            Paid</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
            Credit</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Balance</font></strong></td>
        </tr>
        <tr bgcolor="white"> 
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td bgcolor="white"><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="black">
        <tr align="center" bgcolor="silver"> 
          <td width="9%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Ref#</font></strong></td>
          <td width="10%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Date</font></strong></td>
          <td width="8%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Item</font></strong></td>
          <td width="32%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Description</font></strong></td>
          <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Qty</font></strong></td>
          <td width="9%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Price</font></strong></td>
          <td width="7%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Tax</font></strong></td>
          <td width="18%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Amount</font></strong></td>
        </tr>
        <tr bgcolor="white"> 
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td bgcolor="white"><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="black">
        <tr align="center" bgcolor="silver"> 
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Current</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">1-30 
            Due</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">31-60 
            Due</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">61-90 
            Due</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Over 
            90 Due</font></strong></td>
          <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Amount 
            Due</font></strong></td>
        </tr>
        <tr bgcolor="white"> 
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          <td>&nbsp;</td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
-->