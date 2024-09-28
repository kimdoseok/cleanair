<?php
	include_once("class/class.customers.php");
	include_once("class/class.requests.php");
	include_once("class/class.picks.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.cmemo.php");
	include_once("class/class.cmemodtls.php");
	include_once("class/class.receipt.php");
	include_once("class/class.datex.php");
	include_once("class/class.ezpdf.php");

	$c = new Custs();
	$d = new Datex();
	$p = new Picks();
	$r = new Receipt();
	$m = new Cmemo();

	$recs = $c->getCustsRange("14SU11", "ACJC01", "", "t");
	$numrecs = count($recs);
	$start_date = "5/1/2003";
	$end_date = "5/31/2003";


	$start_date = $d->toIsoDate($start_date);
	$end_date = $d->toIsoDate($end_date);

	for ($i=0;$i<$numrecs;$i++) {
		$stmt_arr = array();
		$p_arr = $p->getPicksStmt($recs[$i]["cust_code"], $start_date, $end_date);
		$p_num = count($p_arr);
		for ($j=0;$j<$p_num;$j++) {
			$tmp = array();
			$tmp[i] = $p_arr[$j]["pick_id"];
			$tmp[d] = $p_arr[$j]["pick_date"];
			$tmp[t] = "p";
			$tmp[a] = $p_arr[$j]["pick_total"];
			array_push($stmt_arr, $tmp);
		}
		print_r($p_arr);
		echo "<br><br>";
		$r_arr = $r->getReceiptStmt($recs[$i]["cust_code"], $start_date, $end_date);
		$r_num = count($r_arr);
		for ($j=0;$j<$r_num;$j++) {
			$tmp = array();
			$tmp[i] = $r_arr[$j]["rcpt_id"];
			$tmp[d] = $r_arr[$j]["rcpt_date"];
			$tmp[t] = "r";
			$tmp[a] = $r_arr[$j]["rcpt_amt"];
			array_push($stmt_arr, $tmp);
		}
		print_r($r_arr);
		echo "<br><br>";
		$m_arr = $m->getCmemoStmt($recs[$i]["cust_code"], $start_date, $end_date);
		$m_num = count($m_arr);
		for ($j=0;$j<$m_num;$j++) {
			$tmp = array();
			$tmp[i] = $m_arr[$j]["cmemo_id"];
			$tmp[d] = $m_arr[$j]["cmemo_date"];
			$tmp[t] = "c";
			$tmp[a] = $m_arr[$j]["cmemo_total"];
			array_push($stmt_arr, $tmp);
		}
		print_r($m_arr);
		echo "<br><br>";
		print_r($stmt_arr);
		echo "<br><br>";
	}

?>