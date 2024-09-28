<?php
  include_once("class/class.dbconfig.php");
	include_once("class/class.datex.php");
	include_once("class/register_globals.php");

	$dbc = new DbConfig();
	$dx = new Datex();

	if (empty($fr)) $fr = date("m/d/Y");
	if (empty($to)) $to = date("m/d/Y");
	$ifr = $dx->toIsoDate($fr);
	$ito = $dx->toIsoDate($to);

$conn = mysql_pconnect($dbc->hostname, $dbc->username, $dbc->password );
mysql_select_db($dbc->dbname,$conn);

$starr = explode("-", $ifr);
$sty = $starr[0];
settype($sty, "integer");
$stm = $starr[1];
settype($stm, "integer");
$std = $starr[2]; 
settype($std, "integer");
$enarr = explode("-", $ito);
$eny = $enarr[0];
settype($eny, "integer");
$enm = $enarr[1];
settype($enm, "integer");
$end = $enarr[2];
settype($end, "integer");

?>
<html>
<head>
</head>
<body>
<TABLE width="600">
  <tr align="center"> 
    <td colspan="3" align="center"><font size="4"><strong>Monthly Report</strong></font></td>
  </tr>
	<TR bgcolor='gray'>
		<td>&nbsp;</td>
		<td align='center'>Sub Total</td>
		<td align='center'>Tax/Discount</td>
		<td align='center'>Freight</td>
	</TR>
<?php
$td = "$eny-$enm-1";
while(1) {
	$std = "$sty-$stm-1";
	$ym = "$stm/$sty";
	$stm += 1;
	if ($stm>12) {
		$stm = 1;
		$sty += 1;
	}
	$edd = "$sty-$stm-1";
  //if ($edd>$td) break;
	$query = "SELECT sum(pick_amt) as amt, sum(pick_tax_amt) as tax_amt, sum(pick_freight_amt) as freight_amt ";
	$query .= "FROM picks p, custs c ";
	$query .= "WHERE p.pick_cust_code=c.cust_code ";
  if ($state!='ALL') {
    if ($state=='OTHER') $query .= "AND c.cust_state NOT IN ('NY','NJ','CT') ";
    else $query .= "AND c.cust_state='$state' ";
  }
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
	$query .= "WHERE p.cmemo_cust_code=c.cust_code ";
  if ($state!='ALL') {
    if ($state=='OTHER') $query .= "AND c.cust_state NOT IN ('NY','NJ','CT') ";
    else $query .= "AND c.cust_state='$state' ";
  }
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
	$query .= "WHERE p.rcpt_cust_code=c.cust_code ";
  if ($state!='ALL') {
    if ($state=='OTHER') $query .= "AND c.cust_state NOT IN ('NY','NJ','CT') ";
    else $query .= "AND c.cust_state='$state' ";
  }
	$query .= "AND p.rcpt_date>= '$std' AND p.rcpt_date < '$edd' ";
	$res = mysql_query($query,$conn);
	$num = mysql_num_rows($res);
	$rcpt = mysql_fetch_assoc($res);
	echo "<TR>";
	echo "<td>Receipt</td>";
	echo "<td align='right'>".number_format($rcpt["amt"],2,".",",")."</td>";
	echo "<td align='right'>".number_format($rcpt["disc_amt"],2,".",",")."</td>";
  if ($pick["amt"]+$pick["tax_amt"]+$pick["freight_amt"]-$cmemo["freight_amt"]-$cmemo["tax_amt"]-$cmemo["amt"]!=0) $pct = ($rcpt["amt"]+$rcpt["disc_amt"]) / ($pick["amt"]+$pick["tax_amt"]+$pick["freight_amt"]-$cmemo["freight_amt"]-$cmemo["tax_amt"]-$cmemo["amt"]) * 100;
  else $pct = 0;
	echo "<td align='right'>".sprintf("%0.1f",$pct)."%</td>";
	echo "</TR>";
	if ($sty>=$eny and $stm>=$enm) break;
}
?>
</TABLE>
</body>
</html>
