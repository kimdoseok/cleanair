<?php
	$fromdate = "4/22/2003";
	$days = 30;
echo strtotime ("2003-4-22")."<br><br>";
echo strtotime ("4/22/2003")."<br><br>";
	$ts = strtotime("+".$days." day", $fromdate);
	echo $ts;
	echo "<br>";
	echo strtotime ("+1 day");
	echo "<br>";
	echo strtotime ("+30 day", $fromdate);
	echo "<br>";
	echo strtotime("+30 day", $fromdate);

	echo "<br>";
	$fromdate = "4/22/2003";
	echo date ("Y-m-d", strtotime("+1 day", $fromdate));
	echo "<br>";
	$fromdate = "2003-4-23";
	echo date ("Y-m-d", strtotime("+1 day", $fromdate));
	echo "<br>";
	$fromdate = "4/22/2003";
	$ts1 = strtotime($fromdate);
	echo date ("Y-m-d", $ts1);
	echo "<br>";
	$fromdate = "2003-4-23";
	$ts2 = strtotime($fromdate);
	echo date ("Y-m-d", $ts2);
	echo "<br>";
	echo $ts2 - $ts1;

	echo "Test...";
	echo "<br>";
/*
	for ($i=1;$i<11;$i++) {
		$xdate = "2003-5-$i";
		echo $i." : ".date("D", strtotime($xdate));
		echo "<br>";
	}
	echo "<br>";
	$xdate = "5/1/2003";
	for ($i=1;$i<11;$i++) {
		echo date ("Y-m-d", strtotime("+$i day", $xdate));
		echo "<br>";
	}
	echo "<br>";
	$xdate = "5/1/2003";
	for ($i=1;$i<11;$i++) {
		echo date ("Y-m-d", strtotime("+$i day"));
		echo "<br>";
	}
*/
	$fromday = "6/3/2003";
	$wday = "wed";
		$xday = date("D", strtotime($fromday));
		$year = date("Y", strtotime($fromday));
		$month = date("m", strtotime($fromday))+0;
		$day = date("j", strtotime($fromday));

		if (strtolower($xday) == "mon") $xpos = 1;
		else if (strtolower($xday) == "tue") $xpos = 2;
		else if (strtolower($xday) == "wed") $xpos = 3;
		else if (strtolower($xday) == "thu") $xpos = 4;
		else if (strtolower($xday) == "fri") $xpos = 5;
		else if (strtolower($xday) == "sat") $xpos = 6;
		else if (strtolower($xday) == "sun") $xpos = 7;
		else return false;

		if (strtolower($wday) == "mon") $wpos = 1;
		else if (strtolower($wday) == "tue") $wpos = 2;
		else if (strtolower($wday) == "wed") $wpos = 3;
		else if (strtolower($wday) == "thu") $wpos = 4;
		else if (strtolower($wday) == "fri") $wpos = 5;
		else if (strtolower($wday) == "sat") $wpos = 6;
		else if (strtolower($wday) == "sun") $wpos = 7;
		else return false;

		$dday = $wpos - $xpos;
		if ($dday < 0) $dday += 7;
echo $dday."<br>";
		$daystr = "+$dday days";
		$ts = mktime(0,0,0,$month,$day+$dday,$year);
		echo date("m/d/Y", $ts);
//echo "<br>";
echo date("D", date("m/d/Y", $ts));

?>