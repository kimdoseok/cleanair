<?php
	include_once("class/class.formutils.php");
	include_once("class/class.styldtls.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/register_globals.php");

//------------------------------------------------------------------------


	$f = new FormUtil();

//------------------------------------------------------------------------
// Customer Screen Text View Language Select
	$lang = 'en';
	$charsetting = "iso-8859-1";
	if ($_SERVER["PHP_AUTH_USER"] != "admin") {
		$lang = "ch";
		$charsetting = "gb2312";
	}
//-----------------------------------------------------------------------
?>
<html>
<head>
<title><?= $label[$lang][Style_Detail] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	var itemBrowse;
	function openItemBrowse(objname)  {
		if (!itemBrowse || itemBrowse.closed) {
			itemBrowse = window.open("items_popup.php?objname="+objname, "itemBrowseWin", "height=450,width=350,resizable=yes");
		} else {
			itemBrowse.focus();
		}
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
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new StylDtls();
		$numrows = $c->getStylDtlsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $styldtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = base64_encode(serialize($oldrec));
			$_SESSION["olds"] = $olds;
		}
		include("style_detail_add.php");
	} else if ($ty == "e") {
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new StylDtls();
		$numrows = $c->getStylDtlsRows($purch_id);
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $styldtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = base64_encode(serialize($oldrec));
			$_SESSION["olds"] = $olds;
			if (!empty($styldtl_id)) $cnt = 1;
		}

		include("style_detail_edit.php");
	} else if ($ty == "v") {
		$c = new StylDtls();
		$numrows = $c->getStylDtlsRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $styldtl_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($styldtl_id)) $cnt = 1;
		}
		include("style_detail_view.php");
	} else  {
		include("style_detail_list.php");
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
