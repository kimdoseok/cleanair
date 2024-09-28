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
    <td><strong>Customer Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_customers_out.php">
		<INPUT TYPE="hidden" name="ty" value="r">
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Customer#</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_cust", $start_cust, 20, 32, "inbox") ?>
			<A HREF="javascript:openCustBrowse('start_cust')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Customer#</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_cust", $end_cust, 20, 32, "inbox") ?>
			  <A HREF="javascript:openCustBrowse('end_cust')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" height="5"></td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start zip</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_zip", $start_zip, 20, 32, "inbox") ?></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End zip</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_zip", $end_zip, 20, 32, "inbox") ?></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" height="5"></td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Phone</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_tel", $start_tel, 20, 32, "inbox") ?></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Phone</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_tel", $end_tel, 20, 32, "inbox") ?></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" height="5"></td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Points</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_points", $start_points, 20, 32, "inbox") ?></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Points</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_points", $end_points, 20, 32, "inbox") ?></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" height="5"></td>
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
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Sort By</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
		        <SELECT NAME="sby">
			        <option value="c">Customer#</option>
			        <option value="n">Name</option>
			        <option value="z">Printer</option>
			        <option value="p">Phone</option>
			        <option value="e">Email</option>
			        <option value="a">Aging</option>
			      </SELECT>
          </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Report!"></td>
        </tr>
	    </FORM>
      </table></td>
    </tr>
 </table>
