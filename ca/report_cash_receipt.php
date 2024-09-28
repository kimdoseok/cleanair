<?php
	include_once("class/class.formutils.php");

  $vars = array("start_item","end_item");
	foreach ($vars as $var) {
		$$var = "";
	}
	$vars = array("page");
	foreach ($vars as $var) {
		$$var = 0;
	}

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
    <td><strong>Cash Receipt Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_cash_receipt_out.php">
		<INPUT TYPE="hidden" name="ty" value="r">
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
          <td width="29%" bgcolor="silver">Pay Type</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
		    <INPUT TYPE="checkbox" NAME="method_ca" CHECKED>Cash
		    <INPUT TYPE="checkbox" NAME="method_ch">Check
		    <INPUT TYPE="checkbox" NAME="method_cc">Credit Card
		    <INPUT TYPE="checkbox" NAME="method_ot">Other<br>
		    <INPUT TYPE="checkbox" NAME="method_dc">Discount
		    <INPUT TYPE="checkbox" NAME="method_bc">NSF Check
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Show Detail</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><INPUT TYPE="checkbox" NAME="show_detail" value="t"></td>
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
