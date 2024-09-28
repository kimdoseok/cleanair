<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.sales.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.customers.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.taxrates.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");

	$vars = array("ty","start_item","end_item","start_vendor","end_vendor",
				  "start_prodline","end_prodline","start_material","end_material");
	foreach ($vars as $var) {
		$$var = "";
	} 
	$vars = array("pg","cn");
	foreach ($vars as $var) {
		$$var = 0;
	} 

	include_once("class/register_globals.php");
//------------------------------------------------------------------------

	$f = new FormUtil();
	$cust_code = "";
	if (array_key_exists("sale_cust_code", $_SESSION)) {
		$cust_code = $_SESSION["sale_cust_code"];
	}
	
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
//-----------------------------------------------------------------------
//echo $_SESSION[saledtls_edit];

?>
<html>
<head>
<title>Item Reports</title>
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


	var itemBrowse;
	function openItemBrowse(objname) {
		if (itemBrowse && !itemBrowse.closed) itemBrowse.close();
		var url = "report_items_popup.php?objname="+objname;
		itemBrowse = window.open(url, "itemBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
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

	var vendorBrowse;
	function openVendorBrowse(objname) {
		if (vendorBrowse && !vendorBrowse.closed) vendorBrowse.close();
		var url = "report_vendor_popup.php?objname="+objname;
		vendorBrowse = window.open(url, "vendorBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=750");
		vendorBrowse.focus();
		vendorBrowse.moveTo(110,110);
	}

	function openVendorBrowseFilter(objname, filtertext) {
		if (vendorBrowse && !vendorBrowse.closed) vendorBrowse.close();
		var url = "report_vendor_popup.php?objname="+objname+"&ft="+filtertext;
		vendorBrowse = window.open(url, "vendorBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=750");
		vendorBrowse.focus();
		vendorBrowse.moveTo(110,110);
	}

	var prodLineBrowse;
	function openProdLineBrowse(objname) {
		if (prodLineBrowse && !prodLineBrowse.closed) prodLineBrowse.close();
		var url = "report_prodline_popup.php?objname="+objname;
		prodLineBrowse = window.open(url, "prodLineBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		prodLineBrowse.focus();
		prodLineBrowse.moveTo(110,110);
	}

	function openProdLineBrowseFilter(objname, filtertext) {
		if (prodLineBrowse && !prodLineBrowse.closed) prodLineBrowse.close();
		var url = "report_prodline_popup.php?objname="+objname+"&ft="+filtertext;
		prodLineBrowse = window.open(url, "prodLineBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		prodLineBrowse.focus();
		prodLineBrowse.moveTo(110,110);
	}

	var materialBrowse;
	function openMaterialBrowse(objname) {
		if (materialBrowse && !materialBrowse.closed) materialBrowse.close();
		var url = "report_material_popup.php?objname="+objname;
		materialBrowse = window.open(url, "materialBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		materialBrowse.focus();
		materialBrowse.moveTo(110,110);
	}

	function openMaterialBrowseFilter(objname, filtertext) {
		if (materialBrowse && !materialBrowse.closed) materialBrowse.close();
		var url = "report_material_popup.php?objname="+objname+"&ft="+filtertext;
		materialBrowse = window.open(url, "materialBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		materialBrowse.focus();
		materialBrowse.moveTo(110,110);
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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_items.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr> 
                <td colspan="3" bgcolor="#CCFFFF" align="right">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$d = new Datex();
	$c = new Custs();
	$s = new Sales();

	if ($ty == "ig") {
		include("report_filtered_item.php");
	} else if ($ty == "si") {
		//include("report_sales_by_item.php");
	} else if ($ty == "sc") {
		//include("report_sales_by_category.php");
	} else if ($ty == "scu") {
		//include("report_sales_by_customer.php");
	} else if ($ty == "stny") {
		//include("report_sales_tax.php");
	} else  {
		include("item_default_rpt.php");
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
