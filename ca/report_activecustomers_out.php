<?php
	include_once("class/class.userauths.php");
	include_once("class/register_globals.php");

	$ua = new UserAuths();

	if ($method == "html") {
    	$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_sale_cust_d");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("report_activecustomers_html.php");
		else include("permission.php");
	} else {
		header("Location: $HTTP_REFERER");
	}
?>
