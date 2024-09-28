<?php
	$fr = "7/9/2003";
	$to = "7/15/2003";
	echo $fr."<br>";
	echo $to."<br>";
	echo "<br>";
	$fr_t = strtotime($fr);
	$to_t = strtotime($to);
	echo $fr_t."<br>";
	echo $to_t."<br>";
	$t_sec = $to_t-$fr_t;
	echo $t_sec." sec<br>";
	$t_min = $t_sec/60;
	echo $t_min." min<br>";
	$t_hour = $t_min / 60;
	echo $t_hour." hour<br>";
	$t_day = $t_hour / 24 + 1;
	echo $t_day." day<br>";
	echo "<br>";
	echo "<br>";
	$days = ($to_t - $fr_t)/86400 + 1;
	$t_arr = getdate($fr_t);

	for ($i=0;$i<$t_day;$i++) {
		$ts = mktime(0,0,0,$t_arr["mon"],$t_arr["mday"]+$i,$t_arr["year"]);
		echo date("m/d/Y", $ts);
		echo "<br>";
	}

?>