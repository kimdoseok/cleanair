<?php
	include_once("class/class.formutils.php");
	$f = new FormUtil();
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong><?= "General Ledger Commented Account" ?></strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
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
          <td width="29%" bgcolor="silver">Start Acct.</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_acct", $start_acct, 20, 32, "inbox") ?>
		  <A HREF="javascript:openAcctBrowse('start_acct')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Acct.</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_acct", $end_acct, 20, 32, "inbox") ?>
		  <A HREF="javascript:openAcctBrowse('end_acct')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><INPUT TYPE="button" name="process" value="Generate Report" onClick="goResult()"></td>
        </tr>
      </table></td>
    </tr>
 </table>
