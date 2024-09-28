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
$stm = $starr[1];
$std = $starr[2]; 
$enarr = explode("-", $ito);
$eny = $enarr[0];
$enm = $enarr[1];
$end = $enarr[2];

?>
<html>
<head>
</head>
<body>
<TABLE width="600">
  <tr align="center"> 
    <td colspan="4" align="center"><font size="4"><strong>Monthly Report</strong></font></td>
  </tr>
<?php
echo "<TR bgcolor='gray'>";
echo "<td align='center'>Period</td>";
echo "<td align='center'>Sales</td>";
echo "<td align='center'>Return</td>";
if ($show_receipt=='t') echo "<td align='center'>Receipt</td>";
echo "</TR>";

$trxarr = Array();
$total = Array('pick'=>0,'cmemo'=>0,'rcpt'=>0);
while(1) {
	$std = "$sty-$stm-1";
	$ym = "$stm/$sty";
	$stm += 1;
	if ($stm>12) {
		$stm = 1;
		$sty += 1;
	}
	$edd = "$sty-$stm-1";
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
  $tmparr = Array('period'=>$ym);
  $tmparr['pick']=$pick["amt"]+$pick["tax_amt"]+$pick["freight_amt"];
  $total['pick']+=$tmparr['pick'];
  
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
  $tmparr['cmemo']=$cmemo["amt"]+$cmemo["tax_amt"]+$cmemo["freight_amt"];
  $total['cmemo']+=$tmparr['cmemo'];

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
  $tmparr['rcpt']=$rcpt["amt"];
  $total['rcpt']+=$tmparr['rcpt'];
  array_push($trxarr,$tmparr);
	if ($sty>=$eny and $stm>=$enm) break;
}
for ($i=0;$i<count($trxarr);$i++) {
	echo "<TR>";
	echo "<td width='150' align='center'><b>".$trxarr[$i][period]."</b></td>";
	echo "<td width='150' align='right'>".number_format($trxarr[$i][pick],2,".",",")."</td>";
	echo "<td width='150' align='right'>".number_format($trxarr[$i][cmemo],2,".",",")."</td>";
	if ($show_receipt=='t') echo "<td width='150' align='right'>".number_format($trxarr[$i][rcpt],2,".",",")."</td>";
	echo "</TR>";
}
	echo "<TR bgcolor='silver'>";
	echo "<td width='150' align='center'><b>TOTAL</b></td>";
	echo "<td width='150' align='right'>".number_format($total[pick],2,".",",")."</td>";
	echo "<td width='150' align='right'>".number_format($total[cmemo],2,".",",")."</td>";
	if ($show_receipt=='t') echo "<td width='150' align='right'>".number_format($total[rcpt],2,".",",")."</td>";
	echo "</TR>";
  ?>
</TABLE>
</body>
</html>
