<?php
set_time_limit(600);
if ($run == 1) {
	$conn = odbc_pconnect("catest", "doseok", "7795004");
	$query = "SELECT pick_id, pick_cust_code, pick_paid_amt, pick_amt, pick_tax_amt, pick_freight_amt FROM picks ORDER BY pick_id ";
echo $query."<br>";
	$rs = odbc_exec($conn, $query);
	$numrows = odbc_num_rows($rs);
	$i=1;
	while (odbc_fetch_row($rs)) {
		$pick_id = odbc_result($rs, "pick_id");
		$pick_cust_code = odbc_result($rs, "pick_cust_code");
		$pick_amt = odbc_result($rs, "pick_amt");
		$pick_tax_amt = odbc_result($rs, "pick_tax_amt");
		$pick_freight_amt = odbc_result($rs, "pick_freight_amt");
		$pick_paid_amt = odbc_result($rs, "pick_paid_amt");
		$sale_amt = $pick_amt + $pick_tax_amt + $pick_freight_amt;
		$query = "SELECT sum(rcptdtl_amt) as rcpt_amt FROM rcptdtls WHERE rcptdtl_pick_id=$pick_id ";
echo $query."<br>";
		$rs1 = odbc_exec($conn, $query);
		$paid_amt = odbc_result($rs1, "rcpt_amt");
		$balance = round(($sale_amt - $pick_paid_amt - $paid_amt)*100)/100;
echo "Balance : $balance = $sale_amt - $pick_paid_amt - $paid_amt <br>";
		if ($balance<=0) continue;

		$query = "SELECT rcpt_id, rcptdtl_id, rcptdtl_type, rcptdtl_amt FROM rcptdtls d, rcpts r WHERE rcpt_id=rcptdtl_rcpt_id AND rcpt_cust_code='$pick_cust_code' AND rcptdtl_pick_id=0 ORDER BY rcpt_date, rcptdtl_type DESC ";
echo $query."<br>";
		$rs1 = odbc_exec($conn, $query);
		$paid = 0;
		while (odbc_fetch_row($rs1)) {
			$rcpt_id = odbc_result($rs1, "rcpt_id");
			$rcptdtl_id = odbc_result($rs1, "rcptdtl_id");
			$rcptdtl_type = odbc_result($rs1, "rcptdtl_type");
			$rcptdtl_amt = odbc_result($rs1, "rcptdtl_amt");
			if ($balance > $rcptdtl_amt) {
				$paid += $rcptdtl_amt;
				$balance -= $rcptdtl_amt;
			} else if ($balance == $rcptdtl_amt) {
				$paid += $rcptdtl_amt;
				$balance = 0;
			} else if ($balance < $rcptdtl_amt) {
				$paid = round($balance*100)/100;
				$open_pay_amt = round(($rcptdtl_amt - $balance)*100)/100;
echo "$open_pay_amt = $rcptdtl_amt - $balance <br>";
				$query = "INSERT INTO rcptdtls (rcptdtl_type, rcptdtl_rcpt_id, rcptdtl_pick_id, rcptdtl_acct_code, rcptdtl_amt) VALUES ('$rcptdtl_type', $rcpt_id, 0, '10000', '$open_pay_amt') ";
echo $query."<br>";
				$rs2 = odbc_exec($conn, $query);
				$balance =0;
			}
			$query = "UPDATE rcptdtls SET rcptdtl_pick_id='$pick_id' WHERE rcptdtl_id=$rcptdtl_id ";
			$rs2 = odbc_exec($conn, $query);
			if ($balance <= 0) break;
		}

		$query = "UPDATE picks SET pick_paid_amt = $paid WHERE pick_id='$pick_id' ";
echo $query."<br>";
		$rs1 = odbc_exec($conn, $query);
		$i++;
	}
}

?>