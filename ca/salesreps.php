<?php
	include_once("class/class.formutils.php");
	include_once("class/class.salesreps.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");

	$vars = array("ty","condition","filtertext","reverse","cust_code","slsrep_code",
				"slsrep_name","slsrep_addr1","slsrep_addr2","slsrep_addr3","slsrep_city"
				,"slsrep_state","slsrep_zip","slsrep_tel","slsrep_fax","slsrep_exp_acct"
				,"slsrep_ap_acct");
	foreach ($vars as $var) {
		$$var = "";
	}
	$vars = array("page","dir");
	foreach ($vars as $var) {
		$$var = 0;
	}

	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

?>

<html>
<head>
<title>Sales Rep.</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setPaste(name) {
		var f = eval('document.forms[0].'+name);
		f.value = '<?= $_SESSION["last_value"] ?>';
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
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
		$_SESSION["olds"] = NULL;
		$c = new SalesReps();
		$numrows = $c->getSalesRepsRows();
		$d = new Datex();
		if (!empty($cust_code) && $oldrec = $c->getTextFields($dir, $cust_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
		}
		include("salesreps_add.php");

	} else if ($ty == "e") {
		$_SESSION["olds"] = NULL;
		$c = new SalesReps();
		$numrows = $c->getSalesRepsRows();
		$d = new Datex();
		if (empty($slsrep_code)) $oldrec = $c->getFirstSalesReps();
		else $oldrec = $c->getTextFields($dir, $slsrep_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
			if (!empty($slsrep_code)) $cnt = 1;
		}
		include("salesreps_edit.php");
	} else if ($ty == "v") {
		$c = new SalesReps();
		$numrows = $c->getSalesRepsRows();
		$d = new Datex();
		if (empty($slsrep_code)) $oldrec = $c->getFirstSalesReps();
		else $oldrec = $c->getTextFields($dir, $slsrep_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($slsrep_code)) $cnt = 1;
		}
		include("salesreps_view.php");
	} else  {
		include("salesreps_list.php");
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