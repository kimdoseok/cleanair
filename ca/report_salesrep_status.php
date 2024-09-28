<?php
include_once ("class/class.formutils.php");

$vars = array(
	"start_rep",
	"end_rep",
);
foreach ($vars as $var) {
	$$var = "";
}
$vars = array("pg", "cn");
foreach ($vars as $var) {
	$$var = 0;
}

include_once ("class/register_globals.php");

$f = new FormUtil();
$last_day = date("t");
$month = date("m");
$year = date("Y");
if (empty($start_date))
	$start_date = "$month/1/$year";
if (empty($end_date))
	$end_date = "$month/$last_day/$year";
if (empty($cutoff_date))
	$cutoff_date = date("m/d/Y");

?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
	<tr align="right">
		<td><strong>Sales Rep. Status Report</strong></td>
	</tr>
	<tr>
		<td align="left">
			<font size="2">&nbsp; </font>
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<FORM METHOD=POST ACTION="report_salesrep_status_out.php">
					<INPUT TYPE="hidden" NAME="ty" VALUE="srsr">
					<tr>
						<td width="10%">&nbsp;</td>
						<td width="29%" bgcolor="silver">Start Date</td>
						<td width="1%">&nbsp;</td>
						<td width="50%"><?= $f->fillTextBox("start_date", $start_date, 20, 32, "inbox") ?>
							<a href="javascript:openCalendar('start_date')">C</a>
						</td>
						<td width="10%">&nbsp;</td>
					</tr>
					<tr>
						<td width="10%">&nbsp;</td>
						<td width="29%" bgcolor="silver">End Date</td>
						<td width="1%">&nbsp;</td>
						<td width="50%"><?= $f->fillTextBox("end_date", $end_date, 20, 32, "inbox") ?>
							<a href="javascript:openCalendar('end_date')">C</a>
						</td>
						<td width="10%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td width="10%">&nbsp;</td>
						<td width="29%" bgcolor="silver">Cutoff Date</td>
						<td width="1%">&nbsp;</td>
						<td width="50%"><?= $f->fillTextBox("cutoff_date", $cutoff_date, 20, 32, "inbox") ?>
							<a href="javascript:openCalendar('cutoff_date')">C</a>
						</td>
						<td width="10%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td width="10%">&nbsp;</td>
						<td width="29%" bgcolor="silver">Sort</td>
						<td width="1%">&nbsp;</td>
						<td width="50%">
							<SELECT NAME="sort">
								<option value="rep">Sales Rep.</option>
								<option value="cust">Customer</option>
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
						<td width="10%">&nbsp;</td>
						<td width="29%" bgcolor="silver">Start Sales Rep.</td>
						<td width="1%">&nbsp;</td>
						<td width="50%"><?= $f->fillTextBox("start_rep", $start_rep, 20, 32, "inbox") ?>
							<A HREF="javascript:openSalesRepBrowse('start_rep')">Lookup</a>
						</td>
						<td width="10%">&nbsp;</td>
					</tr>
					<tr>
						<td width="10%">&nbsp;</td>
						<td width="29%" bgcolor="silver">End Sales Rep.</td>
						<td width="1%">&nbsp;</td>
						<td width="50%"><?= $f->fillTextBox("end_rep", $end_rep, 20, 32, "inbox") ?>
							<A HREF="javascript:openSalesRepBrowse('end_rep')">Lookup</a>
						</td>
						<td width="10%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<!--
							<tr>
							  <td width="10%">&nbsp;</td>
							  <td width="29%" bgcolor="silver">Statement Type</td>
							  <td width="1%">&nbsp;</td>
							  <td width="50%">
								<INPUT TYPE="radio" NAME="details" value="s" <?= ($details != "d") ? "CHECKED" : "" ?>>Summary
								<INPUT TYPE="radio" NAME="details" value="d" <?= ($details == "d") ? "CHECKED" : "" ?>>Detail
							  </td>
							  <td width="10%">&nbsp;</td>
							</tr>
							<tr>
							  <td colspan="4">&nbsp;</td>
							</tr>
-->
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
								<option value="html">HTML</option>
								<option value="pdf">PDF</option>
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
						<td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Proceed"></td>
					</tr>
				</FORM>
			</table>
		</td>
	</tr>
</table>
</td>