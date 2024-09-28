<?php

	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.itemunits.php");
	include_once("class/class.category.php");
	include_once("class/class.unit_measures.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");
 	include_once("class/class.vendors.php");
	include_once("class/class.materials.php");
	include_once("class/class.productlines.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/map.default.php");

	$vars = array("item_vend_code","item_material","item_prod_line","item_vend_prod_code","item_vend_prod_name", "ty","ft","rv","item_code","dir");
	foreach ($vars as $var) {
		$$var = "";
	}
	$vars = array("pg","cn");
	foreach ($vars as $var) {
		$$var = 0;
	}

	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

$olds = $default["comp_code"]."_olds";

?>
<html>
<head>
<title>Item</title>
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

	function updateField(fldname) {
		var f = document.forms[0];
		var g = eval("f."+fldname);
		if (g.value != "") {
			f.method = 'post';
			f.action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
			f.submit();
		}
	}

	var vendBrowse;
	function openVendBrowse(objname)  {
		if (vendBrowse && !vendBrowse.closed) vendBrowse.close();
		vendBrowse = window.open("vendors_popup.php?objname="+objname, "vendBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		vendBrowse.focus();
		vendBrowse.moveTo(100,100);
	}

	var vendFilterBrowse;
	function openVendBrowseFilter(objname, filtertext) {
		if (vendFilterBrowse && !vendFilterBrowse.closed) vendFilterBrowse.close();
		var url = "vendors_popup.php?objname="+objname+"&cn=code&ft="+filtertext;
		vendFilterBrowse = window.open(url, "vendFilterBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		vendFilterBrowse.focus();
		vendFilterBrowse.moveTo(110,110);
	}

	var materialBrowse;
	function openMaterialBrowse(objname)  {
		if (materialBrowse && !materialBrowse.closed) materialBrowse.close();
		materialBrowse = window.open("material_popup.php?objname="+objname, "materialBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		materialBrowse.focus();
		materialBrowse.moveTo(100,100);
	}


	var materialFilterBrowse;
	function openMaterialBrowseFilter(objname, filtertext) {
		if (materialFilterBrowse && !materialFilterBrowse.closed) materialFilterBrowse.close();
		var url = "material_popup.php?objname="+objname+"&cn=code&ft="+filtertext;
		materialFilterBrowse = window.open(url, "materialFilterBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		materialFilterBrowse.focus();
		materialFilterBrowse.moveTo(110,110);
	}

	var plineBrowse;
	function openProductLineBrowse(objname)  {
		if (plineBrowse && !plineBrowse.closed) plineBrowse.close();
		plineBrowse = window.open("product_line_popup.php?objname="+objname, "plineBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		plineBrowse.focus();
		plineBrowse.moveTo(100,100);
	}


	var plineFilterBrowse;
	function openProductLineBrowseFilter(objname, filtertext) {
		if (plineFilterBrowse && !plineFilterBrowse.closed) plineFilterBrowse.close();
		var url = "product_line_popup.php?objname="+objname+"&cn=code&ft="+filtertext;
		plineFilterBrowse = window.open(url, "plineFilterBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		plineFilterBrowse.focus();
		plineFilterBrowse.moveTo(110,110);
	}

	var acctBrowse;
	function openAcctBrowse(objname)  {
		if (acctBrowse && !acctBrowse.closed) acctBrowse.close();
		acctBrowse = window.open("item_acct_popup.php?objname="+objname, "acctBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		acctBrowse.focus();
		acctBrowse.moveTo(100,100);
	}


	var acctFilterBrowse;
	function openAcctBrowseFilter(objname, filtertext) {
		if (acctFilterBrowse && !acctFilterBrowse.closed) acctFilterBrowse.close();
		var url = "item_acct_popup.php?objname="+objname+"&cn=code&ft="+filtertext;
		acctFilterBrowse = window.open(url, "acctFilterBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=520,width=750");
		acctFilterBrowse.focus();
		acctFilterBrowse.moveTo(110,110);
	}

	var historyBrowse;
	function openHistory(item)  {
		if (historyBrowse && !historyBrowse.closed) historyBrowse.close();
		historyBrowse = window.open("itemhists_popup.php?item_code="+item, "historyBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=420,width=380");
		historyBrowse.focus();
		historyBrowse.moveTo(100,100);
	}



//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php") ?></td>
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
	$c = new Items();
	$d = new Datex();
	$numrows = $c->getItemsRows();
	$t = new Category();
	$t_num = $t->getCategoryRows();
	$t_arr = $t->getCategoryList("name", "", "f", 1, $t_num);
	$catbox = array();
	for ($i=0;$i<$t_num;$i++) {
		$cat = array("value"=>"", "name"=>"");
		$cat["value"] = $t_arr[$i]["cate_code"];
		$cat["Name"] = $t_arr[$i]["cate_name1"];
		if (!empty($t_arr[$i]["cate_name2"])) $cat["Name"] .= ":".$t_arr[$i]["cate_name2"];
		if (!empty($t_arr[$i]["cate_name3"])) $cat["Name"] .= ":".$t_arr[$i]["cate_name3"];
		if (!empty($t_arr[$i]["cate_name4"])) $cat["Name"] .= ":".$t_arr[$i]["cate_name4"];
		array_push($catbox, $cat);
	}

	$vend_code = $item_vend_code;
	$mate_code = $item_material;
	$pline_code = $item_prod_line;

	$typebox =  array(
					array("value"=>"s", "name"=>"Supply"), 
					array("value"=>"e", "name"=>"Equipment"), 
					array("value"=>"p", "name"=>"Part"), 
					array("value"=>"n", "name"=>"Note"), 
					array("value"=>"m", "name"=>"Service"), 
					array("value"=>"o", "name"=>"Other")
				);

	$u = new UnitMeasures();
	$u_num = $u->getUnitMeasuresRows();
	$u_arr = $u->getUnitMeasuresList("name", "", "f", 1, $u_num);
				

	$ua = new UserAuths();
	if ($ty == "a") {
		if (!array_key_exists($olds, $_SESSION) || $_SESSION[$olds]) $_SESSION[$olds]=NULL;
		if ($oldrec = $c->getTextFields($dir, $item_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
		}
		if ($_SERVER["PHP_AUTH_UER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("items_add.php");
		else include("permission.php");

	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		if (empty($item_code)) $oldrec = $c->getFirstItems();
		else $oldrec = $c->getTextFields($dir, $item_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) {
				if (is_string($v)) {
					$$k = stripslashes($v);
				}
			}
			$_SESSION[$olds] = $oldrec;
			if (!empty($item_code)) $cnt = 1;

			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "item_edit");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("items_edit.php");
			else include("permission.php");
		} else {
			$ty = "a";
			$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "item_add");
			if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
			if ($ua_arr["userauth_allow"]=="t") include("items_add.php");
			else include("permission.php");
		}

	} else if ($ty == "v") {
		if (empty($item_code)) $oldrec = $c->getFirstItems();
		else $oldrec = $c->getTextFields($dir, $item_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($item_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "item_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("items_view.php");
		else include("permission.php");

	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "item_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("items_list.php");
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