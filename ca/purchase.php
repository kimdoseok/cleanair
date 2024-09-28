<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.purchase.php");
	include_once("class/class.purdtls.php");
	include_once("class/class.vendors.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.taxrates.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");

	$vars = array("ty","cmd","dir","page","purch_vend_code_old");
	foreach ($vars as $var) {
		$$var = "";
	} 
	$vars = array("purch_taxrate","purch_id","purdtl_del");
	foreach ($vars as $var) {
		$$var = 0;
	} 
	 
	include_once("class/register_globals.php");

	//------------------------------------------------------------------------

	$f = new FormUtil();

	$vend_code = "";
	if (array_key_exists("purch_vend_code", $_SESSION)) {
		$vend_code = $_SESSION["purch_vend_code"];
	}

//------------------------------------------------------------------------
	include_once("class/map.lang.php");
//-----------------------------------------------------------------------
//echo $_SESSION["purdtls_edit"];
?>
<html>
<head>
<title>Purchase Order</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

	function updateForm() {
		var f = document.forms[0];
		f.method = 'post';
		f.action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.submit();
	}

	var rcvdBrowse;
	function openRcvdBrowse(code) {
		if (rcvdBrowse && !rcvdBrowse.closed) rcvdBrowse.close();
		rcvdBrowse = window.open("purcv_popup.php?purcv_purdtl_id="+code, "rcvdBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=340,width=400");
		rcvdBrowse.focus();
		rcvdBrowse.moveTo(100,100);
	}

	var vendBrowse;
	function openVendBrowse(objname)  {
		if (vendBrowse && !vendBrowse.closed) vendBrowse.close();
		vendBrowse = window.open("purchase_vendors_popup.php?objname="+objname, "vendBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		vendBrowse.focus();
		vendBrowse.moveTo(100,100);
	}

	var vendFilterBrowse;
	function openVendBrowseFilter(objname, filtertext) {
		if (vendFilterBrowse && !vendFilterBrowse.closed) vendFilterBrowse.close();
		var url = "purchase_vendors_popup.php?objname="+objname+"&cn=code&ft="+filtertext;
		vendFilterBrowse = window.open(url, "vendFilterBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		vendFilterBrowse.focus();
		vendFilterBrowse.moveTo(110,110);
	}

	var custBrowse;
	function openCustBrowse(objname, ty)  {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		custBrowse = window.open("purchase_customers_popup.php?ty="+ty+"&objname="+objname, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	var custFilterBrowse;
	function openCustBrowseFilter(objname, filtertext, ty) {
		if (custFilterBrowse && !custFilterBrowse.closed) custFilterBrowse.close();
		var url = "purchase_customers_popup.php?ty="+ty+"&objname="+objname+"&cn=code&ft="+filtertext;
		custFilterBrowse = window.open(url, "custFilterBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		custFilterBrowse.focus();
		custFilterBrowse.moveTo(100,100);
	}

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
	}

	function setCursor(field) {
		var f = document.forms[0];
		var fn = "document.forms[0].";
		var fv = eval(fn+field+".focus()");
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
          <td width="110" bgcolor="#CCCCFF">&nbsp;<br> <?php include ("left_purchases.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr> 
                <td colspan="3" bgcolor="#CCFFFF" align="right">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php

	$ua = new UserAuths();
	$d = new Datex();
	if ($ty == "a") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$c = new Purchases();
		$numrows = $c->getPurchaseRows();
		if ($oldrec = $c->getTextFields($dir, $purch_id)) {
			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			$_SESSION["olds"] = $oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("purchase_add.php");
		else include("permission.php");
	
	} else if ($ty == "e") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$c = new Purchases();
		$numrows = $c->getPurchaseRows();
		if ($oldrec = $c->getTextFields($dir, $purch_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
			if (!empty($purch_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("purchase_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		$c = new Purchases();
		$numrows = $c->getPurchaseRows();
		$oldrec = $c->getTextFields($dir, $purch_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($purch_id)) $cnt = 1;
		}
		$v = new Vends();
		$varr = $v->getVends($purch_vend_code);

		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("purchase_view.php");
		else include("permission.php");

	} else  {
		$c = new Purchases();
		$v = new Vends();
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("purchase_list.php");
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
