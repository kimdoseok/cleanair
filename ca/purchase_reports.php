<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.purchase.php");
	include_once("class/class.purdtls.php");
	include_once("class/class.vendors.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.taxrates.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/register_globals.php");

	include_once("class/map.lang.php");
//------------------------------------------------------------------------

	$f = new FormUtil();
	$vend_code = $purch_vend_code;

	//echo $_SESSION["purdtls_edit"];

?>
<html>
<head>
<title>Purchase Reports</title>
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

	var vendBrowse;
	function openVendBrowse(objname)  {
		if (vendBrowse && !vendBrowse.closed) {
			vendBrowse.close();
		}
		vendBrowse = window.open("report_customer_popup.php?objname="+objname, "vendBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		vendBrowse.focus();
		vendBrowse.moveTo(100,100);
	}

	function openVendBrowseFilter(objname, filtertext) {
		if (vendBrowse && !vendBrowse.closed) vendBrowse.close();
		var url = "report_customer_popup.php?objname="+objname+"&ft="+filtertext;
		vendBrowse = window.open(url, "vendBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		vendBrowse.focus();
		vendBrowse.moveTo(100,100);
	}

	var itemBrowse;
	function openItemBrowse(objname)  {
		if (itemBrowse && !itemBrowse.closed) {
			itemBrowse.close();
		}
		itemBrowse = window.open("report_items_popup.php?objname="+objname, "itemBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		itemBrowse.focus();
		itemBrowse.moveTo(110,110);
	}

	function openItemBrowseFilter(objname, filtertext) {
		if (itemBrowse && !itemBrowse.closed) itemBrowse.close();
		var url = "report_items_popup.php?objname="+objname+"&ft="+filtertext;
		itemBrowse = window.open(url, "itemBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		itemBrowse.focus();
		itemBrowse.moveTo(110,110);
	}

	var cateBrowse;
	function openCategoryBrowse(objname)  {
		if (cateBrowse && !cateBrowse.closed) {
			cateBrowse.close();
		}
		cateBrowse = window.open("report_category_popup.php?objname="+objname, "cateBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		cateBrowse.focus();
		cateBrowse.moveTo(120,120);
	}

	function openCategoryBrowseFilter(objname, filtertext) {
		if (cateBrowse && !cateBrowse.closed) cateBrowse.close();
		var url = "report_category_popup.php?objname="+objname+"&ft="+filtertext;
		cateBrowse = window.open(url, "cateBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		cateBrowse.focus();
		cateBrowse.moveTo(120,120);
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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_purchases.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr> 
                <td colspan="3" bgcolor="#CCFFFF" align="right">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$d = new Datex();
	$c = new Vends();
	$s = new Purchases();

	if ($ty == "dr") {
		include("report_daily.php");
	} else if ($ty == "si") {
		include("report_purchase_by_item.php");
	} else if ($ty == "sc") {
		include("report_purchase_by_category.php");
	} else if ($ty == "scu") {
		include("report_purchase_by_customer.php");
	} else  {
		include("purchase_default_rpt.php");
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
