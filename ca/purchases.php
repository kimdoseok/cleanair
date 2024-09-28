<?php
	include_once("class/class.formutils.php");
	include_once("class/class.purchases.php");
	include_once("class/class.purdtls.php");
	include_once("class/class.vendors.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------
$f = new FormUtil();


?>
<html>
<head>
<title><?= $label[$lang]["Purchases"] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	var vendBrowse;
	function openVendBrowse(objname)  {
		if (!vendBrowse || vendBrowse.closed) {
			vendBrowse = window.open("vendors_popup.php?objname="+objname, "vendBrowseWin", "height=450, width=350");
		} else {
			vendBrowse.focus();
		}
		vendBrowse.moveTo(100,100);
	}

	function firstWork() {
<?php
	if ($ty == "a") echo "document.forms[0].purch_vend_code.focus();";
	else if ($ty == "e") echo "document.forms[0].purch_vend_code.select();";
?>
	}
//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="firstWork()">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php") ?> </td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_purchases.php") ?> </td>
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
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new Purchases();
		$numrows = $c->getPurchasesRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $purch_id)) {
			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			$olds = base64_encode(serialize($oldrec));
			$_SESSION["olds"] = $olds;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("purchases_add.php");
		else include("permission.php");
		
	} else if ($ty == "e") {
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new Purchases();
		$numrows = $c->getPurchasesRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $purch_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = base64_encode(serialize($oldrec));
			$_SESSION["olds"] = $olds;

			if (!empty($purch_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("purchases_edit.php");
		else include("permission.php");
		
	} else if ($ty == "v") {
		$c = new Purchases();
		$numrows = $c->getPurchasesRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $purch_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($purch_id)) $cnt = 1;
		}
		$v = new Vends();
		$varr = $v->getVends($purch_vend_code);

		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("purchases_view.php");
		else include("permission.php");
		
	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("purchases_list.php");
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
