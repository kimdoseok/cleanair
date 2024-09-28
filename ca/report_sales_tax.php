<?php
	include_once("class/class.formutils.php");
	include_once("class/register_globals.php");

	$f = new FormUtil();
	
	$last_day=date("t");
	$month = date("m");
	$year = date("Y");

	if ($month>1 && $month <= 2) $quarter = 4;
	else if ($month>2 && $month <= 5) $quarter = 1;
	else if ($month>5 && $month <= 8) $quarter = 2;
	else if ($month>8 && $month <= 11) $quarter = 3;
	else $quarter = 4;

	$curqtr = $quarter - 1;
	$tax_year = $year+0;
	if ($curqtr == 0) {
		$curqtr = 4;
		$tax_year -= 1;
	}
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong>Sales By Customer Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_sales_tax_out.php">
		<INPUT TYPE="hidden" name="ty" value="r">
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="19%" bgcolor="silver">Year</td>
          <td width="1%">&nbsp;</td>
          <td width="60%">
			<?= $f->fillTextBox("tax_year", $tax_year, 20, 32, "inbox") ?>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="19%" bgcolor="silver">Type</td>
          <td width="1%">&nbsp;</td>
          <td width="60%">
		  <font face="Helvetica" size="2">
			<INPUT TYPE="radio" NAME="viewtype" VALUE="0" <?= ($viewtype==0)?"CHECKED":"" ?>>Quater
			<INPUT TYPE="radio" NAME="viewtype" VALUE="1" <?= ($viewtype==1)?"CHECKED":"" ?>>Month
		  </font>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="19%" bgcolor="silver">Quator</td>
          <td width="1%">&nbsp;</td>
          <td width="60%">
		  <font face="Helvetica" size="2">
			<INPUT TYPE="radio" NAME="tax_qtr" VALUE="1" <?= ($curqtr==1)?"CHECKED":"" ?>>Mar~May
			<INPUT TYPE="radio" NAME="tax_qtr" VALUE="2" <?= ($curqtr==2)?"CHECKED":"" ?>>Jun~Aug
			<INPUT TYPE="radio" NAME="tax_qtr" VALUE="3" <?= ($curqtr==3)?"CHECKED":"" ?>>Sep~Nov
			<INPUT TYPE="radio" NAME="tax_qtr" VALUE="4" <?= ($curqtr==4)?"CHECKED":"" ?>>Dec~Feb

		  </font>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="19%" bgcolor="silver">Month</td>
          <td width="1%">&nbsp;</td>
          <td width="60%">
		  <font face="Helvetica" size="2">
			<INPUT TYPE="checkbox" NAME="tax_mth[0]" VALUE="0">Jan.
			<INPUT TYPE="checkbox" NAME="tax_mth[1]" VALUE="1">Feb.
			<INPUT TYPE="checkbox" NAME="tax_mth[2]" VALUE="2">Mar.
			<INPUT TYPE="checkbox" NAME="tax_mth[3]" VALUE="3">Apr.
			<INPUT TYPE="checkbox" NAME="tax_mth[4]" VALUE="4">May
			<INPUT TYPE="checkbox" NAME="tax_mth[5]" VALUE="5">Jun.<br>
			<INPUT TYPE="checkbox" NAME="tax_mth[6]" VALUE="6">Jul.
			<INPUT TYPE="checkbox" NAME="tax_mth[7]" VALUE="7">Aug.
			<INPUT TYPE="checkbox" NAME="tax_mth[8]" VALUE="8">Sep.
			<INPUT TYPE="checkbox" NAME="tax_mth[9]" VALUE="9">Oct.
			<INPUT TYPE="checkbox" NAME="tax_mth[10]" VALUE="10">Nov.
			<INPUT TYPE="checkbox" NAME="tax_mth[11]" VALUE="11">Dec.
		  </font>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="19%" bgcolor="silver">State</td>
          <td width="1%">&nbsp;</td>
          <td width="60%">
			<SELECT NAME="tax_state">
			  <OPTION VALUE="NY">New York</OPTION>
			  <OPTION VALUE="NJ">New Jersey</OPTION>
			  <OPTION VALUE="CT">Connecticut</OPTION>
			  <OPTION VALUE="PA">Pennsylvania</OPTION>
			</SELECT>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="19%" bgcolor="silver">Output Method</td>
          <td width="1%">&nbsp;</td>
          <td width="60%">
		    <SELECT NAME="method">
			  <option value="html">HTML</option>
<!--
			  <option value="pdf">PDF</option>
			  <option value="text">TEXT</option>
			  <option value="print">Printer</option>
-->
			</SELECT>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Generate Report"></td>
        </tr>
	    </FORM>
      </table></td>
    </tr>
 </table>
