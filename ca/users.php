<?php
	include_once("class/class.formutils.php");
	include_once("class/class.users.php");
	
	include_once("class/class.taxrates.php");
	
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
<title>Users</title>
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
	$c = new Users();
	$numrows = $c->getUsersRows();
	$d = new Datex();
	$ua = new UserAuths();
	if ($ty == "a") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		if ($olds = $c->getTextFields($dir, $user_code)) {
			foreach ($olds as $k => $v) $$k = $v;
      $_SESSION["olds"]=$olds;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "user_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("users_add.php");
		else include("permission.php");

	} else if ($ty == "e") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		if (empty($user_code)) $olds = $c->getFirstUsers();
		else $olds = $c->getTextFields($dir, $user_code);
		if ($olds) {
			foreach ($olds as $k => $v) $$k = $v;
      $_SESSION["olds"]=$olds;
			if (!empty($user_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "user_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("users_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		if (empty($user_code)) $oldrec = $c->getFirstUsers();
		else $oldrec = $c->getTextFields($dir, $user_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($user_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "user_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("users_view.php");
		else include("permission.php");

	} else {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "user_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("users_list.php");
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
