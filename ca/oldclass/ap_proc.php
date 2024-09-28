<?php
include_once("class/class.vendors.php");
include_once("class/class.requests.php");

if ($cmd =="vend_edit") {
	if (preg_match ("/vendors.php/i", $HTTP_REFERER,$match)) {
		$c = new Vends();
		$r = new Requests();
		$arr = array();
		if ($check = $c->getVends($cust_code)) {
			$oldrec = unserialize(base64_decode($_SESSION["olds"]));
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$c->updateVends($vend_code, $arr); 
		} else {
			$errno = 2;
			$errmsg = "Couldn't find vendor code entered.";
			include("error.php")
		}
		$loc = "Location: vendors.php?vend_code=$vend_code";
	} else {
		$loc = "Location: $HTTP_REFERER";
	}
	header($loc);
} else if ($cmd =="vend_add") {
	if (preg_match ("/vendors.php/i", $HTTP_REFERER,$match)) {
		$c = new Vends();
		$r = new Requests();
		$arr = array();

		if ($check = $c->getVends($vend_code)) {
			$errno = 1;
			$errmsg = "Vendor code should be unique";
			include("error.php")
		} else {
			$oldrec = unserialize(base64_decode($_SESSION["olds"]));
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$c->insertVends($arr); 
		}
		$loc = "Location: vendors.php?vend_code=$vend_code";
	} else {
		$loc = "Location: $HTTP_REFERER";
	}
	header($loc);
} else {
	header("Location: $HTTP_REFERER");
}
?>