<?php
	include_once("class/class.formutils.php");
	include_once("class/class.picks.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.items.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");



//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

	if (!empty($pickdtl_po_no)) $po_no = $pickdtl_po_no;

?>
<html>
<head>
<title><?= $label[$lang]["Sales_Detail"] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">

<!--
	function updateForm() {
		var f = document.forms[0];
		f.action = '<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.method = 'get';
		f.submit();
	}

	var itemBrowse;

	function openItemBrowse(objname) {
		if (itemBrowse && !itemBrowse.closed) itemBrowse.close();
		itemBrowse = window.open("picking_items_popup.php?objname="+objname, "itemBrowseWin", "height=450,width=350,resizable=yes");
		itemBrowse.focus();
		itemBrowse.moveTo(100,100);
	}


	var itemHistBrowse;

	function openItemHistBrowse(cust) {
		var f = document.forms[0];
		if (f.pickdtl_item_code.value == "") {
			window.alert("Item Code should be set to see item's history");
		} else {
			if (itemHistBrowse && !itemHistBrowse.closed) itemHistBrowse.close();
			itemHistBrowse = window.open("picking_itemhist_popup.php?pick_cust_code="+cust+"&pickdtl_item_code="+f.pickdtl_item_code.value, "itemHistBrowseWin", "height=450,width=350,resizable=yes");
			itemHistBrowse.focus();
			itemHistBrowse.moveTo(100,100);
		}
	}


	function calcAmt() {
		var f = document.forms[0];
		var q = Math.round(parseFloat(f.pickdtl_qty.value)*100)/100;
		var c = Math.round(parseFloat(f.pickdtl_cost.value)*1000)/1000;
		f.amount.value = Math.round(q * c * 100)/100;
	}


	function calcPrc() {
		var f = document.forms[0];
		var a = Math.round(parseFloat(f.amount.value)*100)/100;
		var q = Math.round(parseFloat(f.pickdtl_qty.value)*100)/100;
		f.pickdtl_cost.value = Math.round(a / q * 100)/100;
	}
//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td> 
		<?php include("top_menu.php"); ?>
	</td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_sales.php") ?> </td>
          <td width="681" align="center">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$c = new PickDtls();
		$numrows = $c->getPickDtlsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $pickdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = $oldrec;
      $_SESSION["olds"]=$olds;
		}
		include("picking_detail_add.php");
	} else if ($ty == "e") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$c = new PickDtls();
		$numrows = $c->getPickDtlsRows($purch_id);
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $pickdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = $oldrec;
      $_SESSION["olds"]=$olds;
			if (!empty($pickdtl_id)) $cnt = 1;
		}

		include("picking_detail_edit.php");
	} else if ($ty == "v") {
		$c = new PickDtls();
		$numrows = $c->getPickDtlsRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $pickdtl_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($pickdtl_id)) $cnt = 1;
		}
		include("picking_detail_view.php");
	} else  {
		include("picking_detail_list.php");
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
</body>
</html>
