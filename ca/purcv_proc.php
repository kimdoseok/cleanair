<?php
include_once("class/map.default.php");
include_once("class/class.datex.php");
include_once("class/class.requests.php");
include_once("class/class.purcvds.php");
	
include_once("common_proc.php");
include_once("class/register_globals.php");

if ($cmd =="purcv_edit") {
	$c = new Purcvds();
	$r = new Requests();
	$arr = array();
	echo $purch_id;
	
	if ($check = $c->getPurcvds($purcv_id)) {
		$oldrec = $_SESSION["olds"];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->updatePurcvds($purcv_id, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't purchase receving number entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: purcv_popup.php?purcv_purdtl_id=$purcv_purdtl_id&purcv_id=$purcv_id";
	header($loc);
	exit;
	
} else if ($cmd =="purcv_add") {
	$c = new Purcvds();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getPurcvds($purcv_id)) {
		$errno = 1;
		$errmsg = "The number should be unique";
		include("error.php");
		exit;
	} else {
		$oldrec = $r->getConvertArray($c->getPurcvdsFields());
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->insertPurcvds($arr); 
	}

	$loc = "Location: purcv_popup.php?purcv_purdtl_id=$purcv_purdtl_id&purcv_id=$purcv_id";
//	header($loc);
	exit;
	
} else if ($cmd =="purcv_del") {
	$loc = "Location: purcv_popup.php?purcv_purdtl_id=$purcv_purdtl_id";
	header($loc);
	exit;
	
} else {
	header("Location: $HTTP_REFERER");
	exit;
}
?>