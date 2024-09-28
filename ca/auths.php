<?php
	include_once("class/map.default.php");
	include_once("class/class.formutils.php");
	include_once("class/class.auths.php");
	
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

$comp = $default["comp_code"];
if (!is_array($_SESSION[$comp])) $_SESSION[$comp]=array();

?>
<html>
<head>
<title>Auths</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
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
   <td>
	<table width="800" border="0" cellspacing="0" cellpadding="0">
	 <tr valign="top">
	  <td width="110" bgcolor="#CCCCFF">
	    <?php include ("left_systems.php") ?> 
	  </td>
	  <td width="681" align="center">
	   <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
         <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
		</tr>
		<tr>
		 <td width="10">&nbsp;</td>
		 <td valign="top">
<?php
	$ua = new UserAuths();

	$c = new Auths();

	$numrows = $c->getAuthsRows();

	$d = new Datex();


	if ($ty == "a") {
		if ($_SESSION[$comp]["olds"]) $_SESSION[$comp]["olds"]=NULL;
		if ($olds = $c->getTextFields($dir, $auth_code)) {
			foreach ($olds as $k => $v) $$k = $v;
			$_SESSION["olds"]=$olds;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "auth_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("auths_add.php");
		else include("permission.php");

	} else if ($ty == "e") {
		if ($_SESSION[$comp]["olds"]) $_SESSION[$comp]["olds"]=NULL;
		if (empty($auth_code)) $olds = $c->getFirstAuths();
		else $olds = $c->getTextFields($dir, $auth_code);
		if ($olds) {
			foreach ($olds as $k => $v) $$k = $v;
			$_SESSION[$comp]["olds"]=$olds;
			if (!empty($auth_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "auth_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("auths_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		if (empty($auth_code)) $oldrec = $c->getFirstAuths();
		else $oldrec = $c->getTextFields($dir, $auth_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($auth_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "auth_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("auths_view.php");
		else include("permission.php");

	} else {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "auth_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("auths_list.php");
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
