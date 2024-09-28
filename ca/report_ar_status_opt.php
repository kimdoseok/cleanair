<?php
	include_once("class/class.formutils.php");

  $vars = array("start_cust","end_cust");
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
    <td><strong>AR Status Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_ar_status_out.php">
		<INPUT TYPE="hidden" name="ty" value="r">
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Cutoff Date</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("cutoff_date", (empty($cutoff_date))?date("m/d/Y"):$cutoff_date, 20, 32, "inbox") ?>
			<a href="javascript:openCalendar('cutoff_date')">C</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Customer</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_cust", $start_cust, 20, 32, "inbox") ?>
			<A HREF="javascript:openCustBrowse('start_cust')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Customer</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_cust", $end_cust, 20, 32, "inbox") ?>
			  <A HREF="javascript:openCustBrowse('end_cust')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Include Zero Bal Acct</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><INPUT TYPE="checkbox" NAME="zero_balance" value="z"></td>
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
			  <option value="pdf">PDF</option>
			  <option value="html">HTML</option>
<!--
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
          <td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Generate Statement"></td>
        </tr>
	    </FORM>
      </table></td>
    </tr>
 </table>
