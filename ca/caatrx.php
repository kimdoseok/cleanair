<?php
	include_once("class/register_globals.php");

$dbuser = 'doseok';
$dbpass = '7795004';
$dbname = 'cad';
$conn = mysql_pconnect('192.168.1.3', $dbuser, $dbpass );
mysql_select_db($dbname,$conn);

$stm = 12;
$sty = 2006;

$state = "NJ";	
	
?>
<html>
<head>
</head>
<body>
<TABLE width="600">
	<TR bgcolor='gray'>
		<td>&nbsp;</td>
		<td align='center'>Sub Total</td>
		<td align='center'>Tax/Discount</td>
		<td align='center'>Freight</td>
	</TR>
<?php
while(1) {
	$std = "$sty-$stm-1";
	$ym = "$stm/$sty";
	$stm += 1;
	if ($stm>12) {
		$stm = 1;
		$sty += 1;
	}
	$edd = "$sty-$stm-1";
	if ($sty==2011 and $stm>1) break;
	$query = "SELECT sum(pick_amt) as amt, sum(pick_tax_amt) as tax_amt, sum(pick_freight_amt) as freight_amt ";
	$query .= "FROM picks p, custs c ";
	$query .= "WHERE p.pick_cust_code=c.cust_code AND c.cust_state='$state' ";
	$query .= "AND p.pick_date>= '$std' AND p.pick_date < '$edd' ";
	//echo $query."<br>";
	$res = mysql_query($query,$conn);
	$num = mysql_num_rows($res);
	$pick = mysql_fetch_assoc($res);
	echo "<TR><TD colspan='4' bgcolor='silver'><b> $ym </b></TD></TR>";
	echo "<TR>";
	echo "<td width='150'>Invoice</td>";
	echo "<td width='150' align='right'>".number_format($pick["amt"],2,".",",")."</td>";
	echo "<td width='150' align='right'>".number_format($pick["tax_amt"],2,".",",")."</td>";
	echo "<td width='150' align='right'>".number_format($pick["freight_amt"],2,".",",")."</td>";
	echo "</TR>";
	$query = "SELECT sum(cmemo_amt) as amt, sum(cmemo_tax_amt) as tax_amt, sum(cmemo_freight_amt) as freight_amt ";
	$query .= "FROM cmemos p, custs c ";
	$query .= "WHERE p.cmemo_cust_code=c.cust_code AND c.cust_state='$state' ";
	$query .= "AND p.cmemo_date>= '$std' AND p.cmemo_date < '$edd' ";
	$res = mysql_query($query,$conn);
	$num = mysql_num_rows($res);
	$cmemo = mysql_fetch_assoc($res);
	echo "<TR>";
	echo "<td>CR Memo</td>";
	echo "<td align='right'>".number_format($cmemo["amt"],2,".",",")."</td>";
	echo "<td align='right'>".number_format($cmemo["tax_amt"],2,".",",")."</td>";
	echo "<td align='right'>".number_format($cmemo["freight_amt"],2,".",",")."</td>";
	echo "</TR>";
	$query = "SELECT sum(rcpt_amt) as amt, sum(rcpt_disc_amt) as disc_amt ";
	$query .= "FROM rcpts p, custs c ";
	$query .= "WHERE p.rcpt_cust_code=c.cust_code AND c.cust_state='$state' ";
	$query .= "AND p.rcpt_date>= '$std' AND p.rcpt_date < '$edd' ";
	$res = mysql_query($query,$conn);
	$num = mysql_num_rows($res);
	$rcpt = mysql_fetch_assoc($res);
	echo "<TR>";
	echo "<td>Receipt</td>";
	echo "<td align='right'>".number_format($rcpt["amt"],2,".",",")."</td>";
	echo "<td align='right'>".number_format($rcpt["disc_amt"],2,".",",")."</td>";
	$pct = ($rcpt["amt"]+$rcpt["disc_amt"]) / ($pick["amt"]+$pick["tax_amt"]+$pick["freight_amt"]-$cmemo["freight_amt"]-$cmemo["tax_amt"]-$cmemo["amt"]) * 100;
	echo "<td align='right'>".sprintf("%0.1f",$pct)."%</td>";
	echo "</TR>";
}
?>
</TABLE>
</body>
</html>
