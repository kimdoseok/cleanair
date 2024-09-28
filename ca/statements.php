<?php
//	if ($ty == "r") header("Location: statements_result.php?method=$method&start_cust=$start_cust&start_date=$start_date&end_date=$end_date&start_cust=$start_cust&end_cust=$end_cust&zero_balance=$zero_balance");

	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.sales.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.customers.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.taxrates.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	$vars = array("start_cust","end_cust","details");
	foreach ($vars as $var) {
		$$var = "";
	} 


	include_once("class/register_globals.php");

	$f = new FormUtil();
	if (array_key_exists( "sale_cust_code", $_SESSION)) {
		$cust_code = $_SESSION["sale_cust_code"];
	} else {
		$cust_code = "";
		$_SESSION["sale_cust_code"] = "";
	}
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
//-----------------------------------------------------------------------
//echo $_SESSION[saledtls_edit];

	$f = new FormUtil();
	$last_day=date("t");
	$month = date("m");
	$year = date("Y");
	if (empty($start_date)) $start_date = "$month/1/$year";
	if (empty($end_date)) $end_date = "$month/$last_day/$year";
	if (empty($stmt_date)) $stmt_date = date("m/d/Y");

?>
<html>
<head>
<title>Statements</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

	function updateForm() {
		var f = document.forms[0];
		f.method = 'post';
		f.action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.submit();
	}

	var custBrowse;
	function openCustBrowse(objname)  {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		var ft = eval("document.forms[0]."+objname)
		var url = "customers_popup.php?objname="+objname+"&ft="+ft.value;
		custBrowse = window.open(url, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	function openCustBrowseFilter(objname, filtertext) {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		var url = "customers_popup.php?objname="+objname+"&ft="+filtertext;
		custBrowse = window.open(url, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
	}

	function setCursor(field) {
		var f = document.forms[0];
		var fn = "document.forms[0].";
		var fv = eval(fn+field+".focus()");
	}

//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php") ?> </td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_sales.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr> 
                <td colspan="3" bgcolor="#CCFFFF" align="right">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
					<table width="100%" border="0" cellspacing="1" cellpadding="0">
					  <tr align="right"> 
						<td><strong>Statements</strong></td>
					  </tr>
					  <tr>
						<td align="left"><font size="2">&nbsp; </font> 
						  <table width="100%" border="0" cellspacing="1" cellpadding="1">
							<FORM METHOD=POST ACTION="statements_out.php">
							<tr>
							  <td width="10%">&nbsp;</td>
							  <td width="29%" bgcolor="silver">Statement Date</td>
							  <td width="1%">&nbsp;</td>
							  <td width="50%"><?= $f->fillTextBox("stmt_date", $stmt_date, 20, 32, "inbox") ?>
								  <a href="javascript:openCalendar('stmt_date')">C</a></td>
							  <td width="10%">&nbsp;</td>
							</tr>
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
							  <td width="29%" bgcolor="silver">Statement Type</td>
							  <td width="1%">&nbsp;</td>
							  <td width="50%">
							    <INPUT TYPE="radio" NAME="details" value="s" <?= ($details!="d")?"CHECKED":"" ?>>Summary
								<INPUT TYPE="radio" NAME="details" value="d" <?= ($details=="d")?"CHECKED":"" ?>>Detail
								<INPUT TYPE="radio" NAME="details" value="l" <?= ($details=="l")?"CHECKED":"" ?>>List
							  </td>
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
							  <td width="10%">&nbsp;</td>
							  <td width="29%" bgcolor="silver">Date Sort</td>
							  <td width="1%">&nbsp;</td>
							  <td width="50%"><INPUT TYPE="checkbox" NAME="date_sort" value="True" checked></td>
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
							  <td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Generate Statement"></td>
							</tr>
							</FORM>
						  </table></td>
						</tr>
					 </table>
                  </td>
                <td width="10">&nbsp;</td>
              </tr>
            </table></td>
          <td width="10" bgcolor="#CCCCFF">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr bgcolor="#6666FF"> 
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
