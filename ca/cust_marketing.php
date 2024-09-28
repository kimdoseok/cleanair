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
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

	$weekbox = array(0=>array("value"=>"mon", "name"=>"Monday"),
					1=>array("value"=>"tue", "name"=>"Tuesday"),
					2=>array("value"=>"wed", "name"=>"Wednesday"),
					3=>array("value"=>"thu", "name"=>"Thursday"),
					4=>array("value"=>"fri", "name"=>"Friday"),
					5=>array("value"=>"sat", "name"=>"Saturday"),
					6=>array("value"=>"sun", "name"=>"Sunday")
			  );

	$tm = new Terms();
	$termbox = $tm->getTermsBox();
?>
<html>
<head>
<title>Customer To Be Called</title>
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

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
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
	    <?php include ("left_crm.php") ?> 
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

	$c = new Custs();

	$numrows = $c->getCustsRows();

	$d = new Datex();

	$t = new TaxRates();

	$t_arr = $t->getTaxRatesList();

	$t_num = $t->getTaxRatesRows();

	$taxbox = array();

	for ($i=0;$i<$t_num;$i++) {
		$tx = array("value"=>"", "name"=>"");
		$tx["value"] = $t_arr[$i]["taxrate_code"];
		$tx["Name"] = $t_arr[$i]["taxrate_desc"];
		array_push($taxbox, $tx);

	}
	$sr = new SalesReps();
	$sr_arr = $sr->getSalesRepsList();
	$sr_num = $sr->getSalesRepsRows();
	$slsrepbox = array();
	$tmp = array("value"=>"", "name"=>"");
	$tmp["value"] = "";
	$tmp["Name"] = "House Account";
	array_push($slsrepbox, $tmp);
	for ($i=0;$i<$sr_num;$i++) {
		$tmp = array("value"=>"", "name"=>"");
		$tmp["value"] = $sr_arr[$i]["slsrep_code"];
		$tmp["Name"] = $sr_arr[$i]["slsrep_name"];
		array_push($slsrepbox, $tmp);

	}
	$ua = new UserAuths();


	if ($ty == "v") {
		if (empty($cust_code)) $oldrec = $c->getFirstCusts();
		else $oldrec = $c->getTextFields($dir, $cust_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($cust_code)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cust_market_view.php");
		else include("permission.php");

	} else {
		$cm = new Cmemo();
		$cr = new Receipt();
		$pt = new Picks();
		
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cust_market_list.php");
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
