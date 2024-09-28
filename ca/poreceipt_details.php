<?php
	include_once("class/class.formutils.php");
	include_once("class/class.porcptdtls.php");
	include_once("class/class.items.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.default.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

	$item_code = $purdtl_item_code;
	$porcptdtl_del = $default["comp_code"]."_porcptdtl_del";
	$porcptdtl_edit = $default["comp_code"]."_porcptdtl_edit";
	$porcptdtl_add = $default["comp_code"]."_porcptdtl_add";
	$porcpt_edit = $default["comp_code"]."_porcpt_edit";
	$porcpt_add = $default["comp_code"]."_porcpt_add";
	$oldds = $default["comp_code"]."_oldds";


?>
<html>
<head>
<title>PO Receiving Detail</title>
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
		var url = "poreceipt_items_popup.php?objname="+objname+"&ft="+f.porcptdtl_item_code.value;
		itemBrowse = window.open(url, "itemBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=650");
		itemBrowse.focus();
		itemBrowse.moveTo(100,100);
	}

	var itemBrowseFilter;
	function openItemBrowseFilter(objname, filtertext)  {
		if (itemBrowseFilter && !itemBrowseFilter.closed) itemBrowseFilter.close();
		itemBrowseFilter = window.open("poreceipt_items_popup.php?objname="+objname+"&ft="+filtertext, "itemBrowseFltWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=650");
		itemBrowseFilter.focus();
		itemBrowseFilter.moveTo(100,100);
	}

	var itemHistBrowse;
	function openItemHistBrowse(vend)  {
		var f = document.forms[0];
		if (f.porcptdtl_item_code.value == "") {
			window.alert("Item Code should be set to see item's history");
		} else {
			if (itemHistBrowse && !itemHistBrowse.closed) itemHistBrowse.close();
			url = "poreceipt_itemhist_popup.php?porcpt_vend_code="+vend+"&porcptdtl_item_code="+f.porcptdtl_item_code.value;
			itemHistBrowse = window.open(url, "itemHistBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=450");
			itemHistBrowse.focus();
			itemHistBrowse.moveTo(100,100);
		}
	}

	var porHistBrowse;
	function openPoReceiptHistBrowse(vend)  {
		var f = document.forms[0];
		if (vend == "") {
			window.alert("Vendor Code should be set to see this history");
		} else {
			if (porHistBrowse && !porHistBrowse.closed) porHistBrowse.close();
			url = "poreceipt_hist_popup.php?porcpt_vend_code="+vend+"&ft="+f.porcptdtl_item_code.value;
			window.alert(url);
			porHistBrowse = window.open(url, "porHistBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=500");
			porHistBrowse.focus();
			porHistBrowse.moveTo(120,120);
		}
	}

	function calcAmt() {
		var f = document.forms[0];
		var q = Math.round(parseFloat(f.porcptdtl_qty.value)*1000)/1000;
		var c = Math.round(parseFloat(f.porcptdtl_cost.value)*100)/100;
		f.amount.value = formatCurrency(Math.round(q * c * 100)/100);
	}

	function calcPrc() {
		var f = document.forms[0];
		var a = Math.round(parseFloat(f.amount.value)*100)/100;
		var q = Math.round(parseFloat(f.porcptdtl_qty.value)*100)/100;
		f.pordtl_cost.value = formatCurrency(Math.round(a / q * 100)/100);
	}

	function firstWork() {
		var f = document.forms[0];
//		if (f.ty.value=='a' || f.ty.value=='e') f.pordtl_item_code.focus();
	}

	function setCursor(field) {
		var f = document.forms[0];
		var fn = "document.forms[0].";
		var fv = eval(fn+field+".focus()");
	}

	function formatCurrency(num) {
		num = num.toString().replace(/\$|\,/g,'');
		if(isNaN(num)) num = "0";
		sign = (num == (num = Math.abs(num)));
		num = Math.floor(num*100+0.50000000001);
		cents = num%100;
		num = Math.floor(num/100).toString();
		if(cents<10) cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) num = num.substring(0,num.length-(4*i+3))+ num.substring(num.length-(4*i+3));
		return (((sign)?'':'-') + num + '.' + cents);
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
	$c = new PoRcptDtls();
	$numrows = $c->getPoRcptDtlsRows();
	if ($ty == "a") {
		if ($_SESSION[$oldds]) $_SESSION[$oldds]=NULL;
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $porcptdtl_id)) {
//			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$oldds] = $oldrec;
		}
		include("poreceipt_detail_add.php");
	
	} else if ($ty == "e") {
		if ($_SESSION[$oldds]) $_SESSION[$oldds]=NULL;
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $purdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$oldds] = $oldrec;
			if (!empty($purdtl_id)) $cnt = 1;
		}
		include("poreceipt_detail_edit.php");

	} else if ($ty == "v") {
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $purdtl_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($purdtl_id)) $cnt = 1;
		}
		include("poreceipt_detail_view.php");
	} else  {
		include("poreceipt_detail_list.php");
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
