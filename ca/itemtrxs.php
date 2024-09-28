<?php
	include_once("class/class.items.php");
	include_once("class/class.itemunits.php");
	include_once("class/class.formutils.php");
	include_once("class/class.itemtrxs.php");

	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/map.default.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

	$d = new Datex();

	$invtrx_inv_acct = 12000;

	$olds = $default["comp_code"]."_olds";
?>
<html>
<head>
<title>Item Transaction</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	function updateForm() {
		var f = document.forms[0];
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.method = "post";
		f.submit();
	}

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
	}

	var itemBrowse;
	function openItemBrowse(objname)  {
		var f = document.forms[0];
		if (itemBrowse && !itemBrowse.closed) itemBrowse.close();
		itemBrowse = window.open("itemtrx_item_popup.php?objname="+objname+"&ft="+f.invtrx_item_code.value, "itemBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=650");
		itemBrowse.focus();
		itemBrowse.moveTo(100,100);
	}

//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td>&nbsp;| <a href="sales.php"><?= $label[$lang]["Sales"] ?> </a> | <a href="purchases.php"><?= $label[$lang]["Purchases"] ?> 
            </a> | <a href="items.php"><?= $label[$lang]["Inventory"] ?></a> | <a href="accounts.php"><?= $label[$lang]["General_Ledger"] ?></a> |</td>
        </tr>
      </table></td>
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
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new ItemTrxs();
		$numrows = $c->getItemTrxsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $invtrx_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = $oldrec;
			$_SESSION["olds"] = $olds;

		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "invtrans_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("itemtrxs_add.php");
		else include("permission.php");
		
	} else if ($ty == "e") {
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new ItemTrxs();
		$numrows = $c->getItemTrxsRows();
		$d = new Datex();
		if (empty($invtrx_id)) $oldrec = $c->getFirstItemTrxs();
		else $oldrec = $c->getTextFields($dir, $invtrx_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = $oldrec;
			$_SESSION["olds"] = $olds;

			if (!empty($invtrx_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "invtrans_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("itemtrxs_edit.php");
		else include("permission.php");
		
	} else if ($ty == "v") {
		$c = new ItemTrxs();
		$numrows = $c->getItemTrxsRows();
		$d = new Datex();
		if (empty($invtrx_id)) $oldrec = $c->getFirstItemTrxs();
		else $oldrec = $c->getTextFields($dir, $invtrx_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($invtrx_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "invtrans_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("itemtrxs_view.php");
		else include("permission.php");
		
	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "invtrans_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("itemtrxs_list.php");
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