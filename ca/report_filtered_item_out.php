<?php
	include_once("class/class.userauths.php");
	include_once("class/register_globals.php");

	$ua = new UserAuths();

	$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_fltd_item");
	if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
	if ($ua_arr["userauth_allow"]=="t") {
		if ($method == "html") include("report_filtered_item_html.php");
		else if ($method == "pdf") include("report_filtered_item_pdf.php");
		else header("Location: $HTTP_REFERER");
	} else {
		include("permission.php");
	}
?>