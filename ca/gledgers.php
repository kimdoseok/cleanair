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
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

$jrnltrxs_edit = $default["comp_code"]."_jrnltrxs_edit";
$jrnltrxs_add = $default["comp_code"]."_jrnltrxs_add";
$gledger_edit = $default["comp_code"]."_gledger_edit";
$gledger_add = $default["comp_code"]."_gledger_add";

?>
<html>
<head>
<title>
General Ledgers
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	var acctBrowse;
	function openAcctBrowse(objname)  {
		if (!acctBrowse || acctBrowse.closed) {
			acctBrowse = window.open("gledgers_account_popup.php?objname="+objname, "acctBrowseWin", "height=500, width=350");
		} else {
			acctBrowse.focus();
		}
		acctBrowse.moveTo(100,100);
	}

	function applDtl() {
		var f = document.forms[0];
		if (f.jrnltrx_amt.value == "" || f.jrnltrx_amt.value == 0) {
			window.alert("Amount should be more than 0!");
		} else if (f.jrnltrx_acct_code.value == "") {
			window.alert("Account number should not be blank!");
		} else {
			f.cmd.value = "gldgr_detail_sess_apply";
			f.method = "post";
			f.action = "gl_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "gldgr_detail_sess_del";
		f.method = "get";
		f.action = "gl_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.gldgr_date.value == "") {
			window.alert("Date should not be blank!");
		} else if (parseFloat(f.balance.value) != 0) {
			window.alert("Balance should be zero!");
		} else {
			f.cmd.value = "gldgr_apply";
			f.method = "post";
			f.action = "gl_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "gldgr_sess_clear";
		f.method = "post";
		f.action = "gl_proc.php";
		f.submit();
	}

	function updateHead() {
		var f = document.forms[0];
		f.action = "gl_proc.php";
		f.cmd.value = "gldgr_sess_apply";
		f.method = "post";
		f.submit();
	}

	function clearDtl() {
		window.location = "<?= htmlentities($_SERVER['PHP_SELF'])."?ty=$ty&gldgr_id=$gldgr_id" ?>";
	}

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
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
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$ua = new UserAuths();
	if ($ty == "a") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new GLedger();
		$numrows = $c->getGLedgerRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $gldgr_id)) {
			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			$_SESSION[$olds] = $oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gledger_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("gledger_add.php");
		else include("permission.php");
		
	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new GLedger();
		$j = new JrnlTrxs();
		$numrows = $c->getGLedgerRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $gldgr_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
			if (!empty($gldgr_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gledger_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("gledger_edit.php");
		else include("permission.php");
		
	} else if ($ty == "v") {
		$c = new GLedger();
		$j = new JrnlTrxs();
		$numrows = $c->getGLedgerRows();
		$oldrec = $c->getTextFields($dir, $gldgr_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($gldgr_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gledger_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("gledger_view.php");
		else include("permission.php");
		
	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "gledger_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("gledger_list.php");
		else include("permission.php");
		
	}
?>
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
