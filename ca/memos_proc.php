<?php
include_once("class/class.memos.php");
include_once("class/class.requests.php");
include_once("class/map.default.php");
include_once("class/register_globals.php");

$olds = $default["comp_code"]."_olds";

$errno = 0;

include_once("common_proc.php");

if ($cmd =="memo_edit") {
	$c = new Memos();
	$r = new Requests();
	$arr = array();
	$oldrec = $_SESSION[$olds];
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$arr[memo_user_code] = $_SERVER["PHP_AUTH_USER"];
	$c->updateMemos($memo_id, $arr); 
	$loc = "Location: memos.php?ty=e&mt=$mt&code=$code&memo_id=$memo_id";
	header($loc);

} else if ($cmd =="memo_add") {
	$c = new Memos();
	$r = new Requests();
	$arr = array();
	$oldrec = $_SESSION[$olds];
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$arr[memo_user_code] = $_SERVER["PHP_AUTH_USER"];
	$code = $memo_doc_code;
	$c->insertMemos($arr); 
	$loc = "Location: memos.php?ty=a&mt=$memo_type&code=$code&memo_id=$memo_id";
	header($loc);

} else if ($cmd =="memo_del") {
	$c = new Memos();
	$r = new Requests();
	$arr = array();
	$c->deleteMemos($memo_id); 

	$loc = "Location: memos.php?ty=l&mt=$mt&code=$code";
	header($loc);

} else {
	header("Location: $HTTP_REFERER");
}
?>