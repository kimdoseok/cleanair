<?php
	include_once("class/class.formutils.php");
	include_once("class/class.jrnltrxs.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

?>
<html>
<head>
<title>Transaction Journal</title>
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
		updateFilter();
	}

	function setPaste(name) {
		var f = eval('document.forms[0].'+name);
		f.value = '<?= $_SESSION["last_value"] ?>';
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
/*
	if ($ty == "a") {
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new Accts();
		$numrows = $c->getAcctsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $acct_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = base64_encode(serialize($oldrec));
			session_register("olds");
		}
		include("jrnltrxs_add.php");
	} else if ($ty == "e") {
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new Accts();
		$numrows = $c->getAcctsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $acct_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = base64_encode(serialize($oldrec));
			session_register("olds");
			if (!empty($acct_code)) $cnt = 1;
		}
		include("jrnltrxs_edit.php");
	} else if ($ty == "v") {
		$c = new Accts();
		$numrows = $c->getAcctsRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $acct_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($acct_code)) $cnt = 1;
		}
		include("jrnltrxs_view.php");
	} else  {
		include("jrnltrxs_list.php");
	}
*/
	$ua = new UserAuths();
	$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "jrnl_list");
	if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
	if ($ua_arr["userauth_allow"]=="t") include("jrnltrxs_list.php");
	else include("permission.php");
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