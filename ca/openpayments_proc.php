<?php
include_once("class/map.default.php");
include_once("class/class.accounts.php");
include_once("class/class.receipt.php");
include_once("class/class.rcptdtls.php");
include_once("class/class.openpay.php");
include_once("class/class.customers.php");
include_once("class/class.requests.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.items.php");
include_once("class/register_globals.php");

$errno = 0;
//echo $cmd;

$s = new Receipt();
$d = new RcptDtls();
$o = new OpenPay();

if ($cmd == "openpay_detail_add") {
	$opdtl = $o->getOpenPayDtlsList($rcpt_id);
	if ($opdtl) $opnum = count($opdtl);
	else $opnum = 0;
	$applied = 0;
	$rcptdtl_disc_amt = 0;
	$rcptdtl_op_amt = 0;
	$rcptdtl_cm_amt = 0;
	$rcptdtl_aw_amt = 0;
	for ($i=0;$i<$opnum;$i++) {
		if ($opdtl[$i]["rcptdtl_type"]=="ar") $applied += $opdtl[$i]["rcptdtl_amt"];
		else if ($opdtl[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $opdtl[$i]["rcptdtl_amt"];
		else if ($opdtl[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $opdtl[$i]["rcptdtl_amt"];
		else if ($opdtl[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $opdtl[$i]["rcptdtl_amt"];
		else if ($opdtl[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $opdtl[$i]["rcptdtl_amt"];
		else $applied += $opdtl[$i]["rcptdtl_amt"];
	}

	if ($rcptdtl_op_amt<$rcptdtl_amt && $rcptdtl_amt>0) {
		$sls = array();
		$sls[rcptdtl_rcpt_id]	= $rcpt_id;
		$sls["rcptdtl_pick_id"]	= $rcptdtl_pick_id;
		$sls[rcptdtl_acct_code]	= $rcptdtl_acct_code;
		$sls["rcptdtl_type"]		= $rcptdtl_type;
		$sls["rcptdtl_amt"]		= $rcptdtl_amt;
		$sls["rcptdtl_desc"]		= $rcptdtl_desc;
		$sls[rcptdtl_ref_code]	= $rcptdtl_id;
		$success = true;
		if (!$o->insertOpenPay($sls)) $success = false;
		$sls["rcptdtl_type"]		= "OP";
		$sls["rcptdtl_amt"]		= $rcptdtl_amt*-1;
		$sls[rcptdtl_acct_code]	= $default[licd_acct];
		if (!$o->insertOpenPay($sls)) $success = false;
		if ($success) {
			$j = new JrnlTrxs();
			$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $rcptdtl_acct_code, "c", "d", $rcptdtl_amt,  date("m-d-Y"));
			$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $default[licd_acct], "c", "c", $rcptdtl_amt, date("m-d-Y")); // expense 
		}
	}
	$loc = "Location: openpay_details.php?ty=a&rcpt_id=$rcpt_id&rcptdtl_id=$rcptdtl_id";
//	openpay_details.php?ty=a&rcpt_id=7738&rcptdtl_id=7949
	header($loc);
	exit;

} else if ($cmd == "openpay_detail_edit") {

	$opdtl = $o->getOpenPayDtlsList($rcpt_id);
	if ($opdtl) $opnum = count($opdtl);
	else $opnum = 0;
	$applied = 0;
	$rcptdtl_disc_amt = 0;
	$rcptdtl_op_amt = 0;
	$rcptdtl_cm_amt = 0;
	$rcptdtl_aw_amt = 0;
	for ($i=0;$i<$opnum;$i++) {
		if ($opdtl[$i]["rcptdtl_type"]=="ar") $applied += $opdtl[$i]["rcptdtl_amt"];
		else if ($opdtl[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $opdtl[$i]["rcptdtl_amt"];
		else if ($opdtl[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $opdtl[$i]["rcptdtl_amt"];
		else if ($opdtl[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $opdtl[$i]["rcptdtl_amt"];
		else if ($opdtl[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $opdtl[$i]["rcptdtl_amt"];
		else $applied += $rcptdtl[$i]["rcptdtl_amt"];
	}

	$org_arr = $o->getOpenPayDtls($rcptdtl_id);
	if ($rcptdtl_op_amt<$rcptdtl_amt && $rcptdtl_amt>0) {
		$sls = array();
//		$sls[rcptdtl_rcpt_id]	= $rcpt_id;
		$sls["rcptdtl_pick_id"]	= $rcptdtl_pick_id;
		$sls[rcptdtl_acct_code]	= $rcptdtl_acct_code;
		$sls["rcptdtl_type"]		= $rcptdtl_type;
		$sls["rcptdtl_amt"]		= $rcptdtl_amt;
		$sls["rcptdtl_desc"]		= $rcptdtl_desc;
		$sls[rcptdtl_ref_code]	= $rcptdtl_id;
		$success = true;
		if (!$o->updateOpenPay($rcptdtl_id, $sls)) $success = false;
		$sls["rcptdtl_type"]		= "OP";
		$sls["rcptdtl_amt"]		= $rcptdtl_amt*-1;
		$sls[rcptdtl_acct_code]	= $default[licd_acct];
		if (!$o->updateOpenPay($rcptdtl_id, $sls)) $success = false;

		if ($success) {
			$j = new JrnlTrxs();
			$j->deleteJrnlTrx();
			$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $rcptdtl_acct_code, "c", "d", $rcptdtl_amt,  date("m-d-Y"));
			$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $default[licd_acct], "c", "c", $rcptdtl_amt, date("m-d-Y")); // expense 
		}
	}

////////////////////////////////////////////////////////////////
	if ($rcptdtl_type == "ar") {
		if ($rcpt["rcpt_amt"] < $applied + $rcptdtl_amt ) {
			$errno=5;
			$recommand = sprintf("%0.2f", $rcpt["rcpt_amt"] - $applied);
			$errmsg="Amount should be equal or less than $recommand!";
			include("error.php");
			exit;
		}
	}
	$arr = array();
	for ($i=0;$i<count($rcptdtl);$i++) {
		if ($i == $did) {
			$sls = array();
			$sls[rcptdtl_rcpt_id]	= $rcptdtl_rcpt_id;
			$sls["rcptdtl_pick_id"]		= $rcptdtl_pick_id;
			$sls[rcptdtl_acct_code]	= $rcptdtl_acct_code;
			$sls["rcptdtl_type"]		= $rcptdtl_type;
//			if ($rcptdtl_type != "d") $rcptdtl_amt = abs($rcptdtl_amt)*-1;
			$sls["rcptdtl_amt"]			= $rcptdtl_amt;
			$sls["rcptdtl_desc"]		= $rcptdtl_desc;
			array_push($arr, $sls);
		} else {
			array_push($arr, $rcptdtl[$i]);
		}
	}
	$_SESSION[rcptdtls_edit] = $arr;
	$loc = "Location: openpay_details.php?ty=e&ht=e&did=$did&rcpt_id=$rcpt_id";
	header($loc);
	exit;

}
?>