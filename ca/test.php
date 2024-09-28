<?php
	include_once("class/register_globals.php");

?>

<html>
<body>
<?php
set_time_limit(600);
if ($run == 1) {
	$conn = odbc_pconnect("ca", "doseok", "7795004");
	$query = "select * from item_t ";
	$rs = odbc_exec($conn, $query);
	$numrows = odbc_num_rows($rs);
	$i=0;
	while (odbc_fetch_row($rs)) {
		$fld1 = odbc_result($rs, "fld1");
		$fld6 = odbc_result($rs, "fld6");
		$descript = odbc_result($rs, "descript");
		$msrp = odbc_result($rs, "msrp");
		$tax = odbc_result($rs, "tax");
		$fld2="";
		$fld3="";
		$fld4="";
		$fld5="";
		list($fld2, $fld3, $fld4, $fld5) = explode(":", $fld1);
		$fld2 = trim($fld2);
		$fld3 = trim($fld3);
		$fld4 = trim($fld4);
		$fld5 = trim($fld5);
		$query = " UPDATE item_t SET fld2='$fld2', fld3='$fld3', fld4='$fld4', fld5='$fld5' WHERE fld6='$fld6' ";	
echo $query."<br>";
		$rs1 = odbc_exec($conn, $query);
		$i++;
	}

} else if ($run == 2) {
	$conn = odbc_pconnect("ca", "doseok", "7795004");
	$query = "SELECT fld2, fld3, fld4, fld5 FROM item_t GROUP BY fld2, fld3, fld4, fld5 ";
	$rs = odbc_exec($conn, $query);
	$numrows = odbc_num_rows($rs);
	$i=0;
	while (odbc_fetch_row($rs)) {
		$cate_code = "";
		$fld2 = addslashes(odbc_result($rs, "fld2"));
		$fld3 = addslashes(odbc_result($rs, "fld3"));
		$fld4 = addslashes(odbc_result($rs, "fld4"));
		$fld5 = addslashes(odbc_result($rs, "fld5"));
		$i++;
		$cate_code .= substr(odbc_result($rs, "fld2"), 0, 1);
		$cate_code .= substr(odbc_result($rs, "fld3"), 0, 1);
		$cate_code .= substr(odbc_result($rs, "fld4"), 0, 1);
		$cate_code .= substr(odbc_result($rs, "fld5"), 0, 1);
		$j = 0;
		do {
			if ($j==0) $code = $cate_code;
			else $code = $cate_code.$j;
			$query1 = "SELECT cate_code FROM cates WHERE cate_code = '$code' ";
//echo $query1." $rownum<br>";
			$rs1 = odbc_exec($conn, $query1);

			if (odbc_fetch_row($rs1)) $numrow = 1;
			else $numrow = 0;
			$j++;
echo odbc_num_rows($rs1)."  $j  $numrow <br>";
if ($j>50) break;
		} while ($numrow != 0);

		$query2 = "INSERT INTO cates (cate_code, cate_name1, cate_name2, cate_name3, cate_name4) VALUES ('$code', '$fld2', '$fld3', '$fld4', '$fld5') ";
echo $query2."<br>";
		$rs2 = odbc_exec($conn, $query2);

		$query3 = "UPDATE item_t SET fld1='$code' WHERE fld2='$fld2' AND fld3='$fld3' AND fld4='$fld4' AND fld5='$fld5' ";
echo $query3."<br>";
		$rs3 = odbc_exec($conn, $query3);
	}

} else if ($run == 3) {
	$conn = odbc_pconnect("ca", "doseok", "7795004");

	$query = "select * from item_t ";
	$rs = odbc_exec($conn, $query);
	$numrows = odbc_num_rows($rs);

	while (odbc_fetch_row($rs)) {
		$fld1 = addslashes(odbc_result($rs, "fld1"));
		$fld2 = addslashes(odbc_result($rs, "fld2"));
		$fld3 = addslashes(odbc_result($rs, "fld3"));
		$fld4 = addslashes(odbc_result($rs, "fld4"));
		$fld5 = addslashes(odbc_result($rs, "fld5"));
		$fld6 = addslashes(odbc_result($rs, "fld6"));
		$descript = addslashes(odbc_result($rs, "descript"));
		$msrp = addslashes(odbc_result($rs, "msrp"));
		$tax = odbc_result($rs, "tax");
		if ($tax == 'N') $taxable = "f";
		else $taxable = "t";

		$query1 = "INSERT INTO items (item_code, item_cate_code, item_desc, item_msrp, item_tax) VALUES ('$fld6', '$fld1', '$descript', '$msrp', 't') ";
echo $query1."<br>";
		$rs1 = odbc_exec($conn, $query1);
	}

} else if ($run == 4) {
	$conn = odbc_pconnect("ca", "doseok", "7795004");

	$query = "SELECT fld2, fld3, fld4, fld5 FROM item_t GROUP BY fld2, fld3, fld4, fld5 ";
	$rs = odbc_exec($conn, $query);
	$numrows = odbc_num_rows($rs);
	$i=0;
	while (odbc_fetch_row($rs)) {
		$fld2 = addslashes(odbc_result($rs, "fld2"));
		$fld3 = addslashes(odbc_result($rs, "fld3"));
		$fld4 = addslashes(odbc_result($rs, "fld4"));
		$fld5 = addslashes(odbc_result($rs, "fld5"));
		$i++;
		$query = "INSERT INTO cates (cate_code, cate_name1, cate_name2, cate_name3, cate_name4) VALUES ('$i', '$fld2', '$fld3', '$fld4', '$fld5') ";
		$rs1 = odbc_exec($conn, $query);
	}

	$query = "select * from item_t ";
	$rs = odbc_exec($conn, $query);
	$numrows = odbc_num_rows($rs);

	while (odbc_fetch_row($rs)) {
		$fld1 = addslashes(odbc_result($rs, "fld1"));
		$fld2 = addslashes(odbc_result($rs, "fld2"));
		$fld3 = addslashes(odbc_result($rs, "fld3"));
		$fld4 = addslashes(odbc_result($rs, "fld4"));
		$fld5 = addslashes(odbc_result($rs, "fld5"));
		$fld6 = addslashes(odbc_result($rs, "fld6"));
		$desc = addslashes(odbc_result($rs, "desc"));
		$msrp = addslashes(odbc_result($rs, "msrp"));
		$tax = odbc_result($rs, "tax");
		if ($tax == 'Y') $taxable = "t";
		else $taxable = "f";

		$query = "SELECT cate_code FROM cates WHERE cate_name1='$fld2' AND cate_name2='$fld3' AND cate_name3='$fld4' AND cate_name4='$fld5' ";
		$rs1 = odbc_exec($conn, $query);
		if (odbc_fetch_row($rs)) $cate_code = addslashes(odbc_result($rs1, "cate_code"));

		$query = "SELECT item_code FROM items WHERE item_code='$fld6' ";
		$rs1 = odbc_exec($conn, $query);
		if (odbc_fetch_row($rs1)) {
			$item_code = addslashes(odbc_result($rs1, "item_code"));
			$item_code .= "XX";
		}

		$query = "INSERT INTO items (item_code, item_cate_code, item_desc, item_msrp, item_tax) VALUES ('$fld6', '$cate_code', '$desc', '$msrp', '$taxable') ";
		echo $query."<br>";
		$rs1 = odbc_exec($conn, $query);
	}

} else if ($run == 5) {
	$conn = odbc_pconnect("ca", "doseok", "7795004");

	$query = "select * from cust_t ";
	$rs = odbc_exec($conn, $query);
	$numrows = odbc_num_rows($rs);

	while (odbc_fetch_row($rs)) {
		$cust_code = addslashes(odbc_result($rs, "cust_code"));
		$cust_city = addslashes(odbc_result($rs, "cust_city"));
		$cust_state = addslashes(odbc_result($rs, "cust_state"));
		$cust_zip = addslashes(odbc_result($rs, "cust_zip"));
		if ($tax == 'N') $taxable = "f";
		else $taxable = "t";

		$query1 = "UPDATE custs SET cust_city='$cust_city', cust_state='$cust_state', cust_zip='$cust_zip' WHERE cust_code='$cust_code' ";
echo $query1."<br>";
		$rs1 = odbc_exec($conn, $query1);
	}

} else if ($run == 6) {
	$conn = odbc_pconnect("ca", "doseok", "7795004");
	$f = fopen("../balance.csv", r);
	while (!feof($f)) {
		$line = fgets($f);
		list($code,$name,$addr1,$city,$state,$tel,$bal) = explode(",", $line);
		if (!empty($code)) {
			$bal += 0;
			$query = "UPDATE custs SET cust_name='$name', cust_addr1='$addr1', cust_city='$city', cust_state='$state', cust_tel='$tel', cust_init_bal=$bal WHERE cust_code='$code' ";
	echo $query."<br>";
			$rs = odbc_exec($conn, $query);

		}
		echo "$code/$name/$addr1/$city/$state/$tel/$bal<br>";
	}
	fclose($f);
}

?>
</body>
</html>