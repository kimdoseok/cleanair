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
	//include_once("class/class.ezpdf.php");
	include_once("class/fpdf.php");
	include_once("class/class.userauths.php");
	
	$date_sort = "False";

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

	$last_day = date("t");
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

//stmt_summary
	if ($method == "html") {
		if ($details == "d") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_detail");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") {
				if ($date_sort=="True") include("statements_detail_sort_html.php");
				else include("statements_detail_html.php");
			} else {
				include("permission.php");
			}
			
		} else if ($details == "l") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_summary");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("statements_list_html.php");
			else include("permission.php");
			
		} else {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_summary");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") {
				if ($date_sort=="True") include("statements_summary_sort_html.php");
				else include("statements_summary_html.php");
			} else {
				include("permission.php");
			}
		}
	} else if ($method == "pdf") {
		if ($details == "d") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_detail");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") {
				if ($date_sort=="True") include("statements_detail_sort_pdf.php");
				else include("statements_detail_pdf.php");
			} else {
				include("permission.php");
			}
			
	} else if ($details == "l") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_summary");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("statements_list_pdf.php");
			else include("permission.php");
			
		} else {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_summary");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") {
				if ($date_sort=="True") include("statements_summary_sort_pdf.php");
				else include("statements_summary_pdf.php");
			} else {
				include("permission.php");
			}
			
		}
	} else {
		header("Location: $HTTP_REFERER");
	}
?>