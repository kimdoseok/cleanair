<?php
include_once("class/class.salehists.php");
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.requests.php");
	//include_once("class/class.ezpdf.php");
	
	include_once("class/class.userauths.php");
	include_once("class/register_globals.php");

	$d = new Datex();
	$sh = new SaleHists();
	$ua = new UserAuths();

// $start_sale_id $end_sale_id $start_datetime $end_datetime

	if ($method == "html") {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "stmt_summary");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("report_salehist_html.php");
		else include("permission.php");
	} else {
		header("Location: $HTTP_REFERER");
	}
?>