<?php
include_once("class/map.default.php");
include_once("class/class.datex.php");
include_once("class/class.requests.php");
include_once("class/class.terms.php");
include_once("class/register_globals.php");

include_once("common_proc.php");

if ($cmd =="terms_edit") {
	$c = new Terms();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getTerms($term_code)) {
		$oldrec = $_SESSION["olds"];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->updateTerms($term_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find terms code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: terms.php?term_code=$term_code";
	header($loc);

} else if ($cmd =="terms_add") {
	$c = new Terms();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getTerms($term_code)) {
		$errno = 1;
		$errmsg = "Tax rate code should be unique";
		include("error.php");
		exit;
	} else {
		$oldrec = $_SESSION["olds"];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->insertTerms($arr); 
	}

	$loc = "Location: terms.php?term_code=$term_code";
	header($loc);

} else {
	header("Location: $HTTP_REFERER");
}
?>