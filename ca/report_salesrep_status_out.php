<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.cmemo.php");
	include_once("class/class.cmemodtls.php");
	include_once("class/class.receipt.php");
	include_once("class/class.rcptdtls.php");
	include_once("class/class.requests.php");
	include_once("class/class.userauths.php");

	$vars = array("zero_balance","first_pb","");
	foreach ($vars as $var) {
		$$var = "";
	} 
	$vars = array("pg","cn");
	foreach ($vars as $var) {
		$$var = 0;
	} 

	include_once("class/register_globals.php");

	$d = new Datex();
	$r = new Dbutils();
	$c = new Custs();
	$p = new Picks();
	$pd = new PickDtls();
	$cm = new Cmemo();
	$cd = new CmemoDtl();
	$r = new Receipt();
	$rd = new RcptDtls();
	$ua = new UserAuths();

	$details = "s";

	$last_day = date("t");
	$month = date("m");
	$year = date("Y");
	if (empty($start_date)) $start_date = "$year-$month-1";
	else $start_date = $d->toIsoDate($start_date);
	if (empty($end_date)) $end_date = "$year-$month-$last_day";
	else $end_date = $d->toIsoDate($end_date);
	$cust_arr = $c->getCustsRangeRep($start_rep, $end_rep, "f", $sort);
// $start_date $end_date $start_cust $end_cust $zero_balance

	$day0 = date("Y-m-d");
	$day30 = $d->getIsoDate($day0,30,"b");
	$day60 = $d->getIsoDate($day0,60,"b");
	$day90 = $d->getIsoDate($day0,90,"b");

//stmt_summary
	if ($method == "html") {
		if ($details == "d") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_slsrep_dtl");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("report_sales_rep_status_detail_html.php");
			else include("permission.php");
			
		} else {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_slsrep_sum");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("report_sales_rep_status_summary_html.php");
			else include("permission.php");
			
		}
	} else if ($method == "pdf") {
		if ($details == "d") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_slsrep_dtl");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("report_sales_rep_status_detail_detail_pdf.php");
			else include("permission.php");
			
		} else {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_slsrep_sum");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("report_sales_rep_status_summary_pdf.php");
			else include("permission.php");
			
		}
	} else {
		print_r($_POST);
//		header("Location: $HTTP_REFERER");
	}
?>