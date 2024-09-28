<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.sales.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.cmemo.php");
	include_once("class/class.cmemodtls.php");
	include_once("class/class.customers.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.taxrates.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.default.php");
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//------------------------------------------------------------------------

	$f = new FormUtil();
	$cust_code = $_SESSION["sale_cust_code"];

$cmemodtl_del = $default["comp_code"]."_cmemodtl_del";
$cmemodtl_edit = $default["comp_code"]."_cmemodtl_edit";
$cmemodtl_add = $default["comp_code"]."_cmemodtl_add";
$cmemo_edit = $default["comp_code"]."_cmemo_edit";
$cmemo_add = $default["comp_code"]."_cmemo_add";
$cmemo_filter = $default["comp_code"]."_cmemo_filter";
$olds = $default["comp_code"]."_olds";

?>
<html>
<head>
<title>Credit Memo</title>
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

	var custBrowse;
	function openCustBrowse(objname)  {
		if (custBrowse && !custBrowse.closed) {
			custBrowse.close();
		}
		custBrowse = window.open("cmemo_customers_popup.php?objname="+objname, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	function openCustBrowseFilter(objname, filtertext) {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		var url = "cmemo_customers_popup.php?objname="+objname+"&ft="+filtertext;
		custBrowse = window.open(url, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_sales.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr> 
                <td colspan="3" bgcolor="#CCFFFF" align="right">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$ua = new UserAuths();
	if ($ty == "a") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new Cmemo();
		$numrows = $c->getCmemoRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $cmemo_id)) {
			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			//print_r($comp);
			//echo "<br>";
			//print_r($_SESSION[$comp]);
			//echo "<br>";
			//print_r($oldrec);
			$_SESSION[$olds] = $oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cmemo_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cmemo_add.php");
		else include("permission.php");
	
	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$c = new Cmemo();
		$numrows = $c->getCmemoRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $cmemo_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
			if (!empty($cmemo_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cmemo_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cmemo_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		$c = new Cmemo();
		$numrows = $c->getCmemoRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $cmemo_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($cmemo_id)) $cnt = 1;
		}
		$v = new Custs();
		$varr = $v->getCusts($cmemo_cust_code);
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cmemo_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cmemo_view.php");
		else include("permission.php");

	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cmemo_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cmemo_list.php");
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
