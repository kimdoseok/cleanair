<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.cmemo.php");
	include_once("class/class.receipt.php");

	$d = new Datex();
	$r = new Dbutils();
	$c = new Custs();
	$p = new Picks();
	$cm = new Cmemo();
	$r = new Receipt();
	$last_day=date("t");
	$month = date("m");
	$year = date("Y");
	if (empty($start_date)) $start_date = "$year-$month-1";
	else $start_date = $d->toIsoDate($start_date);
	if (empty($end_date)) $end_date = "$year-$month-$last_day";
	else $end_date = $d->toIsoDate($end_date);
	$cust_arr = $c->getCustsRange($start_cust, $end_cust, "cust_code");
// $start_date $end_date $start_cust $end_cust $zero_balance

	$day0 = date("Y-m-d");
	$day30 = $d->getIsoDate($day0,30,"b");
	$day60 = $d->getIsoDate($day0,60,"b");
	$day90 = $d->getIsoDate($day0,90,"b");

	if ($method == "html") {
		include("statements_html.php");
	} else if ($method == "text") {
		include("statements_text.php");
	} else if ($method == "print") {
		include("statements_print.php");
	} else if ($method == "pdf") {
		include("statements_pdf.php");
	} else {
		include("statements_html.php");
	}
?>