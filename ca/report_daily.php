<?php
	include_once("class/class.formutils.php");
  include_once("class/register_globals.php");
	$f = new FormUtil();
	if (empty($fr)) $fr = date("m/d/Y");
	if (empty($to)) $to = date("m/d/Y");

?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong>Daily Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_daily_out.php">
		<INPUT TYPE="hidden" name="ty" value="r">
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Date</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("fr", $fr, 20, 32, "inbox") ?>
			<a href="javascript:openCalendar('fr')">C</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Date</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("to", $to, 20, 32, "inbox") ?>
			  <a href="javascript:openCalendar('to')">C</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">State</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
		    <SELECT NAME="state">
			  <option value="ALL">ALL</option>
			  <option value="NJ">NJ</option>
			  <option value="NY">NY</option>
			  <option value="CT">CT</option>
			  <option value="PA">PA</option>
			</SELECT>
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
          <td width="50%"><INPUT TYPE="checkbox" NAME="show_detail" value="t" checked></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Show Lines</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><INPUT TYPE="checkbox" NAME="show_line" value="t"></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Show Type</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
		    <INPUT TYPE="checkbox" NAME="show_pick" value="t" checked> Sales
		    <INPUT TYPE="checkbox" NAME="show_cmemo" value="t" checked> Credit Memo
		    <INPUT TYPE="checkbox" NAME="show_rcpt" value="t" checked> Receipts
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
