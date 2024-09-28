<?php
$conn = odbc_pconnect("ca", "doseok", "7795004");
$query = "select * from custs order by cust_name";
$rs = odbc_exec($conn, $query);
echo "<table>";
$i=0;
while($arr = odbc_fetch_row($rs)) {
	$name = stripslashes(odbc_result($rs,"cust_name"));
	$addr = stripslashes(odbc_result($rs,"cust_addr1"));
	$city = stripslashes(odbc_result($rs,"cust_city"));
	$state = stripslashes(odbc_result($rs,"cust_state"));
	$zip = stripslashes(odbc_result($rs,"cust_zip"));
	$tel = stripslashes(odbc_result($rs,"cust_tel"));
	echo "<tr>";
	echo "<td>$name</td><td>$addr</td>";
	echo "<td>$city</td><td>$state</td>";
	echo "<td>$zip</td><td>$tel</td>";
	echo "</tr>";
	$i++;
}
echo "</table>";

?>