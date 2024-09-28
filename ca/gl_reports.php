<?php
	include_once("class/class.formutils.php");
	include_once("class/class.gledger.php");
	include_once("class/class.jrnltrxs.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.accounts.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/register_globals.php");
//------------------------------------------------------------------------

	$f = new FormUtil();

//------------------------------------------------------------------------
// Customer Screen Text View Language Select
	$lang = 'en';
	$charsetting = "iso-8859-1";
//	if ($_SERVER["PHP_AUTH_USER"] != "admin") {
//		$lang = "ch";
//		$charsetting = "gb2312";
//	}
//-----------------------------------------------------------------------

?>
<html>
<head>
<title>
General Ledger Reports
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	function goResult() {
		var f = document.forms[0];
		f.result.value = 't';
		f.submit();
	}

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
	}

	var acctBrowse;
	function openAcctBrowse(objname)  {
		if (acctBrowse && !acctBrowse.closed) acctBrowse.close();
		acctBrowse = window.open("accounts_popup.php?objname="+objname, "acctBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=400");
		acctBrowse.focus();
		acctBrowse.moveTo(100,100);
	}


//-->
</SCRIPT>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td><?php include ("top_menu.php") ?></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_generalledgers.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <form>
			  <INPUT TYPE="hidden" name="result" value="f">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF" align="right">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td colspan="3" align="center">
				Report Type : 
				  <SELECT NAME="report_page" onChange="javascript:submit()">
				    <OPTION value="">Select Report</OPTION>
				    <OPTION value="gl_trial_bal_rpt.php" <?= ($report_page=="gl_trial_bal_rpt.php")?"SELECTED":"" ?>>Trial Banance</OPTION>
				    <OPTION value="gl_comntd_acct_rpt.php" <?= ($report_page=="gl_comntd_acct_rpt.php")?"SELECTED":"" ?>>Commented Accts</OPTION>
				  </SELECT>
				  <INPUT TYPE="submit">
				</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$ua = new UserAuths();
	if ($result != "t") {
		if (empty($report_page)) {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gl_rpt_default");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("gl_default_rpt.php");
			else include("permission.php");
			
		} else {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gl_rpt_default");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("$report_page");
			else include("permission.php");
			
		}
	} else {
		if (empty($report_page)) {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gl_rpt_default");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("gl_default_rpt.php");
			else include("permission.php");
			
		} else if ($report_page=="gl_trial_bal_rpt.php") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gl_trial_bal");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("gl_trial_bal_result.php");
			else include("permission.php");
			
		} else if ($report_page=="gl_comntd_acct_rpt.php") {
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gl_comnted_acct");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("gl_comntd_acct_result.php");
			else include("permission.php");
			
		}

	}
?>
                </td>
                <td width="10">&nbsp;</td>
              </tr>
			  </form>
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
