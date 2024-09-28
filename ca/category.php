<?php

	include_once("class/class.formutils.php");
	include_once("class/class.category.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");
//------------------------------------------------------------------------
	include_once("class/map.default.php");
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();

//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

$last_value = $default["comp_code"]."_last_value";
$olds = $default["comp_code"]."_olds";
?>
<html>
<head>
<title>Category</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
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
    <td> <?php include("top_menu.php") ?></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_items.php") ?> </td>
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
		$c = new Category();
		$numrows = $c->getCategoryRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $cate_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds]=$oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "category_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("category_add.php");
		else include("permission.php");
		
	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new Category();
		$numrows = $c->getCategoryRows();
		$d = new Datex();
		if (empty($cate_code)) $oldrec = $c->getFirstCategory();
		else $oldrec = $c->getTextFields($dir, $cate_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds]=$oldrec;
			if (!empty($cate_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "category_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("category_edit.php");
		else include("permission.php");
		
	} else if ($ty == "v") {
		$c = new Category();
		$numrows = $c->getCategoryRows();
		$d = new Datex();
		if (empty($cate_code)) $oldrec = $c->getFirstCategory();
		else $oldrec = $c->getTextFields($dir, $cate_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($cate_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "category_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("category_view.php");
		else include("permission.php");
		
	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "category_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("category_list.php");
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
</body>
</html>