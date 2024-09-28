<?php
	include_once("class/class.formutils.php");

	$f = new FormUtil();
	
	$vars = array("start_date","end_date");
	foreach ($vars as $var) {
		$$var = "";
	} 
	$vars = array("pg","cn");
	foreach ($vars as $var) {
		$$var = 0;
	} 

	include_once("class/register_globals.php");

?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong>Best/Worst Customer Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="rpt_best_customer_out.php">
		<INPUT TYPE="hidden" name="ty" value="r">
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">Start Date</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%"><?= $f->fillTextBox("start_date", $start_date, 20, 32, "inbox") ?>
			<a href="javascript:openCalendar('start_date')">C</a></td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">End Date</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%"><?= $f->fillTextBox("end_date", $end_date, 20, 32, "inbox") ?>
			<a href="javascript:openCalendar('end_date')">C</a></td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Sort By</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
		    <SELECT NAME="sortby">
			  <OPTION VALUE="sale">Sales</OPTION>
			  <OPTION VALUE="rcpt">Payment</OPTION>
			  <OPTION VALUE="cmemo">Credit Memo</OPTION>
<!--
			  <OPTION VALUE="balance">Balance</OPTION>
-->
			</SELECT>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">Display #</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%"><?= $f->fillTextBox("num_disp", 50, 5, 10, "inbox") ?></td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">&nbsp;</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%">
		    <INPUT TYPE="radio" NAME="sort" VALUE="t" CHECKED>Best
			<INPUT TYPE="radio" NAME="sort" VALUE="f">Worst
		  </td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
<!--
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Show Inactive</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><INPUT TYPE="checkbox" NAME="show_inactive" value="t"></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
-->
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Output Method</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
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
