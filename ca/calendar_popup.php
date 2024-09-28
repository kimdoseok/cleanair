<?php
	include_once("class/register_globals.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Calendar Popup</TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function setCode(value) {
	var s = self.opener.document.forms[0];
<?php
	if (!empty($objname)) {
		echo "s.$objname.value = value;\n";
		echo "s.$objname.focus();\n";
	}
?>
	self.close();

}

function setClose() {
	var s = self.opener.document.forms[0];
<?php
	if (!empty($objname)) echo "s.$objname.focus();\n";
?>
	self.close();
}

//-->
</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?

if (empty($year)) $year = date("Y"); 
if (empty($month)) $month = date("n")+0; 
$today = date("d")+0;
$thismonth = date("n").date("Y");
?>

<table border='0' cellspacing='1' cellpadding='0' width='320' bgcolor='gray' align='center'>
<tr><td align='center' bgcolor='black' colspan='7'><b><font face='Helvetica' color='white'><?= "$month / $today / $year " ?></font></b></td></tr>
<tr>
<td align='center' bgcolor='silver' width='46'><b><font face='Helvetica'>Sun</font></b></td>
<td align='center' bgcolor='silver' width='46'><b><font face='Helvetica'>Mon</font></b></td>
<td align='center' bgcolor='silver' width='46'><b><font face='Helvetica'>Tue</font></b></td>
<td align='center' bgcolor='silver' width='46'><b><font face='Helvetica'>Wed</font></b></td>
<td align='center' bgcolor='silver' width='46'><b><font face='Helvetica'>Thu</font></b></td>
<td align='center' bgcolor='silver' width='46'><b><font face='Helvetica'>Fri</font></b></td>
<td align='center' bgcolor='silver' width='46'><b><font face='Helvetica'>Sat</font></b></td></tr>
<tr>
<?php
$day=1; 
$offset=0;
$days_of_month = date("t", mktime(0,0,0,$month,$day,$year));
$days_of_week = date("w", mktime(0,0,0,$month,$day,$year)); // 0:sunday - 6:saturday

for ($i=0;$i<$days_of_week;$i++) {
	echo "<td align='center' bgcolor='white' height='28'>&nbsp;</td>";
}
$offset = $days_of_week;
$curmonth = $month.$year;
for ($i=0;$i<$days_of_month;$i++) {
	$day = $i+1;
	if ($day == $today && $thismonth==$curmonth) echo "<td align='center' bgcolor='yellow'><b>";
	else echo "<td align='center' bgcolor='white' height='28'>";
	echo "<a href='javascript:setCode(\"$month/$day/$year\")'>";
	echo $day;
	echo "</a>";
	if ($day == $today && $thismonth==$curmonth) echo "</b></td>\n";
	else echo "</td>\n";
	$offset++;
	if ($offset > 6) {
		if ($i+1 == $days_of_month) echo "</tr>";
		else echo "</tr>\n<tr>";
		$offset = 0;
	}
}
if ($offset > 0) for ($i=0;$i<7-$offset;$i++) echo "<td align='center' bgcolor='white' height='28'>&nbsp;</td>";

$ly = $year - 1;
$ny = $year + 1;
$lm = $month - 1;
if ($lm == 0) {
	$lm = 12;
	$lmy = $year - 1;
} else {
	$lmy = $year;
}
$nm = $month + 1;
if ($nm > 12) {
	$nmy = $year + 1;
	$nm = 1;
} else {
	$nmy = $year;
}

?>
</tr>

<tr align="right"> 
  <td align="center" colspan="7" bgcolor="silver"><font color="red" size="2">
    <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&year=$ly&month=$month" ?>">&lt;&lt;Last Y.</a> &nbsp; 
    <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&year=$lmy&month=$lm" ?>">&lt;Last M.</a> &nbsp; 
    <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&year=".date("Y")."&month=".date("n") ?>"> Today </a> &nbsp; 
	<a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&year=$nmy&month=$nm" ?>">Next M.&gt;</a> &nbsp; 
	<a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&year=$ny&month=$month" ?>">Next Y.&gt;&gt;</a></font>
  </td>
</tr>

</table>
</BODY>
</HTML>

