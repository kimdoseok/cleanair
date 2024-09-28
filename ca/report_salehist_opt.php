<?php
	include_once("class/class.formutils.php");
	include_once("class/register_globals.php");

	$f = new FormUtil();
	$last_day=date("t");
	$month = date("m");
	$year = date("Y");
	if (empty($start_date)) $start_date = "$month/1/$year";
	if (empty($end_date)) $end_date = "$month/$last_day/$year";

?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong>Sales History Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_salehistory_out.php">
		<INPUT TYPE="hidden" name="ty" value="r">
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Sale#</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_sale_id", $start_sale_id, 20, 32, "inbox") ?></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Sale#</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_sale_id", $end_sale_id, 20, 32, "inbox") ?></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Date/Time</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
<?php
	$year = date("Y");
	$month = date("m");
	$day = date("d");
	$hour = 0;
	$minute = 0;
	$second = 0;
?>
			<SELECT name="start_datetime_year">
<?php
	for ($i=2000;$i<=$year;$i++) {
		echo "<OPTION value=";
		printf ("%04d",$i);
		if ($i==$year) echo " SELECTED";
		echo ">";
		printf ("%04d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="start_datetime_month">
<?php
	for ($i=1;$i<=12;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$month) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="start_datetime_day">
<?php
	for ($i=1;$i<=31;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$day) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="start_datetime_hour">
<?php
	for ($i=0;$i<24;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$hour) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="start_datetime_minute">
<?php
	for ($i=0;$i<60;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$minute) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="start_datetime_second">
<?php
	for ($i=0;$i<60;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$second) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Date/Time</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
<?php
	$hour = 23;
	$minute = 59;
	$second = 59;
?>
			<SELECT name="end_datetime_year">
<?php
	for ($i=2000;$i<=$year;$i++) {
		echo "<OPTION value=";
		printf ("%04d",$i);
		if ($i==$year) echo " SELECTED";
		echo ">";
		printf ("%04d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="end_datetime_month">
<?php
	for ($i=1;$i<=12;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$month) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="end_datetime_day">
<?php
	for ($i=1;$i<=31;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$day) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="end_datetime_hour">
<?php
	for ($i=0;$i<24;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$hour) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="end_datetime_minute">
<?php
	for ($i=1;$i<=60;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$minute) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
			<SELECT name="end_datetime_second">
<?php
	for ($i=0;$i<60;$i++) {
		echo "<OPTION value=";
		printf ("%02d",$i);
		if ($i==$second) echo " SELECTED";
		echo ">";
		printf ("%02d",$i);
		echo "</OPTION>";
	}
?>
			</SELECT>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Output Method</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
		    <SELECT NAME="method">
			  <option value="html">HTML</option>
			</SELECT>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Generate Statement"></td>
        </tr>
	    </FORM>
      </table></td>
    </tr>
 </table>
