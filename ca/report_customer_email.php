<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.requests.php");
	include_once("class/register_globals.php");


	$d = new Datex();
	$r = new Dbutils();
	$c = new Custs();
	$c->active='t';
	$cust_arr = $c->getCustsRange();
	$cust_num = count($cust_arr);
	echo "<html><body><table>";
	for ($i=0;$i<$cust_num;$i++) {
    if (empty($cust_arr[$i]['cust_email'])) continue;
		echo "<tr>";
		echo sprintf("<td>%s</td><td>%s</td><td>%s</td>",$cust_arr[$i]['cust_email'],$cust_arr[$i]['cust_name'],$cust_arr[$i]['cust_code']);
		echo "</tr>";
	}
	echo "</table></body></html>";
?>
