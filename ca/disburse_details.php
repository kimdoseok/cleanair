<?php
	include_once("class/class.formutils.php");
	include_once("class/class.disburdtls.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.default.php");
	include_once("class/register_globals.php");
//------------------------------------------------------------------------

$disburdtls_del = $default["comp_code"]."_disburdtls_del";
$disburdtls_edit = $default["comp_code"]."_disburdtls_edit";
$disburdtls_add = $default["comp_code"]."_disburdtls_add";
$disburse_edit = $default["comp_code"]."_disburse_edit";
$disburse_add = $default["comp_code"]."_disburse_add";
$olds = $default["comp_code"]."_olds";
$lastdisb = $default["comp_code"]."_lastdisb";

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
<title>Disbursement_Detail</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	var acctBrowse;
	function openAcctBrowse(objname)  {
		if (!acctBrowse || acctBrowse.closed) {
			acctBrowse = window.open("accounts_popup.php?objname="+objname, "acctBrowseWin", "height=450, width=350");
		} else {
			acctBrowse.focus();
		}
		acctBrowse.moveTo(100,100);
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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_purchases.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new DisburDtls();
		$numrows = $c->getDisburDtlsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $disburdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds]=$oldrec;
		}
		include("disburse_detail_add.php");

	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new DisburDtls();
		$numrows = $c->getDisburDtlsRows($purch_id);
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $disburdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds]=$oldrec;
			if (!empty($disburdtl_id)) $cnt = 1;
		}

		include("disburse_detail_edit.php");
	} else if ($ty == "v") {
		$c = new DisburDtls();
		$numrows = $c->getDisburDtlsRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $disburdtl_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($disburdtl_id)) $cnt = 1;
		}
		include("disburse_detail_view.php");
	} else  {
		include("disburse_detail_list.php");
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
