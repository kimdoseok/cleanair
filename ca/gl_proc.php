<?php
include_once("class/class.accounts.php");
include_once("class/class.requests.php");
include_once("class/map.default.php");
include_once("class/register_globals.php");


$errno = 0;
$olds = $default["comp_code"]."_olds";

include_once("common_proc.php");


if ($cmd =="acct_edit") {
		$c = new Accts();
		$r = new Requests();
		$arr = array();
		if ($check = $c->getAccts($acct_code)) {
			$oldrec = $_SESSION[$olds];
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$c->updateAccts($acct_code, $arr); 
		} else {
			$errno = 2;
			$errmsg = "Couldn't find account code entered.";
			include("error.php");
		}
		$loc = "Location: accounts.php?ty=e&acct_code=$acct_code";
	if ($errno == 0) header($loc);

} else if ($cmd =="acct_add") {
		$c = new Accts();
		$r = new Requests();
		$arr = array();
		if ($check = $c->getAccts($acct_code)) {
			$errno = 1;
			$errmsg = "Account code should be unique";
			include("error.php");
		} else {
			$oldrec = $_SESSION[$olds];
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$c->insertAccts($arr); 
		}
		$loc = "Location: accounts.php?ty=a";
	if ($errno == 0) header($loc);

} else if ($cmd =="acct_del") {
		$c = new Accts();
	if ($check = $c->getAccts($acct_code)) {
		$c->deleteAccts($acct_code);
	} else {
		$errno = 6;
		$errmsg = "Account Delete Failure";
		include("error.php");
	}
	$loc = "Location: accounts.php?ty=l";
	if ($errno == 0) header($loc);

} else if (substr($cmd, 0,6) == "gldgr_") {
	include_once("gledger_proc.php");

} else {
	header("Location: $HTTP_REFERER");
}
?>