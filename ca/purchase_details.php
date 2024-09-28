<?php
	include_once("class/class.formutils.php");
	include_once("class/class.purdtls.php");
	include_once("class/class.items.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
//-----------------------------------------------------------------------
	include_once("defaults.php");

	$vars = array("dir","cmd","old_purdtl_item_code","not_found_item","purdtl_item_code");
	foreach ($vars as $var) {
		$$var = "";
	} 
	$vars = array("purdtl_id","purch_id","ctrl","purch_taxrate");
	foreach ($vars as $var) {
		$$var = 0;
	} 

	include_once("class/register_globals.php");


	$item_code = $purdtl_item_code;
?>
<html>
<head>
<title>Purchase Detail</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	function updateForm() {
		var f = document.forms[0];
		f.action = '<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.method = 'post';
		f.submit();
	}

	function refreshItem(value) {
		var f = document.forms[0];
		f.ctrl.value = value;
		f.action = '<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.method = 'post';
		f.submit();
	}

	var itemBrowse;
	function openItemBrowse(objname)  {
		var f = document.forms[0];
		if (itemBrowse && !itemBrowse.closed) itemBrowse.close();
		itemBrowse = window.open("purchase_items_popup.php?objname="+objname+"&ft="+f.purdtl_item_code.value, "itemBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=650");
		itemBrowse.focus();
		itemBrowse.moveTo(100,100);
	}

	var itemBrowseFilter;
	function openItemBrowseFilter(objname, filtertext)  {
		if (itemBrowseFilter && !itemBrowseFilter.closed) itemBrowseFilter.close();
		itemBrowseFilter = window.open("purchase_items_popup.php?objname="+objname+"&ft="+filtertext, "itemBrowseFltWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=650");
		itemBrowseFilter.focus();
		itemBrowseFilter.moveTo(100,100);
	}

	var itemHistBrowse;
	function openItemHistBrowse(cust)  {
		var f = document.forms[0];
		if (f.purdtl_item_code.value == "") {
			window.alert("Item Code should be set to see item's history");
		} else {
			if (itemHistBrowse && !itemHistBrowse.closed) itemHistBrowse.close();
			itemHistBrowse = window.open("purchase_itemhist_popup.php?purch_vend_code="+cust+"&purdtl_item_code="+f.purdtl_item_code.value, "itemHistBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=450");
			itemHistBrowse.focus();
			itemHistBrowse.moveTo(100,100);
		}
	}

	var purHistBrowse;
	function openPurchaseHistBrowse(cust)  {
		var f = document.forms[0];
		if (cust == "") {
			window.alert("Vendor Code should be set to see this history");
		} else {
			if (purHistBrowse && !purHistBrowse.closed) purHistBrowse.close();
			purHistBrowse = window.open("purchase_hist_popup.php?purch_vend_code="+cust+"&ft="+f.purdtl_item_code.value, "purHistBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=500");
			purHistBrowse.focus();
			purHistBrowse.moveTo(120,120);
		}
	}

	function calcAmt() {
		var f = document.forms[0];
		var q = Math.round(parseFloat(f.purdtl_qty.value)*100)/100;
		var c = Math.round(parseFloat(f.purdtl_cost.value)*1000)/1000;
		f.amount.value = Math.round(q * c * 100)/100;
	}

	function calcPrc() {
		var f = document.forms[0];
		var a = Math.round(parseFloat(f.amount.value)*100)/100;
		var q = Math.round(parseFloat(f.purdtl_qty.value)*100)/100;
		f.purdtl_cost.value = Math.round(a / q * 100)/100;
	}

	function firstWork() {
		var f = document.forms[0];
//		if (f.ty.value=='a' || f.ty.value=='e') f.purdtl_item_code.focus();
	}

	function setCursor(field) {
		var f = document.forms[0];
		var fn = "document.forms[0].";
		var fv = eval(fn+field+".focus()");
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
          <td width="110" bgcolor="#CCCCFF">&nbsp;<br> <?php include ("left_purchases.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$c = new PurDtls();
		$numrows = $c->getPurDtlsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $purdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
		}
		include("purchase_detail_add.php");
	
	} else if ($ty == "e") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$c = new PurDtls();
		$numrows = $c->getPurDtlsRows($purch_id);
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $purdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
			if (!empty($purdtl_id)) $cnt = 1;
		}
		include("purchase_detail_edit.php");

	} else if ($ty == "v") {
		$c = new PurDtls();
		$numrows = $c->getPurDtlsRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $purdtl_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($purdtl_id)) $cnt = 1;
		}
		include("purchase_detail_view.php");
	} else  {
		include("purchase_detail_list.php");
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
