<?php
	include_once("class/class.formutils.php");
	include_once("class/class.disburse.php");
	include_once("class/class.disburdtls.php");
	include_once("class/class.vendors.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.default.php");
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//------------------------------------------------------------------------

	$f = new FormUtil();

$disburdtls_del = $default["comp_code"]."_disburdtls_del";
$disburdtls_edit = $default["comp_code"]."_disburdtls_edit";
$disburdtls_add = $default["comp_code"]."_disburdtls_add";
$disburse_edit = $default["comp_code"]."_disburse_edit";
$disburse_add = $default["comp_code"]."_disburse_add";
$olds = $default["comp_code"]."_olds";
$lastdisb = $default["comp_code"]."_lastdisb";

?>
<html>
<head>
<title>Disbursement</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	var vendBrowse;
	function openVendBrowse(objname)  {
		if (!vendBrowse || vendBrowse.closed) {
			vendBrowse = window.open("vendors_popup.php?objname="+objname, "vendBrowseWin", "height=450, width=350");
		} else {
			vendBrowse.focus();
		}
		vendBrowse.moveTo(100,100);
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
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$ua = new UserAuths();
	if ($ty == "a") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new Disburse();
		$numrows = $c->getDisburseRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $disbur_id)) {
			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			$_SESSION[$olds]=$oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "disburse_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("disburse_add.php");
		else include("permission.php");
		
	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new Disburse();
		$numrows = $c->getDisburseRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $disbur_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds]=$oldrec;
			if (!empty($disbur_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "disburse_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("disburse_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		$c = new Disburse();
		$numrows = $c->getDisburseRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $disbur_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($disbur_id)) $cnt = 1;
		}
		$v = new Vends();
		$varr = $v->getVends($disbur_vend_code);
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "disburse_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("disburse_view.php");
		else include("permission.php");

	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "disburse_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("disburse_list.php");
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
