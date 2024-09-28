<?php
$conn = odbc_pconnect("ca", "doseok", "7795004");
$query = "select * from sales where sale_date>='20050101' AND sale_date<='20051231' AND sale_slsrep='DAVID' order by sale_date ";
$query = "SELECT * FROM picks p, custs c WHERE p.pick_date>='20050101' AND p.pick_date<='20051231' AND c.cust_slsrep='DAVID' AND p.pick_cust_code=c.cust_code ORDER BY p.pick_date ";
$rs = odbc_exec($conn, $query);
echo "<table>";
echo "<tr bgcolor='silver'>";
echo "<td width='100'>#</td>";
echo "<td width='300'>Customer</td>";
echo "<td width='150'>Date</td>";
echo "<td width='100'>Amount</td>";
echo "<td width='100'>Freight</td>";
echo "<td width='100'>Tax</td>";
echo "<td width='100'>Total</td>";
echo "</tr>";
$i=0;
$subtotal = 0;
$taxtotal = 0;
$freighttotal = 0;
while($arr = odbc_fetch_row($rs)) {
	$pick_id = odbc_result($rs,"pick_id");
	$cust_code = odbc_result($rs,"cust_code");
	$cust_name = stripslashes(odbc_result($rs,"cust_name"));
	$pick_date = stripslashes(odbc_result($rs,"pick_date"));
	$amount = odbc_result($rs,"pick_amt");
	$tax = odbc_result($rs,"pick_tax_amt");
	$freight = odbc_result($rs,"pick_freight_amt");
	$total = $amount+$tax+$freight;
	$subtotal += $amount;
	$taxtotal += $tax;
	$freighttotal += $freight;
	echo "<tr>";
	echo "<td>$pick_id</td>";
	echo "<td>$cust_name($cust_code)</td>";
	echo "<td>".date("m/d/Y",strtotime($pick_date))."</td>";
	echo "<td align='right'>".number_format($amount,2,".",",")."</td>";
	echo "<td align='right'>".number_format($freight,2,".",",")."</td>";
	echo "<td align='right'>".number_format($tax,2,".",",")."</td>";
	echo "<td align='right'>".number_format($total,2,".",",")."</td>";
	echo "</tr>";
	$i++;
}
echo "<tr bgcolor='silver'>";
echo "<td>&nbsp;</td>";
echo "<td>&nbsp;</td>";
echo "<td>&nbsp;</td>";
echo "<td align='right'>".number_format($subtotal,2,".",",")."</td>";
echo "<td align='right'>".number_format($freighttotal,2,".",",")."</td>";
echo "<td align='right'>".number_format($taxtotal,2,".",",")."</td>";
echo "<td align='right'>".number_format($subtotal+$freighttotal+$taxtotal,2,".",",")."</td>";
echo "</tr>";
echo "</table>";

?>