<?php
	include_once("class/class.formutils.php");
	include_once("class/class.customers.php");
	
	include_once("class/class.custships.php");
	include_once("class/class.taxrates.php");
	
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.receipt.php");
	include_once("class/class.picks.php");
	include_once("class/class.cmemo.php");
	include_once("class/class.terms.php");
	include_once("class/class.userauths.php");
	include_once("class/class.salesreps.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.default.php");
	include_once("class/map.lang.php");

	$vars = array("cust_code","cust_sls_rep","cust_tax_code");
	foreach ($vars as $v) {
		$$v = "";
	} 


	include_once("class/register_globals.php");
//------------------------------------------------------------------------

	$f = new FormUtil();
//-----------------------------------------------------------------------
	$comp = $default["comp_code"];
	if (!array_key_exists($comp, $_SESSION) && !is_array($_SESSION[$comp])) $_SESSION[$comp]=array();

	$tm = new Terms();
	$termbox = $tm->getTermsBox();

?>
<html>
<head>
<title>Customer</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setPaste(name) {
		var f = eval('document.forms[0].'+name);
		f.value = '<?= $_SESSION["last_value"] ?>';
	}

	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
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
   <td>
	<table width="800" border="0" cellspacing="0" cellpadding="0">
	 <tr valign="top">
	  <td width="110" bgcolor="#CCCCFF">
	    <?php include ("left_sales.php") ?> 
	  </td>
	  <td width="681" align="center">
	   <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
         <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
		</tr>
		<tr>
		 <td width="10">&nbsp;</td>
		 <td valign="top">
<?php
	$strkeys = array("ty", "dir","ft","cn","rv","page","objname");
	foreach ($strkeys as $key) {
		if (!isset($$key)) {
			$$key = "";
		}	
	}

	$c = new Custs();
	$numrows = $c->getCustsRows();
	$d = new Datex();
	$t = new TaxRates();
	$t_num = $t->getTaxRatesRows();
	$t_arr = $t->getTaxRatesList();
	$taxbox = array();

	for ($i=0;$i<$t_num;$i++) {
		$tx = array("value"=>"", "name"=>"");
		$tx["value"] = $t_arr[$i]["taxrate_code"];
		$tx["name"] = $t_arr[$i]["taxrate_desc"];
		array_push($taxbox, $tx);

	}
	$sr = new SalesReps();
	$sr_arr = $sr->getSalesRepsList();
	$sr_num = $sr->getSalesRepsRows();
	$slsrepbox = array();
	$tmp = array("value"=>"", "name"=>"");
	$tmp["value"] = "";
	$tmp["name"] = "House Account";
	array_push($slsrepbox, $tmp);
	//print_r($sr_arr);
	for ($i=0;$i<count($sr_arr);$i++) {
		if ($sr_arr[$i]["slsrep_code"]=="") continue;
		$tmp = array("value"=>"", "name"=>"");
		$tmp["value"] = $sr_arr[$i]["slsrep_code"];
		$tmp["name"] = $sr_arr[$i]["slsrep_name"];
		/*
		if (!array_key_exists("slsrep_code", $sr_arr[$i])) {
			$tmp["value"] = $sr_arr[$i]["slsrep_code"];
		}
		if (!array_key_exists("slsrep_name", $sr_arr[$i])) {
			$tmp["name"] = $sr_arr[$i]["slsrep_name"];
		}
		*/
		array_push($slsrepbox, $tmp);		
	}
	//print_r($slsrepbox);
	$ua = new UserAuths();

	if ($ty == "a") {
		if (array_key_exists("olds", $_SESSION[$comp]) && $_SESSION[$comp]["olds"]) $_SESSION[$comp]["olds"]=NULL;
		if ($olds = $c->getTextFields($dir, $cust_code)) {
			foreach ($olds as $k => $v) $$k = $v;
			$_SESSION[$comp]["olds"]=$olds;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("customers_add.php");
		else include("permission.php");

	} else if ($ty == "e") {
		if ($_SESSION[$comp]["olds"]) $_SESSION[$comp]["olds"]=NULL;
		if (empty($cust_code)) $olds = $c->getFirstCusts();
		else $olds = $c->getTextFields($dir, $cust_code);
		if ($olds) {
			foreach ($olds as $k => $v) $$k = $v;
			$_SESSION[$comp]["olds"]=$olds;
			if (!empty($cust_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("customers_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		if (empty($cust_code)) $oldrec = $c->getFirstCusts();
		else $oldrec = $c->getTextFields($dir, $cust_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($cust_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("customers_view.php");
		else include("permission.php");

	} else if ($ty == "b") {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_label");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("customers_label_opt.php");
		else include("permission.php");

	} else {
		$cm = new Cmemo();
		$cr = new Receipt();
		$pt = new Picks();
			
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("customers_list.php");
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
