<?php
	include_once("class/class.userauths.php");

	$vars = array("start_item","end_item","show_amount");
	foreach ($vars as $var) {
		$$var = "";
	}
	$vars = array("page");
	foreach ($vars as $var) {
		$$var = 0;
	}

	include_once("class/register_globals.php");

	$ua = new UserAuths();

	if ($method == "html") {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_receipt");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("report_cash_receipt_html.php");
		else include("permission.php");
	} else if ($method == "pdf") {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_receipt");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("report_cash_receipt_pdf.php");
		else include("permission.php");
	} else {
		header("Location: $HTTP_REFERER");
	}
?>