<?php
	include_once("class/register_globals.php");

?>
<html>
<body>
<?php
set_time_limit(600);
if ($run == 1) {
	$conn = odbc_pconnect("ca", "doseok", "7795004");
	$query = "select cust_code from custs ORDER BY cust_code ";
	$rs = odbc_exec($conn, $query);
	$numrows = odbc_num_rows($rs);
	$i=1;
	while (odbc_fetch_row($rs)) {
		$cust_code = odbc_result($rs, "cust_code");
		$query = "UPDATE custs SET cust_id=$i WHERE cust_code='$cust_code' ";
echo $query."<br>";
		$rs2 = odbc_exec($conn, $query);
		$i++;
	}
}

?>
</body>
</html>