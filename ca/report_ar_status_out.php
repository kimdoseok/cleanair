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
//	include_once("class/class.ezpdf.php");
	include_once("class/fpdf.php");

	include_once("class/class.userauths.php");

	$vars = array("start_cust","end_cust","method");
	foreach ($vars as $var) {
		$$var = "";
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

	$last_day = date("t");
	$month = date("m");
	$year = date("Y");
	if (empty($cutoff_date)) $cutoff_date = date("m/d/Y");
	$cust_arr = $c->getCustsRange($start_cust, $end_cust, "cust_code");
// $start_date $end_date $start_cust $end_cust $zero_balance

	$end_date = $cutoff_date;
	$day0 = $d->toIsoDate($cutoff_date);
	$day30 = $d->getIsoDate($day0,30,"b");
	$day60 = $d->getIsoDate($day0,60,"b");
	$day90 = $d->getIsoDate($day0,90,"b");

	if ($method == "html") {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_summary");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("report_ar_status_html.php");
		else include("permission.php");
	} else if ($method == "pdf") {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_summary");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("report_ar_status_pdf.php");
		else include("permission.php");
	} else {
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
?>