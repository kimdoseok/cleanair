<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");
	include_once("class/map.default.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

	$comp = $default["comp_code"];
	$olds = $comp."_olds";
	$last_value = $comp."_last_value";

?>
<html>
<head>
<title>Accounts</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

	function updateFilter() {
		var f = document.forms[0];
		f.method="GET";
		f.action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}

	function updateForm() {
		var f = document.forms[0];
		f.method="GET";
		f.action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}

	function setPaste(name) {
		var f = eval('document.forms[0].'+name);
		f.value = '<?= $_SESSION[$last_value] ?>';
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
		$c = new Accts();
		$numrows = $c->getAcctsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $acct_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "account_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("accounts_add.php");
		else include("permission.php");

	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new Accts();
		$numrows = $c->getAcctsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $acct_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
			if (!empty($acct_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "account_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("accounts_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		$c = new Accts();
		$numrows = $c->getAcctsRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $acct_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($acct_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "account_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("accounts_view.php");
		else include("permission.php");

	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "account_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("accounts_list.php");
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
