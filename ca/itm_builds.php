<?php
	include_once("class/class.formutils.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");
	include_once("class/class.items.php");
	include_once("class/class.itm_builds.php");
	include_once("class/class.itm_buildtls.php");

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
<title>Item Builds</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">

<!--

	function AddDtl() {
		var f = document.forms[0];
		if (f.itmbuild_name.value == "") {
			window.alert("Name should not be blank!");
		} else {
			f.cmd.value = "itmblds_sess_add";
			f.method = "post";
			f.action = "itm_builds_proc.php";
			f.submit();
		}
	}


	function delDtl(ht, did) {
		var f = document.forms[0];
		var agree=confirm("Are you sure you wish to continue?");
		if (agree) window.location='itm_builds_proc.php?ty='+ht+'&cmd=itmblds_detail_sess_del&did='+did;
	}


	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "itmblds_clear_sess_add";
		f.method = "post";
		f.action = "itm_builds_proc.php";
		f.submit();
	}


	function printOpen(code, pr) {
		if (code != "" && pr == "t") {
			var url = 'styles_print.php?itmbuild_id='+code;
			var printWin=window.open(url, 'pbml_win','toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=yes,width=615,height=550');
			printWin.focus();

		}
	}


	function memoOpen(code) {
		if (code != "") {
			var url = 'memos.php?mt=styl&code='+code;
			var memoWin=window.open(url, 'memo_win','toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=yes,width=400,height=240');
			memoWin.focus();
		}
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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_items.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$ua = new UserAuths();
	if ($ty == "a") {
		if ($_SESSION["olds"]) $_SESSION["olds"]=NULL;
		$c = new ItmBuilds();
		$numrows = $c->getItmBuildsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $itmbuild_id)) {
			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			$_SESSION["olds"] = $oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "itmblds_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("itm_builds_add.php");
		else include("permission.php");
		
	} else if ($ty == "e") {
		if ($_SESSION["olds"]) $_SESSION["olds"]=NULL;
		$c = new ItmBuilds();
		$numrows = $c->getItmBuildsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $itmbuild_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
			if (!empty($itmbuild_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "itmblds_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("itm_builds_edit.php");
		else include("permission.php");
		
	} else if ($ty == "v") {
		$c = new ItmBuilds();
		$numrows = $c->getItmBuildsRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $itmbuild_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($itmbuild_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "itmblds_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("itm_builds_view.php");
		else include("permission.php");
		
	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "itmblds_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("itm_builds_list.php");
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
