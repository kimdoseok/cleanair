<?php
	include_once("class/class.userauths.php");
	include_once("class/register_globals.php");

	$ua = new UserAuths();

	$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "rpt_best_cust");
	if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
	if (true or $ua_arr["userauth_allow"]=="t") {
		if ($method == "html") include("rpt_best_customer_html.php");
		else if ($method == "pdf") include("rpt_best_customer_pdf.php");
		else header("Location: $HTTP_REFERER");
	} else {
		include("permission.php");
	}
?>