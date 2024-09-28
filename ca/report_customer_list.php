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
		if ($i==0) {
			echo "<tr>";
			foreach($cust_arr[$i] as $k=>$v) {
				if (!is_int($k)) {
					echo "<th>";
					echo $k;
					echo "</th>";
				}
			}
			echo "</tr>";
		}
		echo "<tr>";
		foreach($cust_arr[$i] as $k=>$v) {
			if (!is_int($k)) {
				echo "<td>";
				echo $v;
				echo "</td>";
			}
		}
		echo "</tr>";
	}
	echo "</table></body></html>";
?>
