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
    <td><strong>Sales By Category Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_sales_by_category_out.php">
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
          <td width="29%" bgcolor="silver">Start Category</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_cate", $start_cate, 20, 32, "inbox") ?>
			<A HREF="javascript:openCategoryBrowse('start_cate')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Category</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_cate", $end_cate, 20, 32, "inbox") ?>
			  <A HREF="javascript:openCategoryBrowse('end_cate')">Lookup</a></td>
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
          <td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Generate Statement"></td>
        </tr>
	    </FORM>
      </table></td>
    </tr>
 </table>
