<?php
include_once("class/map.default.php");
include_once("class/class.disbursements.php");
include_once("class/class.vendors.php");
include_once("class/class.requests.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.items.php");
include_once("class/map.default.php");
include_once("class/register_globals.php");


$errno = 0;
$olds = $default["comp_code"]."_olds";

include_once("common_proc.php");

if ($cmd =="vend_edit") {
	$c = new Vends();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getVends($vend_code)) {
		$arr = $r->getAlteredArray($_SESSION[$olds], $_POST); 
		$c->updateVends($vend_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find vendor code entered.";
		include("error.php");
	}
	$loc = "Location: vendors.php?ty=e&vend_code=$vend_code";
	if ($errno == 0) header($loc);

} else if ($cmd =="vend_add") {
	$c = new Vends();
	$r = new Requests();
	$arr = array();

	if ($check = $c->getVends($vend_code)) {
		$errno = 1;
		$errmsg = "Vendor code should be unique";
		include("error.php");
		exit;
	} else {
		$arr = $r->getAlteredArray($_SESSION[$olds], $_POST); 
		$c->insertVends($arr); 
	}
	$loc = "Location: vendors.php?ty=e&vend_code=$vend_code";
	if ($errno == 0) header($loc);

} else if ($cmd =="disburs_edit") {
	$c = new Disburs();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getDisburs($disbur_id)) {
		$arr = $r->getAlteredArray($_SESSION[$olds], $_POST); 
		$c->updateDisburs($disbur_id, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find disbursement number entered.";
		include("error.php");
		exit;
	}

	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($disbur_id, "d");
	$j->insertJrnlTrxExs($disbur_id, $_SERVER["PHP_AUTH_USER"], $default["litp_acct"], "d", "d", $disbur_amt, $disbur_date); // ap for disbursement
	$j->insertJrnlTrxExs($disbur_id, $_SERVER["PHP_AUTH_USER"], $disbur_acct_code, "d", "c", $disbur_amt, $disbur_date); // account for disbursement

	$loc = "Location: disbursement.php?ty=e&disbur_id=$disbur_id";
	if ($errno == 0) header($loc);

} else if ($cmd =="disburs_add") {
	$c = new Disburs();
	$r = new Requests();
	$v = new Vends();
	$arr = array();
	if (!$check = $v->getVends($disbur_vend_code)) {
		$errno = 10;
		$errmsg = "Vendor code is not found in DB!";
		include("error.php");
		exit;
	}

	$arr = $r->getAlteredArray($_SESSION[$olds], $_POST); 
	if (!$disbur_id = $c->insertDisburs($arr)) {
		$errno = 1;
		$errmsg = "Disburse Inserting Failure";
		include("error.php");
		exit;
	}

	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($disbur_id, "d");
	$j->insertJrnlTrxExs($disbur_id, $_SERVER["PHP_AUTH_USER"], $default["litp_acct"], "d", "d", $disbur_amt, $disbur_date); // ap for disbursement
	$j->insertJrnlTrxExs($disbur_id, $_SERVER["PHP_AUTH_USER"], $disbur_acct_code, "d", "c", $disbur_amt, $disbur_date); // account for disbursement
	$loc = "Location: disbursement.php?ty=a";
	if ($errno == 0) header($loc);

} else if ($cmd =="disburs_del") {
	$c = new Disburs();
	if ($check = $c->getDisburs($disbur_id)) { 
		$c->deleteDisburs($disbur_id);
		$j = new JrnlTrxs();
		$j->deleteJrnlTrxRefs($disbur_id, "d");
	} else {
		$errno = 6;
		$errmsg = "Disbursement Delete Failure";
		include("error.php");
		exit;
	}
	$loc = "Location: disbursement.php?ty=l";
	if ($errno == 0) header($loc);

} else if (substr($cmd,0,7) == "disbur_") {
	include_once("disburse_proc.php");

} else if (substr($cmd,0,6) == "purch_") {
	include_once("purchase_proc.php");

} else {
	header("Location: $HTTP_REFERER");
}
?>