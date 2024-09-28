<?php
	include_once("class/class.customers.php");
	include_once("class/register_globals.php");

	foreach (array('_GET', '_POST', '_COOKIE', '_SERVER') as $_SG) {
		foreach ($$_SG as $_SGK => $_SGV) {
			$$_SGK = $_SGV;
		}
	}

if ($cmd=="cust_bal") {
	$c = new Custs();

	$cust_arr = $c->getCustsList($cust_code);
	if ($c->getCusts($cust_code)) {
		$c->updateCustsBalance($cust_code);
	}
	echo "Update OK";

} else if ($cmd=="") {

} else {

}
?>