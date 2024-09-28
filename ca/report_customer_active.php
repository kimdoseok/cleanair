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
	$cust_arr = $c->getActiveCustsYear();
	$cust_keys = array("cust_code","cust_name","cust_addr1","cust_addr2","cust_addr3","cust_city","cust_state","cust_zip","cust_tel","cust_cell");
	$cust_num = count($cust_arr);
	echo "<html><body><table width='1024'>";
	echo "<tr>";
	for ($i=0;$i<count($cust_keys);$i++) {
		echo sprintf("<th bgcolor='silver'>%s</th>",$cust_keys[$i]);
	}
	echo "</tr>\n";
	for ($i=0;$i<$cust_num;$i++) {
		echo "<tr>";
		for ($j=0;$j<count($cust_keys);$j++) {
			echo sprintf("<tD>%s</td>",$cust_arr[$i][$cust_keys[$j]]);
		}
		echo "</tr>\n";
	}
	echo "</table></body></html>";
?>
