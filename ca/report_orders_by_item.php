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
    <td><strong>Orders By Item Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_orders_by_item_out.php">
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
          <td width="29%" bgcolor="silver">Start Item</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_item", $start_item, 20, 32, "inbox") ?>
			<A HREF="javascript:openItemBrowse('start_item')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Item</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_item", $end_item, 20, 32, "inbox") ?>
			  <A HREF="javascript:openItemBrowse('end_item')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
<!--
		<tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Customer</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_cust", $start_vend, 20, 32, "inbox") ?>
			<A HREF="javascript:openVendBrowse('start_cust')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Customer</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_cust", $end_vend, 20, 32, "inbox") ?>
			  <A HREF="javascript:openVendBrowse('end_cust')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
-->
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
          <td width="29%" bgcolor="silver">Show Format</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
		    <SELECT NAME="show_format">
			  <OPTION VALUE="woa">Without Amount</OPTION>
			  <OPTION VALUE="wa">With Amount</OPTION>
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
          <td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Generate Statement"></td>
        </tr>
	    </FORM>
      </table></td>
    </tr>
 </table>
