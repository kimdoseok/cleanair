<?php
	include_once("class/class.userauths.php");
	include_once("class/register_globals.php");

	$ua = new UserAuths();

	if ($method == "html") {
		if ($show_detail== "t") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_daily_detail");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("report_monthly_detail_html.php");
			else include("permission.php");
			
		} else {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_daily_sum");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("report_monthly_summary_html.php");
			else include("permission.php");
			
		}
	} else if ($method == "pdf") {
		if ($show_detail== "t") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_daily_detail");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("report_monthly_detail_pdf.php");
			else include("permission.php");
			
		} else {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_daily_sum");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("report_monthly_summary_pdf.php");
			else include("permission.php");
			
		}
	} else {
		header("Location: $HTTP_REFERER");
	}
?>