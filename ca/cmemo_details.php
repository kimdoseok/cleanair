<?php
	include_once("class/class.formutils.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.cmemodtls.php");
	include_once("class/class.items.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.default.php");
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//-----------------------------------------------------------------------
	$item_code = $cmemodtl_item_code;

$cmemodtl_del = $default["comp_code"]."_cmemodtl_del";
$cmemodtl_edit = $default["comp_code"]."_cmemodtl_edit";
$cmemodtl_add = $default["comp_code"]."_cmemodtl_add";
$cmemo_edit = $default["comp_code"]."_cmemo_edit";
$cmemo_add = $default["comp_code"]."_cmemo_add";
$olds = $default["comp_code"]."_olds";

?>
<html>
<head>
<title>Credit Memo Detail</title>
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

	var itemBrowse;
	function openItemBrowse(objname, cust_code)  {
		var f = document.forms[0];
		if (itemBrowse && !itemBrowse.closed) itemBrowse.close();
		itemBrowse = window.open("cmemo_items_popup.php?cmemo_cust_code="+cust_code+"&objname="+objname+"&ft="+f.cmemodtl_item_code.value, "itemBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=550");
		itemBrowse.focus();
		itemBrowse.moveTo(100,100);
	}

	var itemBrowseFilter;
	function openItemBrowseFilter(objname, filtertext, cust_code)  {
		if (itemBrowseFilter && !itemBrowseFilter.closed) itemBrowseFilter.close();
		itemBrowseFilter = window.open("cmemo_items_popup.php?cmemo_cust_code="+cust_code+"&objname="+objname+"&ft="+filtertext, "itemBrowseFltWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=550");
		itemBrowseFilter.focus();
		itemBrowseFilter.moveTo(100,100);
	}

	var itemHistBrowse;
	function openItemHistBrowse(cust)  {
		var f = document.forms[0];
		if (f.cmemodtl_item_code.value == "") {
			window.alert("Item Code should be set to see item's history");
		} else {
			if (itemHistBrowse && !itemHistBrowse.closed) itemHistBrowse.close();
			itemHistBrowse = window.open("cmemo_itemhist_popup.php?cmemo_cust_code="+cust+"&cmemodtl_item_code="+f.cmemodtl_item_code.value, "itemHistBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=450");
			itemHistBrowse.focus();
			itemHistBrowse.moveTo(100,100);
		}
	}

	function calcAmt() {
		var f = document.forms[0];
		var q = Math.round(parseFloat(f.cmemodtl_qty.value)*100)/100;
		var c = Math.round(parseFloat(f.cmemodtl_cost.value)*1000)/1000;
		f.amount.value = Math.round(q * c * 100)/100;
	}

	function calcPrc() {
		var f = document.forms[0];
		var a = Math.round(parseFloat(f.amount.value)*100)/100;
		var q = Math.round(parseFloat(f.cmemodtl_qty.value)*100)/100;
		f.cmemodtl_cost.value = Math.round(a / q * 100)/100;
	}

	function firstWork() {
		var f = document.forms[0];
//		if (f.ty.value=='a' || f.ty.value=='e') f.cmemodtl_item_code.focus();
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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_sales.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
		if ($_SESSION[$olds]) $_SESSION[$olds];
		$c = new CmemoDtl();
		$numrows = $c->getCmemoDtlRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $cmemodtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
		}
		include("cmemo_detail_add.php");
	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new CmemoDtl();
		$numrows = $c->getCmemoDtlRows($cmemodtl_id);
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $cmemodtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
			if (!empty($cmemodtl_id)) $cnt = 1;
		}

		include("cmemo_detail_edit.php");
	} else if ($ty == "v") {
		$c = new CmemoDtl();
		$numrows = $c->getCmemoDtlRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $cmemodtl_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($cmemodtl_id)) $cnt = 1;
		}
		include("cmemo_detail_view.php");
	} else  {
		include("cmemo_detail_list.php");
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
