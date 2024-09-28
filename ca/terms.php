<?php
	include_once("class/class.formutils.php");
	include_once("class/class.terms.php");
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
<title>Terms</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php"); ?></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_sales.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$c = new Terms();
	$d = new Datex();
	$ua = new UserAuths();
	if ($ty == "a") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$numrows = $c->getTermsRows();
		if ($oldrec = $c->getTextFields($dir, $term_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = $oldrec;
      $_SESSION["olds"]=$olds;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "term_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("terms_add.php");
		else include("permission.php");

	} else if ($ty == "e") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$numrows = $c->getTermsRows();
		if (empty($term_code)) $oldrec = $c->getFirstTerms();
		else $oldrec = $c->getTextFields($dir, $term_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = $oldrec;
      $_SESSION["olds"]=$olds;
			if (!empty($term_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "term_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("terms_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		$numrows = $c->getTermsRows();
		if (empty($term_code)) $oldrec = $c->getFirstTerms();
		else $oldrec = $c->getTextFields($dir, $term_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($term_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "term_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("terms_view.php");
		else include("permission.php");

	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "term_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("terms_list.php");
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